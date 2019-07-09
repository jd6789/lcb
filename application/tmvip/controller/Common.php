<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/3/30
 * Time: 18:33
 */
namespace app\tmvip\controller;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;
class Common extends Controller{

    //标识是否进行权限认证
    public $is_check_rule =true;
    //保存用户的信息，包括基本信息、角色ID、权限信息
    public $user=array('role_id'=>"",'menus'=>"");

    public function __construct()
    {
        // echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        parent::__construct();



        if(!cookie('admin')){
            echo '<script type="text/javascript">';
            echo 'window.top.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmvip"';//top表示在最上面的框架打开
            echo '</script>';
        }



//        //判断当前用户是否登录
        $admin =cookie('admin');
        if(empty($admin)){
           // $this->error('登录信息已失效，请重新登录','Common/check');
            echo '<script type="text/javascript">';
            echo 'window.top.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmvip"';//top表示在最上面的框架打开
            echo '</script>';
            //$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/tmvip');
            //$this->redirect('tmvip/Index/index');
        }
        //根据用户的ID获取对应的角色ID
        //$role_info = M('AdminRole')->where('admin_id='.$admin['id'])->find();
        //将角色ID存储到user属性中
        //$this->user['role_id']=$role_info['role_id'];


        $this->user['role_id']= $admin['role_id'];
        if($admin['role_id']==1){
            //超级管理员
            $this->is_check_rule=false;//不验证权限
            $rule_list = Db::table('tea_rule')->select();
        }else{
            //普通管理员
            //1、根据角色ID获取对应的权限ID
            //2、根据权限ID获取对应的权限信息
            $rules = Db::table('tea_role_rule')->where("role_id",intval($admin['role_id']))->select();
            //将查询到的权限ID的二维数组转换为一维数组
            $rules_ids = array();
            foreach ($rules as $key => $value) {
                $rules_ids[]=$value['rule_id'];
            }
            //将一维数组转换为字符串格式方便使用in语法
            $rules_ids =implode(',', $rules_ids);
            //根据权限ID 获取对应的权限信息
            $rule_list = Db::table('tea_rule')->where("id in ($rules_ids)")->select();
        }
        //将用户对应的二维数组的权限信息转换为一维并且保存到user属性中
        foreach ($rule_list as $key => $value) {
            //将对应的模型、控制器、方法的名称进行拼接
            $this->user['rules'][]= $value['module_name'].'/'.$value['controller_name'].'/'.$value['action_name'];
            //考虑到导航菜单的显示
            if($value['is_show']==1){
                $this->user['menus'][]=$value;
            }
        }
        //针对超级管理员不进行权限认证
//        if($this->user['role_id']==1){
//            $this->is_check_rule=false;
//        }

        //权限做处理
//        foreach($this->user['menus'] as $k=>$v){
//
//        }



        if($this->is_check_rule) {
            //增加默认具备的访问权限
            $this->user['rules'][] = 'tmvip/Admin/main_index';
            $this->user['rules'][] = 'tmvip/Admin/out_login';
            $this->user['rules'][] =  'tmvip/User/user_rech_info';
            $this->user['rules'][] = 'tmvip/Report/integral_log_excel';
            $this->user['rules'][] = 'tmvip/Order/postal_excel';
            $this->user['rules'][] = 'tmvip/Order/order_excel';

            //普通管理员
            //获取当前用户访问的URL地址
            //$action = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);

            $request = Request::instance();
            $action = $request->module(). '/' . $request->controller() . '/' . $request->action() ;

            if (!in_array($action, $this->user['rules'])) {
                if ($this->request->isAjax()) {
                    return json(array('status' => 0, 'msg' => '没有权限'));
                }elseif ($this->request->isPost()) {
                    return json(array('status' => 0, 'msg' => '没有权限'));
                } else {
                    echo '没有权限';
                    exit();
                    // $this->error('没有权限');
                }
            }
        }
    }


    //获取登录管理员信息
    public function admin_info(){
        $admin_info = cookie("admin");
        return $admin_info;
    }

    //判断用户是否登录
    public function checkLogin(){
        if(!cookie('user_id')){
            $this->redirt('');
            $this->error('未登录','tmvip/Admin/admin_login');
            return false;
        }
        return true;
    }
    //让每个控制器判断是否登录
    public function check(){
        exit('1');
        echo '<script type="text/javascript">';
        echo 'window.top.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmvip"';//top表示在最上面的框架打开
        echo '</script>';
       //$this->error('登录信息已失效，请重新登录','check');
    }

    //获取当前权限的id
    public function url_id($module,$controller,$action){
       return  Db::table("tea_rule")->where(["module_name"=>$module,"controller_name"=>$controller,"action_name"=>$action])->find();
    }
}