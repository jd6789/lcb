<!-- <html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付样例-订单查询</title>
</head> -->
<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../wxpay/lib/WxPay.Api.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

function printf_info($data)
{
    // foreach($data as $key=>$value){
    //     echo "<font color='#f00;'>$key</font> : $value <br/>";
    // }
    // 判断支付状态。如果支付了，则将微信订单号入库
    if ($data['trade_state']==="SUCCESS") {
//			$attach = $data['attach'];
//			$res = M('Order')->where("order_num = '$attach'")->save(array('trade_no'=>$data['out_trade_no']));
//			//$res = M('Order')->where("order_num = '$attach'")->save(array('trade_no'=>$data['out_trade_no']));
		$key =  substr($data['out_trade_no'],0,2);
		$attach =  substr($data['out_trade_no'],2);
		if($key == "go"){
			//执行购买的数据库记录
			M('RechargeCart')->where("recharge_num = '$attach'")->save(array('trade_no'=>$data['out_trade_no']));
		}elseif($key == "lc"){
			//执行理茶宝的数据库记录
			 M('Order')->where("order_num = '$attach'")->save(array('trade_no'=>$data['out_trade_no']));
//			$res = M('Order')->where("order_num = '$attach'")->save(array('trade_no'=>$data['out_trade_no']));
		}
    }else{
        echo 0;
    }
}
if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
	$transaction_id = $_REQUEST["transaction_id"];
	$input = new WxPayOrderQuery();
	$input->SetTransaction_id($transaction_id);
	printf_info(WxPayApi::orderQuery($input));
	exit();
}
$_REQUEST["out_trade_no"]=$out_trade_no;
if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
	$out_trade_no = $_REQUEST["out_trade_no"];
	$input = new WxPayOrderQuery();
	$input->SetOut_trade_no($out_trade_no);
	printf_info(WxPayApi::orderQuery($input));
	exit();
}
?>
<!-- <body>  
	<form action="" method="post" id="callpay">
        <div style="margin-left:2%;color:#f00">微信订单号和商户订单号选少填一个，微信订单号优先：</div><br/>
        <div style="margin-left:2%;">微信订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="transaction_id" /><br /><br />
        <div style="margin-left:2%;">商户订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="out_trade_no" /><br /><br />
		<div align="center">
		<input type="hidden" name="out_trade_no" value="" />
			<input type="submit" value="查询" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;"	 id="btn" />
		</div>
	</form>
</body>
<script>
// window.onload = function(){ 
//  document.getElementById("btn").click();
// }; 
</script>
</html> -->