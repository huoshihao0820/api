<?php

namespace App\Http\Controllers;

use App\models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use App\Models\CommonModel;
use GuzzleHttp\Client;

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
    public function showData()
    {

        // 收到 token
        $uid = $_SERVER['HTTP_UID'];
        $token = $_SERVER['HTTP_TOKEN'];

        // 请求passport鉴权
        $url = 'http://passport.com/test/showTime';         //鉴权接口
        $response = CommonModel::curl2($url,['uid'=>$uid,'token'=>$token]);

        $status = json_decode($response,true);

        //处理鉴权结果
        if($status['errno']==0)     //鉴权通过
        {
            $data = "sdlfkjsldfkjsdlf";
            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'data'  => $data
            ];
        }else{          //鉴权失败
            $response = [
                'errno' => 40003,
                'msg'   => '授权失败'
            ];
        }

        return $response;

    }

    public function sign2()
    {
        $key = "1905a";
        //签名数据
        $order_info = [
            "order_id"          => 'LN_' . mt_rand(100000,999999),
            "order_amount"      => mt_rand(100,999),
            "uid"               => 12345,
            "add_time"          => time(),
        ];
        $data_json = json_encode($order_info);
        //md5加密
        $sign = md5($data_json.$key);
        // post发送数据
        $client = new Client();
        $url = 'http://passport.com/test/check2';
        $response = $client->request("POST",$url,[
            "form_params"   => [
                "data"  => $data_json,
                "sign"  => $sign
            ]
        ]);

        //接收服务器端响应的数据
        $response_data = $response->getBody();
        echo $response_data;

    }
    public function crypt(){
	           $data=$_GET;
		          $data=json_encode($data);
	          echo "明文".$data;
		         echo "<br>";
	         $method="AES-128-CBC";
	        $key='1905abc';
	        $iv='abcdefghijkrmnop';
		       $ponse=openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
		       $ponse=urlencode(base64_encode($ponse));
		         echo "加密";
	                     echo "base64_encode".$ponse;
	                            echo "<hr>";
                                  $url="http://passport.com/test/encrypt?data=".$ponse;
                                //       echo $url;die();
                                 $ponse2=file_get_contents($url);
                                 echo $ponse2;
    }

}
