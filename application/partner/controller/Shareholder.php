<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/5/4
 * Time: 18:52
 */
namespace app\partner\controller;
use think\Cookie;
use think\Request;
use app\partner\model\Gudong;
use think\Session;
use think\Db;
use think\Upload;
use app\tmvip\controller\Api;
use think\cache;
class Shareholder extends Co
{

    /**
     *                             _ooOoo_
     *                            o8888888o
     *                            88" . "88
     *                            (| -_- |)
     *                            O\  =  /O
     *                         ____/`---'\____
     *                       .'  \\|     |//  `.
     *                      /  \\|||  :  |||//  \
     *                     /  _||||| -:- |||||-  \
     *                     |   | \\\  -  /// |   |
     *                     | \_|  ''\---/''  |   |
     *                     \  .-\__  `-`  ___/-. /
     *                   ___`. .'  /--.--\  `. . __
     *                ."" '<  `.___\_<|>_/___.'  >'"".
     *               | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     *               \  \ `-.   \_ __\ /__ _/   .-` /  /
     *          ======`-.____`-.___\_____/___.-`____.-'======
     *                             `=---='
     *          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
     *                     佛祖保佑        永无BUG
     *            佛曰:
     *                   写字楼里写字间，写字间里程序员；
     *                   程序人员写程序，又拿程序换酒钱。
     *                   酒醒只在网上坐，酒醉还来网下眠；
     *                   酒醉酒醒日复日，网上网下年复年。
     *                   但愿老死电脑间，不愿鞠躬老板前；
     *                   奔驰宝马贵者趣，公交自行程序员。
     *                   别人笑我忒疯癫，我笑自己命太贱；
     *                   不见满街漂亮妹，哪个归得程序员？
     */
    //股东注册
    public function register()
    {
        if(request()->isAjax()){
                $user_name = input('post.user_name');
                $password = input('post.password1');
                $mobile_phone = input('post.mobile_phone');
                $getCode = input('post.code');
                $re_username = input('post.re_username');
                //推荐人的判断在html页面进行
                $code = session('code');
                //判断验证码是否正确
                if($code != $getCode){
                    return json(array('status'=>2,'msg'=>'验证码错误'));
                }
                //如果有推荐人 判断推荐人是否满足条件
                if($re_username){
                    //根据推荐人用户名查询其信息
                    $re_info = Db::connect(config('db_config2'))->name("users")->where("user_name",$re_username)->find();
                    if(!$re_info){
                        return json(array('status'=>0));
                    }else{
                        $re_userid = $re_info['user_id'];
                        $re_info2 = Db::table('tea_user')->where("user_id",$re_userid)->find();
                        if($re_info2['wait'] == 0){
                            return json_encode(array('status'=>5,'data'=>'被冻结'));
                        }else{
                            //推荐人是否购买产品
                            $info = Db::table('tea_user_recharge')->where('user_id='.$re_userid)->order('id desc')->limit(1)->find();
                            if(!$info){
                                return json_encode(array('status'=>4,'data'=>'未激活'));
                            }
                        }
                    }
                    $parent_id = $re_userid;
                }else{
                    //没有填写推荐人  则推荐人id默认为0
                    $parent_id = 0;
                }
                $tm_salt = rand(0000,9999);     //盐
                $password = md5(md5($password).$tm_salt);   //密码

                //要插入的数组信息
                $data = array(
                    'user_name' => $user_name,        //用户名
                    'password' => $password,          //密码
                    'mobile_phone' => $mobile_phone,  //手机号码
                    'parent_id' => $parent_id,        //推荐人id
                    'tm_salt' => $tm_salt,            //盐
                    'reg_time' => time(),   //注册时间
                );
                $res = Db::connect(config('db_config2'))->name("users")->insert($data);
                if(!$res){
                    return json_encode(array('msg'=>'内部维护中','status'=>3));
                }else{
                    //同步理茶宝用户
                    $user_info = Db::connect(config('db_config2'))->name("users")->where('user_name',$user_name)->find();
                    $user_id = $user_info['user_id'];
                    $data2 = array(
                        'user_id'=>$user_id,
                        'wait'=>1,
                        'is_ceo'=>1
                    );
                    $res2 = Db::table('tea_user')->insert($data2);
                    if($res2){
                        return json_encode(array('msg'=>"注册成功",'status'=>1));
                    }else{
                        return json_encode(array('msg'=>"内部维护中",'status'=>3));
                    }
                }
            }else{
                return $this->fetch();
            }
    }

