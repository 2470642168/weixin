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
        //文本消息
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>"; 
        $msgType = $postObj->MsgType;

        //如果为文本消息
        if ($msgType == 'text') {
            $keyword = $postObj->Content;
            if($keyword == '图片'){
                // 回复图片消息
                $picTpl = '<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[image]]></MsgType>
                  <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                  </Image>
                </xml>';
                // 从用户发送的图片消息中获取
                $MediaId = 'nnRxnP7eMr3ia9TAkMRbSEbE4ymsN2yTb9zqPLMj5a6nKgrLDpTBaGW7FGTFVJJP';
                $resultStr = sprintf($picTpl, $fromUsername, $toUsername, $time, $MediaId);
            }elseif ($keyword == '图文') {
                $newTpl = '<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[news]]></MsgType>
                  <ArticleCount>1</ArticleCount>
                  <Articles>%s</Articles>
                </xml>';
                $Articles = '<item>
                  <Title><![CDATA[Redis连表数据结构]]></Title>
                  <Description><![CDATA[Redis连表数据结构具备原子性操作]]></Description>
                  <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/ZqbO1icS8X6QWiahFvPZ7MpZaLNwWO9Wdumq8xRR6YLCFZrFw0dA4qgkTkzX8rsibLMQjptibKxhLJjRU9XhxQtosg/0]]></PicUrl>
                  <Url><![CDATA[http://www.baidu.com]]></Url>
                </item>';
                $resultStr = sprintf($newTpl, $fromUsername, $toUsername, $time, $Articles);
            }else{

                // 文本消息
                // 对$textTpl变量中字符串进行格式化
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time,'text', $keyword);
                file_put_contents('1.txt', $resultStr);
            }
        	echo $resultStr;
            
           //如果为图片消息 
        }elseif ($msgType == 'image') {
            //按照用户输入的内容回复
            $content = "图片链接为:".$postObj->PicUrl."图片的mediaId:".$postObj->MediaId;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $content);
            echo $resultStr;
            //如果为语音消息
        }elseif($msgType == 'voice'){
            //按照用户输入的内容回复
             //$content = "mediaId:".$postObj->MediaId;
             //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $content);
            // echo $resultStr;
            // 语音识别
            $content = "语音的内容为:".$postObj->Recognition;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $content);
            echo $resultStr;
            
            //如果为地理位置
        }elseif($msgType == 'location'){
            $content = "地理位置维度为:" . $postObj->Location_X . '经度为:' . $postObj->Location_Y;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $content);
            echo $resultStr;

            //如果为链接消息
        }elseif($msgType == 'link'){
            $content = "链接的标题为:" . $postObj->Title;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $content);
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
