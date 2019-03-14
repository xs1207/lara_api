<?php

namespace App\Http\Controllers\Test;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class IndexController extends Controller
{
    protected $hash_token = 'str:h:token:';
    public function login(Request $request)
    {
        $uid=$request->input('uid');
//        echo $uid;
        //生成token
        $token=substr(md5(time()+$uid+rand(1000,9999)),10,20);
//        echo $token;
        if(1){
            $key = $this->hash_token.$uid;
//            echo $key;die;
            Redis::hSet($key,'token',$token);
            Redis::expire($key,3600*24);

            $response=[
                'error'=>0,
                'token'=>$token
            ];
        }else{
            //TODO
        }
        return $response;
    }

    public function uCenter(Request $request)
    {
        $uid = $request->input('uid');
//        print_r($_SERVER);die;
        if(!empty($_SERVER['HTTP_TOKEN'])){
            $http_token = $_SERVER['HTTP_TOKEN'];
            $key = $this->hash_token.$uid;

            $token = Redis::hget($key,'token');

            if($token==$http_token){
                $response=[
                    'error'=>0,
                    'msg'=>'ok'
                ];
            }else{
                $response=[
                    'error'=>50001,
                    'msg'=>'invalid token'
                ];
            }
        }else{
            $response=[
                'error'=>50000,
                'msg'=>'not find token'
            ];
        }
        return $response;
    }
}