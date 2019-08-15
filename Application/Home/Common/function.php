<?php
	if(!function_exists('http_curl')){
		function http_curl($url,$method='get',$postdata=array()){
			// 1、打开会话
			$ch = curl_init();
			// 2、设置请求相关信息
			if($method == 'post'){
				// 设置请求的方式
				curl_setopt($ch, CURLOPT_POST, TRUE);
				// 设置请求的参数
				curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
			}
			// 设置请求地址
			curl_setopt($ch, CURLOPT_URL, $url);
			// 设置返回结果不直接输出
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
			//禁用 https 证书检查
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
			// 3、执行请求
			$json = curl_exec($ch);
			// 4、关闭会话
			curl_close($ch);
			return $json;
	}
}
?>
