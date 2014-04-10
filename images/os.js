function login_button_click(id,link){
	var back = location.href;
	try{if(location.href.indexOf('wp-login.php')>0) back = document.loginform.redirect_to.value;}catch(e){back = '/';}
	location.href=(link?link:'/')+'?connect='+id+'&action=login&back='+escape(back);
}

function share_button_click(link){
	var url = encodeURIComponent(location.href);
	var title = encodeURIComponent(document.title);
	window.open(link.replace("%URL%",url).replace("%TITLE%",title),'xmOpenWindow','width=600,height=480,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1');
}

jQuery(document).ready(function($){
    $('.open_social_box').tooltip({ position: { my: "left top+5", at: "left bottom" }, show: { effect: "blind", duration: 200 } });
	
	$("img.avatar[ip*='.']").each(
		function(){
			$(this).click(
				function(){
					window.open("http://www.baidu.com/#wd="+$(this).attr('ip'));
				});
	});
	
    var float_button = $("#open_social_float_button");
	if(!$("#respond")[0]) $('#open_social_float_button_comment').hide();
    $(window).scroll(function() {
		if(float_button[0]){
	        if ($(window).scrollTop() >= 300) {
	            $('#open_social_float_button').fadeIn(500);
	        } else {
	            $('#open_social_float_button').fadeOut(300);
	        }
		}
    });
    $('#open_social_float_button_top').click(function() {
        $('html,body').animate({
            scrollTop: '0px'
        },
        800);
    });
    $('#open_social_float_button_comment').click(function() {
        $('html,body').animate({
            scrollTop: $('#respond').offset().top
        },
        800);
    });
});
