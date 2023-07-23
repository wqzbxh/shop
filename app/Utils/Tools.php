<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/7/1
 * Time: 15:04
 */

namespace App\Utils;

class Tools
{
    /**
     * @param int $length
     * @return string
     * 获取随机
     */
    public static function randString(int $length,string $type='alphanumeric'): string {

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@$%^&*(,.';
        if ($type=='alphanumeric')
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = substr(str_shuffle($characters), 0, $length);
        return $randomString;
    }

    /**
     * @param string $password
     * @return string
     */
    public static function createPassword(string $password){

        $salt = self::randString(16);
        // 将盐值和密码组合
        $combinedString = $salt . $password;
        // 盐值：
        $dataRecord['salt'] = $salt;
        // 生成 SHA-256 哈希值
        $dataRecord['password'] =  hash('sha256', $combinedString);

        return $dataRecord;
    }
}
