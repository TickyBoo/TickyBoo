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


if (!defined('ft_check')) {die('System intrusion ');}
require_once("admin/class.adminview.php");
require_once("classes/OrphanCheck.php");

class UtilitiesView extends AdminView{

	function garbage_list (){

		$this->list_head(con('garbage'),2);
		$stats= $this->stats();

		echo "<tr class='admin_list_row_0'>
		<td class='admin_list_item'>".con('event')."</td>
		<td class='admin_list_item' align='right'>".$stats['event']."</td></tr>";

		echo "<tr class='admin_list_row_1'>
		<td class='admin_list_item'>".con('seat')."</td>
		<td class='admin_list_item' align='right'>".$stats['seat']."</td></tr>";

		echo "<tr class='admin_list_row_0'>
		<td class='admin_list_item'>".con('order')."</td>
		<td class='admin_list_item' align='right'>".$stats['order']."</td></tr>";

		echo "<tr class='admin_list_row_1'>
		<td class='admin_list_item'>".con('unused_guests') ."</td>
		<td class='admin_list_item' align='right'>".$stats['guests']."</td></tr>";

		echo "</table></form>";
		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?empty=true'>".con('empty_trash')."</a></center><br>";
  }

  function orphan_list() {

    $data = Orphans::getlist($keys);

    $space = (count($keys)*60 < $this->width -200)?1:0;


		$this->list_head(con('Record_Orphan_Test'),count($keys)+2+$space);
    print " <tr class='admin_list_header'>
              <th width=130 align='left'>
                Tablename
              </th>
              <th width=50 align='right'>
                ID
              </th>";
    foreach ($keys as $key) {
      print "<th width=60 align='center'> {$key}&nbsp;</th>";
    }
    if ($space) {
      print "<th align='center'>&nbsp;</th>";
    }

    print "</tr>";
    $alt =0;
    foreach ($data as $row) {

      print "<tr class='admin_list_row_$alt'>
        <td class='admin_list_item'>{$row['_table']}</td>
        <td class='admin_list_item' align='right'>{$row['_id']}</td>\n";
      foreach ($keys as $key) {
        print "<td align='center'>{$row[$key]}&nbsp;</td>\n";
      }
      if ($space) {
        print "<th align='center'>&nbsp;</th>";
      }
      print "</tr>";
      $alt = ($alt + 1) % 2;
    }
    print "</table>";
	}

	function emaillogTable() {
		global $_SHOP;

    $_REQUEST['page'] = is($_REQUEST['page'],1);
  //  $this->page_length = 2;
    $recstart = ($_REQUEST['page']-1)* $this->page_length;
		//echo $history,' => ',
    $query = "select SQL_CALC_FOUND_ROWS * from email_log
              order by el_timestamp
              limit {$recstart},{$this->page_length} ";

		if ( !$res = ShopDB::query($query) ) {
			return;
		}
    if(!$rowcount=ShopDB::query_one_row('SELECT FOUND_ROWS()', false)){return;}
		$alt = 0;
    echo "<table class='admin_list' border='0' width='".($this->width)."' cellspacing='1' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='4' align='left'>" . con('email_log_list_title') . "</td>\n";
    echo "</tr>\n";
    print " <tr class='admin_list_header'>
              <th width=140 align='left'>Date </th>
              <th align='left'>Action</th>
              <th align='left'>TO email</th>
              <th align='left'>Fail</th>
              <th align='left' colspan='2' >Received</th>
              ";

		$alt = 0;

		while ( $row = shopDB::fetch_assoc($res) ) {
			$edate = formatAdminDate( $row["el_timestamp"], false );

      echo "<tr id='nameROW_{$row['event_id']}' class='admin_list_row_$alt' >";
			echo "<td class='admin_list_item' >{$row["el_timestamp"]}</td>\n";
      echo "<td  class='admin_list_item' >{$row["el_action"]}</td>\n";
      $email = print_r( unserialize($row["el_email_to"]), true);
//      $email = $email[1].'&lt;'.$email[0].'&gt;';
      echo "<td  class='admin_list_item' >{$email}</td>\n";
      echo "<td  class='admin_list_item' >{$row["el_failed"]}</td>\n";
      echo "<td  class='admin_list_item' >{$row["el_received"]}</td>\n";
      echo "<td class='admin_list_item' width='18' align='right' nowarp=nowarp'><nowrap>".
        $this->show_button("{$_SERVER['PHP_SELF']}?action=el_view&el_id={$row['el_id']}","view",2);
      echo "</nowrap></td>\n";

			echo "</tr>\n\n";
			$alt = ( $alt + 1 ) % 2;
		}
		echo "</table>\n";
    $this->get_nav( $_REQUEST['page'], $rowcount[0]);
	}

  function emaillogView ($data) {
    global $_SHOP,  $_COUNTRY_LIST;

    $query = "select * from email_log
              where el_id = "._esc((int)$data['el_id']);

		if ( !$row = ShopDB::query_one_row($query) ) {
			return ;
		}
    echo "<table class='admin_form' width='".($this->width)."' cellspacing='1' cellpadding='4'>\n";
    $this->print_field('el_timestamp',$row);
    $this->print_field('el_action',$row);
    $this->print_field('el_email_to',print_r( unserialize($row["el_email_to"]), true));
    $this->print_field("el_failed",  $row);
    $this->print_field("el_received",  $row);
    echo "<tr><td colspan='2' class='admin_name'>" .con('el_log'). "</td></tr>";
    echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed;'>" .
         " <div style='overflow: auto; height: 150px; width:97%;padding:10px;'>".

          nl2br(htmlspecialchars($row['el_log'])) . "</div></td></tr>";
    echo "<tr><td colspan='2' class='admin_name'>" .con('el_bad_emails'). "</td></tr>";
    echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed;'>" .
         " <div style='overflow: auto; height: 50px; width:97%;padding:10px;'>".
          nl2br(htmlspecialchars($row['el_bad_emails'])) . "&nbsp;</div></td></tr>";

    echo "<tr><td colspan='2' class='admin_name'>" .con('el_email_message'). "</td></tr>";
    echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed;'>" .
         " <div style='overflow: auto; height: 250px; width:97%;padding:10px;'>".
          nl2br(htmlspecialchars($row['el_email_message'])) . "</div></td></tr>";
   	echo "</table>\n";
		echo "<br>".$this->show_button("{$_SERVER['PHP_SELF']}",'admin_list',3);
    return true;
	}



	function draw () {
		global $_SHOP;
    if(isset($_REQUEST['tab'])) {
      $_SESSION['_UTILS_tab'] = (int)$_REQUEST['tab'];
    }

    $menu = array( con("orphan_tab")=>"?tab=0", con("garbage_tab")=>'?tab=1',
                   con("emaillog_tab")=>"?tab=2",  con("backup_tab")=>"?tab=3");
    echo $this->PrintTabMenu($menu, (int)$_SESSION['_UTILS_tab'], "left");

    if($_GET['fix']){
      Orphans::dofix($_GET['fix']);
    } elseif($_GET['empty']){
			$this->empty_trash();
		} elseif ($_GET['action']=='el_view') {
      if ($this->emaillogView($_GET )) return;
    }



    switch ((int)$_SESSION['_UTILS_tab']) {
      case 0:
         $this->orphan_list($_POST);
         break;

      case 1:
         $this->garbage_list($_POST);
         break;

      case 2:
         $this->emaillogTable($_POST);
         break;

      case 3:
     //    $this->barcodeForm($_POST);
         echo 'Noting to see yet';
         break;

    }
	}


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