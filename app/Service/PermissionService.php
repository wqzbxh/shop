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

class PermissionService
{

    public function getMenu($role_id)
    {
        $users = DB::table('menu')
            ->whereIn('id', function ($query) use ($role_id) {
                $query->select('menu_id')
                    ->from('admin_role')
                    ->where('id', $role_id);
            })
            ->get();

        return $users;
    }
}
