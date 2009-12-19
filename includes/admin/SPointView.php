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
require_once ( "admin/AdminView.php" );

class SPointView extends AdminView {

	function spoint_view( &$data ) {
		$data['kasse_name'] = $data["user_lastname"];
		echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
		echo "<tr><td colspan='2' class='admin_list_title'>" . $data["kasse_name"] .
			"</td></tr>";

		$data["user_country_name"] = $this->getCountry( $user["user_country"] );

		$this->print_field( 'user_id', $data );
		$this->print_field( 'kasse_name', $data );

		$this->print_field( 'user_address', $data );
		$this->print_field( 'user_address1', $data );
		$this->print_field( 'user_zip', $data );
		$this->print_field( 'user_city', $data );
		$this->print_field( 'user_country_name', $data );

		$this->print_field( 'user_phone', $data );
		$this->print_field( 'user_fax', $data );
		$this->print_field( 'user_email', $data );
		$this->print_field( 'user_prefs', $data );

		$this->print_field( 'login', $data );

		echo "</table>";
		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list .
			"</a></center>";

	}
	function spoint_form( &$data, &$err, $title, $add = 'add' ) {
		echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
		echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
		echo "<tr><td class='admin_list_title' colspan='2'>" . $title . "</td></tr>";

		$this->print_field_o( 'user_id', $data );
		$this->print_input( 'kasse_name', $data, $err, 30, 50 );
		//  $this->print_input('user_firstname',$data,$err,30,50);

		$this->print_input( 'user_address', $data, $err, 30, 75 );
		$this->print_input( 'user_address1', $data, $err, 30, 75 );
		$this->print_input( 'user_zip', $data, $err, 8, 20 );
		$this->print_input( 'user_city', $data, $err, 30, 50 );
		$this->print_input( 'user_state', $data, $err, 30, 50 );
		echo "<td class='admin_name'>" .con('user_country'). "</td><td class='admin_value'>";
		$this->print_countrylist( 'user_country', $data['user_country'], $err );
		echo "</td></tr>";

		$this->print_input( 'user_phone', $data, $err, 30, 50 );
		$this->print_input( 'user_fax', $data, $err, 30, 50 );
		$this->print_input( 'user_email', $data, $err, 30, 50 );

		$this->print_select( 'user_prefs', $data, $err, array('pdt', 'pdf') );

		$this->print_input( 'user_nickname', $data, $err, 30, 50 );

		if ( $add == 'add' ) {
			echo "<tr> <td class='admin_name'>" . password . "</td>
         <td class='admin_value'><input type='password' name='password1' size='10'  maxlength='10'><span class='err'>{$err['password']}</span></td></tr>
         <tr> <td class='admin_name'>" . password2 . "</td>
         <td class='admin_value'><input type='password' name='password2' size='10'  maxlength='10'></td></tr>";
		}
		if ( $add == 'update' ) {
			echo "<tr> <td class='admin_name'>" . old_password . "</td>
         <td><input type='password' name='old_password' size='10'  maxlength='10'>
	 <span class='err'>{$err['old_password']}</span></td></tr>
         <tr> <td class='admin_name'>" . new_password . "</td>
         <td class='admin_value'><input type='password' name='new_password1' size='10'  maxlength='10'><span class='err'>{$err['new_password']}</span></td> </tr>
	 <tr> <td class='admin_name'>" . password2 . "</td>
         <td class='admin_value'><input type='password' name='new_password2' size='10'  maxlength='10'></td></tr>";
		}
		if ( $data['user_id'] ) {
			echo "<input type='hidden' name='user_id' value='{$data['user_id']}'/>\n";
			echo "<input type='hidden' name='action' value='update'/>\n";
		} else {
			echo "<input type='hidden' name='action' value='insert'/>\n";
		}

		echo "<tr><td align='center' class='admin_value' colspan='2'>
    <input type='submit' name='submit' value='" . save . "'>
  <input type='reset' name='reset' value='" . res . "'></td></tr>";
		echo "</table></form>\n";

		echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list .
			"</a></center>";
	}

