<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/3/29
 * Time: 10:25
 */
namespace  app\tmvip\controller;
use think\Db;
class Integral{

    public function getProduct($user_id){

        return  Db::table('tea_user_recharge')->where('pay_status',1)->where('user_id',$user_id)->order('id desc')->limit(1)->find();
    }

    //发展奖比例
    public function grow_rate($user_id){
        $user_id=empty($user_id)? intval(input('get.user_id')) :$user_id;
        $datas=[
            'test'=>$user_id,
            'trade_status'=>'发展奖user_id'
        ];
        Db::table('tea_test')->insert($datas);
        // 取得系统设定的理茶宝分配配置
        $rate_info = $this->getRate();

        // 取得购买人自身产品信息
        $user_info = $this->getProduct($user_id);

        //判断用户的上级
        $user_parent_id = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
        if(intval($user_parent_id['parent_id'])!=0){


            // 获取上级信息
            $parent = $this->getParent($user_id);

            // 获得上级用户的当前integral表信息
            //$parent_integral = M('integral')->where('user_id='.$parent['id'])->Field('grow_rate')->find();
            $parent_integral = Db::table('tea_integral')->where('user_id',$parent['user_id'])->Field('grow_rate')->find();

            // 获得上级用户的购买金额
            //$parent_rec_money = M('user_recharge')->where('user_id='.$parent['id'])->Field('rec_money')->find();
            $parent_rec_money = Db::table('tea_user_recharge')->where('user_id',$parent['user_id'])->Field('rec_money')->find();
            //dump($parent['user_id']);die;
            // 取得上级所有直接下级用户id
            //$lower_users_id = M('user')->where('parent_id='.$parent['id'])->Field('id')->select();
            $lower_users_id = Db::connect(config('db_config2'))->name("users")->where('parent_id',$parent['user_id'])->Field('user_id')->select();

            //定义查询上级所有直接下级用户总额条件
            $id_arr['user_id'] = array('in');
            foreach ($lower_users_id as $v)
            {
                $temp_arr[] = $v['user_id'];
            }
            $id_arr['user_id'][] = $temp_arr;

            // 取得上级所有直接下级用户购买理茶宝总额
            //$lower_users_count_money = M('user_recharge')->where($id_arr)->SUM('rec_money');
            $lower_users_count_money = Db::table('tea_user_recharge')->where($id_arr)->SUM('rec_money');

            // 定义要增加上级用户固定释放增长值 = (下级总额 / 上级额度 * 理茶宝)

            $parent_grow_rate_new['grow_rate'] = intval($lower_users_count_money / $parent_rec_money['rec_money']) * $rate_info['dev_rate'];
            // $parent_grow_rate_new['grow_rate'] = $parent_grow_rate_new['grow_rate'] - $parent_integral['grow_rate'];
            // 最终用户固定释放增长值
            $parent_integral['grow_rate'] = $parent_grow_rate_new['grow_rate'];
            // 如果
            //M('Integral')->where('user_id='.$parent['id'])->save($parent_integral);
            Db::table('tea_integral')->where('user_id',$parent['user_id'])->update($parent_integral);

        }

    }

    public function grow_rate2($user_id){
        //echo 1;
        //$user_id = session('user_id');
        //$user_id = 5;
        //用户购买产品的信息
        //$data_sec_mer = "";



        $des = $this->getProduct($user_id);
        $info_fir = $this->getParent($user_id);
        //用户与上一级用户产品等级比较(发展奖）
        $dev_info = $this->getProductByUserId($info_fir['id']);

        if($dev_info){
            //上级的小于等于自己的等级
            if(intval($dev_info['rec_lev']) <= intval($des['rec_lev']) ){
                //更改上级用户的增长率
                $dev_res = $this->getInteByUserId($info_fir['id']);
                //dump($dev_res);
                $rate_info = $this->getRate();
                $dev_res['grow_rate'] = $dev_res['grow_rate']+$rate_info['dev_rate'];
                $sum = $dev_res['grow_rate']+$dev_info['init_rates'];
                if($sum>$rate_info['hight_rate']){
                    $dev_res['grow_rate'] = $dev_res['hight_rate']-$dev_info['init_rates'];
                }
                //dump($dev_res['grow_rate']);
                $this->updInte($dev_res['id'],$dev_res);
            }
        }
    }

    //更新用户积分表
    public function updInte($id,$res){
        $res['addtime'] = time();
        $res['year'] = date("Y");
        $res['month'] = date("m");
        $res['day'] = date("d");
        return  Db::table('tea_integral')
            ->where('id',$id)
            ->update($res);
    }

