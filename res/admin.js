"use strict";
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