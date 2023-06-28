<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendRegisterMail($email,$link)
    {
//      演示是 同步处理，在Laravel中，send()方法在成功发送电子邮件后会返回null，
        Mail::to($email)->send(new RegisterMail([
            'email' =>$email,
            'link' =>$link,
        ]));
//        在实际中应 异步处理 将发送信息丢到 消息队列 直接返回
    }
}
