<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/9
 * Time: 11:36
 */
namespace app\partner\controller;

use app\partner\model\RechargeCart;
use app\appmobile\controller\user;
use app\tmvip\controller\Api;
use app\tmvip\controller\Common;
use think\Db;
use think\View;

class Recharge extends Co
{
    //判断用户是否购买权限
    public function allowBuy($user_id,$recharge_money)
    {
        //首先判断用户是否有过购买记录，然后判断是否允许购买以及再次购买
        $res = Db::table('tea_user_recharge')
            ->where([
                'is_return' =>1,
                'user_id'   =>$user_id
            ])
            ->find();
        if($res){
            //如果存在就是再次购买
            $this->allowAddBuy($user_id,$recharge_money);
        }else{
            return true;
        }
    }

    //判断用户是否能再次购买
    public function allowAddBuy($user_id,$recharge_money)
    {
        //根据用户的购买记录查询最后一次的购买钱数
        //只允许购买大于等于最后一次的购买的钱数
        $actioninfo =  Db::table('tea_user_recharge')
            ->where([
                'is_return' =>0,
                'user_id'   =>$user_id
            ])
            ->order('rec_addtime desc')->limit('1')
            ->find();
        //如果不存在则提示用户前往购买理茶宝
        if(!$actioninfo){
            echo 0;die;// 跳转到购买页面
            //$this->error("当前的积分未返还完",U('Index/customer_info'));
        }
        if(intval($actioninfo['rec_money']) > intval($recharge_money)){
            echo 1;die;
            //$this->error("当前购买的产品金钱数小于上次购买的钱数",U('index/lichabao'));
        }else{
            return true;
        }
    }

    //用户购买理茶宝产品(第一次及后续积分返还完再次购买)
    public function buyManage()
    {
        $this->checkLogin();
        $user_id=session('user_id');
        $recharge_id = intval(input('post.recharge_id'));   //获取传递过来的股东Id
        $rechargeData=Db::table('tea_new_recharge')->where('recharge_id',$recharge_id)->find();
        //判断用户是否有推荐人，调去公共查询用户的方法
        $user_con=new User();
        $user_info=$user_con->is_real();
        if($user_info==0){
            return 3;
            //$this->error('尚未填写推荐人，前往填写',U('user/my_recommender'));
        }
        //首先判断是否允许客户购买
        $res =$this->allowBuy($user_id,$rechargeData['rec_money']);
        if(!$res){
            return 0;
            //  $this->error('请在理茶宝积分返还完再购买',U('index/index'));
        }

        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if ($nopayorder) {
            return  4;
            // $this->error('您有尚未支付的理茶宝产品，请支付或者取消',U('index/customer_info'));
        }

        //只有用户支付成功才允许数据入库
        $model = new RechargeCart();
        $res =$model->buyManage($recharge_id,$user_id,$rechargeData['rec_money']);
        if(!$res){
            echo 5;die;
            //$this->error('下单购买失败');
        }
        //下单成功，跳转到支付宝支付，带上Id
        $data['status']  = 1;
        $data['id'] = $res;
        return json($data);
    }

    //用户追加购买
    public function addBuyManage(){
        $this->checkLogin();
        $user_id = session('user_id');
        //获取用户追加购买时参数
        $recharge_id = intval(input('post.reacharge_id'));
        $moeny = intval(input('post.rec_money'));
        //在个人中心实现追加购买
        //①首先判断用户购买的金额是否大于最后一次购买的金额
        $actioninfo = Db::table('tea_user_recharge')
            ->where('user_id',$user_id)
            ->where('is_return',0)
            ->order('rec_addtime desc')
            ->limit('1')->find();
        if(intval($actioninfo['rec_money']) > $moeny){
            return 0;
            //$this->error("当前追加购买的产品金钱数小于当前购买的钱数",U('index/customer_info'));
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if ($nopayorder) {
            return  4;
            // $this->error('您有尚未支付的理茶宝产品，请支付或者取消',U('index/customer_info'));
        }
        //②将购买的金额以及ID添加到积分购物车里面去
        $model = new RechargeCart();
        $res =$model->buyManage($recharge_id,$user_id,$moeny);
        if(!$res){
            return 2;
            $this->error('下单购买失败');
        }
        //下单成功，跳转到支付宝支付，带上Id
        //下单成功，跳转到支付宝支付，带上Id
        $data['status']  = 1;
        $data['id'] = $res;
        return json($data);
    }



    //取消相关的订单
    public function del(){

        $this->checkLogin();
        //理茶宝订单表ID
        $id=intval(input('post.id'));
        $user_id = session('user_id');
        //取消订单时将订单所有的茶籽返还到积分表中去
        //在模型中调用这个方法
        $model=new RechargeCart();
        $res = $model->del($user_id,$id);
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

    //显示个人中心的我的理茶宝订单
    public function myRechargeIndex(){
        $this->checkLogin();
        $user_id=session('user_id');
        $data=Db::table('tea_recharge_cart')
            ->alias('c')
            ->field('c.*,r.rec_money')
            ->where('c.user_id',$user_id)
            ->join('tea_recharge r','r.id=c.recharge_id')
            ->select();
        return json($data);
    }


    //在我的理茶宝页面，点击购买激活的时候查看，用户信息完整验证
    public function checkToActive(){
        $user_id=session('user_id');
        $userApi=new User();
        $user_info=$userApi->is_real();
        if($user_info==0){
            $data['status']=0;
            $data['msg']="信息不完整";
            return json($data);
            //$this->error('尚未填写推荐人，前往填写',U('user/my_recommender'));
        }
        $actioninfo = Db::table('tea_user_recharge')
            ->where("user_id=$user_id AND is_return=1")
            ->order('addtime desc')
            ->limit('1')
            ->find();
        if($actioninfo){
            //已经购买了
            $data['status']=2;
            return json($data);
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if ($nopayorder) {
            $data['status']=3;
            return json($data);
            // $this->error('您有尚未支付的理茶宝产品，请支付或者取消',U('index/customer_info'));
        }


        $data['status']=1;
        return json($data);

    }
}