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
use think\Cookie;
use think\Request;
class Admin extends Common{
        //后台登陆后首页
    public function main_index()
    {
        $admin_obj = new AdminUser();
        //$menu = $this->user['menus'];
        $menu = $this->user['menus'];
        $admin = cookie("admin");
        //获取角色名
        $role = $admin_obj->getRoleById($admin['role_id']);
        $admin['role_name'] = $role['role_name'];
        //dump($role);
        //die;
        return view('index/index',['menu'=>$menu,'admin'=>$admin]);
        //dump($_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        //$this->success("11111");
    }

    //管理员登录
//    public function admin_login(){
//        if(request()->isGet()){
//            return view("admin_login");
//        }else{
//            $username  = trim(input('post.username'));
//            $password  = trim(input('post.password'));
//            //判断用户名是否存在
//            $admin_obj = new AdminUser();
//            $data_user = $admin_obj->user_exit($username);
//            if($data_user){
//                //判断密码
//                $password = md5(md5($password));
//                $data_pwd = $admin_obj->pwd_exit($username,$password);
//                if($data_pwd){
//                    $this->redirect('tmvip/Index/index');
//                }else{
//                    $this->error("密码错误");
//                }
//            }else{
//                $this->error("用户不存在");
//            }
//        }
//    }

    public function index(){
        $menu = $this->user['menus'];
        //dump($menu);die;
        //获取当前url的权限id
        $request = Request::instance();
        //$request->module(). '/' . $request->controller() . '/' . $request->action() ;
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }

    //添加后台管理用户
    public function add_admin(){
        $admin_obj = new AdminUser();
        if(request()->isGet()){
            $data = $admin_obj->getRole();
            return view('add_admin',['data'=>$data]);
        }else{
            $username  = trim(input('post.username'));
            $password  = trim(input('post.password'));
            $name = trim(input('post.name'));
            $role_id = trim(input('post.role_id'));
            if(!$username || !$password || !$name || !$role_id){
                $this->error("填写不完整，重新添加");
            }
            //判断是否存在
            $name_exit = $admin_obj->user_exit($username);
            if($name_exit){
                $this->error("登录名已存在，重新添加");
            }
            $password = md5(md5($password));

            $data = ['username' => $username, 'password' => $password,'name'=>$name,'role_id'=>intval($role_id)];
            $insert_data = $admin_obj->add($data);
            if($insert_data){
                $this->success("添加成功","tmvip/Admin/admin_show");
            }else{
                $this->error("添加失败");
            }
        }
    }

    //获取管理员信息 id
    public function user_edit($user_id){
        $admin_obj = new AdminUser();
        $admin_data = $admin_obj->user_data($user_id);
        $role = $admin_obj->getRole();

        //dump($admin_data);die;
        return view("user_edit",['data'=>$admin_data,'role'=>$role]);
    }

    //修改管理员信息
    public function save_user_edit(){
        $admin_obj = new AdminUser();

        $username  = trim(input('post.username'));
        $name  = trim(input('post.name'));
        $user_id  = trim(input('post.user_id'));
        $role_id = trim(input('post.role_id'));
        //dump(input('post.'));die;
        if(!$username || !$name){
            $this->error("填写不完整，重新添加");
        }
        //判断是否存在
//        $name_exit = $admin_obj->user_exit($username);
//        if($name_exit){
//            $this->error("登录名已存在，重新添加");
//        }
        $data = $admin_obj->save_edit_user($username,$name,$user_id,$role_id);
        if($data){
            $this->success("修改成功","tmvip/Admin/admin_show");
        }else{
            $this->error("修改失败");
        }
    }

