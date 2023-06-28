<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/1
 * Time: 21:06
 */

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser($data)
    {
        // 创建新用户的逻辑
        $user = User::create($data);

        // 可以在这里执行其他操作，如发送欢迎邮件、分配角色等

        return $user;
    }

    public function getUser($id)
    {
        // 获取用户的逻辑
        $user = User::findOrFail($id);

        return $user;
    }

    public function updateUser($id, $data)
    {
        // 更新用户的逻辑
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function deleteUser($id)
    {
        // 删除用户的逻辑
        $user = User::findOrFail($id);
        $user->delete();

        return true;
    }

    public function getList($request)
    {
        $resource = CommonService::getList(UserModel::class,$request);
        $data['data'] =UserResource::collection($resource['resource']);
        $data['total'] = $resource['count'];
        return  MsgService::msg(200, $data);

    }
}
