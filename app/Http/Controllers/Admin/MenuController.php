<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\MenuService;
use App\Service\MsgService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //
    //获取菜单信息
    public function getMenu()
    {
        $info =  MenuService::getMenu(false);
        return  MsgService::msg(200, $info);
    }
}
