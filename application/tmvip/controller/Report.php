<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/4/8
 * Time: 10:48
 */

namespace  app\tmvip\controller;
use app\tmvip\model\HomeUser;
use think\Controller;
use think\Db;
use think\Request;
use app\tmvip\controller\Api;
class Report extends Common{

    public function index(){

        $menu = $this->user['menus'];
        //dump($menu);die;
        //获取当前url的权限id
        $request = Request::instance();
        //$request->module(). '/' . $request->controller() . '/' . $request->action() ;
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }

    //推荐奖励
    public function recommend_reward(){
        //$where = 'menu = 3 and recom = 1';
        $homeuser_obj = new HomeUser();

        $where = " recom = 1 ";
        $username = input('post.username');
        if($username){
            //$user_info = M('user')->where("username='$username'")->find();
            $user_info = $homeuser_obj->getInfoByName($username);
            $id = $user_info['list_id'];
            $where .=" and user_id in ($id)";
        }
        //时间
        $howtime1 = input('post.howtime1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .= " and addtime >= $howtime1";
        }
        $howtime2 = input('post.howtime2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .= " and addtime <= $howtime2";
        }
        //获得具体的数据
        //$data = M('IntegralLog')->where($where)->order('addtime desc')->select();
        $data = $homeuser_obj->getIntegralLog($where);

        $list = $this->list_info($data);
        //$this->assign('dop',$list);

        //$this->display();
        return view("recommend_reward",['dop'=>$list]);
    }

    //绩效奖励
    public function performance_reward(){
        //$where = 'menu = 3 and grade = 1';
        $homeuser_obj = new HomeUser();

        $where = " grade = 1 ";
        $username = input('post.username');
        if($username){
            $user_info = $homeuser_obj->getInfoByName($username);
            $id = $user_info['list_id'];
            $where .=" and user_id in ($id)";
        }
        //时间
        $howtime1 = input('post.howtime1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .= " and addtime >= $howtime1";
        }
        $howtime2 = input('post.howtime2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .= " and addtime <= $howtime2";
        }
        //获得具体的数据
        //$data = M('IntegralLog')->where($where)->order('addtime desc')->select();
        $data = $homeuser_obj->getIntegralLog($where);

        $list = $this->list_info($data);
        //$this->assign('dop',$list);
        //$this->display();
        return view("performance_reward",['dop'=>$list]);
    }


    //id获取信息
    public function list_info($data){
        $homeuser_obj = new HomeUser();
        $sum = 0;
        $tea_inte = 0;
        $tea_ponit_inte = 0;
        foreach($data as $k=>$v){
            //通过id找用户信息
            //$info = $homeuser_obj->getUserById(intval($v['user_id']));
            //$data[$k]['name'] = $info['username'];
            $other = $homeuser_obj->getUserById(intval($v['other_id']));
            $data[$k]['other_name'] = $other['username'];
            $sum = $v['surplus_inte']+$sum;
            $tea_inte = $v['tea_inte']+$tea_inte;
            $tea_ponit_inte = $v['tea_ponit_inte']+$tea_ponit_inte;
        }
        $dop['sum'] =$sum;
        $dop['tea_inte'] =$tea_inte;
        $dop['tea_ponit_inte'] =$tea_ponit_inte;
        $dop['info'] = $data;
        return $dop;
    }

    //出局会员统计
    public function user_out(){
        $homeuser_obj = new HomeUser();
        $where =" is_return = 0";
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and user_id = $user_ids ";
            }else{
                $where .= " and user_id in ($user_ids) ";
            }
        }
