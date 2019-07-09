<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/19
 * Time: 16:23
 */

namespace app\newapp\controller;

use app\tmvip\controller\Api;
use think\Db;
use think\Controller;
use think\Request;
use think\View;
use think\Session;
class Alluser extends Com
{
    //激活理茶宝
    public function active_lcb11111($id, $user_id)
    {
        $rate_info = $this->getRate();
        $info = Db::table("tea_user_recharge")->where('is_active =0')->where('user_id', $user_id)->order('id desc')->limit(1)->find();
        //以前是否购买过
        $data = Db::table("tea_integral")->where('user_id', $user_id)->where('is_ceo',0)->order('id desc')->limit(1)->find();
        if ($data) {
            //已买过，获得上次购买记录数据叠加

            //判断是否释放结束************
            if ($data['is_return'] == 1) {
                $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
            } else {
                $res['grow_rate'] = 0;
            }
            $res['total_sum'] = $data['total_sum'] + $info['total_inte'];   //总积分
            //剩余注册积分=剩余注册积分+未激活产品的赠送注册积分-需要消耗的积分
            $res['reg_inte'] = $data['reg_inte'] + $info['reg_rec'] - $info['sec_reg_rec'];
            $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
            $res['tea_inte'] = $data['tea_inte'];       //当前茶券
            $res['tea_ponit_inte'] = $data['tea_ponit_inte'];   //当前茶点

        } else {
            $res['total_sum'] = $info['total_inte'];    //总积分
            $res['reg_inte'] = $info['reg_rec'] - $info['sec_reg_rec'];     //剩余注册积分
            $res['grow_rate'] = 0;  //增加的固定释放
            $res['tea_inte'] = 0;   //当前茶券
            $res['tea_ponit_inte'] = 0; //当前茶点
        }
        $res['erevy_back_rate'] = $info['init_rates'];  //每日固定释放值
        $res['user_id'] = $user_id; //用户id
        $res['back_inte'] = 0;  //已返还积分

        //判断是否为礼包
        if($info['is_gifts']==1){
            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + intval($info['gift_inte']);
        }

        //开始返积分
        $every_rate = $res['erevy_back_rate'] + $res['grow_rate'];    //每日固定释放总值
        // 当释放总值大于固定释放封顶
        if ($every_rate > $rate_info['hight_rate']) {
            //当天要返还的积分
            $inte = $info['total_inte'] * $rate_info['hight_rate']; //充值返还总额 * 固定释放封顶返还率
        } else {
            $inte = $info['total_inte'] * $every_rate;  //充值返还总额 x 释放总值
        }

        $res['surplus_inte'] = $res['total_sum'] - $inte;     //剩余积分 = 需返还总积分 - 当天返还积分

        if ($user_id <= 2645) {
            $res['tea_inte'] = $res['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];     //茶券 = 当前茶券 + 当天返还总积分 x 静态积分茶券释放比例
            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];    // 茶点 = 当前茶点 + 当天返还总积分 x 静态积分茶点释放比例
        } else {
            $res['tea_inte'] = 0;
            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte;
        }

        $res['back_inte'] = $res['back_inte'] + $inte;    //已返还积分 = 已返还积分 - 当天返还总积分
        $res['last_time'] = date('Y-m-d');  //最后释放时间
        $res['is_return'] = 1;  //是否返还结束为未结束
        $res['lev'] = intval($info['rec_lev']);
        //记录产生时间
        $res['addtime'] = time();
        //记录产生时间
        $res['year'] = date("Y");
        //记录产生时间
        $res['month'] = date("m");
        //记录产生时间
        $res['day'] = date("d");
        $res_insert = Db::table('tea_integral')->insert($res);

        //数据更新到用户表
        Db::table("tea_user")->where('user_id',$user_id)->update(['tea_inte'=>$res['tea_inte'],'tea_ponit_inte'=>$res['tea_ponit_inte']]);

        $tea_inte = $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
        $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];   //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
        //修改记录
        $tea_inte_ch = "+".$tea_inte;
        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
        $inte_ch = "-".$inte;

        $introduce = "激活个人账户(释放)";
        $log_obj = new \app\tmvip\controller\Integral();
        $log_obj->MakeLog($user_id, intval($info['rec_lev']), $inte_ch, $tea_inte_ch, $tea_ponit_inte_ch, 0, $introduce,
            4, $res['total_sum'], $res['surplus_inte'], 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, $user_id, intval($info['rec_lev']),$log_out_trade_no);
        $data_upd = Db::table("tea_user_recharge")->where('id', $info['id'])->update(['is_active' => 1]);
        $user_active = Db::table("tea_user_recharge")->where('id', $info['id'])->select();
        
