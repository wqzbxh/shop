<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Service\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //
    public function getUser(Request $request)
    {

        return (new UserService())->getList($request);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function userAction(Request $request)
    {
        if($request->method()=== 'PUT')
            return  (new UserService())->updateUser($request);

        return (new UserService())->createUser($request);
    }


    /**
     * 删除操作
     * @param Request $request
     * @return array
     */
    public function userDelete(Request $request)
    {
        return  (new UserService())->delete($request);
    }
}

