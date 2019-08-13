<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 
 */
class MessageController extends Controller
{
	public function valid()
	{
        $echoStr = isset($_GET["echostr"])?$_GET["echostr"]:'';
        if (!$echoStr) {
        	$this -> responseMsg();
        	exit;
        }
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
	}

    public function responseMsg()
    {
    	//php7版本废弃
		// $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = file_get_contents('php://input');
		if (!empty($postStr)){
			echo '';
			exit;
        }
        libxml_disable_entity_loader(true);
      	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";             
		if(!empty( $keyword ))
        {
      		$msgType = "text";
        	$contentStr = "Welcome to wechat world!";
        	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        	echo $resultStr;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        //设置token为固定值
		$token = 'weixin';
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
}