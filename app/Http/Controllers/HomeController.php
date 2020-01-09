<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\PkModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function pubkey(){
        return view('test/pubkey');
    }
    public function pubkey_do(){
        $data=$_POST;
        unset($data['_token']);
        $res=PkModel::insert($data);
            return view('home');
    }
    public function encrypt(Request $request){
        if($request->isMethod('post')){
        $data=$_POST;
        unset($data['_token']);
            var_dump($data);echo "<hr>";
        $userinfo=PkModel::where('user_id',$data['user_id'])->first()->toArray();
//        dd($userinfo);
        if ($userinfo){
            $date=$data['pubkey'];
            $pubkey=$userinfo['pubkey'];
        }
            $date=base64_decode($date);
//            $pubkey=file_get_contents($pubkey);
            var_dump($date);echo "<hr>";
            var_dump($pubkey);echo "<hr>";
        openssl_public_decrypt($date,$dec_data,$pubkey);
        var_dump($dec_data);
        }
        return view('test/encrypt');
    }
}
