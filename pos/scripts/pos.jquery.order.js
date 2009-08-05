var catData = new Object();

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
				type:		"POST",
				url:		"ajax.php",
				dataType:	"json",
				data:		{"pos":true,"action":"categories","event_id":data[0]},
				success:function(data, status){
					catData = data; //set cat var
					
					//Fill Categories
					$("#cat-select").hide().html("");
					$.each(data.categories,function(){
						$("#cat-select").append(this.html);
					});
					$("#cat-select").show().change();
				}	
			});
		}else{
			$("#cat-select").html("<option value='0'></option>");
		}
	});
	
	$("#cat-select").change(function(){
		if($("#event-id").val() > 0 && $("#cat-select").val() > 0 ){
			
			var catId = $("#cat-select").val();
			
			//Check catData for discounts...
			if(catData.enable_discounts){
				$("#discount-select").html("");
				$.each(catData.discounts,function(){
					$("#discount-select").append(this.html);
				});
				$("#discount-name").show();
				$("#discount-select").show();
			}else{
				$("#discount-name").hide();
				$("#discount-select").hide().html("<option value='0'></option>");
			}
			updateSeatChart();
			$("#continue").attr("type","submit");
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

/* refreshSeatChart
 * //Todo: Need to grab all the categories placemaps again after add
 * 		as the reserved seats appear on all the placemaps
 */
var refreshCategories = function(){
	if($("#event-id").val() > 0 ){
		var eventId = $("#event-id").val();
		
		ajaxQManager.add({
				type:		"POST",
				url:		"ajax.php",
				dataType:	"json",
				data:		{"pos":true,"action":"categories","categories_only":true,"event_id":eventId},
				success:function(data, status){
					if(data.status){
						catData.categories = data.categories; //set cat var
						updateSeatChart();
					}
				}	
			});
	}
}

var updateSeatChart = function(){
	var catId = $("#cat-select").val();
	unBindSeatChart();
	if(catData.categories[catId].numbering){
		$("#seat-qty").hide();
		$("#qty-name").hide();
		$("#seat-chart").html(catData.categories[catId].placemap);
		bindSeatChart();
	}else{
		unBindSeatChart();
		$("#seat-chart").html("");
		$("#qty-name").show();
		$("#seat-qty").show();
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