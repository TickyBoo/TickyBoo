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

require_once ( "admin/AdminView.php" );
require_once ( "admin/PlaceMapZoneView.php" );
require_once ( "admin/PlaceMapPartView.php" );
require_once ( "admin/PlaceMapCategoryView.php" );

require_once ( "classes/ShopDB.php" );
require_once ( "classes/PlaceMap.php" );

class PlaceMapView2 extends AdminView {

	/**
	 * PlaceMapView2::pm_view
	 *
	 * Displays the seating for the currently selected event.
	 *
	 * Form > Categories > Zones > Seating Parts > Discounts
	 *
	 *
	 */
	function pm_view( $pm_id, $pm = null ) {
		global $_SHOP;

		$query = "select *
              from PlaceMap2 left join Ort On pm_ort_id=ort_id
              where pm_id=" . _esc( $pm_id );
		if ( $row = ShopDB::query_one_row($query) ) {
			$this->pm_form( $row, $err, con('edit_pm') );
		}
	}

	function pm_form( &$data, &$err, $title ) {
		echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>";

		$this->form_head( $title );

		$this->print_field_o( 'pm_id', $data );

		$pm_id = $data['pm_id'];

		$pm = PlaceMap::load( $pm_id );
		if ( $pm->pm_event_id ) {
			require_once ( 'classes/Event.php' );
			$event = Event::load( $pm->pm_event_id, false );
		}
		$this->print_field( 'ort_name', $data );
		if ( $event ) {
			$live = $event->event_status != 'unpub';

			$data['event_name'] = "{$event->event_name}";
			$data['event_status'] = "{$event->event_status}";
			$data['event_date'] = "{$event->event_date}  {$event->event_time}";
			$this->print_field_o( 'event_name', $data );
			$this->print_field_o( 'event_date', $data );
			$this->print_field_o( 'event_status', $data );
		} else {
			$live = false;
		}

		$this->print_input( 'pm_name', $data, $err, 30, 100 );
		$this->print_file( 'pm_image', $data, $err, 'img' );
		// $this->print_input('pm_width',$data,$err,4,3);
		// $this->print_input('pm_height',$data,$err,4,3);
		$this->form_foot();


		if ( $data['pm_id'] ) {
			echo "<input type=hidden name=action value=update_pm>
                  <input type=hidden name=pm_id value='{$data['pm_id']}'>";
		} else {
			echo "<input type=hidden name=action value=insert_pm>";
		}

		echo "<input type=hidden name=pm_event_id value='{$data['pm_event_id']}'>
             <input type=hidden name=pm_ort_id value='{$data['pm_ort_id']}'>
             </form>";

		if ( $data['pm_id'] ) {
			$pmp_view = new PlaceMapCategoryView( $this->width );
			$pmp_view->category_list( $pm_id, $live );
			echo "<br>";
			$pmz_view = new PlaceMapZoneView( $this->width );
			$pmz_view->pmz_list( $pm_id, $live );
			echo "<br>";
			$pmp_view = new PlaceMapPartView( $this->width );
			$pmp_view->pmp_list( $pm_id, $live );
			echo "<br>";
		}
		if ( $event ) {
			require_once ( "admin/DiscountView.php" );
			$dist_view = new DiscountView( $this->width );
			$dist_view->discount_list( $event->event_id, $live );

			echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list .
				"</a></center>";
		} else {
			//if (!$data['pm_id']) {echo "<br>";}
			echo "   <center><a class='link' href='{$_SERVER['PHP_SELF']}?ort_id={$data['pm_ort_id']}'>
                 " . admin_list . "</a></center>";
		}
	}

	function pm_check( &$data, &$err ) {
		if ( empty($data['pm_name']) ) {
			$err['pm_name'] = mandatory;
		}

		/*if(!$data['pm_width'] or $data['pm_width']<=0){
		* $err['pm_width']=invalid;
		* }

		* if(!$data['pm_height'] or $data['pm_height']<=0){
		* $err['pm_height']=invalid;
		* }
		*/
		return empty( $err );
	}

