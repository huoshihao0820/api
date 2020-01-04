<?php

namespace App\Http\Middleware;

use Closure;

class fileter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $token=$_SERVER['HTTP_TOKEN'];
//        dd($token);
//        dd(1);
        if (isset($_SERVER['HTTP_TOKEN'])){
            $redis_key='str:count:res:'.$_SERVER['HTTP_TOKEN'].':url:'.$_SERVER['REQUEST_URI'];
            $count=\Redis::get($redis_key);
            if ($count>=5){

                \Redis::expire($redis_key,60);
                $respon=[
                    'error'=>20004,
                    'msg'=>"接口上限，稍后再试"
                ];
                die(json_encode($respon,JSON_UNESCAPED_UNICODE));
            }
//            dd(2);
            \Redis::incr($redis_key);
        }else{
//            dd(3);
            $respon=[
                'error'=>20003,
                'msg'=>"未授权"
            ];
            die(json_encode($respon,JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}
