<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
     public function index(){
         $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . C('appid') . "&secret=" . C('appsecret');
echo 123;
         $json = http_curl($url);
         dump($url);die;
     }
}
