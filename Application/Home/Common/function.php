<?php
	if (!function_exists('http_curl')) {
		function http_curl($url, $method = 'get' , $postdata = array()){
			// 1.初始化
			$ch = curl_init();
			// 2.设置参数
			if ($method == 'post'){
				//post方式
				curl_setopt($ch, CURLOPT_POST, TRUE);
				//参数
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			}
			//get方式
			curl_setopt($ch, CURLOPT_URL, $url);
			//结果返回 不直接输出
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//禁用 https 证书检查
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			// 3.发送请求
			$json = curl_exec($ch);
			// 4.关闭
			curl_close($ch);
			return $json;
		}
	}
?>