	function spoint_list() {
		global $_SHOP;

		$query = "SELECT * FROM User WHERE user_status='1' ";
		if ( !$res = ShopDB::query($query) ) {
			user_error( shopDB::error() );
			return;
		}

		$alt = 0;
		echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
		echo "<tr><td class='admin_list_title' colspan='8' align='center'>" .
			spoint_title . "</td></tr>\n";


		while ( $row = shopDB::fetch_assoc($res) ) {
			echo "<tr class='admin_list_row_$alt'>";
			//    echo "<td class='admin_list_item'>{$row['user_id']}</td>\n";
			echo "<td class='admin_list_item' width='33%'>{$row['user_lastname']}</td>\n";
			echo "<td class='admin_list_item' width='59%'>{$row['user_address']} {$row['user_address1']} {$row['user_city']}</td>\n";
			echo "<td class='admin_list_item' nowrap='nowrap' align='right'>";
			//            <a class='link' href='{$_SERVER['PHP_SELF']}?action=view&user_id={$row['user_id']}'><img src='../images/view.png' border='0' alt='".view."' title='".view."'></a></td>\n";
			echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&user_id={$row['user_id']}'><img src='../images/edit.gif' border='0' alt='" .
				edit . "' title='" . edit . "'></a>\n";
			echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&user_id={$row['user_id']}\";}'><img src='../images/trash.png' border='0' alt='" .
				remove . "' title='" . remove . "'></a>\n";
			echo "</td></tr>";
			$alt = ( $alt + 1 ) % 2;
		}
		echo "</table>\n";

		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>" .
			add . "</a></center>";
	}

