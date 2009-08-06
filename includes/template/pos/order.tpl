<script type="text/javascript">
{literal}
	$(document).ready(function(){
		loadOrder();	
	});
{/literal}
</script>
<form id="order-form" name='addtickets' action='index.php' method='post'>
<div id="order-div" style="width:100%;">
	<h2>{!pos_booktickets!}</h2>
	<table width="100%" cellpadding='1' cellspacing='1' bgcolor='white' >
		<tbody>
			<tr>
				<td width="30%" class='admin_list_row_1'>{!event!}:</td>
				<td width="70%" class='admin_list_row_0' >
					<input type="text" id="event-input" size="40" style="width:250px;" />
           <button type="button" id="clear-button">{!clear_selection!}</button>
					<input type="hidden" id="event-id" name="event_id" />
				</td>
			</tr>
			<tr>
				<td class='admin_list_row_1'>{!select_category!}:</td>
				<td  class='admin_list_row_0'>
					<select name='category_id' id='cat-select' style="width:250px;">
						<option value='0'></option>
					</select>
				</td>
			</tr>
			<tr id='discount-name' style="display:none;">
				<td class='admin_list_row_1' >{!discounts!}:</td>
				<td  class='admin_list_row_0'>
					<select name='discount_id' id='discount-select' style="display:none; width:250px;">
						<option value='0'></option>
					</select>
				</td>
			</tr>
			<tr>
				<td id="qty-name" style="display:none;" class='admin_list_row_1'>{!tickets_nr!}:</td>
				<td class='admin_list_row_0' class="seat-selection" >
					<div id="show-seats" style="display:none;">
						<button type="button" name='submit' value='show seating'>{!show_seats!} </button>
					</div>
					<div id="seat-qty" style="display:none;"><input type='text' name='place' size='4' maxlength='2' /></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='admin_list_row_0' align='right'>
					<button type="button" id="continue" name='submit' value='submit'>{!add!} {!tickets!}</button>
				</td>
			</tr>
		</tbody>
	</table>

	<table id="cart-table" width="100%">
		<thead>
			<tr>
				<td>{!event!}</td>
				<td>{!tickets!}</td>
				<td>{!total!}</td>
				<td>{!expires_in!}</td>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<div id="continue-div" style="width:100%; overflow:auto;">
</div>
<div id="seat-chart"></div>
<div style="float:left; overflow:auto; width:50%;">
	Personal details
</div>
<div style="float:left; overflow:auto; width:50%;">
	Payment Method
</div>
</form>