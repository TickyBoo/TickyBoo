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
require_once ( "admin/EventViewCommon.php" );

class EventPropsView extends EventViewCommon {

	function table($history=false) {
		global $_SHOP;

		echo "<script>";
		echo "var val=1;";
		echo "
    function checkall()	{
      for(i=0;i<document.getElementsByTagName('input').length;i++){
        if(document.getElementsByTagName('input')[i].type == 'checkbox'){
          if(document.getElementsByTagName('input')[i].checked == false && val==1) {
            document.getElementsByTagName('input')[i].checked=true;
     			}
					if(document.getElementsByTagName('input')[i].checked == true && val==0)	{
				    document.getElementsByTagName('input')[i].checked=false;
					}
        }
      }
			val=(val==1)?0:1;
  	}
  </script>\n";

/*
select SQL_CALC_FOUND_ROWS *
              from Event LEFT JOIN Ort ON event_ort_id=ort_id
              WHERE event_rep!='sub'
              and event_status!='trash'
              and ((event_rep!='main' and  (event_status='pub' or  event_date >= NOW()))
                   or (event_rep='main' and  (select count(*)
                                               from Event main
                                               where main.event_main_id = Event.event_id
                                               and  (event_status='pub' or  event_date >= NOW() ))) > 0)
              order by event_date
              limit 0,15

 select SQL_CALC_FOUND_ROWS *
              from Event LEFT JOIN Ort ON event_ort_id=ort_id
              WHERE event_rep!='sub'
              and event_status!='trash'
              and ((event_rep!='main' and  (event_status='pub' or  event_date >= NOW() )
                   or (event_rep='main' and  (select count(*)
                                               from Event main
                                               where main.event_main_id = Event.event_id
                                               and  (event_status='pub' or  event_date >= NOW() ))) > 0)
              order by event_date
              limit 0,15
*/
    $wherex = (!$history)?"event_status='pub' or ":"event_status !='pub' AND ";
    $where  = "and ((event_rep!='main' and  ($wherex TO_DAYS(event_date) ".(($history)?'<':'>=')." TO_DAYS(NOW())-1 )) \n";
    $where .= "     or (event_rep='main' and ((select COALESCE(count(*),0)
                                               from Event main
                                               where main.event_main_id = Event.event_id
                                               and event_status!='trash'
                                               and  ($wherex TO_DAYS(event_date) ".(($history)?'<':'>=')." TO_DAYS(NOW())-1 ))) > 0)
                                             OR
                                             ((select COALESCE(count(*),0)
                                               from Event main
                                               where main.event_main_id = Event.event_id) = 0) and event_rep='main')";
    $_REQUEST['page'] = is($_REQUEST['page'],1);
  //  $this->page_length = 2;
    $recstart = ($_REQUEST['page']-1)* $this->page_length;
		//echo $history,' => ',
    echo $query = "select SQL_CALC_FOUND_ROWS `event_id`, `event_name`, `event_text`, `event_short_text`, `event_url`, `event_image`, `event_ort_id`, `event_pm_id`, `event_date`, event_time event_time, `event_open`, `event_end`, `event_status`, `event_order_limit`, `event_template`, `event_group_id`, `event_mp3`, `event_rep`, `event_main_id`, `event_type`, `ort_id`, `ort_name`, `ort_phone`, `ort_plan_nr`, `ort_url`, `ort_image`, `ort_address`, `ort_address1`, `ort_zip`, `ort_city`, `ort_state`, `ort_country`, `ort_pm`, `ort_fax`
              from Event LEFT JOIN Ort ON event_ort_id=ort_id
              WHERE event_rep!='sub'
              and event_status!='trash'
              $where
              order by event_date, event_time
              limit {$recstart},{$this->page_length} ";

		if ( !$res = ShopDB::query($query) ) {
			return;
		}
    if(!$rowcount=ShopDB::query_one_row('SELECT FOUND_ROWS()', false)){return;}
		$alt = 0;
    echo "<form action='{$_SERVER['PHP_SELF']}' method='POST' name='frmEvents'>";
    echo "<table class='admin_list' border='0' width='$this->width' cellspacing='1' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='4' align='left'>" . con(($history)?'event_history_title':'event_title') . "</td>\n";
    echo "<td colspan='1' align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add",3)."</td>";
    echo "</tr>\n";

		$img_pub = $this->fill_images();

		$alt = 0;

		while ( $row = shopDB::fetch_assoc($res) ) {
			$edate = formatAdminDate( $row["event_date"], false );
			$etime = formatTime( $row["event_time"] );

      echo "<tr id='nameROW_{$row['event_id']}' class='admin_list_row_$alt' >";
      echo "<td colspan=2 class='admin_list_item' width=150 NOWRAP><nobr> ";
      if (!$history) {
   		  echo "<input type='checkbox' name='cbxEvents[]'
                 id='main_event_".$row['event_main_id']."'
                 value='".$row['event_id']."'>";
      }
      echo  '&nbsp;'. showstr( $row['event_name'], 30 ) . "</nobr></td>\n";

			if ( $row['event_rep'] == 'main') {
        if (!$history) {
  				echo "<td colspan=2 class='admin_list_item'>".
               $this->show_button("{view_event.php?action=add_sub&event_main_id={$row['event_id']}",'add_multi',3) .
               "</td>\n";
		  	} else {
				  echo "<td colspan=2 class='admin_list_item'>&nbsp;</td>\n";
        }
			} else {
				echo "<td width=120 class='admin_list_item'>$edate $etime</td>\n";
				echo "<td class='admin_list_item'>" . showstr( $row["ort_name"] ) . "</td> ";
			}

			echo "<td width=105 class='admin_list_item' nowrap='nowrap'><nobr>";
      $this->showbuttons($img_pub, $row, $history);
			echo "</nobr></td></tr>\n\n";

			$alt = ( $alt + 1 ) % 2;
			if ( $row['event_rep'] == 'main' ) {
				$this->tableSubs( $row['event_id'], $alt,$row['event_name'], $history);
			}
		}
		if (!$history){
      echo "
          <tr>
          	<td colspan='5'>
          		<input type='hidden' name='action' id='action' value=''>
              <input type='checkbox'  onclick=\"checkall();\">&nbsp;<font size=-1>Check/Uncheck All&nbsp;&nbsp; </font>
           		<button name='publish' value  ='".con('publish').  "' onclick='javascript: document.frmEvents.action.value=\"" . "publish" . "\";document.frmEvents.submit();'>".con('publish').  "</button>
           		<button name='unpublish' value='".con('unpublish')."' onclick='javascript: document.frmEvents.action.value=\"" . "unpublish" . "\";document.frmEvents.submit();'>".con('unpublish')."</button>
           		<button name='publish' value  ='".con('delete').   "' onclick='javascript: if(confirm(\"" . con('delete_item') . "\")){document.frmEvents.action.value=\"" . "remove_events" . "\";document.frmEvents.submit();}'>".con('delete').   "</button>
          	</td>
          </tr>\n";
    }
