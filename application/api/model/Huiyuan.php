<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/4/8
 * Time: 11:44
 */
namespace app\api\model;
use think\Db;
use think\Cookie;
use think\Session;
use think\Model;
use think\Request;
class Huiyuan extends Model{
    //判断用户密码是否正确
    public function pwd_exit($username,$password)
    {
        $user_info = Db::connect(config('db_config2'))->name("users")->where('user_name',$username)->find();
        if($password != $user_info['password']){
            return false;
        }else{
            //记录登录时间
            Db::connect(config('db_config2'))->name("users")->where('user_id',$user_info['user_id'])->setField('last_login', time());
            Db::table('tea_user')->where('user_id',$user_info['user_id'])->setField('last_time',time());
            Cookie::set("user",$user_info);
            session::set("user",$user_info);
            session::set("user_id",$user_info['user_id']);
            return true;
        }
    }
}