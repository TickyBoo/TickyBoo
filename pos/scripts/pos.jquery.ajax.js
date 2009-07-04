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
var ajaxQManager = $.manageAjax.create('ajaxQMan',{
	queue:true,
	abortOld:true,
	maxRequests: 10,
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
 
 var loadOrder = function(){
 	
 	ajaxQManager.add({
	//$.ajax({
		type: "POST",
    	url: "index.php",
    	data: {ajax:'yes',page:"cart_content"},
    	cache:false,
    	success: function(html){
    		$("#cart-table tbody:first").html(html);
    	}
	});
	
	$("#event-input").autocomplete('index.php?ajax=yes&page=get_events', {
		autoFill:true,
		cacheLength:1,
		width: 400,
		multiple: false,
		matchContains: true,
		minChars:0,
		mustMatch:false,
		formatItem: formatItem,
		formatResult: formatResult,
		selectFirst:false
	});
	
	$("#event-input").result(function(event, data, formatted) {
		if (data){
			$("#event-id").val(data[0]);
		}else{
			$("#event-id").val('0');
		}
		if(data[0] > 0){
			ajaxQManager.add({
			//$.ajax({
				type: "POST",
		    	url: "index.php",
		    	data: {ajax:'yes',page:"get_catagories",event_id:data[0]},
		    	cache:false,
		    	success: function(html){
		    		$("#cat-select").fadeOut("fast").html(html).fadeIn("fast").change();
		    		
		    	}
			});
		}else{
			$("#cat-select").html("<option value='0'>No Event Selected</option>");
		}
	});
	
	$("#cat-select").change(function(){
		if($("#event-id").val() > 0 && $("#cat-select").val() > 0 ){
			ajaxQManager.add({
			//$.ajax({
				type:"POST",
				url: "index.php",
				data: {ajax:'yes',page:"get_catagories",event_id:$("#event-id").val(), category_id:$("#cat-select").val()},
				cache:false,
				success:function(html){
					html = $.trim(html);
					if(html.length > 100){
						$("#qty-td").html("");
						$("#seat-chart").html(html).show('blind','normal');
					}else{
						$("#seat-chart").hide('blind','normal').html("");
						$("#qty-td").html(html);
					}
				}
			});	
		}	
	});
	
	refreshTimer = setInterval(function(){refreshOrder();}, 10000);
	
	
	//Make sure all add ticket fields are added to this so when clearing selection 
	// All fields are reset.
	$('#clear-button').click(function(){
		$('#event-input').val('');
		$('#event-id').val('0');
		$("#cat-select").html("<option value='0'>No Event Selected</option>");
		$("#qty-td").html("");
		$("#seat-chart").hide('blind','normal').html("");
		$("#continue-div").hide('blind','normal');
	});
}

//The refresh orderpage, the ajax manager SHOULD ALLWAYS be used where possible.
var refreshOrder = function(){
	ajaxQManager.add({
	//$.ajax({
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