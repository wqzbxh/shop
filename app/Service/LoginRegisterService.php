<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2022/6/3
 * Time: 13:36
 */
namespace App\Service;
use App\Models\RegistrationLinksModel;
use App\Models\UserModel;
use App\Utils\Tools;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class LoginRegisterService
{
    /**
     * @param $request
     * @return array
     * 初始化用户信息，建立一个未激活的用户
     */
    public function register($request)
    {
        //获取所有已经处理过的数据
        $dataRecord = $request->all();
        //获取用户的原始密码，因为要加盐，所以单独摘出
        $password = $request->get('password');
        //获取邮箱
        $email = $request->get('email');
        //生成密码
        $newPasswordWithSalt = Tools::createPassword($password);
        $dataRecord['salt'] = $newPasswordWithSalt['salt'];
        $dataRecord['password'] = $newPasswordWithSalt['password'];
//      创建
        $result = (new UserModel)->create($dataRecord);
//        添加记录失败
        if($result == false){
            return  MsgService::msg(20002, []);
        }
        $link = $this->generateRegistrationLink($email);
//        生产链接异常，直接返回
        if($link === false){
            return  MsgService::msg(20003, []);
        }
//       发送邮件,同步处理，在Laravel中，send()方法在成功发送电子邮件后会返回null，
        (new EmailService())->sendRegisterMail($email,$link);
//        返回信息
        logsService::Logs('zc',$request->get('email').'注册成功',$request->url(),$request->method(),serialize($request->getContent()),20008, serialize(''),$request->get('email'),1);
        return MsgService::msg(200, [],'Please log in to your email to activate your account');

    }

    /**
     * @param $email
     * @return false|string
     * 生成激活链接
     */
    public function generateRegistrationLink($email)
    {
//        生效时间
        $time = time();
//       JWT数据包
        $payload = [
            'iss' => 'http://shop.com',
            'aud' => 'http://shop.com',
            'iat' =>  $time, //发布时间
            'nbf' =>  $time, //生效时间
            'email'=>$email,
            'exp' => $time + 3600, // 当前时间 + 1 小时
        ];
//       生成链接码
        $jwt = JWT::encode($payload,  config('app.jwt_secret'), 'HS256');

//      创建一个链接记录
        $result =  (new RegistrationLinksModel())->create($email,$jwt);
//        激活链接码异常
        if($result === false){
            return false;
        }
//        配置激活链接
        $link = config('app.host').'register_by_eamil?register_token='.$jwt;

        return $link;
    }

    /**
     * @param $requestData
     * @return array
     * 激活链接操作
     */
    public function registerActivation($requestData)
    {
        $register_token  = $requestData->get('register_token');
        //判断参数有没有token
        if($register_token === null){
            return MsgService::msg(20001, []);
        }
        //得到链接中的信息
        try {
            $userInfo = JWT::decode($register_token, new Key(config('app.jwt_secret'), 'HS256'));
        }catch (\Throwable $e){
            return MsgService::msg($e->getCode(), [],$e->getMessage());
        }

        $userRecord = UserModel::where('email', $userInfo->email)->first();
        $registerLinkRecord = RegistrationLinksModel::where('email', $userInfo->email)->where('is_used', 0)->first();
        //判断这个邮箱是不是在两个表并存的状态
        if($userRecord === null || $registerLinkRecord === null){
            return MsgService::msg(20004, [],);
        }
        //判断是不过期了 和有没有使用，两个有一个false 都不能用
        if($registerLinkRecord->expiration_time < time() || $registerLinkRecord->is_used !== 0){
            return MsgService::msg(20005, [],);
        }

//        修改用户的状态，达到成功注册的状态
        try {
            $userRecord->registration_status = 'finish';
            $userRecord->save();
            $registerLinkRecord->is_used = 1;
            $registerLinkRecord->save();
        }catch (\Throwable $e){
            return MsgService::msg($e->getCode(), [],$e->getMessage());
        }

        return MsgService::msg(200, []);
    }

    /**
     * 登录操作
     * @param $request
     * @return array
     *
     */
    public function loginAction($request)
    {
        $userRecord = UserModel::where('email', $request->get('email'))->first();

//      此用户不存在
        if($userRecord === null){
            return MsgService::msg(20006, []);
        }

//      此用户尚未被激活，请登录邮箱激活链接
        if($userRecord->registration_status === 'pending'){
            return MsgService::msg(20007, []);
        }

//      获取此用户的盐值
        $salt = $userRecord->salt;
//        生成匹配密码
        $combinedString = $salt . $request->get('password');
        $commitPassword =  hash('sha256', $combinedString);
//        密码错误
        if($commitPassword !== $userRecord->password){
            logsService::Logs('dl','用户登陆时失败，密码不匹配',$request->url(),$request->method(),serialize($request->getContent()),20008, serialize($request->all()),$request->get('email'),$userRecord->id);
            return MsgService::msg(20008, []);

        }

//      获取菜单信息信息
        $payload['menuInfo'] = MenuService::getMenu($userRecord->role_id);

//        生效时间
        $time = time();

        $payload = [
            'iss' => 'http://shop.com',
            'aud' => 'http://shop.com',
            'iat' =>  $time, //发布时间
            'nbf' =>  $time, //生效时间
            'email'=>$userRecord->email,
            'name'=>$userRecord->name,
            'user_id'=>$userRecord->id,
            'exp' => $time + 3600, // 当前时间 + 1 小时
            'menuInfo'=> MenuService::getMenu($userRecord->role_id),
        ];
//       返回jwt信息
        $jwt = JWT::encode($payload,  config('app.jwt_secret'), 'HS256');
        $payload['token'] = $jwt;
        logsService::Logs('dl','用户登陆成功',$request->url(),$request->method(),serialize($request->getContent()),20008, serialize($payload),$request->get('email'),$userRecord->id);
        return MsgService::msg(200,$payload);
    }

    /**
     * 发送eamil
     * @param $request
     * @return array
     */
    public function sendEmailAction($request)
    {
        $email = $request->get('email');
        if($email === null){
            //邮箱参数不存在
            return MsgService::msg(20009, []);
        }

    //  修改用户的状态，把之前是否被使用全部变成失效
        try {
            RegistrationLinksModel::where('email', $email)->update(['is_used' => 3]);
            }catch (\Throwable $e){
                return MsgService::msg($e->getCode(), [],$e->getMessage());
            }

        $link = $this->generateRegistrationLink($email);
//        生产链接异常，直接返回
        if($link === false){
            return  MsgService::msg(20003, []);
        }
//       发送邮件,同步处理，在Laravel中，send()方法在成功发送电子邮件后会返回null，
        (new EmailService())->sendRegisterMail($email,$link);

        return MsgService::msg(200, []);

    }

    /**
     * 登出操作
     * @param $request
     * @return array
     */
    public function logout($request)
    {
        $authorization = $request->header('Authorization');
//      没有token，直接退出，
        if (!$authorization)
            return MsgService::msg(200, []);

        //得到链接中的信息
        try {
            $userInfo = JWT::decode($authorization, new Key(config('app.jwt_secret'), 'HS256'),true);
        }catch (\Throwable $e){
            return MsgService::msg(20014, [],$e->getMessage());
        }

        logsService::Logs('tc','用户退出',$request->url(),$request->method(),$request->getContent(),200, '',$userInfo->email,$userInfo->user_id);
//      设置Jwt过期，加入过期名单存入redis中， redis中的过期时间是jwt的时间
//      清空用户在服务端的信息
        return MsgService::msg(200, []);
    }

}