//      时间
        $howtime1 = input('post.howtime1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .=" and addtime >= $howtime1 ";
        }

        $howtime2 = input('post.howtime2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .=" and addtime < $howtime2 ";
        }

        $data = Db::query("select * from tea_integral where id in (SELECT max(id) FROM `tea_integral` GROUP BY user_id) and $where");
            foreach($data as $k=>$v){
                //通过id找用户信息
                $info = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->find();
                $data[$k]['name'] = $info['user_name'];
            }
        return view("user_out",['data'=>$data]);

    }

    //释放统计
    public function release_tongji(){
        $homeuser_obj = new HomeUser();

        //$where = 'menu in (3,4)';
        $where = " fix in (1,2) ";
        $username = input('post.username');
        if($username){
            $user_info = $homeuser_obj->getInfoByName($username);
            $id = $user_info['list_id'];
            $where .=" and user_id in ($id)";
        }
        //时间
        $howtime1 = input('post.howtime1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .= " and addtime >= $howtime1";
        }
        $howtime2 = input('post.howtime2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .= " and addtime <= $howtime2";
        }
        $cha_inte = input('post.cha_inte');
        if($cha_inte ==1){
            $where .= " and fix = 1";
        }
        if($cha_inte ==2){
            $where .= " and fix = 2";
        }
        //获得具体的数据
        $data = $homeuser_obj->getIntegralLog($where);

        $sum = 0;
        $tea_inte = 0;
        $tea_ponit_inte = 0;
        foreach($data as $k=>$v){
            $sum = $v['surplus_inte']+$sum;
            $tea_inte = $v['tea_inte']+$tea_inte;
            $tea_ponit_inte = $v['tea_ponit_inte']+$tea_ponit_inte;
        }
        $dop['sum'] =$sum;
        $dop['tea_inte'] =$tea_inte;
        $dop['tea_ponit_inte'] =$tea_ponit_inte;
        $dop['info'] = $data;
        //dump($dop);
        return view("release_tongji",['dop'=>$dop]);
    }

    //兑换统计
    public function exchange_tongji()
    {
        $homeuser_obj = new HomeUser();
        //$where = " menu = 6 ";
        $where = 'exchange = 1';

        $username = I('post.username');
        if($username){
            $user_info = $homeuser_obj->getInfoByName($username);
            $id = $user_info['list_id'];
            $where .=" and user_id in ($id)";
        }
        //时间
        $howtime1 = I('post.howtime1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .= " and addtime >= $howtime1";
        }
        $howtime2 = I('post.howtime2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .= " and addtime <= $howtime2";
        }
        $cha_inte = I('post.cha_inte');
        if($cha_inte ==1){
            $where .= " and tea_ponit_inte = 0";
        }
        if($cha_inte==2){
            $where .= " and tea_inte = 0";
        }

        //dump($p);
        //获得具体的数据
        //$data = M('IntegralLog')->where($where)->order('addtime desc')->select();
        $data = $homeuser_obj->getIntegralLog($where);
        //$data = Db::query("select a.id,a.username,a.parent_id,a.rank_id,b.* from tea_user as a join tea_integral_log as b on a.id = b.user_id  where $where order by addtime desc");
        $sum = 0;
        $tea_inte = 0;
        $tea_ponit_inte = 0;
        foreach($data as $k=>$v){
            //通过id找用户信息
            $info = $homeuser_obj->getUserById(intval($v['user_id']));
            $data[$k]['name'] = $info['username'];
            $sum = $v['reg_inte']+$sum;
            $tea_inte = $v['tea_inte']+$tea_inte;
            $tea_ponit_inte = $v['tea_ponit_inte']+$tea_ponit_inte;
        }
        $dop['sum'] =$sum;
        $dop['tea_inte'] =$tea_inte;
        $dop['tea_ponit_inte'] =$tea_ponit_inte;
        return view("exchange_tongji",['dop'=>$dop]);
    }

    //会员升级记录
    //会员升级记录
    public function vip_again(){
        $homeuser_obj = new HomeUser();

        $where = ' pay_status = 1 and is_againbuy = 1';
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and tea_recharge_cart.user_id = $user_ids ";
            }else{
                $where .= " and tea_recharge_cart.user_id in ($user_ids) ";
            }
        }
        //时间
        $howtime1 = input('post.howtime1');
        if($howtime1){
            $howtime1 = strtotime($howtime1);
            $where .= " and rec_addtime >= $howtime1";
        }
        $howtime2 = input('post.howtime2');
        if($howtime2){
            $howtime2 = strtotime($howtime2);
            $where .= " and rec_addtime <= $howtime2";
        }
        $data = $homeuser_obj->recharge_cart_data($where);
        $sum = 0;
        $recharge_money = 0;
        $again_money = 0;
        foreach($data as $k=>$v){
            //通过id找用户信息
            $info = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->find();
            $data[$k]['name'] = $info['user_name'];
            $data[$k]['sum'] = $v['recharge_money']+$v['again_money'];
            $info_inte = $homeuser_obj->getRechargeById(intval($v['recharge_id']));
            //dump($info_inte);die;
            $data[$k]['new_lev'] =$info_inte['rec_lev'];
            $recharge_money = $v['recharge_money']+$recharge_money;
            $again_money = $v['again_money']+$again_money;
            $sum = $again_money+$recharge_money;
        }
        $dop['sum'] =$sum;
        $dop['recharge_money'] =$recharge_money;
        $dop['again_money'] =$again_money;
        $dop['info'] = $data;
        return view("vip_again",['dop'=>$dop]);
    }

    //会员统计
    public function user_tongji(){
        $homeuser_obj = new HomeUser();
        $where = 'a.id > 0';
        $username = I('post.username');
        if($username){
            //$user_info = M('user')->where("username='$username'")->find();
            $user_info = $homeuser_obj->getInfoByName($username);
            $id = $user_info['list_id'];
            $where .=" and a.id in ($id)";
        }
        $status = I('post.status');
        if($status){
            if($status == 4){
                $where .=" and c.is_return = 0";
            }
            if($status == 3){
                $where .=" and wait = 0";
            }
            if($status == 2){
                $where .=" and surplus_inte > 0 and c.is_return = 1";
            }
            if($status == 1){
                $where .=" and c.id is null and wait = 1";
            }
        }
        $chazi = I('post.chazi');
        if($chazi){
            $where .= " and reg_inte >= $chazi";
        }
        $chajuan = I('post.chajuan');
        if($chajuan){
            $where .= " and tea_inte >= $chajuan";
        }
        $chadian = I('post.chadian');
        if($chadian){
            $where .= " and tea_ponit_inte >= $chadian";
        }
        $e_du = I('post.e_du');
        if($e_du){
            $where .= " and surplus_inte >= $e_du";
        }
        //时间
        $zhuce_time1 = I('post.zhuce_time1');
        if($zhuce_time1){
            $zhuce_time1 = strtotime($zhuce_time1);
            $where .= " and first_time >= $zhuce_time1";
        }
        $zhuce_time2 = I('post.zhuce_time2');
        if($zhuce_time2){
            $zhuce_time2 = strtotime($zhuce_time2);
            $where .= " and first_time <= $zhuce_time2";
        }
        //时间
        $jihuo_time1 = I('post.jihuo_time1');
        if($jihuo_time1){
            $jihuo_time1 = strtotime($jihuo_time1);
            $where .= " and rec_addtime >= $jihuo_time1";
        }
        $jihuo_time2 = I('post.jihuo_time2');
        if($jihuo_time2){
            $jihuo_time2 = strtotime($jihuo_time2);
            $where .= " and rec_addtime <= $jihuo_time2";
        }
        $rank_id = I('post.rank_id');
        if($rank_id){
            $where .= " and a.rank_id = $rank_id";
        }

        //获得具体的数据
        $User = M('Integral');
        $data = $User->query("select * from jieyu_user as a left join (select * from jieyu_integral where id in (SELECT max(id) FROM 
               `jieyu_integral` GROUP BY user_id) ) as b on a.id = b.user_id left join jieyu_user_recharge as c on a.id = c.user_id where $where");
        foreach($data as $k=>$v){
            //通过id找用户信息
            //$info = D('user')->where('id='.$v['user_id'])->find();
            //$info = $homeuser_obj->getUserById(intval($v['user_id']));
            //$data[$k]['name'] = $info['username'];
            if($v['total_sum']==""){
                $data[$k]['total_sum'] = 0;
            }
            if($v['is_return']==0){
                $data[$k]['is_return'] = 0;
            }
            if($v['wait']==0){
                $data[$k]['wait'] = 0;
            }
        }
        //将需要的数据及分页导航都返回
        //return array('pageStr' => $show, 'data' => $data);
        //$this->assign('data',$data);
        //$this->display();
        return view("user_tongji",['data'=>$data]);
    }

    //显示用户的所有消费记录
    public function consumption_old(){
        $where ='u.user_id >0 ';
        //$data = input('post.');
//        if(empty($data)){
//            $username="";
//            $cate=0;
//            $money=0;
//        }else{
//            $username=$data['username'];
//            $cate=intval($data['cate']);
//            $money=intval($data['money']);
//        }
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and u.user_id = $user_ids ";
            }else{
                $where .= " and u.user_id in ($user_ids) ";
            }
        }
        $cate = input('post.cate');
        if(!$cate){
            $cate = 0;
        }
        $money = input('post.money');
        if(!$money){
            $money = 0;
        }


        if($cate==1){
            if($money==0){
                $where .=' AND  o.tea_inte >=0 AND o.tea_ponit_inte=0 ';
            }elseif($money==1){
                $where .=' AND  o.tea_inte BETWEEN 0 AND 99 AND o.tea_ponit_inte=0 ';
            }elseif($money==2){
                $where .=' AND o.tea_inte BETWEEN 100 AND 199 AND o.tea_ponit_inte=0 ';
            }elseif($money==3){
                $where .=' AND o.tea_inte BETWEEN 200 AND 499 AND o.tea_ponit_inte=0 ';
            }else{
                $where .=' AND o.tea_inte >=500 AND o.tea_ponit_inte=0 ';
            }
        }elseif($cate==2){
            if($money==0){
                $where .=' AND  o.tea_ponit_inte >=0 AND o.tea_ponit_inte=0 ';
            }elseif($money==1){
                $where .=' AND  o.tea_ponit_inte BETWEEN 0 AND 99 AND o.tea_inte=0 ';
            }elseif($money==2){
                $where .=' AND o.tea_ponit_inte BETWEEN 100 AND 199 AND o.tea_inte=0 ';
            }elseif($money==3){
                $where .=' AND o.tea_ponit_inte BETWEEN 200 AND 499 AND o.tea_inte=0 ';
            }else{
                $where .=' AND o.tea_ponit_inte >=500 AND o.tea_inte=0 ';
            }
        }elseif($cate==3){

            if($money==0){
                $where .=' AND  o.actual_price >=0 ';
            }elseif($money==1){
                $where .=' AND  o.actual_price BETWEEN 0 AND 99 ';
            }elseif($money==2){
                $where .=' AND o.actual_price BETWEEN 100 AND 199 ';
            }elseif($money==3){
                $where .=' AND o.actual_price BETWEEN 200 AND 499 ';
            }else{
                $where .=' AND o.actual_price >=500  ';
            }
        }elseif($cate==4){
            if($money==0){
                $where .=' AND  o.recharge_money >=0 ';
            }elseif($money==1){
                $where .=' AND  o.recharge_money BETWEEN 0 AND 99 ';
            }elseif($money==2){
                $where .=' AND o.recharge_money BETWEEN 100 AND 199 ';
            }elseif($money==3){
                $where .=' AND o.recharge_money BETWEEN 200 AND 499 ';
            }else{
                $where .=' AND o.recharge_money >=500  ';
            }
        }elseif($cate=0){
            $where .='';
        }
