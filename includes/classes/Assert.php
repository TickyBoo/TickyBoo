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

  class Assert{

    function run_all ($show=FALSE){
      $res=TRUE;
      
      if(!Assert::free_seat_ticket()){$str.= "<div class=error>free_seat_ticket</div>";$res=FALSE;}
      if(!Assert::ticket_no_place()){$str.= "<div class=error>ticket_no_place</div>";$res=FALSE;}
      if(!Assert::ticket_place_integrity()){$str.= "<div class=error>ticket_place_integrity</div>";$res=FALSE;}
      if(!Assert::ticket_category_price()){$str.= "<div class=error>ticket_category_price</div>";$res=FALSE;}
      if(!Assert::com_seat_no_ticket()){$str.= "<div class=error>com_seat_no_ticket</div>";$res=FALSE;}
      if(!Assert::ticket_order_price()){$str.= "<div class=error>ticket_order_price</div>";$res=FALSE;}
      if(!Assert::two_tickets_one_seat()){$str.= "<div class=error>two_tickets_one_seat</div>";$res=FALSE;}
      if(!Assert::tickets_per_order()){$str.= "<div class=error>tickets_per_order</div>";$res=FALSE;}
      if(!Assert::stats_per_event()){$str.= "<div class=error>stats_per_event</div>";$res=FALSE;}
      if(!Assert::stats_per_category()){$str.= "<div class=error>stats_per_category</div>";$res=FALSE;}
      if(!Assert::free_places_stats()){$str.= "<div class=error>free_places_stats</div>";$res=FALSE;}

      if(!$res and $show){
        echo "$str<script>alert('assertion failed')</script>";
      }
      
      return $res;
    }  
    
    /* tickets for free seats */
    function free_seat_ticket (){
      $query="SELECT count(*) as count
              FROM Ticket,Place  
              WHERE ticket_place_id = place_id and place_status = 'free'";    
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['count']==0;
    }
    
    function ticket_no_place (){
      $query="    SELECT count(  *  ) as count
      		  FROM Ticket
                  LEFT  JOIN Place ON ticket_place_id = place_id
      		  WHERE place_id IS  NULL";    
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['count']==0;
    }
    
    /*order_price=sum(ticket_price)+fee */
    function ticket_order_price (){
   $query = 'SELECT order_id, 
   	abs( ( sum( ticket_price ) - order_total_price + order_fee ) ) AS delta 
	FROM Ticket, `Order` 
	WHERE ticket_order_id = order_id 
	GROUP BY order_id 
	ORDER BY delta DESC LIMIT 1';    
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['delta']==0;
    }
    
    /*ticket price= category_price-discount */
    function ticket_category_price (){
    $query = "SELECT count( * ) as count 
    		FROM Ticket,Discount,Category 
		WHERE ticket_discount_id = discount_id and 
		ticket_category_id = category_id and 
		discount_type='percent' and
		abs( ticket_price - category_price * ( 1 - cast( discount_value as signed ) / 100.0 ) ) > 0 ";    

      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	$count_percent=0;
      }else{
        $count_precent=$res['count'];
      }

    $query = "SELECT count( * ) as count 
    		FROM Ticket,Discount,Category 
		WHERE ticket_discount_id = discount_id and 
		ticket_category_id = category_id and 
		discount_type='fixed' and
		ticket_price - discount_value != category_price  ";    
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	$count_fixe=0;
      }else{
        $count_fixe=$res['count'];
      }

    $query = "SELECT count( * ) as count 
    		FROM Ticket LEFT JOIN Discount ON ticket_discount_id = discount_id ,Category 
		WHERE  
		ticket_category_id = category_id and 
		discount_type IS NULL and
		ticket_price != category_price";    

      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	$count_full=0;
      }else{
        $count_full=$res['count'];
      }
      
      return ($count_fixe+$count_percent+$count_full)==0;
    }
    
    /*user_id, category, event should be the same for both ticket and place */
    function ticket_place_integrity (){
    $query="SELECT count(  *  ) as count
    FROM Ticket,Place WHERE
    ticket_place_id = place_id and
    (ticket_event_id!=place_event_id or
    ticket_category_id!=place_category_id or
    ticket_user_id != place_user_id)";
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['count']==0;
    }
        
    /* tickets for free seats */
    function com_seat_no_ticket (){
      $query="SELECT count(*) as count
              FROM Place LEFT JOIN Ticket ON  ticket_place_id = place_id
              WHERE  ticket_id IS NULL and place_status = 'com'";    
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['count']==0;
    }
 

    /* two tickets for same seat */
    function two_tickets_one_seat (){
      $query="SELECT count(*) as count
              FROM Ticket
	      GROUP BY ticket_place_id
	      ORDER BY count DESC
	      LIMIT 1";
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['count']==1;
    }
    
    /* tickets per order */
    function tickets_per_order (){
      $query="SELECT order_id, abs(order_tickets_nr-count(ticket_id)) as delta 
              FROM `Order`, Ticket
              WHERE ticket_order_id = order_id
	      and order_status!='reemit' 
	      and order_status!='cancel'
              GROUP  BY order_id
              ORDER  BY  `delta`  DESC
	      LIMIT 1";
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['delta']==0;
    }
    
    function stats_per_event (){
      $query="SELECT es_event_id, (count(ticket_id)+es_free)-es_total as delta
              FROM Event_stat, Ticket
              WHERE ticket_event_id = es_event_id
              GROUP  BY es_event_id
              ORDER  BY  `delta`  DESC 
	      LIMIT 1";
    
      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['delta']==0;
    }

    function stats_per_category (){
      $query="SELECT cs_category_id, 
                     (count(ticket_id)+cs_free)-cs_total as delta
              FROM Category_stat, Ticket
              WHERE ticket_category_id = cs_category_id
              GROUP  BY cs_category_id
              ORDER  BY  `delta`  DESC 
              LIMIT 1";

      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
        return TRUE;
      }

      return $res['delta']==0;
    }
  
  
    function free_places_stats (){
      $query="SELECT count(*) as count FROM Place";
      if(!$res=ShopDB::query_one_row($query)){
        $places=0;
      }else{
        $places=$res['count'];
      }
    
      $query="SELECT sum(cs_total)as count FROM Category_stat";
      if(!$res=ShopDB::query_one_row($query)){
        $cs=0;
      }else{
        $cs=$res['count'];
        if(!$cs){$cs=0;}

      }

      $query="SELECT sum(es_total)as count FROM Event_stat";
      if(!$res=ShopDB::query_one_row($query)){
        $es=0;
      }else{
        $es=$res['count'];
	if(!$es){$es=0;}
      }

      if(!($cs==$es  and $cs==$places)){
        echo "cs: $cs, es: $es, places: $places";
	return FALSE;
      }else{
        return TRUE;
      }
    }

    function free_stats_cat_2 (){
      $query="SELECT cs_category_id, count( place_id )  - cs_free AS delta
              FROM Place, Category_stat
              WHERE place_status =  'free' AND place_category_id = cs_category_id
              GROUP  BY cs_category_id
              ORDER  BY delta DESC
              LIMIT 1"; 
    


      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['delta']==0;
 }


    function free_stats_event_2 (){
      $query="SELECT es_event_id, count( place_id )  - es_free AS delta
              FROM Place, Event_stat
              WHERE place_status =  'free' AND place_event_id = es_event_id
              GROUP  BY es_event_id
              ORDER  BY delta DESC
              LIMIT 1"; 
    


      if(!$res=ShopDB::query_one_row($query)){
        //user_error("assert query failed");
	return TRUE;
      }
      
      return $res['delta']==0;
 }
}
?>