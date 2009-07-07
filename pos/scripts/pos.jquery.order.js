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
			$("#cat-select").html("<option value='0'></option>");
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
						$("#seat-qty").hide();
						$("#qty-name").hide();
						$("#seat-chart").html(html);
						bindSeatChart();
					}else{
						unBindSeatChart();
						$("#seat-chart").html("");
						$("#qty-name").show();
						$("#seat-qty").show();
						
					}
					$("#continue").attr("type","submit");
				}
			});
			ajaxQManager.add({
				type:"POST",
				url: "index.php",
				data: {ajax:'yes',page:"get_discounts", category_id:$("#cat-select").val()},
				cache:false,
				success:function(html){
					html = $.trim(html);
					if(html.length > 20){						
						$("#discount-name").show();
						$("#discount-select").html(html).show();
					}else{
						$("#discount-name").hide();
						$("#discount-select").hide().html("<option value='0'></option>");
					}
				}
			});
			
		}	
	});
	
	refreshTimer = setInterval(function(){refreshOrder();}, 30000);
	
	
	//Make sure all add ticket fields are added to this so when clearing selection 
	// All fields are reset.
	$('#clear-button').click(function(){
		$('#event-input').val('');
		$('#event-id').val('0');
		$("#cat-select").html("<option value='0'></option>");
		$("#discount-select").hide().html("<option value='0'></option>");
		$("#discount-name").hide();
		$("#qty-name").hide();
		$("#seat-qty").hide();
		$("#seat-chart").html("");
		$("#continue").attr("type","button");
		unBindSeatChart();
		
	});
	
	$("#seat-chart").dialog({
		bgiframe: false,
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		buttons: {'Close': function() {
			$(this).dialog('close');
			}
		}
	});
	
	$("#order-form").submit(function(){
		$(this).ajaxSubmit({
			data:{ajax:"yes",action:"addtocart"},
			success: function(html){
				console.log(html);
				refreshOrder();
				refreshSeatChart();
			}
		});
		return false;
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
    		$("#cart-table tbody:first").html(html);
    	}
	});
}

var refreshSeatChart = function(){
	if($("#seat-chart").html().length < 200){
		return false;
	}
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
					$("#seat-chart").html(html);
				}
			}
		});
	}
}

var bindSeatChart = function(){
	$("#show-seats").show();
	$("#show-seats button").click(function(){
		$("#seat-chart").dialog('open');
	});
}
var unBindSeatChart = function(){
	//$("#seat-chart").dialog('destroy');
	$("#show-seats").hide();
	$("#show-seats button").unbind( "click" );
}

//Creates a auto refreshing function.
var refreshTimer = setInterval(function(){refreshOrder();}, 30000);

function formatItem(row) {
	return row[1];
}
function formatResult(row) {
	return row[1].replace(/(<.+?>)/gi, '');
}