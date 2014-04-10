<?php
/**
 * Plugin Name: Open Social for China
 * Plugin URI: http://www.xiaomac.com/201311150.html
 * Description: Login and Share with social networks: QQ, Sina, Baidu, Google, Live, DouBan, RenRen, KaiXin, XiaoMi, CSDN, OSChina, Facebook, Twitter, Github. No API, NO Register, NO 3rd-Party!
 * Author: Afly
 * Author URI: http://www.xiaomac.com/
 * Version: 1.3.0
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: open-social
 * Domain Path: /lang
 */

if(!session_id()) session_start();
$GLOBALS['osop'] = get_option('osop');

//init
add_action('init', 'open_init', 1);
function open_init() {
	load_plugin_textdomain( 'open-social', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	$GLOBALS['open_str'] = array(
		'qq'		=> __('QQ','open-social'),
		'sina'		=> __('Sina','open-social'),
		'baidu'		=> __('Baidu','open-social'),
		'google'	=> __('Google','open-social'),
		'live'		=> __('Microsoft Live','open-social'),
		'douban'	=> __('Douban','open-social'),
		'renren'	=> __('RenRen','open-social'),
		'kaixin'	=> __('Kaixin001','open-social'),
		'xiaomi'	=> __('XiaoMi','open-social'),
		'csdn'		=> __('CSDN','open-social'),
		'oschina'	=> __('OSChina','open-social'),
		'163'		=> __('163','open-social'),
		'360'		=> __('360','open-social'),
		'taobao'	=> __('Taobao','open-social'),
		'facebook'	=> __('Facebook','open-social'),
		'twitter'	=> __('Twitter','open-social'),
		'instagram'	=> __('Instagram','open-social'),
		'github'	=> __('Github','open-social'),
		'login' 	=> __('Login with %OPEN_TYPE%','open-social'),
		'unbind'	=> __('Unbind with %OPEN_TYPE%','open-social'),
		'share'		=> __('Share with %SHARE_TYPE%','open-social'),
		'share_weibo'	=> __('Sina','open-social'),
		'share_qzone'	=> __('QQZone','open-social'),
		'share_qqt'		=> __('QQWeiBo','open-social'),
		'share_youdao'	=> __('YoudaoNote','open-social'),
		'share_email'	=> __('Email to Me','open-social'),
		'share_qq'		=> __('Chat with Me','open-social'),
		'share_weixin'	=> __('WeiXin','open-social'),
		'share_google'	=> __('Google Translation','open-social'),
		'share_twitter'	=> __('Twitter','open-social'),
		'share_facebook'	=> __('Facebook','open-social'),
		'share_language'	=> __('Language Switcher','open-social'),
		'setting_menu'		=> __('Open Social','open-social'),
		'setting_menu_adv'	=> __('Account Setting','open-social'),
		'about_title'			=> __('About this plugin','open-social'),
		'about_info'			=> __('if you like this plugin','open-social'),
		'about_alipay'			=> __('Buy me a drink','open-social'),
		'about_link'			=> __('Or leave me a link','open-social'),
		'about_plugin'			=> __('Or give me Five','open-social'),
		'widget_title'			=> __('Open Social Login', 'open-social'),
		'widget_name'			=> __('Howdy', 'open-social'),
		'widget_desc'			=> __('Display your Open Social login button', 'open-social'),
		'widget_share_title'	=> __('Open Social Share', 'open-social'),
		'widget_share_name'		=> __('Connect', 'open-social'),
		'widget_share_desc'		=> __('Display your Open Social share button', 'open-social'),
		'widget_float_title'	=> __('Floating Button', 'open-social'),
		'widget_float_desc'		=> __('Some floating useful buttons', 'open-social'),
		'err_other_openid'		=> __('This account has been bound by other user.','open-social'),
		'err_other_user'		=> __('You can only bind to one account at a time.','open-social'),
		'err_other_email'		=> __('Your EMAIL has been registered by other user.','open-social'),
		'osop_login_button'			=> __('Login Buttons','open-social'),
		'osop_show_login_form1'		=> __('Before comment form','open-social'),
		'osop_show_login_form2'		=> __('After comment form','open-social'),
		'osop_show_login_form0'		=> __('None','open-social'),
		'osop_show_login_page'		=> __('Show in Login page','open-social'),
		'osop_share_button'			=> __('Share Button','open-social'),
		'osop_show_share_content'	=> __('Show in Post pages','open-social'),
		'osop_share_sina_user'		=> __('Sina weibo related UserID','open-social'),
		'osop_share_qqt_appkey'		=> __('QQ weibo share AppKey','open-social'),
		'osop_share_qq_email'		=> __('QQ EmailMe code','open-social'),
		'osop_share_qq_talk'		=> __('QQ ContactMe link','open-social'),
		'osop_delete_setting'		=> __('Delete all configurations on this page after plugin deleted, NOT RECOMMENDED!','open-social'),
		'open_social_hide_text'		=> __('Login to check this hidden content out','open-social'),
		'open_social_email_hello'	=> __('Hello','open-social'),
		'open_social_email_title'	=> __('New reply to your comment','open-social'),
		'open_social_email_text1'	=> __('Go check it out','open-social'),
		'open_social_email_text2'	=> __('Receive reply email notification','open-social'),
		'osop_extend_function'		=> __('Extensions','open-social'),
		'osop_extend_show_nickname'	=> __('Show nickname in users list','open-social'),
		'open_social_edit_profile'	=> __('Please update your profile before posting a comment, thx.','open-social'),
		'osop_extend_email_login'	=> __('Allow to login with email address','open-social'),
	);
    $GLOBALS['open_arr'] = array('qq','sina','baidu','google','live','douban','renren','kaixin','xiaomi','csdn','oschina','facebook','twitter','github');
    $GLOBALS['open_share_arr'] = array(
        'weibo'=>"http://v.t.sina.com.cn/share/share.php?url=%URL%&title=%TITLE%&appkey=".osop('SINA_AKEY')."&ralateUid=".osop('share_sina_user')."&language=zh_cn&searchPic=true",
        'qzone'=>"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=%URL%&title=%TITLE%&desc=&summary=&site=",
        'qqt'=>"http://share.v.t.qq.com/index.php?c=share&amp;a=index&url=%URL%&title=%TITLE%&appkey=".osop('share_qqt_appkey'),
        'youdao'=>"http://note.youdao.com/memory/?url=%URL%&title=%TITLE%&sumary=&pic=&product=",
        'weixin'=>"http://chart.apis.google.com/chart?chs=400x400&cht=qr&chld=L|5&chl=%URL%",
        'email'=>"http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=".osop('share_qq_email'),
        'qq'=>'http://sighttp.qq.com/authd?IDKEY='.osop('share_qq_talk'),
        'google'=>"http://translate.google.com.hk/translate?hl=zh-CN&sl=en&tl=zh-CN&u=%URL%",
        'twitter'=>"http://twitter.com/home/?status=%TITLE%:%URL%",
        'facebook'=>"http://www.facebook.com/sharer.php?u=%URL%&amp;t=%TITLE%",
        'language'=>home_url('/')."?open_lang=".(get_locale()!='en_US'?'en_US':'zh_CN')
    );
	if (isset($_GET['connect'])) {
		define('OPEN_TYPE',$_GET['connect']);
		if(in_array(OPEN_TYPE,$GLOBALS['open_arr'])){
		    $open_class = strtoupper(OPEN_TYPE).'_CLASS';
			$os = new $open_class();
		}else{
			exit();
		}
		if ($_GET['action'] == 'login') {
			if($_GET['back']) $_SESSION['back'] = $_GET['back'];
			$os -> open_login();
		} else if ($_GET['action'] == 'callback') {
			if(!isset($_GET['code']) || isset($_GET['error']) || isset($_GET['error_description'])){
				header('Location:'.home_url());
				exit();
			}
			$os -> open_callback($_GET['code']);
			open_action( $os );
		} else if ($_GET['action'] == 'unbind') {
			open_unbind();
		} else if ($_GET['action'] == 'update'){
			if (OPEN_TYPE=='sina' && isset($_GET['text'])) open_update_test($_GET['text']);
		}
	}else{
		if (isset($_GET['code']) && isset($_GET['state'])) {
			if($_GET['state']=='profile' && osop('GOOGLE')) header('Location:'.osop('GOOGLE_BACK').'?connect=google&action=callback&'.http_build_query($_GET));//for google
			if(strlen($_GET['state'])==32 && osop('DOUBAN')) header('Location:'.osop('DOUBAN_BACK').'?connect=douban&action=callback&'.http_build_query($_GET));//for douban
			exit();
		}
	} 
} 

//activate
register_activation_hook( __FILE__, 'open_social_activation' );
function open_social_activation(){
	if(!$GLOBALS['osop']) update_option('osop', array(
		'show_login_page'	    => 0,
		'show_login_form'	    => 1,
		'show_share_content'    => 0,
		'extend_show_nickname'	=> 1,
		'extend_comment_email'	=> 1,
		'extend_email_login'	=> 1,
		'delete_setting'	    => 0
	));
}
//uninstall
register_uninstall_hook( __FILE__, 'open_social_uninstall' );
function open_social_uninstall(){
	if( osop('delete_setting',1) ) delete_option('osop');
}

function osop($osop_key,$osop_val=NULL){
    if(isset($GLOBALS['osop']) && isset($GLOBALS['osop'][$osop_key])){
        if(isset($osop_val)){
            return $GLOBALS['osop'][$osop_key]==$osop_val;
        }else{
            return $GLOBALS['osop'][$osop_key];
        }
    }
    return '';
}

class QQ_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('QQ_AKEY'),
			'state'=>md5(uniqid(rand(), true)),
			'scope'=>'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo',
			'redirect_uri'=>osop('QQ_BACK').'?connect=qq&action=callback'
		);
		header('Location:https://graph.qq.com/oauth2.0/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('QQ_AKEY'),
			'client_secret'=>osop('QQ_SKEY'),
			'redirect_uri'=>osop('QQ_BACK').'?connect=qq&action=callback'
		);
		$str = open_connect_http('https://graph.qq.com/oauth2.0/token?'.http_build_query($params));

		$_SESSION['access_token'] = $str['access_token'];
		$str = open_connect_http("https://graph.qq.com/oauth2.0/me?access_token=".$_SESSION['access_token']);
		$str_r = json_decode(trim(trim(trim($str),'callback('),');'), true);
		if(isset($str_r['error'])) open_close("<h3>error:</h3>".$str_r['error']."<h3>msg  :</h3>".$str_r['error_description']);
		$_SESSION['open_id'] = $str_r['openid'];
	} 
	function open_new_user(){
		$str = open_connect_http('https://graph.qq.com/user/get_user_info?access_token='.$_SESSION['access_token'].'&oauth_consumer_key='.osop('QQ_AKEY').'&openid='.$_SESSION['open_id']);
		$nickname = $str['nickname'];
		$str = open_connect_http('https://graph.qq.com/user/get_info?access_token='.$_SESSION['access_token'].'&oauth_consumer_key='.osop('QQ_AKEY').'&openid='.$_SESSION['open_id']);
		$name = $str['data']['name'];//t.qq.com/***
		return array(
			'nickname' => $nickname?$nickname:'Q'.time(),
			'display_name' => $nickname?$nickname:'Q'.time(),
			'user_url' => $name?'http://t.qq.com/'.$name:'',
			'user_email' => ($name?$name:strtolower($_SESSION['open_id'])).'@t.qq.com'//fake
		);
	}
} 

