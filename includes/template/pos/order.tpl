<script type="text/javascript">
{literal}
	$(document).ready(function(){
		loadOrder();	
	});
{/literal}
</script>
<form id="order-form" name='addtickets' action='index.php' method='post'>
<div style="width:100%;">
	<h2>{!pos_booktickets!}</h2>
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
	<table width="100%">
		<tbody>
			<tr>
				<td width="20%">{!event!}:</td>
				<td colspan="5">
					<input type="text" id="event-input" size="40" />
					<input type="hidden" id="event-id" name="event_id" />
				</td>
			</tr>
			<tr>
				<td>{!select_category!}:</td>
				<td>
					<select name='category_id' id='cat-select' style="width:210px;">
						<option value='0'></option>
					</select>
				</td>
				<td id='discount-name' style="display:none;">{!discounts!}:</td>
				<td>
					<select name='discount_id' id='discount-select' style="display:none; width:180px;">
						<option value='0'></option>
					</select>
				</td>
				<td id="qty-name" style="display:none;">{!tickets_nr!}:</td>
				<td id="qty-td">
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">
					<button type="button" style="display:none;" id="show-seats" name='submit' value='show seating'>{!show_seats!} </button>
					<button type="button" id="continue" name='submit' value='submit'>{!add!} {!tickets!}</button>
					<button type="button" id="clear-button">{!clear_selection!}</button>
				</td>
			</tr>
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