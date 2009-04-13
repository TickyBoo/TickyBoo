<?PHP
/*
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
 
 */

require_once("classes/ShopDB.php");
require_once("classes/Order.php");
require_once("classes/Event.php");


class Trash {
	
	function stats(){
	  global $_SHOP;
		
		$res=array('event'=>0,'seat'=>0,'order'=>0);
		
		$query="select count(event_id) as count 
						from Event 
						where event_status='trash'";
						
		if($data=ShopDB::query_one_row($query)){
		  $res['event']=$data['count'];
		}				

		$query="select count(seat_id) as count 
						from Seat 
						where seat_status='trash'";
						
		if($data=ShopDB::query_one_row($query)){
		  $res['seat']=$data['count'];
		}				

				$query="select count(order_id) as count 
						from `Order` 
						where order_status='trash'";
						
		if($data=ShopDB::query_one_row($query)){
		  $res['order']=$data['count'];
		}				
		
		return $res;

	}
	
	function empty_trash(){
	  Order::toTrash();
		Event::emptyTrash();
		Order::emptyTrash();
	}
}

?>