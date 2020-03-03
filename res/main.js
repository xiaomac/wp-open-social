
function open_social_param(name){
    var aParams = document.location.search.substr(1).split('&');
    for (var i = 0; i < aParams.length; i++){
        var aParam = aParams[i].split('=');
        if(decodeURIComponent(aParam[0]) == name) return decodeURIComponent(aParam[1]);
    }
    return '';
}

function login_button_click(id,link,type,site){
    var back = location.href;
    if(id=='wechat_mp' && !/MicroMessenger/.test(navigator.userAgent)){
        jQuery('#os-popup-overlay, #os-popup-box').remove();
        jQuery('body').append("<div id='os-popup-overlay'></div><div id='os-popup-box' class='os-popup-box'><div id='os-popup-title'>"+jQuery('#os-popup-placeholder').html()+"</div><div id='os-popup-content'></div></div>");
        jQuery('#os-popup-overlay').show();
        jQuery('#os-popup-box').css({
            top: jQuery(window).height()/2-175, left: jQuery(window).width()/2-150
        }).show();
        jQuery('#os-popup-content').empty().qrcode({
            width: 250, height: 250, text: os_utf16to8(decodeURIComponent(location.href))
        });
        return;
    }
    if(open_social_param('redirect_to')) back = open_social_param('redirect_to');
    location.href = link + (/\?/.test(link) ? '&' : '?') + 'connect=' + id + '&action=' + type + (site ? '&site='+site : '') + '&back=' + escape(back);
}

function share_button_click(link){
    var url = encodeURIComponent(location.href);
    var title = encodeURIComponent(document.title);
    var summary = document.querySelector('.entry-content') || document.querySelector('article') || document.querySelector('main') || document.querySelector('body') || '';
    var pic = '', pics = '';
    if(summary){
        var obj = summary.querySelectorAll('img');
        for (var i = 0; i < obj.length; i++){
            pics += (pics?'||':'') + encodeURIComponent(obj[i].getAttribute('data-src')||obj[i].getAttribute('src'));
            if(i>2) break;
        }
        summary = encodeURIComponent(summary.innerText.replace(/\r|\n|\t/g,'').replace(/ +/g,' ').replace(/<!--(.*)\/\/-->/g,'').substr(0,80));
        pic = pics.split('||')[0];
    }
    if(!pic && window.os_share_image) pic = os_share_image.url;
    if(!/summary/.test(link) && summary) title = title + ': ' + summary + '.. ';
    link = link.replace(/%URL%/g,url).replace(/%TITLE%/g,title).replace(/%SUMMARY%/g,summary).replace(/%PICS%/g,pics).replace(/%PIC%/g,pic);
    window.open(link, 'xmOpenWindow', 'width=750,height=600,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1');
}

function os_utf16to8(str){
    var i, c, out = '', len = str.length;
    for(i = 0; i < len; i++) {
        c = str.charCodeAt(i);
        if ((c >= 0x0001) && (c <= 0x007F)) {
            out += str.charAt(i);
        } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        } else {
            out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        }
    }
    return out;
}

window.jQuery && (function(){
    jQuery(document).on('click', '.os-share-box .os-wechat', function(){
        jQuery('#os-popup-overlay, #os-popup-box').remove();
        jQuery('body').append("<div id='os-popup-overlay'></div><div id='os-popup-box' class='os-popup-box'><div id='os-popup-title'>"+jQuery('#os-popup-placeholder').html()+"</div><div id='os-popup-content'></div></div>");
        jQuery('#os-popup-overlay').show();
        if(/Mobile/.test(navigator.userAgent)){
            jQuery('#os-popup-title').hide();
            jQuery('#os-popup-box').removeClass().css({
                top: 0, right: 0, background: 'transparent', zIndex: 100000
            }).show();
            jQuery('#os-popup-content').html('<i class="os-icon os-share-native"><svg aria-hidden="true"><use xlink:href="#os-share-native"></use></svg></i>');
        }else{
            jQuery('#os-popup-box').css({
                top: jQuery(window).height()/2-175, left: jQuery(window).width()/2-150
            }).show();
            jQuery('#os-popup-content').empty().qrcode({
                width: 250, height: 250, text: os_utf16to8(decodeURIComponent(location.href))
            });
        }
    });
    jQuery(document).on('click', '#os-popup-overlay, #os-popup-box', function(){
        jQuery('#os-popup-overlay, #os-popup-box').hide();
    });
})();

document.addEventListener && document.addEventListener("DOMContentLoaded", function(){
    if(window.wx && 'object' == typeof os_wechat_init){
        os_wechat_init['debug'] = false;
        os_wechat_init['jsApiList'] = ['onMenuShareAppMessage','onMenuShareTimeline','updateAppMessageShareData','updateTimelineShareData'];
        wx.config(os_wechat_init);
        wx.ready(function(){
            var shareData = {
                title: document.title,
                desc: '',
                link: location.href,
                imgUrl: ''
            };
            var content = document.querySelector('.entry-content') || document.querySelector('article') || document.querySelector('body') || '';
            if(content){
                shareData.desc = content.innerText.replace(/\r|\n|\t/g,'').replace(/ +/g,' ').replace(/<!--(.*)\/\/-->/g,'').substr(0,50);
                var img = content.querySelector('img');
                if(img){
                    shareData.imgUrl = img.getAttribute('data-src')||img.getAttribute('src');
                }else{
                    if(window.os_share_image) shareData.imgUrl = os_share_image.url;
                }
            }
            if(wx.onMenuShareAppMessage){
                wx.onMenuShareAppMessage(shareData);
                wx.onMenuShareTimeline(shareData);
            }else{
                wx.updateAppMessageShareData(shareData);
                wx.updateTimelineShareData(shareData);
            }
        });
        wx.error(function(res){
            window.console && console.log(res.errMsg);
        });
    };
}, false);
