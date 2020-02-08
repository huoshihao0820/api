<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonModel extends Model
{
    public static function curl1($url){
//        $url="http://1905api.comcto.com/test/curl1?name=huoshiaho&sex=nan&age=24";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_exec($ch);
        curl_close($ch);
    }
    public static function curl2($url,$data){
//        $data=[
//            'name'=>'huoshihao',
//            'sex'=>2,
//            'age'=>24
//        ];
//            dd($url);

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response=curl_exec($ch);
        $error=curl_error($ch);
        dd($response);
        if ($error){
            var_dump($error);
            die;
        }
        curl_close($ch);
        var_dump($response);
    }
    public static function curl3($url,$data){
//        $url="http://1905api.comcto.com/test/curl3";
//        $data=[
//            'img'=>new \CURLFile('qq.png')
//        ];
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

        curl_exec($ch);
        curl_close($ch);

    }
    public static function curl4($url,$data){
//        $url="http://1905api.comcto.com/test/curl4";
//        $data=[
//            'name'=>'huoshihao',
//            'sex'=>2,
//            'age'=>24
//        ];
        $post_json=json_encode($data);
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_json);
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
            'Content-Type:text/plain',
            'token:'.'12345678901234567890'
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
    public static function curlGet($url,$header){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,1);
        $response=curl_exec($ch);
        $error=curl_error($ch);
        if ($error){
            var_dump($error);
            die;
        }
        curl_close($ch);
        return json_decode($response,true);
    }
}
