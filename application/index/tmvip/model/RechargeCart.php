<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/9
 * Time: 15:38
 */
namespace app\tmvip\Model;
use think\Model;
use think\Db;
class RechargeCart extends Model{
   //购买理茶宝
        //用户购买理茶宝成功
        public function buyUpdate($user_id,$recharge_num,$trade_no){
            //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
            $cartInfo = Db::table('tea_recharge_cart')
                ->where("user_id = $user_id AND recharge_num = '$recharge_num'")
                ->find();
            //然后通过积分购物车表查询的充值表ID查询出对应的记录
            $rechargeInfo = Db::table('tea_recharge')->where('id='.$cartInfo['recharge_id'])->find();
            $rate = Db::table('tea_rate')->find();

            //判断用户是否选择理茶宝
                $data = array(
                    'user_id' => $user_id,
                    'rec_money' => $rechargeInfo['rec_money'],
                    'subject' => $rechargeInfo['subject'],
                    'body' => $rechargeInfo['body'],
                    'rec_lev' => $rechargeInfo['rec_lev'],
                    'total_inte' => $rechargeInfo['total_inte'],
                    'init_rates' => $rechargeInfo['init_rates'],
                    'fir_rec' => $rechargeInfo['fir_rec'],
                    'sec_rec' => $rechargeInfo['sec_rec'],
                    'sec_merits' => $rechargeInfo['sec_merits'],
                    'fir_merits' => $rechargeInfo['fir_merits'],
                    'cap_rates' => $rate['hight_rate'],
                    'reg_rec' => $rechargeInfo['reg_rec'],
                    'sec_reg_rec' => $rechargeInfo['sec_reg_rec'],
                    'is_first' =>0,
                    'addtime'   =>time(),
                    'tea_inte_rate' => $rate['tea_inte_rate'],
                    'tea_score_rate' => $rate['tea_score_rate'],
                    'recharger_num'  =>$recharge_num,
                    'trade_no'  =>$trade_no,
                    'pay_status' =>1,
                    'gift_inte'=>0,
                    'is_gifts' =>0,
                    'year'=>date('Y'),
                    'month'=>date('m'),
                    'day'=>date('d'),
                );
                $inte_obj = new Integral();
                $inte_obj->InteReturn($user_id );
                $inte_obj->grow_rate($user_id );
                return Db::table('tea_user_recharge')->insert($data);


        }
}