class SINA_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('SINA_AKEY'),
			'redirect_uri'=>osop('SINA_BACK').'?connect=sina&action=callback'
		);
		header('Location:https://api.weibo.com/oauth2/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('SINA_AKEY'),
			'client_secret'=>osop('SINA_SKEY'),
			'redirect_uri'=>osop('SINA_BACK').'?connect=sina&action=callback'
		);
		$str = open_connect_http('https://api.weibo.com/oauth2/access_token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
		$_SESSION['open_id'] = $str["uid"];
	}
	function open_new_user(){
		$user = open_connect_http("https://api.weibo.com/2/users/show.json?access_token=".$_SESSION["access_token"]."&uid=".$_SESSION['open_id']);
		return array(
			'nickname' => $user['screen_name'],
			'display_name' => $user['screen_name'],
			'user_url' => 'http://weibo.com/'.$user['profile_url'],
			'user_email' => $_SESSION['open_id'].'@weibo.com'//fake
		);
	} 
} 

class BAIDU_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('BAIDU_AKEY'),
			'redirect_uri'=>osop('BAIDU_BACK').'?connect=baidu&action=callback',
			'scope'=>'basic',
			'display'=>'page'
		);
		header('Location:https://openapi.baidu.com/oauth/2.0/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('BAIDU_AKEY'),
			'client_secret'=>osop('BAIDU_SKEY'),
			'redirect_uri'=>osop('BAIDU_BACK').'?connect=baidu&action=callback'
		);
		$str = open_connect_http('https://openapi.baidu.com/oauth/2.0/token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
	}
	function open_new_user(){
		$user = open_connect_http("https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser?access_token=".$_SESSION["access_token"]);
		$_SESSION['open_id'] = $user['uid'];
		$_SESSION['open_img'] = 'http://himg.bdimg.com/sys/portrait/item/'.$user['portrait'].'.jpg';
		return array(
			'nickname' => $user["uname"],
			'display_name' => $user["uname"],
			'user_url' => 'http://www.baidu.com/p/'.$user['uname'],
			'user_email' => $user["uid"].'@baidu.com'//fake
		);
	}
} 

class GOOGLE_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('GOOGLE_AKEY'),
			'scope'=>'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
			'redirect_uri'=> osop('GOOGLE_BACK'),
			'state'=>'profile',
			'access_type'=>'offline'
		);
		header('Location:https://accounts.google.com/o/oauth2/auth?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('GOOGLE_AKEY'),
			'client_secret'=>osop('GOOGLE_SKEY'),
			'redirect_uri'=>osop('GOOGLE_BACK')
		);
		$str = open_connect_http('https://accounts.google.com/o/oauth2/token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
	}
	function open_new_user(){
		$user = open_connect_http("https://www.googleapis.com/oauth2/v1/userinfo?access_token=".$_SESSION["access_token"]);
		$_SESSION['open_id'] = $user["id"];
		$_SESSION['open_img'] = $user["picture"];
		return array(
			'nickname' => $user['name'],
			'display_name' => $user['name'],
			'user_url' => $user['link'],
			'user_email' => $user["email"]//this one is real
		);
	}
} 

class LIVE_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('LIVE_AKEY'),
			'redirect_uri'=>osop('LIVE_BACK').'?connect=live&action=callback',
			'scope'=>'wl.signin wl.basic wl.emails'
		);
		header('Location:https://login.live.com/oauth20_authorize.srf?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('LIVE_AKEY'),
			'client_secret'=>osop('LIVE_SKEY'),
			'redirect_uri'=>osop('LIVE_BACK').'?connect=live&action=callback'
		);
		$str = open_connect_http('https://login.live.com/oauth20_token.srf', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
	}
	function open_new_user(){
		$user = open_connect_http("https://apis.live.net/v5.0/me");
		$_SESSION['open_id'] = $user["id"];
		return array(
			'nickname' => $user["name"],
			'display_name' => $user["name"],
			'user_url' => 'https://profile.live.com/cid-'.$_SESSION['open_id'],
			'user_email' => $user['emails']['preferred']//this on is real too
		);
	}
} 

