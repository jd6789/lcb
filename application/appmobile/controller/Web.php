<?php
/**
 * Created by PhpStorm.
 * User: jieyu
 * Date: 2018/4/28
 * Time: 23:15
 */
namespace app\appmobile\controller;

use think\Controller;
use think\Db;
use think\Cookie;
class Web extends Controller {

    public function user_wx(){
        $data = input('get.');
        $user_info = Db::connect(config('db_config2'))->name("users")->where('user_id',$data['user_id'])->find();
        session("user_id",$data['user_id']);
        session("user",$user_info);
        Cookie::set("user",$user_info);
       $this->redirect('user/index');
    }
}