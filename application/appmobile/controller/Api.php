<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/25
 * Time: 10:21
 */
namespace app\appmobile\controller;
use think\Controller;
use think\Db;
use think\Session;
class Api extends Controller{
    //扫码支付的接口
    public function pay(){
        //通过传递过来的值来查询这个值是否在有效期内
        $key=input('post.key');
        $key_data=Db::table('tea_session')->where('key',$key)->find();
        if(!$key_data){
            //二维码的信息不存在，已经失效删除了
            return 0;
        }else{
            if(intval($key_data['overtime']) < time()){
                //二维码没有删除，但是已经过期
                return 0;
            }
            //二维码的信息是OK存在的，开始处理业务逻辑
            //获取传递过来的参数
            //START
            $user_id=intval($key_data['user_id']);
            //查询用户的茶点茶券
            $user_data=Db::table('tea_user')->where('user_id',$user_id)->find();
            return json($user_data);
        }
    }
}