<?php
/**
 * Plugin Name: WP Open Social
 * Version: 5.0
 * Plugin URI: https://www.xiaomac.com/wp-open-social.html
 * Description: 使用 QQ、微信、微博、谷歌、推特等热门社交平台实现一键登录和分享。模块化结构，按需扩展，代码开源。Login and Share with social networks: QQ, WeiBo, Weixin, WeChat, Google, Twitter, Facebook...
 * Author: Link (XiaoMac.com)
 * Author URI: https://www.xiaomac.com/
 * Text Domain: open-social
 * License: MIT License
 * Domain Path: /lang
 * Network: true
 */

/**
 * Copyright 2019 Link (playes@qq.com) @WP Open Social
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * 本软件以 MIT 许可协议发布，简言：你可以自由使用或各种改造再发行，但在软件中必须包含以上许可声明；由软件引起的损失或意外本人概不负责。
 */

if(!session_id()) session_start();
open_social_include_module();
add_action('init', 'open_social_init', 1);
function open_social_init(){
    do_action('open_social_init_action');
    $GLOBALS['osop'] = get_site_option('osop');
    $site = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $osop_login = apply_filters('open_social_login_filter', array(
        'qq'=>array(__('QQ','open-social'), '//connect.qq.com/'),
        'sina'=>array(__('Weibo','open-social'), '//open.weibo.com/'),
        'baidu'=>array(__('Baidu','open-social'), 'http://developer.baidu.com/console#app/project'),
        'wechat'=>array(__('WeChat Scan','open-social'), '//open.weixin.qq.com/'),
        'google'=>array(__('Google','open-social'), '//console.developers.google.com/'),
        'live'=>array(__('Microsoft','open-social'), '//apps.dev.microsoft.com/'),
        'facebook'=>array(__('Facebook','open-social'), '//developers.facebook.com/'),
        'twitter'=>array(__('Twitter','open-social'), '//apps.twitter.com/'),

    ));
    if($order = wpos_ops('osop_login_order')){
        $order = preg_split('/[ ,]+/', $order, null, PREG_SPLIT_NO_EMPTY);
        if(!empty($order)) $osop_login = array_merge(array_flip($order), $osop_login);
    }
    $GLOBALS['open_login_arr'] = $osop_login;
    $osop_share = apply_filters('open_social_share_filter', array(
        'qq'=>array(__('QQ','open-social'), '//connect.qq.com/widget/shareqq/index.html?url=%URL%&title=%TITLE%&summary=%SUMMARY%&pics=%PICS%'),
        'weibo'=>array(__('Weibo','open-social'), 'http://service.weibo.com/share/share.php?url=%URL%&title=%TITLE%&pic=%PICS%&appkey='.wpos_ops('SINA_AKEY').'&ralateUid='.wpos_ops('share_sina_user').'&language=zh_cn&searchPic=true'),
        'wechat'=>array(__('WeChat','open-social'), ''),
        'qqzone'=>array(__('QQZone','open-social'), '//sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=%URL%&title=%TITLE%&desc=&summary=%SUMMARY%&site='.$site.'&pics=%PICS%'),
        'linkedin'=>array(__('Linkedin','open-social'), '//www.linkedin.com/shareArticle?mini=true&url=%URL%&title=%TITLE%&summary=%SUMMARY%&source='.$site),
        'facebook'=>array(__('Facebook','open-social'), '//www.facebook.com/sharer.php?u=%URL%&amp;t=%TITLE%'),
        'twitter'=>array(__('Twitter','open-social'), '//twitter.com/home/?status=%TITLE%:%URL%'),
        'google'=>array(__('Google','open-social'), '//www.google.com/bookmarks/mark?op=edit&bkmk=%URL%&title=%TITLE%&annotation=%SUMMARY%'),
        'reddit'=>array(__('Reddit','open-social'), '//www.reddit.com/submit?url=%URL%&amp;title=%TITLE%'),
        'pinterest'=>array(__('Pinterest','open-social'), '//pinterest.com/pin/create/button/?url=%URL%&amp;media=%TITLE%&amp;description=%SUMMARY%'),
    ), $site);
    if($order = wpos_ops('osop_share_order')){
        $order = preg_split('/[ ,]+/', $order, null, PREG_SPLIT_NO_EMPTY);
        if(!empty($order)) $osop_share = array_merge(array_flip($order), $osop_share);
    }
    $GLOBALS['open_share_arr'] = $osop_share;
    define('OPEN_CBURL', wpos_ops('extend_callback_url') ? wpos_ops('extend_callback_url') : home_url());
    if(isset($_GET['error_description'], $_SESSION['open_login_state'])) open_social_check($_GET, 'login', 'code');
    if(isset($_GET['auth_code'], $_SESSION['open_login_state'])) $_GET['code'] = $_GET['auth_code'];
    if(isset($_GET['connect']) || (isset($_GET['code'], $_GET['state'], $_SESSION['state'], $_SESSION['open_login_state']))){
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if(!isset($_GET['connect'])){
            foreach ($GLOBALS['open_login_arr'] as $k => $v){
                if(!is_array($v)) continue;
                if(wpos_ops(strtoupper($k)) && $_GET['state'] == md5($k.$_SESSION['state'])){
                    $action = 'callback';
                    define('OPEN_TYPE', $k);
                    break;
                }
            }
        }else{
            if($action == 'login' && !isset($_GET['back']) && isset($_SERVER['HTTP_REFERER'])){
                $_GET['back'] = $_SERVER['HTTP_REFERER'];
            }
            if(in_array($_GET['connect'], array_keys($GLOBALS['open_login_arr'])) && wpos_ops(strtoupper($_GET['connect']))){
                define('OPEN_TYPE', $_GET['connect']);
            }
            do_action('open_social_connect_action', $action, $_GET);
        }
        if(!defined('OPEN_TYPE') || !$action) return;
        if(!isset($_SESSION['back'])) $_SESSION['back'] = !empty($_GET['back']) ? $_GET['back'] : home_url('/');
        $open_class = 'WPOS_'.strtoupper(OPEN_TYPE).'_CLASS';
        if(!class_exists($open_class)) open_social_next(OPEN_CBURL);
        $wpos = new $open_class;
        if($action == 'login' || $action == 'callback') $action_info = array(
            'akey' => wpos_ops(strtoupper(OPEN_TYPE).'_AKEY'),
            'skey' => wpos_ops(strtoupper(OPEN_TYPE).'_SKEY'),
            'cburl' => wpos_ops('more_login_cburl') && wpos_ops(strtoupper(OPEN_TYPE).'_CBURL') ? wpos_ops(strtoupper(OPEN_TYPE).'_CBURL') : OPEN_CBURL
        );
        if(is_multisite() && !empty($_GET['site'])){
            if(($site_id = intval($_GET['site'])) && ($site_url = get_site_url($site_id))){
                $_SESSION['site_id'] = $site_id;
            }
        }
        if($action == 'login'){
            if(!isset($_POST['state'], $_SESSION['state'], $_SESSION['open_login_state'])){
                $_SESSION['state'] = $_SESSION['open_login_state'] = uniqid(rand(), true);
            }
            do_action('open_social_server_before_action', OPEN_TYPE);
            $wpos->open_login(md5(OPEN_TYPE.$_SESSION['state']), $action_info);
        }else if($action == 'callback'){
            do_action('open_social_server_after_action', OPEN_TYPE);
            if(!isset($_GET['code']) || isset($_GET['error']) || isset($_GET['error_code']) || isset($_GET['error_description'])) open_social_next(OPEN_CBURL);
            $wpos->open_callback($_GET['code'], $action_info);
            $wpos_user = $wpos->open_new_user($action_info);
            do_action('open_social_callback_action', $_SESSION, $wpos_user);
            open_social_action($wpos_user);
        }else if($action == 'bind'){
            open_social_action($_POST);
        }else if($action == 'unbind'){
            open_social_action_unbind();
        }else if($action == 'activate'){
            do_action('open_social_activate_action', $_GET);
        }
    }else{
        do_action('open_social_hook_action');
        if(!is_user_logged_in()){
            if(wpos_ops('extend_must_login') && !open_social_login_page()) open_social_next(wp_login_url($_SERVER['REQUEST_URI']));
            if(!open_social_login_page()) open_social_unsession();//clear
        }else{
            open_social_unsession('back, state, open_login_state, login_back_state');//clear
            if(open_social_login_page() && !count($_GET)) open_social_next(home_url());//no point
        }
    }
}

function open_social_include_module(){
    $GLOBALS['wposmods'] = array('compatible', 'socialmedia', 'registration', 'wechat', 'weibo', 'sms', 'proxied');
    foreach($GLOBALS['wposmods'] as $m){ if(file_exists(__DIR__."/mod/$m.php")) require_once(__DIR__."/mod/$m.php"); }
}

add_action('init', 'open_social_init_later', 10);
function open_social_init_later(){
    if(!empty($_SERVER['HTTP_REFERER']) && empty($_SESSION['HTTP_REFERER'])){
        $_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
    }
    if(wpos_ops('extend_ignore_reset') && has_action('after_password_reset', 'wp_password_change_notification')){
        remove_action('after_password_reset', 'wp_password_change_notification');
    }
    do_action('open_social_init_later_action');
}

add_action('wp_loaded', 'open_social_login_init', 1);
function open_social_login_init(){
    if(is_user_logged_in() || !wpos_ops('extend_hide_login') || wpos_ops('extend_must_login') || defined('DOING_AJAX')) return;
    if(is_admin() || (open_social_login_page() && empty($_GET['action']) && empty($_GET['loggedout']) && !isset($_POST) && !isset($_SESSION['open_id'], $_SESSION['access_token']))){
        open_social_next(home_url(), 404);
    }
}

add_action('after_setup_theme', 'open_social_after_setup_theme');
function open_social_after_setup_theme(){
    load_plugin_textdomain('open-social', false, 'open-social/lang');
}

function wpos_ops($k, $v=null){
    if(!isset($GLOBALS['osop'])) $GLOBALS['osop'] = get_site_option('osop');
    return isset($GLOBALS['osop'][$k]) ? (isset($v) ? $GLOBALS['osop'][$k] == $v : $GLOBALS['osop'][$k]) : '';
}

function open_social_in($str, $find){
    return stripos($str, $find) !== false;
}

function open_social_unsession($str=''){
    if(empty($str)) $str = 'open_id, access_token, nickname, open_img, unionid';//base
    if(!$arr = preg_split('/[ ,;]+/', trim($str), null, PREG_SPLIT_NO_EMPTY)) return;
    foreach($arr as $v){ if(isset($_SESSION[$v])) unset($_SESSION[$v]); }
}

function open_social_login_page($url=null){
    return stripos(!empty($url) ? $url : home_url($_SERVER['REQUEST_URI']), wp_login_url()) === 0;
}

function open_social_back($qa=array(), $url='', $encode=true){
    if($encode) $qa = array_map('urlencode', $qa);
    if(empty($url)) $url = home_url($_SERVER['REQUEST_URI']);
    return add_query_arg($qa, $url);
}

function open_social_next($msg, $status=302){
    wp_redirect($msg, $status);
    exit();
}

function open_social_text($msg, $title=''){
    if(empty($title)) $title = __('OOPS!', 'open-social');
    $site = get_bloginfo('name', 'display').(is_rtl() ? ' &rsaquo; ' : ' &lsaquo; ').__('Log In', 'open-social');
    $link = is_user_logged_in() ? get_edit_profile_url() : home_url();
    if(!open_social_in($msg, 'button')) $msg .= open_social_link($link, __('Back', 'open-social'), 'p,button');
    $style = '<style>pre{white-space: pre-wrap;} p{word-break: break-all;}</style>';
    open_social_unsession('back, state, open_login_state, login_back_state');//clear
    wp_die("{$style}<h1>{$title}</h1><p>{$msg}</p>", $site, array('response'=>200, 'back_link'=>false));
}

