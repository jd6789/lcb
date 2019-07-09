<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/9
 * Time: 11:36
 */
namespace app\tmvip\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\tmvip\Controller\Integral;
use app\tmvip\controller\Api;
class Recharge extends Common {


    //显示上边的栏
    public function index(){
        $menu = $this->user['menus'];
        //dump($menu);die;
        //获取当前url的权限id
        $request = Request::instance();
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }
    private $gufen=2;     //购买股东对应的股份，可以修改的
    protected $times=30;    //购买门店股东返还的次数
    //购买理茶宝
    //用户购买理茶宝成功
    private function buyUpdate($user_id,$recharge_num,$trade_no){
        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
        $cartInfo = Db::table('tea_recharge_cart')
            ->where("user_id", $user_id)
            ->where("recharge_num",$recharge_num)
            ->find();
        //然后通过积分购物车表查询的充值表ID查询出对应的记录
        $rechargeInfo = Db::table('tea_new_recharge')->where('recharge_id',$cartInfo['recharge_id'])->find();
        $rate = Db::table('tea_rate')->find();

        //判断用户是否选择理茶宝
        $data = array(
            'user_id' => $user_id,
            'rec_money' => $rechargeInfo['rec_money'],
            'subject' => $rechargeInfo['body'],
            'body' => $rechargeInfo['body'],
            'rec_lev' => $rechargeInfo['rec_lev'],
            'total_inte' => $rechargeInfo['total_inte'],
            'init_rates' => 0,
            'fir_rec' => $rechargeInfo['fir_rec'],
            'sec_rec' => $rechargeInfo['sec_rec'],
            'sec_merits' => $rechargeInfo['sec_merits'],
            'fir_merits' => $rechargeInfo['fir_merits'],
            'cap_rates' => $rate['hight_rate'],
            'reg_rec' => 0,
            'sec_reg_rec' => 0,
            'is_first' =>0,
            'addtime'   =>time(),
            'tea_inte_rate' => $rate['tea_inte_rate'],
            'tea_score_rate' => $rate['tea_score_rate'],
            'recharger_num'  =>$recharge_num,
            'trade_no'  =>$trade_no,
            'pay_status' =>1,
            'gift_inte'=>0,
            'is_gifts' =>0,
            'is_ceo' =>1,
            'year'=>date('Y'),
            'month'=>date('m'),
            'day'=>date('d'),
        );
        $inte_obj = new \app\tmvip\controller\Integral();
        $inte_obj->InteReturn($user_id );
        //exit('1');
        //$inte_obj->grow_rate($user_id );
        return Db::table('tea_user_recharge')->insert($data);
    }

    //开始线下支付
    public function updateRec111111(){
        if($this->request->isAjax()) {
            $id = intval(input('post.id'));
            //$id=12;
            $a = rand(100, 999);
            $b = rand(100, 999);
            $num_order = "$a" . time() . "$b" . '168';
            //首先判断用户是购买还是升级
            //先获取用户的订单表的用户ID以及购买的产品的ID来查询用户的购买记录已经将字段更新到用户充值表中
            $recinfo = Db::table('tea_recharge_cart')
                ->alias('a')
                ->where('a.id=' . $id)
                ->field('a.user_id,a.recharge_num,a.is_againbuy,b.*')
                ->join('tea_new_recharge b', 'a.recharge_id=b.recharge_id')
                ->find();
            $recharge_data = array(
                'pay_status' => 1,
                'trade_no' => $num_order,
                'trade_beizhu' => '交易成功'
            );
            Db::table('tea_recharge_cart')->where('recharge_num', $recinfo['recharge_num'])->setField($recharge_data);
            //①购买理茶宝,根据字段is_againbuy来判断
            $resInfo = $this->buyUpdate(intval($recinfo['user_id']), $recinfo['recharge_num'], $num_order);
            if ($resInfo) {
                return $resInfo;
            } else {
                return false;
            }
        }
    }

