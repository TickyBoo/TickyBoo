<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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
require_once(INC."classes/redundantdatachecker.php");
require_once(INC."classes".DS."class.model.php");
require_once(INC."classes".DS."model.organizer.php");


class install_execute {

  function precheck($Install) {
    global $_SHOP;
    RemoveDir(ROOT."includes/temp",false);
    $install_mode=$_SESSION['radio'];

    OpenDatabase();
    if (!ShopDB::$link) {
      array_push($Install->Errors,"Can not connect to the database.");
      return true;
    }

    if($install_mode == 'NORMAL'){
      $Table_Names = ShopDB::TableList('');
      for ($i=0;$i<count($Table_Names);$i++){
        ShopDB::query("drop table `{$Table_Names[$i]}`");
      }
    }

    global $tbls;
    require_once(ROOT."includes/install/install_db.php");
    if ($errors = ShopDB::DatabaseUpgrade($tbls, true)){
      foreach ($errors as $data) {
        if ($data['error']) {
          $Install->Errors[] = "<pre>".$data['changes']. $data['error']."</pre>";
        }
      }
      if ($Install->Errors) return true;
    }
    if (ShopDB::Tableexists('SPoint')){
         $Install->Warnings[] = "<pre>Migrated Spoint</pre>";
      self::MigrateSpoint();
    }
    if (ShopDB::Tableexists('Control')){
         $Install->Warnings[] = "<pre>Migrated Control</pre>";
      self::MigrateControl();
    }
    if ($install_mode == 'NORMAL'){
      // import contens of mysqldump to db
      if ($error = file_to_db(ROOT."includes/install/base_sql.sql")){
        array_push($Install->Errors,$error);
        return true;
      }

      if ($_SESSION['db_demos']==1 and $error = file_to_db(ROOT."includes/install/demo_sql.sql")){
        array_push($Install->Errors,$error);
        return true;
      }
      $query = "update Admin set
                  admin_login='{$_SESSION['admin_login']}',
                  admin_password=md5('{$_SESSION['admin_password']}'),
                  admin_status='admin'
                where admin_id = 1";

      if (!shopDB::query($query)){
        array_push($Install->Errors,"Admin user can not be created!".ShopDB::error());
        return true;
      }
    }

    install_execute::CreateConfig();

    if (getophandata()!=='none') {
      array_push($Install->Warnings,'After the update the installer found some problems with your database.<br>'.
                                    'To use with the new version we suggest to fix the database or create an new database.');

      return true ;
    }

    $org = Organizer::load();
    $org->_fill($_SESSION['ORG']);
    if (!$org->saveex()){
      array_push($Install->Warnings,"It was not possible to save the merchant data!".ShopDB::error());
    }

    shopDB::query("UPDATE Template set template_status='new'");
    shopDB::query("UPDATE Template set template_type='systm' where  template_type='email' and template_name='forgot_passwd'");
    shopDB::query("UPDATE Template set template_type='systm' where  template_type='email' and template_name='Signup_email'");
    shopDB::query("UPDATE Template set template_type='systm' where  template_type='email' and template_name='email_res'");
    Orphans::clearZeros('Category',     array('category_pm_id','category_event_id','category_pmp_id'));
    Orphans::clearZeros('Event',        array('event_group_id','event_main_id'));
    Orphans::clearZeros('Order',        array('order_owner_id'));
    Orphans::clearZeros('PlaceMapPart', array('pmp_pm_id','pmp_ort_id','pmp_event_id'));
    Orphans::clearZeros('Seat',         array('seat_category_id','seat_zone_id' ,'seat_user_id' ,
                                              'seat_order_id'   ,'seat_pmp_id'  ,'seat_discount_id'));

    return false;
  }

