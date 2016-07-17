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

        //纯文本自动回复消息
        if (strtolower($postObj->MsgType) == 'text') {
                $toUser = $postObj->FromUserName;
                $formUser = $postObj->ToUserName;
                $msgType = 'text';
                $time = time();
                switch(trim($postObj->Content)){
                    case '我是谁':
                        $content = '你是::'.$toUser;
                        break;
                    case '哪里学编程':
                        $content = '<a href="http://www.imooc.com">慕课网</a>';
                        break;
                    case '福利':
                        $itemarr = array(
                            array(
                                'title'=>'福利放送',
                                'description'=>'每周一福利',
                                'picurl'=>'http://img1.mm131.com/pic/2557/0.jpg',
                                'url'=>'http://www.mm131.com/xinggan/2557.html'
                            ),
                            array(
                                'title'=>'第二波福利',
                                'description'=>'每周第二福利福利',
                                'picurl'=>'http://s1.dwstatic.com/group1/M00/2F/63/2f63078b323b9129845f06d88b3c55932195.png',
                                'url'=>'http://tu.duowan.com/gallery/126222.html#p1'
                            ),
                            array(
                                'title'=>'第三波福利特别献给神将',
                                'description'=>'每周第三福利福利',
                                'picurl'=>'http://s1.dwstatic.com/group1/M00/F3/84/58ee3e72c0b862bb4d270a0a3bfffb5e.jpg',
                                'url'=>'http://tu.duowan.com/gallery/124997.html#p1'
                            ),
                        );
                        $reply_template = "<xml>
                            <ToUserName><![CDATA[".$toUser."]]></ToUserName>
                            <FromUserName><![CDATA[".$formUser."]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[news]]></MsgType>
                            <ArticleCount>".count($itemarr)."</ArticleCount>
                            <Articles>";
                        foreach($itemarr as $k=>$v) {
                            $reply_template .= "<item>
                            <Title><![CDATA[".$v['title']."]]></Title>
                            <Description><![CDATA[".$v['description']."]]></Description>
                            <PicUrl><![CDATA[".$v['picurl']."]]></PicUrl>
                            <Url><![CDATA[".$v['url']."]]></Url>
                            </item>";
                        }
                        $reply_template .="
                            </Articles>
                            </xml>";
                        echo $reply_template;
                        exit;
                    default:
                        $content = '谢谢光临代码民工小站！';
                }
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