	function draw() {
		if ( $_POST['action'] == 'insert' ) {

			if ( !$this->spoint_check($_POST, $err) ) {
				$this->spoint_form( $_POST, $err, spoint_add_title );
			} else {
				$query = "INSERT INTO User (user_lastname, user_address,user_address1,user_zip,user_city,user_state,user_country,user_email,user_phone,user_prefs,user_fax,user_status)" .
					" VALUES (" . _ESC( $_POST['kasse_name'] ) . ",
            " . _ESC( $_POST['user_address'] ) . ",
            " . _ESC( $_POST['user_address1'] ) . ",
            " . _ESC( $_POST['user_zip'] ) . ",
            " . _ESC( $_POST['user_city'] ) . ",
            " . _ESC( $_POST['user_state'] ) . ",
            " . _ESC( $_POST['user_country'] ) . ",
            " . _ESC( $_POST['user_email'] ) . ",
     		" . _ESC( $_POST['user_phone'] ) . ",
	        " . _ESC( $_POST['user_prefs'] ) . ",
            " . _ESC( $_POST['user_fax'] ) . ",
	          '1')";
				if ( !ShopDB::query($query) ) {
					user_error( shopDB::error() );
					return 0;
				}

				if ( $user_id = shopDB::insert_id() ) {
					$query = "insert into SPoint (login, password, user_id) VALUES (
                    " . _ESC( $_POST['user_nickname'] ) . ",
                    " . _ESC( md5($_POST['password1']) ) . ",
                    " . _ESC( $user_id ) . ")";

					if ( !ShopDB::query($query) ) {
						user_error( shopDB::error() );
						return false;
					}
				}
				$this->spoint_list();
			}
		} elseif ( $_POST['action'] == 'update' ) {
			if ( !$this->spoint_check($_POST, $err) ) {
				$this->spoint_form( $_POST, $err, spoint_update_title, 'update' );
			} else {
				$query = " UPDATE User set
            user_lastname=" . _ESC( $_POST['kasse_name'] ) . ",
            user_address=" . _ESC( $_POST['user_address'] ) . ",
            user_address1=" . _ESC( $_POST['user_address1'] ) . ",
            user_zip=" . _ESC( $_POST['user_zip'] ) . ",
            user_city=" . _ESC( $_POST['user_city'] ) . ",
            user_state=" . _ESC( $_POST['user_state'] ) . ",
            user_country=" . _ESC( $_POST['user_country'] ) . ",
            user_email=" . _ESC( $_POST['user_email'] ) . ",
	          user_phone=" . _ESC( $_POST['user_phone'] ) . ",
	          user_prefs=" . _ESC( $_POST['user_prefs'] ) . ",
            user_fax=" . _ESC( $_POST['user_fax'] ) . ",user_status='1'
            where user_id=" . _esc( $_POST["user_id"] );

				if ( !ShopDB::query($query) ) {
					user_error( shopDB::error() );
					return 0;
				}

				if ( isset($_POST["old_password"]) and isset($_POST["new_password1"]) and isset
					($_POST['user_nickname']) ) {
					$query = "UPDATE SPoint set
               login=" . _ESC( $_POST['user_nickname'] ) . ",
               password=" . _ESC( md5($_POST['new_password1']) ) . "
             where user_id=" . _ESC( $_POST["user_id"] ) . "
	           and password=" . _ESC( md5($_POST['old_password']) );

					if ( !ShopDB::query($query) ) {
						user_error( shopDB::error() );
						return false;
					}

				}
				$this->spoint_list();
			}
		} elseif ( $_GET['action'] == 'add' ) {
			$this->spoint_form( $row, $err, spoint_add_title );

		} elseif ( $_GET['action'] == 'edit' ) {
			$query = "SELECT * FROM User left join SPoint on SPoint.user_id = User.user_id
                  WHERE User.user_id=" . _esc( (int)$_GET['user_id'] ) ;
			if ( !$row = ShopDB::query_one_row($query) ) {
				user_error( shopDB::error() );
				return 0;
			}
			$row['kasse_name'] = $row['user_lastname'];
			$row['user_nickname'] = $row['login'];

			$this->spoint_form( $row, $err, spoint_update_title, 'update' );
		} elseif ( $_GET['action'] == 'remove' and $_GET['user_id'] > 0 ) {
			$query = "DELETE FROM User WHERE user_id="._esc((int)$_GET['user_id']);
			if ( !ShopDB::query($query) ) {
				user_error( shopDB::error() );
				return 0;
			}
			$query = "DELETE FROM SPoint WHERE user_id="._esc((int)$_GET['user_id']);
			if ( !ShopDB::query($query) ) {
				user_error( shopDB::error() );
				return 0;
			}
			$this->spoint_list();
		} elseif ( $_GET['action'] == 'view' ) {
			$query = "SELECT * FROM User left join SPoint on SPoint.user_id = User.user_id
                   WHERE User.user_id=" . _esc( (int)$_GET['user_id']."
                   and user.user_status=1" ) ;
			if ( !$row = ShopDB::query_one_row($query) ) {
				user_error( shopDB::error() );
				return 0;
			}
			$this->spoint_view( $row );
		} else {
			$this->spoint_list();
		}
	}

	function spoint_check( &$data, &$err ) {
		if ( empty($data['kasse_name']) ) {
			$err['kasse_name'] = mandatory;
		}
		if ( empty($data['user_address']) ) {
			$err['user_address'] = mandatory;
		}
		if ( empty($data['user_zip']) ) {
			$err['user_zip'] = mandatory;
		}
		if ( empty($data['user_city']) ) {
			$err['user_city'] = mandatory;
		}
		if ( empty($data['user_country']) ) {
			$err['user_country'] = mandatory;
		}
		if ( empty($data['user_nickname']) ) {
			$err['user_nickname'] = mandatory;
		}

		if ( $nickname = $data['user_nickname'] and $data["action"] == 'insert' ) {
			$query = "select Count(*) as count from SPoint where login='$nickname'";
			if ( !$res = ShopDB::query_one_row($query, false) ) {
				user_error( shopDB::error() );
				return 0;
			}
			if ( $res["count"] > 0 ) {
				$err['user_nickname'] = already_exist;
			}
		}
		//if(empty($data['user_email'])){$err['user_email']=mandatory;}
		if ( $email = $data['user_email'] ) {
			$check_mail = preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$email );
			if ( !$check_mail ) {
				$err['user_email'] = not_valid_email;
			}
		}
		if ( !$data["user_id"] ) {
			if ( empty($data['password1']) or empty($data['password2']) ) {
				$err['password'] = invalid;
			}
			if ( $data['password1'] and $data['password2'] ) {
				if ( $data['password1'] != $data['password2'] ) {
					$err['password'] = pass_not_egal;
				}
				if ( strlen($data['password1']) < 5 ) {
					$err['password'] = pass_too_short;
				}
			}
		}
		if ( $data["user_id"] ) {
			if ( $pass = $data["old_password"] ) {
				$query = "select password from SPoint where user_id=" . _esc( $data["user_id"] );
				if ( !$row = ShopDB::query_one_row($query) ) {
					user_error( shopDB::error() );
					return 0;
				}
				if ( md5($pass) != $row["password"] ) {
					$err["old_password"] = login_invalid;
				} else {
					if ( $data['new_password1'] != $data['new_password2'] ) {
						$err['new_password'] = pass_not_egal;
					}
					if ( strlen($data['new_password1']) < 5 ) {
						$err['new_password'] = pass_too_short;
					}

				}

			}
		}
		return empty( $err );
	}
}

?>