<?php
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

if (!defined('ft_check')) {die('System intrusion ');}
require_once("admin/AdminView.php");

class OrderView extends AdminView{
  var $page_length=15;

function order_prepare_delete ($order_id){
  echo "<center><form action='".$_SERVER["PHP_SELF"]."?action=cancel&order_id=$order_id' method='post'>
        <input type='submit' name='cancel' value='".con('cancel')."'></form></center><br>";
  $this->order_details ($order_id);
  echo "<br><center><form action='".$_SERVER["PHP_SELF"]."?action=cancel&order_id=$order_id' method='post'>
        <input type='submit' name='cancel' value='".con('cancel')."'></form></center><br>";

}

function order_details ($order_id){
  $query="select * from `Order`,User where order_id='$order_id' and order_user_id=user_id";
  if(!$order=ShopDB::query_one_row($query)){
    echo "<div class='error'>".con('order_not_found')." $order_id</div>";
    return;
  }

  $com = implode('&nbsp;&nbsp;',$this->order_commands($order,FALSE));;

  $status=$this->print_order_status($order);
  $order["order_status"]=$status;
  $order['order_responce_date'] =($order['order_responce_date']== '0000-00-00 00:00:00')?'':$order['order_responce_date'];

  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title'>".con('order_nr')."  ".$order_id."</td>
  <td align='right'><table width='100' style='border:#cccccc 1px solid;'><tr><td align='center'>
  $com
  </td></tr></table></td></tr>";

  $this->print_field('order_tickets_nr',$order);
  $this->print_field('order_fee',$order);
  $this->print_field('order_total_price',$order);
  $this->print_field('order_date',$order);

  $order['order_shipment_status']=con($order['order_shipment_status']);
  $order['order_payment_status']=con($order['order_payment_status']);

  $this->print_field('order_shipment_status',$order);
  $this->print_field('order_payment_status',$order);
  $this->print_field_o('order_payment_id',$order);
  $this->print_field('order_status',$order);
  $this->print_field_o('order_responce',con($order['order_responce']));
  $this->print_field_o('order_responce_date',$order);
  $this->print_field_o('order_note',clean($order['order_note']));
  echo "</table><br>\n";
/*
  echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
  echo "<input type='hidden' name='oid' value='".$order_id."'>";
  echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='0'>\n";
  echo "<tr><td align='center' width='90%'>";
  echo "<table width='100%' cellspacing='0' cellpadding='0' class='admin_form'>\n";
  $this->print_input('confirmation_number',$order, $err,25,100,' ');
  echo "</table>";
  echo "</td>";
  echo "<td align='center' width='10%'><table width='100%' cellspacing='0' cellpadding='0' class='admin_form'>";
  echo "<tr><td><input type='submit' name='submit' value='".submit."'></td></tr></table></td></tr>";
  echo "</table></form>";
*/

	$query="select * from Seat LEFT JOIN Discount ON Seat.seat_discount_id=Discount.discount_id
                        		 LEFT JOIN Event ON Seat.seat_event_id=Event.event_id
                        		 LEFT JOIN Category ON Seat.seat_category_id=Category.category_id
                        		 LEFT JOIN PlaceMapZone ON Seat.seat_zone_id=PlaceMapZone.pmz_id
    			WHERE Seat.seat_order_id="._esc($order_id);

  if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
  }
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='8'>".con('tickets')."</td></tr>";
  $alt=0;

