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
   	$('#user_info_search').change(function(){
    	$('#search_user').show();
    	$('#user_data').show();
      $('#event_user_id').val(-1);
    });
   	$('#user_info_none').change(function(){
    	$('#search_user').hide();
    	$('#user_data').hide();
      $('#event_user_id').val(0);
    });
   	$('#user_info_new').change(function(){
    	$('#search_user').hide();
    	$('#user_data').show();
      $('#event_user_id').val(-2);
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
			   $(this).dialog('close');
			 }
		}
	});
  $('#search_user').click(function() {
      var FormValues = {};
      $("#user_data :input").each(function() {
         if ($(this).val()) {
           FormValues[$(this).attr("name")] = $(this).val();
         }
      });
      if ( array_length(FormValues) >2) {
        var str = $.toJSON(FormValues)
  			ajaxQManager.add({
  				type:		"POST",
  				url:		"ajax.php",
  				dataType:	"json",
  				data:		{"pos":true,"action":"UserSearch",'values':str},
  				success:function(data, status){

  	        $("#user-table tbody:first").hide().html("");
  					$.each(data.users,function(){
  						$("#user-table tbody:first").append(this.html);
  					});
  					$("#user-table tbody:first").show().change();
  			    $('#search-dialog').dialog('open');
  				}
  			});
      } else {
        confirm('You need to enter atliest 3 personal address field before you can search.');
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
            <td  class='user_item' >
         		  <input type='radio' id='user_info_search' class='checkbox_dark' name='user_info' value='1'>
       		  	<label for='user_info_search'> {!search!} </label>
           </td>
            <td class='user_item'  >
         		  <input type='radio' id='user_info_new' class='checkbox_dark' name='user_info' value='2'>
       		  	<label for='user_info_new'> {!new_partron!} </label>
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

    </tbody>
    <tr>
      <td class='user_item' height='16' width='120'>
         {!without_fee!}
      </td>
      <td  class='user_value'>
        <input type='checkbox' class='checkbox' name='no_fee' value='1'>
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='hidden' name='event_user_id' value='0'>
 	     </td>
    </tr>
  </table>
 <div id="search-dialog" title="Personal Search dialog">
  <div style='border:1px solid #840; width:100%'>
  	<table id="user-table-header" width="100%" >
  		<thead>
  			<tr class='festival'>
  				<th class='festival' width='152'>{!user_name!}</th>
          <th class='festival' width='102'>{!user_zip!}</th>
  				<th class='festival' width='235'>{!user_city!}</th>
  				<th class='festival' width='202'>{!user_email!}</th>

  			</tr>
  		</thead>
  	</table>
    <div style='overflow-y: scroll; height: 295px;  width:100%'>

  	<table id="user-table" width="100%" >
  		<tbody>
  		</tbody>
  	</table>
  </div>
</div>

</div>