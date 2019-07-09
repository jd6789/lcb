<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/3/30
 * Time: 18:33
 */
namespace  app\tmvip\controller;
use app\tmvip\model\HomeUser;
//use app\tmvip\controller\Common;
use think\Db;
use think\Request;
use app\tmvip\controller\Api;

class User extends Common {

    //实例化模型
    public function homeuser(){
        //$obj = HomeUser::getInstance();
        $obj = new HomeUser();
        return $obj;
    }

     //会员详情
    public function user_detailsss(){
        $obj = new HomeUser();

        //dump($list);die;
        //$where = " tea_user.id > 0 ";
        $username = input('post.user');
        if(!$username){
            $username = "";
        }
        $tel = input("post.tel");
        if(!$tel){
            $tel = "";
        }
        //$data_num = $obj->getNumber($where);
        $data_num = $obj->user_detail($username,$tel);

        // ->paginate(5, false, ['var_page'=>'p']);
//            $data = Db::query("select id,username,parent_id,t.money from tea_user join (select sum(rec_money) as money,user_id
//                                   from tea_user_recharge where pay_status = 1 and user_id = 109) as t on t.user_id = tea_user.id; ");
//        select id,username,parent_id,t.money from tea_user  left join (select sum(rec_money) as money,user_id
//                                   from tea_user_recharge where pay_status = 1 GROUP BY user_id ) as t on  tea_user.id in (t.user_id)

        return view('user_detail',['data'=>$data_num]);


    }

    public function index(){
        $menu = $this->user['menus'];
        //dump($menu);die;
        //获取当前url的权限id
        $request = Request::instance();
        //$request->module(). '/' . $request->controller() . '/' . $request->action() ;
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        //dump($menu);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }

    //会员统计
    public function user_manage(){
        //$homeuser_obj = new HomeUser();
//        $username = input('post.username');
//        if($username){
//            //$user_info = M('user')->where("username='$username'")->find();
//            $user_info = $homeuser_obj->getInfoByName($username);
//            $id = $user_info['list_id'];
//            $where .=" and a.user_id in ($id)";
//        }

        $api_obj = new Api();
        $where = ' tea_user.user_id > 0 ';
        $username = input('post.user');
//        if (!$username) {
//            $username = "";
//        }
        $tel = input("post.tel");
//        if (!$tel) {
//            $tel = "";
//        }
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$username,$tel);
        //获取用户信息
        if($username || $tel){
            $user_ids = $this->getId($user_info['info']['list']);
            //$where = " and tea_user.user_id in ($user_ids) ";

            if(!empty($user_ids)){
                if(strpos($user_ids,',')===false){
                    $where .= " and tea_user.user_id = $user_ids ";
                }else{
                    $where .= " and  tea_user.user_id in ($user_ids) ";
                }
            }else{
                //$where .= " tea_user.user_id ='' ";
            }
        }
        //dump($username );
        //获取用户信息
//        if($username || $tel){
//            $user_ids = $this->getId($user_info['info']['list']);
//            $where = " tea_user.user_id in ($user_ids) ";
//        }else{
//            $where = " tea_user.user_id > 0 ";
//        }
        $status = input('post.status');
        if($status){
            if($status == 4){
                $where .=" and c.is_return = 0 and b.total_sum is not null ";
            }
            if($status == 3){
                $where .=" and tea_user.wait = 0";
            }
            if($status == 2){
                $where .=" and surplus_inte > 0 and c.is_return = 1";
            }
            if($status == 1){
                $where .=" and b.total_sum is null and tea_user.wait = 1";
            }
        }
        $chazi = input('post.chazi');
        if($chazi){
            $where .= " and reg_inte >= $chazi";
        }
        $chajuan = input('post.chajuan');
        if($chajuan){
            $where .= " and tea_inte >= $chajuan";
        }
        $chadian = input('post.chadian');
        if($chadian){
            $where .= " and tea_ponit_inte >= $chadian";
        }
        $e_du = input('post.e_du');
        if($e_du){
            $where .= " and surplus_inte >= $e_du";
        }
        $rec_lev = intval(input('post.rec_lev'));
        if($rec_lev ){
            $where .= " and c.rec_lev = $rec_lev";
        }
        //dump($where);die;
        //获得具体的数据
//        $data = Db::query(" select a.user_id as userid ,a.wait,b.total_sum,b.user_id,b.surplus_inte,b.tea_inte,b.tea_ponit_inte,b.reg_inte,c.rec_lev,c.is_return
//    from tea_user as a left join ( select d.* from tea_integral d where d.id in
//               (SELECT max(id) as id FROM `tea_integral` GROUP BY user_id)) as b on a.user_id = b.user_id
//    left join (select f.* from tea_user_recharge f where f.id in
//               (SELECT max(id) as id FROM `tea_user_recharge` GROUP BY user_id)) as c on a.user_id = c.user_id where $where ");


//        $subQuery = Db::table('tea_user_recharge')
//            ->field("sum(rec_money) as money,user_id")
//            ->where("pay_status = 1 ")
//            ->group("user_id")
//            ->buildSql();
//
//        $data = Db::table("tea_user")
//            ->field("tea_user.id,tea_user.user_id,t.money")
//            //->join($subQuery.' t',"tea_user.user_id in (t.user_id)","left")//->select();
//            ->join($subQuery.' t',"t.user_id in (tea_user.user_id)","left")//->select();
//            ->where($where)
//            ->paginate(14);


        $subQuery = Db::table('tea_integral')->field("max(id) as idss")->group('user_id')->buildSql();
        $subQuery1 = Db::table('tea_integral')->join($subQuery.' t',"tea_integral.id in (t.idss)")->buildSql();

        $subQuery2 = Db::table('tea_user_recharge')->field("max(id) as ids")->group('user_id')->buildSql();
        $subQuery3 = Db::table('tea_user_recharge')->join($subQuery2.' f',"tea_user_recharge.id in (f.ids)")->buildSql();

        $data = Db::table("tea_user")
            ->field("tea_user.user_id as userid, tea_user.tea_inte,tea_user.tea_ponit_inte, tea_user.wait,b.total_sum,b.user_id,b.surplus_inte,b.reg_inte,c.rec_lev,c.is_return")
            ->join($subQuery1.' b',"b.user_id  = tea_user.user_id","left")//->select();
            ->join($subQuery3.' c',"c.user_id  = tea_user.user_id","left")
            ->where($where)
            ->paginate(14);
        
        //dump($data);die;
        foreach($data->items() as $k=>$v){
            //通过id找用户信息
            $user_id = intval($v['userid']);
            $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name')->where("user_id",$user_id)->find();
            $list[$user_id]['username'] = $user_info['user_name'];
            //dump($user_info['user_name']);
        }
        $list['info'] = $data;

        return view("user_manage",['data'=>$list]);
    }

