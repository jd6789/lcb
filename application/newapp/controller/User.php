<?php
namespace app\newapp\controller;
use think\Db;
use think\Cookie;
use think\Session;
use app\newapp\model\Huiyuan;

class User  extends Com
{
    function aaa(){
        $ip=$this->request->ip();
        dump($ip);
    }
    //显示我的订单页面
    public function order(){
        $this->checkLogin();
        $user_id = session('lcb_user_id');
        $data=Db::table('tea_order')->alias('o')
            ->where('o.user_id',$user_id)
            ->join('tea_order_cart c','c.order_id=o.order_id')
            ->order('order_addtime desc')
            ->select();
        $this->assign('data', $data);
        return $this->fetch();
    }
    //我的理茶宝页面
    public function myrichardtea(){
        $this->checkLogin();
        return $this->fetch();
    }
	//理茶宝会员登录
    public function login()
    {
        return $this->fetch();
    }
    //忘记密码页面
    public function repwd()
    {
        return $this->fetch();
    }
    //忘记密码修改
    public function reppwd()
    {
        if(request()->isAjax()){
            $tel = input('post.tel');
            $userinfo = Db::connect(config('db_config2'))->name("users")->where("mobile_phone='$tel'")->find();
            //判断验证码是否正确
            $code = session('code');
            $user_code = input('post.code');
            if($user_code != $code){
                return json_encode(array("data"=>"验证码错误",'status'=>3));
            }
            //重置密码
            $password1 = input('post.password1');
            $password2 = input('post.password2');
            //判断密码是否一致
            if($password1 != $password2){
                return json_encode(array('data'=>"亲,两次密码必须一致哦!!!",'status'=>2));
            }
            $salt = $userinfo['tm_salt'];
            $password = md5(md5($password1).$salt);

            //判断密码修改是否成功
            $res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone='$tel'")->setField('password',$password);
            //dump($res);
            if($res){
                return json_encode(array('data'=>"密码修改成功",'status'=>1));
            }else{
                return json_encode(array('data'=>"密码修改失败",'status'=>0));
            }
        }
    }

    public function tellogin()
    {
        return $this->fetch();
    }
    public function index()
    {
        return $this->fetch();
    }
    //显示我的理茶宝页面
    public function richardtea()
    {
        $this->checkLogin();
        return $this->fetch();
    }
    //在理茶宝页面点击购买后进入理茶宝的详情信息
    public function confirmproduct()
    {
        $recharge_id=intval(input('get.recharge_id'));
        $recahrge_data=Db::table('tea_recharge')
            ->where('id',$recharge_id)
            ->find();
        $recahrge_data['b']=floatval($recahrge_data['total_inte'])-floatval($recahrge_data['gift']);
        return view('confirmproduct',['data'=>$recahrge_data]);
    }
    public function confirm()
    {
        $id=intval(input('get.id'));
        $user_id=session('lcb_user_id');
        $data=Db::table('tea_recharge_cart')
            ->where('id',$id)
            ->find();
        $user_wallet=Db::table('tea_user')->where('user_id',$user_id)->find();
        $data['wallet']=floatval($user_wallet['wallet']);
        $recharge_data=Db::table('tea_recharge')->where('id',$data['recharge_id'])->find();
        $this->assign('data',$data);
        $this->assign('recharge',$recharge_data);
        return $this->fetch();
    }
    public function record()
    {
        return $this->fetch();
    }
    //显示我的个人中心页面
    public function myinfo111()
    {
        $this->checkLogin();
        $user_id        = session('lcb_user_id');
        $user_rank      = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->field('user_rank,user_name,user_picture')->find();
        $userinfo       = Db::table('tea_user')->where("user_id",$user_id)->find();
        //如果昵称不存在就显示用户名
        if(empty($user_rank['nick_name'])){
            $userinfo['user_name']          = $user_rank['user_name'];
        }else{
            $userinfo['user_name']          = $user_rank['nick_name'];
        }
        $userinfo['user_rank']              = $user_rank['user_rank'];
        $integral = Db::table('tea_user')->alias('a')
            ->join('tea_integral b','a.user_id=b.user_id','LEFT')
            ->join('tea_integral_log c','a.user_id=c.user_id','LEFT')
            ->field('b.surplus_inte,c.surplus_inte as sfjf')
            ->where("a.user_id=$user_id AND b.is_ceo=0")
            ->order('c.id desc')
            ->limit(1)
            ->find();
        if(empty($integral['surplus_inte']) || empty($integral['sfjf'])) {
            $userinfo['surplus_inte']       = 0.00;
            $userinfo['sfjf']               =0.00;
        }else{
            $userinfo['surplus_inte']       = $integral['surplus_inte'];
            $userinfo['sfjf']               = $integral['sfjf'];
        }
        if($user_rank['user_picture'] ==''){
            $userinfo['user_picture']       ='http://'.$_SERVER['HTTP_HOST'].'/newtea/images/mrimg.png';
        }else{
            $userinfo['user_picture']       =$user_rank['user_picture'];
        }
        $this->assign('data',$userinfo);
        return $this->fetch();
    }

