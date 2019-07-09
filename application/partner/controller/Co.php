<?php
/**
 * Created by PhpStorm.
 * user: jacku-fu
 * Date: 2018/4/30
 * Time: 18:33
 */
namespace app\partner\controller;
use think\Controller;
class Co extends Controller{
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

    public function __construct()
    {
        //session('user',null);
        //session('user_id',null);
        parent::__construct();
       // $this->auto_wx();

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
            $this->error('未登录','shareholder/login');
            return false;
        }
        return true;
    }


    public function getcode_user(){
        //$url = "http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Wx/user_wx";
        //$this->redirect("http://testshop.fgj800.cc/mobile/oauth?type=wechat&back_url=http://".$_SERVER['HTTP_HOST']."/partner/Web/user_wx");
        $this->redirect("http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://".$_SERVER['HTTP_HOST']."/partner/Web/user_wx");
//        header('location: http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://lcb.guochamall.com/appmobile/Wx/user_wx');

    }
}