class DOUBAN_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('DOUBAN_AKEY'),
			'redirect_uri'=>osop('DOUBAN_BACK'),
			'scope'=>'shuo_basic_r,shuo_basic_w,douban_basic_common',
			'state'=>md5(time())
		);
		header('Location:https://www.douban.com/service/auth2/auth?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('DOUBAN_AKEY'),
			'client_secret'=>osop('DOUBAN_SKEY'),
			'redirect_uri'=>osop('DOUBAN_BACK')
		);
		$str = open_connect_http('https://www.douban.com/service/auth2/token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
		$_SESSION['open_id'] = $str["douban_user_id"];
	}
	function open_new_user(){
		$user = open_connect_http("https://api.douban.com/v2/user/~me?access_token=".$_SESSION["access_token"]);
		return array(
			'nickname' => $user['name'],
			'display_name' => $user['name'],
			'user_url' => 'http://www.douban.com/people/'.$_SESSION['open_id'].'/',
			'user_email' => $_SESSION['open_id'].'@douban.com'//fake
		);
	}
} 

class RENREN_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('RENREN_AKEY'),
			'redirect_uri'=>osop('RENREN_BACK').'?connect=renren&action=callback',
			'scope'=>'status_update read_user_status'
		);
		header('Location:https://graph.renren.com/oauth/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('RENREN_AKEY'),
			'client_secret'=>osop('RENREN_SKEY'),
			'redirect_uri'=>osop('RENREN_BACK').'?connect=renren&action=callback'
		);
		$str = open_connect_http('https://graph.renren.com/oauth/token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
		$_SESSION['open_id'] = $str["user"]["id"];
	}
	function open_new_user(){
		$user = open_connect_http("https://api.renren.com/v2/user/login/get?access_token=".$_SESSION["access_token"]);
		$_SESSION['open_img'] = $user['response']["avatar"][1]['url'];
		return array(
			'nickname' => $user['response']['name'],
			'display_name' => $user['response']['name'],
			'user_url' => 'http://www.renren.com/'.$_SESSION['open_id'].'/profile',
			'user_email' => $_SESSION['open_id'].'@renren.com'//fake
		);
	}
} 

class KAIXIN_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('KAIXIN_AKEY'),
			'redirect_uri'=>osop('KAIXIN_BAC').'?connect=kaixin&action=callback',
			'scope'=>'basic'
		);
		header('Location:http://api.kaixin001.com/oauth2/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('KAIXIN_AKEY'),
			'client_secret'=>osop('KAIXIN_SKEY'),
			'redirect_uri'=>osop('KAIXIN_BACK').'?connect=kaixin&action=callback'
		);
		$str = open_connect_http('https://api.kaixin001.com/oauth2/access_token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
	}
	function open_new_user(){
		$user = open_connect_http("https://api.kaixin001.com/users/me?access_token=".$_SESSION["access_token"]);
		$_SESSION['open_id'] = $user["uid"];
		$_SESSION['open_img'] = $user['logo50'];
		return array(
			'nickname' => $user['name'],
			'display_name' => $user['name'],
			'user_url' => 'http://www.kaixin001.com/home/'.$_SESSION['open_id'].'.html',
			'user_email' => $_SESSION['open_id'].'@kaixin.com'//fake
		);
	}
} 

class XIAOMI_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('XIAOMI_AKEY'),
			'redirect_uri'=>osop('XIAOMI_BACK').'?connect=xiaomi&action=callback',
			'state'=>'state',
			'scope'=>''
		);
		header('Location:https://account.xiaomi.com/oauth2/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('XIAOMI_AKEY'),
			'client_secret'=>osop('XIAOMI_SKEY'),
			'redirect_uri'=>osop('XIAOMI_BACK').'?connect=xiaomi&action=callback',
			'token_type'=>'mac'
		);
		$str = open_connect_http('https://account.xiaomi.com/oauth2/token?'.http_build_query($params));
		$_SESSION["access_token"] = $str["access_token"];
		$_SESSION["mac_key"] = $str["mac_key"];
	}
	function open_new_user(){
        list($usec, $sec) = explode(' ', microtime());
        $nonce = (float)mt_rand();
        $minutes = (int)($sec / 60);
        $nonce = $nonce.":".$minutes;
        $base = $nonce."\nGET\nopen.account.xiaomi.com\n/user/profile\nclientId=".osop('XIAOMI_AKEY')."&token=".$_SESSION["access_token"]."\n";
		$sign = urlencode(base64_encode(hash_hmac('sha1', $base, $_SESSION["mac_key"], true)));
        $head = array('Authorization:MAC access_token="'.$_SESSION["access_token"].'", nonce="'.$nonce.'",mac="'.$sign.'"');
		$user = open_connect_http("https://open.account.xiaomi.com/user/profile?clientId=".osop('XIAOMI_AKEY')."&token=".$_SESSION["access_token"],'','GET',$head);
		$_SESSION['open_id'] = $user['data']['userId'];
		//$_SESSION['open_img'] = $user['data']['miliaoIcon'];//not there
		unset($_SESSION["mac_key"]);
		return array(
			'nickname' => $user['data']['aliasNick'],
			'display_name' => $user['data']['miliaoNick'],
			'user_url' => 'http://www.miui.com/space-uid-'.$_SESSION['open_id'].'.html',
			'user_email' => $_SESSION['open_id'].'@xiaomi.com'//fake
		);
	}
} 

class CSDN_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('CSDN_AKEY'),
			'redirect_uri'=>osop('CSDN_BACK').'?connect=csdn&action=callback'
		);
		header('Location:http://api.csdn.net/oauth2/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('CSDN_AKEY'),
			'client_secret'=>osop('CSDN_SKEY'),
			'redirect_uri'=>osop('CSDN_BACK').'?connect=csdn&action=callback'
		);
		$str = open_connect_http('http://api.csdn.net/oauth2/access_token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
		$_SESSION['open_id'] = $str["username"];//not id
		$user_avatar = open_connect_http("http://api.csdn.net/user/getavatar?access_token=".$_SESSION["access_token"]."&users=".$_SESSION['open_id']);
		$_SESSION['open_img'] = $user_avatar[0]['avatar'];
	}
	function open_new_user(){
		$user = open_connect_http("http://api.csdn.net/user/getinfo?access_token=".$_SESSION["access_token"]);
		return array(
			'nickname' => $user['nickname']?$user['nickname']:$user['username'],
			'display_name' => $user['username'],
			'user_url' => 'http://my.csdn.net/'.$user['username'],
			'user_email' => $_SESSION['open_id'].'@csdn.net'//fake
		);
	} 
} 

class OSCHINA_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('OSCHINA_AKEY'),
			'redirect_uri'=>osop('OSCHINA_BACK').'?connect=oschina&action=callback'
		);
		header('Location:https://www.oschina.net/action/oauth2/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>osop('OSCHINA_AKEY'),
			'client_secret'=>osop('OSCHINA_SKEY'),
			'redirect_uri'=>osop('OSCHINA_BACK').'?connect=oschina&action=callback',
			'dataType'=>'json'
		);
		$str = open_connect_http('https://www.oschina.net/action/openapi/token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
	}
	function open_new_user(){
		$user = open_connect_http("https://www.oschina.net/action/openapi/user?access_token=".$_SESSION["access_token"]);
		$_SESSION['open_id'] = $user["id"];
		$_SESSION['open_img'] = $user['avatar'];
		return array(
			'nickname' => $user['name'],
			'display_name' => $user['name'],
			'user_url' => $user['url'],
			'user_email' => $user['email']//real one
		);
	} 
} 