    //显示个人中心我的页面
    public function personcenter()
    {
        return $this->fetch();
    }
    //判断个人中心我的信息页面是否显示实名认证
    public function is_realname(){
        if(request()->isAjax()) {
            $user_id = session('lcb_user_id');
            $is_real = Db::connect(config('db_config2'))->name('users_real')->where("user_id", $user_id)->find();
            if ($is_real) {
                return json(array('status' => 1,'data' => $is_real));
            } else {
                $parent_id = Db::connect(config('db_config2'))->name('users')->where("user_id", $user_id)->value('parent_id');
                if ($parent_id == 0) {
                    return json(array('parent_id' => 0));
                } else {
                    return json(array('parent_id' => $parent_id));
                }
            }
        }
    }
    //显示我的付款码页面
    public function paycode()
    {
        return $this->fetch();
    }
    public function bindphone()
    {
        return $this->fetch();
    }

    //个人信息显示
    public function personinfo()
    {
        $user_id = session('lcb_user_id');
        $user = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->field('nick_name,mobile_phone,reg_time,user_picture')->find();
        if($user['user_picture'] ==''){
            $user['user_picture']='http://'.$_SERVER['HTTP_HOST'].'/newtea/images/mrimg.png';
        }
        $user2 = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->field('real_name,self_num,bank_name')->find();
        $rec_addtime = Db::table('tea_user_recharge')->where("user_id",$user_id)->value('rec_addtime');
        $user['real_name'] = $user2['real_name'];
        $user['self_num'] = $user2['self_num'];
        $user['bank_name'] = $user2['bank_name'];
        $user['rec_addtime'] = $rec_addtime;
        //$user['user_picture'] = session('user')['user_picture'];
        $this->assign('data',$user);
        return $this->fetch();
    }
    public function loginandreg()
    {
        return $this->fetch();
    }
    public function richardtea1()
    {
        return $this->fetch();
    }
    //判断会员是否进行过实名认证
    public function is_real()
    {
        $user_id = session('lcb_user_id');
        $is_real = Db::connect(config('db_config2'))->name('users_real')->where("user_id",$user_id)->find();
        if($is_real){
            return (1);
        }else{
            return (0);
        }
    }
    //帮人注册
    public function otherreg()
    {
        $user_id = session('lcb_user_id');
        if (request()->isAjax()) {
            $username = trim(input('post.username'));
            $password = trim(input('post.password1'));
            $tel = trim(input('post.tel'));
            $pay_pwd = trim(input('post.pay_pwd1'));
            $msginfo = trim(input('post.sendCode'));
            $code = session('code');
            //判断验证码是否正确
            if ($msginfo != $code) {
                return json(array('status' => 0, 'msg' => "验证码错误"));
            }
            session::set('msg',null);
            $salt = rand(1000, 9999);
            $password = md5(md5($password) . $salt);
            $pay_pwd = md5(md5($pay_pwd) . $salt);
            $data = array(
                'user_name' => $username,
                'password' => $password,
                'mobile_phone' => $tel,
                'parent_id' => $user_id,
                'tm_salt' => $salt,
                'reg_time' => time(),
                'user_rank'=>9
            );
            $info = Db::connect(config('db_config2'))->name("users")->insert($data);
            if (!$info) {
                return json(array('status' => 2, 'msg' => "注册失败"));
            } else {
                $time = time();
                //将用户同步到lcb
                $userid = Db::connect(config('db_config2'))->name("users")->where("user_name",$username)->value('user_id');
                $data2 = array(
                    'user_id'=>$userid,
                    'pay_pwd'=>$pay_pwd,
                    'wait'=>1,
                );
                $lcb_user = Db::table('tea_user')->insert($data2);
                if(!$lcb_user){
                    return json(array('status' => 2, 'msg' => "注册失败"));
                }
                return json(array('status' => 1, 'msg' => "ok"));
            }
        } else {
//            //判断用户是否有权限去推荐别人
//            $user_info=Db::table('tea_integral')->where('user_id',$user_id)->find();
//            if($user_info){
//                return $this->fetch();
//            }else{
//                $this->error('您未购买相关理茶宝产品，不能代人注册','user/recommender');
//            }
            return $this->fetch();
        }
    }

