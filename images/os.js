function login_button_click(id,link){
	var back = location.href;
	if('wordpress'==id){
		location.href = link;
	}else{
		try{if(back.indexOf('wp-login.php')>0) back = document.loginform.redirect_to.value;}catch(e){back = '/';}
		if(back.indexOf('profile.php')>0 && back.indexOf('updated')<0) back = back.indexOf('?')>0 ? (back + '&updated=1') : (back + '?updated=1');
		location.href=(link?link:'/')+'?connect='+id+'&action=login&back='+escape(back);
	}
}

function login_button_unbind_click(id,link){
	var back = location.href;
	if(back.indexOf('profile.php')>0 && back.indexOf('updated')<0) back = back.indexOf('?')>0 ? (back + '&updated=1') : (back + '?updated=1');
	location.href=(link?link:'/')+'?connect='+id+'&action=unbind&back='+escape(back);
}

function share_button_click(link,ev){
	if(link=='QRCODE'){
		if(!window.jQuery) return;
		var elm = ev.srcElement || ev.target;
		var qrDiv = jQuery(elm).parent().find('.open_social_qrcode');
		if(!qrDiv.find('canvas').length){
			qrDiv.qrcode({width:180,height:180,correctLevel:0,background:'#fff',foreground:'#111',text:location.href});
		}
		qrDiv.toggle(250);
	}else{
		var url = encodeURIComponent(location.href);
		var title = encodeURIComponent(document.title + (window.jQuery ? (': ' + jQuery('article .entry-content').text().replace(/\r|\n|\t/g,'').replace(/ +/g,' ').replace(/<!--(.*)\/\/-->/g,'').substr(0,100)) : ''));
		var pic = '';
		window.jQuery && jQuery('#content > article img').each(function(){pic+=(pic?'||':'')+encodeURIComponent(jQuery(this).attr('src'));});
		window.open(link.replace("%URL%",url).replace("%TITLE%",title).replace("%PIC%",pic),'xmOpenWindow','width=600,height=480,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1');
	}
}

window.jQuery && jQuery(document).ready(function($){
    try{
    	$('.open_social_box').tooltip({ position: { my: "left top+5", at: "left bottom" }, show: { effect: "blind", duration: 200 } });
    }catch(e){}
	$("img.avatar[ip*='.']").each(
		function(){
			$(this).click(
				function(){
					window.open("http://www.baidu.com/s?wd="+$(this).attr('ip'));
				});
	});
	$('.comment-content a,.comments-area a.url').each(
		function(){$(this).attr('target','_blank');}
	);
    var float_button = $("#open_social_float_button");
	if(!$("#respond")[0]) $('#open_social_float_button_comment').hide();
    $(window).scroll(function() {
		if(float_button[0]){
	        if ($(window).scrollTop() >= 300) {
	            $('#open_social_float_button').fadeIn(250);
	        } else {
	            $('#open_social_float_button').fadeOut(250);
	        }
		}
    });
    $('#open_social_float_button_top').click(function() {
        $('html,body').animate({
            scrollTop: '0px'
        },
        100);
    });
    $('#open_social_float_button_comment').click(function() {
        $('html,body').animate({
            scrollTop: $('#respond').offset().top
        },
        200);
    });
});