  function postcheck($Install) {
    if ($_POST['fixdatabase1']==2) {
      renameTables(array('Category','Category_stat','Discount','Event','Event_group','Event_stat',
                         'PlaceMap2','PlaceMapPart','PlaceMapZone','Seat','Order'));
      array_push($Install->Warnings,"The next tables are renamed: Category, Category_stat, Discount, Event, Event_group, Event_stat,
                                     PlaceMap2, PlaceMapPart, PlaceMapZone, Seat, Order. You can copy the data back yourself.");
    }
    return false;
  }

  function CreateConfig() {
    $config = "<?php\n";
    $config .= "/**\n";
    $config .= "%%%copyright%%%\n";
    $config .= file_get_contents (ROOT."licence.txt")."\n";
    $config .= "*/\n\n";
    $config .= "// The following settings are automatic filled by the installation procedure:\n\n";
    $config .= "global \$_SHOP;\n\n";
    $config .= "define(\"CURRENT_VERSION\",\"".INSTALL_VERSION."\");\n\n";

    $_SESSION['SHOP']['root'] = BASE_URL."/";

    unset($_SESSION['SHOP']['install_dir']);

    if (!isset($_SESSION['SHOP']['root_secured']) or empty($_SESSION['SHOP']['root_secured'])) {
      $_SESSION['SHOP']['root_secured'] = $_SESSION['SHOP']['root'];
    }
    if (!isset($_SESSION['SHOP']['secure_id'])) {
      $_SESSION['SHOP']['secure_id'] = sha1(AUTH_REALM. BASE_URL . uniqid());
    }
    foreach ($_SESSION['SHOP'] as $key =>$value) {
      $value = _esc($value);
      $config .= "\$_SHOP->{$key} = {$value};\n";
    }

    $config .= "\n?>";
    return file_put_contents (ROOT."includes".DS."config".DS."init_config.php", $config);
  }

  function display($Install) {
    global $_SHOP, $orphancheck;
    OpenDatabase();
    if(isset($_GET['fix'])){
      Orphans::dofix($_GET['fix']);
    }
    $data = Orphans::getlist($keys,true,"&do=fix&inst_mode=post&inst_pg={$Install->return_pg}");

    $space = (count($keys)*60 < 780 -200)?1:0;
    Install_Form_Open ($Install->return_pg,'', 'Database Orphan check');

    echo "<table cellpadding=\"1\" cellspacing=\"2\" width='100%'>
            <tr><td>
              The list below gives you a view of the orphans in your database. Look at the our website for instructions how to fix this or contact us on the forum or IRC.
              To be on the save site, we suggest you to create a new database and import the common information in the new database. This can be done by the installer.
            </td></tr>
            <tr> <td height='6px'></td> </tr>
            <tr> <td>
               <input type=\"radio\" name=\"fixdatabase\" value=\"1\" id='fixdatabase1'  checked /><label for='fixdatabase1'> Fix tables manual </label>
               <input type=\"radio\" name=\"fixdatabase\" value=\"2\" id='fixdatabase2' /><label for='fixdatabase2'>  Recreate tables </label>
            </td> </tr>

          </table>";

    echo "<div style='overflow: auto; height: 250px; width:100%; border: 1'>";
    echo "<table cellpadding=\"1\" cellspacing=\"2\" width='100%'>";
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
    echo "</table></div>\n";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }

  function checkadmin($name) {
    $query="select Count(*) as count
            from Admin
            where admin_login= "._esc($name);
    if(!$res=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
    } else
      return ($res["count"]>0);
  }

  function MigrateSpoint() {
    $query = "select * from SPoint";
    $res = ShopDB::Query($query);
    while ($row = ShopDB::fetch_assoc($res)){
      If (self::checkAdmin($row['login'])) $row['login'] = "pos~{$row['login']}";
      $query = "INSERT INTO `Admin` SET ".
         "admin_login = '{$row['login']}',
          admin_password = '{$row['password']}',
          admin_user_id = '{$row['user_id']}',
          admin_status = 'pos'";
      ShopDB::query($query);
    }
    $sql = "RENAME TABLE `SPoint` TO  `old_spoint`"; // The MySQL way.
    ShopDB::query($sql);
  }

  function MigrateControl(){
    $query = "select * from `Control`";
    $res = ShopDB::Query($query);
    while ($row = ShopDB::fetch_assoc($res)){
      If (self::checkAdmin($row['control_login'])) $row['control_login'] = "tt~{$row['control_login']}";
      $query = "INSERT INTO `Admin` SET ".
         "admin_login = '{$row['control_login']}',
          admin_password = '{$row['control_password']}',
          control_event_ids = '{$row['control_event_ids']}',
          admin_status = 'control'";
      ShopDB::query($query);
    }
    $sql = "RENAME TABLE `Control` TO  `old_control`"; // The MySQL way.
    ShopDB::query($sql);
  }

  function renameTables($array) {
    if (is_array($array)) {
      foreach($array as $table) {
        $sql = "RENAME TABLE `{$table}` TO `old_{$table}`"; // The MySQL way.
        ShopDB::query($sql);
      }
    }
  }
}
?>