        $time = time();
        //M('UserRecharge')->where('id=' . $info['id'])->setField('rec_addtime', $time);
        Db::table("tea_user_recharge")->where('id', $info['id'])->update(['rec_addtime' => $time]);
        if ($res_insert && $data_upd) {
            Db::table("tea_recharge_cart")->where('id', $id)->update(['is_active' => 1]);
            return true;
        } else {
            return false;
        }

    }

    //获得利率信息问题
    public function getRate()
    {

        return Db::table('tea_rate')->order("id desc")->limit(1)->find();
    }

    //激活理茶宝
    public function active_tea_treasure()
    {
        //购买记录id
        $recharge_cart_id = input('post.id');
        $user_id = empty(input('post.user_id')) ? session('lcb_user_id') : input('post.user_id');
        $res = $this->active_lcb($recharge_cart_id, $user_id);
        if ($res) {
            return json_encode(1);
        } else {
            return json_encode(0);
        }
    }

    //显示我的推荐页面
    public function registeruser(){
        return $this->fetch();
    }

    //显示我的邀请好友页面
    public function friends(){
        $user_id=session('lcb_user_id');
        $url_host=$_SERVER['HTTP_HOST'];
        $url = "http://$url_host/appmobile/alluser/registeruser?user_id=$user_id ";   //扫码登录(好的)
        //$url = "http://shop.guochamall.com/guocha/login/register_user.html?user_id=$user_id";   //扫码登录(好的)
        $view=new View();
        $view->assign('data',$url);
        return $view->fetch();
    }

    //显示用户名的信息
    public function findusenamebyid(){
        $user_id=intval(input('post.user_id'));
        $userApi=new Api();
        $user_info=$userApi->getUserInfo($user_id,'','');
        $username= $user_info['info']['list'][0]['user_name'];
        return json($username);
    }

    //显示我的付款码
    public function paycode(){
        //判断用户是否登录
        $this->checkLogin();
        $time=time();
        //删除所有超时的二维码信息
        Db::table('tea_session')->where('overtime','<',$time)->delete();
        $user_id=session('lcb_user_id');
        $user_session=Db::table('tea_session')->where('user_id',$user_id)->find();
        if($user_session){
            //当前用户已经有二维码信息入库，判断二维码是否有效
            if($time > intval($user_session['overtime'])){
                $key=strtoupper(md5(uniqid()));
                //二维码失效
                Db::table('tea_session')->where('user_id',$user_id)->delete();
                //重新生成数据
                $data=array(
                    'user_id'=>$user_id,
                    'addtime'=>time(),
                    'overtime'=>time()+600,
                    'key_s'=>$key,
                    'salt'=>md5($key.$time)
                );
                Db::table('tea_session')->insert($data);
            }else{
                $key=$user_session['key_s'];
            }
        }else{
            $key=strtoupper(md5(uniqid()));
            $data=array(
                'user_id'=>$user_id,
                'addtime'=>time(),
                'overtime'=>time()+600,
                'key_s'=>$key,
                'salt'=>md5($key.$time)
            );
            Db::table('tea_session')->insert($data);
        }

        $view=new View();
        $view->assign('data',$key);
        return $view->fetch();
    }

    //开始扫码支付
    public function qrcodepay(){
        $time=time();
        $oldtime=intval(input('get.key'));
        $timecha=$time-$oldtime;
        if($timecha<=7200){

        }else{
            return 0;
        }
    }


    //首页判断用户是否登录
    public function userLogin(){
        if(!session('lcb_user_id')){
            $data['status']=0;
            $data['msg']='no';
           return json($data);
        }
        $name = session('user');
        $data['status']=1;
        $data['msg']=$name['user_name'];
        //$data['msg']=session('lcb_user_id');
        return json($data);
    }


    ///----------------------------------------------------------------------------------
    //用户理查宝激活新规则
    public function active_lcb($id, $user_id){
        $rate_info = $this->getRate();
        $info = Db::table("tea_user_recharge")->where('is_active =0')->where('user_id', $user_id)->order('id desc')->limit(1)->find();
        //以前是否购买过
        $data = Db::table("tea_integral")->where('user_id', $user_id)->where('is_ceo',0)->order('id desc')->limit(1)->find();
        if ($data) {
            //已买过，获得上次购买记录数据叠加

            //判断是否释放结束************
//            if ($data['is_return'] == 1) {
//                $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
//            } else {
//                $res['grow_rate'] = 0;
//            }
            //剩余注册积分=剩余注册积分+未激活产品的赠送注册积分-需要消耗的积分
            //$res['reg_inte'] = $data['reg_inte'] + $info['reg_rec'] - $info['sec_reg_rec'];
            $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放

        } else {
            //$res['reg_inte'] = $info['reg_rec'] - $info['sec_reg_rec'];     //剩余注册积分
            $res['grow_rate'] = 0;  //增加的固定释放
        }

        $res['total_sum'] = $info['total_inte']   ;    //总积分
        $res['tea_inte'] = 0;   //当前茶券
        $res['tea_ponit_inte'] = $info['gift_inte'] ; //当前茶点
        $res['reg_inte'] = 0;
        $res['erevy_back_rate'] = $info['init_rates'];  //每日固定释放值
        $res['user_id'] = $user_id; //用户id
        $res['back_inte'] = 0;  //已返还积分
        $res['surplus_inte'] = $res['total_sum'] ;     //剩余积分 = 需返还总积分 - 当天返还积分
        //新增释放比例
        $res['every_tea_inte_rate'] = $rate_info['slow_tea_inte_rate'];
        $res['every_tea_ponit_inte_rate'] = $rate_info['slow_tea_score_rate'];

        //判断是否为礼包
//        if($info['is_gifts']==1){
//            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + intval($info['gift_inte']);
//        }

        //开始返积分
//        $every_rate = $res['erevy_back_rate'] + $res['grow_rate'];    //每日固定释放总值
//        // 当释放总值大于固定释放封顶
//        if ($every_rate > $rate_info['hight_rate']) {
//            //当天要返还的积分
//            $inte = $info['total_inte'] * $rate_info['hight_rate']; //充值返还总额 * 固定释放封顶返还率
//        } else {
//            $inte = $info['total_inte'] * $every_rate;  //充值返还总额 x 释放总值
//        }
//
//        $res['surplus_inte'] = $res['total_sum'] - $inte;     //剩余积分 = 需返还总积分 - 当天返还积分
//
//        if ($user_id <= 2645) {
//            $res['tea_inte'] = $res['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];     //茶券 = 当前茶券 + 当天返还总积分 x 静态积分茶券释放比例
//            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];    // 茶点 = 当前茶点 + 当天返还总积分 x 静态积分茶点释放比例
//        } else {
//            $res['tea_inte'] = 0;
//            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte;
//        }
//
//        $res['back_inte'] = $res['back_inte'] + $inte;    //已返还积分 = 已返还积分 - 当天返还总积分
        $res['back_inte'] = 0;
        $res['last_time'] = date('Y-m-d');  //最后释放时间
        $res['is_return'] = 1;  //是否返还结束为未结束
        $res['lev'] = intval($info['rec_lev']);
        //记录产生时间
        $res['addtime'] = time();
        //记录产生时间
        $res['year'] = date("Y");
        //记录产生时间
        $res['month'] = date("m");
        //记录产生时间
        $res['day'] = date("d");
        $res_insert = Db::table('tea_integral')->insert($res);

        //数据更新到用户表
        //Db::table("tea_user")->where('user_id',$user_id)->update(['tea_inte'=>$res['tea_inte'],'tea_ponit_inte'=>$res['tea_ponit_inte']]);
        Db::table("tea_user")->where('user_id',$user_id)->setInc('tea_ponit_inte',$res['tea_ponit_inte']);
        Db::table("tea_user")->where('user_id',$user_id)->setInc('tea_inte',$res['tea_inte']);

//        $tea_inte = $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
//        $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];   //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
//        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
//        //修改记录
//        $tea_inte_ch = "+".$tea_inte;
//        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
//        $inte_ch = "-".$inte;
//
//        $introduce = "激活个人账户(释放)";
//        $log_obj = new \app\tmvip\controller\Integral();
//        $log_obj->MakeLog($user_id, intval($info['rec_lev']), $inte_ch, $tea_inte_ch, $tea_ponit_inte_ch, 0, $introduce,
//            4, $res['total_sum'], $res['surplus_inte'], 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, $user_id, intval($info['rec_lev']),$log_out_trade_no);
        $data_upd = Db::table("tea_user_recharge")->where('id', $info['id'])->update(['is_active' => 1]);
        //$user_active = Db::table("tea_user_recharge")->where('id', $info['id'])->select();

        //形成记录
        $tea_ponit_inte ="+".$info['gift_inte'];
        $tea_inte ="+0";
        $surplus_inte_ch ="+".$info['gift_inte'];
        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
        $introduce = "产品激活一次性释放";
        $obj = new \app\tmvip\controller\Integral();
        $obj->MakeLog($user_id,0,$surplus_inte_ch,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,$log_out_trade_no);

        $time = time();
        //M('UserRecharge')->where('id=' . $info['id'])->setField('rec_addtime', $time);
        Db::table("tea_user_recharge")->where('id', $info['id'])->update(['rec_addtime' => $time]);
        if ($res_insert && $data_upd) {
            Db::table("tea_recharge_cart")->where('id', $id)->update(['is_active' => 1]);
            return true;
        } else {
            return false;
        }

    }


}