  while($ticket=shopDB::fetch_assoc($res)){
    if((!$ticket["category_numbering"]) or $ticket["category_numbering"]=='both'){
      $place=$ticket["seat_row_nr"]."-".$ticket["seat_nr"];
    }else if($ticket["category_numbering"]=='rows'){
      $place=con('place_row')." ".$ticket["seat_row_nr"];
    }else if($ticket["category_numbering"]=='seat'){
      $place=con('place_seat')." ".$ticket["seat_nr"];
     }else{
       $place='---';
     }

    $t_com = implode('&nbsp;',$this->ticket_commands($order_id,$ticket['seat_id']));;

    echo "<tr class='admin_list_row_$alt'>
     	   <td class='admin_list_item'>".$ticket["event_name"]."</td>
     	   <td class='admin_list_item'>".formatAdminDate($ticket["event_date"], false).' '.
                                   	   formatTime($ticket["event_time"])."</td>
     	   <td class='admin_list_item'>".$ticket["category_name"]."</td>

     	   <td class='admin_list_item'>$place</td>
    	   <td class='admin_list_item'>".$ticket["discount_name"]."</td>

     	   <td class='admin_list_item' align='right'>".$ticket["seat_price"]."</td>
     	   <td class='admin_list_item' align='right'>".$t_com."</td>

 	   <tr>\n";
     $alt=($alt+1)%2;

   }
   echo "</table><br>\n";

   $order["user_country_name"]=$this->getCountry($order["user_country"]);
   $status=$this->print_status($order["user_status"]);
   $order["user_status"]=$status;

   echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
   echo "<tr><td class='admin_list_title' colspan='2'>".con('user_id')." ".$order["user_id"]."</td></tr>";

   $this->print_field('user_lastname',$order);
   $this->print_field('user_firstname',$order);
   $this->print_field('user_address',$order);
   $this->print_field('user_address1',$order);
   $this->print_field('user_zip',$order);
   $this->print_field('user_city',$order);
   $this->print_field('user_state',$order);
   $this->print_field('user_country',$order);
   $this->print_field('user_phone',$order);
   $this->print_field('user_fax',$order);
   $this->print_field('user_email',$order);
   $this->print_field('user_status',$order);

   echo "</table>\n";

 }

 function link ($action, $order_id, $img, $confirm=FALSE, $con_msg='',$param=null, $target=''){
   if($confirm){
     $param['action1']=$action;
     $param['order_id1']=$order_id;

     foreach($param as $key=>$val){
       $par.=$psep."$key=$val";
       $psep="&";
     }

     return "<a href='javascript:if(confirm(\"".con($con_msg)."\")){location.href=\"".$_SERVER['PHP_SELF']."?$par\";}'>".
          "<img border='0' src='../images/$img'></a>";
   }
   return "<a $target href='".$_SERVER['PHP_SELF']."?action=$action&order_id=$order_id'>".
          "<img border='0' src='../images/$img'></a>";
 }

 function get_limit ($page,$count=0){
   if(!$page){ $page=1; }
   $limit["start"]=($page-1)*$this->page_length;
   $limit["end"]=$this->page_length;
   return $limit;

 }

 function ticket_commands ($order_id,$ticket_id){
   $params=$_GET;
   $params['seat_id']=$ticket_id;
   $com["reissue"]=$this->link("reissue_ticket",$order_id,"remis.png",TRUE,con('reissue_ticket')." $ticket_id",$params);
   $com["delete"]=$this->link("delete_ticket",$order_id,"trash.png",TRUE,con('delete_ticket')." $ticket_id",$params);
   return $com;
 }


 function order_commands ($order,$list=TRUE){
     if($list){$com["details"]=$this->link("details",$order["order_id"],"view.png");}
     if($order['order_status']=='reissue' or $order['order_status']=='cancel'){
       if(empty($com)){$com[]='';}
       return $com;
     }

     $com["print"]=$this->link("print",$order["order_id"],"printer.gif",false,'',null, 'target="pdfdoc"');

     if(!$list){
       $com["ord"]=$this->link("set_status_ord",$order["order_id"],"ord.png",TRUE,con('change_status_to_ord'),$_GET);
     }

     if(!$list or $order['order_shipment_status']=='none'){
       $com["send"]=$this->link("set_status_shipment_send",$order["order_id"],"mail.png",TRUE,con('change_status_to_send'),$_GET);
     }

     if(!$list){
       $com["no_send"]=$this->link("set_status_shipment_none",$order["order_id"],"no_mail.png",TRUE,con('change_status_to_no_send'),$_GET);
     }

     if(!$list or $order['order_payment_status']=='none'){
       $com["payed"]=$this->link("set_status_payment_payed",$order["order_id"],"pig.png",TRUE,con('change_status_to_payed'),$_GET);
     }

     if(!$list){
       $com["no_payed"]=$this->link("set_status_payment_none",$order["order_id"],"no_pig.png",TRUE,con('change_status_to_no_payed'),$_GET);
     }

     if(!$list){
       $com["reissue"]=$this->link("make_new",$order["order_id"],"remis.png",TRUE,con('reissue_order'),$_GET);
       $com["delete"]=$this->link ("delete",$order["order_id"],"trash.png");
     }
     if(empty($com)){$com[]='';}
     return $com;
 }