    //激活理茶宝
    private function active_lcb1111($id,$user_id,$store_id,$recharge_id)
    {
        $info = Db::table("tea_user_recharge")->field('id')->where('is_active =0')->where('user_id', $user_id)->order('id desc')->limit(1)->find();
        $recharg_info=Db::table('tea_new_recharge')->where('recharge_id',$recharge_id)->find();
        $res['total_sum'] = $recharg_info['total_inte'];    //总积分
        $res['tea_inte'] = 0;   //当前茶券
        $res['tea_ponit_inte'] = 0; //当前茶点
        $res['user_id'] = $user_id; //用户id
        $res['back_inte'] = 0;  //已返还积分
        $res['surplus_inte'] = $res['total_sum'];//剩余积分
        $res['last_time'] = date('Y-m-d');  //最后释放时间
        $res['is_return'] = 1;  //是否返还结束为未结束
        $res['addtime'] = time();//记录产生时间
        $res['year'] = date("Y"); //记录产生时间
        $res['month'] = date("m");//记录产生时间
        $res['day'] = date("d"); //记录产生时间
        $res['is_ceo'] = 1; //生成为股东的积分记录
        $time = time();
        //开启事务
        Db::startTrans();
        try{
            $res_insert = Db::table('tea_integral')->insertGetId($res); //插入积分表并返回主键值
            //将获取的积分表ID插入到股东门店表中
            $ceo_store=array(
                'user_id'=> $user_id,
                'store_id'=>$store_id,
                'inte_id'=>$res_insert,
                'gufen'=>$this->gufen
            );
            Db::table("tea_ceo_store")->insert($ceo_store);
            //将获取的积分表ID插入到新建数据库去
            $ceo_data=array(
                'integral_id'=>$res_insert,
                'thistime'=>date('Y-m-d'),
                'next_time'=>date('Y-m-d',strtotime('+1 month')),
                'back_inte'=>0,
                'tea_point_num'=>$recharg_info['sec_merits'],
                'tea_int_point_num'=>$recharg_info['fir_merits'],
                'months'=>intval($recharg_info['type_time']),
                'tims'=>0,
            );
            Db::table("tea_ceo_integral_log")->insert($ceo_data);
            //将用户理茶宝表字段修改为激活
            Db::table("tea_user_recharge")->where('id', $info['id'])->update(['is_active' => 1]);
            Db::table("tea_user_recharge")
                ->where('id', $info['id'])
                ->update(['rec_addtime' => $time]);
            Db::table("tea_recharge_cart")
                ->where('id', $id)
                ->update(['is_active' => 1]);
            //提交事务

            Db::commit();
            return true;
        }catch (\Exception $e){

            //事务回滚
            Db::rollback();
            return false;
        }
    }

    //获得利率信息问题
    private function getRate()
    {

        return Db::table('tea_rate')->order("id desc")->limit(1)->find();
    }

    //手动激活股东权益
    public function active_tea_treasure1111()
    {
        //购买记录id
        $recharge_cart_id = intval(input('post.id'));
        $recharge_carts=Db::table('tea_recharge_cart')->field('user_id,store_id,recharge_id')->where('id',$recharge_cart_id)->find();
        $res = $this->active_lcb($recharge_cart_id, intval($recharge_carts['user_id']),intval($recharge_carts['store_id']),intval($recharge_carts['recharge_id']));
        if ($res) {
            return json_encode(1);
        } else {
            return json_encode(0);
        }
    }

