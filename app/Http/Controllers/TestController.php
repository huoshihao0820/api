<?php

namespace App\Http\Controllers;

use App\models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function alipay(){
        $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';  //支付网关
        // 公共请求参数
        $appid = '2016100100643043';
        $method = 'alipay.trade.page.pay';
        $charset = 'utf-8';
        $signtype = 'RSA2';
        $sign = '';
        $timestamp = date('Y-m-d H:i:s');
        $version = '1.0';
        $return_url = 'http://1905api.comcto.com/test/alipay/return';       // 支付宝同步通知
        $notify_url = 'http://1905api.comcto.com/test/alipay/notify';        // 支付宝异步通知地址
        $biz_content = '';
        // 请求参数
        $out_trade_no = time() . rand(1111,9999);       //商户订单号
        $product_code = 'FAST_INSTANT_TRADE_PAY';
        $total_amount = 0.01;
        $subject = '测试订单' . $out_trade_no;



        $request_param = [
            'out_trade_no'  => $out_trade_no,
            'product_code'  => $product_code,
            'total_amount'  => $total_amount,
            'subject'       => $subject
        ];
        $param = [
            'app_id'        => $appid,
            'method'        => $method,
            'charset'       => $charset,
            'sign_type'     => $signtype,
            'timestamp'     => $timestamp,
            'version'       => $version,
            'notify_url'    => $notify_url,
            'return_url'    => $return_url,
            'biz_content'   => json_encode($request_param)
        ];
        //echo '<pre>';print_r($param);echo '</pre>';
        // 字典序排序
        ksort($param);
        //echo '<pre>';print_r($param);echo '</pre>';
        // 2 拼接 key1=value1&key2=value2...
        $str = "";
        foreach($param as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }
        //echo 'str: '.$str;echo '</br>';
        $str = rtrim($str,'&');
        //echo 'str: '.$str;echo '</br>';echo '<hr>';
        // 3 计算签名   https://docs.open.alipay.com/291/106118
        $key = storage_path('keys/app_priv');
        $priKey = file_get_contents($key);
        $res = openssl_get_privatekey($priKey);
        //var_dump($res);echo '</br>';
//        dd(1);
        openssl_sign($str, $sign, $res, OPENSSL_ALGO_SHA256);       //计算签名
//        dd(2);
        $sign = base64_encode($sign);
        $param['sign'] = $sign;
        // 4 urlencode
        $param_str = '?';
        foreach($param as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $param_str = rtrim($param_str,'&');
        $url = $ali_gateway . $param_str;
//        dd(23);
        //发送GET请求
//        echo $url;die;
        header("Location:".$url);
    }

    public function register(Request $request){
        $data=\request()->all();
//        var_dump($data);
        $password=$request->password;
        $password1=$request->password1;
        if ($password !=$password1){
            die("密码不一致");
        }
        unset($data['password1']);
        $data['password']=password_hash($password,PASSWORD_BCRYPT);

        $id=UserModel::insertGetId($data);
        var_dump($id);
    }
    public function login(Request $request){
        $name=$request->name;
        $password=$request->password;
//        dd($password);
        $res=UserModel::where('name',$name)->first();
        if ($res){
            if (password_verify($password,$res['password'])){
                echo "密码dui";
                $token=Str::random(32);
                $respon=[
                    'error'=>0,
                    'msg'=>'登陆成功',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
            }else{
                $respon=[
                    'error'=>20001,
                    'msg'=>'密码错误',
                ];
            }
        }else{
            $respon=[
                'error'=>20002,
                'msg'=>'用户名错误',
            ];
        }
        return $respon;
    }
    public function userList(){
        $list=UserModel::all();
        print_r($list->toArray());
    }
}
