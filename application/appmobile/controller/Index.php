<?php
namespace app\appmobile\controller;
use think\Controller;
use app\api\controller\Alpay;
use app\appmobile\model\RechargeCart;
class Index extends Controller
{
 protected $appid = "wx97c3159a64046d86";
    protected $secret ="05bb81ed77cf0dc8fbd2a56e7f3f8aec";
    public function index1()
    {
        $out_trade_no='1161523266547534168';
        $out_trade_no='1161523266547534168111';
        $total_amount='1';
        $subject='我的茶馆';
        $body='我的茶馆';
        $setReturnUrl='http://love1314.ink/api/alpay/buyReturnUrl';
        $notify_url='http://love1314.ink/api/alpay/buyNotiflUrl';
       $model=new Alpay();
       $model->testAlpay($out_trade_no,$total_amount,$subject,$body,$setReturnUrl,$notify_url);
    }
    public function test(){
        $model=new RechargeCart();
        $res=$model->buyUpdate(301,'6821523266650422168','2018041121001004350200326897');
        dump($res);
    }

     public function index()
{

//        $urls = "http://open.weixin.qq.com/connect/oauth2/authorize?appId=".$this->appid."&redirect_uri=http%3a%2f%2flove1314.ink&response_type=code&scope=snsapi_userinfo#wechat_redirect";
//        $data1= $this->getHttp($urls);
//        dump($data1);die;
         echo 1;die;
         $code="";
         $code = input("get.code");
         if($code){
             $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx97c3159a64046d86&secret=05bb81ed77cf0dc8fbd2a56e7f3f8aec&code=" . $code . "&grant_type=authorization_code";
             $data = $this->getHttp($url);
             $url_user = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
             $info = $this->getHttp($url_user);
             $user = array('openid' => $info['openid'], 'alias' => $info['nickname'], 'last_login_ip' => $_SERVER['REMOTE_ADDR'], 'create_at' => time());
             $openid = $data['openid'];
             dump($openid);
         }else{
             $urls = "http://open.weixin.qq.com/connect/oauth2/authorize?appId=".$this->appid."&redirect_uri=http%3a%2f%2flove1314  .ink/appmobile/Index/index&response_type=code&scope=snsapi_userinfo#wechat_redirect";
             redirect($urls);
         }

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
}
