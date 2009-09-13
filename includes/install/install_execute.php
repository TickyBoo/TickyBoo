<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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
 
class install_execute {
  function precheck($Install) {
    global $_SHOP;
    $includes_dir = ROOT . "includes";
    if (!$init_config = @file_get_contents("$includes_dir/install/init_config.php")){
      array_push($Install->Errors,"Cannot read master configuration file <i>$includes_dir/install/init_config.php</i>.");
      return true;
    }

    $install_mode=$_SESSION['radio'];

    OpenDatabase();
    if (!$_SHOP->link) {
      array_push($Install->Errors,"<div class=err>ERROR: can not connect to the database</div>");
      return true;
    }

    if($Install_Type == 'NORMAL'){
      $Table_Names = ShopDB::TableList('');
      for ($i=0;$i<count($Table_Names);$i++){
        ShopDB::query("drop table ".$Table_Names[$i]);
      }
    }

    global $tbls;
    require_once("../includes/install/install_db.php");
    if ($errors = ShopDB::DatabaseUpgrade($tbls, true)){
      $Install->Errors[] = $errors;
      return true;
    }

    if ($install_mode == 'NORMAL'){
      // import contens of mysqldump to db
      if (!file_to_db("$install_dir/includes/install/base_sql.sql")){
        array_push($Install->Errors,"Can not create database structure!");
        return true;
      }

      if ($_POST['db_demos']==1 and !file_to_db("$install_dir/includes/install/demo_sql.sql")){
        array_push($Install_Errors,"Can't fill the demostration data!");
        return true;
      }
      $query = "update Admin set
                  admin_login='{$_SESSION['admin_login']}',
                  admin_password=md5('{$_SESSION['admin_password']}'
                  admin_status='admin')";

      if (!shopDB::query($query)){
        array_push($Install_Errors,"Admin user can not be created!");
        return true;
      }
    }
    shopDB::query("UPDATE Template set template_status='new'");
    shopDB::query("UPDATE Template set template_type='systm' where template_name='forgot_passwd'");
    shopDB::query("UPDATE Template set template_type='systm' where template_name='Signup_email'");
    shopDB::query("UPDATE Template set template_type='systm' where template_name='email_res'");
    install_execute::CreateConfig();
    return true;
  }

  function postcheck($Install) {
    return true;
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
 //   unset($_SESSION['SHOP']['install_dir']);
    foreach ($_SESSION['SHOP'] as $key =>$value) {
      $value = _esc($value);
      $config .= "\$_SHOP->{$key} = {$value};\n";
    }

    $config .= "\n?>";
    return file_put_contents (ROOT."includes".DS."config".DS."install_config.php", $config);
  }
  function display() {
    Install_Form_Open (BASE_URL."/index.php",'', false);
    echo "<h2>Installation Completed</h2>You are now ready to start using Fusion Ticket.<br />\n";
    echo "For security reasons you should put the configuratin file and folder to read-only by webserver:<br>
          - <i>includes/config/init_config.php</i>";
    if (is_writable("$install_dir/includes/config")) {
      echo "<br>-  <i>includes/config</i>";
    }

    echo "<div><br>You should also delete the <i>inst</i> folder.</div>
          <br>
          <ul>
            <li><a href='".BASE_URL."/admin/index.php' target='_blank'>Go to Admin</a>.</li>
            <li><a href='".BASE_URL."/pos/index.php' target='_blank'>Go to Box Office</a></li>
            <li><a href='".BASE_URL."/control/index.php' target='_blank'>Go to Ticket Control Point</a></li>
          </ul>";

    Install_Form_Buttons ();
    Install_Form_Close ();
//        session_destroy();
  }  
}
?>