class FACEBOOK_CLASS {
	function open_login() {
		$params=array(
			'response_type'=>'code',
			'client_id'=>osop('FACKBOOK_AKEY'),
			'redirect_uri'=>osop('FACKBOOK_BACK').'?connect=facebook&action=callback',
			'state'=>md5(uniqid(rand(), true)),
			'display'=>'page',
			'scope'=>'basic_info,email'
		);
		header('Location:https://www.facebook.com/dialog/oauth?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'code'=>$code,
			'client_id'=>osop('FACKBOOK_AKEY'),
			'client_secret'=>osop('FACKBOOK_SKEY'),
			'redirect_uri'=>osop('FACKBOOK_BACK').'?connect=facebook&action=callback'
		);		
		$str = open_connect_http('https://graph.facebook.com/oauth/access_token?'.http_build_query($params));
		$_SESSION['access_token'] = $str['access_token'];
	}
	function open_new_user(){
		$user_img = open_connect_http("https://graph.facebook.com/me/picture?redirect=false&height=100&type=small&width=100");
		$_SESSION['open_img'] = $user_img['data']['url'];	
		$user = open_connect_http("https://graph.facebook.com/me?access_token=".$_SESSION['access_token']);
		$_SESSION['open_id'] = $user['id'];
		return array(
			'nickname' => $user['username'],
			'display_name' => $user['name'],
			'user_url' => $user['link'],
			'user_email' => $user['email']//real one
		);
	} 
} 
  
class TWITTER_CLASS {
	function open_login() {
        $now = time();
		$str = '';
		$params=array(
			'oauth_callback'=>osop('TWITTER_BACK').'?connect=twitter&action=callback&code=1',//fix no code return
			'oauth_consumer_key'=>osop('TWITTER_AKEY'),
			'oauth_nonce'=>trim(base64_encode($now), '='),
			'oauth_signature_method'=>'HMAC-SHA1',
			'oauth_timestamp'=>$now,
			'oauth_version'=>'1.0'
		);
		foreach ($params as $key => $val) { $str .= '&' . $key . '=' . rawurlencode($val); }
        $base = 'GET&'.rawurlencode('https://api.twitter.com/oauth/request_token').'&'.rawurlencode(trim($str, '&'));
		$sign = base64_encode(hash_hmac('sha1', $base, osop('TWITTER_SKEY').'&', true));
		$params['oauth_signature'] = $sign;
		$str = open_connect_http('https://api.twitter.com/oauth/request_token?'.http_build_query($params));
		$_SESSION['oauth_token_secret'] = $str['oauth_token_secret'];
        header('Location:https://api.twitter.com/oauth/authorize?oauth_token='.$str['oauth_token']);
		exit();
	} 
	function open_callback($code) {
        $now = time();
		$str = '';
		$params=array(
			'oauth_consumer_key'=>osop('TWITTER_AKEY'),
			'oauth_nonce'=>trim(base64_encode($now), '='),
			'oauth_signature_method'=>'HMAC-SHA1',
			'oauth_timestamp'=>$now,
			'oauth_token'=>$_GET['oauth_token'],
			'oauth_version'=>'1.0'
		);
		foreach ($params as $key => $val) { $str .= '&' . $key . '=' . rawurlencode($val); }
        $base = 'GET&'.rawurlencode('https://api.twitter.com/oauth/access_token').'&'.rawurlencode(trim($str, '&'));
		$sign = base64_encode(hash_hmac('sha1', $base, osop('TWITTER_SKEY').'&'.$_SESSION['oauth_token_secret'], true));
		$params['oauth_signature'] = $sign;
		$params['oauth_verifier'] = $_GET['oauth_verifier'];
		unset($_SESSION['oauth_token_secret']);
		$token = open_connect_http('https://api.twitter.com/oauth/access_token?'.http_build_query($params));
		$_SESSION['access_token'] = $token['oauth_token'];
		//$_SESSION['access_token_secret'] = $token['oauth_token_secret'];
		$_SESSION['open_id'] = $token['user_id'];
		$_SESSION['open_name'] = $token['screen_name'];
	}
	function open_new_user(){
		$twnu = array(
			'nickname' => $_SESSION['open_name'],
			'display_name' => $_SESSION['open_name'],
			'user_url' => 'https://twitter.com/'.$_SESSION['open_name'],
			'user_email' => $_SESSION['open_name'].'@twitter.com'//really fake one
		);
		unset($_SESSION['open_name']);
		return $twnu;
	} 
} 

class GITHUB_CLASS {
	function open_login() {
		$params=array(
			'client_id'=>osop('GITHUB_AKEY'),
			'redirect_uri'=>osop('GITHUB_BACK').'?connect=github&action=callback',
			'scope'=>'user'
		);
		header('Location:https://github.com/login/oauth/authorize?'.http_build_query($params));
		exit();
	} 
	function open_callback($code) {
		$params=array(
			'code'=>$code,
			'client_id'=>osop('GITHUB_AKEY'),
			'client_secret'=>osop('GITHUB_SKEY'),
			'redirect_uri'=>osop('GITHUB_BACK').'?connect=github&action=callback'
		);
		$str = open_connect_http('https://github.com/login/oauth/access_token', http_build_query($params), 'POST');
		$_SESSION["access_token"] = $str["access_token"];
	}
	function open_new_user(){
		$user = open_connect_http("https://api.github.com/user?access_token=".$_SESSION["access_token"]);
		$_SESSION['open_id'] = $user['id'];
		$_SESSION['open_img'] = $user['avatar_url'];
		return array(
			'nickname' => $user['login'],
			'display_name' => $user['login'],
			'user_url' => $user['url'],
			'user_email' => $user['login'].'@github.com'//fake
		);
	} 
} 

function open_close($open_info){
	wp_die($open_info);
	exit();
}

function open_isbind($open_id) {
	global $wpdb;
	$sql = "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '%s' AND meta_value = '%s'";
	return $wpdb -> get_var($wpdb -> prepare($sql, 'open_id', $open_id));
} 

function open_unbind(){
	if (is_user_logged_in()) {
		$user = wp_get_current_user();
		delete_user_meta($user -> ID, 'open_type');
		delete_user_meta($user -> ID, 'open_email');
		delete_user_meta($user -> ID, 'open_img');
		delete_user_meta($user -> ID, 'open_id');
		delete_user_meta($user -> ID, 'open_access_token');
		header('Location:'.get_edit_user_link($user -> ID));
	}
	exit;
}

function open_action($os){
	if (!isset($_SESSION['open_id'])) $newuser = $os -> open_new_user();
	if (!$_SESSION['access_token'] ||!$_SESSION['open_id'] || !OPEN_TYPE) return;
	if (is_user_logged_in()) {//bind
		$wpuid = get_current_user_id();
		if (open_isbind($_SESSION['open_id'])) {
			open_close($GLOBALS['open_str']['err_other_openid']);
		}else{
			$open_id = get_user_meta($wpuid, 'open_id', true);
			if ($open_id) open_close($GLOBALS['open_str']['err_other_user']);
		}
	} else { //login
		if(!isset($newuser)) $newuser = $os -> open_new_user();//refresh avatar in case
		$wpuid = open_isbind($_SESSION['open_id']);
		if (!$wpuid) {
			$wpuid = username_exists(strtoupper(OPEN_TYPE).$_SESSION['open_id']);
			if(!$wpuid){
				$userdata = array(
					'user_pass' => wp_generate_password(),
					'user_login' => strtoupper(OPEN_TYPE).$_SESSION['open_id'],
					'show_admin_bar_front' => 'false'
				);
				$userdata = array_merge($userdata, $newuser);
				if(email_exists($userdata['user_email'])) open_close($GLOBALS['open_str']['err_other_email']);//Google,Live
				if(!function_exists('wp_insert_user')){
					include_once( ABSPATH . WPINC . '/registration.php' );
				} 
				$wpuid = wp_insert_user($userdata);
			}
		} 
	} 
	if($wpuid){
		update_user_meta($wpuid, 'open_type', OPEN_TYPE);
		if(isset($_SESSION['open_img'])) update_user_meta($wpuid, 'open_img', $_SESSION['open_img']);
		update_user_meta($wpuid, 'open_id', $_SESSION['open_id']);
		update_user_meta($wpuid, 'open_access_token', $_SESSION["access_token"]);
		wp_set_auth_cookie($wpuid, true, false);
		wp_set_current_user($wpuid);
	}
	unset($_SESSION['open_id']);
	unset($_SESSION["access_token"]);
	if(isset($_SESSION['open_img'])) unset($_SESSION['open_img']); 
	$back = isset($_SESSION['back']) ? $_SESSION['back'] : home_url();
	if(isset($_SESSION['back'])) unset($_SESSION['back']); 
	header('Location:'.$back);
	exit;
}

