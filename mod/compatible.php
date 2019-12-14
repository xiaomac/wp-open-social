<?php
/**
 * Plugin Name: WP Open Social
 * Plugin Version: 5.0
 * Plugin URI: https://www.xiaomac.com/wp-open-social.html
 * Author: Link (XiaoMac.com)
 * License: MIT License
 */

/**
 * 模块名称：兼容模块
 * Module Name: Compatible
 * Module Version: 1.0
 *
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
 */

add_action('open_social_init_action', 'open_social_init_action_comp', 1);
function open_social_init_action_comp(){
    define('WPOS_MOD_COMPATIBLE', __('Compatible Mod','open-social'));
}

add_action('open_social_screen_help_filter', 'open_social_screen_help_filter_comp', 99);
function open_social_screen_help_filter_comp($screen){
    $constant = 'constant';
    $content = '<p>'.open_social_data('Description').'</p><br/><p>';
    foreach($GLOBALS['wposmods'] as $m){
        if(defined('WPOS_MOD_'.strtoupper($m))) $content .= "<button class=button>{$constant('WPOS_MOD_'.strtoupper($m))}</button> ";
    }
	$screen->add_help_tab(array('id'=>'open_social_mods', 'title'=>__('Module Loaded','open-social'), 'content'=>$content));
}

add_action('open_social_init_later_action', 'open_social_init_later_action_comp');
function open_social_init_later_action_comp(){
    if(empty($_SESSION['os_bp_new_user_activated'])) return;
    add_filter('bp_disable_profile_sync', '__return_true');//xprofile
    do_action('bp_core_activated_user', intval($_SESSION['os_bp_new_user_activated']));
    open_social_unsession('os_bp_new_user_activated');
}

add_action('open_social_tabone_profile_action', 'open_social_tabone_profile_action_comp');
function open_social_tabone_profile_action_comp(){
    ?>
        <label><input name="osop[extend_must_cellphone]" type="checkbox" value="1" <?php checked(wpos_ops('extend_must_cellphone'),1);?> /> <?php _e('New user must fill in cellphone number (by <i>Cellphone Identification Policy</i>)','open-social'); ?></label><br/>
        <label><input name="osop[extend_profile_path]" type="checkbox" value="1" <?php checked(wpos_ops('extend_profile_path'),1);?> /> <?php _e('New user using user-id as nicename instead of username in url','open-social'); ?></label><br/>
    <?php
}

add_filter('bp_core_fetch_avatar', 'open_social_bp_fetch_avatar', 999, 2);
add_filter('bp_core_fetch_avatar_url', 'open_social_bp_fetch_avatar', 999, 2);
function open_social_bp_fetch_avatar($url, $params){
    if(!open_social_in($url, 'gravatar.com')) return $url;
    if(!empty($params['item_id']) && $arr = open_social_get_avatar_data(array(), $params['item_id'])){
        if(!empty($arr['url'])) $img = substr($arr['url'], stripos($arr['url'], '//'));
        if(!empty($img)) return empty($params['html'])? $img : preg_replace('/src="([^\"]+)"/', "src=\"$img\"", $url);
    }
    return $url;
}

add_action('open_social_hook_action', 'open_social_hook_action_with');
function open_social_hook_action_with(){
    $arr = array('bp_core_general_settings_after_submit', 'woocommerce_after_edit_account_form', 'um_after_account_general');
    foreach($arr as $k){
        if(!has_action($k, 'open_social_bind_html_echo')) add_action($k, 'open_social_bind_html_echo');
    }
    $arr = array('um_main_login_fields', 'um_main_register_fields', 'woocommerce_login_form', 'woocommerce_register_form');
    foreach($arr as $k){
        if(!has_action($k, 'open_social_login_html_echo')) add_action($k, 'open_social_login_html_echo');
    }
    foreach(array('login', 'share', 'bind', 'profile') as $k){
        if(!$hooks = wpos_ops($k.'_button_hook')) continue;
        if(!$arr = preg_split('/[ ,;]+/', $hooks, null, PREG_SPLIT_NO_EMPTY)) continue;
        $key = "open_social_{$k}_html_echo";
        foreach($arr as $one){
            if(has_action($one, $key)){
                remove_action($one, $key);
            }else{
                add_action($one, $key);
            }
        }
    }
}

add_action('personal_options_update', 'open_social_profile_update');
add_action('edit_user_profile_update', 'open_social_profile_update');
function open_social_profile_update($user_id) {
    if(!current_user_can('edit_user', $user_id)) return;
    if(!empty($_POST['cellphone'])) open_social_update_cellphone($user_id, $_POST['cellphone']);
    if(!empty($_GET['clear']) && $_GET['clear'] == 'avatar') update_user_meta($user_id, 'open_img', '');
}

add_filter('user_profile_picture_description', 'open_social_clear_avatar', 10, 2);
function open_social_clear_avatar($desc, $user){
    if(!$user || (!$img = get_avatar_url($user)) || open_social_in($img, 'gravatar.com')) return $desc;
    if(current_user_can('edit_user')) $link = 'javascript:jQuery("#your-profile").attr("action","?clear=avatar").find(":submit").click();';
    return isset($link) ? open_social_link($link, __('Reset this avatar', 'open-social'), 'button') : '';
}

add_action('xprofile_updated_profile', 'open_social_sync_cellphone', 999);
add_action('bp_core_signup_user', 'open_social_sync_cellphone', 999);
add_action('bp_core_activated_user', 'open_social_sync_cellphone', 999);
function open_social_sync_cellphone($uid){
    if(!$field = wpos_ops('sync_cellphone_bp')) return;
    $cellphone = bp_get_profile_field_data(array('field' => $field, 'user_id' => $uid));
    if(!empty($cellphone)) update_user_meta($uid, 'cellphone', esc_attr($cellphone));
}

function open_social_update_cellphone($uid, $cellphone){
    update_user_meta($uid, 'cellphone', esc_attr($cellphone));
    if(($field = wpos_ops('sync_cellphone_bp')) && function_exists('xprofile_set_field_data')){
        xprofile_set_field_data($field, $uid, esc_attr($cellphone));
    }
}

?>