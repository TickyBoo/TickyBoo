{include file="header.tpl"}
<script type="text/javascript">
{literal}
	$(document).ready(function(){
		loadOrder();	
	});
{/literal}
</script>
<div id="order-div" style="width:100%;">
   <br />
<form id="order-form" name='addtickets' action='index.php' method='post'>

	<table width="100%" cellpadding='2' cellspacing='2' bgcolor='white' >
{*    <tr>
      <td colspan="2" class="title">
          {!pos_booktickets!}
      </td>
    </tr> *}
		<tbody>
			<tr>
				<td width="120" class='user_item'>{!event!}:</td>
				<td class='user_value' >
					Date From (yyyy-mm-dd) : <input type="text" id="event-from" size="10" />
					Date To (yyyy-mm-dd) : <input type="text" id="event-to" size="10" /><br />
					<select id="event-id" name="event_id" size="1">
					</select>
				</td>
			</tr>
			<tr>
				<td class='user_item'>{!select_category!}:</td>
				<td class='user_value'>
					<select name='category_id' id='cat-select' style="width:250px;">
						<option value='0'></option>
					</select>
				</td>
			</tr>
			<tr id='discount-name' {* style="display:none;" *}>
				<td class='user_item' >{!discounts!}:</td>
				<td class='user_value'>
					<select name='discount_id' id='discount-select' style="width:250px;"> {*display:none;      *}
						<option value='0'></option>
					</select>
				</td>
			</tr>
			<tr>
				<td id="qty-name" style="" class='user_item'>{!tickets_nr!}:</td>
				<td class='user_value' class="seat-selection" >
					<div id="show-seats" style="display:none;">
						<button type="button" name='submit' value='show seating'>{!show_seats!} </button>
						Seat Count: <input type="text" size="2" />
					</div>
					<div id="seat-qty" style="display:none;"><input type='text' name='place' size='4' maxlength='2' /></div>
				</td>
			</tr>
			<tr>
				<td align="left"></td>
				<td class='' align='right'>
				 	<button type="button" id="clear-button">{!clear_selection!}</button>&nbsp;
					<button type="submit" id="continue" name='submit' value='submit'>{!add_tickets!}</button>
				</td>
			</tr>
		</tbody>
	</table>

 	<br />


<div id="continue-div" style="width:100%; overflow:auto;"></div>
<div id="seat-chart" title='{!select_seat!}'></div>
</form>

<table id="cart_table" class="scroll" cellpadding="2" cellspacing="2"></table>

<br />
  <div id="error-message" title='{!order_error_message!}' class="ui-state-error ui-corner-all" style="display:none; padding: 0 .7em;" >
     <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		  	<strong>Alert: </strong><span id='error-text'>Sample ui-state-error style.</span>
     </p>
  </div>
  <table width='100%'>
  	<tr>
    	<td valign='top'  width='50%'>
          <form>
         		{include file='user.tpl'}
          </form>
     	</td>
     	<td valign='top' align='right'>
        <form>
  		   	<table id='handling-table' width='99%' cellspacing='2' cellpadding='2'  bgcolor='white'>
    				<thead>
    					<tr>
    	  		    <td colspan='3' class='title' align='left'>{!handlings!}</td>
  		    		</tr>
  		     	</thead>
  		    	<tbody id='handling-block'>
    					{include file='handlings.tpl'}
  		    	</tbody>
  		    </table>
        </form>

   		</td>
  	</tr>
  </table>
<br />
<table width='100%'>
	<tr>
    	<td class='title' align='left'>
      		{!ordertickets!}
    	</td>
  	</tr>
  	
</table>
<form >
  <button type='button' id='checkout' name='action' value='PosCheckout'>{!order_it!}</button>
{* update->view event_date=$min_date user=user->user_id *}
{* if $update_view.can_reserve
         &nbsp;
	<button type='button' id='reserved' name='action' value='PosReserved'>{!reserve!}</button>
 /if
        *}
  &nbsp;
  <button type='button' id='cancel' name='action' value='PosCancel' onclick='return confirmSubmit()'>{!cancel!}</button>
  <div id="order_action" title='{!Order_page!}'></div>
</form>
</div>



<br> <br>

{include file="footer.tpl"}