//post api (test for now)
function open_update_test($text){
	$params=array(
		'status'=>$text
	);
	$re = open_connect_api('https://api.weibo.com/2/statuses/update.json', $params, 'POST');
	echo '<script>alert("ok");opener.window.focus();window.close();</script>';
	exit;
}

function open_connect_api($url, $params=array(), $method='GET'){
	$user = wp_get_current_user();
	$access_token = get_user_meta($user -> ID, 'open_access_token', true);
	if($access_token){
		$params['access_token']=$access_token;
		if($method=='GET'){
			$result=open_connect_http($url.'?'.http_build_query($params));
		}else{
			$result=open_connect_http($url, http_build_query($params), 'POST');
		}
		return $result;	
	}
}

function open_connect_http($url, $postfields='', $method='GET', $headers=array()){
	$ci = curl_init();
    //curl_setopt($ci, CURLOPT_PROXY, '192.168.1.10:8087');//gae debug for facebook/twitter/
	curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ci, CURLOPT_HEADER, false);
	curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ci, CURLOPT_TIMEOUT, 30);
	if($method=='POST'){
		curl_setopt($ci, CURLOPT_POST, TRUE);
		if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
	}
	if(!$headers && isset($_SESSION["access_token"])){
		$headers[]='Authorization: Bearer '.$_SESSION["access_token"];
	}
	$headers[] = 'User-Agent: Open Social Login for China(xiaomac.com)';
	$headers[] = 'Expect:';
	curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ci, CURLOPT_URL, $url);
	$response = curl_exec($ci);
	if($response===false) $response = curl_error($ci);
	curl_close($ci);
	$response = trim(trim($response),'&&&START&&&');
	$json_r = array();
	$json_r = json_decode($response, true);
    if(count($json_r)==0) parse_str($response,$json_r);
    if(count($json_r)==1 && current($json_r)==='') return $response;
	return $json_r;
}

function open_check_url($url){
	$headers = @get_headers($url);
	if (!preg_match("|200|", $headers[0])) {
		return FALSE;
	} else {
		return TRUE;
	}
}

//lang
add_filter( 'locale', 'open_social_locale' );
function open_social_locale( $lang ) {
	if ( isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) && !isset($_SESSION['WPLANG_LOCALE']) ) {
		$languages = strtolower( $_SERVER["HTTP_ACCEPT_LANGUAGE"] );
		$languages = explode( ",", $languages );
		$languages = explode( "-", $languages[0] );
		$_SESSION['WPLANG_LOCALE'] = strtolower($languages[0]) . '_' . strtoupper($languages[1]);
	}
	if ( isset( $_GET['open_lang'] ) && strpos($_GET['open_lang'], "_") ) {
		$_SESSION['WPLANG'] = $_GET['open_lang'];
		$back = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : home_url('/');
		header('Location:'.$back);
		exit();
	} else {
		if( isset($_SESSION['WPLANG']) && strpos($_SESSION['WPLANG'], "_") ) {
			return $_SESSION['WPLANG'];
		} else {
			$_SESSION['WPLANG'] = $lang;
			return $lang;
		}
	} 
}

//admin_init
add_action( 'admin_init', 'open_social_admin_init' );
function open_social_admin_init() {
	register_setting( 'open_social_options_group', 'osop' );
}

//setting link 
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'open_settings_link' );
function open_settings_link($links) {
	array_unshift($links, '<a href="options-general.php?page='.plugin_basename(__FILE__).'">'.__('Settings').'</a>');
	return $links;
}

//setting menu
add_action('admin_menu', 'open_options_add_page');
function open_options_add_page() {
    if(!current_user_can('manage_options')){
	    remove_menu_page('index.php'); 
    }else{
		add_options_page($GLOBALS['open_str']['setting_menu'], $GLOBALS['open_str']['setting_menu'], 'manage_options', plugin_basename(__FILE__), 'open_options_page');
	}
}

