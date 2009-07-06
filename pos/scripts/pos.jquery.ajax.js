 $(document).ready(function(){
	
	$(document).checkboxAreaSelect();
	bindForms();
	bindLinks();
	
	
	$().ajaxSend(function(evt, request, settings){
		$(".loading").show();	
	});
	
	$().ajaxStop(function(evt, request, settings){  
		$(".loading").fadeOut("fast");  
		bindForms();
		bindLinks();
	});
 });
 
var ajaxManager = $.manageAjax.create('ajaxMan',{
	queue:true,
	abortOld:true,
	maxRequests: 4,
	cacheResponse: false
});
var bindForms = function(){
	$("form").submit(function(){
		$(this).ajaxSubmit({
			data:{ajax:"yes"},
			success: function(html){
				$("#right").html(html);
			}
		});
		return false;
	});
}

var bindLinks = function(){
	//$("a:not([href^='http'])").click(function () { //does not work after rebind in ie8.
	$("a").click(function () {
    	var url = $(this).attr('href');
    	if($(this).hasClass("ui-dialog-titlebar-close")){
    		return false;
    	}
   		ajaxManager.clear();
   		ajaxManager.add({
		//$.ajax({
			type: "GET",
        	url: url,
        	data: {ajax:'yes'},
        	cache:false,
        	success: function(html){
        		clearInterval(refreshTimer);
        		$("#right").html(html);
        	}
    	});
	   	//$.ajax({});
		return false;
	});
}