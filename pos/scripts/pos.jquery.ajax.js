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
        		clearInterval(refreshTimer);
        		$("#right").html(html);
        	}
    	});
	   	//$.ajax({});
		return false;
	});
}
 
 var loadOrder = function(){
 	
 	ajaxQManager.add({
		type: "POST",
    	url: "index.php",
    	data: {ajax:'yes',page:"cart_content"},
    	cache:false,
    	success: function(html){
    		$("#cart-table tbody:first").html(html);
    	}
	});
	
	$("#event-input").autocomplete('index.php?ajax=yes&page=get_events', {
		width: 400,
		multiple: false,
		matchContains: true,
		minChars:0,
		mustMatch:true,
		formatItem: formatItem,
		formatResult: formatResult
	});
	
	$("#event-input").result(function(event, data, formatted) {
		if (data){
			$("#event-id").val(data[0]);
		}else{
			$("#event-id").val('0');
		}
		if(data[0] > 0){
			ajaxQManager.add({
				type: "POST",
		    	url: "index.php",
		    	data: {ajax:'yes',page:"get_catagories",event_id:data[0]},
		    	cache:false,
		    	success: function(html){
		    		$("#cat-select").fadeOut("fast").html(html).fadeIn("fast");
		    	}
			});
		}else{
			$("#cat-select").html("<option value='0'>No Event Selected</option>");
		}
	});
	
	refreshTimer = setInterval(function(){refreshOrder();}, 10000);
}

//The refresh orderpage, the ajax manager SHOULD ALLWAYS be used where possible.
var refreshOrder = function(){
	ajaxQManager.add({
		type: "POST",
    	url: "index.php",
    	data: {ajax:'yes',page:"cart_content"},
    	cache:false,
    	success: function(html){
    		$("#cart-table tbody:first").fadeOut("fast").html(html).fadeIn("fast");
    	}
	});
}
 
function formatItem(row) {
	return row[1];
}
function formatResult(row) {
	return row[1].replace(/(<.+?>)/gi, '');
}

//Creates a auto refreshing function.
var refreshTimer = setInterval(function(){refreshOrder();}, 10000);