<?php 
header("content-type:text/html;charset=utf-8");
	class Wechat{
		// 定义一个access_token方法
		protected function get_token(){
			$appid = "wx7797056e9ca6c4f7";
			$secret ="413cf776bd09963ff0ef89fc9f301b1c";
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$str=$this->http_request($url);
			$json = json_decode($str);
			$access_token=$json->access_token;
			return $access_token;
		}
		// 封装curl库，用于发送HTTP请求
		protected function http_request($url,$data=null){
					// 创建curl
			$ch = curl_init();
			// 设置
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//禁止服务器端效验ssl证书
			if(!empty($data)){
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //以文档流形式返回数据
			//执行
			$output = curl_exec($ch);
			curl_close($ch);
			return $output;
		}
			//图灵调用的curl请求
		protected function request($url,$data=null){
		// 创建curl
		$ch = curl_init();
		// 设置
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//禁止服务器端效验ssl证书
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //以文档流形式返回数据

		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POST, 1);
			$data = json_encode($data);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		}
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type:application/json',
	    'Content-Length:'.strlen($data))
		);

		//执行
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}



?>