    //判断用户名是否已经存在
    public function checkName()
    {
        if(request()->isAjax()){
            $user_name = input('post.userName');
            $res = Db::connect(config('db_config2'))->name('users')->where("user_name",$user_name)->find();
            if($res){
                //用户名已存在
                return json(array('status'=>0));
            }else{
                return 1;
            }
        }
    }

    //判断手机号是否已经存在
    public function checkTel()
    {
        if(request()->isAjax()){
            $mobile_phone = input('post.mobile_phone');
            $res = Db::connect(config('db_config2'))->name('users')->where("mobile_phone",$mobile_phone)->find();
            if($res){
                //手机号已存在
                return json(array('status'=>0));
            }
        }
    }

    //判断手机登录的短息验证码是否正确
    public function checkCode()
    {
        $getCode = input('post.code');
        $code = session('code');
        $tel = input('post.tel');
        //$res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();
        if($getCode != $code){
            return json_encode(array('msg'=>"验证码错误",'status'=>0));
        }else{
            $data = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();

            $user_id = $data['user_id'];
            $wait = Db::table('tea_user')->where("user_id",$user_id)->value('wait');
            //dump($wait);
            if($wait == 0){
                return json_encode(array('status'=>2,'冻结'));
            }
            cookie::set('admin',$data);
            session::set('admin',$data);
            cookie::set('user',$data);
            session::set('user',$data);
            session::set('user_id',$data['user_id']);
            return json_encode(array('msg'=>"登录成功",'status'=>1));
        }
    }

    //手机号码快捷登录
    public function mb_login()
    {
        if(request()->isAjax()){
            $mobile_phone = input('post.mobile_phone');
            $mobileCode = input('post.mobileCode');
            //验证用户是否存在
            $info = Db::connect(config('db_config2'))->name('users')->where("mobile_phone",$mobile_phone)->find();
            if(!$info){
                return json(array('msg'=>"手机号不存在",'status'=>2));
            }
            $user_id = $info['user_id'];
            $userinfo = Db::table('tea_user')->where("user_id",$user_id)->find();
            if($userinfo['wait'] == 0){
                return json(array('msg'=>"该账户已冻结",'status'=>4));
            }
            if($userinfo['is_ceo'] == 0){
                //return json(array('status'=>3,'data'=>"您目前还不是股东"));
            }
            $code = session('code');
            //判断短信验证码是否正确
            if($mobileCode != $code){
                return jsone(array('msg'=>"验证码错误",'status'=>0));
            }else{
                session::set('user',$info);
                Cookie::set('user',$info);
                //记录最后的登录时间

                Db::table('tea_user')->where('user_id',$user_id)->setField('last_time',time());
                Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->setField('last_login', time());
                return json(array('msg'=>"登陆成功",'status'=>1));
            }
        }else{
            return $this->fetch();
        }
    }

    //判断手机号码登录权限
    public function cmobile_phone()
    {
        if(request()->isAjax()){
            $tel = input('post.tel');
            //$tel = '13886643564';
            $res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();
            //dump($res);
            if(empty($res)){
                return json_encode(array('data'=>"手机号不存在",'status'=>0));
            }else{
                $user_id = $res['user_id'];
                $wait = Db::table('tea_user')->where("user_id",$user_id)->value('wait');
                if($wait == 0){
                    return json_encode(array('status'=>2,'冻结'));
                }
                return json_encode(array('data'=>"手机号已存在",'status'=>1));
            }
        }
    }
    //显示用户登录页面
    public function login(){
        if(!session('user_id')){
            return $this->fetch();
        }else{
            $this->redirect('index/index');
        }
    }

