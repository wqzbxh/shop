<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Mail\RegisterMail;
use App\Models\menuModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MenuService
{

    /**
     * 获取角色的菜单信息
     * @param $role_id
     * $role_id为false时返回全部信息
     * @param $type
     *
     * @return \Illuminate\Support\Collection
     */
    public static function  getMenu($role_id,$type='array_level')
    {
        $menu_arr = [];
        if($role_id !== false)
        {
            //获取角色的menu_id集合
            $menu_id_arr = DB::table('admin_role')->select('menu_id')->where('id', $role_id)->first();
            if(empty($menu_id_arr->menu_id))
                return false;
            //将字符串集合变为数组
            $menuids = explode(',',$menu_id_arr->menu_id);
            //获取所有的菜单集合
            $menu_arr = DB::table('admin_menu')->whereIn('id',$menuids)->orderBy('menu_order','asc')->get();
        }else{
            $menu_arr = DB::table('admin_menu')->orderBy('menu_order','asc')->get();
        }
        //层级关系返回数组， 默认array_level类型
        $menus = self::menuObjToArrayWithLevel($menu_arr);

        return $menus;
    }

    /**
     * @param object $menu
     * @param $pid
     * @return array
     * 两级循环
     */
    public static function menuObjToArrayWithLevel(object $menu, $pid = 0, $level = 0): array
    {
        // 对象是空返回空数组
        if (empty($menu)) {
            return [];
        }
        // 初始化返回数组
        $returnMenuArray = [];
        //处理分层
        foreach ($menu as $item) {
            if ($item->pid === $pid) {
                $subMenu = json_decode(json_encode($item), true);
                $subMenu['level'] = $level;
                $subMenu['children'] = self::menuObjToArrayWithLevel($menu, $item->id, $level + 1);
                $returnMenuArray[] = $subMenu;
            }
        }
        return $returnMenuArray;
    }



}
