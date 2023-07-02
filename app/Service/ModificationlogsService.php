<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Http\Middleware\AuthMiddleware;
use App\Mail\RegisterMail;
use App\Models\ModificationlogsModel;
use Illuminate\Support\Facades\Mail;

class ModificationlogsService
{

    const ORMTOTABLE =[
        'App\Models\GoodsCategory' => 'goods_category',
        'App\Models\UserRole' => 'admin_role',
        'App\Models\UserModel' => 'admin_user',
    ];
    /**
     * @param $orm 数据库名字
     * @param $row_id 行记录
     * @param array $newValue 新值集合
     * @param array $oldValue 老值集合
     * @param $changed_by 修改人员
     * @return true
     */
    public static function ModificationLog($orm,$row_id,array $newValue,array $oldValue)
    {
        $table = self::ORMTOTABLE[$orm];
        $changed_by  = AuthMiddleware::$userInfo['email'];
        $changed_by_user_id =  AuthMiddleware::$userInfo['user_id'];
        //修改批号
        $sequence_number = $changed_by_user_id + time();
        // 保留第一个数组中与第二个数组不同的元素
        $newValue = array_diff_assoc($newValue, $oldValue);
        // 从两个数组中删除相同的键
        $oldValue = array_intersect_key($oldValue, $newValue);
        $saveData = [];
        //循環要記錄的字段
        if(count($newValue)>0)
            foreach ($newValue as $key => $value){
                $item = [];
                $item['column_name'] = $key;
                $item['row_id'] = $row_id;
                $item['old_value'] = $oldValue[$key];
                $item['new_value'] = $value;
                $item['changed_by_user_id'] = $changed_by_user_id;
                $item['sequence_number'] = $sequence_number;
                $item['changed_by'] = $changed_by;
                $item['table_name'] = $table;
                array_push($saveData,$item);
            }
        ModificationlogsModel::insert($saveData);
    }
}
