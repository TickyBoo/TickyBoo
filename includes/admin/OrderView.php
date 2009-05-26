<?php
/*
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.

 */

require_once("admin/AdminView.php");
require_once("classes/Seat.php");
require_once("classes/Order.php");

class OrderView extends AdminView{
  var $page_length=15;





function order_prepare_delete ($order_id){
  echo "<center><form action='".$_SERVER["PHP_SELF"]."?action=cancel&order_id=$order_id' method='post'>
        <input type='submit' name='cancel' value='".cancel."'></form></center><br>";
  $this->order_details ($order_id);
  echo "<br><center><form action='".$_SERVER["PHP_SELF"]."?action=cancel&order_id=$order_id' method='post'>
        <input type='submit' name='cancel' value='".cancel."'></form></center><br>";

}

function order_details ($order_id){
  global $_SHOP;
  $query="select * from `Order`,User where order_id='$order_id' and order_user_id=user_id";
  if(!$order=ShopDB::query_one_row($query)){
    echo "<div class='error'>".order_not_found." $order_id</div>";
    return;
  }

  $com = implode('&nbsp;&nbsp;',$this->order_commands($order,FALSE));;

  $status=$this->print_order_status($order);
  $order["order_status"]=$status;


  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title'>".order_nr."  ".$order_id."</td>
  <td align='right'><table width='100' style='border:#cccccc 1px solid;'><tr><td align='center'>
  $com
  </td></tr></table></td></tr>";

  $this->print_field('order_tickets_nr',$order);
  $this->print_field('order_total_price',$order);
  $this->print_field('order_date',$order);

  $order['order_shipment_status']=con($order['order_shipment_status']);
  $order['order_payment_status']=con($order['order_payment_status']);

  $this->print_field('order_shipment_status',$order);
  $this->print_field('order_payment_status',$order);
  $this->print_field_o('order_payment_id',$order);


  $this->print_field('order_fee',$order);

  $this->print_field('order_status',$order);
  echo "</table><br>\n";

	$query="select * from Seat LEFT JOIN Discount ON Seat.seat_discount_id=Discount.discount_id
			LEFT JOIN Event ON Seat.seat_event_id=Event.event_id
			LEFT JOIN Category ON Seat.seat_category_id=Category.category_id
			LEFT JOIN PlaceMapZone ON Seat.seat_zone_id=PlaceMapZone.pmz_id
			WHERE Seat.seat_order_id='".$order_id."'";

  if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
  }
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='8'>".tickets."</td></tr>";
  $alt=0;
  while($ticket=shopDB::fetch_assoc($res)){
    if((!$ticket["category_numbering"]) or $ticket["category_numbering"]=='both'){
      $place=$ticket["seat_row_nr"]."-".$ticket["seat_nr"];
    }else if($ticket["category_numbering"]=='rows'){
      $place=place_row." ".$ticket["seat_row_nr"];
    }else if($ticket["category_numbering"]=='seat'){
      $place=place_seat." ".$ticket["seat_nr"];
     }else{
       $place='---';
     }

    $t_com = implode('&nbsp;',$this->ticket_commands($order_id,$ticket['seat_id']));;

    echo "<tr class='admin_list_row_$alt'>
     	   <td class='admin_list_item'>".$ticket["seat_id"]."</td>
     	   <td class='admin_list_item'>".$ticket["event_name"]."</td>
     	   <td class='admin_list_item'>".$ticket["category_name"]."</td>
     	   <td class='admin_list_item'>".$ticket["pmz_name"]."</td>

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
   echo "<tr><td class='admin_list_title' colspan='2'>".user." ".$order["user_id"]."</td></tr>";

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

 function link ($action,$order_id,$img,$confirm=FALSE,$con_msg='',$param=null){
   if($confirm){
     $param['action1']=$action;
     $param['order_id1']=$order_id;

     foreach($param as $key=>$val){
       $par.=$psep."$key=$val";
       $psep="&";
     }

     return "<a href='javascript:if(confirm(\"".con($con_msg)."\")){location.href=\"".$_SERVER['PHP_SELF']."?$par\";}'>".
          "<img border='0' src='images/$img'></a>";
   }
   return "<a href='".$_SERVER['PHP_SELF']."?action=$action&order_id=$order_id'>".
          "<img border='0' src='images/$img'></a>";
 }

 function get_limit ($page,$count=0){
   if(!$page){ $page=1; }
   $limit["start"]=($page-1)*$this->page_length;
   $limit["end"]=$this->page_length;
   return $limit;

 }

 function get_nav ($page,$count,$condition){
   if(!isset($page)){ $page=1; }

   echo "<table border='0' width='$this->width'><tr><td align='center'>";
   echo "<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=1'>".firstpage."</a>";

   if($page>1){
     $prev=$page-1;
     echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$prev'>".previouspage."</a>";
   }
   $num_pages=ceil($count/$this->page_length);
   echo "&nbsp;[";
   for ($i=floor(($page-1)/10)*10+1;$i<=min(ceil($page/10)*10,$num_pages);$i++){
     if($i==$page){
       echo "&nbsp;<b>$i</b>";
     }else{
       echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$i'>$i</a>";
     }
   }
   echo "&nbsp;]&nbsp;";
   $next=$page+1;
     if($next*$this->page_length<$count){
       echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$next'>".nextpage."</a>";
     }
   echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$num_pages'>".lastpage."</a>";

   echo "</td></tr></table>";
 }

 function ticket_commands ($order_id,$ticket_id){
   $params=$_GET;
   $params['seat_id']=$ticket_id;
   $com["reemit"]=$this->link("reemit_ticket",$order_id,"remis.png",TRUE,reemit_ticket." $ticket_id",$params);
   $com["delete"]=$this->link("delete_ticket",$order_id,"trash.png",TRUE,delete_ticket." $ticket_id",$params);
   return $com;
 }


 function order_commands ($order,$list=TRUE){
     if($list){$com["details"]=$this->link("details",$order["order_id"],"view.png");}
     if($order['order_status']=='reemit' or $order['order_status']=='cancel'){
       if(empty($com)){$com[]='';}
       return $com;
     }

     $com["print"]=$this->link("print",$order["order_id"],"printer.gif");

     if(!$list){
       $com["ord"]=$this->link("set_status_ord",$order["order_id"],"ord.png",TRUE,change_status_to_ord,$_GET);
     }

     if(!$list or $order['order_shipment_status']=='none'){
       $com["send"]=$this->link("set_status_shipment_send",$order["order_id"],"mail.png",TRUE,change_status_to_send,$_GET);
     }

     if(!$list){
       $com["no_send"]=$this->link("set_status_shipment_none",$order["order_id"],"no_mail.png",TRUE,change_status_to_no_send,$_GET);
     }

     if(!$list or $order['order_payment_status']=='none'){
       $com["payed"]=$this->link("set_status_payment_payed",$order["order_id"],"pig.png",TRUE,change_status_to_payed,$_GET);
     }

     if(!$list){
       $com["no_payed"]=$this->link("set_status_payment_none",$order["order_id"],"no_pig.png",TRUE,change_status_to_no_payed,$_GET);
     }

     if(!$list){
       $com["reemit"]=$this->link("make_new",$order["order_id"],"remis.png",TRUE,reemit_order,$_GET);
       $com["delete"]=$this->link ("delete",$order["order_id"],"trash.png");
     }
     if(empty($com)){$com[]='';}
     return $com;
 }



function order_list (){
  global $_SHOP;
  $query='SELECT order_handling_id, order_shipment_status, order_payment_status, order_status, count( * ) as count, Handling.* '.
         'FROM `Order` , Handling FORCE INDEX (PRIMARY) '.
	 "WHERE order_handling_id=handling_id ".
	 "and Order.order_status!='trash' ".
   'GROUP BY order_handling_id, order_status, order_shipment_status, order_payment_status '.
	 'ORDER BY order_handling_id, order_status, order_shipment_status, order_payment_status ';

  if(!$res=ShopDB::query($query)){return;}

  $tr['ord']=order_type_ordered;
  $tr['send']=order_type_sended;
  $tr['cancel']=order_type_canceled;
  $tr['reemit']=order_type_reemited;
  $tr['payed']=order_type_payed;
  $tr['res']=order_type_reserved;

	$this->list_head(order_list_title,1);
	echo '</table></br>';

  while($obj=shopDB::fetch_object($res)){
    if($hand!=$obj->handling_id){
      if(isset($hand)){echo '<tr><td colspan=4 align=right>'.total.' : '.$sum.' </td></tr></table><br>' ;}

      $hand=$obj->handling_id;
      $sum=0;
      echo "<table class='admin_list' width='$this->width' cellspacing='1'
      cellpadding='4' border='0'>\n";
      echo "<tr><td class='admin_list_title' colspan='4' align='center'>
      <a href='{$_SERVER['PHP_SELF']}?action=list_all&order_handling_id=$hand' class=link>".
      con($obj->handling_shipment)." / ".
      con($obj->handling_payment)."  <a href='view_handling.php?action=view&handling_id={$obj->handling_id}' class=link> (#{$obj->handling_id} {$obj->handling_sale_mode})</a></td></tr>\n";

		}
		$alt=$this->_order_status_color((array)$obj);

    $link = "<a class=link href='{$_SERVER['PHP_SELF']}?action=list_all&order_handling_id=$hand&order_status={$obj->order_status}&order_shipment_status={$obj->order_shipment_status}&order_payment_status={$obj->order_payment_status}'>";

		if($obj->order_status=='cancel'){
		  $purge="(<a class=link href='{$_SERVER['PHP_SELF']}?action=purge_deleted&order_handling_id=$hand'>".purge."</a>)";
		}elseif($obj->order_status=='reemit'){
		  $purge="(<a class=link href='{$_SERVER['PHP_SELF']}?action=purge_reemited&order_handling_id=$hand'>".purge."</a>)";
		}else{
			$purge='';
		}

		echo "<tr class='admin_order_$alt'>
          <td class=admin_list_item align=left width='80%'>$link{$tr[$obj->order_status]}
          {$tr[$obj->order_payment_status]}
          {$tr[$obj->order_shipment_status]}</a> $purge </td>
          <td class=admin_list_item align=right >
	  {$obj->count}</td></tr>\n";

    $sum+=$obj->count;
    $alt=($alt+1)%2;
  }

  if(isset($hand)){echo '<tr><td colspan=4 align=right>'.total.' : '.$sum.' </td></tr></table><br>' ;}
}

function order_sub_list ($order_handling_id,$order_status,$order_shipment_status,$order_payment_status,$page){
  global $_SHOP;
  require_once('classes/Handling.php');

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
  if(!$count=ShopDB::query_one_row('SELECT FOUND_ROWS()')){return;}
  if(!$hand=Handling::load($order_handling_id)){return;}

  $tr['ord']=order_type_ordered;
  $tr['send']=order_type_sended;
  $tr['cancel']=order_type_canceled;
  $tr['reemit']=order_type_reemited;
  $tr['payed']=order_type_payed;
  $tr['none']='-';

  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border='0'>\n";
  echo "<tr><td class='admin_list_title' colspan='5' align='center'>".
  "<a href='view_handling.php?action=view&handling_id={$hand->handling_id}' class=link>#{$hand->handling_id} ({$hand->handling_sale_mode})</a> ".con($hand->handling_shipment).' / '.
                   con($hand->handling_payment).
		   " ({$tr[$order_shipment_status]}/{$tr[$order_payment_status]})
  </td></tr>\n";



  while($row=shopDB::fetch_array($res)){
		$alt=$this->_order_status_color($row);

    echo "<tr class='admin_order_$alt'><td class='admin_list_item'>".$row["order_id"]."</td>
    <td class='admin_list_item'>".$row["order_total_price"]."</td>
    <td class='admin_list_item'>".$row["order_date"]."</td>";

    $com=$this->order_commands($row,TRUE);
    echo "<td class='admin_list_item'>".$com["details"]." ".$com["print"]."</td>
          <td class='admin_list_item' valign='middle'>".$com["send"]." ".$com["payed"]." ".$com["reemit"]." ".$com["delete"]."</td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<br>".
       $this->get_nav ($page,$count[0],"action=list_all&order_handling_id=$order_handling_id&order_status=$order_status&order_shipment_status=$order_shipment_status&order_payment_status=$order_payment_status");

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

function draw (){
  global $_SHOP;

  if(preg_match('/^set_status_/',$_GET['action1']) and $_GET['order_id1']>0){
    if(!$order=Order::load($_GET['order_id1'])){return;}
    switch($_GET['action1']){
      case 'set_status_shipment_send': $order->set_shipment_status('send');break;
      case 'set_status_shipment_none': $order->set_shipment_status('none');break;
      case 'set_status_payment_payed': $order->set_payment_status('payed');break;
      case 'set_status_payment_none':  $order->set_payment_status('none');break;
      case 'set_status_ord':           $order->set_status('ord');break;
    }
  }else
  if($_GET['action1']=="make_new" and $_GET["order_id1"]){
    if($new_id=Order::order_reemit($_GET["order_id1"], 0)){
      $this->order_details($new_id);
    }
  }else
  if($_GET['action1']=="delete_ticket" and $_GET["order_id1"] and $_GET['seat_id']){
    Order::order_delete_ticket($_GET["order_id1"], $_GET['seat_id'],0);
  }else
  if($_GET['action1']=="reemit_ticket" and $_GET["order_id1"] and $_GET['seat_id']){
    Ticket::reemit($_GET["order_id1"], $_GET['seat_id']);
  }



  if($_GET['action']=='list_type'){
    $this->order_bytype($_GET["order_status"],$_GET["order_type"],$_GET["page"]);
  }else
  if($_GET['action']=='details'){
    $this->order_details($_GET["order_id"]);
  }else
  if($_GET['action']=='delete'){
    $this->order_prepare_delete($_GET["order_id"]);
  }else
  if($_GET['action']=='cancel'){
    Order::order_delete($_GET["order_id"], 0);
    $this->order_details($_GET["order_id"]);

  } else
  if($_GET['action']=='list_all'){
    $this->order_sub_list($_GET["order_handling_id"],
                          $_GET["order_status"],
                          $_GET["order_shipment_status"],
                          $_GET["order_payment_status"],
			                    $_GET["page"]);

//    $this->order_byshipment($_GET["order_type"],$_GET["page"]);
	} else if($_GET['action']=='purge_deleted'){
		Order::purgeDeleted((int)$_GET['order_handling_id']);
    $this->order_list();
	} else if($_GET['action']=='purge_reemited'){
		Order::purgeReemited((int)$_GET['order_handling_id']);
    $this->order_list();
  } else{
    $this->order_list();
  }
}

function print_status ($user_status){
  if($user_status=='1'){
    return sale_point;
  }else if ($user_status=='2'){
    return member;
  }else if($user_status=='3'){
    return guest;
  }
}

function print_order_status ($order){
  switch($order['order_status']){
    case 'ord':   return "<font color='blue'>".ordered."</font>";
    case 'send':  return "<font color='red'>".sended."</font>";
    case 'payed': return "<font color='green'>".payed."</font>";
    case 'cancel':return "<font color='#787878'>".canceled."</font>";
    case 'reemit':return "<font color='#787878'>".reemited."</font> (
    <a href='{$_SERVER['PHP_SELF']}?action=details&order_id={$order['order_reemited_id']}'>
    {$order['order_reemited_id']}</a> )";
  }


  }
  function extramenus(&$menu) {
    $menu[]="
    <table width='190' class='menu_admin' cellspacing='2'>
    <tr><td align='center' class='menu_admin_title'>".legende."</td></tr>
    <tr><td class='admin_order_res' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".reserved."</td></tr>
    <tr><td class='admin_order_ord' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".ordered."</td></tr>
    <tr><td class='admin_order_send' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".sended."</td></tr>
    <tr><td class='admin_order_payed' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".payed."</td></tr>
    <tr><td class='admin_order_cancel' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".canceled."</td></tr>
    <tr><td class='admin_order_reemit' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".reemited."</td></tr>
    <tr><td class='admin_order_payedsend' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".payed_and_send."</td></tr>
    </table><br>";

    if($_GET["action"]=='list_all' or $_GET["action"]=='list_type' or $_GET["action"]=='details'){
      $sty="style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'";
      $menu[]="
      <table width='190' class='menu_admin' cellspacing='2'>
      <tr><td align='center' class='menu_admin_title'>".possible_actions."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='images/view.png' border='0'> ".view_order_details."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='images/printer.gif' border='0'> ".print_order."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='images/ord.png' border='0'> ".change_order_to_ord."</td></tr>

      <tr><td class='menu_admin_item' $sty><img src='images/mail.png' border='0'> ".send_order_post."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='images/no_mail.png' border='0'> ".no_send_order_post."</td></tr>

      <!--tr><td class='menu_admin_item' $sty><img src='images/email.png' border='0'> ".send_order_email."</td></tr-->
      <tr><td class='menu_admin_item' $sty><img src='images/pig.png' border='0'> ".change_order_to_payed."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='images/no_pig.png' border='0'> ".change_order_to_no_payed."</td></tr>

      <tr><td class='menu_admin_item' $sty><img src='images/remis.png' border='0'> ".reemit_order_menu."</td></tr>
      <tr><td class='menu_admin_item' $sty><img src='images/trash.png' border='0'> ".cancel_order."</td></tr>

      </table>";
    }
  }

}
?>