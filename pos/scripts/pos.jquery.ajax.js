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
	cacheResponse: false,
});

var ajaxQManager = $.manageAjax.create('ajaxQMan',{
	queue:true,
	abortOld:true,
	maxRequests: 10,
	cacheResponse: false,
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
   		ajaxManager.clear();
   		ajaxManager.add({
			type: "GET",
        	url: url,
        	data: {ajax:'yes'},
        	cache:false,
        	success: function(html){
        		$("#right").html(html);
        	}
    	});
	   	//$.ajax({});
		return false;
	});
}
 
 var loadOrder = function(){
 	
 	ajaxManager.add({
		type: "POST",
    	url: "index.php",
    	data: {ajax:'yes',page:"cart_content"},
    	cache:false,
    	success: function(html){
    		$("#cart-table tbody:first").html(html);
    	}
	});
	$("#event-input").autocomplete('index.php?ajax=yes&page=events', {
		width: 300,
		multiple: true,
		matchContains: true,
		formatItem: formatItem,
		formatResult: formatResult
	});
 	
 }
 
function formatItem(row) {
	return row[0] + " (<strong>id: " + row[1] + "</strong>)";
}
function formatResult(row) {
	return row[0].replace(/(<.+?>)/gi, '');
}