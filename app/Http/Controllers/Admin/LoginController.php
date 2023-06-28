<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Service\LoginRegisterService;
use App\Service\MenuService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * 注册用户
     * @param UserRequest $request
     * @return array
     */
    public function register(UserRequest $request)
    {
        return (new LoginRegisterService)->register($request);
    }

    /**
     *  激活注册用户
     * @param Request $request
     * @return array
     */
     public function registerActivation(Request $request)
     {
         return (new LoginRegisterService)->registerActivation($request);
     }

    /**
     * 登录
     * @param UserRequest $request
     * @return array
     */
     public function login(UserRequest $request)
     {
         return (new LoginRegisterService)->loginAction($request);
     }

    /**
     * 发邮件
     * @param Request $request
     * @return array
     */
     public function sendEmail(Request $request)
     {
         return (new LoginRegisterService)->sendEmailAction($request);
     }


    /**
     * 退出
     * @param Request $request
     * @return array
     */
    public function logout(Request $request)
    {
        return (new LoginRegisterService)->logout($request);
    }
}
