<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/1
 * Time: 21:06
 */

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserModel;
use App\Utils\Tools;
use Illuminate\Support\Facades\DB;

class UserService
{

    protected $userDB;

    public function __construct()
    {
        $this->userDB = app(UserModel::class);
    }

    public  function  createUser($request)
    {
        $this->userDB->name = $request->get('name');
        $this->userDB->idcard = $request->get('idcard');
        $this->userDB->email = $request->get('email');
        $this->userDB->status = $request->get('status');
        $this->userDB->phone = $request->get('phone');
        $this->userDB->realname = $request->get('realname');
        $this->userDB->role_id = $request->get('role_id');
        $newPasswordWithSalt = Tools::createPassword($request->get('password'));
        $this->userDB->password =$newPasswordWithSalt['password'];
        $this->userDB->salt =$newPasswordWithSalt['salt'];
        $result = $this->userDB->save();
        //添加记录失败
        if($result == false) return  MsgService::msg(20002, []);
        logsService::Logs('cj','创建了用户'. $request->get('email').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, []);

    }

    /**
     * @return void
     */
    public function updateUser($request)
    {
        //构建修改条件
        $updateCondition = [];
        if (empty($request->get('id')))
            return MsgService::msg(31000, []);
        $where = ['id' => $request->get('id')];

        $originValusArr = CommonService::getOne(UserModel::class,$where);
        $request->get('name') ? $updateCondition['name']  = $request->get('name') : '';
        $request->get('email') ? $updateCondition['email']  = $request->get('email') : '';
        $request->get('status') ? $updateCondition['status']  = $request->get('status') : '';
        $request->get('phone') ? $updateCondition['phone']  = $request->get('phone') : '';
        $request->get('realname') ? $updateCondition['realname']  = $request->get('realname') : '';
        $request->get('role_id') ? $updateCondition['role_id']  = $request->get('role_id') : '';
        $request->get('idcard') ? $updateCondition['idcard']  = $request->get('idcard') : '';

        if($request->get('password')){
            $newPasswordWithSalt = Tools::createPassword($request->get('password'));
            $updateCondition['password'] =$newPasswordWithSalt['password'];
            $updateCondition['salt'] =$newPasswordWithSalt['salt'];
        }

        $result = UserModel::where($where)->update($updateCondition);
        if(!$result)
            return MsgService::msg(20015, []);
        ModificationlogsService::ModificationLog(UserModel::class,$request->get('id'),$updateCondition,$originValusArr);
        logsService::Logs('gx','修改用户信息ID为'.$originValusArr['id'].'的记录，此时名称为'.$request->get('name'),$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return MsgService::msg(200, []);
    }


    /**
     * @param $request
     * @return array
     */

    public function delete($request)
    {
        if (empty($request->get('id')))
            return MsgService::msg(31000, []);
        $where = ['id' => $request->get('id')];
        $updateCondition['is_del'] = 1;
        $result = UserModel::where($where)->update($updateCondition);
        if(!$result){
            return MsgService::msg(20015, []);
        }
        $originValusArr = CommonService::getOne(UserModel::class,$where);
        logsService::Logs('sc','对用户ID为'.$request->get('id').'的记录进行删除',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200,$originValusArr);
    }
    public function getList($request)
    {
        $type =  $request->get('type') ?? false;
//        当没有指定type值得时候以分页形式返回
        if($type == 'select'){
//            当为select为下拉款返回全部
            $data = [];
            $selectRow = UserModel::where('is_del','=','0')->select('id','name','email')->get();
            if(!empty($selectRow)){
                $data = $selectRow->toArray();
            }
            logsService::Logs('cx','请求用户下拉框-类型为'. $request->get('type').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
            return  MsgService::msg(200, $data);
        }
        $resource = CommonService::getList(UserModel::class,$request);
        $data['data'] =UserResource::collection($resource['resource']);
        $data['total'] = $resource['count'];
        logsService::Logs('cx','请求分页用户的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, $data);
    }
}