//        		<a class='link' href='view_event.php?action=add'>Add</a>
		echo "</table>\n</form>\n";
  $this->get_nav( $_REQUEST[page], $rowcount[0]);
//		echo "<br><center><a class='link' href='view_event.php?action=add'>" . con('add') .	"</a></center>";
	}

	function tableSubs( $event_main_id, &$alt, $main_name, $history= false ) {
		global $_SHOP;
    $where = "and (TO_DAYS(event_date) ".(($history)?'<':'>=')." TO_DAYS(NOW())+1 ".((!$history)?"or event_status='pub'":"and event_status!='pub'").')';

		$query = "select *
              from Event LEFT JOIN Ort ON event_ort_id=ort_id
              where event_main_id="._esc($event_main_id)."
              and event_rep='sub'
              and event_status!='trash'
              $where
              order by event_date, event_time ";

		if ( !$res = ShopDB::query($query) ) {
       return;
		}

		$img_pub = $this->fill_images();

		while ( $row = shopDB::fetch_assoc($res) ) {
			$edate = formatAdminDate( $row["event_date"], false );
			$etime = formatTime( $row["event_time"] );
//                                  onClick=\"window.location='view_event.php?action=edit&event_id={$row['event_id']}'\"

      echo "<tr id='nameROW_{$row['event_id']}' class='admin_list_row_$alt' >
                <td class='admin_list_item' style='width:5;' bgcolor='white' >&nbsp;</td>
                <td class='admin_list_item' width='130' ><nobr>";
      if (!$history) {
        echo "<input type='checkbox' name='cbxEvents[]'
                 id='main_event_".$row['event_main_id']."'
                 class='".$row['event_main_id']."'
                 value='".$row['event_id']."'>";
      }
      if ($main_name !== $row['event_name']) {
        echo  '&nbsp;'. showstr( $row['event_name'], 28 ) ;


      }

      echo "&nbsp;</nobr></td>
                <td class='admin_list_item'>$edate $etime</td>
                <td class='admin_list_item' NOWRAP><nobr>" . showstr( $row['ort_name'] ) .	"</nobr></td>\n";

			echo "<td class='admin_list_item' width=250 NOWRAP><nobr>xx";
      $this->showbuttons($img_pub, $row, $history);
			echo "
      </nobr></td></tr>\n\n";
			$alt = ( $alt + 1 ) % 2;
		}
	}

  function showbuttons($img_pub, $row, $history) {
    echo $this->show_button("{$img_pub[$row['event_status']]['link']}{$row['event_id']}",
                            $img_pub[$row['event_status']]['title'],2,
                             array('image'=>$img_pub[$row['event_status']]['src'],
                                   'alt'  =>con($img_pub[$row['event_status']]['alt']),
                                   'disable'=> $history));
    echo  $this->show_button("view_event.php?action=edit&event_id={$row['event_id']}",'edit',2);
    echo  $this->show_button("view_event.php?action=edit_pm&pm_id={$row['event_pm_id']}",'place_map',2,
                             array('image'=>'pm.png',
                                   'disable'=> !$row['event_pm_id']));
    $pub = ( ($row['event_pm_id'] and $row['event_status'] == 'unpub') or (!$row['event_pm_id'] and
      				$row['event_status'] != 'pub') or ($row["event_status"] == 'nosal') );
    echo  $this->show_button("archive_event.php?event_id={$row['event_id']}",'Archive',2,
                             array('image'=>'archive.png',
                                   'disable'=> !$pub));
    echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&event_id={$row['event_id']}\";}","remove",2,
                             array('tooltiptext'=>"Delete {$row['event_name']}?",
                                   'disable'=> !$pub ));
  }

	function form( $data, $err ) {
		global $_SHOP;

		echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
		if ( !$data['event_id'] ) {
  		$this->form_head(con('event_add_title'));
		} else {
  		$this->form_head(con('event_edit_title'));
			echo "<input type='hidden' name='event_id' value='{$data['event_id']}'/>\n";
    }
		echo "<input type='hidden' name='action' value='save'/>\n";
    If ($data['event_rep']=='sub'){
      if(!$main=Event::load($data['event_main_id'],FALSE) ){return FALSE;}
    } else {
      $mail ='';
    }
		$this->print_field_o('event_id', $data);
		$this->print_input('event_name', $data, $err, 30, 100, $main );
		if ( !$data['event_id'] ) {
			$this->print_select_group( 'event_group_id', $data, $err, $main );
      If ($data['event_ort_id']) {
        $data['event_pm_ort_id'] = $data['event_pm_id'].','.$data['event_ort_id'];
      }
			$this->print_select_pm( 'event_pm_ort_id', $data, $err );
		} else {
			$this->print_field_o( 'event_group_name', $data );
			$this->print_field( 'event_ort_name', $data );
			$this->print_field_o( 'event_pm_name', $data );
		}

    If ($data['event_rep']=='sub'){
			echo "<input type='hidden' name='event_rep' value='sub'/>\n";
			echo "<input type='hidden' name='event_main_id' value='{$data['event_main_id']}'/>\n";
    } elseif ( !$data['event_id'] ) {
			$this->print_select( 'event_rep', $data, $err, array('unique', 'main') );
		} else {
			$this->print_field( 'event_rep', $data );
		}


		$this->print_area( 'event_short_text', $data, $err, 3,55,$main );
		$this->print_large_area( 'event_text', $data, $err,6,95,$main  );
		$this->print_input( 'event_url', $data, $err, 30, 100, $main  );

		$this->print_date( 'event_date', $data, $err, $main );
		if ( !$data['event_id'] ) {
   		$this->print_select_recurtype("event_recur_type",$data);
    }

		$this->print_time( 'event_time', $data, $err , $main);
		$this->print_time( 'event_open', $data, $err, $main );
		$this->print_time( 'event_end', $data, $err, $main );

		$this->print_input( 'event_order_limit', $data, $err, 3, 4, $main  );
		$this->print_select_tpl( 'event_template', $data, $err, $main );


		$this->select_types( 'event_type', $data, $err, $main );

		$this->print_file( 'event_image', $data, $err, 'img', $main);
		$this->print_file( 'event_mp3', $data, $err, 'mp3', $main );
        //recurrence


		$this->form_foot();

		echo "</form>\n";
		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . con('admin_list') ."</a></center>";
	}


	// #######################################################
	// #######################################################
	// #######################################################
	function draw($history = false) {
		global $_SHOP;
		if ( preg_match('/_disc$/', $_REQUEST['action']) or preg_match('/_pmp$/', $_REQUEST['action']) or
    		 preg_match('/_pmz$/' , $_REQUEST['action']) or preg_match('/_category$/', $_REQUEST['action']) or
  			 preg_match('/_pm$/'  , $_REQUEST['action']) ) {
			require_once ( "admin/PlaceMapView2.php" );
			$pmp_view = new PlaceMapView( $this->width );
			if ( !$pmp_view->draw($history) ) {
        $this->addJQuery($pmp_view->getJQuery());
				return;
			}

		} elseif ( $_REQUEST['action'] == 'publish' ) {
    	  if($this->state_change(1)) {
          return;
        }
  	} elseif ( $_REQUEST['action'] == 'unpublish' ) {
    	  if($this->state_change(2)) {
          return;
        }

		} elseif ( $_REQUEST['action'] == 'add_sub' ) {
			if ($event = Event::load( (int)$_GET['event_main_id'], false )){
  			$row = ( array )$event;
        $row['event_rep'] = 'sub';
        $row['event_main_id'] = (int)$_GET['event_main_id'];
        unset($row['event_id']);
  			$this->form( $row, null );
        return;
      }
		} elseif ( $_REQUEST['action'] == 'add' ) {
      $event = new Event(true);
			$this->form( (array)$event, null );
      return;
		} elseif ( $_GET['action'] == 'edit' and $_GET['event_id'] ) {
			$event = Event::load( $_GET['event_id'], false );
			$row = ( array )$event;
			if ( !$row ) {
				return $this->table($history);
			}
			$this->form( $row, null );
      return;
		} elseif ( $_POST['action'] == 'save' ) {
      if (!$event = Event::load($_POST['event_id'], false)) {
        $event = new Event(true);
      }
      if (!$event->fillPost() || !$event->saveEx()) {
				$this->form( $_POST, null);
        return;
			}

		} elseif ( $_GET['action'] == 'remove' and $_GET['event_id'] ) {
			If ($event = Event::load( $_GET['event_id'], false )){
			  $event->delete();
      }
		} elseif($_POST['action'] == 'remove_events') {
		  if(count($_REQUEST['cbxEvents']) > 0)
			  foreach($_REQUEST['cbxEvents'] as $eventId){
          $event = Event::load($eventId, false);
          if ($event->event_status !=='pub') {
                $event->delete();
          }
			}
		}
		$this->table($history);
	}

  function event_view (&$data, $stats=0, $pmps=0 ) {
      $data["event_date"] = formatAdminDate($data["event_date"]);
      $data["event_time"] = formatTime($data["event_time"]);
      $data["event_open"] = formatTime($data["event_open"]);
      $event_id = $data["event_id"];

      $agenda = (!$data['event_pm_id'])?' - ' . con('agenda_only'):'';

      echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
      echo "<tr><td colspan='2' class='admin_list_title'>" .$data['event_id']." - ". $data["event_name"] . "{$agenda} </td></tr>";
      $this->print_field('ort_name', $data);
      $this->print_field('event_short_text', $data);
      $this->print_field('event_text', $data);
      $this->print_url('event_url', $data);
      $this->print_field('event_date', $data);
      $this->print_field('event_time', $data);
      $this->print_field('event_open', $data);
      $this->print_field('event_group_name', $data);
      $this->print_field('event_order_limit', $data);
      $this->print_field('event_template', $data);
      $this->view_file('event_image' , $data, $err);
      $this->view_file('event_mp3' , $data, $err, 'mp3');
      if ($data['event_rep'] == 'main') {
         echo "<tr><td colspan='2' class='admin_list_title'>" .
              "<input type=checkbox name='also_sub_{$event_id}' id='also_sub_{$event_id}' style='border:0px;' value=1>
                <label for='also_sub_$event_id'> " . con('also_sub') . "</label></br> </td></tr>";
      }

      if ($data['event_pm_id'] and ($data['event_rep'] == 'sub' or $data['event_rep'] == 'main,sub')) {
          if ($cats = PlaceMapCategory::loadAll($data['event_pm_id'])) {
            foreach($cats as $category) {
              $cat_d = (array)$category;
              $err = $this->state_test($cat_d, $event_d, $stats, $pmps);
              echo "<tr><td class='admin_list_title' colspan='2'>{$cat_d['category_name']}</td></tr>";
              $this->print_field('category_price', $cat_d);
              $this->print_field('category_numbering', $cat_d);
              $this->print_field('category_size', $cat_d);

              if ($cat_d['category_numbering'] != 'none') {
                  $this->print_field('category_pm_id', $cat_d);
                  $this->print_field('category_pmp_id', $cat_d);
              }
              $this->print_field('category_template', $cat_d);
              $errs = ($err or $errs);
            }
          } else {
              $errs = true;
              echo "<tr class='error'><td align='center'>" . con('undefined_category') . '<br></td></tr>';
          }
      }
      echo "</table><br>\n";
   		echo "<input type='hidden' name='cbxEvents[]'  value='{$data['event_id']}'>";

      return $errs;
  }

  function state_test(&$data, $event, $stats, $pmps) {
    if (empty($stats)) return false;
    $err = false;

    if (!$data['category_template'] and !$event['event_template']) {
        $data['category_template'] = '<div class=warning>' . con('undefined') . '</div>';
        // $err=TRUE;
    } else if (!$data['category_template'] and $event['event_template']) {
        $data['category_template'] = $event['event_template'];
    }

    if ($data['category_numbering'] != 'none') {
        $data['category_size'] = $stats[$data['category_ident']];
        if (empty($pmps[$data['category_ident']])) {
            $err = true;
            $data['category_pmp_id'] = '<div class=error>' . con('undefined') . '</div>';
        } else if (sizeof($pmps[$data['category_ident']]) > 1) {
            $data['category_pmp_id'] = '<div class=error>' . implode(',', $pmps[$data['category_ident']]) . '</div>';
            $err = true;
        } else {
            $data['category_pmp_id'] = $pmps[$data['category_ident']][0];
        }
    }

    if (!$data['category_size']) {
        $data['category_size'] = '<div class=error>0</div>';
        $err = true;
    }

    if ($data['category_price'] == 0) {
        $data['category_price'] = '<div class=error>0.00</div>';
        $err = true;
    }
    return $err;
	}


  function state_confirm_button ($state, $show_button = true) {
    $names = array(1=>'pub_confirm_msg', 2=>'nosale_confirm_msg',3=>'pub_confirm_msg', 4=>'pub_events_confirm_msg');
   // echo $names[$state], ' - ', $state;
    echo "<div class='info'><br>" . con($names[$state]).'<br>';
    if ($show_button) {
       echo "<input type='hidden' name='action' value='{$_REQUEST['action']}'><br>
       <input type='submit' name='confirm' value='".con('confirm_yes')."'>
       <input type='button' name='goback' onclick='location.href=\"view_event.php\"' value='".con('confirm_no')."'>";
       echo "</form>";
     } else {
	     echo "<form action='{$_SERVER['PHP_SELF']}' method='POST'>";
     }

     echo "</div>";
  }

  function state_change_event ($state, $event) {

    if ($event->event_rep == 'sub'){
       $date = formatAdminDate($sub->event_date);
    } else {
       $date = $event->event_name;
    }

    if ($state == 1 and $event->event_status == 'unpub') {
         $oke = $event->publish($stats, $pmps);
         $result = 'pub_';
         $oldstate = 'unpub';
    } elseif ($state == 1 and $event->event_status == 'nosal') {
         $oke = $event->restart_sales();
         $result = 'restart_';
         $oldstate = 'nosal';
    } elseif($state == 2 and $event->event_status == 'pub') {
       $oke = $event->stop_sales();
       $result = 'stop_';
       $oldstate = 'pub';
    } else
      return '';

    if ($oke){
      $log = "<div class='success'> <b>'$date'</b> " . con($result.'success') . "</div>\n";
      if ($event->event_rep == 'main' and $_POST['also_sub_'.$event->event_id] and $subs = Event::loadAllSubs($event->event_id)) {
        foreach($subs as $sub) {
          if ($sub->event_status == $oldstate) {
            $log .= $this->state_change_event($state, $sub);
          }
        }
      }
    } else {
      $log = "<div class='err'> <b>'$date'</b> " . con($result.'failure') . "</div>\n";
    }
    return $log;
  }

  function state_change ($state)    {
      global $_SHOP;
      $varNum = 0;
      $log    = '';
      if (count($_REQUEST['cbxEvents']) > 0) {
      	foreach($_REQUEST['cbxEvents'] as $eventID) {
          if ($event = Event::load($eventID, false)) {
            if ($state == 1 and $event->event_status == 'unpub' and $_POST['confirm'] !== con('confirm_yes') ) {
              //echo $event->event_status;
              unset($stats);
              unset($pmps);
              $event->publish($stats, $pmps, true);
             	$event_d = (array)$event;
             	if($varNum==0) $this->state_confirm_button($state, false);
             	$errs = $this->event_view($event_d, $stats, $pmps, false) or $errs;
             	$varNum++;
            } else {
              $log .= $this->state_change_event ($state, $event);
            }
          }
        }
        If ($log) addWarning($log);

        if($varNum!==0) {
          if ($errs) {
            echo "<br><div class=error align=center>" . con('correct_errors_first') . "<br></div>";
          } else {
            $this->state_confirm_button($state);
          }
        } else {
//          $this->delayedLocation('view_event.php');
        }
	      return true;

      } elseif (count($_POST['cbxEvents']) > 0) {
		     $varNum = 0;
         $errs = false;
          if ($state == 1) {
             $oldstate = 'unpub';
          } elseif($state == 2) {
             $oldstate = 'pub';
          } elseif($state == 3) {
             $oldstate = 'nosal';
          }

		     foreach($_POST['cbxEvents'] as $eventID) {
            if ($event = Event::load($eventID, false) and $event->event_status == $oldstate ) {
              $stats = 0;
              if ($state == 1) {
                $event->publish($stats, $pmps, true);
              }
            	$event_d = (array)$event;
            	if($varNum==0) $this->state_confirm_button($state, false);
            	$errs = $this->state_event_view($event_d, $stats, $pmps, false) or $errs;
            }
		     }

		      return $varNum!==0;

      }  else
        return false;
  }

  function fill_images() {
		$img_pub['pub'] = array(
            "src" => '../images/grun.png',
            'title' => con('icon_unpublish'),
            'alt' => con('icon_unpublish_alt'),
            'link' => "view_event.php?action=unpublish&cbxEvents[]=" );

		$img_pub['unpub'] = array(
            "src" => '../images/rot.png',
            'title' => con('icon_publish'),
            'alt' => con('icon_publish_alt'),
            'link' => "view_event.php?action=publish&cbxEvents[]=" );

		$img_pub['nosal'] = array(
            "src" => '../images/grey.png',
            "title" => con('icon_nosal'),
            "alt" => con('icon_nosal_alt'),
            "link" => "view_event.php?action=publish&cbxEvents[]=" );

		return $img_pub;
	}

}
?>