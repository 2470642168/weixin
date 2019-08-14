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

    /**
     * 实现消息回复
     */
    public function responseMsg()
    {
    	//获取请求数据,在php高版本已经弃用此方法
		// $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    
        // php高版本使用
		$postStr = file_get_contents('php://input');
        //判断请求是否携带数据
		if (empty($postStr)){
			echo '';
			exit;
        }
        //xml安全处理
        libxml_disable_entity_loader(true);
        //处理xml数据 , 转换为对象,方便调用
      	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        //发生者的openid
        $fromUsername = $postObj->FromUserName;
        //开发者身份标识
        $toUsername = $postObj->ToUserName;
        //获取发送的内容
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
            //如果为文本消息
            $msgType = $postObj->MsgType;
            if ($msgType == 'text') {
                //按照用户输入的内容回复
            	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $postObj->Content);
            	echo $resultStr;
        	   // $contentStr = "你好啊";
            }

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