    //开启会员
    public function user_use($user_id){
        //$id = intval(input("get.user_id"));
        $obj = new HomeUser();
        $data = $obj->user_use(intval($user_id));
        if($data){
            $this->success("success");
        }else{
            $this->error("error");
        }
    }

    //冻结会员
    public function user_wait($user_id){
        //$id = input("get.user_id");
        $obj = new HomeUser();
        $data = $obj->user_wait(intval($user_id));
        if($data){
            $this->success("success");
        }else{
            $this->error("error");
        }
    }

    //激活理茶宝
    public function active_lcb($id,$user_id){
        //$user_id = session('user_id');
        //$this->request->isPost()
            //订单id
            //$id = I('post.id');

            $rate_info = $this->getRate();
            //$info = M('UserRecharge')->where('is_active = 0 and user_id='.$user_id)->order('id desc')->limit(1)->find();
            $info = Db::table("tea_user_recharge")->where('is_active ', 0)->where( 'user_id',$user_id)->order('id desc')->limit(1)->find();

            //以前是否购买过
            //$data = M('Integral')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
            $data = Db::table("tea_integral")->where('user_id=' , $user_id)->order('id desc')->limit(1)->find();
            if ($data) {
                //已买过，获得上次购买记录数据叠加

                //判断是否释放结束************
                if ($data['is_return']==1) {
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

            //开始返积分
            $every_rate = $res['erevy_back_rate']+$res['grow_rate'];    //每日固定释放总值
            // 当释放总值大于固定释放封顶
            if($every_rate > $rate_info['hight_rate']){
                //当天要返还的积分
                $inte = $info['total_inte'] * $rate_info['hight_rate']; //充值返还总额 * 固定释放封顶返还率
            }else{
                $inte = $info['total_inte'] * $every_rate;  //充值返还总额 x 释放总值
            }

            $res['surplus_inte'] = $res['total_sum']-$inte;     //剩余积分 = 需返还总积分 - 当天返还积分
            $res['tea_inte'] = $res['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];     //茶券 = 当前茶券 + 当天返还总积分 x 静态积分茶券释放比例
            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];    // 茶点 = 当前茶点 + 当天返还总积分 x 静态积分茶点释放比例
            $res['back_inte'] = $res['back_inte']+$inte;    //已返还积分 = 已返还积分 - 当天返还总积分
            $res['last_time'] = date('Y-m-d');  //最后释放时间
            $res['is_return'] = 1;  //是否返还结束为未结束
            $res['lev'] = intval($info['rec_lev']);
            //记录产生时间
            $log['addtime'] = time();
            //记录产生时间
            $log['year'] = date("Y");
            //记录产生时间
            $log['month'] = date("m");
            //记录产生时间
            $log['day'] = date("d");

            //$res_insert = M('Integral')->add($res);
            $res_insert = Db::table('tea_integral')->insert($res);

            //生成记录
//            $time = time(); //生成时间
            //$log['user_id'] = $user_id; //用户id
//            $log['surplus_inte'] = $inte;   //返还总积分
//            $log['tea_inte'] = $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
//            $log['tea_ponit_inte'] = $inte * $rate_info['slow_tea_score_rate']; //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
//            $log['reg_inte'] = "0";     //注册积分
//            $log['addtime'] = $time;    //添加时间
//            $log['introduce'] = "激活释放"; //记录描述
//            $log['menu'] = 4;
//            ['recharge_money'] = 0; //充值
//            $log['exchange'] = 0;   //兑换
//            $log['shopping'] = 0;   //购物
//            M('IntegralLog')->add($log);

            $tea_inte =  $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
            $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];   //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
            $introduce = "激活释放";
            $log_obj = new \app\tmvip\controller\Integral();
//            $log_obj->MakeLogs($user_id,$inte,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,0,$info['rec_lev'],$res['total_sum'],$res['surplus_inte'],0,0,1,0,0,0);
            $log_obj->MakeLog($user_id,intval($info['rec_lev']),$inte,$tea_inte,$tea_ponit_inte,0,$introduce,
                4,$res['total_sum'],$res['surplus_inte'],1,0,0,0,0,0,0,0,0,0,0,1,$user_id,intval($info['rec_lev']));

//            MakeLog($user_id,$user_lev,$surplus_inte,$tea_inte,$tea_ponit_inte,$reg_inte,$introduce,$menu
//                ,$sum_inte,$have_inte,$use_type,$recom,$recom_one,$recom_two,$grade,$grade_one,$grade_two
//                ,$recharge_money,$shopping,$exchange,$online,$fix,$other_id,$other_lev)

            //$data_upd = M('UserRecharge')->where('id=' . $info['id'])->setField('is_active', '1');
            //$user_active = M('UserRecharge')->where('id=' . $info['id'])->find();

            $data_upd = Db::table("tea_user_recharge")->where('id', $info['id'])->update('is_active', '1');
            $user_active = Db::table("tea_user_recharge")->where('id', $info['id'])->select();
            $time = time();
            //M('UserRecharge')->where('id=' . $info['id'])->setField('rec_addtime', $time);
            Db::table("tea_user_recharge")->where('id', $info['id'])->update('rec_addtime', $time);
            if ($res_insert && $data_upd) {

                //积分消耗明细
//                $time = time();
//                $log['user_id'] = $user_id;
//                $log['surplus_inte'] = $inte;
//                $log['tea_inte'] = 0;
//                $log['tea_ponit_inte'] = 0;
//                $log['reg_inte'] =  $info['sec_reg_rec'];
//                $log['addtime'] = $time;
//                $log['introduce'] = "激活本人账户";
//                $log['menu'] = 2;
//                $log['recharge_money'] = $user_active['rec_money'];
//                $log['exchange'] = 0;
//                $log['shopping'] = 0;
//                M('IntegralLog')->add($log);

                $introduce = "激活本人账户";
                $log_obj = new \app\tmvip\controller\Integral();
 //               $log_obj->MakeLog($user_id,$inte,0,0,$info['sec_reg_rec'],$introduce,2,$user_active['rec_money'],0,0,$info['rec_lev'],$res['total_sum'],$res['surplus_inte'],0,0,1,0,0,0);
                $log_obj->MakeLog($user_id,intval($info['rec_lev']),$inte,$tea_inte,$tea_ponit_inte,0,$introduce,
                    2,$res['total_sum'],$res['surplus_inte'],1,0,0,0,0,0,0,0,0,0,0,1,$user_id,intval($info['rec_lev']));


//                M('RechargeCart')->where('id='.$id)->setField('is_active', '1');
                Db::table("tea_recharge_cart")->where('id', $id)->update('is_active', '1');
                return true;
            } else {
                return false;
            }

    }

    //获得利率信息问题
    public function getRate(){

        return Db::table('tea_rate')->order("id desc")->limit(1)->find();
    }

    //激活理茶宝
    public function active_tea_treasure()
    {
        //购买记录id
        $data = Request::instance()->post();
        $recharge_cart_id = intval($data['id']);
        if($data['user_id']!= ""){
            //帮别人激活理茶宝
            $user_id = intavl($data['user_id']);
        }else{
            //激活自己理茶宝
            $user_id = intval(session("user_id"));
        }
        $res = $this->active_lcb($recharge_cart_id,$user_id);
        if($res){
            return json_encode(1);
        }else{
            return json_encode(0);
        }
    }

    //修改用户资料(获得信息)
    public function edit_user_info($user_id){
        $obj = new HomeUser();
        //$user_id = intval(input("get.user_id"));
        //$user_info = $obj->getUserById($user_id);
        //dump($user_info);die;
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->field('user_id,user_name,nick_name,mobile_phone,reg_time')->find();
        $user2 = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->find();
        $user_info['real_name'] = $user2['real_name'];
        $user_info['self_num'] = $user2['self_num'];
        $user_info['bank_name'] = $user2['bank_name'];
        $user_info['bank_card'] = $user2['bank_card'];
        $user_info['bank_mobile'] = $user2['bank_mobile'];
        //获取地址信息
        $user_address = $obj->user_address($user_id);
        $user_info['info'] = $user_address;
        //dump($user_info);die;
        return view("user_edit",['data'=>$user_info]);
    }

    //保存用户修改信息
    public function save_user_info(){
        $obj = new HomeUser();

        $data = input("post.");
        $user_id = $data['user_id'];
        $address_id = $data['address_id'];
//        $list1 = array("self_num"=>$data['idcard'],"real_name"=>$data['real_name'],"bank_name"=>$data['bank_name'], "bank_card"=>$data['bank_card']);
        $real_arr = array();
        $real_arr['user_id'] = $user_id;
        $real_arr['self_num'] = $data['idcard'];
        $real_arr['real_name'] = $data['real_name'];
        $real_arr['bank_name'] = $data['bank_name'];
        $real_arr['bank_card'] = $data['bank_card'];
        $real_arr['bank_mobile'] = $data['mobile_phone'];
        //Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->update(['mobile_phone'=>$data['mobile_phone']]);

        $real_data = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->find();
        if (!empty($real_data)) {
            Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->update($real_arr);
        } else {
            Db::connect(config('db_config2'))->name("users_real")->insert($real_arr);
        }
        //$up_userinfo = $obj->save_user_info($user_id,$list1);

        $address_arr = array();
        $address_arr['user_id'] = $user_id;
        $address_arr['province'] = $data['province'];
        $address_arr['city'] = $data['city'];
        $address_arr['area'] = $data['area'];
        $address_arr['district'] = $data['district'];
        $address_arr['tel'] = $data['mobile_phone'];
        $address_arr['rec_name'] = $data['real_name'];
        $address_arr['is_use'] = 1;

//            $list2 = array('province' => $data['province'], 'city' => $data['city'], 'area'=>$data['area'], 'district'=>$data['district'],
//                'tel'=>$data['mobile_phone'], 'rec_name'=>$data['real_name'], 'is_use'=>1);
        if ($address_id > 0) {
            $obj->save_user_address($user_id, $address_arr);
        } else {
            $obj->save_user_address(0, $address_arr);
        }

        $this->success("修改成功",'tmvip/user/user_detail');

    }

    //查看用户信息
    public function one_user_info($user_id){
        $obj = new HomeUser();
        //$user_id = intval(input("get.user_id"));
        //$user_info = $obj->getUserById($user_id);
        //获取地址信息
        $user_address = $obj->user_address($user_id);

        //dump($user_info);die;
        //return view("one_user_info",['data'=>$user_info]);




        $user = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->field('user_name,nick_name,mobile_phone,reg_time')->find();
        $user2 = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->field('real_name,self_num,bank_name')->find();
        $rec_addtime = Db::table('tea_user_recharge')->where("user_id",$user_id)->value('rec_addtime');
        $user['real_name'] = $user2['real_name'];
        $user['self_num'] = $user2['self_num'];
        $user['bank_name'] = $user2['bank_name'];
        $user['rec_addtime'] = $rec_addtime;
        $user['info'] = $user_address;
//        $this->assign('data',$user);
//        return $this->fetch();;
        return view("one_user_info",['data'=>$user]);

    }



    //完善个人信息
    public function perfectinfo()
    {

            $user_id = cookie('admin')['user_id'];
            //$parent_id = trim(input('post.parent_id'));  //推荐人id
            $real_name = trim(input('post.real_name'));   //真实姓名

            //支付密码
            $pay_pwd = trim(input('post.pay_pwd1'));

            $idcard = trim(input('post.idcard'));   //身份证
            $bank = trim(input('post.bank'));    //银行卡号
            $bank_name = trim(input('post.bank_name'));   //开户行

            $p_user = trim(input('post.p_user'));  //推荐人用户名
            if($p_user){
                $p_info = Db::connect(config('db_config2'))->name("users")->where("user_name='$p_user'")->find();
                $parent_id = $p_info['user_id'];
            }else{
                $parent_id = 0;
            }
            $user_info = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->find();
            $tel = $user_info['mobile_phone'];
            $ec_salt = $user_info['ec_salt'];
            $pay_pwd = md5(md5($pay_pwd).$ec_salt);   //支付密码
            $user_address = input('post.user_address');
            $arr = explode('-',$user_address);
            $province = $arr[0];   //省
            $city = $arr[1];    //市
            $area = $arr[2];      //县 区
            $district = trim(input('post.district'));   //详细地址
            $res1 = Db::connect(config('db_config2'))->name("users_real")
                ->insert([
                    'user_id'=>$user_id,
                    'real_name' => $real_name,
                    'self_num' => $idcard,
                    'bank_name'=>$bank_name,
                    'bank_card'=>$bank,
                ]);
            $res2 = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->setField('parent_id',$parent_id);

            $address = array(
                'user_id'=>$user_id,
                'province'=>$province,
                'city'=>$city,
                'area'=>$area,
                'district'=>$district,
                'rec_name'=>$real_name,
                'is_use'=>1,
                'tel'=>$tel,
            );
            $res3 = Db::table('tea_user')->where('user_id',$user_id)->setField('pay_pwd',$pay_pwd);
            $res4 = Db::table('tea_user_address')->insert($address);
            if($res1 && $res4){
                return json(array('status'=>1));
            }else{
                return json(array('status'=>0));
            }
    }





    //重置登录密码
    public function reset_login_pwd($user_id){
        //$user_id = intval(input("get.user_id"));
        //$password = md5(md5("000000"));
        $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
        $password = md5(md5("000000").$user['ec_salt']);
        $up_res = $this->homeuser()->reset_login_pwd($user_id,$password);
        if($up_res){
            $this->success("重置成功，重置后为000000");
        }else{
            $this->error("重置出错，请重试。。");
        }
    }

    //重置支付密码
    public function reset_pay_pwd($user_id){
        //$user_id = intval(input("get.user_id"));
        $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
        $pay_pwd = md5(md5("123456").$user['ec_salt']);
        $up_res = $this->homeuser()->reset_pay_pwd($user_id,$pay_pwd);
        if($up_res){
            $this->success("重置成功，重置后为123456");
        }else{
            $this->error("重置出错，请重试。。");
        }
    }

    //理茶宝记录
    public function manage_trade(){
        $data = input("post.");
        $user_id = intval(session('user_id'));
        $where = " id > 0 and user_id = $user_id ";
        if($data['time1']){
            $time1 = strtotime($data['time1']);
            $where .= " and addtime >= $time1 ";
        }
        if($data['time2']){
            $time2 = strtotime($data['time2']);
            $where .= " and addtime < $time2 ";
        }
        if($data['$type_id']){
            $type_id = intval($data['$type_id']);
            $condition = $this->switch_trade_type($type_id);
            $where .= " and $condition ";
        }
        $integral_data = Db::table("tea_integral_log")->where($where)->select();

    }

    public function switch_trade_type($type_id){
        switch($type_id){
            case 0:
                $where = " fix in (1,2) ";
                break;
            case 1:
                $where = " menu = 2 ";
                break;
            case 2:
                //$where = " menu = 4 ";
                $where = " fix = 1 ";
                break;
            case 3:
                $where = " shopping <> 0 ";
                break;
        }
        return $where;
    }

    public function cv(){
        $url = "www.tmvip.cn/Api.php?method=taom.user.list.get&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&format=json";
        $data = $this->getHttp($url);
        dump($data);
    }

    //get请求
    public function getHttp($url){
        $ch=curl_init();
        //设置传输地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置以文件流形式输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //接收返回数据
        $data=curl_exec($ch);
        curl_close($ch);
        $jsonInfo=json_decode($data,true);
        return $jsonInfo;
    }


    //---------------------------------------------------------------------------------------------------------------------------
    //会员详情
    public function user_detail()
    {
        $obj = new HomeUser();
        $username = input('post.user');
        $tel = input("post.tel");
        $data_num = $obj->user_detail($username, $tel);
        //dump($data_num);die;
        return view('user_detail', ['data' => $data_num]);
    }

    //会员删除
    public function user_del(){
        $user_id = Request::instance()->param('user_id');

        $user_data = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
        if(!empty($user_data)){
            $this->error("不允许删除...");
        }else{
            $del_res = Db::table('tea_user')->where('user_id',$user_id)->delete();
            if($del_res){
                $this->success("删除成功");
            }else{
                $this->error("删除失败");
            }
        }
    }

    //钱包充值记录
    public function increase_wallet(){
        $obj = new HomeUser();
        $username = input('post.user');
        if (!$username) {
            $username = "";
        }
        $tel = input("post.tel");
        if (!$tel) {
            $tel = "";
        }
        $data  = $obj->increase_wallet($username,$tel);
        return view('increase_wallet', ['data' => $data]);
    }

    //钱包充值记录
    public function withdrawal_wallet(){
        $obj = new HomeUser();
        $username = input('post.user');
        if (!$username) {
            $username = "";
        }
        $tel = input("post.tel");
        if (!$tel) {
            $tel = "";
        }
        $data  = $obj->withdrawal_wallet($username,$tel);
        return view('withdrawal_wallet', ['data' => $data]);
    }

    //积分变动
    public function integral_change(){
        //$data = input("post.");
        //时间
        $where  = " id > 0 and use_type in (1,2) and wallet = 0 ";
        $username = input('post.user');
//        if (!$username) {
//            $username = "";
//        }
        $tel = input("post.tel");
//        if (!$tel) {
//            $tel = "";
//        }
        $api_obj = new Api();
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$username,$tel);
        //获取用户信息
        if($username || $tel){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and user_id = $user_ids ";
            }else{
                $where .= " and user_id in ($user_ids) ";
            }
        }
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
//        if($type==1){
//            $where .= " and use_type = (1,2) and wallet = 0";
//        }
        if($type==2){
            $where .= " and fix = 1 ";
        }
        if($type==3){
            $where .= " and recom = 1  ";
        }
        if($type==4){
            $where .= " and grade = 1  ";
        }
        if($type==5){
            $where .= " and shopping = 1  ";
        }
        if($type==6){
            $where .= " and online = 1  ";
        }
        //dump($where);
        $data = Db::table("tea_integral_log")->where($where)->paginate(10);;
        foreach($data->items() as $k => $v){
            //获取用户信息
            $user_info = Db::connect(config('db_config2'))->name("users")->where('user_id',intval($v['user_id']))->find();
            $list[$v['user_id']]['user_name'] = $user_info['user_name'];
            $list[$v['user_id']]['mobile_phone'] = $user_info['mobile_phone'];
        }
        //dump($data);die;
        $list['info'] = $data;
        return view('integral_change',['data'=>$list]);
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