    //在关联用户里面切换用户
    public function change_user(){
        session('user',null);
        session('lcb_user_id',null);
        Cookie::set("user",null);
        $unionid = session('unionid');
        $wechat_id = Db::connect(config('db_config2'))->name('wechat')->where(array('default_wx' => 1, 'status' => 1))->find();
        $main_user_id = Db::connect(config('db_config2'))
            ->name('wechat_user')
            ->where(array('unionid' => $unionid, 'wechat_id' => $wechat_id['id']))
            ->find();
        $main_user_info = Db::connect(config('db_config2'))->name("users")->where('user_id', $main_user_id['ect_uid'])->find();
        session('user',$main_user_info['user_id']);
        session('lcb_user_id',$main_user_info);
        Cookie::set("user",$main_user_info);
        session('unionid',null);
        $this->redirect('user/myinfo');
    }

    //用户修改密码
    public function changepwd()
    {
        if(request()->isAjax()){
            $user_id = cookie('user')['user_id'];
            //判断用户当前密码是否正确
            $password1 = trim(input('post.password1'));
            $userinfo = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->find();
            $password = $userinfo['password'];
            $password1 = md5(md5($password1).$userinfo['tm_salt']);
            if($password1 != $password){
                return json(array('status'=>0,'data'=>"当前密码不正确"));
            }

            $password2 = trim(input('post.password2'));
            $password3 = trim(input('post.password3'));
            if($password2 != $password3){
                return json(array('status'=>2,'data'=>"密码不一致"));
            }
            $password = md5(md5($password2).$userinfo['tm_salt']);
            $res = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->setField('password',$password);
            if($res){
                return json(array('status'=>1,'data'=>"密码修改成功"));
            }else{
                return json(array('status'=>3,'data'=>"系统维护中"));
            }
        }else{
            return $this->fetch();
        }
    }
        //获取推荐人数
    public function getNumber($user_id)
    {
        $one = array();
        $two = array();
        //先获取所有的分类信息
        $data = Db::connect(config('db_config2'))->name('users')->select();
        //dump($data);
        //获得每个用户一级推荐人信息（人数）
        foreach ($data as $k => $v) {
            //dump($v);
            $c = $v['user_id'];
            //dump($c);die;
            $i = 0;
            foreach ($data as $k1 => $v1) {
                $arr[$c] = $i;
                //dump($arr);die;
                if ($v1['parent_id'] == $c) {
                    $i++;
                    $list[$c][] = $v1['user_id'];
                    //dump($list);die;
                    /*$list = array(1){
                        [61] => array {
                            [0] => 62}}*/
                    $arr[$c] = $i;
                    //dump($arr);die;     $arr=array('61'=>1)
                    //$list1[$c][] = $v1;
                }
            }
        }

        //获得每个二级推荐人人数
        //dump($list);die;
        foreach($list as $k=>$v){
            //dump($list);die;
            //dump($k);
            $sum = 0;
            foreach($v as $k1=>$v2){
                $n = (int)$v2;
                //dump($n);
                //dump($arr);
                $sum += $arr[$n];
                $list2[$k] = $sum;    //每个会员的二级推荐人数
                //dump($list2);
            }
        }
        //dump($arr);die;
        foreach($arr as $k => $v){
            //dump($arr);
            //dump($list);
            //dump($k);
            if($v == 0){
                $list[$k] = 0;
            }
        }
        $info['once'] = $arr;
        $info['second'] = $list2;
        //dump($info);die;
        //dump($user_id);
        foreach($arr as $k => $v){
            $user_id = (int)$user_id;
            //dump($user_id);
            //dump($v);
            if($k == $user_id){
                $one = $arr[$k];
            }
        }
        //dump($one);die;
        //dump($list2);die;
        foreach($list2 as $k1 => $v1){
            $user_id = (int)$user_id;
            if($k1 == $user_id){
                $two = $list2[$k1];
            }
        }

        $info['one'] = $one;
        $info['two'] = count($two);

        //dump($info);die;
        return $info;
    }

