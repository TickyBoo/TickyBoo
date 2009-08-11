var loadUser = function(){
	$('#search_user').hide();

	$('#users-table').jqGrid({
		url:'ajax.php',
		datatype: 'json',
		mtype: 'POST',
		postData: {"pos":true,"action":"UserSearch"},
		colNames:['{!user_id!}','{!user_name!}','{!user_zip!}','{!user_city!}','{!user_email!}'],
		colModel :[
			{name:'user_id',    index:'User_id',    width:52},
			{name:'user_name',  index:'user_name',  width:152},
			{name:'user_zip',   index:'user_zip',   width:102},
			{name:'user_city',  index:'user_city',  width:235},
			{name:'user_email', index:'user_email', width:202} ],
		altRows: true,
		height: 250,
		hiddengrid : true,
		footerrow : true,
		viewrecords: true
    });
	
	$('#user_info_none').change(function(){
    	$("#user_data :input").each(function() {
        	$(this).val('');
      	});
    	$('#search_user').hide();
    	$('#user_data').hide();
      	$('#user_id').val(-1);
    });
	
	$('#user_info_search').change(function(){
    	$('#search_user').show();
    	$('#user_data').show();
    	
	    if ($('#user_id').val() <=0) {
	    	$('#user_id').val(-2);
     	}
    });
	
	$('#user_info_new').change(function(){
    	$('#search_user').hide();
    	$('#user_data').show();
      	if (($('#user_id').val() <=0) || confirm('Are you sure you want to create a new user?')) {
        	$("#user_data :input").each(function() {
           		$(this).val('');
        	});
        	$('#user_id').val(0);
      	} else {
        	$('#user_info_search').change();
        	$('#user_info_search').click();
      	}
    });
    
	$("#search-dialog").dialog({
		bgiframe: false,
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		resizable  : false,
		buttons: {
    		'Cancel': function() {
				$(this).dialog('close');
			 },
      		'Ok': function() {
         		var selrow = $('#users-table').getGridParam("selrow");
   			 	ajaxQManager.add({
					type:		"POST",
					url:		"ajax.php",
					dataType:	"json",
					data:		{"pos":true,"action":"UserData",'user_id':selrow},
					success:function(data, status){
              			$.each(data.user, function(i,item){
                 			$("#"+i).val(item);
              			});
 						$("#search-dialog").dialog('close');
           			}
			  	});
		  	}
		}
	});
  	$('#search_user').click(function() {
    	$('#users-table').clearGridData();
      	var data = $('#users-table').getGridParam('postData'), i=0;
      	$("#user_data :input").each(function() {
        	if ($(this).attr("name") != 'user_id') {
           		data[$(this).attr("name")] = $(this).val();
           		if ($(this).val().length >1 ) {
           			i++;
           		}
       		}
      	});
      	if ( i >2) {
	        $('#users-table').setGridParam('postData', data);
	        $('#users-table').trigger("reloadGrid");
	        $("#search-dialog").dialog('open');
      	} else {
			alert('You need to fill atliest 3 personal address fields,\n with minimal 2 characters, before you can search.');
      	}
	});
}

function array_length(arr) {
	var length = 0;
	for(val in arr) {
	    length++;
	}
	return length;
}