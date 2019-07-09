<?php
/**
 * Created by PhpStorm.
 * User: jieyu
 * Date: 2018/4/26
 * Time: 13:48
 */

namespace app\newapp\controller;

//use think\Controller;
use think\Cookie;
use think\Session;
use think\Db;
//use think\Request;
class Wx extends Com{
//wx97c3159a64046d86
////05bb81ed77cf0dc8fbd2a56e7f3f8aec
//    protected $appid = "wx7797056e9ca6c4f7";
//    protected $secret ="413cf776bd09963ff0ef89fc9f301b1c";
//    protected $open_id = "";

    // 定义一个access_token方法
    public function get_token(){
        //$appid = "wx7797056e9ca6c4f7";
        //$secret ="413cf776bd09963ff0ef89fc9f301b1c";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->secret;
        $str=$this->http_request($url);
        $json = json_decode($str);
        $access_token=$json->access_token;
        return $access_token;
    }




    //获取openid
    public function get_openid(){
        //$code = input('get.code');
        //$file = file_get_contents('php://input');
        //f//ile_put_contents('./wx.txt',$file);
        dump(input('get.'));
        die;
        //$method =  input("get.method");
        $method = Request::instance()->request('method');
        //dump($method);die;
        if($code){
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->secret."&code=" . $code . "&grant_type=authorization_code";
            $data = $this->getHttp($url);

            $url_user = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
            $info = $this->getHttp($url_user);
            return $info;

        }
    }


    public function actionIndex()
    {
        $type = I('get.type');
        $back_url = I('get.back_url', '', 'urldecode');
        $user_id = input('get.user_id', 0, 'intval');
        $file = ADDONS_PATH . 'connect/' . $type . '.php';

        if (file_exists($file)) {
            include_once $file;
        }
        else {
            show_message(L('msg_plug_notapply'), L('msg_go_back'), url('user/login/index'));
        }

        $url = url('/', array(), false, true);

        if (0 < $user_id) {
            $param = array('m' => 'oauth', 'type' => $type, 'user_id' => $user_id, 'back_url' => empty($back_url) ? url('user/index/index') : $back_url);
        }
        else {
            $param = array('m' => 'oauth', 'type' => $type, 'back_url' => empty($back_url) ? url('user/index/index') : $back_url);
        }

        $url .= '?' . http_build_query($param, '', '&');
        $config = $this->getOauthConfig($type);

        if (!$config) {
            show_message(L('msg_plug_notapply'), L('msg_go_back'), url('user/login/index'));
        }

        $obj = new $type($config);
        if (isset($_GET['code']) && ($_GET['code'] != '')) {
            if ($res = $obj->callback($url, $_GET['code'])) {
                $param = get_url_query($back_url);
                $is_drp = false;

                if (isset($param['u'])) {
                    $up_uid = get_affiliate();
                    $res['parent_id'] = !empty($param['u']) && ($param['u'] == $up_uid) ? intval($param['u']) : 0;
                }

                if (isset($param['d'])) {
                    $up_drpid = get_drp_affiliate();
                    $res['drp_parent_id'] = !empty($param['d']) && ($param['d'] == $up_drpid) ? intval($param['d']) : 0;
                    $is_drp = true;
                }

                $res['openid'] = $res['openid'];
                $res['unionid'] = $res['unionid'];
                session('unionid', $res['unionid']);
                session('parent_id', $res['parent_id']);
                session('drp_parent_id', $res['drp_parent_id']);
                if (isset($_SESSION['user_id']) && (0 < $user_id) && ($_SESSION['user_id'] == $user_id) && !empty($res['unionid'])) {
                    $this->UserBind($res, $user_id, $type);
                }
                else {
                    if ($this->oauthLogin($res, $type) === true) {
                        redirect($back_url);
                    }

                    if ((!empty($_SESSION['unionid']) && isset($_SESSION['unionid'])) || $res['unionid']) {
                        $res['unionid'] = !empty($_SESSION['unionid']) ? $_SESSION['unionid'] : $res['unionid'];
                        $res['parent_id'] = !empty($_SESSION['parent_id']) ? $_SESSION['parent_id'] : $res['parent_id'];
                        $res['drp_parent_id'] = !empty($_SESSION['drp_parent_id']) ? $_SESSION['drp_parent_id'] : $res['drp_parent_id'];
                        $res['nickname'] = session('nickname');
                        $res['headimgurl'] = session('headimgurl');
                        $this->doRegister($res, $type, $back_url, $is_drp);
                    }
                    else {
                        show_message(L('msg_author_register_error'), L('msg_go_back'), url('user/login/index'), 'error');
                    }
                }

                return NULL;
            }
            else {
                show_message(L('msg_authoriza_error'), L('msg_go_back'), url('user/login/index'), 'error');
            }

            return NULL;
        }

        $url = $obj->redirect($url);
        redirect($url);
    }

    //get请求
    public function getHttp($url)
    {
        $ch = curl_init();
        //设置传输地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置以文件流形式输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //接收返回数据
        $data = curl_exec($ch);
        curl_close($ch);
        $jsonInfo = json_decode($data, true);
        return $jsonInfo;
    }


    //是否需要关联
    public function account_link(){
        $unionid = session('unionid');
        if(empty($unionid)){
            return json_encode(array('status' =>1));
        }
        $type = session('type');
//       if(intval($type)==1){
//           return json_encode(array('status' =>0));
//       }

        $wx_data = Db::connect(config('db_config2'))
            ->name('connect_user')
            ->where(array('open_id' => $unionid, 'connect_code' => 'sns_wechat'))->find();

        $wechat_id = Db::connect(config('db_config2'))
            ->name('wechat')
            ->where(array('default_wx' => 1, 'status' => 1))->find();

//
        $wx_datas = Db::connect(config('db_config2'))
            ->name('wechat_user')
            ->where(array('unionid' => $unionid, 'wechat_id' => $wechat_id['id']))
            ->find();
        if($wx_data['user_id'] == $wx_datas['ect_uid']){
            return json_encode(array('status' =>0));
        }else{
            //已关联
            return json_encode(array('status' =>1));
        }
    }


    //用户是否绑定过微信
    public function user_wx(){
        dump(@$_SESSION);
        echo 111;
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

    public function is_weixin(){

        if ( strpos($_SERVER['HTTP_USER_AGENT'],

                'MicroMessenger') !== false ) {

            return true;

        }

        return false;

    }

    public function cc(){
        if($this->is_weixin()){
            if(!session('user_id')){
                //自动登录
                //$wx_obj = new Wx();
                $this->getcode_user();
            }

        }else{

            //echo "这是微信外部浏览器";

        }
    }
}