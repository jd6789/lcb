<?php
/**
 * Created by PhpStorm.
 * User: jieyu
 * Date: 2018/4/20
 * Time: 15:20
 */
namespace app\tmvip\controller;

class Api {

    protected $domain_name = "www.tmvip.cn";
    protected $app_key ="1807CC58-3D08-465B-B908-DF7F088E3218";

    public function Userinfo(){
        $url = $this->domain_name."/api.php?method=taom.user.list.get&app_key=$this->app_key&page=1&page_size=30&format=json";
        $data = $this->getHttp($url);
        return $data;
    }

    //获取用户的单条信息
    public function getUserInfo($user_id,$user_name,$mobile){
        $url = $this->domain_name."/api.php?method=taom.user.list.get&app_key=$this->app_key&format=json&user_id=$user_id&user_name=$user_name&mobile=$mobile";
        $data = $this->getHttp($url);
        return $data;
    }

    //get请求
    public function getHttp($url){
        $ch=curl_init();
        //设置传输地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置以文件流形式输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //接收返回数据
        $data=curl_exec($ch);
        curl_close($ch);
        $jsonInfo=json_decode($data,true);
        return $jsonInfo;
    }
}
