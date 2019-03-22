<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class IllegalMiddleware
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

//        echo __METHOD__;
//        var_dump($_SERVER);die;
        $request_uri=$_SERVER['REQUEST_URI'];
//        echo $request_uri;echo '</br>';

//        echo md5($request_uri);echo '</br>';
        $uri_hash=substr(md5($request_uri),10,10);

        $ip = $_SERVER['REMOTE_ADDR'];      //客户端IP

        $redis_key= 'str:'.$uri_hash . ':' .$ip;

        $num = Redis::incr($redis_key);     //+1
        Redis::expire($redis_key,60);       //60秒

//        echo 'count: '.$num;echo '</br>';
        if($num>20){         //非法请求
            //拒绝服务十分钟
            $response = [
                'error'=>40003,
                'msg'=>'Invalid Request!!!'
            ];
            Redis::sAdd('ip',$ip);
            Redis::expire($redis_key,600);      //10s
            return json_encode($response);
        }
        return $next($request);
    }

}