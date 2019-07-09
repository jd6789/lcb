<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/3/30
 * Time: 18:33
 */
namespace app\newapp\controller;
use think\Controller;

class Com extends Controller{
    public function __construct()
    {
        parent::__construct();
        //$this->auto_wx();
    }
        //冒泡排序算法
    public function getpao($arr)
    {
        $len=count($arr);
        //设置一个空数组 用来接收冒出来的泡
        //该层循环控制 需要冒泡的轮数
        for($i=1;$i<$len;$i++)
        {
            //该层循环用来控制每轮 冒出一个数 需要比较的次数
            for($k=0;$k<$len-$i;$k++)
            {
                if($arr[$k]>$arr[$k+1])
                {
                    $tmp=$arr[$k+1];
                    $arr[$k+1]=$arr[$k];
                    $arr[$k]=$tmp;
                }
            }
        }
        return $arr;
    }
    //判断是否为微信
    public function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    public function auto_wx()
    {
      if($this->is_weixin()){
           if(!session('lcb_user_id')){
               //自动登录
//              $wx_obj = new Wx();
//               $wx_obj = new \app\appmobile\controller\Wx();
              $this->getcode_user();
            }
        }
    }


    //判断用户是否登录
    public function checkLogin(){
        if(!session('lcb_user_id')){
            $this->error('未登录','user/login');
            return false;
        }
        return true;
    }


    public function getcode_user(){
        //$url = "http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Wx/user_wx";
        //$this->redirect("http://testshop.fgj800.cc/mobile/oauth?type=wechat&back_url=http://love1314.ink/newapp/Web/user_wx");
        //$this->redirect("http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://love1314.ink/newapp/Web/user_wx");
//        header('location: http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Wx/user_wx');
    }
}