	function pm_list( $ort_id ) {
		global $_SHOP;

		$mine = true;

		$query = "select * from PlaceMap2 where pm_ort_id=$ort_id and pm_event_id IS NULL";
		if ( !$res = ShopDB::query($query) ) {
			return;
		}

		$alt = 0;
	  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='3'>\n";
	  echo "<tr><td class='admin_list_title' colspan='2' align='left'>" . con('place_maps') . "</td>\n";
    echo "<td colspan=1 align='right'><a class='link' href='{$_SERVER['PHP_SELF']}?action=add_pm&pm_ort_id=$ort_id'>" . add . "</a></td>";
	  echo "</tr>";

		while ( $pm = shopDB::fetch_assoc($res) ) {
			echo "<tr class='admin_list_row_$alt'>";

			echo "<td class='admin_list_item' width='20'>{$pm['pm_id']}</td>\n";
			echo "<td class='admin_list_item' >{$pm['pm_name']}</td>\n";

			echo "<td class='admin_list_item' width=60 align=right>";
			echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$pm['pm_id']}'><img src='images/edit.gif' border='0' alt='" .
				edit . "' title='" . edit . "'></a>\n";
			echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=copy_pm&pm_id={$pm['pm_id']}&pm_ort_id={$pm['pm_ort_id']}'><img src='images/copy.png' border='0' alt='" .
				copy . "' title='" . copy . "'></a>\n";
			echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_pm&pm_id={$pm['pm_id']}&pm_ort_id={$pm['pm_ort_id']}\";}'><img src='images/trash.png' border='0' alt='" .
				remove . "' title='" . remove . "'></a>\n";
			echo "</td></tr>";
			$alt = ( $alt + 1 ) % 2;
		}
		echo "</table>";
	}

	function split_form( $pm_id, $pmp_id ) {
		global $_SHOP;

		if ( !$pm = PlaceMap::load($pm_id) ) {
			return;
		}
		if ( !$pm_parts = PlaceMapPart::loadAll($pm_id) ) {
			return;
		}

		echo "<form action='{$_SERVER['PHP_SELF']}' method=POST>";
		$this->form_head( pm_split );
		if ( !$pmp_id ) {
			echo "<tr><td class='admin_name'  width='40%' >" . split_pm . "</td>
                 <td class='admin_value'>
                 <select name='pm_parts[]' multiple>\n";
			foreach ( $pm_parts as $pmp ) {
				echo "<option value='{$pmp->pmp_id}'>{$pmp->pmp_name}</option>\n";
			}
		} else {
			echo "<input type='hidden' name='pm_parts[]' value='$pmp_id'>";
		}
		echo "</select></td></tr>\n";

		$this->print_checkbox( 'split_zones', $data, $err );
		$this->form_foot();

		echo "
    <input type='hidden' name='action' value='split_pm'>
    <input type='hidden' name='pm_id' value='$pm_id'>
    </form>
    <br>
    <center><a class=link href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id=$pm_id'>" .
			place_map . "</a></center>
    ";
	}

	function draw() {
		global $_SHOP;
		if ( preg_match('/_disc$/', $_REQUEST['action']) ) {
			require_once ( "admin/DiscountView.php" );
			$pmp_view = new DiscountView( $this->width );
			if ( $pmp_view->draw() ) {
				require_once ( 'classes/Event.php' );
				$event = Event::load( $_REQUEST['discount_event_id'], false );
				$this->pm_view( $event->event_pm_id );
			}
		} elseif ( preg_match('/_pmp$/', $_REQUEST['action']) ) {
			$pmp_view = new PlaceMapPartView( $this->width );
			if ( $pmp_view->draw() ) {
				$this->pm_view( $_REQUEST['pm_id'] );
			}
		} elseif ( preg_match('/_pmz$/', $_REQUEST['action']) ) {
			$pmz_view = new PlaceMapZoneView( $this->width );
			if ( $pmz_view->draw() ) {
				$this->pm_view( $_REQUEST['pm_id'] );
			}
		} elseif ( preg_match('/_category$/', $_REQUEST['action']) ) {
			$view = new PlaceMapCategoryView( $this->width );
			if ( $view->draw() ) {
				$this->pm_view( $_REQUEST['pm_id'] );
			}
		} elseif ( $_GET['action'] == 'view_pm' and $_GET['pm_id'] > 0 ) {
			$this->pm_view( $_GET['pm_id'] );

		} elseif ( $_GET['action'] == 'add_pm' ) {
				$this->pm_form( $_GET, $err, '' );

		} elseif ( $_POST['action'] == 'insert_pm' ) {
        if ( !$this->pm_check($_POST, $err) ) {
          $this->pm_form( $_GET, $err, add_pm );
        } else {
          $pm = PlaceMap::create( $_POST['pm_ort_id'], $_POST['pm_name'] );
          if ( $pm_id = $pm->save() ) {
            if ( !$this->photo_post($_POST, $pm_id) ) {
              echo "<div class=error>" . img_loading_problem . "</div>";
            }

            $this->pm_view( $pm_id );
          } else {
            echo '<div class=error>' . cannot_insert_pm . '</div>';
            $this->pm_form( $_GET, $err, add_pm );
          }
        }
      } elseif ( $_GET['action'] == 'copy_pm' and $_GET['pm_id'] > 0 ) {
        if ( $pm = PlaceMap::load($_GET['pm_id']) ) {
          $pm->copy();
          return true;
        }
      } elseif ( $_GET['action'] == 'edit_pm' and $_GET['pm_id'] > 0 ) {
        $query = "select * from PlaceMap2,Ort where pm_id=" . _esc( $_GET['pm_id'] ) .
          " and pm_ort_id=ort_id";
        if ( $row = ShopDB::query_one_row($query) ) {
          $this->pm_form( $row, $err, edit_pm );
        }
      } elseif ( $_POST['action'] == 'update_pm' and $_POST['pm_id'] > 0 ) {
        if ( !$this->pm_check($_POST, $err) ) {
          $this->pm_form( $_POST['pm_id'], $err, edit_pm );
        } else {
          $pm = PlaceMap::load( $_POST['pm_id'] );
          $pm->pm_name = $_POST['pm_name'];
          $pm->save();

          if ( !$this->photo_post($_POST, $pm->pm_id) ) {
            echo "<div class=error>" . img_loading_problem . "</div>";
          }

          if ( $pm->pm_event_id ) {
            $this->pm_view( $pm->pm_id );
          } else {
            //echo $_REQUEST['action']. '  '.$_REQUEST['pm_id'].' '.$_SERVER['PHP_SELF'];
            return true;
          }
        }
      } elseif ( $_GET['action'] == 'remove_pm' and $_GET['pm_id'] > 0 ) {
        $pm = PlaceMap::load( $_GET['pm_id'] );
        $pm->delete();
        return true;
      } elseif ( $_GET['action'] == 'split_pm' and $_GET['pm_id'] > 0 ) {
        $this->split_form( $_GET['pm_id'], $_GET['pmp_id'] );
      } elseif ( $_POST['action'] == 'split_pm' and $_POST['pm_id'] > 0 ) {
        if ($pm = PlaceMap::load($_POST['pm_id']) ) {
          // print_r($_POST);
          $pm->split( $_POST['pm_parts'], $_POST['split_zones'] );
          $this->pm_view( $_POST['pm_id'] );
        }
      } else
        return false;
	}

	function photo_post( $data, $pm_id ) {
		return $this->file_post( $data, $pm_id, 'PlaceMap2', 'pm', '_image' );
	}
}

?>