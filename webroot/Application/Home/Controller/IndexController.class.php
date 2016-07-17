<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        //echo "hello weixin";
        // 将timestamp nonce token 按字典排序
        // 将排序后的三个参数拼接 sha1加密
        // 将加密后的字符串跟signature进行对比判断是否来自微信

        $array['timestamp'] = $_GET['timestamp'];
        $array['nonce'] = $_GET['nonce'];
        $array['token'] = 'mytest';
        $echostr = $_GET['echostr'];
        $signature = $_GET['signature'];
        sort($array);
        $temstr = implode('', $array);
        $temstr = sha1($temstr);
        if ($temstr == $signature && $echostr) {
            echo $echostr;
            exit;
        } else {
            $this->response();
        }
    }

    //接收事件推送并回复

    public function response()
    {
        $postArr = file_get_contents('php://input');
        $postObj = simplexml_load_string($postArr);

        //关注事件触发
        if (strtolower($postObj->MsgType) == 'event') {
            if (strtolower($postObj->Event) == 'subscribe') {
                $toUser = $postObj->FromUserName;
                $formUser = $postObj->ToUserName;
                $msgType = 'text';
                $content = '欢迎关注我们！我们是神将，会飞天的！！';
                $time = time();
                $reply_template = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    </xml>";
                $info = sprintf($reply_template, $toUser, $formUser, $time, $msgType, $content);
                echo $info;
            }
        }

        //自动回复消息
        if (strtolower($postObj->MsgType) == 'text') {
            if (strtolower($postObj->Content) == '我是谁') {
                $toUser = $postObj->FromUserName;
                $formUser = $postObj->ToUserName;
                $msgType = 'text';
                $content = '你是::'.$toUser;
                $time = time();
                $reply_template = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    </xml>";
                $info = sprintf($reply_template, $toUser, $formUser, $time, $msgType, $content);
                echo $info;
            }
        }
    }
}