function open_social_http($url, $args=array(), $text=false){
    $url = apply_filters('open_social_http_url_filter', $url);//for reverse proxy
    $args['timeout'] = 30;
    $args['sslverify'] = false;
    $args['httpversion'] = '1.1';
    $args['user-agent'] = 'WP Open Social (xiaomac.com)';
    $headers = isset($args['headers']) ? $args['headers'] : array();
    if(!$headers && isset($_SESSION['access_token'])){
        $headers['Authorization'] = 'Bearer '.$_SESSION['access_token'];
    }
    $headers['Expect'] = '';
    $args['headers'] = $headers;
    $response = wp_remote_request($url, $args);
    if(is_wp_error($response)){
        $res = '<p>URL: '.$url.'</p><p>HOST: '.$_SERVER['HTTP_HOST'].'</p><p>'.$response->get_error_message().'</p>';
        if($text) return $res;
        open_social_text($res);
    }
    $res = trim($response['body']);
    if($text) return $res;
    $res = trim($res, '&&&START&&&');
    $json_r = array();
    $json_r = json_decode($res, true);
    if(!is_array($json_r) || count($json_r)==0){
        parse_str($res, $json_r);
        if(count($json_r)==1 && current($json_r)==='') return $res;
    }
    return $json_r;
}

function open_social_check($arr, $in, $out){
    $err = "";
    if(!is_array($arr)){
        $err .= "<h3>ERROR:</h3><p>{$arr}</p>";
    }else if(isset($arr['error']) || isset($arr['error_code'])){
        if(isset($arr['error'])) $err .= "<h3>ERROR:</h3><p>".is_array($arr['error']) ? serialize($arr['error']) : $arr['error']."</p>";
        if(isset($arr['error_code'])) $err .= "<h3>CODE :</h3><p>{$arr['error_code']}</p>";
        if(isset($arr['error_description'])) $err .= "<h3>MSG  :</h3><p>{$arr['error_description']}</p>";
    }else if(!isset($arr[$out])){
        $err .= "<h3>ERROR:</h3><p>{$in} => {$out}</p>";
    }
    if($err){
        if(defined('OPEN_TYPE')) $err = "<h3>LOGIN:</h3><p>".OPEN_TYPE."</p>{$err}";
        $err .= "<h3>RETURN:</h3><pre>".open_social_var_dump($arr)."</pre>";
        open_social_text($err);
    }
}

//ADMIN

add_action('admin_init', 'open_social_admin_init');
function open_social_admin_init(){
    do_action('open_social_admin_init_action');
    register_setting('open_social_options_group', 'osop');
    do_action('open_social_admin_after_action');
}

function open_social_data($key){
    $data = get_plugin_data(__FILE__);
    return isset($data) && is_array($data) && isset($data[$key]) ? $data[$key] : '';
}

add_action('admin_menu','open_social_admin_menu');
function open_social_admin_menu(){
    if(is_multisite() && (!is_main_site() || !is_super_admin())) return;
    $name = __('WP Open Social','open-social');
    add_options_page($name, $name, 'manage_options', 'open-social', 'open_social_options_page');
}

function open_social_link($url, $text='', $ext=''){
    if(empty($text)) $text = $url;
    $button = open_social_in($ext, 'button') ? " class='button'" : "";
    $target = open_social_in($ext, 'blank') ? " target='_blank'" : "";
    $link = "<a href='{$url}'{$button}{$target}>{$text}</a>";
    return open_social_in($ext, 'p') ? "<p>{$link}</p>" : "{$link}";
}

add_filter('plugin_action_links_open-social/open-social.php', 'open_social_settings_link');
function open_social_settings_link($links) {
    if(is_multisite() && (!is_main_site() || !is_super_admin())) return $links;
    $link = open_social_link('options-general.php?page=open-social', __('Settings', 'open-social'));
    return array_merge(array($link), $links);
}

add_filter('manage_settings_page_open-social_columns', 'open_social_setting_columns');
function open_social_setting_columns($cols) {
    $cols['_title'] = __('For Less','open-social');
    foreach ($GLOBALS['open_login_arr'] as $k=>$v) {
        if(!is_array($v)) continue;
        $cols['open_social_login_'.$k] = $v[0];
    }
    return $cols;
}

function open_social_show_more($cols, $ret=false){
    static $header = array();
    $arr  = get_user_option('managesettings_page_open-socialcolumnshidden');
    $hide = (is_array($arr) && in_array($cols, $arr)) ? ' hidden' : '';
    $head = in_array($cols, $header) ? " class='{$cols}" : " id='{$cols}' class='manage-column";
    $out = "{$head} column-{$cols}{$hide}'";
    if(!in_array($cols, $header)) $header[] = $cols;
    if($ret) return $out;
    echo $out;
}

add_action('update_option_osop', 'open_social_update_site_options', 10, 3);
function open_social_update_site_options($old, $value, $option){
    if(is_multisite() && (!is_main_site() || !is_super_admin())) return;
    update_site_option($option, $value);
}

function open_social_update_option($kv){
    if(!isset($GLOBALS['osop'])) $GLOBALS['osop'] = get_site_option('osop');
    if($kv) foreach($kv as $k => $v){ $GLOBALS['osop'][$k] = $v; }
    update_option('osop', $GLOBALS['osop']);
}

