{event start_date=$smarty.now|date_format:"%Y-%m-%d" ort='on' sub='on' stats='on' order="event_date,event_time" search=$smarty.request.q }
	{$shop_event.event_id}|{$shop_event.event_name} - {$shop_event.ort_name} - {$shop_event.event_date|date_format:"%d.%I.%Y"}
{/event}