    //获取用户等级
    public function getLev($id){
        $data =Db::table('tea_user_recharge')->where('user_id='.$id)->order('id desc')->limit(1)->find();
        if($data){
            $rec['lev'] = $data['rec_lev'];
            $rec['out'] = $data['is_return'];
            $rec['active'] = 1;
            $rec['no'] = intval($data['is_active']);
        }else{
            $rec['lev'] = 0;
            $rec['active'] = 0;
            $rec['out'] = 0;
            $rec['no'] = 0;
        }
        return $rec;
    }

    //团队人数
    //无限级分类
    //获取格式化之后的数据
    public function getCateTree($id=0)
    {
        //先获取所有的分类信息
        $data = Db::connect(config('db_config2'))->name("users")->select();
        //dump($data);die;
        //在对获取的信息进行格式化
        $list = $this->getTree($data,$id);
        //dump($list);die;
        return $list;
    }
    //格式化分类信息
    public function getTree($data,$id=0,$lev=1)
    {
        static $list = array();
        foreach ($data as $key => $value) {
            //dump($value);die;
            if($value['parent_id']==$id){
                $value['lev']=$lev;
                $list[]=$value;
                //使用递归的方式获取分类下的子分类
                $this->getTree($data,$value['user_id'],$lev+1);
            }
        }
        return $list;
    }

    public function linkover()
    {
        //
        $unionid = session('unionid');
        $wechat_id = Db::connect(config('db_config2'))->name('wechat')->where(array('default_wx' => 1, 'status' => 1))->find();
        $main_user_id = Db::connect(config('db_config2'))->name('wechat_user')->where(array('unionid' => $unionid, 'wechat_id' => $wechat_id['id']))->find();
        $main_user_info = Db::connect(config('db_config2'))->name("users")->where('user_id', $main_user_id['ect_uid'])->find();

        if (!empty($main_user_info)) {
            $main_user_info['user_name'] = $main_user_info['user_name'] . '(系统默认分配账号)';
            //$main_user_info['user_picture'] = dao('wechat_user')->where(array('unionid' => $_SESSION['unionid'], 'wechat_id' => $wechat_id))->getField('headimgurl');
            //return json_encode(array('status' => 1, 'msg' => $main_user_info));
        }
        //dump($main_user_info);
        $data_user = session('user');
        // dump($data_user);die;
        return view('linkover',['data'=>$main_user_info,'data_user'=>$data_user]);
    }

    public function accountlink()
    {
        $this->checkLogin();
        return $this->fetch();
    }



    //显示我的推荐页面
    public function recommender(){
        $user_id = session('lcb_user_id');
        //查询当前用户的积分信息
        $nowUserInfo=Db::table('tea_user_recharge')->where('user_id',$user_id)->sum('rec_money');
        $groom_info = "";
        $groom_info = $this->groom_info($user_id);
        //每个人推荐的一级人数
        $user_info['one_num'] = $groom_info['num'];
        //每个人推荐的一级人的id
        $user_info['one_num_id'] = $groom_info['num_id'];
        // $user_info['one_num_info'] = $groom_info['num_info'];
        $user_info['sum_one_rec_monry'] = $groom_info['sum_one_rec_monry']+floatval($nowUserInfo);
        //dump($user_info);die;
        //每个人推荐的二级人数
        if($groom_info['num_id']){
            $groom_info_second = $this->groom_info_second($groom_info['num_id']);
            //dump($groom_info_second);die;
            if($groom_info_second['counts_id']){
                //每个人推荐的二级人的id
                $user_info['two_num_id'] = substr($groom_info_second['counts_id'],0,strlen($groom_info_second['counts_id'])-1);
            }else{
                $user_info['two_num_id'] = "";
            }
            //每个人推荐的二级人数
            $user_info['two_num'] = $groom_info_second['counts'];
            // $user_info['two_num_info'] = $groom_info_second['counts_info'];
            $user_info['sum_second_rec_monry'] = $groom_info_second['sum_second_rec_monry'];
        }else{
            $arr = array();
            $user_info['two_num'] = 0;
            $user_info['two_num_id'] = "";
            //$user_info['two_num_info'] = $arr;
            $user_info['sum_second_rec_monry'] = 0;
        }
        //dump($user_info);die;
        $this->assign('data',$user_info);
        return $this->fetch();
    }

