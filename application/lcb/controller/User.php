<?php
/**
 * Created by PhpStorm.
 * User: jieyu
 * Date: 2018/4/8
 * Time: 11:43
 */
namespace app\lcb\controller;
use think\Controller;
use think\Cookie;
use think\Request;
use app\lcb\model\Huiyuan;
use think\Session;
use think\Db;

class User extends Controller
{
    //会员登录
    public function login()
    {
        if(request()->isAjax()){
            //获取用户输入的用户名 密码
            $username = trim(input('post.username'));
            $password = trim(input('post.password'));

            //判断用户名是否存在
            $userobj = new Huiyuan();
            //print_r($userobj);
            $user = Db::table('tea_user')->where('username',$username)->find();
            if(!$user){
                return json_encode(array("data"=>"用户不存在",'status'=>2));
            }else{
                //用户名存在  判断密码是否正确
                $password = md5(md5($password).$user['salt']);
                $res = $userobj->pwd_exit($username,$password);
                if($res){
                    return json_encode(array("data"=>"登陆成功",'status'=>1));
                }else{
                    return json_encode(array("data"=>"密码错误",'status'=>0));
                }
            }
        }else{
            return $this->fetch();
        }
    }

    //快捷登录
    public function login_iphone()
    {
        $tel = input('post.tel');
        $mobileCode = input('post.mobileCode');
        $code = session('code');
        if($mobileCode != $code){
            return json_encode(array('msg'=>"验证码错误",'status'=>0));
        }
        session::set('msg',null);
        //验证用户是否正确
        $info = db('user')->checkTel($tel);
        if(!$info){
            return json_encode(array('msg'=>"登陆失败",'status'=>0));
        }else{
            return json_encode(array('msg'=>"登录成功",'status'=>1));
        }
    }

    //找回密码
    public function reppwd()
    {
        if(request()->isAjax()){
//            $username = input('post.username');
//            $userinfo = db('user')->where("username",$username)->find();
//            if(!$userinfo){
//                return json_encode(array("data"=>"用户名不存在",'status'=>0));
//            }
            //判断验证码是否正确
            $code = session('code');
            $user_code = input('post.code');
            if($user_code != $code){
                return json_encode(array("data"=>"验证码错误",'status'=>0));
            }else{
                return (1);
            }

            //重置密码
//            $password1 = input('post.password1');
//            $password2 = input('post.password2');
//            //判断密码是否一致
//            if($password1 != $password2){
//                return json_encode(array('data'=>"亲,两次密码必须一致哦!!!",'status'=>0));
//            }
//            $salt = $userinfo['salt'];
//            $password = md5(md5($password1).$salt);
//
//            //判断密码修改是否成功
//            $res = db('user')->where("username",$username)->setField('password',$password);
//            if($res){
//                //将用户同步到官网
////                $username = $userinfo['username'];
////                $userinfo['password'] = $password;
////                $gc_user = Db::table('tea_user')->db(1,'DBConf1')
////                    ->table('guocha_user')->where("username',$username'")
////                    ->setField('password',$userinfo['password']);
//
//                return json_encode(array('data'=>"密码修改成功",'status'=>1));
//            }else{
//                return json_encode(array('data'=>"密码修改失败",'status'=>0));
//            }
        }else{
            return $this->fetch();
        }

//        if(request()->isGet()){
//            return $this->fetch();
//        }
    }

    public function reppwd2()
    {
        if(request()->isAjax()){
            $username = input('post.username');
            $userinfo = db('user')->where("username",$username)->find();
//            if(!$userinfo){
//                return json_encode(array("data"=>"用户名不存在",'status'=>0));
//            }
            //判断验证码是否正确
//            $code = session('code');
//            $user_code = input('post.code');
//            if($user_code != $code){
//                return json_encode(array("data"=>"验证码错误",'status'=>0));
//            }else{
//                return (1);
//            }

            //重置密码
            $password1 = input('post.password1');
            $password2 = input('post.password2');
            //判断密码是否一致
            if($password1 != $password2){
                return json_encode(array('data'=>"亲,两次密码必须一致哦!!!",'status'=>0));
            }
            $salt = $userinfo['salt'];
            $password = md5(md5($password1).$salt);

            //判断密码修改是否成功
            $res = db('user')->where("username",$username)->setField('password',$password);
            if($res){
                //将用户同步到官网
//                $username = $userinfo['username'];
//                $userinfo['password'] = $password;
//                $gc_user = Db::table('tea_user')->db(1,'DBConf1')
//                    ->table('guocha_user')->where("username',$username'")
//                    ->setField('password',$userinfo['password']);

                return json_encode(array('data'=>"密码修改成功",'status'=>1));
            }else{
                return json_encode(array('data'=>"密码修改失败",'status'=>0));
            }
        }else{
            return $this->fetch();
        }

//        if(request()->isGet()){
//            return $this->fetch();
//        }
    }

