<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Http\Middleware\AuthMiddleware;
use App\Models\LogsModel;
use App\Models\ModificationlogsModel;

class logsService
{

    const ACTIONTYPE = [
        'dl' => '登录',
        'zc' => '注册',
        'tc' => '退出',
        'cj' => '创建',
        'gx' => '更新',
        'sc' => '删除',
        'cx' => '查询',
        'dc' => '导出',
        'dr' => '导入',
        'sq' => '授权',
        'fb' => '发布',
        'ty' => '通用',
    ];

    /**
     * @param $action 日志类型
     * @param $desc 描述
     * @param $request_url 请求地址
     * @param $request_method 方法
     * @param $request_payload 请求参数
     * @param $response_code 返回状态码
     * @param $response_payload 返回信息
     * @param $user_email 用户邮箱
     * @param $user_id 用户id
     * @return void
     */
    public static function Logs($action = 'ty',$desc,$request_url,$request_method,$request_payload,$response_code,$response_payload,$user_email = '',$user_id = '')
    {
        $item['username'] = $user_email ? $user_email :  AuthMiddleware::$userInfo['email'];
        $item['user_id'] =  $user_id ? $user_id : AuthMiddleware::$userInfo['user_id'];
        $item['ip_address'] =  $_SERVER['REMOTE_ADDR'];
        $item['device_info'] =  $_SERVER['HTTP_USER_AGENT'];
        $item['request_url'] =$request_url;
        $item['action'] = self::ACTIONTYPE[$action];
        $item['request_method'] = $request_method;
        $item['description'] = $desc;
        $item['request_payload'] = $request_payload;
        $item['response_code'] = $response_code;
        $item['response_payload'] = $response_payload;
        LogsModel::insert($item);
    }
}