    //发送AJAX返回数据 我的一级推荐
    public function recommenders(){
        $user_id = session('lcb_user_id');
        $childId = $this->getChilderId($user_id);
        if(empty($childId) )  return 0;
        foreach ($childId as $k=>$v){
            $childId[$k]['sum'] = $this->getChilderSum($v['user_id']);
            $childId[$k]['status'] = $this->getLev($v['user_id']);
        }

        $data = $this->groupByInitials($childId, 'user_name');
        foreach ($data as $k1 => $v1) {
            foreach ($v1 as $v2) {
                $list[] = $v2;
            }
        }
        if($list){
            return json($list);
        }else{
            return 0;
        }
    }


    //发送AJAX返回数据 我的二级推荐
    public function vcm(){
        $user_id = session('lcb_user_id');

            $info_list = Db::connect(config('db_config2'))->name('users')->where("parent_id",$user_id)
                ->where('user_rank','in','9,10')
                ->select();
            if (empty($info_list)) return 0;
            foreach($info_list as $k=>$v){
                //$a[] = D('User')->getChild($v['id']);
                $a[] = Db::connect(config('db_config2'))->name('users') ->where('user_rank','in','9,10')->where("parent_id",$v['user_id'])->select();;
            }
            foreach($a as $vo){
                if($vo != ""){
                    foreach($vo as $v2){
                        $ss[] = $v2;
                    }
                }
            }
            foreach ($ss as $k3 => $v3) {
                $ss[$k3]['sum'] = $this->getChilderSum($v3['user_id']);
                $ss[$k3]['status'] = $this->getLev($v3['user_id']);
            }
            $data = $this->groupByInitials($ss, 'user_name');
            foreach ($data as $k1 => $v1) {
                foreach ($v1 as $v4) {
                    $list[] = $v4;
                }
            }
            if($list){
                return json($list);
            }else{
                return 0;
            }
    }

    //通过用户id 找下一级用户id
    public function getChilderId($user_id){
        return Db::connect(config('db_config2')) ->where('user_rank','in','9,10')->name('users')->where("parent_id",$user_id)->select();
        //return D('User')->where('parent_id = '.$user_id)->select();

    }

    /**
     * 二维数组根据首字母分组排序
     * @param  array  $data      二维数组
     * @param  string $targetKey 首字母的键名
     * @return array             根据首字母关联的二维数组
     */
    public function groupByInitials(array $data, $targetKey = 'user_name')
    {
        $data = array_map(function ($item) use ($targetKey) {
            return array_merge($item, [
                'initials' => $this->getInitials($item[$targetKey]),
            ]);
        }, $data);
        $data = $this->sortInitials($data);
        return $data;
    }

    /**
     * 按字母排序
     * @param  array  $data
     * @return array
     */
    public function sortInitials(array $data)
    {
        $sortData = [];
        foreach ($data as $key => $value) {
            $sortData[$value['initials']][] = $value;
        }
        ksort($sortData);
        return $sortData;
    }

