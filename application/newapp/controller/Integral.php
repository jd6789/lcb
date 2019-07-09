<?php
/**
 * Created by PhpStorm.
 * User: jieyu
 * Date: 2018/4/25
 * Time: 15:40
 */

namespace  app\newapp\controller;
use think\Db;

class Integral extends Com{

    //记录
    public function index(){
        $user_id = intval(session('lcb_user_id'));
        $where  = " id > 0 and user_id = $user_id and wallet = 0 ";
        $howtime1 = input('post.time1');
        if($howtime1){
            $howtime1 = strtotime($howtime1)+24*3600;
            $where .= " and addtime <= $howtime1";
        }
//        $howtime2 = input('post.time2');
//        if($howtime2){
//            $howtime2 = strtotime($howtime2);
//            $where .= " and addtime <= $howtime2";
//        }
        $type = intval(input('post.type'));
        if($type==2){
            $where .= " and fix = 1 ";
        }
        if($type==3){
            $where .= " and use_type = 1 and fix = 2 ";
        }
        if($type==4){
            $where .= " and (shopping = 1  or online = 1 )";
        }
        $page = intval(input('post.page'));
        $count =6;
        if($page){
            $page = (intval($page) - 1)*$count;
        }else{
            $page = 0;
        }
        //dump($where);
        $data = Db::table("tea_integral_log")->where($where)->limit($page,$count)->order('id desc')->select();
        $count = Db::table("tea_integral_log")->where($where)->count('id');

        return json_encode(array('list'=>$data,'count'=>$count));
    }

    public function wallet_info(){
        $user_id = intval(cookie('user')['user_id']);
        $where  = " id > 0 and user_id = $user_id and use_type = 0 ";
        $howtime1 = input('post.time1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .= " and addtime >= $howtime1";
        }
        $howtime2 = input('post.time2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .= " and addtime <= $howtime2";
        }
        $type = intval(input('post.type'));
        if($type==2){
            $where .= " and wallet = 1 ";
        }
        if($type==3){
            $where .= " and wallet = 2  ";
        }
        if($type==4){
            $where .= " and wallet = 3  ";
        }
        if($type==5){
            $where .= " and wallet = 4  ";
        }
        $page = intval(input('post.page'));
        $count =4;
        if($page){
            $page = (intval($page) - 1)*$count;
        }else{
            $page = 0;
        }
        //dump($where);
        $data = Db::table("tea_integral_log")->where($where)->limit($page,$count)->select();
        $count = Db::table("tea_integral_log")->where($where)->count('id');
        //dump($data);die;
        //dump($data);die;
        return json_encode(array('list'=>$data,'count'=>$count));
    }
}