 $(document).ready(function(){
	
	$(document).checkboxAreaSelect();
	bindForms();
	bindLinks();
	
	
	$().ajaxSend(function(evt, request, settings){
		$(".loading").show();	
	});
	
	$().ajaxStop(function(evt, request, settings){  
		$(".loading").fadeOut("fast");
		//console.log("stop");
		bindForms();
		bindLinks();
	});
 });
 
var ajaxQManager = $.manageAjax.create('ajaxQMan',{
	queue:true,
	abortOld:true,
	maxRequests: 10,
	cacheResponse: false
});

var bindForms = function(){
	$("form").submit(function(){
		var form = $(this);
		if($(form).attr("id") == "order-form"){
    		return false;
    	}
		$(form).ajaxSubmit({
			data:{ajax:"yes"},
			success: function(html){
				if($(form).hasClass("remove-tickets")){refreshOrder();return false;}
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
    	$("#seat-chart").each(function(){
    		$(this).remove();	
    	});
   		ajaxQManager.clear();
   		ajaxQManager.add({
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