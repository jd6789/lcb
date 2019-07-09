<?php
namespace app\newapp\controller;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }

    public function cc(){
        $xml = "<xml><return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
<appid><![CDATA[wx03092038d0c42e45]]></appid>
<mch_id><![CDATA[1502167371]]></mch_id>
<nonce_str><![CDATA[l3ogFpcRhzV2c3uh]]></nonce_str>
<sign><![CDATA[D46AD687D82DA0A7CCE58126B9D9C79E]]></sign>
<result_code><![CDATA[SUCCESS]]></result_code>
<out_trade_no><![CDATA[wdcg_1545741457337936168]]></out_trade_no>
<trade_state><![CDATA[USERPAYING]]></trade_state>
<trade_state_desc><![CDATA[需要用户输入支付密码]]></trade_state_desc>
</xml>";

        $xml2 = "<xml><return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
<appid><![CDATA[wx03092038d0c42e45]]></appid>
<mch_id><![CDATA[1502167371]]></mch_id>
<nonce_str><![CDATA[mIaerIIYuH98hpnL]]></nonce_str>
<sign><![CDATA[AA506FF7268CAEAD5EB92D1ECFE69A4E]]></sign>
<result_code><![CDATA[SUCCESS]]></result_code>
<openid><![CDATA[o9DSz1I84IYIIyjlXkWGopgT9RKA]]></openid>
<is_subscribe><![CDATA[Y]]></is_subscribe>
<trade_type><![CDATA[MICROPAY]]></trade_type>
<bank_type><![CDATA[CFT]]></bank_type>
<total_fee>16800</total_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<transaction_id><![CDATA[4200000229201812253067440523]]></transaction_id>
<out_trade_no><![CDATA[wdcg_1545741457337936168]]></out_trade_no>
<attach><![CDATA[]]></attach>
<time_end><![CDATA[20181225213839]]></time_end>
<trade_state><![CDATA[SUCCESS]]></trade_state>
<cash_fee>16800</cash_fee>
<trade_state_desc><![CDATA[支付成功]]></trade_state_desc>
</xml>";
    }
}