    //显示所有的股东理茶宝信息
    public function userrecharge(){
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " a.id > 0 AND a.is_ceo = 1 ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){
                $where .= " and a.user_id = ''";
            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " and a.user_id = $user_ids ";
                }else{
                    $where .= " and a.user_id in ($user_ids) ";
                }
            }
        }
        $data =  Db::table('tea_recharge_cart')
            ->alias('a')
            //->where("u.username like '%$user%'")
            ->field('a.id,a.rec_addtime,a.recharge_num,a.pay_status,b.rec_money,a.recharge_money,b.body,a.is_againbuy,a.user_id,a.is_active,a.contract_num')
            ->join('tea_new_recharge b',' a.recharge_id=b.recharge_id')
            ->where($where)
            ->order('rec_addtime desc')
            ->paginate('14');
        //->select();
        foreach($data->items() as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$user_id]['username'] = $user['user_name'];
            $list[$user_id]['tel'] = $user['mobile_phone'];
        }
        $list['info'] = $data;
        return view("userrecharge",['data'=>$list]);
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

    //显示已经激活的股东权益信息
    public function userintegral(){
        if($this->request->isGet()){
            return view('userintegral');
        }
        //return 0;
        $username = input('post.username');
        $page = intval(input('post.page'));
        $count = 15;
        if($page){
            $page = ($page-1)*$count;
        }else{
            $page = 0;
        }
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " a.is_ceo=1 ";

        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){
                $where .= " AND u.user_id = ''";
            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " AND u.user_id = $user_ids ";
                }else{
                    $where .= " AND u.user_id in ($user_ids) ";
                }
            }
        }

        //显示所有股东权益的积分列表
        $data =  Db::table('tea_integral')
            ->alias('a')
            ->field('a.*,s.store_id,u.tea_inte,u.tea_ponit_inte')
            ->distinct(true)
            ->join('tea_user u ',' a.user_id = u.user_id')
            ->join('tea_ceo_store s','s.inte_id=a.id')
            ->where($where)
            ->limit($page,$count)
            ->order('id desc')
            //->paginate('14');
            ->select();
        if(empty($data)){
            return 0;
        }
        foreach($data as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $sore_name = Db::connect(config('db_config2'))->name("offline_store")->field('stores_name')->where('id',intval($v['store_id']))->find();
            $data[$k]['username'] = $user['user_name'];
            $data[$k]['tel'] = $user['mobile_phone'];
            $data[$k]['stores_name'] = $sore_name['stores_name'];
        }
        $total =  Db::table('tea_integral')
            ->alias('a')
            ->field('a.*,s.store_id')
            ->distinct(true)
            ->join('tea_user u ',' a.user_id = u.user_id')
            ->join('tea_ceo_store s','s.inte_id=a.id')
            ->where($where)
            ->count('a.id');
            $list = ['data'=>$data,'total'=>$total];
        return $list;
    }

    //手动释放积分
    private function handinte(){
        //需要释放的茶券
        $tea_inte=floatval(input('post.tea_inte'));
        //需要释放的茶点
        $tea_ponit_inte=floatval(input('post.tea_ponit_inte'));
        //传递过来需要释放的订单ID
        $int_id=intval(input('post.int_id'));
        //查找当前ID 的积分详情
        $inte_info=Db::table('tea_integral')->where('id',$int_id)->find();
        //更新股东积分表的记录
        $data=array(
            'back_inte'=>floatval($inte_info['back_inte'])+$tea_inte+$tea_ponit_inte,
            'surplus_inte'=>floatval($inte_info['surplus_inte'])-($tea_inte+$tea_ponit_inte),
            'tea_inte'=>floatval($inte_info['tea_inte'])+$tea_inte,
            'tea_ponit_inte'=>floatval($inte_info['tea_ponit_inte'])+$tea_ponit_inte,
            'last_time'=>date('Y-m-d'),
            'year'=>date('Y'),
            'month'=>date('m'),
            'day'=>date('d'),
            'addtime'=>time(),
        );
        //插入积分记录表
        $log_data=array(
            'user_id'=>intval($inte_info['user_id']),
            'user_lev'=>0,
            'surplus_inte'=>'-'.($tea_inte+$tea_ponit_inte),
            'tea_inte'=>'-'.$tea_inte,
            'tea_ponit_inte'=>'-'.$tea_ponit_inte,
            'introduce'=>'手动返还积分'.($tea_inte+$tea_ponit_inte),
            'use_type'=>1,
            'fix'=>1,
            'addtime'=>time(),
            'year'=>date('Y'),
            'month'=>date('m'),
            'day'=>date('d'),
            'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
        );

        //开启事务
        Db::startTrans();
        try{
            //插入记录
            Db::table('tea_integral_log')->insert($log_data);
            //更新积分表
            Db::table('tea_integral')->where('id',$int_id)->setField($data);
            //更新用户表的信息
            Db::table('tea_user')->where('user_id',intval($inte_info['user_id']))->setInc('tea_ponit_inte',$tea_ponit_inte);
            Db::table('tea_user')->where('user_id',intval($inte_info['user_id']))->setInc('tea_inte',$tea_inte);
            // 提交事务
            Db::commit();
            return json(array('status'=>1));
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return json(array('status'=>0));
        }

    }

    //添加股东的规则信息
    public function add_recharge(){
        if(request()->isGet()){
            return view("add_recharge");
        }else{
            $data = input('post.');
            $data['fir_rec'] = $data['fir_rec']/100;
            $data['sec_rec'] = $data['sec_rec']/100;
            $data['fir_merits'] = $data['fir_merits']/100;
            $data['sec_merits'] = $data['sec_merits']/100;
            $data['gufen'] = $data['gufen']/100;
            $insert_data=Db::table('tea_new_recharge')->insert($data);
            if($insert_data){
                $this->success("理茶宝规则添加成功",'tmvip/recharge/recharge_info');
            }else{
                $this->error("理茶宝规则添加失败");
            }
        }
    }

    //股东规则展示
    public function recharge_info(){
        $info = Db::table("tea_new_recharge")->select();
        return view("recharge_info",['info'=>$info]);
    }
    //股东规则修改
    public function recharge_edit($recharge_id){
            $info=Db::table('tea_new_recharge')->where('recharge_id',$recharge_id)->find();
            $info['fir_rec'] = $info['fir_rec']*100;
            $info['sec_rec'] = $info['sec_rec']*100;
            $info['fir_merits'] = $info['fir_merits']*100;
            $info['sec_merits'] = $info['sec_merits']*100;
            $info['gufen'] = $info['gufen']*100;
            return view("recharge_edit",['info'=>$info]);
    }
    //理茶宝规则修改后保存
    public function save_recharge_edit(){
        $recharge_id = intval(input("post.recharge_id"));
        $data = input('post.');
        $data['fir_rec'] = $data['fir_rec']/100;
        $data['sec_rec'] = $data['sec_rec']/100;
        $data['fir_merits'] = $data['fir_merits']/100;
        $data['sec_merits'] = $data['sec_merits']/100;
        $data['gufen'] = $data['gufen']/100;
        $update_data= Db::table('tea_new_recharge')->where('recharge_id',$recharge_id)->update($data);
        if($update_data){
            $this->success("理茶宝规则编辑成功",'recharge_info');
        }else{
            $this->error("理茶宝规则编辑失败");
        }
    }
    //显示单个用户的历史返还记录
    public function oneRechargeLog(){
        if(request()->isAjax()){
            $id=intval(input('post.id'));
            $page = intval(input('post.page'));
            $count = 5;
            if($page){
                $page = ($page-1)*$count;
            }else{
                $page = 0;
            }
            //查找出用户的ID
            $userinte=Db::table('tea_integral')->field('user_id')->where('id',$id)->find();
            $data=Db::table('tea_integral_log')
                ->where('user_id',intval($userinte['user_id']))
                ->where('use_type',1)
                ->where('fix',1)
                ->field('surplus_inte,introduce,log_out_trade_no,year,month,day')
                ->limit($page,$count)
                ->order('id desc')
                ->select();
            $total = Db::table('tea_integral_log')
                ->where('user_id',intval($userinte['user_id']))
                ->where('use_type',1)
                ->where('fix',1)
                ->field('surplus_inte,introduce,log_out_trade_no,year,month,day')
                ->order('id desc')
                ->count('id');
            if($data){
                foreach ($data as $k=>$v){
                    $data[$k]['bb']=$v['year'].'-'.$v['month'].'-'.$v['day'];
                }
                $list = ['data'=>$data,'total'=>$total];
                return $list;
            }else{
                return 0;
            }
        }
    }
    //股东每月定时或者按周期返还返还积分
    public function timeGiveInte(){
        $time = date('Y-m-d');
        //查出所有的股东积分表   连表查
        //查出所有股东积分返还表    查出积分表内的剩余积分
        $subQuery=Db::query('SELECT max(b.int_id) as ids FROM tea_ceo_integral_log as b WHERE b.integral_id > 0 GROUP BY b.integral_id DESC ');

        foreach ($subQuery as $k=>$v){
            $out=Db::table('tea_ceo_integral_log')
                ->where('int_id',$v['ids'])
                ->find();
            if(intval($out['tims'])+1 <=$this->times)
                $integral_log[$k]=Db::table("tea_ceo_integral_log")
                    ->alias('a')
                    ->where('a.tims','<=',30)
                    ->where('int_id','in',$v['ids'])
                    ->field('a.*,b.user_id,b.id')
                    ->join('tea_integral b','a.integral_id=b.id')
                    ->find();
        }
        if(empty($integral_log)){
            return false;
        }
        foreach ($integral_log as $k=>$v ){
            if(intval($v['tims'])+1 <=$this->times)
            // 如果最后释放时间戳小于当前日前时间戳
            if (strtotime($v['last_time']) <= strtotime($time)){
                //修改用户表积分
                $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                if($users){
                    Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_ponit_inte',2000);
                }
                //形成记录
                $tea_ponit_inte ="+".'2000';
                $surplus_inte_ch = "-".'2000';
                $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                $introduce = "股东每月返还";
                $this->MakeLog($v['user_id'],0,$surplus_inte_ch,0,$tea_ponit_inte,0,$introduce,

                    4,0,0,1,0,0,0,0,0,

                    0,0,0,0,0,1,0,0,$log_out_trade_no);

                //更新积分表
                Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_ponit_inte',2000);
                Db::table('tea_integral')->where('id',intval($v['id']))->setInc('back_inte',2000);
                Db::table('tea_integral')->where('id',intval($v['id']))->setDec('surplus_inte',2000);
                $inte_data=array(
                    'last_time'=>time(),
                    'year'=>date("Y"),
                    'month'=>date("m"),
                    'day'=>date("d")
                );
                Db::table('tea_integral')->where('id',intval($v['id']))->update($inte_data);
                //添加
                $ceo_data=array(
                'integral_id'=>intval($v['integral_id']),
                'thistime'=>date('Y-m-d'),
                'next_time'=>date('Y-m-d',strtotime('+1 month')),
                'back_inte'=>2000,
                'tims'=>intval($v['tims'])+1,

            );
            Db::table("tea_ceo_integral_log")->insert($ceo_data);

            }
        }
    }

    //删除股东规则信息
    public function recharge_del($recharge_id){
        //获取传递过来的规则ID
        //$recharge_id=intval(input('get.recharge_id'));
        $del_data = Db::table('tea_new_recharge')->where('recharge_id',$recharge_id)->delete();
        if($del_data){
            $this->success("理茶宝规则删除成功");
        }else{
            $this->error("理茶宝规则删除失败");
        }
    }


    //积分日志记录

    private function MakeLog($user_id,$user_lev,$surplus_inte,$tea_inte,$tea_ponit_inte,$reg_inte,$introduce,$menu

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

    //显示股东的分红记录
    public function fenghonglog(){
        //获取用户的ID
        $id=intval(input('post.id'));
        $page = intval(input('post.page'));
        $count = 5;
        if($page){
            $page = ($page-1)*$count;
        }else{
            $page = 0;
        }
        //通过用户ID去分红记录表查找门店的 ID
        $ceo_bonus=Db::table('tea_ceo_bonus')->where('user_id',$id)
            ->limit($page,$count)
            ->select();
        if(!$ceo_bonus){
            return 0;
        }
        foreach ($ceo_bonus as $k => $v){
            //查找出门店的名字
            $ceo_bonus[$k]['storename'] = Db::connect(config('db_config2'))->name("offline_store")->field('stores_name')->where('id=' . intval($v['store_id']))->find();

        }
        $total = Db::table('tea_ceo_bonus')->where('user_id',$id)
            ->count('id');
        $list = ['data'=>$ceo_bonus,'total'=>$total];
        return $list;
    }


    //------------------------------------------------------------------------------------------------------------
    //修改    10/31
    //开始线下支付
    public function updateRec(){
        if($this->request->isAjax()) {
            $id = intval(input('post.id'));
            //$id=12;
            $a = rand(100, 999);
            $b = rand(100, 999);
            $num_order = "$a" . time() . "$b" . '168';
            //首先判断用户是购买还是升级
            //先获取用户的订单表的用户ID以及购买的产品的ID来查询用户的购买记录已经将字段更新到用户充值表中
            $recinfo = Db::table('tea_recharge_cart')
                ->alias('a')
                ->where('a.id=' . $id)
                ->field('a.user_id,a.recharge_num,a.is_againbuy,b.*,a.inte_type,a.store_id')
                ->join('tea_new_recharge b', 'a.recharge_id=b.recharge_id')
                ->find();
            $recharge_data = array(
                'pay_status' => 1,
                'trade_no' => $num_order,
                'trade_beizhu' => '交易成功'
            );

            //判断用户是都能购买
            if(intval($recinfo['inte_type'])==3){
                $store_id = intval($recinfo['store_id']);
                if(Db::table('tea_recharge_cart')->where('store_id',$store_id)->where('pay_status',1)->count()>=21) return false;
            }
            if(intval($recinfo['inte_type'])==2){
                $store_id = intval($recinfo['store_id']);
                $user_id = intval($recinfo['store_id']);
                if(Db::table('tea_recharge_cart')->where('user_id',$user_id)->where('store_id',$store_id)->where('pay_status',1)->count()>=2) return false;
            }


            Db::table('tea_recharge_cart')->where('recharge_num', $recinfo['recharge_num'])->setField($recharge_data);
            //①购买理茶宝,根据字段is_againbuy来判断
            $resInfo = $this->buyUpdate(intval($recinfo['user_id']), $recinfo['recharge_num'], $num_order);
            if ($resInfo) {
                return $resInfo;
            } else {
                return false;
            }
        }
    }


    //手动激活股东权益
    public function active_tea_treasure()
    {
        //购买记录id
        $recharge_cart_id = intval(input('post.id'));
        $recharge_carts=Db::table('tea_recharge_cart')->field('user_id,store_id,recharge_id,inte_type')->where('id',$recharge_cart_id)->find();
        $res = $this->active_lcb($recharge_cart_id, intval($recharge_carts['user_id']),intval($recharge_carts['store_id']),intval($recharge_carts['recharge_id']),intval($recharge_carts['inte_type']));
        if ($res) {
            return json_encode(1);
        } else {
            return json_encode(0);
        }
    }

    //激活理茶宝
    private function active_lcb($id,$user_id,$store_id,$recharge_id,$inte_type)
    {
        $info = Db::table("tea_user_recharge")->field('id')->where('is_active =0')->where('user_id', $user_id)->order('id desc')->limit(1)->find();
        $recharg_info=Db::table('tea_new_recharge')->where('recharge_id',$recharge_id)->find();
        $res['total_sum'] = $recharg_info['total_inte']-$recharg_info['gifts'];    //总积分
        $res['tea_inte'] = 0;   //当前茶券
        $res['tea_ponit_inte'] = $recharg_info['gifts']; //当前茶点
        $res['user_id'] = $user_id; //用户id
        $res['back_inte'] = 0;  //已返还积分
        $res['surplus_inte'] = $res['total_sum'];//剩余积分
        $res['last_time'] = date('Y-m-d');  //最后释放时间
        $res['is_return'] = 1;  //是否返还结束为未结束
        $res['addtime'] = time();//记录产生时间
        $res['year'] = date("Y"); //记录产生时间
        $res['month'] = date("m");//记录产生时间
        $res['day'] = date("d"); //记录产生时间
        $res['is_ceo'] = 1; //生成为股东的积分记录
        $res['type'] = $inte_type; //生成为股东的积分记录
        $time = time();

        //开启事务
        Db::startTrans();
        try{
            $res_insert = Db::table('tea_integral')->insertGetId($res); //插入积分表并返回主键值
            //将获取的积分表ID插入到股东门店表中
            $ceo_store=array(
                'user_id'=> $user_id,
                'store_id'=>$store_id,
                'inte_id'=>$res_insert,
                //'gufen'=>$this->gufen
                'gufen'=>floatval($recharg_info['gufen'])*100
            );
            Db::table("tea_ceo_store")->insert($ceo_store);
            //将获取的积分表ID插入到新建数据库去
            $ceo_data=array(
                'integral_id'=>$res_insert,
                'thistime'=>date('Y-m-d'),
                'next_time'=>date('Y-m-d',strtotime('+1 month')),
                'back_inte'=>0,
                'tea_point_num'=>$recharg_info['sec_merits'],
                'tea_int_point_num'=>$recharg_info['fir_merits'],
                'months'=>intval($recharg_info['type_time']),
                'tims'=>0,
            );
            Db::table("tea_ceo_integral_log")->insert($ceo_data);
            //将用户理茶宝表字段修改为激活
            Db::table("tea_user_recharge")->where('id', $info['id'])->update(['is_active' => 1]);
            Db::table("tea_user_recharge")
                ->where('id', $info['id'])
                ->update(['rec_addtime' => $time]);
            Db::table("tea_recharge_cart")
                ->where('id', $id)
                ->update(['is_active' => 1]);
            //提交事务
            Db::table("tea_user")->where('user_id', $user_id)->setInc('tea_ponit_inte',$recharg_info['gifts']);

            //形成记录
            $tea_ponit_inte ="+".$recharg_info['gifts'];
            $tea_inte ="+0";
            $surplus_inte_ch ="+".$recharg_info['gifts'];
            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
            $introduce = "购买股份一次性释放";
            $obj = new \app\tmvip\controller\Integral();
            $obj->MakeLog($user_id,0,$surplus_inte_ch,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,$log_out_trade_no);


            Db::commit();
            return true;
        }catch (\Exception $e){

            //事务回滚
            Db::rollback();
            return false;
        }
    }
}