    //验证用户的登录的业务逻辑
    public function loginwork()
    {
            //获取用户输入的用户名 密码
            $username = trim(input('post.username'));
            $password = trim(input('post.password'));

            //判断用户名是否存在
            $userobj = new Gudong();
            //print_r($userobj);
            //通过访问淘米平台数据库查看用户名是否存在
            $user = Db::connect(config('db_config2'))->name("users")->where('user_name', $username)->find();
            //判断是不是股东
//            if($user && intval($user['user_rank'])!=9) return json(array("data" => "不是股东无法登陆", 'status' => 9));
            if (!$user) {
                return json(array("data" => "用户不存在", 'status' => 2));
            } else {
                //是否冻结
                $user_id = $user['user_id'];
                $usernifo = Db::table('tea_user')->where('user_id', $user_id)->find();
                //判断是否为会员
                if($usernifo){
                    if ($usernifo['wait'] == 0) {
                        return json(array("data" => "该账户被冻结", 'status' => 3));
                    }
                }else{
                    //将这个会员插入到数据库里面去
                    Db::table('tea_user')->insert(['user_id'=>$user_id,'is_ceo'=>1]);
                }


//                if ($usernifo['is_ceo'] == 0) {
//                    //return json(array('status' => 4, 'data' => "不是股东"));
//                }
                //用户名存在  判断密码是否正确
                $password =$user['tm_salt']<=0 ? md5($password): md5(md5($password).$user['tm_salt']);
                $res = $userobj->pwd_exit($username, $password);
                if ($res) {
                    session('user_shop','');
                    $list['username'] = $username;
                    $list['password'] = $password;
                    session('user_shop',$list);
                    return json(array("data" => "登陆成功", 'status' => 1));
                } else {
                    return json(array("data" => "密码错误", 'status' => 0));
                }
            }

    }

    //推荐人验证
    public function pTelVer()
    {
        $p_user = trim(input('post.p_user'));
        if ($p_user) {
            $data = Db::connect(config('db_config2'))->name("users")->where("user_name='$p_user'")->find();
            if (!$data) {
                return json(array('status'=>0,'data'=>'不存在'));
            } else {
                $user_id = $data['user_id'];
                $data2 = Db::table('tea_user')->where("user_id",$user_id)->find();
                if($data2['wait']==0){
                    return json(array('status'=>2,'data'=>'被冻结'));
                }else{
                    //推荐人是否购买产品
                    $info = Db::table('tea_user_recharge')->where('pay_status = 1 and user_id='.$data['user_id'])->order('id desc')->limit(1)->find();
                    if(!$info){
                        return json(array('status'=>3,'data'=>'未激活'));
                    }else{
                        return json(array('status'=>1,'data'=>'ok'));
                    }
                }
            }
        }
    }

