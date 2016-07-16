<?php
// echo "hello weixin";
// 将timestamp nonce token 按字典排序
// 将排序后的三个参数拼接 sha1加密
// 将加密后的字符串跟signature进行对比判断是否来自微信

$array['timestamp'] = $_GET['timestamp'];
$array['nonce'] = $_GET['nonce'];
$array['token'] = 'mytest';
$signature = $_GET['signature'];
sort($array);
$temstr = implode('', $array);
$temstr = sha1($temstr);
if ($temstr == $signature) {
	echo $_GET['echostr'];
	exit;
}
