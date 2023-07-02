<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 0:33
 */

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserRoleResource;
use App\Models\UserModel;
use App\Models\UserRole;

class UserRoleService
{


    protected $userRoleDB;

    public function __construct()
    {
        $this->userRoleDB = app(UserRole::class);
    }
    public  function  create($request)
    {
//        获取参数,存储信息
        if(count($request->get('menu_id')) > 0 ){
            $this->userRoleDB->menu_id = implode(',',$request->get('menu_id'));
        }
        $this->userRoleDB->desc = $request->get('desc');
        $this->userRoleDB->role_name = $request->get('role_name');
        $result = $this->userRoleDB->save();
        //添加记录失败
        if($result == false) return  MsgService::msg(20002, []);
        logsService::Logs('cj','创建了用户角色'. $request->get('role_name').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, []);

    }



    /**
     * @return void
     */
    public function update($request)
    {
        //构建修改条件
        $updateCondition = [];
        if (empty($request->get('id')))
            return MsgService::msg(31000, []);
        $where = ['id' => $request->get('id')];

        $originValusArr = CommonService::getOne(UserRole::class,$where);
        $request->get('role_name') ? $updateCondition['role_name']  = $request->get('role_name') : [];
        $request->get('desc') ? $updateCondition['desc']  = $request->get('desc') : [];
        $request->get('menu_id') ?  $updateCondition['menu_id']  = implode(',',$request->get('menu_id')) : '';
        $result = UserRole::where($where)->update($updateCondition);
        if(!$result)
            return MsgService::msg(20015, []);
        ModificationlogsService::ModificationLog(UserRole::class,$request->get('id'),$updateCondition,$originValusArr);
        logsService::Logs('gx','修改角色ID为'.$originValusArr['id'].'的记录，此时角色名称'.$request->get('role_name'),$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return MsgService::msg(200, []);
    }
    public function getList($request)
    {
        switch ($request->get('type')){
            case 'select':
                $resource = CommonService::selectList(UserRole::class);
                $data['data'] =UserRoleResource::collection($resource['resource'],$request);
                break;
            default:
                $resource = CommonService::getList(UserRole::class,$request);
                $data['data'] =UserRoleResource::collection($resource['resource']);
                $data['total'] = $resource['count'];
        }

        return  MsgService::msg(200, $data);

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
        $result = UserRole::where($where)->update($updateCondition);
        if(!$result){
            return MsgService::msg(20015, []);
        }
        $originValusArr = CommonService::getOne(UserRole::class,$where);
        ModificationlogsService::ModificationLog(UserRole::class,$request->get('id'),$updateCondition,$originValusArr);
        logsService::Logs('sc','对角色ID为'.$request->get('id').'的记录进行删除',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200,$originValusArr);
    }

}