    //忘记密码
    public function reppwd()
    {
        if(request()->isAjax()){
            $tel = input('post.tel');
            //通过手机号码获取用户的信息
            $userinfo = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();

            //判断该账户是否被冻结
            $user_id = $userinfo['user_id'];
            $wait = Db::table('tea_user')->where("user_id",$user_id)->value('wait');
            if($wait == 0){
                return json(array('status'=>4,'msg'=>"您的账户已被冻结,不能修改密码"));
            }

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
                return json(array('data'=>"亲,两次密码必须一致哦!!!",'status'=>2));
            }
            $salt = $userinfo['tm_salt'];
            $password = md5(md5($password1).$salt);

            //判断密码修改是否成功
            $res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->setField('password',$password);
            if($res){
                return json(array('data'=>"密码修改成功",'status'=>1));
            }else{
                return json(array('data'=>"内部维护中",'status'=>0));
            }
        }else{
            return $this->fetch();
        }

    }

    //退出登录
    public function loginout(){
        cookie::set('admin',null);
        session::set('user', null);
        session::set('user_id', null);
        session::set('phone',null);
        return json(1);
    }

    //短信验证码 OK
    public function test()
    {
        $code = rand(100000,999999);
        session::set('code',$code);
        //获取传输过来的手机号码
        $tel = input('post.tel');
        session::set('boolen',$tel);
        include_once('../Api/top/TopClient.php');
        $c = new \TopClient;
        $c->appkey = '23662994';
        $c->secretKey = '12c4693b91926a394e8ca913e132be01';
        include_once('../Api/top/request/AlibabaAliqinFcSmsNumSendRequest.php');
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("我的茶馆");
        $req->setSmsParam("{\"code\":\"$code\",\"product\":\"测试\"}");
        $req->setRecNum("$tel");//电话号码
        $req->setSmsTemplateCode("SMS_62170183");
        $resp = $c->execute($req);
        if($resp->result->msg){
            //$this->ajaxReturn(array('s'=>1,'msg'=>'短信发送成功'));
            return json(array('s'=>1,'msg'=>'短信发送成功'));
            //$this->success('短信发送成功');
        }else{
            //$this->ajaxReturn(array('s'=>0,'msg'=>'短信发送失败'));
            return json(array('s'=>0,'msg'=>'短信发送失败'));
            //$this->success('短信发送失败');
        }
    }

    //显示我的付款码页面
    //显示我的付款码
    public function paycode(){
        $this->checkLogin();
        $time=time();
        //删除所有超时的二维码信息
        Db::table('tea_session')->where('overtime','<',$time)->delete();
        $user_id=session('user_id');
               $user_session=Db::table('tea_session')->where('user_id',$user_id)->where('is_ceo',0)->find();
        if($user_session){
            //当前用户已经有二维码信息入库，判断二维码是否有效
            if($time > intval($user_session['overtime'])){
                //$key=$user_id . chr(rand(97,122)) . uniqid();
              $key=$user_id . time();
                //二维码失效
                Db::table('tea_session')->where('user_id',$user_id)->where('is_ceo',0)->delete();
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
            //$key=$user_id . chr(rand(97,122)) . uniqid();
          $key=$user_id . time();
            $data=array(
                'user_id'=>$user_id,
                'addtime'=>time(),
                'overtime'=>time()+600,
                'key_s'=>$key,
                'salt'=>md5($key.$time)
            );
            Db::table('tea_session')->insert($data);
        }

        return view('paycode',['data'=>$key]);
    }

    //显示我的邀请好友页面
    public function friends(){
        $this->checkLogin();
        $user_id=session('user_id');
        $url_host=$_SERVER['HTTP_HOST'];
        $url = "http://$url_host/partner/shareholder/registeruser?user_id=$user_id ";   //扫码登录(好的)
        return view('friends',['data'=>$url]);
    }

    //显示用户名的信息
    public function findusenamebyid(){
        $user_id=intval(input('post.user_id'));
        $userApi=new Api();
        $user_info=$userApi->getUserInfo($user_id,'','');
        $username= $user_info['info']['list'][0]['user_name'];
        return json($username);
    }

    //通过扫描二维码生成的注册页面
    public function registeruser(){
        return view('registeruser');
    }
    //显示个人中心我的销售快报页面
    public function sale(){
        return view('sale');
    }

    //显示记录页面
    public function integralLog(){
        return view('integrallog');
    }
    //记录页面的消费记录详情

    public function logIndex(){
        $this->checkLogin();
        $user_id = intval(cookie('user')['user_id']);

        $where  = " id > 0 and user_id = $user_id and wallet = 0 ";

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

        if($type==2){

            $where .= " and fix = 1 ";
        }
        if($type==3){
            $where .= " and use_type = 1 and fix = 2 ";
        }
        if($type==4){
            $where .= " and (shopping = 1  or online = 1 )";
        }
        $page = intval(input('post.page'));
        $count = 8;
        if($page){

            $page = (intval($page) - 1)*$count;

        }else{
            $page = 0;
        }
        //dump($where);

        $data = Db::table("tea_integral_log")->where($where)->limit($page,$count)->select();

        $count = Db::table("tea_integral_log")->where($where)->count('id');

        return json_encode(array('list'=>$data,'count'=>$count));
    }

    // 显示登入用户的交易类型
    public function recordType()
    {
        $this->checkLogin();
        //
        $user_id = intval(session('user_id'));
        $pageSize = 8;
        $page = intval(input('post.page'))?(intval(input('post.page'))-1)*$pageSize : 0;
        $where = "user_id= $user_id ";
        $time1 = input('post.time');
        if(!empty($time1)){
            $time1=strtotime($time1);
            //$where .= " AND  addtime >= $time1 ";
        }
        $time2 = time();
//        $where = 'user_id='.$user_id .'AND wallet='.$wallet;

        $type = intval(input('post.type'));     //交易类型
        $useType=intval(input('post.usetype'));     //交易记录  支出还是收益
        if($useType==1){
            //收益
            $where .= " AND use_type=1 ";
        }
        if($useType==2){
            //支出
            $where .= " AND use_type=2 ";
        }
        if($type==1){

            $where .= "AND tea_ponit_inte!='+0'";
        }
        if($type==2){

            $where .= "AND  tea_inte!= '+0'  ";
        }
        if($type==3){
            $where .= " AND use_type = 1 and fix = 2 ";
        }
        if($type==4){
            //$where .= " AND (shopping = 1  or online = 1 )";
        }
//        dump($where);die;
        $data = Db::table('tea_integral_log')
              ->where($where)
              ->where('addtime','between',"$time1,$time2")
              ->limit($page,$pageSize)
              ->order('id desc')
              ->select();
        $count = Db::table("tea_integral_log")->where($where)->count('id');
        return json(array('list'=>$data,'count'=>$count));
    }

    //显示我的预约页面
    public function appointment(){
        return view('appointment');
    }


    public function accountlink()
    {
        $this->checkLogin();
        return $this->fetch();
    }
    //显示个人中心页面
    public function cus_info(){
        $this->checkLogin();
        if(request()->isAjax()) {
            $user_id = session('user_id');
            $is_real = Db::connect(config('db_config2'))->name('users_real')->where("user_id", $user_id)->find();
            if ($is_real) {
                return json(array('status' => 1, 'data' => $is_real));
            } else {
                $parent_id = Db::connect(config('db_config2'))->name('users')->where("user_id", $user_id)->value('parent_id');
                if ($parent_id == 0) {
                    return json(array('parent_id' => 0));
                } else {
                    return json(array('parent_id' => $parent_id));
                }
            }
        }
        return $this->fetch();
    }

    //判断个人中心的一些页面的显示
    public function checkUser(){

    }

    //个人信息的展示
    public function cus_infomation(){
        $this->checkLogin();
        $user_id = session('user_id');
        $user = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->field('nick_name,mobile_phone,reg_time,user_picture')->find();
        $user2 = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->field('real_name,self_num,bank_name')->find();
        $rec_addtime = Db::table('tea_user_recharge')->where("user_id",$user_id)->value('rec_addtime');
        $user['real_name'] = $user2['real_name'];
        $user['self_num'] = $user2['self_num'];
        $user['bank_name'] = $user2['bank_name'];
        $user['rec_addtime'] = $rec_addtime;
        if($user['user_picture'] ==''){
            $user['user_picture']='http://'.$_SERVER['HTTP_HOST'].'/newtea/images/mrimg.png';
        }
        $this->assign('data',$user);
        return $this->fetch();
    }
    //显示实名认证的页面
    public function real_name(){
        $this->checkLogin();
        if(request()->isPost()){
            $user_id = cookie('user')['user_id'];
            $bank_name = trim(input('post.bank_name'));   //开户行
            $bank = trim(input('post.bank'));    //银行卡号
            //支付密码
            $pay_pwd = trim(input('post.pay_pwd1'));

            $face_img = $this->request->file('zhengmian');
            $back_img = $this->request->file('fanmian');
            if($face_img){
                $info1 = $face_img->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info1){
                    $face1 = $info1->getSaveName();

                    $face2 = "local.lcb.com/Lcb/tea_treasure/public/uploads/".$face1;
                }else{
                    // 上传失败获取错误信息
                    echo $face_img->getError();
                }
            }
            if($back_img){
                $info2 = $back_img->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info2){
                    $back1 = $info2->getSaveName();
                    $back2 = "local.lcb.com/Lcb/tea_treasure/public/uploads/".$back1;
                }else{
                    // 上传失败获取错误信息
                    echo $back_img->getError();
                }
            }
            $dir= '/public/uploads';
            $face = $dir . $face1;
            $back = $dir . $back1;
            $real_name = trim(input('post.real_name'));
            $sex = trim(input('post.sex'));
            $idcard = trim(input('post.idcard'));
            //插入到验证表
            $res1 = Db::connect(config('db_config2'))->name("users_real")
                ->insert([
                    'user_id'=>$user_id,
                    'real_name' => $real_name,
                    'self_num' => $idcard,
                    'bank_name'=>$bank_name,
                    'bank_card'=>$bank,
                    'front_of_id_card'=>$face,
                    'reverse_of_id_card'=>$back,
                ]);
            if($res1){
                $sex = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->setField('sex',$sex);
            }
            $data['real_name'] = $real_name;
            $data['sex'] = $sex;
            $data['idcard'] = $idcard;
            $data['face_img'] = $face;
            $data['back_img'] = $back;
            $data['is_pass'] = 0;
            $data['is_first'] = 1;
            $data['addtime'] = time();
            $data['user_id'] = cookie('admin')['user_id'];

            $user = Db::table('tea_real_name')->where("user_id",$user_id)->find();
            if(!$user){
                $cc = Db::table('tea_real_name')->insert($data);
            }else{
                $cc = Db::table('tea_real_name')->where("user_id",$user_id)->update($data);
            }

            $p_user = trim(input('post.p_user'));  //推荐人用户名
            if($p_user){
                $p_info = Db::connect(config('db_config2'))->name("users")->where("user_name='$p_user'")->find();
                //return json(array('data'=>$p_info));
                if(!$p_info){
                    return json(array('status'=>'4'));
                }else{
                    $parent_id = $p_info['user_id'];
                    $data2 = Db::table('tea_user')->where("user_id",$parent_id)->find();
                    if($data2['wait']==0){
                        return json(array('status'=>5,'data'=>'被冻结'));
                    }else{
                        //推荐人是否购买产品
                        $info = Db::table('tea_user_recharge')->where('pay_status = 1 and user_id='.$data2['user_id'])->order('id desc')->limit(1)->find();
                        if(!$info){
                            return json(array('status'=>6,'data'=>'未激活'));
                        }else{
                            $parent_id = $p_info['user_id'];
                        }
                    }
                }
            }

            if($cc){

                $this->success('验证通过',url('index/custom_info'));
            }else{
                $this->error('服务器维护中');
            }
        }else{
            return $this->fetch();
        }
    }

    //是否需要关联
    public function account_link(){
        $unionid = session('unionid');
        $user_id = session('user_id');
        $wx_data = Db::connect(config('db_config2'))->name('connect_user')->where(array('open_id' => $unionid, 'connect_code' => 'sns_wechat'))->find();

        $wechat_id = Db::connect(config('db_config2'))->name('wechat')->where(array('default_wx' => 1, 'status' => 1))->find();

//
        $wx_datas = Db::connect(config('db_config2'))->name('wechat_user')->where(array('unionid' => $unionid, 'wechat_id' => $wechat_id['id']))->find();
        //return json_encode(array('status' =>$wx_datas));
        //$wx_datas = Db::connect(config('db_config2'))->name('wechat_user')->where(array('unionid' => $unionid))->find();
        if($wx_data['user_id'] == $wx_datas['ect_uid']){
            return json_encode(array('status' =>0));
        }else{
            //已关联
            return json_encode(array('status' =>1));
        }
    }
    //关联完了进入的页面
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

    //关联账户
    public function relative_user()
    {

        $name = input('post.name');
        $user = Db::connect(config('db_config2'))->name('users')->where('user_name', $name)->find();
        if (empty($user)) {
            return json_encode(array('status' => 0, 'msg' => '用户不存在'));
        } else {
            //判断密码
            $password = input('post.password');
            $tm_salt = $user['tm_salt'];
            $password = md5(md5($password) . $tm_salt);
            if ($password == $user['password']) {
                //绑定
                $user_id = intval(session('user_id'));
                $relative1 = Db::connect(config('db_config2'))->name("connect_user")->where('user_id', $user_id)->find();
                if (!$relative1) {
                    return json_encode(array('status' => 0, 'msg' => '关联出错'));
                } else {
                    //是否存在
                    $connect_user_id = Db::connect(config('db_config2'))->name('connect_user')->where(array('user_id' => intval($user['user_id']), 'connect_code' => 'sns_wechat'))->count();
                    if (($connect_user_id == 0) && (intval($user['user_id'] != intval($user_id)))) {
                        $relative = Db::connect(config('db_config2'))->name("connect_user")->where('id', $relative1['id'])->update(['user_id' => intval($user['user_id'])]);

                        if ($relative) {
                            return json_encode(array('status' => 1, 'msg' => '关联完成'));
                        } else {
                            return json_encode(array('status' => 0, 'msg' => '关联出错'));
                        }
                    } else {
                        return json_encode(array('status' => 0, 'msg' => '此用户已关联'));
                    }
                }
            } else {
                return json_encode(array('status' => 0, 'msg' => '密码错误'));
            }
        }
    }

    //个人中心修改密码
    public function edit_password(){
        $this->checkLogin();
        if(request()->isAjax()){
            $user_id = session('user_id');
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


    //个人中心我的推荐
    public function recommender()
    {
        $this->checkLogin();
        $data_two = array();
        $ss=$a=$info_list=$data_one = array();

        if(request()->isAjax()){
            //当前会员的user_id
            $user_id = session('user_id');
            //当前会员的下一级的user_id
            $child_user_id = Db::connect(config('db_config2'))->name('users')->where("parent_id",$user_id)->field('user_id,user_name')->select();


            $user_info_list = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name,parent_id')->select();
            //下一级的用户id
            foreach($user_info_list as $key=>$value){
                if($value['parent_id'] == $user_id){
                    $info_list[] = $value;
                }
            }
            //dump($info_list);die;

            //下二级用户id
            foreach($user_info_list as $k=>$v){
                //$a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
                foreach($info_list as $k1 =>$v1){
                    if($v['parent_id'] == $v1['user_id']){
                        $a[] = $v;
                    }
                }
            }
            //dump($a);

            foreach ($a as $k3 => $v3) {
                //$ss[$k3]['sum'] = $this->getChilderSum($v3['id']);
                $ss[$k3]['recharge_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('recharge_money');
                $ss[$k3]['again_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('again_money');
                $ss[$k3]['status'] = $this->getLev($v3['parent_id']);
                $ss[$k3]['parent_id'] = $a[$k3]['parent_id'];
            }
//dump(count($ss));
//            for($i=0;$i<count($ss);$i++){
//                for($j=1;$j<$i;$j++){
//                    if($ss[$i]['parent_id'] == $ss[$j]['parent_id']){
//                        $ss[$i]['recharge_money'] = $ss[$i]['recharge_money']+$ss[$j]['recharge_money'];
//                        $ss[$i]['again_money'] = $ss[$i]['again_money']+$ss[$j]['again_money'];
//                        unset($ss[$j]);
//                    }
//                }
//            }

            for($i=0;$i<count($ss);$i++){
                for($j=1+$i;$j<count($ss);$j++){
                    if($ss[$i]['parent_id'] == $ss[$j]['parent_id']){
                        $ss[$i]['recharge_money'] = $ss[$i]['recharge_money']+$ss[$j]['recharge_money'];
                        $ss[$i]['again_money'] = $ss[$i]['again_money']+$ss[$j]['again_money'];
                        array_splice($ss[$j],0,count($ss[$j]));
                        $i = 0;
                    }
                }
            }
            for($r = 0; $r< count($ss) ; $r++){
                if(empty($ss[$r])){
                    //$ss[$r] = $ss[$r+1];
                    unset($ss[$r]);
                }
            }

            //$ss['sum'] = $ss['again_money']+$ss['recharge_money'];
            foreach($ss as $k => $v){
                $ss[$k]['sum'] = $ss[$k]['again_money']+$ss[$k]['recharge_money'];
            }




            foreach($child_user_id as $k => $v){
                $child_user_id[$k]['status'] = $this->getLev($v['user_id']);
            }
            if($ss || $child_user_id){
                $data['aa'] = $child_user_id;
                $data['ss'] = $ss;
                return json(array('data'=>$data,'status'=>1));
            }else{
                //$child_user_id['ss'] = $ss;

                return json(array('status'=>0));
            }
        }else{
            //当前会员的用户id
            $user_id = session('user_id');

            //下一级的user_id
            $child_user_id = Db::connect(config('db_config2'))->name('users')->where("parent_id",$user_id)->field('user_id,user_name')->select();

            foreach($child_user_id as $k => $v){

//start
                //取得上级所有直接下级用户id (二级的user_id)
                $lower_users_id[] = Db::connect(config('db_config2'))->name('users')->where('parent_id='.$v['user_id'])->Field('user_id')->select();
//end
                foreach($lower_users_id as $k1=>$v1){
                    //dump($v1);echo 1;
                    foreach($v1 as $k2=>$v2){
                        //dump($v2);echo 2;

                        $child_user_id[$k1]['recharge_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->sum('recharge_money');
                        $child_user_id[$k1]['again_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->value('again_money');
                        $child_user_id[$k1]['sum'][] = $child_user_id[$k1]['recharge_money']+$child_user_id[$k1]['again_money'];
//                        foreach($child_user_id as $m=>$n){
//                            //dump($n);
//                            if(!empty($n['recharge_money']) || !empty($n['again_money'])){
//                                $child_user_id[$k1]['sum'] = $n['recharge_money']+$n['again_money'];
//                            }
//                        }
                    }
                }
                if(empty($child_user_id[$k1]['sum'])){
                    $child_user_id[$k]['sum'] = 0;
                }
                $child_user_id[$k]['status'] = $this->getLev($v['user_id']);
            }

//dump($lower_users_id);


            $res = $this->getNumber($user_id);
            //下一级人数
            $this->assign('one',$res['one']);
            //下二级人数
            $this->assign('two',$res['two']);
            //dump($res);

            //$total = $this->getCateTree($user_id);
            $data = $user_info_list = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name,parent_id')->select();
            //所有下一级
            foreach($data as $k1=>$v1){
                if($v1['parent_id'] == $user_id){
                    $data_one[] = $v1;
                }
            }
            //所有下二级
            foreach($data as $k=>$v){
                foreach($data_one as $k1 => $v1){
                    if($v['parent_id'] == $v1['user_id']){
                        $data_two[] = $v;
                    }
                }
            }

            //合并下属的一级 二级 会员
            $total = array_merge($data_one,$data_two);

            //团队总人数
            $num_num = count($total)+1;
            $this->assign('num_num',$num_num);


            //团队业绩
            foreach($total as $k => $v){
                $list[] = $v['user_id'];
            }
            $list[] = $user_id;
            $list_user_id = implode(',',$list);
            $list_user_id = $list_id = substr($list_user_id, 0,strlen($list_user_id));
            //dump($list_user_id);
            $recharge_money = Db::table('tea_recharge_cart')->where("pay_status = 1 and user_id in ($list_user_id)")->SUM('recharge_money');
            $again_money = Db::table('tea_recharge_cart')->where("pay_status = 1 and user_id in ($list_user_id)")->SUM('again_money');
            //$money_total = number_format($recharge_money+$again_money,2);
            $money_total = $recharge_money+$again_money;
            // dump($money_total);die;

            //dump($child_user_id);die;
            $this->assign('money_total',$money_total);
            $this->assign('child',$child_user_id);
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


    //显示我的钱包以及理茶宝明细页面
    public function inte_info(){
        $this->checkLogin();
        $user_id=session('user_id');
        //显示可用积分以及可用钱包余额
        $user_info=Db::table('tea_user')
            ->where('user_id',$user_id)
            ->field('wallet,tea_ponit_inte,tea_inte')
        ->find();
        $this->assign('user',$user_info);
//        $rechargeLog=Db::table('tea_integral_log')
//            ->where('use_type=2')
//            ->order('id desc')
//            ->paginate(6);
//        $this->assign('data',$rechargeLog);
        //显示我的钱包充值以及我的理茶宝购买记录
        $moneyData=Db::table('tea_money')
            ->where('user_ids',$user_id)
            ->select();
        $rechargeData=Db::table('tea_recharge_cart')
            ->where('user_id',$user_id)
            ->where('is_ceo',1)
            ->select();
        $rechargeLog['money']=$moneyData;
        $rechargeLog['recharge']=$rechargeData;
        $this->assign('data',$rechargeLog);
        return $this->fetch();
    }

    //代人注册
    public function otherreg(){
        $username = input('post.username');
        $password1 = input('post.password1');
        $password2 = input('post.password2');
        $name = input('post.name');
        $user_id=session('user_id');
        $user_rank=intval(input('post.user_rank'));
        if($password1 != $password2) return json(array('status'=>3));
        $tm_salt = rand(0000,9999);
        $password = md5(md5($password1).$tm_salt);   //密码
        $data = array(
            'user_name' => $username,
            'nick_name' => $name,
            'password' => $password,
            'parent_id' => $user_id,
            'tm_salt' => $tm_salt,      //盐
            'reg_time' => time(),   //注册时间
            'email' => $username.'@qq.com',   //注册时间
            'user_rank'=>$user_rank         //会员等级是理茶宝会员
        );
        //插入到数据库，并且返回他的user_id
        $res = Db::connect(config('db_config2'))->name("users")->insertGetId($data);
        $data2 = array(
            'user_id'=>$res,
            'wait'=>1,
        );
        $res2 = Db::table('tea_user')->insert($data2);
        if($res2){
            return json(array('msg'=>"注册成功",'status'=>1));
        }else{
            return json(array('msg'=>"内部维护中",'status'=>2));
        }
    }
}
