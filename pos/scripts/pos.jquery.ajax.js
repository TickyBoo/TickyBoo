 $(document).ready(function(){
	var bindForms = function(){
		$("form").submit(function(){
			$(this).ajaxSubmit({
				success: function(html){
					$("#right").html(html);
				}
			});
			return false;
		});
	}
	
	bindForms();
	
	$("#btn_home").click(function(){
		$("#right").load("index.php?action=home");
	});
	$("#btn_order").click(function(){
		$("#right").load("index.php?action=calendar");
	});
 	
 	$('.link').live("click",function () { 
    	// Your code here. You should be able to get the href variable and
    	// do your ajax request based on it. Something like:
    	var url = $(this).attr('href');
   		$.ajax({
        	type: "GET",
        	url: url,
        	cache:false,
        	success: function(html){
        		$("#right").html(html);
        	}
    	});
    	return false; // You need to return false so the link
				// doesn't actually fire.
	});
	
	$().ajaxSend(function(evt, request, settings){
		$(".loading").show();	
	});
	
	$().ajaxStop(function(evt, request, settings){  
		$(".loading").fadeOut("fast");  
		bindForms();
	});
 });