    /**
     * 获取首字母
     * @param  string $str 汉字字符串
     * @return string 首字母
     */
    public function getInitials($str)
    {
        if (empty($str)) {return '';}
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str{0});
        }

        $s1  = iconv('UTF-8', 'gb2312', $str);
        $s2  = iconv('gb2312', 'UTF-8', $s1);
        $s   = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }
        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }
        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }
        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }
        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }
        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }
        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }
        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }
        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }
        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }
        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }
        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }
        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }
        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }
        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }
        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }
        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }
        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }
        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }
        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }
        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }
        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }
        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }
        return null;
    }

    //通过id找到下一级用户购买总额
    public function getChilderSum($user_id){
        //$child_list =  D('User')->where('parent_id = '.$user_id)->Field('id')->select();
        //dump($child_list);
        //$sum = M('RechargeCart')->where('user_id in $child_list  AND pay_status = 1')->SUM('recharge_money');
        //return $sum;

        // 取得上级所有直接下级用户id
        //$lower_users_id = M('user')->where('parent_id='.$user_id)->Field('id')->select();
        $lower_users_id=Db::connect(config('db_config2'))->name('users') ->where('user_rank','in','9,10')->where("parent_id",$user_id)->Field('user_id')->select();
        //定义查询上级所有直接下级用户总额条件
        $id_arr['user_id'] = array('in');
        if(empty($lower_users_id)){
            $id_arr['user_id'][] = 0;
            $lower_users_count_money=0.00;
        }else{
            foreach ($lower_users_id as $v)
            {
                $temp_arr[] = $v['user_id'];
            }
            $id_arr['user_id'][] = $temp_arr;
            $lower_users_count_money = Db::table('tea_user_recharge')->where($id_arr)->SUM('rec_money');
        }

        // 取得上级所有直接下级用户购买理茶宝总额
        return intval($lower_users_count_money);
    }


    //获取用户总金额
    public function  getMoneySum($id){
        $data = Db::table('tea_recharge_cart')->where(array('parent_id'=>$id,'pay_status'=>1))->select();
        $sum = 0;
        if($data){
            foreach($data as $k => $v){
                $sum = $v['recharge_money']+$sum;
            }
        }
        return  $sum;
    }

    //获取每个人的推荐人一级（直推）
    public function groom_info($id){

        //$groom_info = Db::table("tea_user")->field('id,user_name,parent_id')->where("parent_id",$id)->select();
        $groom_info = Db::connect(config('db_config2'))->name('users')->where("parent_id",$id) ->where('user_rank','in','9,10')->select();
        $one_rec_monry = 0;
        foreach($groom_info as $k => $v){
            $groom_info[$k]['integral'] = $this->last_integral(intval($v['user_id']));
            $one_rec_monry += $groom_info[$k]['integral']['sum_rec_money'];
        }
        $list['num'] = count($groom_info);
        $list['num_id'] = $this->getId($groom_info);
        //$list['num_info'] = $groom_info;
        $list['sum_one_rec_monry'] = $one_rec_monry;
        return $list;
    }

    //获取每个人二推人数
    public function groom_info_second($groom_info_id){
        $arr = explode(",",$groom_info_id);
        $counts = 0;
        $counts_id = "";
        $counts_sum = 0;
        $count_arr = array();
        for($i = 0;$i< count($arr);$i++){
            $infos = $this->groom_info($arr[$i]);
            $counts += intval($infos['num']);
            if($infos['num_id']!=""){
                $counts_id .= $infos['num_id'] .",";
            }
            //$count_arr = array_merge($count_arr, $infos['num_info']);
            $counts_sum += intval($infos['sum_one_rec_monry']);
        }
        $list['counts'] = $counts;
        $list['counts_id'] = $counts_id;
        //$list['counts_info'] = $count_arr;
        $list['sum_second_rec_monry'] = $counts_sum;
        return $list;
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

    //获取用户最后一次购买的记录
    public function last_integral($user_id){
        //$user_id = intval($data['id']);
        //是否激活
        $integral_data = Db::query("select max(id) as 'id' from tea_integral where user_id = $user_id");
        if($integral_data){
            //是否购买
            $user_integral_data = Db::query("select max(id) as 'id' from tea_user_recharge where user_id = $user_id and pay_status = 1 ");

            if($user_integral_data){
                //未购买
                $list = array("total_sum"=>0,"surplus_inte"=>0,"back_inte"=>0,"tea_inte"=>0,"tea_ponit_inte"=>0,"reg_inte"=>0,"lev"=>0);
            }else{
                $list = array("total_sum"=>$user_integral_data['total_inte'],"surplus_inte"=>$user_integral_data['total_inte'],
                    "back_inte"=>0,"tea_inte"=>0,"tea_ponit_inte"=>0,"reg_inte"=>$user_integral_data['reg_rec'],"lev "=>$user_integral_data['lev']);
            }
        }else{
            $list = array("total_sum"=>$integral_data['total_sum'],"surplus_inte"=>$integral_data['surplus_inte'],"back_inte"=>$integral_data['back_inte'],
                "tea_inte"=>$integral_data['tea_inte'],"tea_ponit_inte"=>$integral_data['tea_ponit_inte'],"reg_inte"=>0,"lev "=>$integral_data['lev']);
        }
        $sum_user_integral_data = Db::query("select sum(rec_money) as 'sum_rec_money' from tea_user_recharge where user_id = $user_id");
        $list['sum_rec_money'] = $sum_user_integral_data[0]['sum_rec_money'];
        //$data['integral'] = $list;
        return $list;
    }

    //实名认证
    public function realname()
    {
        if($_POST){
            $dataaaa=array(
                'test'=>json_encode(input('post.')),
                'trade_status'=>'实名认证'
            );
            Db::table('tea_test')->insert($dataaaa);
            $user_id = session('lcb_user_id');
            $bank_name = trim(input('post.bank_name'));   //开户行
            $bank = trim(input('post.bank'));    //银行卡号
            //支付密码
            $pay_pwd = trim(input('post.pay_pwd1'));
            $real_name = trim(input('post.real_name'));
            $sex = trim(input('post.sex'));
            $idcard = trim(input('post.idcard'));
            //判断有没有
            $real_user = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)->find();
            if($real_user){
                $res1 = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)
                    ->update([
                        //'user_id'=>$user_id,
                        'real_name' => $real_name,
                        'self_num' => $idcard,
                        'bank_name'=>$bank_name,
                        'bank_card'=>$bank,
                    ]);
            }else{
                //插入到验证表
                $res1 = Db::connect(config('db_config2'))->name("users_real")
                    ->insert([
                        'user_id'=>$user_id,
                        'real_name' => $real_name,
                        'self_num' => $idcard,
                        'bank_name'=>$bank_name,
                        'bank_card'=>$bank,
                    ]);
            }
            if($res1){
                $sex = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->setField('sex',$sex);
            }
            $data['real_name'] = $real_name;
            $data['sex'] = $sex;
            $data['idcard'] = $idcard;
//            $data['face_img'] = $face;
//            $data['back_img'] = $back;
            $data['is_pass'] = 0;
            $data['is_first'] = 1;
            $data['addtime'] = time();
            $data['user_id'] = $user_id;
//            //更新理茶宝用户表的二级安全密码
//            $pay_pwd = md5(md5($pay_pwd).$user_info['tm_salt']);   //支付密码
//            Db::table('tea_user')->where('user_id',$user_id)->setField('pay_pwd',$pay_pwd);   //修改支付密码
            $user = Db::table('tea_real_name')->where("user_id",$user_id)->find();
            if(!$user){
                $cc = Db::table('tea_real_name')->insert($data);
            }else{
                $cc = Db::table('tea_real_name')->where("user_id",$user_id)->update($data);
            }

//            $p_user = trim(input('post.p_user'));  //推荐人用户名
//            if($p_user){
//                $p_info = Db::connect(config('db_config2'))->name("users")->where("user_name='$p_user'")->find();
//                //return json(array('data'=>$p_info));
//                if(!$p_info){
//                    return json(array('status'=>'4'));
//                }else{
//                    $parent_id = $p_info['user_id'];
//                    $data2 = Db::table('tea_user')->where("user_id",$parent_id)->find();
//                    if($data2['wait']==0){
//                        return json(array('status'=>5,'data'=>'被冻结'));
//                    }else{
//                        //推荐人是否购买产品
//                        $info = Db::table('tea_user_recharge')->where('pay_status = 1 and user_id='.$data2['user_id'])->order('id desc')->limit(1)->find();
//                        if(!$info){
//                            return json(array('status'=>6,'data'=>'未激活'));
//                        }else{
//                            $parent_id = $p_info['user_id'];
//                        }
//                    }
//                }
//            }

            if($cc){

                $this->success('验证通过',url('user/myinfo'));
            }else{
                $this->error('服务器维护中');
            }
        }else{
            return $this->fetch();
        }
    }


    //图片
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('zhengmian');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    public function upFile($img, $dir)
    {
        //dump($_FILES[$img]);
        if (!isset($_FILES[$img]) || $_FILES[$img]['error'] != 0 || $_FILES[$img]['size'] > 2097152) {
            return false;
        }
        $upload = new \Think\Upload();
        $upload->rootPath = $dir;
        $info = $upload->uploadOne($_FILES[$img]);
        if (!$info) {
            $this->error = $upload->getError();
        }
        //上传后的图片地址
        $img = $dir . $info['savepath'] . $info['savename'];
        return $img;
    }

    //用户的登录
    public function login_login(){
        //获取用户输入的用户名 密码
        $username = trim(input('post.username'));
        $password = trim(input('post.password'));
        //判断用户名是否存在
        $userobj = new Huiyuan();
        //通过访问淘米平台数据库查看用户名是否存在
        $user = Db::connect(config('db_config2'))->name("users")->where('user_name',$username)->find();
        //判断是不是会员
//        if($user && intval($user['user_rank'])!=10) return json(array("data" => "不是会员无法登陆", 'status' => 9));
        if(!$user){
            return json(array("data"=>"用户不存在",'status'=>2));
        }else{
            //是否冻结
            $user_id= $user['user_id'];
            $usernifo = Db::table('tea_user')->where('user_id',$user_id)->find();
            if($usernifo){
                if($usernifo['wait'] == 0){
                    return json(array("data"=>"该账户被冻结",'status'=>3));
                }
            }else{
                //将这个会员插入到数据库里面去
                Db::table('tea_user')->insert(['user_id'=>$user_id,'is_ceo'=>0]);
            }
            //用户名存在  判断密码是否正确
            $password =empty($user['tm_salt'])?  md5($password): md5(md5($password).$user['tm_salt']);
            $res = $userobj->pwd_exit($username,$password);
            if($res){
                session('v_user_shop','');
                $list['username'] = $username;
                $list['password'] = $password;
                session('v_user_shop',$list);
                return json(array("data"=>"登陆成功",'status'=>1));
            }else{
                return json(array("data"=>"密码错误",'status'=>0));
            }
        }
    }



    //------------------------------------------
    //   10/31
    //显示我的个人中心页面
    public function myinfo()
    {
        $this->checkLogin();
        $user_id        = session('lcb_user_id');
        $user_rank      = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->field('user_rank,user_name,user_picture')->find();
        $userinfo       = Db::table('tea_user')->where("user_id",$user_id)->find();
        //如果昵称不存在就显示用户名
        if(empty($user_rank['nick_name'])){
            $userinfo['user_name']          = $user_rank['user_name'];
        }else{
            $userinfo['user_name']          = $user_rank['nick_name'];
        }
        $userinfo['user_rank']              = $user_rank['user_rank'];
        $integral = Db::table('tea_user')->alias('a')
            ->join('tea_integral b','a.user_id=b.user_id','LEFT')
            ->join('tea_integral_log c','a.user_id=c.user_id','LEFT')
            ->field('b.surplus_inte,c.surplus_inte as sfjf')
            ->where("a.user_id=$user_id AND b.is_ceo=0 and c.use_type = 1 ")
            ->order('c.id desc')
            ->limit(1)
            ->find();
        if( empty($integral['sfjf'])) {
            $userinfo['surplus_inte']       = $integral['surplus_inte'];
            $userinfo['sfjf']               =0.00;
        }else{
            $userinfo['surplus_inte']       = $integral['surplus_inte'];
            $userinfo['sfjf']               = substr($integral['sfjf'],1);
        }
        if($user_rank['user_picture'] ==''){
            $userinfo['user_picture']       ='http://'.$_SERVER['HTTP_HOST'].'/newtea/images/mrimg.png';
        }else{
            $userinfo['user_picture']       =$user_rank['user_picture'];
        }
        $this->assign('data',$userinfo);
        return $this->fetch();
    }
}
