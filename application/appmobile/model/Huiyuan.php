<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/4/8
 * Time: 11:44
 */
namespace app\appmobile\model;
use think\Db;
use think\Cookie;
use think\Session;
use app\appmobile\controller;
use think\Model;
use think\Request;
class Huiyuan extends Model
{
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

    //快捷登录
    public function checkTel($tel){

        $data = $this->where('tel',$tel)->find();
        if(!$data){
            $this->error = "该手机号尚未完成注册";
            return false;
        }
        $this->last_time($tel);
        $data = $this->telFirst($tel);
        session('user',$data);
        session('user_id',$data['id']);
        session('phone',1);
        return true;
    }

    //手机登录的最后时间
    public function last_time($tel){
        $info = $this->where('tel',$tel)->order('first_time')->limit(1)->find();
        $info['addtime'] = time();
        $this->where('id',$info['id'])->save($info);
    }

    //通过手机获得该手机号第一人
    public function telFirst($tel){
        $data =  $this->where('wait',1)->where('tel',$tel)->order('first_time')->limit(1)->find();
        return $data;
    }
}