//setting page
function open_options_page() {
    ?> 
	<div class="wrap">
		<h2><?php echo $GLOBALS['open_str']['setting_menu']?></h2>
		<form action="options.php" method="post">
		<?php
		    settings_fields( 'open_social_options_group' );
		?>
		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php echo $GLOBALS['open_str']['osop_login_button']?><br/>
			<a href="<?php echo admin_url('widgets.php');?>"><?php echo __('Widgets');?></a></th>
		<td><fieldset>
			<label for="osop[show_login_page]"><input name="osop[show_login_page]" id="osop[show_login_page]" type="checkbox" value="1" <?php checked(osop('show_login_page'),1);?> /> <?php echo $GLOBALS['open_str']['osop_show_login_page']?></label><br/>
			<label for="osop[show_login_form1]"><input name="osop[show_login_form]" id="osop[show_login_form1]" type="radio" value="1" <?php checked(osop('show_login_form'),1);?> /> <?php echo $GLOBALS['open_str']['osop_show_login_form1']?></label> 
			<label for="osop[show_login_form2]"><input name="osop[show_login_form]" id="osop[show_login_form2]" type="radio" value="2" <?php checked(osop('show_login_form'),2);?> /> <?php echo $GLOBALS['open_str']['osop_show_login_form2']?></label>  
			<label for="osop[show_login_form0]"><input name="osop[show_login_form]" id="osop[show_login_form0]" type="radio" value="0" <?php checked(osop('show_login_form'),0);?> /> <?php echo $GLOBALS['open_str']['osop_show_login_form0']?></label>
		</fieldset>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo $GLOBALS['open_str']['osop_share_button']?><br/>
			<a href="<?php echo admin_url('widgets.php');?>"><?php echo __('Widgets');?></a></th>
		<td><fieldset>
			<p><label for="osop[show_share_content]"><input name="osop[show_share_content]" id="osop[show_share_content]" type="checkbox" value="1" <?php checked(osop('show_share_content'),1);?> /> <?php echo $GLOBALS['open_str']['osop_show_share_content']?></label> <br/>
			<input name="osop[share_sina_user]" id="osop[share_sina_user]" class="regular-text" value="<?php echo osop('share_sina_user')?>" />
			<a href="http://open.weibo.com/sharebutton" target="_blank"><?php echo $GLOBALS['open_str']['osop_share_sina_user']?></a><br/>
			<input name="osop[share_qqt_appkey]" id="osop[share_qqt_appkey]" class="regular-text" value="<?php echo osop('share_qqt_appkey')?>" />
			<a href="http://dev.t.qq.com/websites/share/" target="_blank"><?php echo $GLOBALS['open_str']['osop_share_qqt_appkey']?></a> <br/>
			<input name="osop[share_qq_email]" id="osop[share_qq_email]" class="regular-text" value="<?php echo osop('share_qq_email')?>" />
			<a href="http://open.mail.qq.com/" target="_blank"><?php echo $GLOBALS['open_str']['osop_share_qq_email']?></a> <br/>
			<input name="osop[share_qq_talk]" id="osop[share_qq_talk]" class="regular-text" value="<?php echo osop('share_qq_talk')?>" />
			<a href="http://shang.qq.com/widget/set.php" target="_blank"><?php echo $GLOBALS['open_str']['osop_share_qq_talk']?></a></p>
			<?php
			$i = 0;
			foreach ($GLOBALS['open_share_arr'] as $k=>$v) {
                echo '<label for="osop[share_'.$k.']"><input name="osop[share_'.$k.']" id="osop[share_'.$k.']" type="checkbox" value="1" '.checked(osop('share_'.$k),1,false).' title="'.__('Enabled').'" />'.$GLOBALS['open_str']['share_'.$k].'</label> ';
                if(($i+1)%6==0) echo '<br>';
                $i++;
			}?>
		</fieldset>
		</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo $GLOBALS['open_str']['osop_extend_function']?></th>
		<td><fieldset>
			<label for="osop[extend_comment_email]"><input name="osop[extend_comment_email]" id="osop[extend_comment_email]" type="checkbox" value="1" <?php checked(osop('extend_comment_email'),1);?> /> <?php echo $GLOBALS['open_str']['open_social_email_text2']?></label> <br/>
			<label for="osop[extend_show_nickname]"><input name="osop[extend_show_nickname]" id="osop[extend_show_nickname]" type="checkbox" value="1" <?php checked(osop('extend_show_nickname'),1);?> /> <?php echo $GLOBALS['open_str']['osop_extend_show_nickname']?></label> <br/>
			<label for="osop[extend_email_login]"><input name="osop[extend_email_login]" id="osop[extend_email_login]" type="checkbox" value="1" <?php checked(osop('extend_email_login'),1);?> /> <?php echo $GLOBALS['open_str']['osop_extend_email_login']?></label> <br/>
			<label for="osop[delete_setting]"><input name="osop[delete_setting]" id="osop[delete_setting]" type="checkbox" value="1" <?php checked(osop('delete_setting'),1);?> /> <?php echo $GLOBALS['open_str']['osop_delete_setting']?></label> <br/>
		</fieldset>
		</td>
		</tr>
		</table>
		<?php submit_button();?>
	</div>

	<div class="wrap">
		<h2><?php echo $GLOBALS['open_str']['setting_menu_adv']?></h2>
		<table class="form-table">
		<?php
		    $open_arr_link = array(
		        'qq'=> array('http://connect.qq.com/','http://wiki.connect.qq.com/'),
		        'sina'=> array('http://open.weibo.com/','http://open.weibo.com/wiki/'),
		        'baidu'=> array('http://developer.baidu.com/console','http://developer.baidu.com/wiki/index.php?title=docs/oauth'),
		        'google'=> array('https://cloud.google.com/console','https://developers.google.com/accounts/docs/OAuth2WebServer'),
		        'live'=> array('https://account.live.com/developers/applications','http://msdn.microsoft.com/en-us/library/live/ff621314.aspx'),
		        'douban'=> array('http://developers.douban.com/','http://developers.douban.com/wiki/?title=oauth2'),
		        'renren'=> array('http://dev.renren.com/','http://wiki.dev.renren.com/wiki/Authentication'),
		        'kaixin'=> array('http://open.kaixin001.com/','http://open.kaixin001.com/document.php'),
		        'xiaomi'=> array('http://dev.xiaomi.com/','http://dev.xiaomi.com/doc/'),
		        'csdn'=> array('http://open.csdn.net/','http://open.csdn.net/wiki'),
		        'oschina'=> array('http://www.oschina.net/openapi/','http://www.oschina.net/openapi/docs'),
		        'facebook'=> array('https://developers.facebook.com/','https://developers.facebook.com/docs/facebook-login/permissions'),
		        'twitter'=> array('https://apps.twitter.com/','https://dev.twitter.com/docs/auth/implementing-sign-twitter'),
		        'github'=> array('https://github.com/settings/applications','https://developer.github.com/v3/oauth/')
		    );
			foreach ($GLOBALS['open_arr'] as $v) {
			    $V = strtoupper($v);
                echo '<tr valign="top"><th scope="row">
                    <a href="'.(isset($open_arr_link[$v][0])?$open_arr_link[$v][0]:'').'" target="_blank">'.$GLOBALS['open_str'][$v].'</a>
                    <a href="'.(isset($open_arr_link[$v][0])?$open_arr_link[$v][1]:'').'" target="_blank">?</a> </th>
                <td><label for="osop['.$V.']"><input name="osop['.$V.']" id="osop['.$V.']" type="checkbox" value="1" '.checked(osop($V),1,false).' title="'.__('Enabled').'" />'.__('Enabled').'</label><br />
                    <input name="osop['.$V.'_AKEY]" value="'.osop($V.'_AKEY').'" class="regular-text" /> APP KEY <br/>
                    <input name="osop['.$V.'_SKEY]" value="'.osop($V.'_SKEY').'" class="regular-text" /> SECRET KEY <br/>
                    <input name="osop['.$V.'_BACK]" value="'.osop($V.'_BACK').'" class="regular-text code" placeholder="'.home_url('/').'" /> CALLBACK</td>
                </tr>';
			}
		?>
		</table>
		<?php submit_button();?>
		</form>
	</div>
	<div class="wrap">
		<h2><?php echo $GLOBALS['open_str']['about_title']?></h2>
		<p><?php echo $GLOBALS['open_str']['about_info']?>, 
		<a href="https://me.alipay.com/playes" target="_blank"><?php echo $GLOBALS['open_str']['about_alipay']?></a>, 
		<a href="http://www.xiaomac.com/" target="_blank"><?php echo $GLOBALS['open_str']['about_link']?></a>,  
		<a href="http://wordpress.org/plugins/open-social/" target="_blank"><?php echo $GLOBALS['open_str']['about_plugin']?></a> :)</p>
		<p><code>&lt;a href=&quot;http://www.xiaomac.com/&quot; target=&quot;_blank&quot;&gt;XiaoMac&lt;/a&gt;</code></p>
	</div>
	<?php
} 

//user avatar
add_filter("get_avatar", "open_get_avatar",10,4);
function open_get_avatar($avatar, $id_or_email='',$size='80',$default='') {
	global $comment;
	$comment_ip = '';
    if($id_or_email && is_object($id_or_email)){
        $id_or_email = $id_or_email->user_id;
        if(is_user_logged_in() && current_user_can('manage_options')) $comment_ip = get_comment_author_IP();
    }
	if($id_or_email) $open_type = get_user_meta($id_or_email, 'open_type', true);
	if($id_or_email && $open_type){
		$open_id = get_user_meta($id_or_email, 'open_id', true);
		if($open_type=='qq'){
			if(osop('QQ_AKEY')) $out = 'http://q.qlogo.cn/qqapp/'.osop('QQ_AKEY').'/'.$open_id.'/100';//40
		}elseif($open_type=='sina'){
			$out = 'http://tp3.sinaimg.cn/'.$open_id.'/180/1.jpg';//50
		}elseif($open_type=='douban'){
			$out = 'http://img3.douban.com/icon/ul'.$open_id.'.jpg';//u
		}elseif($open_type=='xiaomi'){
			$out = 'http://avatar.bbs.miui.com/data/avatar/'.substr((strlen($open_id)<=8?'0'.$open_id:$open_id),0,-6).'/'.substr($open_id,-6,-4).'/'.substr($open_id,-4,-2).'/'.substr($open_id,-2).'_avatar_middle.jpg';//egg broken
		}else{
			$out = get_user_meta($id_or_email, 'open_img', true);
		}
		if(isset($open_id) && isset($out)) $avatar = "<img alt='' ip='{$comment_ip}' src='{$out}' class='avatar avatar-{$size}' width='{$size}' />";
	}
	return $avatar;
}

add_filter('comment_form_defaults','open_social_comment_note');
function open_social_comment_note($fields) {
	if(is_user_logged_in()){
		$user = wp_get_current_user();
	    $open_email = get_user_meta($user->ID, 'open_email', true);
		$fields['logged_in_as'] .= '<p class="comment-form-email"><input class="disabled" id="email" name="email" title="'.__( 'Email' ).'" value="'.esc_attr( $user->user_email ).'" size="25" disabled="disabled" /> <input class="disabled" id="url" name="url" title="'.__( 'Website' ).'" value="'.esc_attr( $user->user_url ).'" size="40" disabled="disabled" /></p>';
		if(!is_numeric($open_email)){
			$fields['comment_notes_after'] = '<p><a title="'.__('Edit My Profile').'" href="'.get_edit_user_link($user->ID).'?from='.esc_url($_SERVER["REQUEST_URI"]).'%23comment">'.$GLOBALS['open_str']['open_social_edit_profile'].'</a></p><style>#'.$fields['id_submit'].'{display:none !important;}</style>';
		}else{
			$fields['comment_notes_after'] .= '<p>';
			if( osop('extend_comment_email',1) ){
			    $fields['comment_notes_after'] .= '<input class="disabled" disabled="disabled" type="checkbox" '.checked(esc_attr( $open_email ),1,false).' /> '.$GLOBALS['open_str']['open_social_email_text2'] .'.';
			}
			$fields['comment_notes_after'] .= ' <a href="'.get_edit_user_link($user->ID).'?from='.esc_url($_SERVER["REQUEST_URI"]).'%23comment">'.__('Edit My Profile').'</a></p>';
		}
	}
	return $fields;
}

