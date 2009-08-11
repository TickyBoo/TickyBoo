{*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 *}
<script type="text/javascript">
{literal}

	$(document).ready(function(){
  	$('#search_user').hide();
  	$('#users-table').jqGrid({
      url:'ajax.php',
      datatype: 'json',
      mtype: 'POST',
      postData: {"pos":true,"action":"UserSearch"},
      {/literal}
      colNames:['{!user_id!}','{!user_name!}','{!user_zip!}','{!user_city!}','{!user_email!}'],
      {literal}
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

		})
	});
function array_length(arr) {
    var length = 0;
    for(val in arr) {
        length++;
    }
    return length;
}
{/literal}
</script>


  <table width='99%' border='0' cellspacing='1' cellpadding='5' align='left' >
    <thead>
    
    <tr>
      <td colspan="2" class="title">
          {!pers_info!}
      </td>
    </tr>
    <tr>
      <td class='user_item' colspan='2'>
        <table width='100%' border='0' cellspacing='0' cellpadding='0' >
          <tr>
            <td class='user_item'  > &nbsp;
         		  <input checked="checked" type='radio' id='user_info_none' class='checkbox_dark' name='user_info' value='0'>
       		  	<label for='user_info_none'> {!none!} </label>
           </td>
            <td class='user_item'  >
         		  <input type='radio' id='user_info_new' class='checkbox_dark' name='user_info' value='2'>
       		  	<label for='user_info_new'> {!new_partron!} </label>
      	     </td>
            <td  class='user_item' >
         		  <input type='radio' id='user_info_search' class='checkbox_dark' name='user_info' value='1'>
       		  	<label for='user_info_search'> {!exst_user!} </label>
           </td>
            <td class='user_item'  align  ='right' width='100'>
      		    <button type="button" id="search_user" name='action' value='search_user'>{!search!}</button>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    </thead>
    <tbody id='user_data' name='user_data' style="display:none;">
        {gui->setdata data=$user_data errors=$user_errors nameclass='user_item' valueclass='user_value' namewidth='120'}
        {gui->input name='user_firstname' mandatory=true size='30' maxlength='50'}
        {gui->input name='user_lastname' mandatory=true size='30' maxlength='50'}
        {gui->input name='user_address' mandatory=true size='30' maxlength='75'}
        {gui->input name='user_address1' size='30' maxlength='75'}
        {gui->input name='user_zip' mandatory=true size='8' maxlength='20'}
        {gui->input name='user_city' mandatory=true size='30' maxlength='50'}
        {gui->selectstate name='user_state'}
        {gui->selectcountry name='user_country' mandatory=true}
        {gui->input name='user_phone' size='15' maxlength='50'}
        {gui->input name='user_fax' size='15' maxlength='50'}
        {gui->input name='user_email' mandatory=true size='30' maxlength='50' }
        <input type='hidden' name='user_id' value='-1'>
    </tbody>
    <tr>
      <td class='user_item' height='16' width='120'>
         {!without_fee!}
      </td>
      <td  class='user_value'>
        <input type='checkbox' class='checkbox' name='no_fee' value='1'>
      </td>
    </tr>
  </table>
  <div id="search-dialog" title="Personal Search dialog">
   	<table id="users-table" class="scroll" cellpadding="0" cellspacing="0"></table>
   	
  </div>
