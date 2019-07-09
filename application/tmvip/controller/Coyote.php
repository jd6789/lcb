<?php
/**
 * Created by PhpStorm.
 * User: coyote
 * Date: 2018/8/19
 * Time: 下午10:36
 */

namespace app\tmvip\controller;
use app\tmvip\model\AdminUser;
use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
use WxPayDataBase;

class Coyote extends Common
{

//    public function index ()
//    {
//        return view("index");
//    }
//
//    public function act ()
//    {
//        // 获取表单数据
//        $user_id = $_POST['user_id'];
//        $tea_inte = $_POST['tea_inte'];
//
//        // 修改用户余额
//        Db::table('tea_user')->where('user_id', $user_id)->setInc('tea_inte', $tea_inte);
//
//        // 生成积分变动记录
//        $data = array();
//        $data['user_id'] = $user_id;
//        $data['tea_inte'] = $tea_inte;
//        $data['introduce'] = '财务数据核对';
//        $data['online'] = 1;
//        $data['fix'] = 2;
//        $data['addtime'] = time();
//        $data['year'] = date('Y');
//        $data['month'] = date('m');
//        $data['day'] = date('d');
//        Db::table('tea_integral_log')->insert($data);
//    }

//    public function list_user_integral ()
//    {
//        $user_id = 2192;
//
//        $integral = Db::table('tea_integral_log')
//            ->where('user_id=' . $user_id . ' and tea_inte>0')
//            ->sum('tea_inte');
//        echo '用户已得到茶券：' . $integral . '</br>';
//
//        $integral = Db::table('tea_integral_log')
//            ->where('user_id=' . $user_id . ' and tea_ponit_inte>0')
//            ->sum('tea_ponit_inte');
//        echo '用户已得到茶点：' . $integral . '</br>';
//
//        $integral = Db::table('tea_integral_log')
//            ->where('user_id=' . $user_id . ' and tea_inte<0')
//            ->sum('tea_inte');
//        echo '用户已使用茶券：' . $integral . '</br>';
//
//        $integral = Db::table('tea_integral_log')
//            ->where('user_id=' . $user_id . ' and tea_ponit_inte<0')
//            ->sum('tea_ponit_inte');
//        echo '用户已使用茶点：' . $integral . '</br>';
//
//        $integral = Db::table('tea_integral_log')
//            ->where('user_id=' . $user_id . '')
//            ->sum('tea_inte');
//        echo '用户应剩余茶券：' . $integral . '</br>';
//
//        $integral = Db::table('tea_integral_log')
//            ->where('user_id=' . $user_id)
//            ->sum('tea_ponit_inte');
//        echo '用户应剩余茶点：' . $integral . '</br>';
//
//        $integral = Db::table('tea_user')
//            ->where('user_id=' . $user_id)
//            ->find();
//        echo '用户账户实际茶券：' . $integral['tea_inte'] . '  实际茶点：' . $integral['tea_ponit_inte'];
//    }

//public function user_inte ($u='')
//{
//    $uid = Db::connect(config('db_config2'))->name("users")->where('user_name',$u)->field('user_id')->find();
//    $uid = $uid['user_id'];
//
//    $inte = 0;
//    if ( $uid > 0 ) {
//        $inte = Db::table('tea_integral_log')
//            ->where('user_id='. $uid . ' and tea_inte=0 and tea_ponit_inte>0')
//            ->order('id desc')
//            ->find();
//
//        $ponit = $inte['tea_ponit_inte'];
//
//        if (intval($ponit) > 0) {
//            $inte['tea_inte'] = '+' . intval($ponit) * 0.6;
//            $inte['tea_ponit_inte'] = '+' . intval($ponit) * 0.4;
//
//
//            $user = Db::table('tea_user')
//                ->where('user_id='. $uid)
//                ->order('id desc')
//                ->find();
//
//            $u_ponit = $user['tea_ponit_inte'];
//            $user['tea_inte'] = $user['tea_inte'] + $inte['tea_inte'];
//            $user['tea_ponit_inte'] = $u_ponit - $inte['tea_inte'];
//
//            Db::table('tea_integral_log')->update($inte);
//            Db::table('tea_user')->update($user);
//        }
//
//    }
//    var_dump($user);
//}

    function wx_pay_bank ()
    {
        $url = 'https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank';
        $data['mch_id'] = '1459448102';
        $data['partner_trade_no'] = '1212121221227';
        $data['nonce_str'] = 'langxingguocha1212';
        $data['enc_bank_no'] = '6222623460000107026';
        $data['enc_true_name'] = '郎星';
        $data['bank_code'] = '交通银行';
        $data['amount'] = 1000;
        $data['desc'] = '企业付款测试';
        $data['sign'] = $this->sign($data);
        $data=$this->chqngeXml($data);
        $data=$this->acurl($data,$url);
        var_dump($data);exit;
    }
    // 数组转换成XML数据格式
    public function chqngeXml($data){
        $xml='';$xml.="<xml>";
        foreach ($data as $key => $value) {
            $xml .= "<{$key}>{$value}</{$key}>";
        }
        $xml.= "</xml>";
        return $xml;
    }
    // 通过curl调取微信支付的支付方式
    public function acurl($data,$url){
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 运行cURL，请求网页
        $data = curl_exec($ch);
        dump($data);die;
        if($data){
            // 关闭URL请求
            curl_close($ch);
            return $data;
        }else{
            return curl_error($ch);
        }

    }
    // 签名加密的方法
    public function sign($data){
        $str='';
        ksort($data);
        foreach ($data as $key => $value) {
            $str .= "{$key}={$value}"."&";
        }
        $str .= "key=iTeas641guocha77777shanghai021Ac";
        $str=md5($str);
        $str=strtoupper($str);
        return $str;
    }

}