<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/4/8
 * Time: 11:43
 */
namespace app\appmobile\controller;
use think\Controller;
use think\Cookie;
use think\Request;
use app\appmobile\model\Huiyuan;
use think\Session;
use think\Db;
use think\Upload;
class Newuser extends Common{
    public function index(){
        $user_id = session('user_id');
        $user_id = 32;
        $groom_info = "";
        $groom_info = $this->groom_info($user_id);
        //每个人推荐的一级人数
        $user_info['one_num'] = $groom_info['num'];
        //每个人推荐的一级人的id
        $user_info['one_num_id'] = $groom_info['num_id'];
       // $user_info['one_num_info'] = $groom_info['num_info'];
        $user_info['sum_one_rec_monry'] = $groom_info['sum_one_rec_monry'];
        //每个人推荐的二级人数
        if($groom_info['num_id']){
            $groom_info_second = $this->groom_info_second($groom_info['num_id']);
            //dump($groom_info_second);die;
            if($groom_info_second['counts_id']){
                //每个人推荐的二级人的id
                $user_info['two_num_id'] = substr($groom_info_second['counts_id'],0,strlen($groom_info_second['counts_id'])-1);
            }else{
                $user_info['two_num_id'] = "";
            }
            //每个人推荐的二级人数
            $user_info['two_num'] = $groom_info_second['counts'];
           // $user_info['two_num_info'] = $groom_info_second['counts_info'];
            $user_info['sum_second_rec_monry'] = $groom_info_second['sum_second_rec_monry'];
        }else{
            $arr = array();
            $user_info['two_num'] = 0;
            $user_info['two_num_id'] = "";
            //$user_info['two_num_info'] = $arr;
            $user_info['sum_second_rec_monry'] = 0;
        }
        dump($user_info);

    }

    //获取每个人的推荐人一级（直推）
    public function groom_info($id){

        //$groom_info = Db::table("tea_user")->field('id,user_name,parent_id')->where("parent_id",$id)->select();
        $groom_info = Db::connect(config('db_config2'))->name('users')->where("parent_id",$id)->select();
        $one_rec_monry = 0;
        foreach($groom_info as $k => $v){
            $groom_info[$k]['integral'] = $this->last_integral(intval($v['user_id']));
            $one_rec_monry += $groom_info[$k]['integral']['sum_rec_money'];
        }
        $list['num'] = count($groom_info);
        $list['num_id'] = $this->getId($groom_info);
        //$list['num_info'] = $groom_info;
        $list['sum_one_rec_monry'] = $one_rec_monry;
        return $list;
    }

    //获取每个人二推人数
    public function groom_info_second($groom_info_id){
        $arr = explode(",",$groom_info_id);
        $counts = 0;
        $counts_id = "";
        $counts_sum = 0;
        $count_arr = array();
        for($i = 0;$i< count($arr);$i++){
            $infos = $this->groom_info($arr[$i]);
            $counts += intval($infos['num']);
            if($infos['num_id']!=""){
                $counts_id .= $infos['num_id'] .",";
            }
            //$count_arr = array_merge($count_arr, $infos['num_info']);
            $counts_sum += intval($infos['sum_one_rec_monry']);
        }
        $list['counts'] = $counts;
        $list['counts_id'] = $counts_id;
        //$list['counts_info'] = $count_arr;
        $list['sum_second_rec_monry'] = $counts_sum;
        return $list;
    }

    //获取资源集的id
    public function getId($info){
        if(empty($info)){
            $str = "";
        }else{
            $str  = '';
            foreach($info as $k => $v){
                $str .= $v['user_id'].",";
            }
            $str = substr($str,0,strlen($str)-1);
        }
        return $str;
    }

    //获取用户最后一次购买的记录
    public function last_integral($user_id){
        //$user_id = intval($data['id']);
        //是否激活
        $integral_data = Db::query("select max(id) as 'id' from tea_integral where user_id = $user_id");
        if($integral_data){
            //是否购买
            $user_integral_data = Db::query("select max(id) as 'id' from tea_user_recharge where user_id = $user_id and pay_status = 1 ");
            if($user_integral_data){
                //未购买
                $list = array("total_sum"=>0,"surplus_inte"=>0,"back_inte"=>0,"tea_inte"=>0,"tea_ponit_inte"=>0,"reg_inte"=>0,"lev"=>0);
            }else{
                $list = array("total_sum"=>$user_integral_data['total_inte'],"surplus_inte"=>$user_integral_data['total_inte'],
                    "back_inte"=>0,"tea_inte"=>0,"tea_ponit_inte"=>0,"reg_inte"=>$user_integral_data['reg_rec'],"lev "=>$user_integral_data['lev']);
            }
        }else{
            $list = array("total_sum"=>$integral_data['total_sum'],"surplus_inte"=>$integral_data['surplus_inte'],"back_inte"=>$integral_data['back_inte'],
                "tea_inte"=>$integral_data['tea_inte'],"tea_ponit_inte"=>$integral_data['tea_ponit_inte'],"reg_inte"=>0,"lev "=>$integral_data['lev']);
        }
        $sum_user_integral_data = Db::query("select sum(rec_money) as 'sum_rec_money' from tea_user_recharge ");
        $list['sum_rec_money'] = $sum_user_integral_data[0]['sum_rec_money'];
        //$data['integral'] = $list;
        return $list;
    }
}