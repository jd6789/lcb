<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/3/30
 * Time: 15:46
 */

namespace app\tmvip\model;
use think\Cookie;
use think\Db;
use think\Session;
class AdminUser {

    //用户名是否存在
    public function user_exit($username){
        return Db::table('tea_admin')->where("username",$username)->find();
    }

    //密码是否正确
    public function pwd_exit($username,$password){
        $user_info = Db::table('tea_admin')->where("username",$username)->find();
        if($password!=$user_info['password']){
            return false;
        }else{
            Cookie::set("admin",$user_info);
            //Session::set('admin',$user_info);
            return true;
        }
    }

    //添加后台用户
    public function add($data){
        $data['addtime'] = time();
        return Db::table('tea_admin')->insert($data);
    }

    //修改管理员名称
    public function user_data($user_id){
        return Db::table('tea_admin')->where('id', $user_id)->find();
    }

    //修改管理员名称
    public function save_edit_user($username,$name,$user_id,$role_id){
        return Db::table('tea_admin')->where('id', $user_id)->update(['username' => "$username",'name'=>"$name",'role_id'=>$role_id]);
    }

    //修改管理员名称
    public function del_user($user_id){
        return Db::table('tea_admin')->where('id', $user_id)->delete();
    }

    //修改管理员密码
    public function reset_pwd($user_id,$password){
        return Db::table('tea_admin')->where('id', $user_id)->update(['password' => "$password"]);
    }

    //管理员列表
    public function admin_show(){
        return Db::table("tea_admin")->field("tea_admin.*,tea_role.role_name")->join('tea_role','tea_admin.role_id = tea_role.id','left')->select();
    }

    //获取格式化之后的数据
    public function getCateTree($id=0)
    {
        //先获取所有的分类信息
        $data = Db::table('tea_rule')->select();
        //在对获取的信息进行格式化
        $list = $this->getTree($data,$id);
        return $list;
    }
    //格式化分类信息
    public function getTree($data,$id=0,$lev=1)
    {
        static $list = array();
        foreach ($data as $key => $value) {
            if($value['parent_id']==$id){
                $value['lev']=$lev;
                $list[]=$value;
                //使用递归的方式获取分类下的子分类
                $this->getTree($data,$value['id'],$lev+1);
            }
        }
        return $list;
    }

    //添加权限入库
    public function add_rule($data){
        return Db::table("tea_rule")->insert($data);
    }

    //id获取权限
    public function getRuleById($id){
        return Db::table("tea_rule")->where('id',$id)->find();
    }

    //保存修改
    public function save_edit_rule($id,$data){
        return Db::table("tea_rule")->where('id',$id)->update($data);
    }

    //删除
    public function del_rule($id){
        $result = Db::table("tea_rule")->where('parent_id='.$id)->find();
        if($result){
            return false;
        }
        return Db::table("tea_rule")->where('id',$id)->delete();
    }

    //获取权限
    public function getRule(){
        $data = Db::table("tea_rule")->where("parent_id",0)->select();
        foreach($data as $k => $v){
            $data[$k]['info'] = Db::table("tea_rule")->where("parent_id",intval($v['id']))->select();
            foreach($data[$k]['info'] as $k1=>$v1){
                $data[$k]['info'][$k1]['info'] = Db::table("tea_rule")->where("parent_id",intval($v1['id']))->select();
            }
        }
        return $data;
    }

    //角色名是否存在
    public function role_name_exit($name){
        return Db::table("tea_role")->where("role_name",$name)->find();
    }

    //角色添加
    public function add_role_name($name){
        Db::table("tea_role")->insert(['role_name'=>$name]);
        $Id = Db::name('tea_role')->getLastInsID();
        return $Id;
    }

    //分配权限
    public function add_rule_id($id,$data){
        Db::table("tea_role_rule")->where("role_id",$id)->delete();
        foreach($data as $k=>$v){
            $inse = Db::table("tea_role_rule")->insert(['role_id'=>$id,'rule_id'=>intval($v)]);
            if(!$inse){
                return false;
            }
        }
        return true;
    }

    //所有角色
    public function getRole(){
        return Db::table('tea_role')->select();
    }

    //获取角色权限
    public function edit_role($id){
        return Db::table("tea_role_rule")->where("role_id = $id")->select();
    }

    public function getRoleById($id){
        return Db::table("tea_role")->where('id',$id)->find();
    }

    //角色更新
    public function update_role_name($id,$name){

        return  Db::table("tea_role")->where('id',$id)->update(['role_name'=>$name]);

    }

    //权限更新
    public function update_rule_id($id,$data){
        //删除以前的
        Db::table("tea_role_rule")->where('role_id',$id)->delete();
        return $this->add_rule_id($id,$data);
    }

    //删除
    public function del_role($id){
        $del1 = Db::table("tea_role")->where('id',$id)->delete();
        $del2 = Db::table("tea_role_rule")->where('role_id',$id)->delete();
        if($del1 && $del2){
            return true;
        }else{
            return false;
        }
    }


}