    //图片上传
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

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

    //退出登录
    public function loginout(){
        cookie::set('admin',null);
        //$this->success("您已退出登录");
        session::set('user', null);
        session::set('user_id', null);
        session::set('phone',null);
        $this->redirect('/index');
    }

    //短信验证码 OK
    public function test()
    {
        $code = rand(100000,999999);
        //session('code',$code);
        session::set('code',$code);
        //获取传输过来的手机号码
        //$tel = $_POST['tel'];
        $tel = input('post.tel');
        //dump($tel);die;
        session::set('boolen',$tel);
        //$tel=13886643564;
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
        //dump($resp);die;
        if($resp->result->msg){
            //$this->ajaxReturn(array('s'=>1,'msg'=>'短信发送成功'));
            return json_encode(array('s'=>1,'msg'=>'短信发送成功'));
            //$this->success('短信发送成功');
        }else{
            //$this->ajaxReturn(array('s'=>0,'msg'=>'短信发送失败'));
            return json_encode(array('s'=>0,'msg'=>'短信发送失败'));
            //$this->success('短信发送失败');
        }
    }

    //判断用户名是否存在
    public function checkUser()
    {
        $username = input('post.username');
        $user_info = db('user')->where("username",$username)->find();
        if(!empty($user_info)){
            return json_encode(array('data'=>"该用户已存在",'status'=>0));
        }
    }

    public function checkUser2()
    {
        $username = input('post.username');
        $user_info = db('user')->where("username",$username)->find();
        if(empty($user_info)){
            return json_encode(array('data'=>"用户名不存在",'status'=>0));
        }
    }

    //判断手机号码是否存在
    public function checkTel()
    {
        $tel = input('post.tel');
        $res = db('user')->where("tel",$tel)->find();
        if(empty($res)){
            return json_encode(array('data'=>"手机号不存在",'status'=>0));
        }
    }

    //会员注册 OK
    public function register()
    {
        if(request()->isAjax()){
            $username = input('post.username');
            $password1 = input('post.password1');
            $password2 = input('post.password2');
            //$pay_pwd1 = input('post.pay_pwd1');
            //$pay_pwd2 = input('post.pay_pwd2');
            $tel = input('post.tel');
            $re_username = input('post.re_username');
            $code_user = input('post.code');
            $rank = db('rank')->where("first=1")->find();
            $rank_id = $rank['rank_id'];
            $code = session('code');
            //判断短信验证码是否正确
            if($code_user != $code){
                return json_encode(array('msg'=>"验证码错误",'status'=>0));
            }

            //如果有推荐人
            if($re_username){
                //根据推荐人用户名查询其信息
                $re_info = db('user')->where("username",$re_username)->find();

                if(!$re_info){
                    return json_encode(array('msg'=>"推荐人不存在",'status'=>0));   //推荐人不存在
                }

                if($re_info['wait'] == 0){
                    return json_encode(array('data'=>"该推荐人账户已被冻结",'status'=>0));
                }
                $parent_id = $re_info['id'];
            }else{
                //没有推荐人
                $parent_id = 0;
            }

            //判断该用户名是否已存在
            $user_info = db('user')->where("username",$username)->find();
            if(!empty($user_info)){
                return json_encode(array('data'=>"该用户已存在",'status'=>0));
            }
            $salt = rand(000000,999999);
            if($password1 !== $password2){
                return json_encode(array('data'=>'两次密码必须一致','status'=>0));
            }
//            if($pay_pwd1 !== $pay_pwd2){
//                return json_encode(array('data'=>'支付密码必须一致','status'=>0));
//            }
            $password = md5(md5($password1).$salt);   //密码
            //$pay_pwd = md5(md5($pay_pwd1).$salt);     //支付密码

            $data = array(
                'username' => $username,
                'password' => $password,
                'tel' => $tel,
                'parent_id' => $parent_id,
                'salt' => $salt,
                //'pay_pwd'=>$pay_pwd,
                'first_time' => time(),
                'rank_id'=>$rank_id,
            );
            //dump($data);die;
            $res = Db::table('tea_user')->insert($data);
            //dump($res);die;
            if(!$res){
                return json_encode(array('msg'=>'内部维护中','status'=>0));
            }else{
                //同步官网
                //$time = time();
                //将用户同步到官网
//                $gc_user = Db::table('user')->db(1,'DBConf1')->table('guocha_user')->where("username',$username'")->find();
//                if (!$gc_user) {
//                    db('user')->db(1,'DBConf1')->table('guocha_user')->add(array(
//                        'tel'       =>  $tel,
//                        'username'  =>  $username,
//                        'parent_id' =>  $parent_id,
//                        'password'  =>  $password,
//                        'salt'      =>  $salt,
//                        'first_time'    =>  $time,
//                    ));
//                }

                return json_encode(array('msg'=>"注册成功",'status'=>1));
            }
        }else{
            return $this->fetch();
        }
    }

}