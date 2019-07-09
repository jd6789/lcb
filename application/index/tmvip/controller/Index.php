<?php
namespace app\tmvip\controller;
use think\Controller;
use think\Cookie;
use app\tmvip\model\AdminUser;
class Index extends Controller {

    public function index()
    {
        if(request()->isGet()){
            return view("admin/admin_login");
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

//    public function main_index()
//    {
//        return view('index');
//        //dump($_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
//        //$this->success("11111");
//    }



}
