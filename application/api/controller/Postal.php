<?php

namespace app\api\controller;
use think\Controller;
use think\Db;
class Postal extends Controller{

    //提现页面获取基本信息
    public function getPostalBaseInfo(){
        $user_id = intval(session("user_id"));
        $data = Db::table("tea_user")->where("user_id",$user_id)->find();
        $data['teas']=round((floatval($data['tea_ponit_inte'])+floatval($data['tea_inte'])),2);
        $data['tea_inte']=round(floatval($data['tea_inte']),2);
        //提现所有
        $postal = Db::table("tea_postal")->where("user_id = $user_id and status = 1 ")->sum('money_num');
        $postal_will = Db::table("tea_postal")->where("user_id = $user_id and status = 0 ")->sum('money_num');
        if(empty($postal)){
            $postal = 0;
        }
        if(empty($postal_will)){
            $postal_will = 0;
        }
        $list['data'] = $data;
        $list['info'] = $postal;
        $list['postal_will'] = $postal_will;
        return json($list);
    }

    //提现
    public function getPostalInfo(){
        $inte_num = $this->request->post('inte_num');
        //$user_id = $this->request->post('user_id');
        $user_id = intval(session("user_id"));
        //$user_id = 146;
        //$inte_num = 100;
        //判断是否实名
        $real = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->select();
        if(empty($real)){
            return json(3);
        }
        $data = Db::table("tea_user")->where("user_id",$user_id)->find();

        if(intval($inte_num) > floatval($data['tea_inte'])){
            //积分不足
            return json(0);
        }
        // 启动事务
        Db::startTrans();
        try {
            //修改积分信息
            $tea_inte = floatval($data['tea_inte'])-intval($inte_num);
            Db::table("tea_user")->where("user_id",$user_id)->update(['tea_inte'=>$tea_inte]);
            //写入记录表
            $trade_no=date('Y').date('m').date('d').uniqid().'168';
            //dump($trade_no);
            $list = array('user_id'=>$user_id,'inte_num'=>$inte_num,'money_num'=>$inte_num, 'create_time'=>date('Y-m-d H:i:s'),'trade_no'=>$trade_no);
            Db::table("tea_postal")->insert($list);
            // 提交事务
            Db::commit();
            return json(2);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            //失败
            return json(1);
        }


    }

    //显示所有提现记录
    public function allPostalInfo(){
        $user_id = intval(session("user_id"));
        //$user_id = 146;
        $page = intval(input('post.page'));
        $where = " user_id = $user_id ";
        $status = intval(input('post.status'));
        if($status){
            $where .= " and status = $status ";
        }
        $count = 8;
        if($page){
            $page = ($page-1)*$count;
        }else{
            $page = 0;
        }
        //dump($where);
        $data = Db::table("tea_postal")->where($where)->order("create_time desc")->limit($page,$count)->select();
        if(empty($data)){
            $list['data'] = 0;
            return json($list);
        }
        $data_num = count(Db::table("tea_postal")->where("user_id",$user_id)->select());
        $list['data'] = $data;
        $list['data_num'] = $data_num;
        return json($list);
    }

}