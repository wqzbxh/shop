<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //
    public function getUser(Request $request)
    {

        return (new UserService())->getList($request);
    }
}
