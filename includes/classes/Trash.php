<?PHP
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 */

require_once("classes/Order.php");
require_once("classes/Event.php");
require_once("classes/User.php");


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
		$res['guests']= User::cleanup();
		
		return $res;

	}
	
	function empty_trash(){
	  Order::toTrash();
		Event::emptyTrash();
		Order::emptyTrash();
		User::cleanup(0,true);
	}
}

?>