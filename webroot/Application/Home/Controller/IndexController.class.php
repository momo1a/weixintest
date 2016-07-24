<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    /**
     * APPID
     * @var string
     */
    var $_appid = 'wxf1f337c34f6713ea';

    /**
     * APPSECRET
     * @var string
     */
    var $_secret = 'a2a9286da291d95bf87fb01ae4323b05';


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


    /**
     * @param $url  Request Target URL
     */

    public function httpRequest($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_TIMEOUT,5);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 获取微信AccessToken
     */
    public function getAccessToken(){
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_secret;
        $access_token = $this->httpRequest($requestUrl);
        return $access_token;
    }


    /**
     * 获取微信服务器ip
     */
    public function getWXServerIp(){
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=100t8vbV6JB0OoyidAKtz3WBf0QTFs3EbAqfP3G8SgnSn5ZfqMIaX1KtAjhWDR3hU4ncr4SsUH3BQyPRJJgVkseO7EtlkXc55oeV38XKS_IOPyQHknR2uQLM65OFecTbCINaADATZE';
        $serverIp = $this->httpRequest($requestUrl);
        return $serverIp;
    }

    public function show(){
        $token = $this->getAccessToken();
        $serverIp = $this->getWXServerIp();
        var_dump($serverIp);
        var_dump($token);
    }

	public function test(){
        $m = new \Memcached();
        $m->addServer("47.98.11.105",'11211');
        //var_dump($server);exit;
        $m->set('mykey',md5(range(10000,20000)));
        $a = $m->get('mykey');
        var_dump($a);
	}


}