//profile update
add_action('personal_options_update', 'open_social_update_options');
function open_social_update_options($user_id) {
    if( current_user_can('edit_user',$user_id) ) update_user_meta($user_id, 'open_email', isset($_POST['open_email'])?1:0);
}

//back link
if(isset($_GET['updated']) || isset($_GET['from'])) add_action('admin_notices', 'open_social_edit_profile_note');
function open_social_edit_profile_note() {
	if( isset($_GET['from']) ) $_SESSION['from'] = $_GET['from'];
	$from = isset($_SESSION['from']) ? $_SESSION['from'] : home_url();
	echo '<div class="updated fade""><p><strong><a href="'.esc_url($from).'">'.__('&laquo; Back').': '.esc_url(stripos($from,'http')===0?$from:($_SERVER["SERVER_NAME"].$from)).'</a></strong></p></div>';
}

//login with email
if(osop('extend_email_login',1)){
	add_action('wp_authenticate','open_social_email_login');
	function open_social_email_login($username) {
		if(is_email( $username )){
			$user = get_user_by_email($username);
			if(!empty($user->user_login)) $username = $user->user_login;
		}
		return $username;
	}
	add_filter( 'gettext', 'open_social_email_login_text', 20, 3 );
	function open_social_email_login_text( $translated_text, $text, $domain ) {
		if ( 'wp-login.php' == basename( $_SERVER['SCRIPT_NAME'] ) && "Username" == $text ) $translated_text = __('Username or E-mail:');
		return $translated_text;
	}
}

//login form
if( osop('show_login_page',1) ) add_action('login_form', 'open_social_login_form');
if( osop('show_login_form',1) ) add_action('comment_form_top', 'open_social_login_form');
if( osop('show_login_form',2) ) add_action('comment_form', 'open_social_login_form');
add_action('comment_form_must_log_in_after', 'open_social_login_form');
function open_social_login_form($login_type='') {
	if (!is_user_logged_in() || $login_type=='bind'){
		$html = '<div id="open_social_login" class="open_social_box login_box">';
		foreach ($GLOBALS['open_arr'] as $v){
		    if(osop(strtoupper($v))) $html .= open_login_button_show($v,str_replace('%OPEN_TYPE%',$GLOBALS['open_str'][$v],$GLOBALS['open_str']['login']),osop(strtoupper($v).'_BACK'));
		}
		$html .= '</div>';
		echo $html;
	}
} 

if( osop('show_share_content',1) ) add_filter('the_content', 'open_social_share_form');
function open_social_share_form($content) {
	if(is_single()) {
		$content .= '<div class="open_social_box share_box">';
		$last = end($GLOBALS['open_share_arr']);
        foreach ($GLOBALS['open_share_arr'] as $k=>$v) {
		    if(osop('share_'.$k) && $v!=$last) $content .= open_share_button_show($k,str_replace('%SHARE_TYPE%',$GLOBALS['open_str']['share_'.$k],$GLOBALS['open_str']['share']),$v);
        }
		$content .= '</div>';
	}
	return $content;
} 

//hide user option
add_action('admin_head','open_social_hide_option');
function open_social_hide_option( ){
	if(!is_user_logged_in()) return;
	$current_user = wp_get_current_user();
	$open_type = get_user_meta( $current_user->ID, 'open_type', true);
	if(!current_user_can('manage_options') && $open_type ){//not admin, had been bound
		echo "<script>jQuery(document).ready(function(){jQuery('table.form-table:eq(0)').hide();jQuery('#wpfooter').hide();});</script>";
	}
}

//binding
add_action('profile_personal_options', 'open_social_bind_options');
function open_social_bind_options( $user ) {
	$user_id = get_current_user_id();
	if(isset($_GET['user_id']) && $user_id!=$_GET['user_id']) return;//prevent cross-user editing
	echo '<table class="form-table"><tr valign="top"><th scope="row">'.$GLOBALS['open_str']['setting_menu'].'</th><td>';
	$open_type = get_user_meta( $user->ID, 'open_type', true);
    $open_email = get_user_meta( $user->ID, 'open_email', true);
    if( osop('extend_comment_email',1) ) echo '<p><label for="open_email"><input type="checkbox" value="1" id="open_email" name="open_email" '.checked(esc_attr( $open_email ),1,false).' />'.$GLOBALS['open_str']['open_social_email_text2'].'</label></p>';
	if ($open_type) {
		echo '<p><input class="button-primary" type="button" onclick=\'location.href="'.home_url('/').'?connect='.$open_type.'&action=unbind"\' value="'.str_replace('%OPEN_TYPE%',strtoupper($open_type),$GLOBALS['open_str']['unbind']).'"/></p>';
	} else {
		open_social_login_form('bind');
	} 
	echo '</td></tr></table>';
} 

//script & style
add_action( 'wp_enqueue_scripts', 'open_social_style' );
add_action( 'login_enqueue_scripts', 'open_social_style' );
add_action( 'admin_enqueue_scripts', 'open_social_style' );
function open_social_style() {
	wp_register_style( 'open_social_css', plugins_url('/images/os.css', __FILE__) );
	wp_enqueue_style( 'open_social_css' );
	wp_register_script( 'open_social_js', plugins_url('/images/os.js', __FILE__), array( 'jquery','jquery-ui-tooltip' ), true );
	wp_enqueue_script( 'open_social_js');
}
function open_login_button_show($icon_type,$icon_title,$icon_link){
	return "<div class=\"login_button login_icon_$icon_type\" onclick=\"login_button_click('$icon_type','$icon_link')\" title=\"$icon_title\"></div>";
}
function open_share_button_show($icon_type,$icon_title,$icon_link){
	return "<div class=\"share_button share_icon_$icon_type\" onclick=\"share_button_click('$icon_link')\" title=\"$icon_title\"></div>";
}
function open_tool_button_show($icon_type,$icon_title,$icon_link){
	return "<div class=\"share_button share_icon_$icon_type\" onclick=\"location.href='$icon_link';\" title=\"$icon_title\"></div>";
}

//shortcode
add_shortcode('os_hide', 'open_social_hide');
function open_social_hide($atts, $content=""){
	$output = '';
	if(is_user_logged_in()){
		$output .= '<span class="os_show"><p>'.trim($content).'</p></span>';
	}else{
		$output .= '<p class="os_hide">'.$GLOBALS['open_str']['open_social_hide_text'].'</p>';
	}
	return $output;
}

//email notification
if( osop('extend_comment_email',1) ) add_action('wp_insert_comment','open_social_comment_email',99,2);
function open_social_comment_email($comment_id, $comment_object) {
    if ($comment_object->comment_parent > 0) {
        $comment_parent = get_comment($comment_object->comment_parent);
		$user_id = $comment_parent->user_id;
		if(!$user_id)return;//user only
	    $open_email = get_user_meta( $user_id, 'open_email', true );
		if(!$open_email)return;//user checked only
		$email = get_userdata($user_id)->user_email;//$comment_parent->comment_author_email;
        $content = $GLOBALS['open_str']['open_social_email_hello'].' '.$comment_parent->comment_author.',<br><br>';
		$content .= $comment_object->comment_content . '<br><em>---- ';
		$content .= '<a href="'.esc_attr( $comment_object->comment_author_url ).'">'.$comment_object->comment_author . '</a>';
		$content .= '('.esc_attr( $comment_object->comment_author_email ).') # ';
		$content .= '<a href="'.get_permalink($comment_parent->comment_post_ID).'">'.get_the_title($comment_parent->comment_post_ID).'</a></em><br><br>';
		$content .= $GLOBALS['open_str']['open_social_email_text1'].': <a href="'.get_comment_link( $comment_parent->comment_ID ).'">'.get_comment_link( $comment_parent->comment_ID ).'</a>';
        $headers  = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n";
        wp_mail($email,'['.get_option('blogname').'] '.$GLOBALS['open_str']['open_social_email_title'],$content,$headers);
    }
}