    public function wallet_change(){
        $where  = " id > 0 and wallet in (1,2,3,4) ";
        $username = input('post.user');
        if (!$username) {
            $username = "";
        }
        $tel = input("post.tel");
        if (!$tel) {
            $tel = "";
        }
        $api_obj = new Api();
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$username,$tel);
        //获取用户信息
        if($username || $tel){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and user_id = $user_ids ";
            }else{
                $where .= " and user_id in ($user_ids) ";
            }
        }
        $type = intval(input('post.type'));
//        if($type==1){
//            $where .= " and use_type = 0 and wallet in (1,2,3,4)";
//        }
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
        //获取充值记录
        $wallet_data = Db::table("tea_integral_log")->field('user_id,introduce,surplus_inte,addtime,sum_inte')->where($where)->paginate(14);
        //用户信息
        $list = array();
        foreach($wallet_data->items() as $k => $v){
            $user_id = $v['user_id'];
            $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,mobile_phone')->where('user_id',$user_id)->find();
            $list[$user_id]['user_name'] = $user_info['user_name'];
            $list[$user_id]['mobile_phone'] = $user_info['mobile_phone'];
            //$list[$user_id]['sum_wallet'] = Db::table('tea_user')->where('user_id',$user_id)->find()['wallet'];
        }
        $list['info'] = $wallet_data;
        return view('wallet_change',['data'=>$list]);
    }

    public function integral_chart(){
        //dump($list);
        if(!request()->isAjax()){
            return view('integral_chart');
        }else{
            $where = "use_type = 1 ";
            $time1 = input('post.time1');
            //$time1 = "2018-04-24";
            if($time1){
                $time1 = strtotime($time1);
                $where .= " and addtime >= $time1 ";
            } else{
                $time1 = time()-7*24*3600;
                $where .= " and addtime >= $time1 ";
            }
            $time2 = input('post.time2');
            if($time2){
                $time2 = strtotime($time2);
                $where .= " and addtime < $time2 ";
            }
            //return json_encode(1);die;
            //每日释放总量，每日茶点总量，每日茶券总量
            $year_data = Db::table('tea_integral_log')->field('year')->where($where)->group('year')->select();
            foreach($year_data as $k => $v){
                $month = Db::table('tea_integral_log')->field('month')->where($where.' and year='.$v['year'])->group('month')->select();
                foreach($month as $k1 => $v1){
                    $day = Db::table('tea_integral_log')->field('day')->where($where.' and month='.$v1['month'])->group('day')->select();
                    foreach($day as $k2 => $v2){
                        $data['time'][]=$v['year'].'-'.$v1['month'].'-'.$v2['day'];
                        $data['surplus_inte'][] =Db::table('tea_integral_log')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day'])
                            ->sum('surplus_inte');
                        $data['tea_inte'][] =Db::table('tea_integral_log')
                            ->where("$where and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day'])
                            ->sum('tea_inte');
                        $data['tea_ponit_inte'][] =Db::table('tea_integral_log')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day'])
                            ->sum('tea_ponit_inte');
                        $list[$k][] = $data;
                    }
                }
            }
            if(!$year_data){
                $list = "";
            }
            $inte_data = Db::table('tea_integral_log')->where($where)->select();
            foreach($inte_data as $k3=>$v3){
                $user_id = $v3['user_id'];
                $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,mobile_phone')->where('user_id',$user_id)->find();
                $inte_data[$k3]['user_name'] = $user_info['user_name'];
            }
            //$list['info'] = $inte_data;
            return json_encode(array('list'=>$list,'list1'=>$inte_data));
        }

    }


    //每日购买理茶宝数量
    public function lcb_quantity_chart(){
        if(!request()->isAjax()){
            return view('lcb_quantity_chart');
        }else{
            $where = " pay_status = 1 ";
            $time1 = input('post.time1');
            //$time1 = "2018-04-24";
            if($time1){
                $time1 = strtotime($time1);
                $where .= " and addtime >= $time1 ";
            } else{
                $time1 = time()-7*24*3600;
                $where .= " and addtime >= $time1 ";
            }
            $time2 = input('post.time2');
            if($time2){
                $time2 = strtotime($time2);
                $where .= " and addtime < $time2 ";
            }
            //return json_encode(array('list'=>$where));die;
            //return json_encode(1);die;
            //每日释放总量，每日茶点总量，每日茶券总量
            $year_data = Db::table('tea_user_recharge')->field('year')->where($where)->group('year')->select();

            foreach($year_data as $k => $v){
                $month = Db::table('tea_user_recharge')->field('month')->where($where.' and year='.$v['year'])->group('month')->select();

                foreach($month as $k1 => $v1){
                    $day = Db::table('tea_user_recharge')->field('day')->where($where.' and month='.$v1['month'])->group('day')->select();

                    foreach($day as $k2 => $v2){

                        $data['time'][]=$v['year'].'-'.$v1['month'].'-'.$v2['day'];


                        $data['lev_one'][] =Db::table('tea_user_recharge')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and rec_lev = 1")
                            ->count('id');

                        //return json_encode(array('list'=>$data));die;
                        $data['lev_two'][] =Db::table('tea_user_recharge')
                            ->where("$where and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and rec_lev = 2")
                            ->count('id');
                        $data['lev_three'][] =Db::table('tea_user_recharge')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and rec_lev = 3")
                            ->count('id');
                        $data['lev_four'][] =Db::table('tea_user_recharge')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and rec_lev = 4")
                            ->count('id');
                        $data['lev_five'][] =Db::table('tea_user_recharge')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and rec_lev = 5")
                            ->count('id');
                        $list[$k][] = $data;
                    }
                }
            }

            if(!$year_data){
                $list = "";
            }

            $inte_data = Db::table('tea_user_recharge')->where($where)->select();
            foreach($inte_data as $k3=>$v3){
                $user_id = $v3['user_id'];
                $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,mobile_phone')->where('user_id',$user_id)->find();
                $inte_data[$k3]['user_name'] = $user_info['user_name'];
            }

            //$list['info'] = $inte_data;
            //dump($list);die;
            return json_encode(array('list'=>$list,'list1'=>$inte_data));
        }
    }

    //钱包每日充值总额
    public function wallet_chart(){
        //dump($list);
        if(!request()->isAjax()){
            return view('wallet_chart');
        }else{
            $where = "wallet <> 0 ";
            $time1 = input('post.time1');
            //$time1 = "2018-04-24";
            if($time1){
                $time1 = strtotime($time1);
                $where .= " and addtime >= $time1 ";
            } else{
                $time1 = time()-7*24*3600;
                $where .= " and addtime >= $time1 ";
            }
            $time2 = input('post.time2');
            if($time2){
                $time2 = strtotime($time2);
                $where .= " and addtime < $time2 ";
            }
            //return json_encode(1);die;
            //每日释放总量，每日茶点总量，每日茶券总量
            $year_data = Db::table('tea_integral_log')->field('year')->where($where)->group('year')->select();
            foreach($year_data as $k => $v){
                $month = Db::table('tea_integral_log')->field('month')->where($where.' and year='.$v['year'])->group('month')->select();
                foreach($month as $k1 => $v1){
                    $day = Db::table('tea_integral_log')->field('day')->where($where.' and month='.$v1['month'])->group('day')->select();
                    foreach($day as $k2 => $v2){
                        $data['time'][]=$v['year'].'-'.$v1['month'].'-'.$v2['day'];
                        $data['wallet_add'][] =Db::table('tea_integral_log')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and wallet = 1")
                            ->sum('surplus_inte');
                        $data['wallet_out'][] =Db::table('tea_integral_log')
                            ->where("$where and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and wallet = 2")
                            ->sum('surplus_inte');
                        $data['wallet_use'][] =Db::table('tea_integral_log')
                            ->where("$where  and year=".$v['year'].' AND month='.$v1['month'].' And day ='.$v2['day']." and wallet = 3")
                            ->sum('surplus_inte');
                        $list[$k][] = $data;
                    }
                }
            }
            if(!$year_data){
                $list = "";
            }
            $inte_data = Db::table('tea_integral_log')->where($where)
            ->select();//->paginate(14);
            foreach($inte_data as $k3=>$v3){
                $user_id = $v3['user_id'];
                $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,mobile_phone')->where('user_id',$user_id)->find();
                $inte_data[$k3]['user_name'] = $user_info['user_name'];
            }
            //$list['info'] = $inte_data;
            return json_encode(array('list'=>$list,'list1'=>$inte_data));
        }
    }

    //查看团队
    public function user_team($user_id){
        $user_id = intval($user_id);
        $data = $this-> getCateTree($user_id);
        return view('look_team',['data'=>$data]);
    }

    //无县级
    //获取格式化之后的数据
    public function getCateTree($user_id=0)
    {
        //先获取所有的分类信息
        $data = Db::connect(config('db_config2'))->name("users")->where('parent_id',$user_id)->field('user_name,user_id,parent_id')->select();
        //在对获取的信息进行格式化
        $list = $this->getTree($data,$user_id);
        return $list;
    }
    //格式化分类信息
    public function getTree($data,$id=0,$lev=1)
    {
        static $list = array();
        foreach ($data as $key => $value) {
            $user_recharge=Db::table('tea_integral')->field('id')->where('user_id',intval($value['user_id']))->find();
            if($user_recharge){
                if($value['parent_id']==$id){
                    $value['lev']=$lev;
                    $list[]=$value;
                    //使用递归的方式获取分类下的子分类
                    $this->getTree($data,$value['user_id'],$lev+1);
                }
            }
        }
        return $list;
    }

    //------------------------------------------------------------------------------------------------------------------
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


    //生成会员充值记录
    public function user_rech_info(){
        $info = Db::name('user')->field('user_id,tea_ponit_inte,tea_inte')->select();
        $obj = new HomeUser();
        foreach($info as $k => $v){
            $user_id = intval($v['user_id']);
            //获取用户名信息
            $user_info = Db::connect(config('db_config2'))->name("users")
                ->field('user_name,parent_id,mobile_phone,reg_time,user_rank')
                ->where('user_id',$user_id)->find();
            if(empty($user_info)){
                $info[$k]['user_name'] = '';
                $info[$k]['mobile_phone'] = '';
                $info[$k]['reg_time'] ='';
                $info[$k]['user_rank'] = '';
            }else{
                $info[$k]['user_name'] = $user_info['user_name'];
                $info[$k]['mobile_phone'] = substr_replace($user_info['mobile_phone']," ",3,0); ;
                $info[$k]['reg_time'] =date('Y-m-d ', $user_info['reg_time'])?date('Y-m-d H:i:s ', $user_info['reg_time']):'';
                //$info[$k]['user_rank'] = $user_info['user_rank']==9?'股东':'会员';
                $res = Db::query("select  count(id) as 'id',type  from  tea_integral where user_id = $user_id group by type ");
                $str= '';
                foreach($res as $v){
                    if((int)$v['type']==1){
                        $str .="会员+";
                    }
                    if((int)$v['type']==2){
                        $str .="茶馆+";
                    }
                    if((int)$v['type']==3){
                        $str .="门店+";
                    }
                }
                $info[$k]['user_rank'] = substr($str,0,strlen($str)-1);
                $info[$k]['parent_user'] = Db::connect(config('db_config2'))->name("users")
                    ->field('user_name')
                    ->where('user_id',$user_info['parent_id'])->find()['user_name'];
            }
            $one = Db::connect(config('db_config2'))->name("users")->where('parent_id',$user_id)->count('user_id');
            $info[$k]['one']  = $one?$one:0;
            $two = Db::connect(config('db_config2'))->query("select count(b.user_id) as user_id from taom_users b join (select a.user_id from taom_users a where a.parent_id = $user_id) as c
                                                        on b.parent_id in (c.user_id) ")[0]['user_id'];
            $info[$k]['two']  = $two?$two:0;
            //是否实名
            $res = Db::connect(config('db_config2'))->name('users_real')
                ->field('real_name,bank_name,bank_card')
                ->where('user_id','=',$user_id)->order('real_id desc')->limit(1)->find();
            if(empty($res)){
                $info[$k]['real_name'] ='' ;
                $info[$k]['bank_name'] ='' ;
                $info[$k]['bank_card'] ='' ;
            }else{
                $info[$k]['real_name'] =$res['real_name'] ;
                $info[$k]['bank_name'] =$res['bank_name'] ;
                $info[$k]['bank_card'] =substr_replace($res['bank_card']," ",4,0); ;
            }
            //充值总数
            $info[$k]['sum_money'] = Db::name('user_recharge')->where('user_id',$user_id)->where('pay_status',1)->sum('rec_money');

            //是否激活
            $is_active  = Db::name('user_recharge')->field('id')->where('user_id',$user_id)->where('pay_status',1)->order('id desc')->limit(1)->find();
            $info[$k]['is_active'] = empty($is_active)?'否':"是";

            //积分记录
            $info_inte = Db::name('integral')->field("sum(total_sum) as 'total_sum',sum(back_inte) as 'back_inte',sum(surplus_inte) as 'surplus_inte'")->where('user_id',$user_id)->find();
            $info[$k]['total_sum'] =$info_inte['total_sum'] ;
            $info[$k]['back_inte'] =$info_inte['back_inte'] ;
            $info[$k]['surplus_inte'] =$info_inte['surplus_inte'] ;

        }
        $filename = '会员记录'.date('YmdHis');
        $header = array('会员编号','会员名称','是否激活','总积分','已返积分','待返积分','可用茶点','可用茶券','充值总数','会员手机号','真实姓名','会员类型','会员推荐人','直推人数','间接推荐人数','开户行','卡号','注册时间');
        $index = array('user_id','user_name','is_active','total_sum','back_inte','surplus_inte','tea_ponit_inte','tea_inte','sum_money','mobile_phone','real_name','user_rank','parent_user','one','two','bank_name','bank_card','reg_time');
        //会员记录
        $this->createtable($info,$filename,$header,$index);
    }

    public function user_inte_change()
    {

        if (request()->isGet()) {
            return view('user_inte_change');
        }else{
            $data = input('post.');
            if($data['introduce']==''){
                $this->error('变动原因未填写');
            }
            if((float)$data['tea_ponit_inte'] < 0 || (float)$data['tea_inte'] < 0){
                $this->error('填写积分数不合法');
            }
            $user_name = $data['username'];
            $user_info = Db::connect(config('db_config2'))->name("users")->where("user_name='$user_name'")->field('user_id,user_name,nick_name')->find();
            if($user_info) {
                $user_id = $user_info['user_id'];
                //判断积分类型
                if((int)$data['teaponit_tye']==1 && (float)$data['tea_ponit_inte'] > 0 ){
                    //增加
                    Db::name('user')->where("user_id = $user_id")->setInc('tea_ponit_inte',(float)$data['tea_ponit_inte']);
                    $tea_ponit_inte = "+".(float)$data['tea_ponit_inte'];
                }else{
                    //减少
                    Db::name('user')->where("user_id = $user_id")->setDec('tea_ponit_inte',(float)$data['tea_ponit_inte']);
                    $tea_ponit_inte = "-".(float)$data['tea_ponit_inte'];
                }
                if((int)$data['teainte_type']==1 && (float)$data['tea_inte'] > 0 ){
                    //增加
                    Db::name('user')->where("user_id = $user_id")->setInc('tea_inte',(float)$data['tea_inte']);
                    $tea_inte = "+".(float)$data['tea_inte'];
                }else{
                    //减少
                    Db::name('user')->where("user_id = $user_id")->setDec('tea_inte',(float)$data['tea_inte']);
                    $tea_inte = "-".(float)$data['tea_inte'];
                }

                if((int)$data['teainte_type']==1 && (int)$data['teaponit_tye']==1){
                    $surplus_inte = "+".((float)$data['tea_ponit_inte']+(float)$data['tea_inte']);
                }elseif((int)$data['teainte_type']==0 && (int)$data['teaponit_tye']==1){
                    $surplus_inte = ((float)$data['tea_inte'] - (float)$data['tea_ponit_inte'])."";
                }elseif((int)$data['teainte_type']==1 && (int)$data['teaponit_tye']==0){
                    $surplus_inte = ((float)$data['tea_ponit_inte']-(float)$data['tea_inte'])."";
                }elseif((int)$data['teainte_type']==0 && (int)$data['teaponit_tye']==0){
                    $surplus_inte = "-".((float)$data['tea_ponit_inte']+(float)$data['tea_inte']);
                }
                //写入记录
                $list = array('user_id'=>$user_id,'surplus_inte'=>$surplus_inte,'tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte,'introduce'=>$data['introduce'],
                    'menu'=>3,'use_type'=>1,'fix'=>2,'addtime'=>time(),'year'=>date('Y'),'month'=>date('m'),'day'=>date('d'));
                Db::name('integral_log')->where("user_id = $user_id")->insert($list);
                $this->success('修改成功');
            }else{
                $this->error('用户名不存在');
            }
        }
    }


    public function  check_user(){
        $user_name = input("post.username");
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_name='$user_name'")->field('user_id,user_name,nick_name')->find();
        if($user_info){
            $user_id = $user_info['user_id'];
            $user2 = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->field('real_name,bank_mobile')->find();
            $user_info['real_name'] = $user2['real_name'] ? $user2['real_name'] :"--";
            $user_info['mobile_phone'] = $user2['bank_mobile'] ?$user2['bank_mobile']:"--" ;
            //用户积分
            $info = Db::name('user')->field('user_id,tea_ponit_inte,tea_inte')->where("user_id = $user_id")->find();
            $user_info['tea_inte'] = $info['tea_inte'];
            $user_info['tea_ponit_inte'] = $info['tea_ponit_inte'];
            return json_encode(array('statue'=>1,'data'=>$user_info));
        }else{
            return json_encode(array('statue'=>0,'data'=>''));
        }

    }

    public function  user_all_new(){
        $wheres = 'user_id !=32 and user_id != 992';
        //计算所有用户总积分，已返总积分，剩余总积分，已返总茶点，已返总茶券
        $res = Db::table("tea_integral")->field("sum(total_sum) as 'total_sum',sum(back_inte)  as 'back_inte',sum(surplus_inte)  as 'surplus_inte',
        sum(tea_inte)  as 'tea_inte',sum(tea_ponit_inte)  as 'tea_ponit_inte'")->where($wheres)->where(" user_id != 1151 and user_id != 1152")->find();
        //已使用总积分，总茶点，总茶券
        $wheres1 = "use_type = 2 and $wheres ";
        $list = Db::table("tea_integral_log")->field("sum(surplus_inte)  as 'surplus_inte',
        sum(tea_inte)  as 'tea_inte',sum(tea_ponit_inte)  as 'tea_ponit_inte'")->where($wheres1)->find();
        //用户现有总积分，总茶点，总茶劵'
        $list1 = Db::table("tea_user")->field("sum(tea_inte)  as 'tea_inte',sum(tea_ponit_inte)  as 'tea_ponit_inte'")->where($wheres)->find();
        $res['use_tea_inte'] = substr($list['tea_inte'],1,strlen($list['tea_inte'])-1);
        $res['use_tea_ponit_inte'] = substr($list['tea_ponit_inte'],1,strlen($list['tea_ponit_inte'])-1);
        $res['have_tea_inte'] = $list1['tea_inte'];
        $res['have_tea_ponit_inte'] = $list1['tea_ponit_inte'];
        return view('user_all',['info'=>$res]);
    }

    //非股东，或 旧规则
    public function  user_log(){
        $where = " user_id not in (32,992,1885,1,2709,2987,3027,1151,1152)";
        //用户获得总茶点
        $get_tea_ponit_inte = Db::table("tea_integral_log")->where("$where")->where("tea_ponit_inte > 0 ")->SUM('tea_ponit_inte');
        //用户使用总茶点
        $use_tea_ponit_inte = Db::table("tea_integral_log")->where("$where")->where("tea_ponit_inte <  0 ")->SUM('tea_ponit_inte');
        //用户获得总茶券
        $get_tea_inte = Db::table("tea_integral_log")->where("$where")->where("tea_inte > 0 ")->SUM('tea_inte');
        //用户使用总茶券
        $use_tea_inte = Db::table("tea_integral_log")->where("$where")->where("tea_inte <  0 ")->SUM('tea_inte');
        //$have_inte = $this->user_sum();
        $have_inte_sum =  $user_sum = Db::name("integral")->field("sum(total_sum) as 'total_sum'")->where(" $where" )->find();
        $inte_sum = Db::name("user_recharge")->field("sum(total_inte) as 'total_inte'")->where("pay_status = 1  and rec_money > 0 and $where" )->find();
        $have_inte = $inte_sum['total_inte'] - $have_inte_sum['total_sum'];
        $list = array('have_tea_ponit_inte'=>$get_tea_ponit_inte + $have_inte ,'use_tea_ponit_inte'=>$use_tea_ponit_inte,'have_tea_inte'=>$get_tea_inte,'use_tea_inte'=>$use_tea_inte);
        return $list;
    }

    //股东应得总积分
    public function user_sum(){
        $where = " user_id not in (32,992,1885,1,2709,2987,3027,1151,1152)";
        //用户应得总积分
        $u_s = Db::name("recharge_cart")
            ->field("tea_recharge_cart.user_id,
            sum(tea_new_recharge.rec_money) as 'rec_money',
            is_gift,
            tea_recharge_cart.recharge_id,
            sum(total_inte) as 'total_inte',
            sum(gifts) as 'gifts'")
            ->join("tea_new_recharge","tea_new_recharge.recharge_id = tea_recharge_cart.recharge_id")
            ->where("pay_status = 1 and tea_recharge_cart.is_gift = 1 and $where and is_ceo = 1 and tea_recharge_cart.recharge_money > 0")
            ->find();

        $u_ss = Db::name("recharge_cart")
            ->field("tea_recharge_cart.user_id,
            sum(tea_recharge.rec_money) as 'rec_money',
            is_gift,
            tea_recharge_cart.recharge_id,
            sum(total_inte) as 'total_inte',
            sum(gift) as 'gift'")
            ->join("tea_recharge","tea_recharge.id = tea_recharge_cart.recharge_id")
            ->where("pay_status = 1 and tea_recharge_cart.is_gift = 1 and $where and is_ceo = 0 and tea_recharge_cart.recharge_money > 0")
            ->find();

        //以得总计分为
        $gift =(float)$u_s['gifts'] + (float)$u_ss['gift'];
        return $gift;
    }

    public function user_all(){
        $where = " user_id not in (32,992,1885,1,2709,2987,3027,1151,1152)";

        //总
        $inte_sum = Db::name("user_recharge")->field("sum(total_inte) as 'total_inte'")->where("pay_status = 1  and rec_money > 0 and $where" )->find();

        //待返
        $res = Db::table("tea_integral")->field("sum(surplus_inte)  as 'surplus_inte'")->where("$where")->find();

        //已返总积分
        $res_back = Db::table("tea_integral")->field("sum(back_inte)  as 'back_inte'")->where("$where")->find();

        //$have_inte = $this->user_sum();

        //$res_back_inte = (float)$res_back['back_inte'] + $have_inte;
        $user_sum = Db::name("integral")->field("sum(total_sum) as 'total_sum'")->where(" $where" )->find();
        $res_back_inte = (float)$res_back['back_inte'] + $inte_sum['total_inte'] - $user_sum['total_sum'] - 19990; //多算了19990

        //已返总茶点。及消耗
        $int_inf = $this->user_log();

        //现有
        $list1 = Db::table("tea_user")->field("sum(tea_inte)  as 'tea_inte',sum(tea_ponit_inte)  as 'tea_ponit_inte'")->where("$where")->find();

        $list = array('total_sum'=>$inte_sum['total_inte'],'surplus_inte'=>$res['surplus_inte'],'back_inte'=>$res_back_inte);

        //待返
        $will_inf= $this->inte_will_have();
        $list_new = array_merge($list,$int_inf,$list1,$will_inf);
        return view('user_all',['info'=>$list_new]);

    }

    public function inte_will_have(){

        $where = " user_id not in (32,992,1885,1,2709,2987,3027,1151,1152)";

        $us_inte_arr = Db::name("integral")->field("user_id")->where("$where")->group("user_id")->column("user_id");
        $us_inte = implode(',',$us_inte_arr);
        //待返
        $u_s = Db::name("recharge_cart")
            ->where("pay_status = 1 and tea_recharge_cart.is_gift = 0 and $where and is_ceo = 1 and tea_recharge_cart.recharge_money > 0")
            ->group("user_id")
            ->column("tea_recharge_cart.user_id");
        $str = implode(',',$u_s);

        $res = Db::table("tea_integral")->field("sum(surplus_inte)  as 'surplus_inte'")->where("user_id in ($str) and user_id in ($us_inte)")->where("$where")->find();
        $rate = Db::table("tea_new_recharge")->where('recharge_id = 2 ')->find();
        $tea_ponit_inte = $res['surplus_inte'] *$rate['fir_merits'] ;
        $tea_inte = $res['surplus_inte'] *$rate['sec_merits'] ;


        $u_s_2 = Db::name("recharge_cart")
            ->where("pay_status = 1 and tea_recharge_cart.is_gift = 1 and $where and is_ceo = 1 and tea_recharge_cart.recharge_money > 0")
            ->group("user_id")
            ->column("tea_recharge_cart.user_id");
        $str = implode(',',$u_s_2);

        $res_new = Db::table("tea_integral")->field("sum(surplus_inte)  as 'surplus_inte'")->where("user_id in ($str) and user_id in ($us_inte)")->where("$where")->find();
        $will_tae_ponit = $tea_ponit_inte;
        $will_tae_inte = $res_new['surplus_inte']+$tea_inte;

        //会员
        $u_s = Db::name("recharge_cart")
            ->where("pay_status = 1 and tea_recharge_cart.is_gift = 0 and $where and is_ceo = 0 and tea_recharge_cart.recharge_money > 0 and user_id < 2645")
            ->group("user_id")
            ->column("tea_recharge_cart.user_id");
        $str = implode(',',$u_s);

        $res = Db::table("tea_integral")->field("sum(surplus_inte)  as 'surplus_inte'")->where("user_id in ($str) and user_id in ($us_inte)")->where("$where")->find();
        $user_will_tea_ponit = $res['surplus_inte'];
        $user_will_tea_inte = 0;

        $u_s_1 = Db::name("recharge_cart")
            ->where("pay_status = 1 and tea_recharge_cart.is_gift = 0 and $where and is_ceo = 0 and tea_recharge_cart.recharge_money > 0 and user_id >= 2645")
            ->group("user_id")
            ->column("tea_recharge_cart.user_id");
        $str = implode(',',$u_s_1);


        $res = Db::table("tea_integral")->field("sum(surplus_inte)  as 'surplus_inte'")->where("user_id in ($str) and user_id in ($us_inte)")->where("$where")->find();
        $rate_1 = Db::table("tea_rate")->order("id desc")->limit(1)->find();
        $user_will_tea_inte = $res['surplus_inte'] * $rate_1['slow_tea_inte_rate'] + $user_will_tea_inte;
        $user_will_tea_ponit = $res['surplus_inte'] * $rate_1['slow_tea_score_rate'] + $user_will_tea_ponit;

        $will_tae_ponit = $will_tae_ponit+$user_will_tea_ponit;
        $will_tae_inte = $user_will_tea_inte +$will_tae_inte;
        $list = array('will_tae_ponit'=>$will_tae_ponit,'will_tae_inte'=>$will_tae_inte);
        return $list;
    }


}