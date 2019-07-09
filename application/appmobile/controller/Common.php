<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/3/30
 * Time: 18:33
 */
namespace app\appmobile\controller;
use think\Controller;

class Common extends Controller{
    public function __construct()
    {
        parent::__construct();
        $this->auto_wx();

    }

    public function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'],
                'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    public function auto_wx()
    {

      if($this->is_weixin()){
           if(!session('user_id')){
               //自动登录
//              $wx_obj = new Wx();
//               $wx_obj = new \app\appmobile\controller\Wx();
              $this->getcode_user();
            }
        }
    }


    //判断用户是否登录
    public function checkLogin(){
        if(!session('user_id')){
            //$this->redirt(U('index'));
            $this->error('未登录','recharge/index');
            return false;
        }
        return true;
    }


    public function getcode_user(){
        $url = "http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Wx/user_wx";
        //$this->redirect("http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Web/user_wx");
        $this->redirect("http://testshop.fgj800.cc/mobile/oauth?type=wechat&back_url=http://love1314.ink/appmobile/Web/user_wx");
//        header('location: http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Wx/user_wx');
        dump(11);exit;
    }
}