//show nickname
if( osop('extend_show_nickname',1) ){
	add_filter('manage_users_columns', 'os_show_user_nickname_column');
	add_action('manage_users_custom_column', 'os_show_user_nickname_column_content', 20, 3);
	add_filter('manage_users_sortable_columns', 'os_user_sortable_columns');
	function os_show_user_nickname_column($columns) {
		//unset($columns['name']);
		$columns['nickname'] = __('Nickname');
		$columns['registered'] = __('Registered');
		return $columns;
	}
	function os_show_user_nickname_column_content($value, $column_name, $user_id) {
		$user = get_userdata( $user_id );
		if ( 'nickname' == $column_name ) return $user->nickname;
		if ( 'registered' == $column_name ) return $user->user_registered;
		return $value;
	}
	function os_user_sortable_columns( $columns ) {
		$columns['nickname'] = 'name';
		$columns['registered'] = 'registered';
	    return $columns;
	}
}

//widget
add_action('widgets_init', create_function('', 'return register_widget("open_social_login_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("open_social_share_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("open_social_float_widget");'));

class open_social_login_widget extends WP_Widget {
    function open_social_login_widget() {
        parent::WP_Widget(false, $name = $GLOBALS['open_str']['widget_title'], array( 'description' => $GLOBALS['open_str']['widget_desc'], ) );
    }
	function form($instance) {
		if($instance) {
			$title = $instance['title'];
			foreach ($GLOBALS['open_arr'] as $v){ $$v = isset($instance[$v]) ? esc_attr($instance[$v]) : 0; }
		} else {
			$title = '';
			foreach ($GLOBALS['open_arr'] as $v){ $$v = 1; }
		}
		$html = '<p><label for="'.$this->get_field_id( 'title' ).'">'.__( 'Title:' ).'</label><input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.esc_attr( $title ).'" /></p>';
		$html .= '<p>';
		foreach ($GLOBALS['open_arr'] as $k=>$v) {
		    if(osop(strtoupper($v))) $html .= '<label for="'.$this->get_field_id($v).'"><input id="'.$this->get_field_id($v).'" name="'.$this->get_field_name($v).'" type="checkbox" value="1" '.checked( '1', $$v, false).' />'.$GLOBALS['open_str'][$v].'</label>  ';
		    if(($k+1)%3==0) $html .= '<p>';
		}
		$html .= '</p>';
		echo $html;
	}
	function update($new_instance, $old_instance) {
        $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		foreach ($GLOBALS['open_arr'] as $k=>$v) { $instance[$v] = strip_tags($new_instance[$v]); }
        return $instance;
	}
	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if(!$title) $title = $GLOBALS['open_str']['widget_name'];
		foreach ($GLOBALS['open_arr'] as $v) { $$v = isset($instance[$v]) ? $instance[$v] : 0; }
		$html = $before_widget;
		if ( $title ) $html .= '<h3 class="widget-title">'.$title.'</h3>';
		$html .= '<div class="textwidget"><div class="open_social_box">';
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$email = $current_user->user_email;
			$html .= '<a href="'.(current_user_can('manage_options')?admin_url():(get_edit_user_link($current_user->ID)).'?from='.esc_url($_SERVER["REQUEST_URI"])).'">'.get_avatar($current_user->ID).'</a><br/>';
			$html .= '<a href="'.$current_user->user_url.'" target="_blank" title="'.__( 'Website' ).'">'.$current_user->display_name.'</a>';
			$html .= ' (<a href="'.get_edit_user_link($current_user->ID).'?from='.esc_url($_SERVER["REQUEST_URI"]).'" title="'.__('Edit My Profile').'">'.$email.'</a>)';
			$html .= ' (<a href="'.wp_logout_url($_SERVER['REQUEST_URI']).'">'.__('Log Out').'</a>)';
		}else{
		    foreach ($GLOBALS['open_arr'] as $v) {
			    if(osop(strtoupper($v)) && $$v) $html .= open_login_button_show($v,str_replace('%OPEN_TYPE%',$GLOBALS['open_str'][$v],$GLOBALS['open_str']['login']),osop(strtoupper($v).'_BACK'));
			}
		}
		$html .= '</div></div>';
		$html .= $after_widget;
		echo $html;
	}
}

class open_social_share_widget extends WP_Widget {
    function open_social_share_widget() {
        parent::WP_Widget(false, $name = $GLOBALS['open_str']['widget_share_title'], array( 'description' => $GLOBALS['open_str']['widget_share_desc'], ) );
    }
	function form($instance) {
		if($instance) {
			$title = $instance['title'];
			foreach ($GLOBALS['open_share_arr'] as $k=>$v) { $$k = isset($instance[$k]) ? esc_attr($instance[$k]) : 0; }
		} else {
			$title = '';
			foreach ($GLOBALS['open_share_arr'] as $k=>$v) { $$k = 1; }
		}
		$html = '<p><label for="'.$this->get_field_id( 'title' ).'">'.__( 'Title:' ).'</label><input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.esc_attr( $title ).'" /></p>';
		$html .= '<p>';
		$i = 0;
        foreach ($GLOBALS['open_share_arr'] as $k=>$v) {
    		if(osop('share_'.$k)) $html .= '<label for="'.$this->get_field_id($k).'"><input id="'.$this->get_field_id($k).'" name="'.$this->get_field_name($k).'" type="checkbox" value="1" '.checked( '1', $$k, false).' />'.$GLOBALS['open_str']['share_'.$k].'</label> ';
		    if(($i+1)%3==0) $html .= '<p>';
    		$i++;
        }
		$html .= '</p>';
		echo $html;
	}
	function update($new_instance, $old_instance) {
        $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        foreach ($GLOBALS['open_share_arr'] as $k=>$v) { $instance[$k] = strip_tags($new_instance[$k]); }
        return $instance;
	}
	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if(!$title) $title = $GLOBALS['open_str']['widget_share_name'];
        foreach ($GLOBALS['open_share_arr'] as $k=>$v) { $$k = $instance[$k]; }
		$html = $before_widget;
		if ( $title ) $html .= '<h3 class="widget-title">'.$title.'</h3>';
		$html .= '<div class="textwidget">';
		$html .= '<div id="open_social_share_box" class="open_social_box">';
		$last = end($GLOBALS['open_share_arr']);
        foreach ($GLOBALS['open_share_arr'] as $k=>$v) {
            if($v!=$last){
		        if($$k && osop('share_'.$k)) $html .= open_share_button_show($k,str_replace('%SHARE_TYPE%',$GLOBALS['open_str']['share_'.$k],$GLOBALS['open_str']['share']),$v);
            }else{
		        if($$k && osop('share_'.$k)) $html .= open_tool_button_show($k.'_'.(get_locale()!='en_US'?'en_US':'zh_CN'),$GLOBALS['open_str']['share_'.$k],$v);
            }
		}
		$html .= '</div>';
		$html .= '</div>';
		$html .= $after_widget;
		echo $html;
	}
}	

class open_social_float_widget extends WP_Widget {
    function open_social_float_widget() {
        parent::WP_Widget(false, $name = $GLOBALS['open_str']['widget_float_title'], array( 'description' => $GLOBALS['open_str']['widget_float_desc'], ) );
    }
	function widget($args, $instance) {
		$html = '<div id="open_social_float_button">';
		$html .= "<div class='os_float_button float_icon_top' id='open_social_float_button_top'></div>";
		$html .= "<div class='os_float_button float_icon_comment' id='open_social_float_button_comment'></div>";
		$html .= '</div>';
		echo $html;
	}
}
?>