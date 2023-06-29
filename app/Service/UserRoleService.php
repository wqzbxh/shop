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

    public function getList($request)
    {
        $resource = CommonService::getList(UserRole::class,$request);
        $data['data'] =UserRoleResource::collection($resource['resource']);
        $data['total'] = $resource['count'];
        return  MsgService::msg(200, $data);

    }


}