//        $data['order'] = Db::table('tea_user')
//            ->alias('u')
//            ->field('u.username,c.price,c.good_number,o.pay_way,o.order_addtime,g.goods_name')
//            ->where("u.username like '%$username%' AND o.pay_status=1 AND o.pay_way IN (1,2)")
//            ->join('tea_order o ', 'u.id=o.user_id'.$where)
//            ->join('tea_order_cart c ',' o.id=c.order_id')
//            ->join('tea_goods g ',' g.id=c.goods_id')
//            ->select();
//        $data['recharge']=Db::table('tea_user')
//            ->alias('u')
//            ->field('u.username,o.recharge_money,o.rec_addtime,r.body')
//            ->where("u.username like '%$username%' AND o.pay_status=1")
//            ->join('tea_recharge_cart o','u.id=o.user_id'.$where)
//            ->join('tea_recharge r ','r.id = o.recharge_id')
//            ->select();
//
//        $data['log']=Db::table('tea_user')
//            ->alias('u')
//            ->field('u.username,o.introduce,o.addtime,o.tea_inte,o.tea_ponit_inte')
//            ->where("u.username like '%$username%' AND o.menu IN (5,6)".$where)
//            ->join('tea_integral_log o','u.id=o.user_id')
//            ->select();
//
//        //显示所有购物支付宝微信支付的金额
//        $data['order_money']= Db::table('tea_user')
//            ->alias('u')
//            ->field('u.username,c.price,c.good_number,o.pay_way,o.order_addtime,g.goods_name')
//            ->where("u.username like '%$username%' AND o.pay_status=1 AND o.pay_way IN (1,2)".$where)
//            ->join('tea_order o ',' u.id=o.user_id')
//            ->join('tea_order_cart c ',' o.id=c.order_id')
//            ->join('tea_goods g ',' g.id=c.goods_id')
//            ->sum('o.actual_price');
//
//        //显示所有理茶宝支付宝微信支付的金额
//        $data['recharge_money']= Db::table('tea_user')
//            ->alias('u')
//            ->field('u.username,o.recharge_money,o.rec_addtime,r.body')
//            ->where("u.username like '%$username%' AND o.pay_status=1".$where)
//            ->join('tea_recharge_cart o ',' u.id=o.user_id')
//            ->join('tea_recharge r ',' r.id = o.recharge_id')
//            ->sum('o.recharge_money');

        $data['recharge']=Db::table('tea_user')
            ->alias('u')
            ->field('u.user_id,o.recharge_money,o.rec_addtime,r.body')
            ->where(" o.pay_status=1 and $where ")
            ->join('tea_recharge_cart o','u.id=o.user_id')
            ->join('tea_recharge r ','r.id = o.recharge_id')
            ->select();
         foreach($data['recharge'] as $k=>$v){
             $info = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->find();
             $data['recharge'][$k]['name'] = $info['user_name'];
         }
        $data['log']=Db::table('tea_user')
            ->alias('u')
            ->field('u.user_id,o.introduce,o.addtime,o.tea_inte,o.tea_ponit_inte')
            ->where(" o.menu IN (5,6) and ".$where)
            ->join('tea_integral_log o','u.id=o.user_id')
            ->select();
        foreach($data['log'] as $k=>$v){
            $info = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->find();
            $data['log'][$k]['name'] = $info['user_name'];
        }
        //显示所有购物支付宝微信支付的金额
