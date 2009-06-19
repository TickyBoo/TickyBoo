<script type="text/javascript">
{literal}
	$(document).ready(function(){
		loadOrder();	
	});
{/literal}
</script>
<h2>{!pos_booktickets!}</h2>
<table id="cart-table">
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
	<tbody>
		<tr>
			<td>{!event!}:</td>
			<td>
				<input type="text" id="event-input" />
				<input type="hidden" id="event-id" />
			</td>
			<td>
		   		{!select_category!}:
			</td>
			<td>
				<select name='category_id' id='cat-select'>
				
				</select>
			</td>
		</tr>
	</tbody>
</table>
