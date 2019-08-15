<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
//      public function index(){
// 		 //$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C('appid').'&secret='.C('appsecret');
// 		//dump($url);die;		
// $url = "http://www.baidu.com";
// 		$json = http_curl($url);
// 		dump($json);
// 	}
	public function index(){

    		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxb2478e9e6a676496&secret=207c94be38a6c5780d8beb8b5e2fc770'; 
		//$url = "http://www.baidu.com";    
		$ch = curl_init(); 
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    		
    		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$data = curl_exec($ch); 
    	
		var_dump($data);
    		curl_close($ch);
 
}
}
