<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/9
 * Time: 11:36
 */

namespace app\tmvip\controller;

use think\Request;
use think\Db;
use think\Controller;
class Login extends Controller{
    public function login(){
        if(request()->isGet()){
            return view("login");
        }else{
            $username  = trim(input('post.username'));
            $password  = trim(input('post.password'));
            //判断用户名是否存在
            $admin_obj = new AdminUser();
            $data_user = $admin_obj->user_exit($username);
            if($data_user){
                //判断密码
                $password = md5(md5($password));
                $data_pwd = $admin_obj->pwd_exit($username,$password);
                if($data_pwd){
                    $this->redirect('tmvip/Admin/main_index');
                }else{
                    $this->error("密码错误");
                }
            }else{
                $this->error("用户不存在");
            }
        }
    }
}