//        $data['order_money']= Db::table('tea_user')
//            ->alias('u')
//            ->field('u.user_id,c.price,c.good_number,o.pay_way,o.order_addtime,g.goods_name')
//            ->where(" o.pay_status=1 AND o.pay_way IN (1,2)".$where)
//            ->join('tea_order o ',' u.id=o.user_id')
//            ->join('tea_order_cart c ',' o.id=c.order_id')
//            ->join('tea_goods g ',' g.id=c.goods_id')
//            ->sum('o.actual_price');
//        foreach($data['order_money'] as $k=>$v){
//            $info = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->find();
//            $data['order_money'][$k]['name'] = $info['user_name'];
//        }
        //显示所有理茶宝支付宝微信支付的金额
        $data['recharge_money']= Db::table('tea_user')
            ->alias('u')
            ->field('u.user_id,o.recharge_money,o.rec_addtime,r.body')
            ->where(" o.pay_status=1 and ".$where)
            ->join('tea_recharge_cart o ',' u.id=o.user_id')
            ->join('tea_recharge r ',' r.id = o.recharge_id')
            ->sum('o.recharge_money');

        //所有的茶点消费记录
        $data['rec_tea_inte'] ='';
        //所有的茶券消费记录
        $data['rec_tea_ponit_inte']='';
        foreach($data['log'] as $v){
            $data['rec_tea_inte'] +=$v['tea_inte'];
            $data['rec_tea_ponit_inte']+=$v['tea_ponit_inte'];
        }
        //dump($data);die;
        return view("consumption",['data'=>$data]);
    }

    //显示用户的所有消费记录
    public function consumption(){
        $where='id>0  and use_type=2 ';
        $username = input('post.username');
        if (!$username) {
            $username =" ";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($user_info ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " AND  user_id = $user_ids ";
            }else{
                $where .= " AND user_id in ($user_ids) ";
            }
        }
        $cate = intval(input('post.cate'));
        if($cate===1) $where .=' AND online=1 ';
        if($cate===2) $where .=' AND shopping=1 ';
        if($cate===3) $where .=' AND postal=1 ';
        //显示所有的记录
        $log_data=Db::name('integral_log')->where($where)->order('id desc')->paginate(14);
        foreach($log_data->items() as $k=> $v){
            $list[$v['user_id']]['username'] = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->value('user_name');
        }
        $list['log'] = $log_data;
        $list['rec_tea_inte']       =Db::name('integral_log')->where($where)->sum('tea_inte');
        $list['rec_tea_ponit_inte'] =Db::name('integral_log')->where($where)->sum('tea_ponit_inte');
        return view("consumption",['data'=>$list]);
    }

    //显示用户所有理茶宝的信息
    public function lichabao(){
        $where = " a.pay_status=1 AND a.is_ceo=0";
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and a.user_id = $user_ids ";
            }else{
                $where .= " and a.user_id in ($user_ids) ";
            }
        }

        $data= Db::table("tea_recharge_cart")
            ->alias('a')
            //->where("u.username like '%$username%' AND a.pay_status=1")
            ->field('a.id,a.user_id,a.rec_addtime,a.recharge_num,a.pay_status,b.rec_money,a.recharge_money,b.body,a.is_againbuy,a.again_money,b.rec_money')
            ->join('tea_recharge b ',' a.recharge_id=b.id')
            //->join('tea_user u ',' a.user_id = u.id')
            ->where($where)
            ->order('rec_addtime desc')->paginate(14);
            //->select();
        $list['money']=0;
        $list['again_money']=0;
        foreach($data->items() as $k=> $v){
            $info = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->find();
            $list[$v['user_id']]['username'] = $info['user_name'];
            $list[$v['user_id']]['again_money']=intval($v['again_money']);
            $list[$v['user_id']]['rec_money']=intval($v['rec_money']);
            $list[$v['user_id']]['recharge_money']=intval($v['recharge_money']);
            $list['money'] += $v['recharge_money'];
            $list['again_money'] += $v['again_money'];
        }

        $list['info'] = $data;
        //dump($list);die;
        return view("lichabao",['data'=>$list]);
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


    //显示所有用户购买理茶宝的充值记录