    //更改推荐人积分
    public function ChangeInte($info,$inte,$introduce,$menu,$recom,$recom_one,$recom_two,$grade,$grade_one,$grade_two,$other_id,$other_lev){
        //dump($recom_one);die;
        $res = $this->getInteByUserId($info['user_id']);
        //判断用户是否激活了产品
        if($res){
            //将剩余积分转成茶积分，茶点积分
            $rate_info = $this->getRate();
            if($res['surplus_inte']<=$inte){
                $res['tea_inte'] = $res['tea_inte'] + $res['surplus_inte'] * $rate_info['tea_inte_rate'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $res['surplus_inte'] * $rate_info['tea_score_rate'];
                $res['back_inte'] = $res['total_sum'];
                //$res['surplus_inte'] = 0;
                //改为已返完
                $res['is_return'] = 0;

                //更改推荐人购买记录
                Db::table('tea_user_recharge')->where('user_id',$info['id'])->order('id desc')->limit(1)->setField('is_return',' 0');
                //M('UserRecharge')->where('user_id='.$info['id'])->order('id desc')->limit(1)->setField('is_return',' 0');

                //记录
                $tea_inte = $res['surplus_inte'] * $rate_info['tea_inte_rate'];
                $tea_ponit_inte = $res['surplus_inte'] * $rate_info['tea_score_rate'];
                //$this->MakeLog(intval($info['id']),$res['surplus_inte'],$tea_inte,$tea_ponit_inte,0,$introduce,3,0,0,0,$other_lev,$res['total_sum'],0,$recom,$grade,$fix,$active,$other_id,$user_lev);

                //修改记录
                $tea_inte_ch = "+".$tea_inte;
                $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                $surplus_inte_ch = "-".$res['surplus_inte'];
                $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                $this->MakeLog(intval($info['user_id']),intval($res['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                    $menu,$res['total_sum'],0,1,$recom,$recom_one,$recom_two,$grade,$grade_one,
                    $grade_two,0,0,0,0,2,$other_id,$other_lev,$log_out_trade_no);
                $res['surplus_inte'] = 0;


                //修改用户表积分
                $users = Db::table('tea_user')->where('user_id',intval($info['user_id']))->find();
                if($users){
                    $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                    $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                    Db::table('tea_user')->where('user_id',intval($info['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                }

            }else{
                $res['surplus_inte'] = $res['surplus_inte']-$inte;
                $res['tea_inte'] = $res['tea_inte'] + $inte * $rate_info['tea_inte_rate'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte * $rate_info['tea_score_rate'];
                $res['back_inte'] = $res['back_inte']+$inte;

                //记录
                $tea_inte = $inte * $rate_info['tea_inte_rate'];
                $tea_ponit_inte = $inte * $rate_info['tea_score_rate'];
                //$this->MakeLog(intval($info['id']),$inte,$tea_inte,$tea_ponit_inte,0,$introduce,3,0,0,0,$other_lev,$res['total_sum'],$res['surplus_inte'],$recom,$grade,$fix,$active,$other_id,$user_lev);

                //修改记录
                $tea_inte_ch = "+".$tea_inte;
                $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                $inte_ch = "-".$inte;
                $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                $this->MakeLog(intval($info['user_id']),intval($res['lev']),$inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                    $menu,$res['total_sum'],$res['surplus_inte'],1,$recom,$recom_one,$recom_two,$grade,$grade_one,
                    $grade_two,0,0,0,0,2,$other_id,$other_lev,$log_out_trade_no);

                //修改用户表积分
                $users = Db::table('tea_user')->where('user_id',intval($info['user_id']))->find();
                if($users){
                    $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                    $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                    Db::table('tea_user')->where('user_id',intval($info['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                }

            }
            //修改积分
            $this->updInte($res['id'],$res);
        }

    }

    //获得利率信息问题
    public function getRate(){

        return Db::table('tea_rate')->order("id desc")->limit(1)->find();
    }

    //用户每日返还积分
    public function everyInte(){
        $rate_info = $this->getRate();      //分配比例

        $time = date('Y-m-d');
        //获取员工号
//获取员工号
        $gc_user = Db::connect(config('db_config2'))->name("users")->where("user_name like 'gcwc%' or user_name like 'smgc%'")->select();
        foreach($gc_user as $v_gc){
            $rule['last_time'] = $time;
            $rule['erevy_back_rate'] = 0;
            $rule['grow_rate'] = 0;
            $res['addtime'] = time();
            $res['year'] = date("Y");
            $res['month'] = date("m");
            $res['day'] = date("d");
            Db::table('tea_integral')->where('user_id = '.$v_gc['user_id'])->update($rule);
        }

        //获取未返完的积分信息  //只返还理茶宝的会员，不返还股东的积分
        //$data = M('Integral')->where('is_return = 1')->select();
        $data = Db::table("tea_integral")->where('is_return ', 1)->where('is_ceo',0)->select();  //修改只返还理茶宝会员的积分
        foreach($data as $k=>$v){
            // 如果最后释放时间戳小于当前日前时间戳
            if (strtotime($v['last_time']) < strtotime($time)){
                $every_rate = $v['erevy_back_rate']+$v['grow_rate'];    //总返还率 = 每日固定释放 + 增加释放

                //是否需要返还
                if($every_rate > 0){



                    //如果总返还率大于封顶值
                    if($every_rate > $rate_info['hight_rate']){
                        //当天要返还的积分
                        $inte = $v['total_sum'] * $rate_info['hight_rate'];     //返还积分 = 需返还的总积分 x 封顶值
                    }else{
                        $inte = $v['total_sum'] * $every_rate;  //返还积分 = 需返还的总积分 x 总返还率
                    }
                    //如果剩余积分小于等于需返还的积分
                    if($v['surplus_inte']<=$inte){
                        $v['tea_inte'] = $v['tea_inte'] + $v['surplus_inte'] * $rate_info['slow_tea_inte_rate'];    //茶券 = 茶券 + 剩余积分 x 静态茶券返还比例
                        $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $v['surplus_inte'] * $rate_info['slow_tea_score_rate'];   //茶点 = 茶点 + 剩余积分 x 静态茶点返还比例
                        //$v['surplus_inte'] = 0; //剩余积分=0
                        $v['back_inte'] = $v['total_sum'];
                        //改为已返完
                        $v['is_return'] = 0;
                        $v['last_time'] = $time;

                        //M('UserRecharge')->where('user_id='.$v['user_id'])->order('id desc')->limit(1)->setField('is_return',0);
                        Db::table('tea_user_recharge')->where('user_id',$v['user_id'])->order('id desc')->limit(1)->setField('is_return',' 0');

                        //记录
                        $tea_inte = ($v['surplus_inte'] * $rate_info['slow_tea_inte_rate']);
                        $tea_ponit_inte = $v['surplus_inte'] * $rate_info['slow_tea_score_rate'];

                        //修改记录
                        $tea_inte_ch = "+".$tea_inte;
                        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                        $surplus_inte_ch = "-".$v['surplus_inte'];
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';

                        $introduce = "每日释放";
                        $this->MakeLog($v['user_id'],intval($v['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                            4,$v['total_sum'],0,1,0,0,0,0,0,
                            0,0,0,0,0,1,0,0,$log_out_trade_no);
                        $v['surplus_inte'] = 0;

                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                            $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                        }

                    } else {
                        $v['surplus_inte'] = $v['surplus_inte']-$inte;
                        $v['tea_inte'] = $v['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];
                        $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];
                        $v['back_inte'] = $v['back_inte']+$inte;
                        $v['last_time'] = $time;

                        //记录
                        $tea_inte = $inte * $rate_info['slow_tea_inte_rate'];
                        $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];
                        //修改记录
                        $tea_inte_ch = "+".$tea_inte;
                        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                        $inte_ch = "-".$inte;
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "每日释放";
                        //$this->MakeLog($v['user_id'],$inte,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,0,0,$v['total_sum'],$v['surplus_inte'],0,0,1,0,0,0);
                        $this->MakeLog($v['user_id'],intval($v['lev']),$inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                            4,$v['total_sum'],$v['surplus_inte'],1,0,0,0,0,0,
                            0,0,0,0,0,1,0,0,$log_out_trade_no);


                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                            $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                        }

                    }
                    $this->updInte($v['id'],$v);

                }else{
                    $rule['last_time'] = $time;
                    $rule['erevy_back_rate'] = 0;
                    $rule['grow_rate'] = 0;
                    $rule['addtime'] = time();
                    $rule['year'] = date("Y");
                    $rule['month'] = date("m");
                    $rule['day'] = date("d");
                    Db::table('tea_integral')->where('id',$v['id'])->update($rule);
                }
            }
        }
    }


    public function _empty(){
        $this->error('错误');
    }


    //积分日志记录
    public function MakeLog($user_id,$user_lev,$surplus_inte,$tea_inte,$tea_ponit_inte,$reg_inte,$introduce,$menu
        ,$sum_inte,$have_inte,$use_type,$recom,$recom_one,$recom_two,$grade,$grade_one,$grade_two
        ,$recharge_money,$shopping,$exchange,$online,$fix,$other_id,$other_lev,$log_out_trade_no){
        //dump($recom_one);die;
        $time = time();
        //用户·id
        $log['user_id'] = $user_id;
        //自己等级(推荐时使用)
        $log['user_lev'] = $user_lev;
        //释放额度
        $log['surplus_inte'] = $surplus_inte;
        //茶券
        $log['tea_inte'] = $tea_inte;
        //茶点
        $log['tea_ponit_inte'] = $tea_ponit_inte;
        //茶籽
        $log['reg_inte'] = $reg_inte;
        //记录说明
        $log['introduce'] = $introduce;
        //积分记录分类
        $log['menu'] = $menu;
        //返还总积分
        $log['sum_inte'] = $sum_inte;
        //剩余积分
        $log['have_inte'] = $have_inte;
        //积分类型
        $log['use_type'] = $use_type;
        //推荐
        $log['recom'] = $recom;
        //一级推荐
        $log['recom_one'] = $recom_one;
        //二级推荐
        $log['recom_two'] = $recom_two;
        //绩效
        $log['grade'] = $grade;
        //一级绩效
        $log['grade_one'] = $grade_one;
        //二级绩效
        $log['grade_two'] = $grade_two;
        //充值金额
        $log['recharg_money'] = $recharge_money;
        //购物
        $log['shopping'] = $shopping;
        //兑换
        $log['exchange'] = $exchange;
        //线下
        $log['online'] = $online;
        //固定
        $log['fix'] = $fix;
        //来源id
        $log['other_id'] = $other_id;
        //用户级别
        $log['other_lev'] = $other_lev;
        //记录产生时间
        $log['addtime'] = $time;
        //记录产生时间
        $log['year'] = date("Y");
        //记录产生时间
        $log['month'] = date("m");
        //记录产生时间
        $log['day'] = date("d");
        $log['log_out_trade_no'] = $log_out_trade_no;
        //入库
        //M('IntegralLog')->add($log);
        Db::table("tea_integral_log")->insert($log);
    }

//最后一次更新
    public function InteReturnV($user_id)
    {
        $data_sec_mer='';
        $user_id= empty($user_id)? intval(input('get.user_id')) :$user_id;
        //a->b->c->d->e
        //1.获得用户自己的信息  e
        //$mm = Db::table('tea_user')->where('user_id=' . $user_id)->find();
        $mm = Db::connect(config('db_config2'))->name("users")->where('user_id=' . $user_id)->find();
        //用户购买的产品信息  e
        $des = $this->getProduct($user_id);
        //用户买产品充值的钱数   e
        //$data = M('RechargeCart')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
        $data = Db::table("tea_recharge_cart")->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();

        // 实际支付金额=实际支付金额+使用积分数
        $data['recharge_money'] = $data['recharge_money'] + $data['again_money'];
        //2.找到推荐人d
        $info_fir = $this->getParent($user_id);
        if (!$info_fir === false) {
            // d购买的产品
            $fir_golad = $this->getProductByUserId($info_fir['user_id']);
            if (!$fir_golad === false) {
                //计算一级的推荐奖  d  （d获得推一）
                $fir_ref = $data['recharge_money'] * $fir_golad['fir_rec'];
                //更改一级推荐人的剩余积分
                $introduce = $mm['user_name'] . "用户激活（一级推荐）";
                $this->ChangeInte($info_fir, $fir_ref, $introduce,3,1,$fir_ref,0,0,0,0,$des['user_id'],$des['rec_lev']);

                //3.找到推荐人c
                $info_sec = $this->getParent($info_fir['user_id']);
                $data_fir = $info_sec;
                //计算一级绩效
                if (!$data_fir === false) {
                    //c购买的产品
                    $sec_golad = $this->getProductByUserId($info_sec['user_id']);   //c 产品
                    if (!$sec_golad === false) {
                        //c拿 d 的一级绩效
                        $fir_mer = $fir_ref * $sec_golad['fir_merits'];
                        //更改一级绩效人的剩余积分
                        $introduce = $mm['user_name'] . "用户激活（一级绩效）";
                        //$this->ChangeInte($data_fir, $fir_mer, $introduce,3,0,0,0,1,$fir_mer,0,$des['user_id'],$des['rec_lev']);
                    }
                }

                //4.找到推荐人b
                if($data_fir){
                    $data_sec = $this->getParent($data_fir['user_id']);
                }else{
                    $data_sec = "";
                }

                //计算二级绩效
                if (!$data_sec === false) {
                    //b购买的产品
                    $data_sec_mer = $this->getProductByUserId($data_sec['user_id']);

                    if (!$data_sec_mer === false) {
                        //b拿 d 的二级绩效
                        $sec_mer = $fir_ref * $data_sec_mer['sec_merits'];
                        $introduce = $mm['user_name'] . "用户激活(二级绩效)";
                        //$this->ChangeInte($data_sec, $sec_mer, $introduce,3,0,0,0,1,0,$sec_mer,$des['user_id'],$des['rec_lev']);
                    }

                }
            }

        }
//die;
        if($info_fir){
            $info_sec = $this->getParent($info_fir['user_id']);
        }else{
            $info_sec = "";
        }

        //
        if (!$info_sec === false) {
            //c购买的产品
            $sec_golad = $this->getProductByUserId($info_sec['user_id']);   //c 产品
            //5.c获得二级推荐奖
            if (!$sec_golad === false) {
                //echo  $sec_golad;
                $sec_ref = $data['recharge_money'] * $sec_golad['sec_rec'];
                //更改二级推荐人的剩余积分
                $introduce = $mm['user_name'] . "用户激活（二级推荐）";
                $this->ChangeInte($info_sec, $sec_ref, $introduce,3,1,0,$sec_ref,0,0,0,$des['user_id'],$des['rec_lev']);

                //6. b获得 c 的 一绩
//                if(isset($data_sec_mer)){
//
//                }else{
//                    $data_sec_mer = "";
//                }
                if (!$data_sec_mer === false) {
                    $sec_mer_first = $sec_ref * $data_sec_mer['fir_merits'];
                    $introduce = $mm['user_name'] . "用户激活(一级绩效)";
                    //$this->ChangeInte($data_sec, $sec_mer_first, $introduce,3,0,0,0,1,$sec_mer_first,0,$des['user_id'],$des['rec_lev']);
                }
                if($data_sec){
                    $data_third = $this->getParent($data_sec['user_id']);
                }else{
                    $data_third = "";
                }
                //7.c二级绩效人详细信息  A
                //if($data_sec){
                //$data_third = $this->getParent($data_sec['user_id']);
                //}
//                if(isset($data_third)){
//
//                }else{
//                    $data_third = "";
//                }
                if (!$data_third === false) {
                    //a购买信息
                    $data_third_mer = $this->getProductByUserId($data_third['user_id']);
                    if (!$data_third_mer === false) {
                        //a 拿 c 二绩
                        $third_mer_first = $sec_ref * $data_third_mer['sec_merits'];
                        $introduce = $mm['user_name'] . "用户激活(二级绩效)";
                        //$this->ChangeInte($data_third, $third_mer_first, $introduce,3,0,0,0,1,0,$third_mer_first,$des['user_id'],$des['rec_lev']);
                    }
                }
            }
        }

        //上级是否需要升级
        //$this->user_parent_upgrade($user_id);
        //上级是否抽佣
        //$this->user_parent_money($user_id);
    }


    //通过用户id查找用户购买详情
    public function getProductByUserId($user_id){
        return  Db::table("tea_user_recharge")->where(['user_id'=>$user_id,'pay_status'=>1,'is_active'=>1,'is_return'=>1])->order('addtime desc')->limit(1)->find();
        //M('UserRecharge')->where(array('user_id'=>$user_id,'pay_status'=>1,'is_active'=>1,'is_return'=>1))->order('addtime desc')->limit(1)->find();
    }

    //通过用户id获得用户积分信息
    public function getInteByUserId($user_id){
        return Db::table("tea_integral")->where(['user_id'=>$user_id,'is_return'=>1])->order('id desc')->limit(1)->find();
        //M('Integral')->where(array('user_id'=>$user_id,'is_return'=>1))->order('id desc')->limit(1)->find();
    }

    //查找当前用户的上一级
    public function getParent($user_id){
        //$info = Db::table("tea_user")->field('parent_id')->where('id',$user_id)->find();
        $info = Db::connect(config('db_config2'))->name("users")->field('parent_id')->where('user_id',$user_id)->find();
        if($info){
            //$list = Db::table("tea_user")->where('id',$info['parent_id'])->find();
            if(intval($info['parent_id'] )== 0){
                $list = "";
            }else{
                $list = Db::connect(config('db_config2'))->name("users")->where('user_id',$info['parent_id'])->find();
            }
        }else{
            $list = "";
        }
        return $list;
    }


    //获取上级用户信息是否升级
    public function user_parent_upgrade($id){
        //$user_info= Db::table("tea_user")->where("id",$id)->find();
        //$user_parent = Db::table("tea_user")->where("id",intval($user_info['parent_id']))->find();
        $user_parent = $this->getParentInfo($id);

        //用户所有直推下级充值总额
        $son_info = Db::table("tea_user")
            ->field("sum(rec_money) as sum_rec_money,tea_user.*")
            ->where('parent_id',intval($user_parent['id']))
            ->join("tea_user_recharge","tea_user_recharge.user_id = tea_user.id")
            ->where("tea_user_recharge.pay_status",1)
            ->group("tea_user.id")
            ->select();

//        $parent_id = intval($user_parent['id']);
//        $sons_info = Db::query("select sum(rec_money) as sum_rec_money,tea_user.* from tea_user join tea_user_recharge on tea_user.id = tea_user_recharge.user_id
//                    where tea_user.parent_id = $parent_id and tea_user_recharge.pay_status = 1 group by  tea_user.id");
        $sum_rec_money = 0;
        foreach($son_info as $k => $v){
            $sum_rec_money += intval($v['sum_rec_money']);
        }
        //判断是否满足升级
        //上级用户的等级
        //判断用户是否有等级
        $rank_data = Db::table("tea_rank")->where("rank_id",intval($user_parent['rank_id']))->find();

        if(intval($rank_data['first'])==1){
            //为初始等级
            //获取升级临界值
            $rate_data = $this->getRate();
            if($sum_rec_money >= intval($rate_data['rank_start'])){
                //满足升级条件
                //更新上级用户等级
                //获取第一等级
                $rank_up_data = Db::query("select b.rank_id,b.rank_name,b.rank_peo from tea_rank as b join (select c.rank_id from tea_rank as c  where first = 1) as a where b.son_id = a.rank_id ");
                //dump($rank_up_data);die;
                //更新用户等级
                $res = Db::table("tea_user")->where("id",intval($user_parent['id']))->update(['rank_id'=>intval($rank_up_data[0]['rank_id'])]);
                //dump($res);
                //判断用户上一级的上一级是否需要升级
                //判断每一级人数
                if($res){
                    $this->user_rank_info(intval($user_parent['id']));
                }
            }
        }
    }

    //判断每一级人数
    public function user_rank_info($user_id){
        $user_info = Db::table("tea_user")->where("id",$user_id)->find();
        //dump($user_info);die;
        if($user_info['parent_id']==0){
            return false;
        }else{
            //获取用户上一级
            $parent_info = Db::table("tea_user")->where("id",intval($user_info['parent_id']))->find();
            //获取上级用户直推用户
            //$tui_info = Db::table("tea_user")->where("parent_id")->select();
            $id = intval($parent_info['id']);
            //$user_id = intval($parent_info['id']);
            //获取每个段位的人数
            $tui_info =  Db::query("select rank_name,tea_user.rank_id,rank_peo,count(tea_rank.rank_id) as count from tea_user join tea_rank on tea_rank.rank_id = tea_user.rank_id where parent_id = $id group by tea_user.rank_id ");
            //dump($tui_info);die;
            //判断用户的等级
            $parent_rank_id = intval($parent_info['rank_id']);
            foreach ($tui_info as $k => $v){
                if(intval($v['rank_id']) == $parent_rank_id && intval($v['count']) >= intval($v['rank_peo'])){
                    //升级
                    $rank_data = Db::table("tea_rank")->where("son_id",$parent_rank_id)->find();
                    //更新用户等级
                    $rank_data_id = intval($rank_data['rank_id']);
                    $up_res = Db::table("tea_user")->where("id",$id)->update(['rank_id'=>$rank_data_id]);
                    if($up_res){
                        //$user_info_parent = Db::tbale("tea_user")->where("id",$id)->find();
                        $this->user_rank_info($id);
                    }
                }
            }
        }
    }

    //获取上级用户信息是否抽佣
    public function user_parent_money($user_id){
        $user_info= Db::table("tea_user")->where("id",$user_id)->find();

        $user_parent = Db::table("tea_user")->where("id",intval($user_info['parent_id']))->find();

        $id = intval($user_parent['id']);
        //判断上一级的等级
        $rank_rate = Db::table("tea_rank")->where("rank_id",intval($user_parent['rank_id']))->find();
        if($rank_rate['first']==0 && intval($user_parent['rank_id']) >= intval($user_info['rank_id'])){
            //获取上级的总积分
            $integral_data = Db::query("select b.* from tea_integral as b join (select max(a.id) as id from tea_integral as a where a.user_id = $id ) as c where b.id in (c.id)" );
            if(!empty($integral_data)){
                if(intval($integral_data[0]['is_return'])==1){
                    //计算返还积分
                    $inte = intval($integral_data[0]['total_sum']) * $rank_rate['rank_rate'];
                    $introduce = "抽佣--".$user_info['username'];
                    //修改积分
                    //获得充值等级
                    $user_integral = Db::table("tea_integral")->where("user_id",$user_id)->order("id desc")->limit(1)->find();
                    $lev = intval($user_integral['lev']);
                    $this->ChangeInte($user_parent, $inte, $introduce,8,0,0,0,0,0,0,$user_id,$lev);
                }
            }
        }
    }

    //通过id获取上级用户信息
    public function getParentInfo($user_id){
        //$user_info= Db::table("tea_user")->where("user_id",$user_id)->find();
        //$user_parent = Db::table("tea_user")->where("id",intval($user_info['parent_id']))->find();
        $user_info   = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        $user_parent = Db::connect(config('db_config2'))->name("users")->where('user_id',intval($user_info['parent_id']))->find();
        return $user_parent;
    }



    //返利规则
    public function InteReturnA($user_id){
        //判断用户是否顶级
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        //获得用户购买信息
        $des = $this->getProduct($user_id);
        //用户买产品充值的钱数
        $data = Db::table("tea_recharge_cart")->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
        // 实际支付金额=实际支付金额+使用积分数
        $data['recharge_money'] = $data['recharge_money'] + $data['again_money'];
        if(intval($user_info['parent_id'])==0){
            //是顶级
        }else{
            //获得上级用户信息
            $info_fir = $this->getUserParent($user_id);
            //判断一级用户是否为股东
            $is_ceo = $this->UserCeo($info_fir['user_id']);
            if($is_ceo){
                //股东
                //获得用户购买信息
                $product_info = $this-> getProductInfo($info_fir['user_id']);
                $fir_ref = $data['recharge_money'] * $product_info['fir_rec'];
                //更改一级推荐人的剩余积分
                $introduce = $user_info['user_name'] . "用户激活（一级推荐）";
                //更改用户积分
                $this->getCeoIntegral($info_fir['user_id'],$fir_ref,$introduce,1,$fir_ref,0,$user_id,$des['rec_lev']);
                //找第二级

                $this->secone_change($info_fir,$user_info,$data,$user_id,$des);
            }else{
                //会员
                // d购买的产品
                $fir_golad = $this->getProductByUserId($info_fir['user_id']);
                if($fir_golad){
                    //计算一级的推荐奖  d  （d获得推一）
                    $fir_ref = $data['recharge_money'] * $fir_golad['fir_rec'];
                    //更改一级推荐人的剩余积分
                    $introduce = $user_info['user_name'] . "用户激活（一级推荐）";
                    //更改用户积分
                    $this->ChangeIntegral($info_fir['user_id'],$fir_ref,$introduce,1,$fir_ref,0,$user_id,$des['rec_lev']);
                    $this->secone_change($info_fir,$user_info,$data,$user_id,$des);
                }
            }

        }

    }

    //返利二级的判断
    public function secone_change($info_fir,$user_info,$data,$user_id,$des){
        //判断一级推荐人是否顶级
        if(intval($info_fir['parent_id'])==0){
            //是顶级
        }else{
            //第二级
            //获得上级用户信息
            $info_sec = $this->getUserParent($info_fir['user_id']);
            $is_ceo = $this->UserCeo($info_sec['user_id']);
            if($is_ceo){
                //股东
                //获得用户购买信息
                $product_info_sec = $this-> getProductInfo($info_sec['user_id']);
                $sec_ref = $data['recharge_money'] * $product_info_sec['sec_rec'];
                //更改一级推荐人的剩余积分
                $introduce = $user_info['user_name'] . "用户激活（二级推荐）";
                //更改用户积分
                $this->getCeoIntegral($info_sec['user_id'],$sec_ref,$introduce,1,0,$sec_ref,$user_id,$des['rec_lev']);
            }else{
                //会员
                $sec_golad = $this->getProductByUserId($info_sec['user_id']);  //c 产品
                if($sec_golad){
                    //计算二级的推荐奖  d  （d获得推一）
                    $sec_ref = $data['recharge_money'] * $sec_golad['sec_rec'];
                    //更改一级推荐人的剩余积分
                    $introduce = $user_info['user_name'] . "用户激活（二级推荐）";
                    //更改用户积分
                    $this->ChangeIntegral($info_sec['user_id'],$sec_ref,$introduce,1,0,$sec_ref,$user_id,$des['rec_lev']);
                }
            }
        }
    }


    //返利新规则  股东-股东-股东
    public function InteReturnB($user_id){
        //判断用户是否顶级
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        if(intval($user_info['parent_id'])==0){
            //是顶级
        }else{
            //获得用户购买信息
            $product_info = $this-> getProductInfo($user_id);
            $inte_one = $product_info['recharge_money'] * $product_info['fir_rec'];
            $inte_one = round($inte_one,2);
            //获得上级用户信息
            $user_parent_one = $this->getUserParent($user_id);
            //获得上级的积分信息
            $user_parent_data_one = $this->UserInfo($user_parent_one['user_id']);
            $p_inte_one = $user_parent_data_one['tea_inte'] + $inte_one;
            $this->ChangeUserInte($user_parent_one['user_id'],$p_inte_one);
            //记录
            $introduce = $user_info['user_name'] . "激活（一级推荐）";
            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
            $tea_inte_ch = "+".$inte_one;
            $tea_ponit_inte_ch = "+0";
            $this->MakeLog($user_parent_one['user_id'],0,"-".$inte_one,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                3,0,0,1,1,$inte_one,0,0,0,
                0,0,0,0,0,2,$user_id,0,$log_out_trade_no);


            //判断是否有上级用户
            if(intval($user_parent_one['parent_id'])==0){
                //没有
            }else{
                //获得用户购买信息
                $inte_two = $product_info['recharge_money'] * $product_info['sec_rec'];
                $inte_two = round($inte_two,2);

                //获得上级用户信息
                $user_parent_two = $this->getUserParent($user_parent_one['user_id']);
                //获得上级的积分信息
                $user_parent_data_two = $this->UserInfo($user_parent_two['user_id']);
                $p_inte_two = $user_parent_data_two['tea_inte'] + $inte_two;
                $this->ChangeUserInte($user_parent_two['user_id'],$p_inte_two);

                //记录
                $introduce = $user_info['user_name'] . "激活（二级推荐）";
                $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                $tea_inte_ch = "+".$inte_two;
                $tea_ponit_inte_ch = "+0";
                $this->MakeLog($user_parent_two['user_id'],0,"-".$inte_two,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                    3,0,0,1,1,0,$inte_two,0,0,
                    0,0,0,0,0,2,$user_id,0,$log_out_trade_no);
            }

        }
    }

    //返利最新规则
    public function InteReturn_new($user_id){
        $user_id=empty($user_id)? intval(input('get.user_id')) :$user_id;
        //判断用户是否为股东
        $is_ceo = $this->UserCeo($user_id);
        if($is_ceo){
            //股东
            $this->InteReturnB($user_id);
        }else{
            $this->InteReturnA($user_id);
        }
    }

    //获得用户购买信息
    public function getProductInfo($user_id){
        return  Db::table('tea_recharge_cart')
            ->join(" tea_new_recharge ", "tea_new_recharge.recharge_id = tea_recharge_cart.recharge_id")
            ->where('pay_status',1)
            ->where('is_ceo',1)
            ->where('user_id',$user_id)
            ->order('id desc')
            ->limit(1)
            ->find();
    }

    //更新积分
    public function ChangeUserInte($user_id,$inte){
        return Db::table("tea_user")->where("user_id",$user_id)->update(['tea_inte' => $inte]);
    }

    //获取用户信息
    public function UserInfo($user_id){
        return  Db::table("tea_user")->where("user_id",$user_id)->find();
    }

    //获取上级信息
    public function getUserParent($user_id){
        $user_info   = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        $user_parent = Db::connect(config('db_config2'))->name("users")->where('user_id',intval($user_info['parent_id']))->find();
        return $user_parent;
    }

    //判断用户是否为股东
    public function UserCeo($user_id){
        $user = Db::connect(config('db_config2'))->name("users")->field('user_rank')->where('user_id',$user_id)->find();
        if(intval($user['user_rank'])==10){
            //会员
            return false;
        }else{
            return true;
        }
    }

    //更改用户积分记录(会员)
    public function ChangeIntegral($user_id,$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev){
        $res = $this->getInteByUserId($user_id);
        if($res['surplus_inte']<=$inte){
            $res['tea_inte'] = $res['tea_inte'] + $res['surplus_inte'] ;
            $res['back_inte'] = $res['total_sum'];
            //$res['surplus_inte'] = 0;
            //改为已返完
            $res['is_return'] = 0;

            //更改推荐人购买记录
            Db::table('tea_user_recharge')->where('user_id',$user_id)->order('id desc')->limit(1)->setField('is_return',' 0');;

            //修改记录
            $tea_inte_ch = "+".$res['surplus_inte'];
            $tea_ponit_inte_ch = "+0";
            $surplus_inte_ch = "-".$res['surplus_inte'];
            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
            if(floatval($recom_one) > 0 ){
                $recom_one = $res['surplus_inte'];
            }
            if(floatval($recom_two) > 0 ){
                $recom_two = $res['surplus_inte'];
            }
            $this->MakeLog(intval($user_id),intval($res['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                3,$res['total_sum'],0,1,$recom,$recom_one,$recom_two,0,0,
                0,0,0,0,0,2,$other_id,$other_lev,$log_out_trade_no);
            $res['surplus_inte'] = 0;

            //修改用户表积分
            $user_data = $this->UserInfo($user_id);
            $inte_user = $user_data['tea_inte'] + $inte;
            $this->ChangeUserInte($user_id,$inte_user);

        }else{
            $res['tea_inte'] = $res['tea_inte'] + $inte ;
            $res['back_inte'] = $res['back_inte']+$inte;
            $res['surplus_inte'] = $res['surplus_inte']-$inte;

            //修改记录
            $tea_inte_ch = "+".$inte;
            $tea_ponit_inte_ch = "+0";
            $surplus_inte_ch = "-".$inte;
            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
            $this->MakeLog(intval($user_id),intval($res['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                3,$res['total_sum'],0,1,$recom,$recom_one,$recom_two,0,0,
                0,0,0,0,0,2,$other_id,$other_lev,$log_out_trade_no);

            //修改用户表积分
            $user_data = $this->UserInfo($user_id);
            $inte_user = $user_data['tea_inte'] + $inte;
            $this->ChangeUserInte($user_id,$inte_user);
        }
        //修改积分信息表
        $this->updInte($res['id'],$res);
    }

    //获取用户的积分记录(股东)
    public function getCeoIntegral($user_id,$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev){
        //获取所有未返完信息
        $inte_data = Db::table('tea_integral')->where('is_ceo',1)->where('user_id',$user_id)->select();
        //改变用户积分
        $this->chanageCeoIntegral($inte_data,$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev);

    }

    //改变用户积分信息
    public function chanageCeoIntegral($data,$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev){
        foreach($data as $k=>$v){
            if($inte>0){
                if($v['surplus_inte']<=$inte){
                    if(floatval($recom_one) > 0 ){
                        $recom_one = $v['surplus_inte'];
                    }
                    if(floatval($recom_two) > 0 ){
                        $recom_two = $v['surplus_inte'];
                    }
                    $this->ceo_inte_empty($v,$v['user_id'],$v['surplus_inte'],$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev);
                    $inte = $inte - $v['surplus_inte'];
                }else{
                    if(floatval($recom_one) > 0 ){
                        $recom_one = $inte;
                    }
                    if(floatval($recom_two) > 0 ){
                        $recom_two = $inte;
                    }
                    $this->ceo_inte_empty($v,$v['user_id'],$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev);
                    $inte = 0;
                }
            }else{
                break;
            }
        }
    }


    //积分不足
    public function ceo_inte_empty($res,$user_id,$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev){
        $res['tea_inte'] = $res['tea_inte'] + $res['surplus_inte'] ;
        $res['back_inte'] = $res['total_sum'];
        //$res['surplus_inte'] = 0;
        //改为已返完
        $res['is_return'] = 0;

        //修改记录
        $tea_inte_ch = "+".$res['surplus_inte'];
        $tea_ponit_inte_ch = "+0";
        $surplus_inte_ch = "-".$res['surplus_inte'];
        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';

        $this->MakeLog(intval($user_id),intval($res['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
            3,$res['total_sum'],0,1,$recom,$recom_one,$recom_two,0,0,
            0,0,0,0,0,2,$other_id,$other_lev,$log_out_trade_no);
        $res['surplus_inte'] = 0;

        //修改用户表积分
        $user_data = $this->UserInfo($user_id);
        $inte_user = $user_data['tea_inte'] + $inte;
        $this->ChangeUserInte($user_id,$inte_user);
        //修改积分信息表
        $this->updInte($res['id'],$res);
    }

    //积分多余
    public function ceo_inte_enough($res,$user_id,$inte,$introduce,$recom,$recom_one,$recom_two,$other_id,$other_lev){
        $res['tea_inte'] = $res['tea_inte'] + $inte ;
        $res['back_inte'] = $res['back_inte']+$inte;
        $res['surplus_inte'] = $res['surplus_inte']-$inte;

        //修改记录
        $tea_inte_ch = "+".$inte;
        $tea_ponit_inte_ch = "+0";
        $surplus_inte_ch = "-".$inte;
        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
        $this->MakeLog(intval($user_id),intval($res['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
            3,$res['total_sum'],0,1,$recom,$recom_one,$recom_two,0,0,
            0,0,0,0,0,2,$other_id,$other_lev,$log_out_trade_no);

        //修改用户表积分
        $user_data = $this->UserInfo($user_id);
        $inte_user = $user_data['tea_inte'] + $inte;
        $this->ChangeUserInte($user_id,$inte_user);
        //修改积分信息表
        $this->updInte($res['id'],$res);
    }


    //
    public function InteReturn($user_id){
        $user_id=empty($user_id)? intval(input('get.user_id')) :$user_id;
        //判断用户是否为股东
        //判断用户是否顶级
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        if(intval($user_info['parent_id'])==0){
            //是顶级
        }else{
            //获得用户购买信息
            $product_info = $this-> getProductInfo_new($user_id);
            $inte_one = $product_info['recharge_money'] * $product_info['fir_rec'];
            $inte_one = round($inte_one,2);
            //获得上级用户信息
            $user_parent_one = $this->getUserParent($user_id);
            //获得上级的积分信息
            $user_parent_data_one = $this->UserInfo($user_parent_one['user_id']);
            $p_inte_one = $user_parent_data_one['tea_inte'] + $inte_one;
            $this->ChangeUserInte($user_parent_one['user_id'],$p_inte_one);
            //记录
            if((int)$product_info['is_ceo']==1){
                $str = '股东';
            }else{
                $str = '会员';
            }
            $introduce = $user_info['user_name'] .$str. "激活（一级）";
            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
            $tea_inte_ch = "+".$inte_one;
            $tea_ponit_inte_ch = "+0";
            $this->MakeLog($user_parent_one['user_id'],0,"-".$inte_one,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                3,0,0,1,1,$inte_one,0,0,0,
                0,0,0,0,0,2,$user_id,0,$log_out_trade_no);


            //判断是否有上级用户
            if(intval($user_parent_one['parent_id'])==0){
                //没有
            }else{
                //获得用户购买信息
                $inte_two = $product_info['recharge_money'] * $product_info['sec_rec'];
                $inte_two = round($inte_two,2);

                //获得上级用户信息
                $user_parent_two = $this->getUserParent($user_parent_one['user_id']);
                //获得上级的积分信息
                $user_parent_data_two = $this->UserInfo($user_parent_two['user_id']);
                $p_inte_two = $user_parent_data_two['tea_inte'] + $inte_two;
                $this->ChangeUserInte($user_parent_two['user_id'],$p_inte_two);

                //记录
                $introduce = $user_info['user_name'] .$str. "激活（二级）";
                $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                $tea_inte_ch = "+".$inte_two;
                $tea_ponit_inte_ch = "+0";
                $this->MakeLog($user_parent_two['user_id'],0,"-".$inte_two,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                    3,0,0,1,1,0,$inte_two,0,0,
                    0,0,0,0,0,2,$user_id,0,$log_out_trade_no);
            }

        }

    }

    public function getProductInfo_new($user_id){
        $res =   Db::table('tea_recharge_cart')
            ->field("recharge_money,user_id,is_ceo,tea_new_recharge.fir_rec,tea_new_recharge.sec_rec,tea_recharge.fir_rec as 'fr',tea_recharge.sec_rec as 'sr'")
            ->join(" tea_new_recharge ", "tea_new_recharge.recharge_id = tea_recharge_cart.recharge_id and  is_ceo = 1",'left')
            ->join(" tea_recharge ", "tea_recharge.id = tea_recharge_cart.recharge_id ",'left')
            ->where('pay_status',1)
            ->where('user_id',$user_id)
            ->order('tea_recharge_cart.id desc')
            ->limit(1)
            ->find();
        //dump($res);
        if((int)$res['is_ceo']==1){
            unset($res['fr']);
            unset($res['sr']);
        }else{
            $res['fir_rec'] = $res['fr'];
            $res['sec_rec'] = $res['sr'];
        }
        return $res;
    }

}