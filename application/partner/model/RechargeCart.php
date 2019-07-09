<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/9
 * Time: 15:38
 */
namespace app\partner\model;

use think\Model;
use think\Db;
use app\tmvip\controller\Integral;

class RechargeCart extends Model{
    //用户下单购买股东权益
    public function buyManage1111($recharge_id,$user_id,$recharge_money,$store_id)
    {
        $a = rand(100, 999);
        $b = rand(100, 999);
        $res = "$a" . time() . "$b" . '168';
        $model=new RechargeCart(
            [
                'recharge_money'=>$recharge_money,
                'recharge_id' =>$recharge_id,
                'user_id'     =>$user_id,
                'rec_addtime' =>time(),
                'recharge_num'=>$res,
                'is_ceo'=>1,
                'store_id'=>$store_id,
                'is_gift'=>0
            ]
        );
       $model->save();
       return $model->id;
    }
    //取消订单
    public function del($user_id,$id){
        //①先删除订单表的数据
        $data = Db::table('tea_recharge_cart')
            ->where([
                'user_id'=>$user_id,
                'id'=>$id
            ])
           ->find();
        $resinfo=Db::table('tea_recharge_cart')->where('user_id',$user_id )->where('id',$id)->delete();
        if(floatval($data['again_money'])==0.00){

        }else{
            if($resinfo){
                Db::table('tea_user')->where('user_id',$user_id)->setInc('wallet',floatval($data['again_money']));
                $log_data=array(
                    'user_id'=>$user_id,
                    'introduce'=>'取消理茶宝订单退回钱包的金额'.floatval($data['again_money']),
                    'wallet'=>4,
                    'addtime'=>time(),
                    'year'=>date('Y'),
                    'month'=>date('m'),
                    'day'=>date('d'),
                    'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
                );
                Db::table('tea_integral_log')->insert($log_data);
            }
        }
        return $resinfo;
    }
    //用户升级理茶宝
    public function addBuyManage($recharge_id,$user_id,$recharge_money,$teares,$last_lev){
        $a = rand(100,999);
        $b = rand(100,999);
        $res ="$a".time()."$b".'168';
        $data = array(
            'again_money'=>$teares,
            'recharge_money'=>$recharge_money,
            'recharge_id' =>$recharge_id,
            'user_id'     =>$user_id,
            'rec_addtime' =>time(),
            'is_againbuy'=>1,
            'recharge_num'=>$res,
            'is_gift'=>intval(input('post.is_gift')),
            'last_lev'=>$last_lev
        );
        Db::table('tea_recharge_cart')->insert($data);
        return Db::name('tea_recharge_cart')->getLastInsID();
    }
    //更新数据库的数据(升级时操作user_recharge表)  ---失效方法---start
    public function updateRecharge($user_id,$recharger_num,$trade_no){
        //将数据更新到用户的充值表，需更新的字段为：支付宝交易号，订单号，金额，以及所有需返还的积分
        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
        $cartInfo = Db::table('tea_recharge_cart')
            ->where("user_id=$user_id AND pay_status=1 AND recharge_num='$recharger_num'")
            ->find();
        //然后通过积分购物车表查询的充值表ID查询出对应的记录
        $rechargeInfo = Db::table('tea_recharge')
            ->where('id',$cartInfo['recharge_id'])
            ->find();
        $UserRecharge=Db::table('tea_user_recharge');
        return  $UserRecharge
            ->where('user_id',$user_id)
            ->update([
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
            'is_first' =>0,
            'addtime'   =>time(),
            'trade_no'  =>$trade_no,
        ]);

       // return D('UserRecharge')->where("user_id = '$user_id'")->setFiled($data);

    }
    // ---失效方法---end




