<?php
/**
  * wechat php test
  */
header("content-type:text/html;charset=utf-8");
require 'common.php';
require "Wechat.class.php";
use Home\Controller\IndexController;
ini_set("error_log",__DIR__."/error.log");
define("TOKEN", "weixin");
class wechatCallbackapiTest extends Wechat
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
      	
		if (!empty($postStr)){
               
                libxml_disable_entity_loader(true);
               $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                global $tmp_arr;             
              switch ($postObj->MsgType) {
                        case 'text':
                                //2、判断$keyword关键词是否为‘天气’
                                if($keyword == '天气') {
                                    //提示用户输出地区信息
                                    $contentStr = '请输入地区信息，如北京进行天气查询';
                                    $data = file_get_contents('php://input');
                                    file_put_contents("../a.txt",$data);
                                    //3、对数据进行缓存操作，缓存时间60s
                                    //10、使用文本格式进行数据返回
                                    $msgType = 'text';
                                    $resultStr = sprintf($tmp_arr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;
                                }
                            break;
                        case "event":
                        if($postObj->Event=='subscribe'){
                            $msgType = "text";
                            $contentStr = "感谢您关注！";
                             
                            $data = file_get_contents('php://input');
		 //file_put_contents("../a.txt",$data);
		//$data = file_get_contents('../a.txt');
                            //$this->wx_tuijian()
                            file_put_contents("../a.txt",$data);
                            //$resultStr = sprintf($tmp_arr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //echo $resultStr;
                             }
                            break;          
                    }
        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

  

      public function wx_tuijian()
    {

       $data = file_get_contents('../a.txt');
        $pa = '%<EventKey><!\[CDATA\[(.*)\]\]></EventKey>%';//正则表达式
        preg_match_all($pa,$data,$matches1);
        $pas = '%<FromUserName><!\[CDATA\[(.*)\]\]></FromUserName>%';//正则表达式
        preg_match_all($pas,$data,$matches2);
        $list['EventKey'] = $matches1[1][0];
        //字符串分割
        $EventKey = explode('_',$list['EventKey'])[1];
        if( $EventKey!=""){
            $FromUserName = $matches2[1][0];
        $conn=mysql_connect('121.43.160.236','guochamall','bEntwWKnhGbEntwWKnhGlx') or die("error connecting") ; //连接数据库

        mysql_query("set names 'utf8'"); //数据库输出编码 应该与你的数据库编码保持一致.南昌网站建设公司百恒网络PHP工程师建议用UTF-8 国际标准编码.

        mysql_select_db('guochamall'); //打开数据库
        $sql = "select * from jieyu_wx where openid = '$FromUserName '";
       $result = mysql_query($sql);
       if(!mysql_fetch_array($result)){
            $time = time();
            $sql ="insert into jieyu_wx (other_id,openid,addtime) values ('$EventKey','$FromUserName','$time')"; //SQL语句
        mysql_query($sql);
       //删除文件
       //unlink ( "../a.txt" );
       }
       
      mysql_close(); //关闭MySQL连接
       }
        


    }
}

$wechatObj = new wechatCallbackapiTest ();
//$wechatObj->valid();
$wechatObj->responseMsg();
$wechatObj->wx_tuijian();
?>