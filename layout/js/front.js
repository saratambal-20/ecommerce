$(function(){
'use strict';
// switch between login & signup
$(".login-page h1 span").click(function(){
	$(this).addClass('selected').siblings().removeClass('selected');
	$('.login-page form').hide();
	$('.' + $(this).data('class')).fadeIn(100);
});


// trigger the selectbox
  $("select").selectBoxIt({
  	autoWidth:false
  });


// hide placeholder in focus
$('[placeholder]').focus(function(){
	$(this).attr('data-text',$(this).attr('placeholder'));
	$(this).attr('placeholder','');
}).blur(function(){
$(this).attr('placeholder',$(this).attr('data-text'));
});
// add Asterisk on required field
$('input').each(function(){
if($(this).attr('required')==='required'){
	$(this).after('<span class="asterisk">*</span>');
}

});

// confirmation
$('.confirm').click (function() {
	return confirm('are you sure?');
});

$('.live').keyup(function(){

	$($(this).data('class')).text($(this).val());

});
});
