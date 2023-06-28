<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:55
 */

namespace App\Service;

class MsgService
{
    /**
     *
     */
    const Messages = [
        //        1、开头系统错误
        0=> 'Expired token',
        200 => 'Success!',
//        通用类型错误提示
        20001 => 'Activation link is invalid',
        20002 => 'Register user operation failed',
        20003 => 'Activation link failed, please resend the email',
        20004 => 'Without this user email, this activation link is invalid',
        20005 => 'Activation link has expired',
        20006 => 'This user does not exist',
        20007 => 'This user has not been activated, please log in to the email to activate the link',
        20008 => 'Wrong password, please re-enter',
        20009 => 'Email parameter does not exist',
        20010 => '用户ID异常',
        20011 => '请求不合法，请检查登录信息',
        20012 => '登录信息失效，需要新登录',
        20013 => '登录信息失效，需要新登录',
        20014 => '已退出',
        20015 => '修改失败',
        20016 => '数据库中没有此条信息，可能已被其他用户删除，请刷新',
//        商品类型错误提示
        31000 => '商品的类型ID必须存在',

    ];

    /**
     * @param $code
     * @return array
     */

    public static function msg($code,$data,$msg = '')
    {
        if(empty($msg)) $msg = self::Messages[$code];
        return array('code' => $code,'msg' => $msg, 'data' => $data);
    }
}
