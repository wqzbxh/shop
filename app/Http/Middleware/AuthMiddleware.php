<?php

namespace App\Http\Middleware;

use App\Service\MsgService;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuthMiddleware
{
    public static $userInfo = [];

    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');

        // 没有token，直接退出
        if (!$authorization) {
            return response( MsgService::msg(20011, []));
        }

        // 获取当前时间
        $nowtime = time();
        // 得到链接中的信息
        try {
            $information = JWT::decode($authorization, new Key(config('app.jwt_secret'), 'HS256'));
            if ($nowtime - $information->exp < 600) {
                $information->exp = time() + 3600;
                $information = json_decode(json_encode($information), true);
                $jwt = JWT::encode($information, config('app.jwt_secret'), 'HS256');// 设置请求头// 在 handle 方法中

                $response = new \Illuminate\Http\Response();
                $response->header('Authorization',  $jwt);
            }
        } catch (\Throwable $e) {
            return response(MsgService::msg($e->getCode(), [], $e->getMessage()));
        }


        // 设置全局变量
        self::$userInfo = $information;

        return $next($request);
    }
}