function order_list (){
  $query="SELECT order_handling_id id, order_shipment_status, order_payment_status, order_status, count( * ) as count,
                 handling_id, handling_shipment, handling_payment, handling_sale_mode
          FROM `Order` left join Handling on order_handling_id=handling_id
          WHERE Order.order_status!='trash'
          GROUP BY order_handling_id, order_status, order_shipment_status, order_payment_status
          ORDER BY order_handling_id, order_status, order_shipment_status, order_payment_status ";

  if(!$res=ShopDB::query($query)){return;}
  $orders = array();
  while($obj=shopDB::fetch_assoc($res)){
    If (!isset($orders[$obj['id']])) {
      $orders[$obj['id']]['_name'] = $obj;
    } else
      $orders[$obj['id']]['_name']['count'] += $obj['count'];
    $key = $obj['order_status'].$obj['order_shipment_status']. $obj['order_payment_status'];
    $orders[$obj['id']]['_orders'][$key] = $obj;
  }

  $tr['ord']     = con('order_type_ordered');
  $tr['send']    = con('order_type_sended');
  $tr['cancel']  = con('order_type_canceled');
  $tr['reissue']  = con('order_type_reissued');
  $tr['pending'] = con('order_type_pending');
  $tr['payed']   = con('order_type_payed');
  $tr['res']     = con('order_type_reserved');

	$this->list_head(order_list_title,1);
	echo '</table></br>';

  foreach ($orders as $obj2){
    $hand = $obj2['_name']['id'];
    $obj = $obj2['_name'];
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border='0'>\n";
    echo "<tr class='stats_event_item' >
           <td>
            <a href='{$_SERVER['PHP_SELF']}?action=list_all&order_handling_id=$hand' class='UITabMenuNavOff'>
              ".con($obj['handling_payment'])." / ".con($obj['handling_shipment'])."
            </a> (<a href='view_handling.php?action=edit&handling_id={$hand}' class='UITabMenuNavOff'>
               #{$obj['handling_id']} {$obj['handling_sale_mode']}
            </a>)
           </td>\n";
    echo " <td align=right>".con('total').' : '.$obj['count'].' </td></tr>' ;

    foreach ($obj2['_orders'] as $obj){
  		$alt=$this->_order_status_color($obj);

      $link = "<a class=link href='{$_SERVER['PHP_SELF']}?action=list_all&order_handling_id=$hand".
                                                                        "&order_status={$obj['order_status']}".
                                                                        "&order_shipment_status={$obj['order_shipment_status']}".
                                                                        "&order_payment_status={$obj['order_payment_status']}'>";
  		if($obj['order_status']=='cancel'){
  		  $purge="(<a class=link href='{$_SERVER['PHP_SELF']}?action=purge_deleted&order_handling_id=$hand'>".con('purge')."</a>)";
  		}elseif($obj['order_status']=='reissue'){
  		  $purge="(<a class=link href='{$_SERVER['PHP_SELF']}?action=purge_rereissued&order_handling_id=$hand'>".con('purge')."</a>)";
  		}else{
  			$purge='';
  		}

  		echo "<tr class='admin_order_$alt'>
              <td class=admin_list_item align=left width='80%'>$link{$tr[$obj['order_status']]}
              {$tr[$obj['order_payment_status']]}
              {$tr[$obj['order_shipment_status']]}</a> $purge </td>
              <td class=admin_list_item align=right >
  	            {$obj['count']}
              </td>
            </tr>\n";
      $alt=($alt+1)%2;
    }
    echo "</table><br>" ;
  }
}
function order_event_list (){
  $query="SELECT distinct seat_event_id, seat_order_id, order_shipment_status, order_payment_status, order_status,
                 event_id, event_name, event_status, event_date, event_time
          FROM  Seat left join  Event  on seat_event_id= event_id
                     left JOIN `Order` on seat_order_id=order_id

          WHERE Order.order_status!='trash'
          ORDER BY seat_event_id, order_status, order_shipment_status, order_payment_status ";

  if(!$res=ShopDB::query($query)){return;}
  $orders = array();
  while($obj=shopDB::fetch_assoc($res)){
    If (!isset($orders[$obj['event_id']])) {
      $orders[$obj['event_id']]['_name'] = $obj;
      $orders[$obj['event_id']]['_name']['count'] = 0;
    }
    $orders[$obj['event_id']]['_name']['count'] += 1;
    $key = $obj['order_status'].$obj['order_shipment_status']. $obj['order_payment_status'];
    If (!isset($orders[$obj['event_id']]['_orders'][$key])) {
      $orders[$obj['event_id']]['_orders'][$key] = $obj;
      $orders[$obj['event_id']]['_orders'][$key]['count'] = 0;
    }
    $orders[$obj['event_id']]['_orders'][$key]['count'] += 1;
  }
  $tr['ord']     = con('order_type_ordered');
  $tr['send']    = con('order_type_sended');
  $tr['cancel']  = con('order_type_canceled');
  $tr['reissue']  = con('order_type_reissued');
  $tr['pending'] = con('order_type_pending');
  $tr['payed']   = con('order_type_payed');
  $tr['res']     = con('order_type_reserved');

	$this->list_head(order_event_list_title,1);
	echo '</table></br>';

  foreach ($orders  as $obj){
    $head = $obj['_name'];
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border='0'>\n";
    echo "<tr class='stats_event_item'>
            <td >
              <a href='{$_SERVER['PHP_SELF']}?action=event_all&event_id={$head['event_id']}' class='UITabMenuNavOff'>
                {$head['event_name']}. </a>
              (<a href='view_event.php?action=view&event_id={$head['event_id']}' class='UITabMenuNavOff'>#{$head['event_id']}</a>)
            </td>
            <td align=right>".con('total')." : {$head['count']}</td>
          </tr>" ;

    foreach ($obj['_orders'] as $obj2) {
  		$alt=$this->_order_status_color($obj2);

      $link = "<a class=link href='{$_SERVER['PHP_SELF']}?action=event_all&event_id={$head['event_id']}".
                                                                         "&order_status={$obj2['order_status']}".
                                                                         "&order_shipment_status={$obj2['order_shipment_status']}".
                                                                         "&order_payment_status={$obj2['order_payment_status']}'>";

/*  		if($obj->order_status=='cancel'){
  		  $purge="(<a class=link href='{$_SERVER['PHP_SELF']}?action=purge_deleted&event_id=$hand'>".purge."</a>)";
  		}elseif($obj->order_status=='reissue'){
  		  $purge="(<a class=link href='{$_SERVER['PHP_SELF']}?action=purge_reissued&order_handling_id=$hand'>".purge."</a>)";
  		}else{
  			$purge='';
  		} */

  		echo "<tr class='admin_order_$alt'>
            <td class=admin_list_item align=left width='80%'>$link{$tr[$obj2['order_status']]}
            {$tr[$obj2['order_payment_status']]}
            {$tr[$obj2['order_shipment_status']]}</a> </td>
            <td class=admin_list_item align=right >
  	  {$obj2['count']}</td></tr>\n";
      $alt=($alt+1)%2;
    }
   echo "</table><br>";
  }
}

function order_sub_list ($order_handling_id,$order_status,$order_shipment_status,$order_payment_status,$page){

  $where= "order_handling_id='$order_handling_id'";

  if($order_status){
    $where.=" and order_status='$order_status'";
  }

  if($order_shipment_status){
    $where.=" and order_shipment_status='$order_shipment_status'";
  }

  if($order_payment_status){
     $where.=" and order_payment_status='$order_payment_status'";
   }

   $limit=$this->get_limit($page);

 $query='SELECT SQL_CALC_FOUND_ROWS * '.
         'FROM `Order` '.
      	 "WHERE $where ".
      	 'ORDER BY order_date DESC '.
      	 "LIMIT {$limit['start']},{$limit['end']}";


  if(!$res=ShopDB::query($query)){return;}
  if(!$count=ShopDB::query_one_row('SELECT FOUND_ROWS()', false)){return;}
  if(!$hand=Handling::load($order_handling_id)){return;}

  $tr['ord']     = con('order_type_ordered');
  $tr['send']    = con('order_type_sended');
  $tr['cancel']  = con('order_type_canceled');
  $tr['reissue']  = con('order_type_reissued');
  $tr['pending'] = con('order_type_pending');
  $tr['payed']   = con('order_type_payed');
  $tr['res']     = con('order_type_reserved');
  $tr['none']    = '-';

  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border='0'>\n";
  echo "<tr><td class='admin_list_title' colspan='5' align='center'>".
  "<a href='view_handling.php?action=edit&handling_id={$hand->handling_id}' class=link>#{$hand->handling_id} ({$hand->handling_sale_mode})</a> ".con($hand->handling_shipment).' / '.
                   con($hand->handling_payment).
		   " ({$tr[$order_shipment_status]}/{$tr[$order_payment_status]})
  </td></tr>\n";

  while($row=shopDB::fetch_assoc($res)){
		$alt=$this->_order_status_color($row);

    echo "<tr class='admin_order_$alt'><td class='admin_list_item'>".$row["order_id"]."</td>
    <td class='admin_list_item'>".$row["order_total_price"]."</td>
    <td class='admin_list_item'>".$row["order_date"]."</td>";

    $com=$this->order_commands($row,TRUE);
    echo "<td class='admin_list_item'>".$com["details"]." ".$com["print"]."</td>
          <td class='admin_list_item' valign='middle'>".$com["send"]." ".$com["payed"]." ".$com["reissue"]." ".$com["delete"]."</td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<br>".
       $this->get_nav ($page,$count[0],"action=list_all&order_handling_id=$order_handling_id&order_status=$order_status&order_shipment_status=$order_shipment_status&order_payment_status=$order_payment_status");

}

function order_event_sub_list ($event_id,$order_status,$order_shipment_status,$order_payment_status,$page){
  $tr['ord']     = con('order_type_ordered');
  $tr['send']    = con('order_type_sended');
  $tr['cancel']  = con('order_type_canceled');
  $tr['reissue']  = con('order_type_reissued');
  $tr['pending'] = con('order_type_pending');
  $tr['payed']   = con('order_type_payed');
  $tr['res']     = con('order_type_reserved');


  $where= "event_id='$event_id'";
  $info = '';
  if($order_status){
    $where.=" and order_status='$order_status'";
    $info .= $tr[$order_status];

  }

  if($order_shipment_status){
    $where.=" and order_shipment_status='$order_shipment_status'";
    $info .= $tr[$order_shipment_status];
  }

  if($order_payment_status){
     $where.=" and order_payment_status='$order_payment_status'";
    $info .= $tr[$order_payment_status];
   }

   $limit=$this->get_limit($page);

  $query="SELECT SQL_CALC_FOUND_ROWS distinct seat_order_id, `Order`.*
          FROM  Seat left join Event   on seat_event_id= event_id
                     left JOIN `Order` on seat_order_id=order_id
          WHERE $where
          ORDER BY order_date DESC
          LIMIT {$limit['start']},{$limit['end']}";


  if(!$res=ShopDB::query($query)){return;}
  if(!$count=ShopDB::query_one_row('SELECT FOUND_ROWS()', false)){return;}
  $event = Event::load($event_id);
  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border='0'>\n";
  echo "<tr>
          <td class='admin_list_title' colspan='5' align='center'>
            <a href='view_event.php?action=view&event_id={$event->event_id}' class=link>#{$event->event_id}</a>
            {$event->event_name} {$event->event_date} {$hand->event_time} ({$info})
          </td>
        </tr>\n";
  while($row=shopDB::fetch_assoc($res)){
		$alt=$this->_order_status_color($row);

    echo "<tr class='admin_order_$alt'><td class='admin_list_item'>".$row["order_id"]."</td>
    <td class='admin_list_item'>".$row["order_total_price"]."</td>
    <td class='admin_list_item'>".$row["order_date"]."</td>";

    $com=$this->order_commands($row,TRUE);
    echo "<td class='admin_list_item'>".$com["details"]." ".$com["print"]."</td>
          <td class='admin_list_item' valign='middle'>".$com["send"]." ".$com["payed"]." ".$com["reissue"]." ".$com["delete"]."</td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<br>".
       $this->get_nav ($page,$count[0],"action=event_all&event_id=$event_id&order_status=$order_status&order_shipment_status=$order_shipment_status&order_payment_status=$order_payment_status");

}

function _order_status_color($row){
    $alt='';
    if($row['order_status']=='ord' and ($row['order_payment_status']!='none' or $row['order_shipment_status']!='none')){
      if($row['order_payment_status']!='none'){
        $alt=$row['order_payment_status'];
      }
      if($row['order_shipment_status']!='none'){
        $alt.=$row['order_shipment_status'];
      }
    }else{
      $alt=$row['order_status'];
    }
		return $alt;
}

function draw($noTab=false){
  if(!$noTab){
    if(isset($_REQUEST['tab'])) {
      $_SESSION['_overview_tab'] = (int)$_REQUEST['tab'];
    }
    $menu = array( con("orders_handlings_tab")=>"?tab=0", con("orders_event_tab")=>'?tab=1');
    echo $this->PrintTabMenu($menu, (int)$_SESSION['_overview_tab'], "left");
  }

  if(!isset($_REQUEST['order_id'])){
    $_REQUEST['order_id']=$_REQUEST['order_id1'];
  }

  if(preg_match('/^set_status_/',$_GET['action1']) and $_GET['order_id1']>0){
    if(!$order=Order::load($_GET['order_id1'])){return;}
    switch($_GET['action1']){
      case 'set_status_shipment_send': $order->set_shipment_status('send');break;
      case 'set_status_shipment_none': $order->set_shipment_status('none');break;
      case 'set_status_payment_payed': $order->set_payment_status('payed');break;
      case 'set_status_payment_none':  $order->set_payment_status('none');break;
      case 'set_status_ord':           $order->set_status('ord');break;
    }
  }elseif($_GET['action1']=="make_new" and $_GET["order_id1"]){
    if($new_id=Order::reissue($_GET["order_id1"], 0)){
      $this->order_details($new_id);
    }
  }elseif($_GET['action1']=="delete_ticket" and $_GET["order_id1"] and $_GET['seat_id']){
    Order::delete_ticket($_GET["order_id1"], $_GET['seat_id'],0);
  }elseif($_GET['action1']=="reissue_ticket" and $_GET["order_id1"] and $_GET['seat_id']){
    Seat::reIssue($_GET["order_id1"], $_GET['seat_id']);
  }

  if($_GET['action']=='list_type'){
    $this->order_bytype($_GET["order_status"],$_GET["order_type"],$_GET["page"]);

  }elseif($_GET['action']=='details' || $_REQUEST['action']=='order_detail'){
    $this->order_details($_REQUEST["order_id"]);

  }elseif($_GET['action']=='delete'){
    $this->order_prepare_delete($_GET["order_id"]);

  }elseif($_GET['action']=='cancel'){ 
    Order::delete($_GET["order_id"], 'order_deleted_manual');
    $this->order_details($_GET["order_id"]);

  } elseif($_GET['action']=='list_all'){
    $this->order_sub_list($_GET["order_handling_id"],
                          $_GET["order_status"],
                          $_GET["order_shipment_status"],
                          $_GET["order_payment_status"],
			                    $_GET["page"]);

  } elseif($_GET['action']=='event_all'){
    $this->order_event_sub_list($_GET["event_id"],
                                $_GET["order_status"],
                                $_GET["order_shipment_status"],
                                $_GET["order_payment_status"],
      			                    $_GET["page"]);

	} else if($_GET['action']=='purge_deleted'){
		Order::purgeDeleted((int)$_GET['order_handling_id']);
    $this->order_list();

	} else if($_GET['action']=='purge_reissued'){
		Order::purgeReissued((int)$_GET['order_handling_id']);
    $this->order_list();

  } elseif($_SESSION['_overview_tab']==1) {
     $this->order_event_list();

  } else {
     $this->order_list();
  }
}

function print_status ($user_status){
  if($user_status=='1'){
    return con('sale_point');
  }else if ($user_status=='2'){
    return con('member');
  }else if($user_status=='3'){
    return con('guest');
  }
}

function print_order_status ($order){
  switch($order['order_status']){
    case 'ord':   return "<font color='blue'>".con('ordered')."</font>";
    case 'send':  return "<font color='red'>".con('sended')."</font>";
    case 'payed': return "<font color='green'>".con('payed')."</font>";
    case 'cancel':return "<font color='#787878'>".con('canceled')."</font>";
    case 'reissue':return "<font color='#787878'>".con('reissued')."</font> (
    <a href='{$_SERVER['PHP_SELF']}?action=details&order_id={$order['order_reissued_id']}'>
    {$order['order_reissued_id']}</a> )";
  }


  }
  function extramenus(&$menu) {
    $menu[]="
    <table width='190' class='menu_admin' cellspacing='2'>
    <tr><td align='center' class='menu_admin_title'>".con('legende')."</td></tr>
    <tr><td class='admin_order_res' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('reserved')."</td></tr>
    <tr><td class='admin_order_ord' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('ordered')."</td></tr>
    <tr><td class='admin_order_pending' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('pending')."</td></tr>
    <tr><td class='admin_order_payed' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('payed')."</td></tr>
    <tr><td class='admin_order_send' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('sended')."</td></tr>
    <tr><td class='admin_order_payedsend' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('payed_and_send')."</td></tr>
    <tr><td class='admin_order_cancel' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('canceled')."</td></tr>
    <tr><td class='admin_order_reissue' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".con('reissue')."</td></tr>
    </table><br>";

    if($_GET["action"]=='list_all' or $_GET["action"]=='list_type' or $_GET["action"]=='details'){
      $sty="style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'";
      $menu[]="
      <table width='190' class='menu_admin' cellspacing='2'>
      <tr><td align='center' class='menu_admin_title'>".con('possible_actions')."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='../images/view.png' border='0'> ".con('view_order_details')."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='../images/printer.gif' border='0'> ".con('print_order')."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='../images/ord.png' border='0'> ".con('change_order_to_ord')."</td></tr>

      <tr><td class='menu_admin_item' $sty><img src='../images/mail.png' border='0'> ".con('send_order_post')."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='../images/no_mail.png' border='0'> ".con('no_send_order_post')."</td></tr>

      <!--tr><td class='menu_admin_item' $sty><img src='../images/email.png' border='0'> ".con('send_order_email')."</td></tr-->
      <tr><td class='menu_admin_item' $sty><img src='../images/pig.png' border='0'> ".con('change_order_to_payed')."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='../images/no_pig.png' border='0'> ".con('change_order_to_no_payed')."</td></tr>

      <tr><td class='menu_admin_item' $sty><img src='../images/remis.png' border='0'> ".con('reissue_order_menu')."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='../images/trash.png' border='0'> ".con('cancel_order')."</td></tr>

      </table>";
    }
  }

}
?>