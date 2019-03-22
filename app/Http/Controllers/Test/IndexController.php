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
                    'msg'=>'is ok'
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

    /**
     * 防刷测试
     */

    public function order()
    {
        echo __METHOD__;
        /*
        if($num>20){         //非法请求
            //拒绝服务十分钟
            $response = [
                'error'=>40003,
                'msg'=>'Invalid Request!!!'
            ];
            Redis::sAdd('ip',$ip);
            Redis::expire($redis_key,600);      //10s
        }else{
            $response=[
                'error'=>0,
                'msg'=>'ok',
            ];
        }
        return $response;
        */
    }

    public function encryption()
    {
//        print_r(openssl_get_cipher_methods());

        $str = "Goods morning";
        $key = "asdas";
        $iv = mt_rand(1111111111111111,9999999999999999);       //初始化向量  固定字节  16位

        //加密
        $enc_str = openssl_encrypt($str,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
//        var_dump($enc_str);
//        echo "<hr>";

        //解密
        $dec_str = openssl_decrypt($enc_str,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        echo $dec_str;
    }

    public function lgn(Request $request)
    {
        $name=$request->input('name');
        $pwd=$request->input('pwd');
        $data=[
            'name'=>$name,
            'pwd'=>$pwd
        ];
        //print_r($data);exit;
//        $url = 'http://passport.shop.com/api/login';
        $url = 'http://dpassprot.tactshan.com/api/login';
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

        $rs = curl_exec($ch);
        curl_close($ch);
//        var_dump($rs);exit;
        $response = json_decode($rs,true);
        return $response;
//        print_r($response);die;
    }

}