//    public function user_recharge_info(){
//        $username = input('post.username');
//        if (!$username) {
//            $username = "";
//        }
//        $api_obj = new Api();
//        $user_info = $api_obj->getUserInfo("",$username,'');
//        //获取用户信息
//        $where = " id > 0 ";
//        if($username ){
//            $user_ids = $this->getId($user_info['info']['list']);
//            if(strpos($user_ids,',')===false){
//                $where .= " and user_id = $user_ids ";
//            }else{
//                $where .= " and user_id in ($user_ids) ";
//            }
//        }
//        $userInfo = Db::table("tea_user_recharge")
//            ->alias('a')
//            //->where("b.username like '%$key%'")
//            ->field('a.id,a.rec_money,a.addtime,a.rec_addtime,a.is_return,a.recharger_num,a.total_inte')
//            //->join('jieyu_user b ON a.user_id=b.id')
//            ->where($where)
//            ->order('addtime desc')
//            ->select();
//        dump($userInfo);
//    }
    //显示用户积分记录
    public function integral_log(){
        $where="id > 0 ";
        $username = input('post.username');
        $time1 = strtotime(input('post.time1'));
        $time2 = strtotime(input('post.time2'));
        if(request()->isPost()){
            $log_name['username'] = $username;
            $log_name['time1'] = input('post.time1');
            $log_name['time2'] = input('post.time2');
            session('log_name',$log_name);
        }
        if(request()->isGet()){
            $username = session('log_name')['username']?session('log_name')['username']:'';
            $time1 = session('log_name')['time1']?strtotime(session('log_name')['time1']):'';
            $time2 = session('log_name')['time2']?strtotime(session('log_name')['time2']):'';
        }

        if (!$username) {
            $username = "";
        }
        if($time1){
            $where .=" and addtime > $time1 " ;
        }
        if($time2){
            $where .=" and addtime < $time2 + 24*3600 " ;
        }
        //dump($where);
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){

            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " and user_id = $user_ids ";
                }else{
                    $where .= " and user_id in ($user_ids) ";
                }
            }

        }
        $log_data=Db::name('integral_log')
            ->field('id,user_id,tea_inte,tea_ponit_inte,introduce,times,online')
            ->where($where)
            ->order('id desc')
            ->paginate(14);
        foreach($log_data->items() as $k=> $v){
            $list[$v['user_id']]['username'] = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->value('user_name');
        }

        $list['info'] = $log_data;
        return view('integral_log',['data'=>$list]);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function integral_log_excel(){
        $where="id > 0 ";
        $username = urldecode(input('get.username', "utf-8"));
        $time1 = strtotime(input('get.time1'));
        $time2 = strtotime(input('get.time2'));
        if (!$username) {
            $username = "";
        }

        if($time1){
            $where .=" and addtime > $time1 " ;
        }
        if($time2){
            $where .=" and addtime < $time2 + 24*3600 " ;
        }


        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){

            }else {
                if (strpos($user_ids, ',') === false) {
                    $where .= " and user_id = $user_ids ";
                    $log_data = $this->get_log($where);
                    $name = Db::connect(config('db_config2'))->name("users")->where("user_id", $user_ids)->value('user_name');
                    $filename = $name . '积分记录' . input('get.time1')."--".input('get.time1');
                    $header = array('会员编号','茶点','茶券','说明','注册时间');
                    $index = array('id','tea_ponit_inte','tea_inte','introduce','times');
                } else {
                    //$where .= " and user_id in ($user_ids) ";
                    //$this->error('aaa')
                }
            }
        }else{
            if($username && $time1 && $time2 ){
                $log_data=Db::name('integral_log')
                    ->field('id,user_id,tea_inte,tea_ponit_inte,introduce,times,online')
                    ->where($where)
                    ->order('id desc')
                    ->select();
            }else{
                $log_data=Db::name('integral_log')
                    ->field('id,user_id,tea_inte,tea_ponit_inte,introduce,times,online')
                    ->where($where)
                    ->order('id desc')
                    ->limit(500)
                    ->select();
            }
            foreach($log_data as $k => $v){
                $log_data[$k]['user_name'] = Db::connect(config('db_config2'))->name("users")->where("user_id",intval($v['user_id']))->value('user_name');
            }

            $filename = '会员积分记录'.date('YmdHis');
            $header = array('会员编号','会员名称','茶点','茶券','说明','注册时间');
            $index = array('id','user_name','tea_ponit_inte','tea_inte','introduce','times');

        }


        //会员记录
        $this->createtable($log_data,$filename,$header,$index);


    }

    //获取日志
    public function get_log($where){
        $log_data=Db::name('integral_log')
            ->field('id,user_id,tea_inte,tea_ponit_inte,introduce,times,online')
            ->where($where)
            ->select();
        return $log_data;
    }

    //生成excel
    protected function createtable($list,$filename,$header=array(),$index = array()){
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=".$filename.".xls");
        //header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $teble_header = implode("\t",$header);
        $strexport = $teble_header."\r";
        foreach ($list as $row){
            foreach($index as $val){
                $strexport.=$row[$val]."\t";
            }
            $strexport.="\r";

        }
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
        exit($strexport);
    }



}