    //用户购买理茶宝成功
    public function buyUpdate($user_id,$recharge_num,$trade_no){
        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
        $cartInfo = Db::table('tea_recharge_cart')->where("user_id = $user_id AND recharge_num = '$recharge_num'")->find();
        //然后通过积分购物车表查询的充值表ID查询出对应的记录
        $rechargeInfo = Db::table('tea_recharge')->where('id='.$cartInfo['recharge_id'])->find();
        $rate = Db::table('tea_rate')->find();

        //判断用户是否选择理茶宝
        if(intval($cartInfo['is_gift'])==0){
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
        }else{
            $data = array(
                'user_id' => $user_id,
                'rec_money' => floatval($rechargeInfo['rec_money']),
                'subject' => $rechargeInfo['subject'],
                'body' => $rechargeInfo['body'],
                'rec_lev' => $rechargeInfo['rec_lev'],
                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),
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
                'is_gifts' =>1,
                'gift_inte'=>$rechargeInfo['gift'],
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
    //用户升级理茶宝产品,且支付成功
    public function asign($user_id,$recharge_num,$trade_no){

        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
        $cartInfo = Db::table('tea_recharge_cart')->where("user_id = $user_id AND recharge_num = '$recharge_num'")->find();

        //然后通过积分购物车表查询的充值表ID查询出对应的记录
        $rechargeInfo = Db::table('tea_recharge')->where('id='.$cartInfo['recharge_id'])->find();
        //查询出用户充值的记录表。根据id将升级的数据更新到表中
        $userRecharge=Db::table('tea_user_recharge')
            ->field('id')
            ->where('user_id='.$user_id)
            ->order('addtime desc')
            ->limit(1)->find();
        //判断用户升级是否也是选择的礼包
        if(intval($cartInfo['is_gift'])==0){
            //判断上次是否也是升级
            //升级理茶宝只需要更新几个字段
            $data = array(
                'rec_money' => $rechargeInfo['rec_money'],
                'trade_no'  =>$trade_no,
                'rec_lev' => $rechargeInfo['rec_lev'],
                'total_inte' => $rechargeInfo['total_inte'],
                'init_rates' => $rechargeInfo['init_rates'],
                'fir_rec' => $rechargeInfo['fir_rec'],
                'sec_rec' => $rechargeInfo['sec_rec'],
                'recharger_num'  =>$recharge_num
            );
            $resdata=Db::table('tea_user_recharge')->where('id='.$userRecharge['id'])->setField($data);
            $inte_obj = new Integral();
            $inte_obj->InteReturn($user_id );
            $inte_obj->grow_rate($user_id );
            $jifenInfo=Db::table('tea_integral')
                ->where("user_id=$user_id AND is_return=1")
                ->order('id desc')
                ->limit(1)->find();
            $datae=array(
                'total_sum'=>$rechargeInfo['total_inte'],
                'surplus_inte'=>$rechargeInfo['total_inte']-floatval($jifenInfo['back_inte'])
            );
            Db::table('tea_integral')->where('id='.$jifenInfo['id'])->setField($datae);
            return $resdata;
        }else{

        }

    }
    //用户通过线下支付。然后后台进行确认收款，$id是订单表的ID
    public function updateRec($id)
    {
        $a = rand(100,999);
        $b = rand(100,999);
        $num_order ="$a".time()."$b".'168';
        //首先判断用户是购买还是升级
        //先获取用户的订单表的用户ID以及购买的产品的ID来查询用户的购买记录已经将字段更新到用户充值表中
        $recinfo = Db::table('tea_recharge_cart')
            ->alias('a')
            ->where('a.id=' . $id)
            ->field('a.user_id,a.recharge_num,a.is_againbuy,b.*')
            ->join('tea_recharge b','a.recharge_id=b.id')
            ->find();
        $recharge_data=array(
            'pay_status'=>1,
            'trade_no'=>$num_order,
            'trade_beizhu'=>'交易成功'
        );
        Db::table('tea_recharge_cart')->where('recharge_num',$recinfo['recharge_num'])->setField($recharge_data);
        //①购买理茶宝,根据字段is_againbuy来判断
        if (intval($recinfo['is_againbuy']) == 0) {

            $resInfo=$this->buyUpdate(intval($recinfo['user_id']),$recinfo['recharge_num'],$num_order);
        }else{
            $resInfo=$this->asignManage(intval($recinfo['user_id']),$recinfo['recharge_num'],$num_order);
        }
        if ($resInfo) {
            return $resInfo;
        } else {
            return false;
        }

    }



    //升级理茶宝的新方法
    public function asignManage($user_id,$recharge_num,$trade_no){
        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
        $cartInfo = Db::table('tea_recharge_cart')->where("user_id = $user_id AND recharge_num = '$recharge_num'")->find();
        //然后通过积分购物车表查询的充值表ID查询出对应的记录
        $rechargeInfo = Db::table('tea_recharge')->where('id='.$cartInfo['recharge_id'])->find();
        //查询出用户充值的记录表。根据id将升级的数据更新到表中
        $userRecharge=Db::table('tea_user_recharge')
            //->field('id,gift_inte,is_gifts')
            ->where('user_id='.$user_id)
            ->order('addtime desc')
            ->limit(1)
            ->find();
        //首先判断用户是选择A套餐还是B套餐
        if(intval(intval($cartInfo['is_gift'])==0)){
            //判断上次是否也是升级
            //升级理茶宝只需要更新几个字段
            $data = array(
                'rec_money' => $rechargeInfo['rec_money'],
                'trade_no'  =>$trade_no,
                'rec_lev' => $rechargeInfo['rec_lev'],
                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($userRecharge['gift_inte']),  //用应返积分减去上次购买的赠送的积分，不管上次是购买还是升级
                'init_rates' => $rechargeInfo['init_rates'],
                'fir_rec' => $rechargeInfo['fir_rec'],
                'sec_rec' => $rechargeInfo['sec_rec'],
                'recharger_num'  =>$recharge_num,
                'gift_inte'=>$userRecharge['gift_inte'],
                'is_gifts'=>$userRecharge['is_gifts'],
                'year'=>date('Y'),
                'month'=>date('m'),
                'day'=>date('d'),

            );
            $resdata=Db::table('tea_user_recharge')->where('id',$userRecharge['id'])->setField($data);
            $inte_obj = new Integral();
            $inte_obj->InteReturn($user_id );
            $inte_obj->grow_rate($user_id );
            $jifenInfo=Db::table('tea_integral')
                ->where("user_id=$user_id AND is_return=1")
                ->order('id desc')
                ->limit(1)->find();
            /*更新积分表的字段是
            total_sum   需返还的积分等于这次应返积分减掉上次选择礼包的积分
           surplus_inte     剩余返还的积分等于现在剩余的应返积分再减掉上次选择礼包的积分
            */
            $datae=array(
                'total_sum'=>floatval($rechargeInfo['total_inte'])-floatval($userRecharge['gift_inte']),
                'surplus_inte'=>$rechargeInfo['total_inte']-floatval($jifenInfo['back_inte'])-floatval($userRecharge['gift_inte']),
            );
            Db::table('tea_integral')->where('id',$jifenInfo['id'])->setField($datae);
            return $resdata;
        }else{
            //选择了礼包的升级
            $data = array(
                'rec_money' => $rechargeInfo['rec_money'],
                'trade_no'  =>$trade_no,
                'rec_lev' => $rechargeInfo['rec_lev'],
                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),  //用应返积分减去上次购买的赠送的积分再减去选择礼包赠送的积分，不管上次是购买还是升级
                'init_rates' => $rechargeInfo['init_rates'],
                'fir_rec' => $rechargeInfo['fir_rec'],
                'sec_rec' => $rechargeInfo['sec_rec'],
                'recharger_num'  =>$recharge_num,
                'gift_inte'=>floatval($rechargeInfo['gift']),  //总礼包积分数等于这次选择礼包的积分数
                'is_gifts'=>1,
                'year'=>date('Y'),
                'month'=>date('m'),
                'day'=>date('d'),

            );
            $resdata=Db::table('tea_user_recharge')->where('id',$userRecharge['id'])->setField($data);
            $inte_obj = new Integral();
            $inte_obj->InteReturn($user_id );
            $inte_obj->grow_rate($user_id );
            $jifenInfo=Db::table('tea_integral')
                ->where("user_id=$user_id AND is_return=1")
                ->order('id desc')
                ->limit(1)->find();
            /*更新积分表的字段是
            total_sum   需返还的积分等于这次应返总积分减掉这次选择礼包的积分数再减掉上次选择礼包的积分
            surplus_inte     剩余返还的积分等于现在剩余的应返积分减掉这里选择礼包的积分数再减掉上次选择礼包的积分
            tea_ponit_inte   上次的总茶点数加上这次礼包应该返还的积分数
            */
            $datae=array(
                'total_sum'=>floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),
                'surplus_inte'=>$rechargeInfo['total_inte']-floatval($jifenInfo['back_inte'])-floatval($rechargeInfo['gift']),
                'tea_ponit_inte'=>floatval($jifenInfo['tea_ponit_inte']) + (floatval($rechargeInfo['gift'])-floatval($userRecharge['gift_inte']))
            );
            Db::table('tea_integral')->where('id',$jifenInfo['id'])->setField($datae);
            //更新积分表还要更新用户表
            $user=Db::table('tea_user')->field('tea_ponit_inte,tea_inte')->where('user_id',$user_id)->find();
            $user_data=array(
                'tea_ponit_inte'=>floatval($user['tea_ponit_inte'])+(floatval($rechargeInfo['gift'])-floatval($userRecharge['gift_inte']))
            );
            Db::table('tea_user')->where('user_id',$user_id)->setField($user_data);
        }
        return $resdata;
    }

