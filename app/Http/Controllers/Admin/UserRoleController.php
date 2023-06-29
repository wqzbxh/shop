<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleRequest;
use App\Service\UserRoleService;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    //
    public function userRoleAction(UserRoleRequest $request)
    {
        if($request->method()=== 'PUT')
            return  (new UserRoleService())->update($request);

        return (new UserRoleService())->create($request);
    }

    /**获取商品类型
     * @param Request $request
     * @return array
     */
    public function userRoleList(Request $request)
    {
        return (new UserRoleService())->getList($request);
    }
}
