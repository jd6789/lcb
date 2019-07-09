<?php
/**
 * Created by PhpStorm.
 * User: jieyu
 * Date: 2018/3/29
 * Time: 10:22
 */
namespace  app\tmvip\controller;
use app\tmvip\model\AdminUser;
use think\Controller;
class Admin extends Controller{

    //管理员登录
    public function admin_login(){
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
                $this->success("登录成功");
            }else{
                $this->error("密码错误");
            }
        }else{
            $this->error("用户不存在");
        }
    }

    //添加后台管理用户
    public function add_admin(){
          $username  = trim(input('post.username'));
          $password  = trim(input('post.password'));
          $password = md5(md5($password));
          $admin_obj = new AdminUser();
          $data = $admin_obj->add($username,$password);
          if($data){
              $this->success("添加成功");
          }else{
              $this->error("添加失败");
          }
    }

    //修改管理员名称
    public function user_edit(){
        $username  = trim(input('post.username'));
        $user_id  = trim(input('post.user_id'));
        $admin_obj = new AdminUser();
        $data = $admin_obj->edit_user($username,$user_id);
        if($data){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }

    //删除管理员
    public function user_del(){
        $user_id  = trim(input('get.user_id'));
        $admin_obj = new AdminUser();
        $data = $admin_obj->del_user($user_id);
        if($data){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    //重置管理员密码
    public function reset_pwd(){
        $user_id  = trim(input('get.user_id'));
        $password = md5(md5("000000"));
        $admin_obj = new AdminUser();
        $data = $admin_obj->reset_pwd($user_id,$password);
        if($data){
            $this->success("重置成功");
        }else{
            $this->error("重置失败");
        }
    }
}