function open_social_var_dump($mixed=null) {
    ob_start();
    var_dump($mixed);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_action('wp_login', 'open_social_wp_login', 10, 2);
function open_social_wp_login($user_login, $user) {
    $_SESSION['osbindwith'] = !empty($_POST['osbindwith']);
}

function open_social_is_bind($type, $id){
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='%s' AND meta_value='%s'", 'open_type_'.$type, $id));
}

function open_social_action($newuser){
    $newer = 0;
    $back = !isset($_SESSION['back']) || open_social_login_page($_SESSION['back']) ? home_url() : $_SESSION['back'];
    if(empty($_SESSION['open_id']) || empty($_SESSION['access_token']) || !defined('OPEN_TYPE')) open_social_next($back);
    if(isset($newuser['nickname'])) $_SESSION['nickname'] = $newuser['nickname'];
    $avatar = wpos_ops('extend_default_avatar') ? wpos_ops('extend_default_avatar') : plugins_url('res/gravatar.png',__FILE__);
    if(empty($_SESSION['open_img'])) $_SESSION['open_img'] = $avatar;
    $_SESSION['open_img'] = substr($_SESSION['open_img'], stripos($_SESSION['open_img'], '//'));
    if(is_user_logged_in()){ //bind
        if(isset($_GET['action']) && $_GET['action'] == 'bind' && empty($_SESSION['osbindwith'])){
            open_social_next($back);
        }
        $wpuid = get_current_user_id();
        $wpuid_type = get_user_meta($wpuid, 'open_type', true);
        $wpuid_open = open_social_is_bind(OPEN_TYPE, $_SESSION['open_id']);
        if(!open_social_in($wpuid_type, OPEN_TYPE.',')){//bound before, login directly
            if(!$wpuid_open && isset($_SESSION['unionid'])){
                $wpuid_open = open_social_is_bind('wechat_unionid',$_SESSION['unionid']);
            }
            if(!empty($wpuid_open) && $wpuid != $wpuid_open){
                open_social_unsession();
                open_social_text(__('This login has been bound by other user already, please unbind then try again.','open-social'));
            }
        }else{
            $wpuid_old = get_user_meta($wpuid, OPEN_TYPE, true);
            if(isset($wpuid_old) && !empty($wpuid_old) && $wpuid_old != $_SESSION['open_id']){
                open_social_unsession();
                open_social_text(__('This account has been bound with other login already, please unbind then try again.','open-social'));
            }
        }
    }else{ //login
        $wpuid = open_social_is_bind(OPEN_TYPE,$_SESSION['open_id']);
        if(!$wpuid){
            if(isset($_SESSION['unionid'])) $wpuid = open_social_is_bind('wechat_unionid', $_SESSION['unionid']);
            if(!$wpuid) $wpuid = username_exists(strtoupper(OPEN_TYPE).$_SESSION['open_id']);
            if(!$wpuid){
                $url = open_social_back(array('connect'=>OPEN_TYPE, 'action'=>'bind'), OPEN_CBURL);
                $login_url = open_social_back(array('connect'=>OPEN_TYPE), wp_login_url($url));
                do_action('open_social_cannot_register_action', OPEN_TYPE, $newuser, $login_url, $_SESSION);
                $prename = 'osu';
                $extname = rand(100000,999998);
                if(!empty($newuser['user_login'])){
                    $check_str = '/admin|root|guest|user|author|test|abcd|aaaa/';
                    if(mb_strlen($newuser['user_login'])<3 || preg_match($check_str, strtolower($newuser['user_login'])) || username_exists($newuser['user_login'])){
                        $prename = $newuser['user_login'];
                        $extname = rand(1000,9998);
                        $newuser['user_login'] = '';
                    }
                }
                if(empty($newuser['user_login']) || username_exists($newuser['user_login'])){
                    while(username_exists($prename.$extname)){ $extname++; }
                    $newuser['user_login'] = $prename.$extname;
                }
                if(!isset($newuser['user_email'])) $newuser['user_email'] = '';
                $newuser['user_email'] = sanitize_email($newuser['user_email']);
                if(empty($newuser['user_email'])){//random email
                    while(email_exists($prename.$extname.'@fake.com')){ $extname++; }
                    $newuser['user_email'] = $prename.$extname.'@fake.com';
                }
                $newuser = apply_filters('open_social_newuser_form_filter', $newuser);
                $newuser['first_name'] = $newuser['nickname'];
                $newuser['display_name'] = $newuser['nickname'];
                $newuser['user_pass'] = wp_generate_password();
                if(!function_exists('wp_insert_user')) include_once(ABSPATH.WPINC.'/registration.php');
                $wpuid = wp_insert_user($newuser);
                if(!$wpuid) open_social_text(__('This account may contain some incompatible characters.','open-social'));
                $newer = 1;
                update_user_meta($wpuid, 'open_user', 1);//mark as plugin register
                $_SESSION['os_bp_new_user_activated'] = $wpuid;//deal with it later after bp_load
                if(OPEN_TYPE == 'sms' || wpos_ops('extend_profile_path')){
                	wp_update_user(array('ID' => $wpuid, 'user_nicename' => $wpuid));
                }
                if(is_multisite()){
                    if(!isset($_SESSION['site_id'])) $_SESSION['site_id'] = get_current_blog_id();
                    update_user_meta($wpuid, 'primary_blog', $_SESSION['site_id']);
                    $site_url = get_site_url($_SESSION['site_id']);
                    update_user_meta($wpuid, 'source_domain', parse_url($site_url, PHP_URL_HOST));
                }
            }
        } 
    } 
    if($wpuid){
        $open_type_list = get_user_meta($wpuid, 'open_type', true);
        if($open_type_list) $open_type_list = trim($open_type_list,',').',';
        if(!empty($_SESSION['HTTP_REFERER'])){
            update_user_meta($wpuid, 'open_social_from', esc_url($_SESSION['HTTP_REFERER']));
        }
        if(!open_social_in($_SESSION['open_img'], '/res/gravatar.png') || wpos_ops('extend_default_avatar')){
            update_user_meta($wpuid, 'open_img', esc_url($_SESSION['open_img']));
        }
        if(!open_social_in($open_type_list, OPEN_TYPE.',')){
            update_user_meta($wpuid, 'open_type', $open_type_list.OPEN_TYPE.',');
        }
        update_user_meta($wpuid, 'open_type_'.OPEN_TYPE, $_SESSION['open_id']);
        update_user_meta($wpuid, 'open_access_token', $_SESSION['access_token']);
        if(OPEN_TYPE == 'sms') open_social_update_cellphone($wpuid, $newuser['cellphone']);
        if(is_multisite() && $_SESSION['site_id'] != get_current_blog_id()){
            if(!is_user_member_of_blog($wpuid, $_SESSION['site_id'])){
                $role = get_blog_option($_SESSION['site_id'], 'default_role', 'subscriber');
                if($role) add_user_to_blog($_SESSION['site_id'], $wpuid, $role);
            }
        }
        do_action('open_social_login_before_action', OPEN_TYPE, $wpuid, $_SESSION, $newer);
        wp_set_auth_cookie($wpuid, true, false);
        wp_set_auth_cookie($wpuid, true, true);
        wp_set_current_user($wpuid);
    }
    open_social_unsession();
    open_social_unsession('site_id, back, osbindwith, state, open_login_state');
    open_social_next(wpos_ops('extend_goto_url') ? wpos_ops('extend_goto_url') : $back);
}

function open_social_action_unbind(){
    if(!is_user_logged_in()) return;
    $user = get_current_user_id();
    $user_email = get_userdata($user)->user_email;
    $open_type = get_user_meta($user, 'open_type', true);
    if(OPEN_TYPE == trim($open_type,',') && open_social_in($user_email, '@fake.com')){
        if(!isset($_POST['confirm'])){
            $html = '<form method="post" action="'.open_social_back(array('connect'=>OPEN_TYPE, 'action'=>'unbind'), OPEN_CBURL).'"><p>';
            $html .= '<p>'.__('Warning: Unbind the only social login will cause deletion of a user along with fake email. If this account have published some posts or you just want to keep it, please renew a valid email that can reset password to login with, then try again.','open-social').'</p><br/>';
            $html .= '<p>' . open_social_link(get_edit_profile_url(), __('Back'), 'button');
            $html .= ' <input class="button" name="confirm" type="submit" value="'.__('Delete Users').'">';
            $html .= '</form>';
            open_social_text($html, sprintf(__('Unbind with %s','open-social'), $GLOBALS['open_login_arr'][OPEN_TYPE][0]));
        }else{
            if(count_user_posts($user)>0) open_social_text(__('This account has published some posts so that it cannot be deleted.','open-social'));
            if(!function_exists('wp_delete_user')) include_once(ABSPATH.'wp-admin/includes/user.php');
            wp_delete_user($user);
            open_social_next(home_url());
        }
    }
    if(open_social_in($open_type, OPEN_TYPE.',')){
        $open_type = str_replace(OPEN_TYPE.',', '', rtrim($open_type, ',').',');
        update_user_meta($user, 'open_type', $open_type);
        update_user_meta($user, 'open_img', '');
        delete_user_meta($user, 'open_type_'.OPEN_TYPE);
        if(OPEN_TYPE == 'sms') delete_user_meta($user, 'cellphone');
        if(open_social_in(OPEN_TYPE, 'wechat') && !open_social_in($open_type, 'wechat')){
            delete_user_meta($user, 'open_type_wechat_unionid');
        }
    }
    open_social_next(isset($_SESSION['back']) ? $_SESSION['back'] : home_url());
}

add_action('current_screen', 'open_social_setting_screen');
function open_social_setting_screen(){
    $screen = get_current_screen();
    if($screen->id != 'settings_page_open-social') return;
    $help = '<style>
        .metabox-prefs>label {width: 150px;}
        #wpbody-content .dashicons{font-size: 18px;vertical-align: sub;}
        .ui-sortable th span { cursor: move; } 
        .ui-sortable-helper { opacity: 0.6; border: 2px dashed #efefef; margin-left: 20px; margin-top: 15px; background: #fff; zoom: 0.9; }
        .ui-sortable-helper th { padding: 10px; } 
        .ui-sortable-placeholder { border: 2px dashed #b4b9be; margin-bottom: 20px; background: #fff; }
        .help-tab-content .button{margin-right: 5px}
    </style>';
    $help .= '<p>'.open_social_data('Description').'</p><br/><p>'.
        open_social_link(self_admin_url('update-core.php?force-check=1'), '<span class="dashicons dashicons-update"></span>'.__('Check Update', 'open-social'), 'button').
        open_social_link('javascript:reset_order()', '<span class="dashicons dashicons-editor-insertmore"></span>'.__('Reset Order', 'open-social'), 'button').
        open_social_link('//wordpress.org/plugins/open-social/', '<span class="dashicons dashicons-star-filled"></span>'.__('Rating Stars', 'open-social'), 'button,blank').
        open_social_link(open_social_data('PluginURI'), __('Support & Help', 'open-social'), 'button,blank').
        open_social_link('//www.xiaomac.com/about', __('About Developer', 'open-social'), 'button,blank').
        open_social_link('//www.xiaomac.com/tag/work', __('More Plugins', 'open-social'), 'button,blank').'</p>';
    $screen->add_help_tab(array('id'=>'open_social_help', 'title'=>__('For More', 'open-social'), 'content'=>$help));
    do_action('open_social_screen_help_filter', $screen);
}

function open_social_options_page(){
    do_action('open_social_options_page_action');?>
    <div class="wrap" id="wpos_setting">
        <h1><?php _e('WP Open Social','open-social');?> 
            <a class="page-title-action" href="<?php echo esc_url(network_admin_url('plugin-install.php?tab=plugin-information&plugin=open-social&check=open-social')); ?>" target="_blank"><?php echo open_social_data('Version');?></a>
        </h1>
        <form action="options.php" method="post">
        <?php settings_fields('open_social_options_group'); ?>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="javascript:void(0);"><span class="dashicons dashicons-feedback"></span> <?php _e('General','open-social'); ?></a>
            <a class="nav-tab" href="javascript:void(0);"><span class="dashicons dashicons-art"></span> <?php _e('Customization','open-social'); ?></a>
            <a class="nav-tab" href="javascript:void(0);"><span class="dashicons dashicons-screenoptions"></span> <?php _e('Modules','open-social'); ?></a>
            <a class="nav-tab" href="javascript:void(0);"><span class="dashicons dashicons-share-alt"></span> <?php _e('Login Account','open-social'); ?>
            <span id="tab_login_order" style="display:none;color:#ca4a1f;vertical-align:top;font-size:75%;">&#9679;</span></a>
            <span class="nav-tab" style="margin-left:-1px;cursor:pointer;font-family: Courier New;padding: 5px 8px;">+</span>
            <a class="nav-tab" href="javascript:void(0);"><span class="dashicons dashicons-share"></span> <?php _e('Share Service','open-social'); ?>
            <span id="tab_share_order" style="display:none;color:#ca4a1f;vertical-align:top;font-size:75%;">&#9679;</span></a>
            <a class="nav-tab" href="javascript:void(0);"><span class="dashicons dashicons-welcome-view-site"></span> <?php _e('Preview','open-social'); ?></a>
        </h2>
        <table class="form-table">
        <?php do_action('open_social_tabone_start_action'); ?>
        <tr valign="top"><th><?php _e('Profile','open-social'); ?></th>
        <td><fieldset>
            <label><input name="osop[extend_change_nickname]" type="checkbox" value="1" <?php checked(wpos_ops('extend_change_nickname'),1);?> /> <?php _e('New user can change nickname','open-social'); ?></label><br/>
            <label><input name="osop[extend_must_website]" type="checkbox" value="1" <?php checked(wpos_ops('extend_must_website'),1);?> /> <?php _e('New user can choose website (for exchanging visits)','open-social'); ?></label><br/>
            <label><input name="osop[extend_avatar_cdn]" type="checkbox" value="1" <?php checked(wpos_ops('extend_avatar_cdn'),1);?> /> <?php _e('Speed up Gravatar through cdn.V2EX.com','open-social'); ?> <?php echo open_social_link('//www.v2ex.com/t/141485', '?', 'blank'); ?></label><br/>
            <?php do_action('open_social_tabone_profile_action'); ?>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Button','open-social'); ?></th>
        <td><fieldset>
            <label><input name="osop[extend_show_login]" type="checkbox" value="1" <?php checked(wpos_ops('extend_show_login'),1); ?> /> <?php _e('Show login button for wordpress native login', 'open-social'); ?></label><br/>
            <label><input name="osop[show_share_content]" type="checkbox" value="1" <?php checked(wpos_ops('show_share_content'),1);?> /> <?php _e('Show share buttons in Posts','open-social'); ?></label><br/>
            <label><input name="osop[show_share_content_page]" type="checkbox" value="1" <?php checked(wpos_ops('show_share_content_page'),1);?> /> <?php _e('Show share buttons in Pages','open-social'); ?></label><br/>
            <?php do_action('open_social_tabone_button_action'); ?>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Notify','open-social'); ?></th>
        <td><fieldset>
            <label><input name="osop[extend_send_email]" type="checkbox" value="1" <?php checked(wpos_ops('extend_send_email'),1); ?> /> <?php _e('Send email to notify Administrator when new user created','open-social'); ?></label><br/>
            <label><input name="osop[extend_ignore_reset]" type="checkbox" value="1" <?php checked(wpos_ops('extend_ignore_reset'),1);?> /> <?php _e('Ignore notifying Administrator when user resets lost password','open-social'); ?></label><br/>
            <?php do_action('open_social_tabone_notify_action'); ?>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Security','open-social'); ?></th>
        <td><fieldset>
            <label><input name="osop[extend_must_login]" type="checkbox" value="1" <?php checked(wpos_ops('extend_must_login'),1); ?> /> <?php _e('Visitor must login to interact with this website', 'open-social'); ?></label><br/>
            <label><input name="osop[extend_hide_login]" type="checkbox" value="1" <?php checked(wpos_ops('extend_hide_login'),1); ?> /> <?php _e('Hide login and admin page as 404', 'open-social'); ?></label><br/>
            <?php do_action('open_social_tabone_security_action'); ?>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Advanced','open-social'); ?></th>
        <td><fieldset>
            <label><input name="osop[more_button_icon]" type="checkbox" value="1" <?php checked(wpos_ops('more_button_icon'),1); ?> /> <?php _e('Customize Button Icon separately','open-social'); ?></label><br/> 
            <label><input name="osop[more_login_cburl]" type="checkbox" value="1" <?php checked(wpos_ops('more_login_cburl'),1); ?> /> <?php _e('Customize Callback URL separately','open-social'); ?></label><br/>
            <?php do_action('open_social_tabone_advanced_action'); ?>
            </fieldset>
        </td></tr>
        <?php do_action('open_social_tabone_end_action'); ?>
        </table>

        <table class="form-table">
        <?php do_action('open_social_tabtwo_start_action'); ?>
        <tr valign="top"><th><?php _e('Login','open-social'); ?></th>
        <td><fieldset>
            <label><input type="url" name="osop[extend_callback_url]" size="65" placeholder="<?php echo home_url()?>" value="<?php esc_attr_e(OPEN_CBURL);?>" /> <?php _e('Callback URL default','open-social'); ?></label><br/>
            <label><input type="text" name="osop[extend_goto_url]" size="65" value="<?php esc_attr_e(wpos_ops('extend_goto_url')); ?>" /> <?php _e('Goto URL after login','open-social'); ?></label><br/>
            <?php do_action('open_social_tabtwo_login_action'); ?>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Images','open-social'); ?></th>
        <td><fieldset>
            <label><input type="text" name="osop[extend_default_avatar]" size="65" placeholder="<?php esc_attr_e(parse_url(plugins_url('res/gravatar.png',__FILE__), PHP_URL_PATH)); ?>" value="<?php esc_attr_e(wpos_ops('extend_default_avatar')); ?>" /> <?php _e('Default user avatar image','open-social'); ?></label><br/>
            <label><input type="text" name="osop[extend_block_avatar]" size="65" placeholder="googleusercontent|facebook|fbcdn|fbsbx|twimg" value="<?php esc_attr_e(wpos_ops('extend_block_avatar')); ?>" /> <?php _e('Block user avatars by keywords in domains','open-social'); ?></label><br/>
            <label><input type="text" name="osop[extend_share_image]" size="65" value="<?php esc_attr_e(wpos_ops('extend_share_image')); ?>" /> <?php _e('Default image when share article','open-social'); ?></label><br/>
            <label><input type="text" name="osop[extend_iconfont_url]" size="65" value="<?php esc_attr_e(wpos_ops('extend_iconfont_url')); ?>" placeholder="//at.alicdn.com/t/abc_name.js" /> <?php _e('IconFont online url for symbol use','open-social'); ?>
            <?php echo open_social_link('//www.iconfont.cn/help/detail?helptype=code', '?', 'blank'); ?></label><br/>
            <?php do_action('open_social_tabtwo_images_action'); ?>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Caption','open-social'); ?></th>
        <td><fieldset>
            <label><input type="text" name="osop[login_button_title]" size="65" placeholder="<h5>Login with</h5>" value="<?php esc_attr_e(wpos_ops('login_button_title'));?>" /> <?php esc_attr_e(__('Caption for login buttons','open-social')); ?></label><br/>
            <label><input type="text" name="osop[share_button_title]" size="65" placeholder="<h5>Share with</h5>" value="<?php esc_attr_e(wpos_ops('share_button_title'));?>" /> <?php esc_attr_e(__('Caption for share buttons','open-social')); ?></label><br/>
            <label><input type="text" name="osop[bind_button_title]" size="65" placeholder="<h5>Bind with</h5>" value="<?php esc_attr_e(wpos_ops('bind_button_title'));?>" /> <?php esc_attr_e(__('Caption for bind buttons','open-social')); ?></label><br/>
            <label><input type="text" name="osop[profile_html_title]" size="65" placeholder="<h5>Profile</h5>" value="<?php esc_attr_e(wpos_ops('profile_html_title'));?>" /> <?php esc_attr_e(__('Caption for profile html','open-social')); ?></label>
            <?php do_action('open_social_tabtwo_caption_action'); ?>
        </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Blacklist','open-social'); ?></th>
        <td><fieldset>
            <label><?php _e('Email blacklist, one word per line','open-social')?></label> <br/>
            <textarea name="osop[mail_blacklist]" rows="5" cols="85" placeholder="<?php echo "keyword1\nkeyword2" ?>"><?php echo esc_textarea(wpos_ops('mail_blacklist')); ?></textarea><br/><br/>
            <?php do_action('open_social_tabtwo_blacklist_action'); ?>
        </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Code','open-social'); ?></th>
        <td><fieldset>
            <label><?php _e('Customize button icon style','open-social')?></label> <br/>
            <textarea name="osop[icon_style]" rows="8" cols="85" placeholder="<?php echo ".os-icon { margin: 3px 5px 3px 1px; display: inline-block; }\n.os-login-box { clear: both; line-height: 36px; }\n.os-login-box .os-icon { cursor: pointer; font-size: 32px; }\n.os-share-box .os-icon { cursor: pointer; font-size: 25px; }\n.os-login-title { }\n.os-share-title { }"; ?>"><?php echo esc_textarea(wpos_ops('icon_style')); ?></textarea><br/><br/>
            <label><?php _e('Customize profile html','open-social'); ?></label><br/>
            <textarea name="osop[profile_html]" rows="4" cols="85" placeholder="{profile}<br/>{name} ({logout})"><?php echo esc_textarea(wpos_ops('profile_html')); ?></textarea><br/>
            <code>{name}</code> <code>{email}</code> <code>{avatar}</code> <code>{profile}</code> <code>{logout}</code> <code>{avatar_url}</code> <code>{profile_url}</code> <code>{logout_url}</code>
            <br/><br/>
            <?php do_action('open_social_tabtwo_code_action'); ?>
        </fieldset>
        </td></tr>
        <?php do_action('open_social_tabtwo_end_action'); ?>
        </table>

        <table class="form-table">
        <?php do_action('open_social_tabmod_start_action'); ?>
        <?php if(defined('WPOS_MOD_COMPATIBLE')): ?>
        <tr valign="top"><th><?php echo WPOS_MOD_COMPATIBLE; ?></th>
        <td><fieldset>
            <label><input type="text" name="osop[login_button_hook]" size="65" value="<?php esc_attr_e(wpos_ops('login_button_hook'));?>" placeholder="um_main_register_fields, woocommerce_login_form" /> <?php esc_attr_e(__('Action hooks with login buttons','open-social')); ?></label><br/> 
            <label><input type="text" name="osop[share_button_hook]" size="65" value="<?php esc_attr_e(wpos_ops('share_button_hook'));?>" /> <?php esc_attr_e(__('Action hooks with share buttons','open-social')); ?></label><br/>
            <label><input type="text" name="osop[bind_button_hook]" size="65" value="<?php esc_attr_e(wpos_ops('bind_button_hook'));?>"  /> <?php esc_attr_e(__('Action hooks with bind buttons','open-social')); ?></label><br/>
            <label><input type="text" name="osop[profile_button_hook]" size="65" value="<?php esc_attr_e(wpos_ops('profile_button_hook'));?>" /> <?php esc_attr_e(__('Action hooks with profile html','open-social')); ?></label><br/>
            <sup><?php esc_attr_e(__('Notice: The action will be removed if it has been hooked already','open-social')); ?></sup><br/><br/>
            <label><input type="text" name="osop[sync_cellphone_bp]" size="65" value="<?php esc_attr_e(wpos_ops('sync_cellphone_bp')); ?>" placeholder="Field ID" <?php disabled(!function_exists('xprofile_set_field_data'),1);?> /> <?php _e('Sync BuddyPress cellphone','open-social'); ?></label><br/>
        </fieldset>
        </td></tr>
        <?php endif; ?>
        <?php do_action('open_social_tabmod_end_action'); ?>
        </table>

        <table class="form-table">
        <tbody id="login_order">
        <?php
        foreach ($GLOBALS['open_login_arr'] as $k=>$v) {
            if(!is_array($v) || count($v)<2) continue;
            $K = strtoupper($k);
            echo '<tr name="'.$k.'" '.open_social_show_more('open_social_login_'.$k, 1).'><th><span>'.$v[0].'</span> '.open_social_link($v[1], '?', 'blank').'</th><td><fieldset><label style="padding-right: 12px"><input name="osop['.$K.']" type="checkbox" value="1" '.checked(wpos_ops($K),1,false).' /> '.__('Enabled','open-social').'</label> ';
            if(wpos_ops('client_user_id') && wpos_ops('client_plugin_key')){
                echo '<label style="padding-right: 12px"><input name="osop['.$k.'_in]" type="checkbox" value="1" '.checked(wpos_ops($k.'_in'),1,false).' />'.(defined('OPEN_SOCIAL_SERVER')?__('Hidden','open-social'):__('Proxied','open-social')).'</label>';
            }
            do_action('open_social_login_option_action', $K);
            echo '<br/><label><input type="text" name="osop['.$K.'_AKEY]" value="'.trim(wpos_ops($K.'_AKEY')).'" size="65" placeholder="'.__('App ID','open-social').'" /></label><br/>
            <label><input type="password" name="osop['.$K.'_SKEY]" value="'.trim(wpos_ops($K.'_SKEY')).'" size="65" placeholder="'.__('App Key/Secret','open-social').'" /></label><br/>';
            if(wpos_ops('more_login_cburl')){
                echo '<label><input type="text" name="osop['.$K.'_CBURL]" size="65" value="'.esc_attr(wpos_ops($K.'_CBURL')).'" placeholder="'.__('Callback URL','open-social').'" /></label><br/>';
            }
            if(wpos_ops('more_button_icon')){
                echo '<label><input type="text" id="osop[login_'.$k.'_html]" name="osop[login_'.$k.'_html]" size="65" placeholder="'.esc_attr(open_login_button_show($k)).'" value="'.esc_attr(wpos_ops('login_'.$k.'_html')).'" /></label> 
                <label for="osop[login_'.$k.'_html]">'.open_login_button_show($k).'</label><br/>';
            }
            do_action('open_social_login_input_action', $K);
            echo '</fieldset>
            </td></tr>';
        }?></tbody>
        </table>

        <table class="form-table">
        <tbody id='share_order'>
        <?php
        foreach ($GLOBALS['open_share_arr'] as $k=>$v) {
            if(!is_array($v) || count($v)<2) continue;
            echo '<tr name="'.$k.'"><th><span>'.$v[0].'</span></th><td><fieldset><label>
                <input name="osop[share_'.$k.']" type="checkbox" value="1" '.checked(wpos_ops('share_'.$k),1,false).' /> '.sprintf(__('Share with %s','open-social'), $v[0]).'</label><br/>';
            if(wpos_ops('more_button_icon')) echo '<label><input type="text" id="osop[share_'.$k.'_html]" name="osop[share_'.$k.'_html]" size="65" placeholder="'.esc_attr(open_share_button_show($k)).'" value="'.esc_attr(wpos_ops('share_'.$k.'_html')).'" /></label>
                <label for="osop[share_'.$k.'_html]">'.open_share_button_show($k).'</label>';
            echo '</fieldset></td></tr>';
        }?></tbody>
        </table>

        <table class="form-table">
        <tr valign="top"><th><?php _e('Login','open-social'); ?></th>
        <td><fieldset>
            <p style="margin-top:0px">Shortcode: <code>[os_login]</code> PHP: <code>&lt;?php echo open_social_login_html();?&gt;</code> Widgets: <code><?php _e('WP Open Social Login', 'open-social'); ?></code></p><br/>
            <div class="os-preview-box"><?php echo open_social_login_html(array('preview'=>1)); ?></div><br/>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Share','open-social'); ?></th>
        <td><fieldset>
            <p>Shortcode: <code>[os_share]</code> PHP: <code>&lt;?php echo open_social_share_html();?&gt;</code> Widgets: <code><?php _e('WP Open Social Share', 'open-social'); ?></code></p><br/>
            <div class="os-preview-box"><?php echo open_social_share_html(array('preview'=>1)); ?></div><br/>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Profile','open-social'); ?></th>
        <td><fieldset>
            <p>Shortcode: <code>[os_profile]</code> PHP: <code>&lt;?php echo open_social_profile_html();?&gt;</code></p><br/>
            <div class="os-preview-box"><?php echo open_social_profile_html(); ?></div><br/>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Binding','open-social'); ?></th>
        <td><fieldset>
            <p>Shortcode: <code>[os_bind]</code> PHP: <code>&lt;?php echo open_social_bind_html();?&gt;</code></p><br/>
            <div class="os-preview-box"><?php echo open_social_bind_html(); ?></div><br/>
            </fieldset>
        </td></tr>
        <tr valign="top"><th><?php _e('Other','open-social'); ?></th>
        <td><fieldset>
            <p>Shortcode: <code>[os_hide] XXX [/os_hide]</code></p><br/>
            <div class="os-preview-box">
                <?php echo open_social_hide(array('preview'=>'hide'), 'XXX'); ?><hr/>
                <?php echo open_social_hide(array('preview'=>'show'), 'XXX'); ?>
            </div><br/><br/>
            <p>Shortcode: <code>[os_comment] XXX [/os_comment]</code></p><br/>
            <div class="os-preview-box">
                <?php echo open_social_comment(array('preview'=>'hide'), 'XXX'); ?><hr/>
                <?php echo open_social_comment(array('preview'=>'show'), 'XXX'); ?>
            </div>
        </fieldset>
        </td></tr>
        </table>
        <?php
            $hideArr = array('osop_login_order', 'osop_share_order', 'share_sina_user', 'share_sina_access_token', 'open_social_post_weibo_check', 'wechat_access_token', 'wechat_access_qrcode', 'wechat_access_share', 'client_plugin_key');
            foreach ($hideArr as $v) {
                echo '<input type="hidden" id="'.$v.'" name="osop['.$v.']" value="'.esc_attr(wpos_ops($v)).'" />';
            }
            submit_button();
        ?>
        </form>
        <script type="text/javascript">
            function reset_order(){
                jQuery('#osop_login_order,#osop_share_order').val('');
                jQuery('#submit').click();
            }
            jQuery('a.nav-tab').on('click', function(e){
                var idx = jQuery(this).index('a.nav-tab');
                jQuery('a.nav-tab').removeClass('nav-tab-active').eq(idx).addClass('nav-tab-active').blur();
                jQuery('.form-table').hide().eq(idx).show();
                if(window.localStorage) localStorage.setItem('open_social_tab', idx);
            });
            jQuery('span.nav-tab').on('click', function(e){
                jQuery('#show-settings-link').click();
            });
            jQuery('#show-settings-link').on('click', function(e){
                var toggle = jQuery('#screen-options-wrap').css('display') == 'none';
                jQuery('a.nav-tab').eq(3).click();
                jQuery('span.nav-tab').text(toggle?'-':'+');
            });
            jQuery('.manage-column td').each(function(e){
                jQuery(this).find('input:first').on('click',function(e){//login
                    var obj = jQuery(this).parent().parent().find('input').not(jQuery(this));
                    if(jQuery(this).prop('checked')){
                        obj.filter(':checkbox').removeAttr('disabled');
                        obj.filter(':text,:password').removeAttr('readonly');
                        jQuery(this).parent().parent().find('.os-icon').removeClass('os-icon-gray');
                    }else{
                        obj.filter(':checkbox').attr('disabled','disabled');
                        obj.filter(':text,:password').attr('readonly','readonly');
                        jQuery(this).parent().parent().find('.os-icon').addClass('os-icon-gray');
                    }
                });
                jQuery(this).find('input:checkbox[name*=_in]').on('click',function(e){//login_in
                    if(!jQuery(this).parent().parent().find('input:first').prop('checked')) return;
                    var obj = jQuery(this).parent().parent();
                    if(jQuery(this).prop('checked')){
                        obj.find(':text,:password').attr('readonly','readonly');
                        obj.find(':checkbox:gt(1)').attr('disabled','disabled');
                    }else{
                        obj.find(':text,:password').removeAttr('readonly');
                        obj.find(':checkbox:gt(1)').removeAttr('disabled');
                    }
                });
                if(!jQuery(this).find('input:first').prop('checked')){
                    jQuery(this).find(':text,:password').attr('readonly','readonly');
                    jQuery(this).find('input:checkbox:gt(0)').attr('disabled','disabled');
                    jQuery(this).find('.os-icon').addClass('os-icon-gray');
                }else if(jQuery(this).find('input:eq(1)').prop('checked')){
                    jQuery(this).find(':text,:password').attr('readonly','readonly');
                }
            });
            jQuery('.form-table:eq(2) td').each(function(e){//share
                jQuery(this).find('input:first').on('click',function(e){
                    var obj = jQuery(this).parent().parent().find('input').not(jQuery(this));
                    if(jQuery(this).prop('checked')){
                        obj.filter(':text').removeAttr('readonly');
                        jQuery(this).parent().parent().find('.os-icon').removeClass('os-icon-gray');
                    }else{
                        obj.filter(':text').attr('readonly','readonly');
                        jQuery(this).parent().parent().find('.os-icon').addClass('os-icon-gray');
                    }
                });
                if(!jQuery(this).find('input:first').prop('checked')){
                    jQuery(this).find('input:gt(0):text').attr('readonly','readonly');
                    jQuery(this).find('.os-icon').addClass('os-icon-gray');
                }
            });
            jQuery('input[name*=wechat_mp_desktop]').each(function(){
                var obj = jQuery('input[name*=wechat_mp_]:gt(1)');
                jQuery(this).on('click', function(){
                    if(jQuery(this).prop('checked')){
                        obj.filter(':text,:password').removeAttr('readonly');
                        obj.filter(':checkbox').removeAttr('disabled');
                    }else{
                        obj.filter(':text,:password').attr('readonly','readonly');
                        obj.filter(':checkbox').attr('disabled','disabled');
                    }
                });
                if(!jQuery(this).prop('checked')){
                    obj.filter(':text,:password').attr('readonly','readonly');
                    obj.filter(':checkbox').attr('disabled','disabled');
                }
            });
            jQuery('input[name*=_SKEY],input[name*=_asekey]').focus(
                function(){ jQuery(this).get(0).type = 'text'; }
            ).blur(
                function(){ jQuery(this).get(0).type = 'password'; }
            );
            jQuery('input[name*=WECHAT_MP_]').on('input propertychange', function() {
                jQuery('input[name*=wechat_access_]').val('');
            });
            jQuery(function(){
                var list, changed, tab = window.localStorage ? localStorage.getItem('open_social_tab') : 0;
                jQuery('a.nav-tab').eq(tab*1).click();
                jQuery('#osop_login_order').attr('value2',jQuery('#osop_login_order').val());
                jQuery('#osop_share_order').attr('value2',jQuery('#osop_share_order').val());
                jQuery('#login_order, #share_order').sortable({
                    placeholder: 'ui-sortable-placeholder',
                    containment: 'parent',
                    connectWith: 'tr:visible',
                    dropOnEmpty: false,
                    items: 'tr:visible',
                    handle: 'span',
                    cursor: 'move',
                    distance: 2,
                    tolerance: 'pointer',
                    stop: function(){
                        list = jQuery(this).sortable('toArray', {attribute:'name'}).join(',');
                        jQuery('#osop_'+jQuery(this).attr('id')).val(list);
                        changed = jQuery('#osop_'+jQuery(this).attr('id')).attr('value2') != list;
                        jQuery('#tab_'+jQuery(this).attr('id')).toggle(changed);
                    }
                });
            });
        </script>
    </div>
    <?php
}

//USERS

add_filter('manage_users_columns', 'open_social_user_list_column');
function open_social_user_list_column($columns){
    unset($columns['name']);
    $columns['id'] = __('ID', 'open-social');
    $columns['nickname'] = __('Nickname', 'open-social');
    $columns['cellphone'] = __('Cellphone', 'open-social');
    $columns['open_type'] = __('Binding', 'open-social');
    $columns['registered'] = __('Registered', 'open-social');
    return $columns;
}

add_filter('manage_users_sortable_columns', 'open_social_user_list_sort');
function open_social_user_list_sort($columns){
    $columns['id'] = 'id';
    $columns['nickname'] = 'display_name';
    $columns['cellphone'] = 'cellphone';
    $columns['open_type'] = 'open_type';
    $columns['registered'] = 'user_registered';
    return $columns;
}

add_action('manage_users_custom_column', 'open_social_user_list_content', 20, 3);
function open_social_user_list_content($value, $column_name, $user_id) {
    $user = get_userdata($user_id);
    if('id' == $column_name){
        if($from = get_user_meta($user_id, 'open_social_from', true)){
            return "<a href='{$from}' target=_blank>{$user_id}</a>";
        }
        return $user_id;
    }
    if('nickname' == $column_name) return $user->nickname;
    if('cellphone' == $column_name) return get_user_meta($user_id, 'cellphone', true);
    if('registered' == $column_name) return get_date_from_gmt($user->user_registered);
    if('open_type' == $column_name){
        $html = '<div class="os-user-box">';
        $user_level = get_user_meta($user_id, 'open_user_data_level', true);
        if($user_level){
            if($user_level==2) $html .= '<span class="os-icon dashicons dashicons-email" title="'.__('Email Activating', 'open-social').'"></span>';
            if($user_level==3) $html .= '<span class="os-icon dashicons dashicons-update" title="'.__('Password Resetting', 'open-social').'"></span>';
            if($user_level==4){
                $link = open_social_back(array('connect'=>'admin', 'action'=>'confirm', 'login'=>$user_id), OPEN_CBURL);
                $html .= '<a href="'.$link.'"><span class="os-icon dashicons dashicons-lock" title="'.__('Confirm User Registration', 'open-social').'"></span></a>';
            }
        }
        $open_type = get_user_meta($user_id, 'open_type', true);
        if($open_type){
            $open_type_list = explode(',', trim($open_type,','));
            foreach ($open_type_list as $k){
                $v = isset($GLOBALS['open_login_arr'][$k]) ? $GLOBALS['open_login_arr'][$k][0] : '';
                $html .= '<div class="os-icon os-'.$k.'" title="'.$v.'"><svg aria-hidden="true"><use xlink:href="#os-'.$k.'"></use></svg></div>';
            }
        }
        $html .= '</div>';
        return $html;
    }
    return $value;
}

add_filter('pre_get_users', 'open_social_user_pre_get');
function open_social_user_pre_get($query){
    if(!isset($_GET['orderby'])){
        $query->set('orderby', 'user_registered');
        $query->set('order', 'desc');
    }else if('open_type' == $query->get('orderby')){
        $query->set('orderby', 'meta_value');
        if(!isset($_GET['open_type'])) $query->set('meta_key', 'open_type');
    }
}

add_action('pre_user_query', 'open_social_user_list_query');
function open_social_user_list_query($vars){
    if(isset($_GET['open_type']) && $_GET['open_type']!=''){
        global $wpdb;
        $open_type = sanitize_text_field($_GET['open_type']);
        $vars->query_from .= " LEFT JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID={$wpdb->usermeta}.user_id AND {$wpdb->usermeta}.meta_key='open_type' ";
        if($open_type == '0'){
            $vars->query_where = "WHERE {$wpdb->usermeta}.meta_value='' OR {$wpdb->usermeta}.user_id IS NULL ";
        }else if($open_type == '1'){
            $vars->query_where = "WHERE {$wpdb->usermeta}.meta_value!=''";
        }else{
            $vars->query_where = "WHERE {$wpdb->usermeta}.meta_value LIKE '%".$open_type."%'";
        }
    }
}

add_action('restrict_manage_users', 'open_social_user_filter');
function open_social_user_filter(){
    $section = isset($_GET['open_type']) ? $_GET['open_type'] : '';
    $html = '<select id="open_type" style="float:none;margin-left:8px" onchange="location.href=\'?open_type=\'+this.value">';
    $html .= '<option value="">'.__('All Users', 'open-social').'</option>';
    $html .= '<option value="1"'.($section == '1' ? ' selected="selected"' : '').'>'.__('Binding', 'open-social').'</option>';
    foreach ($GLOBALS['open_login_arr'] as $k=>$v){
        if(!is_array($v)) continue;
        if(wpos_ops(strtoupper($k))) $html .= '<option value="'.$k.'"'.($k == $section ? ' selected="selected"' : '').'>'.$v[0].'</option>';
    }
    $html .= '<option value="0"'.($section == '0' ? ' selected="selected"' : '').'>'.__('Not Binding', 'open-social').'</option>';
    $html .= '</select>';
    echo $html;
}

add_filter('views_users', 'open_social_user_list_views');
add_filter('views_users-network', 'open_social_user_list_views');
function open_social_user_list_views($views){
    $link = open_social_link(admin_url('options-general.php?page=open-social'), __('WP Open Social', 'open-social'));
    if(is_super_admin() && is_main_site()) $views['os_setting'] = $link;
    return $views;
}

//COMMENT

add_filter('comment_form_defaults', 'open_social_comment_note');
function open_social_comment_note($fields){
    if(is_user_logged_in()){
        $user = wp_get_current_user();
        $fields['logged_in_as'] = '<p class="logged-in-as"> '.open_social_link(get_edit_user_link($user->ID).'?from='.esc_url($_SERVER['REQUEST_URI']).'%23comment', get_avatar($user->ID, 80)).'</p>';
    }elseif(get_option('comment_registration') && get_post_meta(get_the_ID(), 'os_guestbook', true)){
        add_filter('option_comment_registration', '__return_false');
        $fields['fields']['url'] = '';
    }
    return $fields;
}
add_action('pre_comment_on_post', 'open_social_comment_on_post', 10, 1);
function open_social_comment_on_post($id){
    if(is_user_logged_in() || !get_option('comment_registration') || !get_post_meta($id, 'os_guestbook', true)) return;
    add_filter('option_comment_registration', '__return_false');
}
add_filter('pre_comment_user_ip', 'open_social_extend_proxy_ip');
function open_social_extend_proxy_ip(){
    $user_ip = $_SERVER['REMOTE_ADDR'];
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    if(!empty($_SERVER['X_FORWARDED_FOR'])) $user_ip = $_SERVER['X_FORWARDED_FOR'];
    $user_ip = preg_replace('/[^0-9\.].*$/', '', $user_ip);
    return preg_replace('/[^0-9a-f:\., ]/si', '', $user_ip);
}

//EMAIL

add_filter('wp_mail', 'open_social_wp_mail_filter');
function open_social_wp_mail_filter($args){
    if(isset($args['to']) && open_social_in($args['to'], '@fake.com')) $args['to'] = '';//no fake email
    return $args;
}

add_filter('retrieve_password_message', 'open_social_fix_password_message', 99, 1);
function open_social_fix_password_message($message){
    return str_replace(array('<','>'), '', $message);//fix the messup link
}

add_action('wp_insert_comment','open_social_email_comment', 99, 2);
function open_social_email_comment($comment_id, $comment_object){
    if(!$comment_object->comment_parent>0 || !wpos_ops('extend_comment_email')) return;
    $comment_parent = get_comment($comment_object->comment_parent);
    $user_id = $comment_parent->user_id;
    if(!$user_id) return;//user only
    $email = get_userdata($user_id)->user_email;
    $comment_email = $comment_parent->comment_author_email;
    if(open_social_in($email, '@fake.com') || (isset($comment_email) && open_social_in($comment_email, '@fake.com'))) return;//no fake
    $headers = "MIME-Version: 1.0"."\r\n"."Content-type: text/html; charset=UTF-8"."\r\n";
    $site = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $toname = esc_attr($comment_parent->comment_author);
    $author = $comment_object->comment_author;
    $author_email = esc_attr($comment_object->comment_author_email);
    $comment = $comment_object->comment_content;
    $link = open_social_link(get_comment_link($comment_parent->comment_ID), get_the_title($comment_parent->comment_post_ID), 'blank');
    $message = "Hi, {toname}:<br/><br/>{comment}<br/>----<em>{author} ({author_email})</em><br/><br/># {link}<br/>";
    $message = apply_filters('open_social_comment_email_filter', $message);
    $message = str_replace(array('{toname}', '{comment}', '{author}', '{author_email}', '{link}'), array($toname, $comment, $author, $author_email, $link), $message);
    wp_mail($email, '['.$site.'] '.__('New reply to your comment', 'open-social'), $message, $headers);
}

//PROFILE

add_filter('get_avatar', 'open_social_get_avatar', 99999, 5);
function open_social_get_avatar($avatar, $id_or_email, $size=80, $default, $alt){
    if(!open_social_in($avatar, 'gravatar.com')) return $avatar;
    $data = open_social_get_avatar_data(array(), $id_or_email);
    if(isset($data['url'])){
        $img = substr($data['url'], stripos($data['url'], '//'));
        $avatar = preg_replace(array('/src="([^\"]+)"/', '/src=\'([^\']+)\'/'), "src=\"$img\"", $avatar);
        $avatar = preg_replace(array('/srcset="([^\"]+)"/', '/srcset=\'([^\']+)\'/'), "", $avatar);
    }
    if(!empty($data['ip']) && current_user_can('manage_options')){
        $avatar = str_replace(' src=', " data-ip=\"".$data['ip']."\" src=", $avatar);
    }
    return $avatar;
}

add_filter('get_avatar_data', 'open_social_get_avatar_data', 99999, 2);
function open_social_get_avatar_data($args, $id_or_email){
    if(isset($args['url']) && !open_social_in($args['url'], 'gravatar.com')) return $args;
    if(is_object($id_or_email)){
        if($id_or_email instanceof WP_Comment){
            $comment_ID = $id_or_email->comment_ID;
            $id_or_email = $id_or_email->user_id;
            if($comment_ID) $args['ip'] = esc_attr(get_comment_author_IP($comment_ID));
        }elseif($id_or_email instanceof WP_User){
            $id_or_email = $id_or_email->ID;
        }elseif($id_or_email instanceof WP_Post){
            $id_or_email = $id_or_email->post_author;
        }
    }elseif(is_email($id_or_email)){
        $user = get_user_by('email', $id_or_email);
        if(is_object($user)) $id_or_email = $user->ID;
        $avatar_option = apply_filters('pre_option_show_avatars', '', 100);
        if(!empty($avatar_option)) return $args;
    }
    if(is_numeric($id_or_email) && $img = get_user_meta($id_or_email, 'open_img', true)){
        if(!(($block = wpos_ops('extend_block_avatar')) && preg_match('/'.$block.'/i', parse_url($img, PHP_URL_HOST)))){
            $args['url'] = substr($img, stripos($img, '//'));
        }
    }
    if(isset($args['url']) && open_social_in($args['url'], 'gravatar.com')){
        if(wpos_ops('extend_default_avatar')){
            $args['url'] = wpos_ops('extend_default_avatar');
        }else if(wpos_ops('extend_avatar_cdn')){
            $args['url'] = preg_replace('/\/\/([a-z\.]+?)\//i', '//cdn.v2ex.com/gr', $args['url']);
        }
    }
    return $args;
}

add_filter('pre_get_avatar_data', 'open_social_pre_get_avatar_data', 99999, 2);
function open_social_pre_get_avatar_data($args, $id_or_email){
    $avatar_option = apply_filters('pre_option_show_avatars', '', 100);
    if(!empty($avatar_option)) return $args;
    $args['default'] = get_option('avatar_default', 'mystery');
    return $args;
}

add_action('personal_options', 'open_social_personal_options');
function open_social_personal_options($user) {
    if(!empty($_GET['user_id'])) echo "<tr valign='top'><th scope='row'>".__('Cellphone', 'open-social')."</th><td><input type='text' name='cellphone' minlength='11' maxlength='15' value='".get_user_meta($user->ID, 'cellphone', true)."' onkeyup='value=value.replace(/[^\d]/g,&#39;&#39;)' /></td></tr>";
}

add_action('profile_personal_options', 'open_social_profile_options');
function open_social_profile_options($user) {
    $link = current_user_can('manage_options') ? open_social_link(network_admin_url('options-general.php?page=open-social'), '?') : open_social_link(open_social_data('PluginURI'), '?', 'blank');
    echo '<h2>'.__('WP Open Social','open-social').' '.$link.'</h2>';
    echo "<table class='form-table' id='open_social_table'>";
    echo "<tr valign='top'><th scope='row'>".__('Binding', 'open-social')."</th><td>".open_social_bind_html(array('title'=>''))."</td></tr>";
    if(defined('OPEN_SOCIAL_SERVER')) echo "<tr valign='top'><th scope='row'>".__('User ID', 'open-social')."</th><td><input type='text' disabled='disabled' class='regular-text' value='".$user->ID."'/></td></tr>";
    echo "<tr valign='top'><th scope='row'>".__('Cellphone', 'open-social')."</th><td><input type='text' class='regular-text' name='cellphone' minlength='11' maxlength='15' value='".get_user_meta($user->ID, 'cellphone', true)."' onkeyup='value=value.replace(/[^\d]/g,&#39;&#39;)' /></td></tr>";
    echo "</table>
    <script type='text/javascript'>jQuery(document).ready(function(){
        jQuery('.user-email-wrap,.user-url-wrap').insertAfter('#open_social_table tr:last');
        jQuery('#display_name').parents('table').next('h2').remove();
    });</script>";
}

//IMAGES

add_action('wp_enqueue_scripts', 'open_social_style', 300);
add_action('login_enqueue_scripts', 'open_social_style');
add_action('admin_enqueue_scripts', 'open_social_style');
function open_social_style(){
    if(is_admin() && !in_array(get_current_screen()->id, array('settings_page_open-social', 'users', 'profile', 'user-edit', 'users-network', 'profile-network', 'user-edit-network'))) return;
    $version = filemtime(plugin_dir_path(__DIR__).plugin_basename(__FILE__));
    wp_enqueue_style('open-social-style', add_query_arg('v', $version, plugins_url('res/main.css',__FILE__)));
    if(is_admin()) wp_enqueue_script('jquery-ui-sortable');
    if(wpos_ops('icon_style')) wp_add_inline_style('open-social-style', wpos_ops('icon_style'));
    $iconfont = wpos_ops('extend_iconfont_url') ? wpos_ops('extend_iconfont_url') : plugins_url('res/iconfont.js',__FILE__);
    wp_enqueue_script('open-social-iconfont', add_query_arg('v', $version, $iconfont), array(), '', true);
    wp_enqueue_script('open-social-script', add_query_arg('v', $version, plugins_url('res/main.js',__FILE__)), array(), '', true);
    if(wpos_ops('extend_share_image')){
        wp_localize_script('open-social-script', 'os_share_image', array('url' => wpos_ops('extend_share_image')));
    }
    if(wpos_ops('share_wechat')){
        wp_enqueue_script('jquery.qrcode', plugins_url('res/jquery.qrcode.min.js',__FILE__), array('jquery'));
    }
    do_action('open_social_script_action');
}

//HTML

add_action('comment_form_top', 'open_social_login_html_echo');
add_action('comment_form_must_log_in_after', 'open_social_login_html_echo');
add_action('register_form', 'open_social_login_html_echo');
add_action('login_form', 'open_social_login_form');
function open_social_login_html_echo(){ echo open_social_login_html(); }
function open_social_share_html_echo(){ echo open_social_share_html(); }
function open_social_bind_html_echo(){ echo open_social_bind_html(); }
function open_social_profile_html_echo(){ echo open_social_profile_html(); }

function open_social_login_form(){
    if(is_user_logged_in()) return;
    if(isset($_SESSION['open_id'], $_SESSION['access_token'], $_GET['connect'], $_REQUEST['redirect_to'])){
        if(($bind = $_GET['connect']) && ($back = $_REQUEST['redirect_to']) && open_social_in($back, $bind)){
            if(open_social_in($back, 'action=bind') && isset($GLOBALS['open_login_arr'][$bind])){
                $title = sprintf(esc_attr__('Login and bind with %s', 'open-social'), $GLOBALS['open_login_arr'][$bind][0]);
                echo "<p class='forgetmenot' style='float:inherit;line-height:250%'>
                    <label><input name='osbindwith' value='1' type='checkbox' checked='checked' /> {$title}</label></p>";
                return;
            }
        }
        open_social_unsession('open_id, access_token');
    }
    echo open_social_login_html();
} 

function open_social_login_html($atts=array()){
    $preview = isset($atts['preview']) && $atts['preview'];
    if(!$preview && is_user_logged_in()) return;
    $title = $html = '';
    $title = isset($atts['title']) ? $atts['title'] : wpos_ops('login_button_title');
    if($title) $title = "<div class='os-login-title'>{$title}</div>";
    $show = (isset($atts, $atts['show']) && !empty($atts)) ? $atts['show'] : '';
    if(wpos_ops('extend_show_login') && !open_social_login_page()){
        $html .= open_login_button_show('system', __('Login','open-social'), wp_login_url(get_permalink()));
    }
    foreach ($GLOBALS['open_login_arr'] as $k=>$v){
        if(!is_array($v)) continue;
        if($show && !open_social_in($show.',', $k.',')) continue;
        if(defined('OPEN_SOCIAL_SERVER') && wpos_ops($k.'_in') && !$preview) continue;
        if((wp_is_mobile() || wpos_ops('wechat_mp_prior')) && $k == 'wechat' && !$preview) continue;//prior to wechat open
        if(((wp_is_mobile() && !open_social_in($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) 
            || (!wp_is_mobile() && !wpos_ops('wechat_mp_desktop'))) && $k == 'wechat_mp' && !$preview) continue;
        if(wpos_ops(strtoupper($k))) $html .= open_login_button_show($k, ($preview ? '' : sprintf(__('Login with %s','open-social'), $v[0])));
    }
    if($html) $html = "<div class='os-login-box'>{$html}</div>";
    return "{$title}{$html}";
} 

function open_social_bind_html($atts=array()){
    if(!is_user_logged_in()) return;
    $title = $html = '';
    $title = isset($atts['title']) ? $atts['title'] : wpos_ops('bind_button_title');
    if($title) $title = "<div class='os-bind-title'>{$title}</div>";
    $user = wp_get_current_user();
    $open_type = get_user_meta($user->ID, 'open_type', true);
    foreach ($GLOBALS['open_login_arr'] as $k=>$v){
        if(!is_array($v)) continue;
        if(wpos_ops(strtoupper($k))){
            if(defined('OPEN_SOCIAL_SERVER') && wpos_ops($k.'_in')) continue;
            if($open_type && open_social_in($open_type, $k.',')){
                $html .= open_login_button_unbind($k, sprintf(__('Unbind with %s','open-social'), $v[0]));
            }else{
                if(wp_is_mobile() && $k == 'wechat') continue;
                if((!wp_is_mobile() || !open_social_in($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) && $k == 'wechat_mp' && !wpos_ops('wechat_mp_desktop')) continue;
                $html .= open_login_button_show($k, sprintf(__('Bind with %s','open-social'), $v[0]));
            }
        }
    }
    if($html) $html = "<div class='os-login-box os-bind-box'>{$html}</div>";
    return "{$title}{$html}";
} 

function open_social_profile_html($atts=array()){
    if(!is_user_logged_in()) return;
    $title = isset($atts['title']) ? $atts['title'] : wpos_ops('profile_html_title');
    if($title) $title = "<div class='os-profile-title'>{$title}</div>";
    $user = wp_get_current_user();
    $name = $user->display_name;
    $email = $user->user_email;
    $avatar_url = get_avatar_url($user->ID);
    $avatar = get_avatar($user->ID);
    $profile_url = current_user_can('manage_options') ? admin_url() : get_edit_user_link().'?from='.esc_url($_SERVER['REQUEST_URI']);
    $profile = open_social_link($profile_url, $avatar);
    $logout_url = wp_logout_url($_SERVER['REQUEST_URI']);
    $logout = open_social_link($logout_url, __('Log Out', 'open-social'));
    $html = "{$profile}<br/>{$name} ({$logout})";
    if(wpos_ops('profile_html')){
        $be_replace = array('{name}','{email}','{avatar}','{profile}','{logout}','{avatar_url}','{profile_url}','{logout_url}');
        $to_replace = array($name, $email, $avatar, $profile, $logout, $avatar_url, $profile_url, $logout_url);
        $html = str_replace($be_replace, $to_replace, wpos_ops('profile_html'));
    }
    if($html) $html = "<div class='os-profile-box'>{$html}</div>";
    return "{$title}{$html}";
} 

add_filter('the_content', 'open_social_share_form');
function open_social_share_form($content) {
    static $shared = 0;
    if(!$shared && ((wpos_ops('show_share_content') && is_single()) || wpos_ops('show_share_content_page') && is_page())){
        $content .= open_social_share_html();
        $shared++;
    }
    return $content;
} 

function open_social_share_html($atts=array()) {
    $title = $html = '';
    $title = isset($atts['title']) ? $atts['title'] : wpos_ops('share_button_title');
    if($title) $title = "<div class='os-share-title'>{$title}</div>";
    $preview = isset($atts['preview']) && $atts['preview'];
    foreach ($GLOBALS['open_share_arr'] as $k=>$v) {
        if(!is_array($v) || count($v)<2) continue;
        if(!wp_is_mobile() && in_array($k, array('line', 'whatsapp'))) continue;
        if(wpos_ops('share_'.$k)) $html .= open_share_button_show($k, ($preview ? '' : sprintf(__('Share with %s','open-social'), $v[0])), $v[1]);
    }
    if($html) $html = "<div class='os-share-box'>{$html}</div>";
    if(wpos_ops('share_wechat')){
        $html .= "<div id='os-popup-placeholder' style='display:none'><span>&#215;</span>".open_share_button_show('wechat').__('Scan to share with WeChat','open-social')."</div>";
    }
    return "{$title}{$html}";
} 

function open_login_button_show($icon_type,$icon_title=null,$icon_link=OPEN_CBURL){
    $html = wpos_ops('more_button_icon') && wpos_ops("login_{$icon_type}_html") ? wpos_ops("login_{$icon_type}_html") : "<i class=\"iconfont os-icon os-{$icon_type}\"><svg aria-hidden=\"true\"><use xlink:href=\"#os-{$icon_type}\"></use></svg></i>";
    $site = is_multisite() ? get_current_blog_id() : '';
    $ext = empty($icon_title) ? "" : "onclick=\"login_button_click('{$icon_type}','{$icon_link}','login','{$site}')\" title=\"{$icon_title}\"";
    return empty($icon_title) ? $html : preg_replace('/ /', " {$ext} ", $html, 1);
}

function open_login_button_unbind($icon_type,$icon_title,$icon_link=OPEN_CBURL){
    $html = wpos_ops('more_button_icon') && wpos_ops("login_{$icon_type}_html") ? wpos_ops("login_{$icon_type}_html") : "<i class=\"iconfont os-icon os-icon-bind os-{$icon_type}\"><svg aria-hidden=\"true\"><use xlink:href=\"#os-{$icon_type}\"></use></svg></i>";
    $site = is_multisite() ? get_current_blog_id() : '';
    $ext = "onclick=\"confirm('".sprintf(__('Unbind with %s','open-social'), $GLOBALS['open_login_arr'][$icon_type][0])."?')&&login_button_click('{$icon_type}','{$icon_link}','unbind','{$site}')\" title=\"{$icon_title}\"";
    return preg_replace('/ /', " {$ext} ", $html, 1);
}

function open_share_button_show($icon_type,$icon_title=null,$icon_link=null){
    $html = wpos_ops('more_button_icon') && wpos_ops("share_{$icon_type}_html") ? wpos_ops("share_{$icon_type}_html") : "<i class=\"iconfont os-icon os-{$icon_type}\"><svg aria-hidden=\"true\"><use xlink:href=\"#os-{$icon_type}\"></use></svg></i>";
    $ext = empty($icon_link) ? "" : "onclick=\"share_button_click('{$icon_link}')\"";
    $ext .= empty($icon_title) ? "" : " title=\"{$icon_title}\"";
    return empty($icon_title) ? $html : preg_replace('/ /', " {$ext} ", $html, 1);
}

//CLASSES

class WPOS_QQ_CLASS {
    function open_login($state, $info) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$info['akey'],
            'scope'=>'get_user_info',
            'redirect_uri'=>$info['cburl'],
            'state'=>$state
        );
        open_social_next('https://graph.qq.com/oauth2.0/authorize?'.http_build_query($params));
    } 
    function open_callback($code, $info) {
        $params=array(
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'client_id'=>$info['akey'],
            'client_secret'=>$info['skey'],
            'redirect_uri'=>$info['cburl']
        );
        $str = open_social_http('https://graph.qq.com/oauth2.0/token?'.http_build_query($params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
        $str = open_social_http("https://graph.qq.com/oauth2.0/me?access_token=".$_SESSION['access_token']);
        $str_r = json_decode(trim(trim(trim($str),'callback('),');'), true);
        open_social_check($str_r,$_SESSION['access_token'],'openid');
        $_SESSION['open_id'] = $str_r['openid'];
    } 
    function open_new_user($info){
        $user = open_social_http('https://graph.qq.com/user/get_user_info?access_token='.$_SESSION['access_token'].'&oauth_consumer_key='.$info['akey'].'&openid='.$_SESSION['open_id']);
        open_social_check($user,$_SESSION['open_id'],'nickname');
        $_SESSION['open_img'] = isset($user['figureurl_qq_2']) ? $user['figureurl_qq_2'] : $user['figureurl_qq_1'];
        $name = isset($user['nickname']) ? $user['nickname'] : 'Q'.time();
        return array('nickname'=>$name);
    }
} 

class WPOS_SINA_CLASS {
    function open_login($state, $info) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$info['akey'],
            'redirect_uri'=>$info['cburl'],
            'state'=>$state
        );
        if(wpos_ops('weibo_force_login')) $params['forcelogin'] = 'true';
        if(wpos_ops('weibo_auto_follow')){
            $params['with_offical_account'] = 1;
            $params['scope'] = 'follow_app_official_microblog';
        }
        open_social_next('https://api.weibo.com/oauth2/authorize?'.http_build_query($params));
    } 
    function open_callback($code, $info) {
        $params=array(
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'client_id'=>$info['akey'],
            'client_secret'=>$info['skey'],
            'redirect_uri'=>$info['cburl']
        );
        $str = open_social_http('https://api.weibo.com/oauth2/access_token', array('method'=>'POST', 'body'=>$params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
        $_SESSION['open_id'] = $str['uid'];
    }
    function open_new_user($info){
        $user = open_social_http("https://api.weibo.com/2/users/show.json?access_token=".$_SESSION['access_token']."&uid=".$_SESSION['open_id']);
        open_social_check($user,$_SESSION['access_token'],'screen_name');
        $_SESSION['open_img'] = isset($user['avatar_large']) ? $user['avatar_large'] : $user['profile_image_url'];
        return array('nickname'=>$user['screen_name'], 'user_url'=>'https://weibo.com/'.$user['profile_url']);
    } 
} 

class WPOS_BAIDU_CLASS {
    function open_login($state, $info) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$info['akey'],
            'redirect_uri'=>$info['cburl'],
            'scope'=>'basic',
            'display'=>wp_is_mobile() ? 'mobile' : 'page',
            'state'=>$state
        );
        open_social_next('https://openapi.baidu.com/oauth/2.0/authorize?'.http_build_query($params));
    } 
    function open_callback($code, $info) {
        $params=array(
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'client_id'=>$info['akey'],
            'client_secret'=>$info['skey'],
            'redirect_uri'=>$info['cburl']
        );
        $str = open_social_http('https://openapi.baidu.com/oauth/2.0/token', array('method'=>'POST', 'body'=>$params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
    }
    function open_new_user($info){
        $user = open_social_http('https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser?access_token='.$_SESSION['access_token']);
        open_social_check($user, $_SESSION['access_token'], 'uid');
        $_SESSION['open_id'] = $user['uid'];
        $_SESSION['open_img'] = 'https://himg.bdimg.com/sys/portrait/item/'.$user['portrait'].'.jpg';
        return array('nickname'=>$user['uname']);
    } 
} 

class WPOS_GOOGLE_CLASS {
    function open_login($state, $info) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$info['akey'],
            'scope'=>'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'redirect_uri'=> OPEN_CBURL,
            'access_type'=>'offline',
            'state'=>$state
        );
        open_social_next('https://accounts.google.com/o/oauth2/auth?'.http_build_query($params));
    } 
    function open_callback($code, $info) {
        $params=array(
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'client_id'=>$info['akey'],
            'client_secret'=>$info['skey'],
            'redirect_uri'=>$info['cburl']
        );
        $str = open_social_http('https://accounts.google.com/o/oauth2/token', array('method'=>'POST', 'body'=>$params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
    }
    function open_new_user($info){
        $user = open_social_http('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$_SESSION['access_token']);
        open_social_check($user,$_SESSION['access_token'],'id');
        $_SESSION['open_id'] = $user['id'];
        $_SESSION['open_img'] = $user['picture'];
        return array('nickname'=>$user['name'], 'user_url'=>isset($user['link'])?$user['link']:'', 'user_email'=>$user['email']);
    }
} 

class WPOS_LIVE_CLASS {
    function open_login($state, $info) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$info['akey'],
            'redirect_uri'=>$info['cburl'],
            'scope'=>'wl.signin wl.basic wl.emails',
            'state'=>$state
        );
        open_social_next('https://login.live.com/oauth20_authorize.srf?'.http_build_query($params));
    } 
    function open_callback($code, $info) {
        $params=array(
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'client_id'=>$info['akey'],
            'client_secret'=>$info['skey'],
            'redirect_uri'=>$info['cburl']
        );
        $str = open_social_http('https://login.live.com/oauth20_token.srf', array('method'=>'POST', 'body'=>$params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
    }
    function open_new_user($info){
        $user = open_social_http('https://apis.live.net/v5.0/me');
        open_social_check($user,$_SESSION['access_token'],'id');
        $_SESSION['open_id'] = $user['id'];
        $_SESSION['open_img'] = 'https://storage.live.com/Users/0x'.$_SESSION['open_id'].'/MyProfile/ExpressionProfile/ProfilePhoto:UserTileStatic';
        $email = isset($user['emails']['preferred']) ? $user['emails']['preferred'] : $user['emails']['account'];
        return array('nickname'=>$user['name'], 'user_url'=>'', 'user_email'=>$email);
    }
} 

class WPOS_FACEBOOK_CLASS {
    function open_login($state, $info) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$info['akey'],
            'redirect_uri'=>$info['cburl'],
            'state'=>md5(uniqid(rand(), true)),
            'display'=>'page',
            'auth_type'=>'reauthenticate',
            //'scope'=>'basic_info,email',
            'state'=>$state
        );
        open_social_next('https://www.facebook.com/dialog/oauth?'.http_build_query($params));
    } 
    function open_callback($code, $info) {
        $params=array(
            'code'=>$code,
            'client_id'=>$info['akey'],
            'client_secret'=>$info['skey'],
            'redirect_uri'=>$info['cburl']
        );
        $str = open_social_http('https://graph.facebook.com/oauth/access_token?'.http_build_query($params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
    }
    function open_new_user($info){
        $user_img = open_social_http('https://graph.facebook.com/me/picture?redirect=false&height=100&type=small&width=100');
        open_social_check($user_img,$_SESSION['access_token'],'data');
        $_SESSION['open_img'] = $user_img['data']['url'];
        $user = open_social_http('https://graph.facebook.com/me?access_token='.$_SESSION['access_token']);
        open_social_check($user,$_SESSION['access_token'],'id');
        $_SESSION['open_id'] = $user['id'];
        return array('nickname'=>$user['name'], 'user_url'=>$user['link'], 'user_email'=>$user['email']);
    } 
} 
  
class WPOS_TWITTER_CLASS {
    function open_login($state, $info) {
        $str = '';
        $params=array(
            'oauth_callback'=>open_social_back(array('code'=>'twitter_fixer', 'state'=>$state), OPEN_CBURL),//fix no code return
            'oauth_consumer_key'=>$info['akey'],
            'oauth_nonce'=>md5(microtime().mt_rand()),
            'oauth_signature_method'=>'HMAC-SHA1',
            'oauth_timestamp'=>time(),
            'oauth_version'=>'1.0'
        );
        foreach ($params as $key => $val) { $str .= '&'.$key.'='.rawurlencode($val); }
        $base = 'GET&'.rawurlencode('https://api.twitter.com/oauth/request_token').'&'.rawurlencode(trim($str, '&'));
        $params['oauth_signature'] = base64_encode(hash_hmac('sha1', $base, $info['skey'].'&', true));
        $str = '';
        foreach ($params as $key => $val) { $str .= ''.$key.'="'.rawurlencode($val).'", '; }
        $header = array('Authorization'=>'OAuth '.trim($str,', '));
        $token = open_social_http('https://api.twitter.com/oauth/request_token', array('headers'=>$header));
        open_social_check($token,$str,'oauth_token');
        $_SESSION['oauth_token'] = $token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
        open_social_next('https://api.twitter.com/oauth/authenticate?force_login=false&oauth_token='.$_SESSION['oauth_token']);
    } 
    function open_callback($code, $info) {
        $str = '';
        $params=array(
            'oauth_consumer_key'=>$info['akey'],
            'oauth_nonce'=>md5(microtime().mt_rand()),
            'oauth_signature_method'=>'HMAC-SHA1',
            'oauth_timestamp'=>time(),
            'oauth_token'=>$_SESSION['oauth_token'],
            'oauth_version'=>'1.0'
        );
        foreach ($params as $key => $val) { $str .= '&'.$key.'='.rawurlencode($val); }
        $base = 'POST&'.rawurlencode('https://api.twitter.com/oauth/access_token').'&'.rawurlencode(trim($str, '&'));
        $params['oauth_signature'] = base64_encode(hash_hmac('sha1', $base, $info['skey'].'&'.$_SESSION['oauth_token_secret'], true));
        $params['oauth_verifier'] = $_GET['oauth_verifier'];//only can be put in header, cant be post
        open_social_unsession('oauth_token, oauth_token_secret');
        $str = '';
        foreach ($params as $key => $val) { $str .= ''.$key.'="'.rawurlencode($val).'", '; }
        $header = array('Authorization'=>'OAuth '.trim($str,', '));
        $token = open_social_http('https://api.twitter.com/oauth/access_token', array('method'=>'POST', 'headers'=>$header));
        open_social_check($token,$code,'oauth_token');
        $_SESSION['access_token'] = $token['oauth_token'];
        $_SESSION['open_id'] = $token['user_id'];
        $_SESSION['open_name'] = $token['screen_name'];
        $params['oauth_token'] = $_SESSION['access_token'];
        $str = '';
        unset($params['oauth_signature'], $params['oauth_verifier']);
        foreach ($params as $key => $val) { $str .= '&'.$key.'='.rawurlencode($val); }
        $base = 'GET&'.rawurlencode('https://api.twitter.com/1.1/account/verify_credentials.json').'&'.rawurlencode('include_email=true&'.trim($str, '&'));
        $params['oauth_signature'] = base64_encode(hash_hmac('sha1', $base, $info['skey'].'&'.$token['oauth_token_secret'], true));
        $str = '';
        foreach ($params as $key => $val) { $str .= ' '.$key.'="'.rawurlencode($val).'", '; }
        $header = array('Authorization'=>'OAuth '.trim($str,', '));
        $user = open_social_http('https://api.twitter.com/1.1/account/verify_credentials.json?include_email=true', array('headers'=>$header));
        open_social_check($user, $str, 'profile_image_url_https');
        $_SESSION['open_img'] = str_replace('_normal', '_200x200', $user['profile_image_url_https']);
        $_SESSION['nick_name'] = $user['name'];
        if(isset($user['email'])) $_SESSION['open_email'] = $user['email'];
        if(strlen($_SESSION['open_id'])<6 || strlen($_SESSION['access_token'])<6) open_social_next('./');//Twitter: Something is technically wrong
    }
    function open_new_user($info){
        $twnu = array(
            'nickname' => isset($_SESSION['nick_name']) ? $_SESSION['nick_name'] : $_SESSION['open_name'],
            'user_url' => 'https://twitter.com/'.$_SESSION['open_name']
        );
        if(isset($_SESSION['open_email'])){
            $twnu['user_email'] = $_SESSION['open_email'];
        }
        open_social_unsession('open_email, open_name, nick_name');
        return $twnu;
    } 
} 

class WPOS_WECHAT_CLASS {
    function open_login($state, $info) {
        $params=array(
            'appid'=>$info['akey'],
            'redirect_uri'=>$info['cburl'],
            'response_type'=>'code',
            'scope'=>'snsapi_login',
            'state'=>$state
        );
        open_social_next('https://open.weixin.qq.com/connect/qrconnect?'.http_build_query($params).'#wechat_redirect');
    } 
    function open_callback($code, $info) {
        $params=array(
            'appid'=>$info['akey'],
            'secret'=>$info['skey'],
            'code'=>$code,
            'grant_type'=>'authorization_code'
        );
        $str = open_social_http('https://api.weixin.qq.com/sns/oauth2/access_token', array('method'=>'POST', 'body'=>$params));
        open_social_check($str,$code,'access_token');
        $_SESSION['access_token'] = $str['access_token'];
        $_SESSION['open_id'] = $str['openid'];
    }
    function open_new_user($info){
        $user = open_social_http('https://api.weixin.qq.com/sns/userinfo?access_token='.$_SESSION['access_token'].'&openid='.$_SESSION['open_id']."&lang=zh_CN");
        open_social_check($user,$_SESSION['open_id'],'headimgurl');
        $_SESSION['open_img'] = preg_replace('/\/0$/', '/132', $user['headimgurl']);
        if(isset($user['unionid'])) $_SESSION['unionid'] = $user['unionid'];
        return array('nickname'=>$user['nickname']);
    } 
} 

//SHORTCODE

add_shortcode('os_login', 'open_social_login_html');
add_shortcode('os_bind', 'open_social_bind_html');
add_shortcode('os_share', 'open_social_share_html');
add_shortcode('os_profile', 'open_social_profile_html');
add_shortcode('os_hide', 'open_social_hide');
add_shortcode('os_comment', 'open_social_comment');
function open_social_hide($atts=array(), $content=''){
    $preview = isset($atts['preview']) ? $atts['preview'] : false;
    $show = $preview ? ($preview == 'show' ? true : false) : is_user_logged_in();
    $html = $show ? trim($content) : '&lt;!--'.__('Login to check out this content','open-social').' --&gt;';
    return $show ? "<div class='os-show'><p>{$html}</p></div>" : "<p class='os-hide'>{$html}</p>";
}
function open_social_comment($atts=array(), $content=''){
    $preview = isset($atts['preview']) ? $atts['preview'] : false;
    $show = $preview == 'show' ? true : false;
    $user_id = get_current_user_id();
    $post_id = get_the_ID();
    if($user_id && $post_id){
        if(current_user_can('edit_post', $post_id)){
            $show = true;
        }else{
            $arr = get_approved_comments($post_id);
            foreach($arr as $comment){
                if($user_id == $comment->user_id){
                    $show = true;
                    break;
                }
            }
        }
    }
    $html = $show ? trim($content) : '&lt;!--'.__('Leave comment to check out this content','open-social').' --&gt;';
    return $show ? "<div class='os-show'><p>{$html}</p></div>" : "<p class='os-hide'>{$html}</p>";
}

//WIDGETS

add_action('widgets_init', 'open_social_widgets_init');
function open_social_widgets_init(){
    register_widget('open_social_login_widget');
    register_widget('open_social_share_widget');
}

class open_social_login_widget extends WP_Widget {
    function __construct() {
        parent::__construct(false, __('WP Open Social Login', 'open-social'), array('description'=>__('Display your WP Open Social login button', 'open-social')));
    }
    function form($instance) {
        $title = $instance ? $instance['title'] : '';
        $hide = isset($instance['hide']) ? (bool) $instance['hide'] : false;
        $html = '<p><label>'.__('Title:', 'open-social').'</label><input class="widefat" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" /></p>';
        $html .= '<p><label><input class="checkbox" type="checkbox" '.checked($hide, 1, 0).' name="'.$this->get_field_name('hide').'" /> '.__('Don\'t show profile after login', 'open-social').'</label></p>';
        echo $html;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = empty($new_instance['title']) ? '' : strip_tags($new_instance['title']);
        $instance['hide'] = isset($new_instance['hide']) ? (bool) $new_instance['hide'] : false;
        return $instance;
    }
    function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $hide = isset($instance['hide']) ? $instance['hide'] : false;
        if(!$title) $title = __('Howdy', 'open-social');
        $html = is_user_logged_in() ? ($hide ? '' : open_social_profile_html(array('title'=>''))) : open_social_login_html(array('title'=>''));
        if(!empty($html)){
            $html = '<div class="textwidget os-login-widget">'.$html.'</div>';
            echo $args['before_widget'].$args['before_title'].$title.$args['after_title'].$html.$args['after_widget'];
        }
    }
}

class open_social_share_widget extends WP_Widget {
    function __construct() {
        parent::__construct(false, $name = __('WP Open Social Share', 'open-social'), array('description' => __('Display your WP Open Social share button', 'open-social')));
    }
    function form($instance) {
        $title = $instance ? $instance['title'] : '';
        $html = '<p><label>'.__('Title:', 'open-social').'</label><input class="widefat" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" /></p>';
        echo $html;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = empty($new_instance['title']) ? '' : strip_tags($new_instance['title']);
        return $instance;
    }
    function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        if(!$title) $title = __('Share With', 'open-social');
        $html = open_social_share_html(array('title'=>''));
        if(!empty($html)){
            $html = '<div class="textwidget os-share-widget">'.$html.'</div>';
            echo $args['before_widget'].$args['before_title'].$title.$args['after_title'].$html.$args['after_widget'];
        }
    }
}

?>