    //----------------------------------------------------------------------
    //修改 10/31
    //用户下单购买股东权益
    public function buyManage($recharge_id,$user_id,$recharge_money,$store_id,$inte_type='')
    {
        $a = rand(100, 999);
        $b = rand(100, 999);
        $res = "$a" . time() . "$b" . '168';
        $contract_num = $this->contract_num($user_id,$store_id);
        $model=new RechargeCart(
            [
                'recharge_money'=>$recharge_money,
                'recharge_id' =>$recharge_id,
                'user_id'     =>$user_id,
                'rec_addtime' =>time(),
                'recharge_num'=>$res,
                'is_ceo'=>1,
                'store_id'=>$store_id,
                'is_gift'=>1,
                'inte_type'=>$inte_type,
                'contract_num'=>$contract_num
            ]
        );
        $model->save();
        session('user_contract_num',$contract_num);
        return $model->id;
    }

    //判断用户在同一门店,同一天内次数
    public function contract_num($user_id,$store_id){
        $time = strtotime(date('Y-m-d'));
        $rech_info = Db::name('recharge_cart')->field('contract_num,store_id')->where('user_id',$user_id)->where('store_id',$store_id)->where('order_addtime','>',$time)->select();
        if(empty($rech_info)){
            //生成新的合同编号  地区编号+时间+uid
            $qz = $this->get_contract($store_id);
            $contract_num = $qz."_".$store_id.date('Ymd').$user_id;
        }elseif (count($rech_info)==1){
            $contract_num = $rech_info[0]['contract_num'];
        }
        return $contract_num;
    }

    public function get_contract($store_id){
        $data=Db::connect(config('db_config2'))->name("offline_store")
            ->field('city')
            ->where("id",$store_id)
            ->where('is_confirm', 1)
            ->find();
        return $data['city'];
    }
}