    //删除管理员
    public function user_del($user_id){
        //$user_id  = trim(input('get.user_id'));
        $admin_obj = new AdminUser();
        $data = $admin_obj->del_user($user_id);
        if($data){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    //重置管理员密码
    public function reset_pwd($user_id){
        //$user_id  = trim(input('get.user_id'));
        $password = md5(md5("000000"));
        $admin_obj = new AdminUser();
        $data = $admin_obj->reset_pwd($user_id,$password);
        if($data){
            $this->success("重置成功");
        }else{
            $this->error("重置失败");
        }
    }

    //管理员退出
    public function out_login(){
        Cookie::clear('admin');// 删除cookie
        //Cookie::set("admin","");
        $this->redirect('tmvip/Index/index');
    }

    //管理员列表
    public function admin_show(){
        $admin_obj = new AdminUser();
        $data = $admin_obj->admin_show();
        return view("admin_show",['data'=>$data]);
    }


    //添加权限
    public function add_rule(){
        $admin_obj = new AdminUser();

        if(request()->isGet()){
            //获取格式化之后的权限信息
            $cate = $admin_obj->getCateTree();
            //将信息赋值给模板
            //dump($cate);die;
            return view("add_rule",['cate'=>$cate]);
        }else{
            //数据入库
            //创建数据
            $data = input("post.");
            if($data['rule_name'] && $data['rule_name'] && $data['rule_name'] &&$data['rule_name']){
                $insertid = $admin_obj->add_rule($data);
                if(!$insertid){
                    $this->error('数据写入失败');
                }

                $this->success('写入成功','tmvip/Admin/rule_show');
            }else{
                $this->error('数据填写不完整');
            }

        }
    }

    //权限列表
    public function rule_show(){
        $admin_obj = new AdminUser();
        $list = $admin_obj->getCateTree();
        return view("rule_show",['list'=>$list]);
    }

    //权限修改
    public function edit_rule($id){
        $admin_obj = new AdminUser();
        $cate = $admin_obj->getCateTree();
        $data = $admin_obj->getRuleById(intval($id));
        return view("edit_rule",['cate'=>$cate,'data'=>$data]);
    }

    //保存修改
    public function save_edit_rule(){
        $admin_obj = new AdminUser();
        $id = intval(input("post.id"));
        $data = input("post.");
        //dump($id);die;
        $up_rule = $admin_obj->save_edit_rule($id,$data);
        if($up_rule){
            $this->success("修改成功",'tmvip/Admin/rule_show');
        }else{
            $this->error("修改失败");
        }
    }

    //权限修改
    public function del_rule($id){
        $admin_obj = new AdminUser();
        $data = $admin_obj->del_rule(intval($id));
        if(!$data){
            $this->error("删除失败");
        }
        $this->success("删除成功",'tmvip/Admin/rule_show');
    }

    //添加角色
    public function add_role(){
        $admin_obj = new AdminUser();

        if(request()->isGet()){
            //$rule_data = $admin_obj->getCateTree();
            $rule_data = $admin_obj->getRule();
            //dump($rule_data);die;
            return view("add_role",['rule'=>$rule_data]);
        }else{
            $data = input("post.");
            if(!$data['rule_name']){
                $this->error("角色名不能为空");
            }
            if(empty($data['rule'])){
                $this->error("权限不能为空");
            }
            //判断角色名是否重复
            $rule_data = $admin_obj->role_name_exit($data['rule_name']);
            if(!empty($rule_data)){
                $this->error("角色名重复");
            }
            $role_data = $admin_obj->add_role_name($data['rule_name']);
            if(!$role_data){
                $this->error("添加失败");
            }else{
                $rule_data = $admin_obj->add_rule_id(intval($role_data),$data['rule']);
                if(!$rule_data){
                    $this->error("分配权限失败");
                }else{
                    $this->success("添加成功",'tmvip/Admin/role_show');
                }
            }
        }
    }

    //角色展示
    public function role_show(){
        $admin_obj = new AdminUser();
        $data = $admin_obj->getRole();
        return view("role_show",['data'=>$data]);
    }

    //角色修改
    public function edit_role($id){
        $admin_obj = new AdminUser();
        $rule_data = $admin_obj->getRule();
        $hasRules = $admin_obj->edit_role(intval($id));
        $hasRulesIds = "";
        foreach ($hasRules as $key => $value) {
            $hasRulesIds .=$value['rule_id'].",";
        }
        $hasRulesIds = substr($hasRulesIds,0,strlen($hasRulesIds)-1);
        $role_name = $admin_obj->getRoleById($id);
        return view("edit_role",['rule'=>$rule_data,'hasRules'=>$hasRulesIds,'role_name'=>$role_name]);
    }

    //修改保存
    public function save_edit_role()
    {
        $admin_obj = new AdminUser();

        $id = intval(input("post.id"));
        $data = input("post.");
        if (!$data['rule_name']) {
            $this->error("角色名不能为空");
        }
        if (empty($data['rule'])) {
            $this->error("权限不能为空");
        }
        //角色名是否改变
        $role_name = $admin_obj->getRoleById($id);
        if ($role_name['role_name'] != $data['rule_name']) {
            $role_data = $admin_obj->update_role_name($id, $data['rule_name']);
        }
        $rule_data = $admin_obj->add_rule_id($id, $data['rule']);
        if (!$rule_data) {
            $this->error("分配权限失败");
        } else {
            $this->success("修改成功", 'tmvip/Admin/role_show');
        }
    }

    //删除角色
    public function del_role($id){
        $admin_obj = new AdminUser();
        $del = $admin_obj->del_role(intval($id));
        if(!$del) {
            $this->error("删除失败");
        } else {
            $this->success("删除成功", 'tmvip/Admin/role_show');
        }
    }

}