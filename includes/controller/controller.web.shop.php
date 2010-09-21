<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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



/*/Check page is secure
if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
$url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='$url';</script>"; exit;
//header("Location: https://"$_SHOP->root_secured.$_SERVER['SCRIPT_NAME']);exit;}
}
//remove the www. to stop certificate errors.
if(("https://".$_SERVER['SERVER_NAME']."/") != ($_SHOP->root_secured)) {
$url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='$url';</script>"; exit;
}*/

require_once (CLASSES.'class.smarty.php');


// remove the # below under linux to get a list of locale tags.

#  print_r(list_system_locales());



class ctrlWebShop  {
  protected $smarty ;
  protected $HelperList = array();
  protected $context = '';

  public function __construct($context='web') {
    $this->smarty = new MySmarty($this);
    $this->context = $context;
    $this->Loadplugins(array('MyCart','User','Order','Update'));
    if (strtolower($context) == 'pos') {
      $this->Loadplugins(array('POS'));
    }
    require_once (INC. 'config'.DS.'init.php' );
    $this->initPlugins();
    $this->smarty->init($context);
    plugin::call('*Pageload', $this);
  }

  public function draw($fond, $isAjax= false) {
    $this->smarty->display(is($fond, 'shop') . '.tpl');
    orphanCheck();
    trace("End of shop \n\n\r");
  }

  protected function checkSSL(){
    global $_SHOP;
//    print_r($_SERVER);
    if ($_SHOP->secure_site) {
      $url = $_SHOP->root_secured.basename($_SERVER['SCRIPT_NAME']);
      if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
        header("Location: $url");
        exit;
      }
    } elseif($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
      addWarning('This_page_is_not_secure');
    }
    /* */
  }

  public function assign($tpl_var, $value = null) {
    return $this->smarty->assign($tpl_var, $value);
  }

  public function Loadplugins($pluginList) {
    foreach ($pluginList as $plugin) {
      $filename = 'smarty.'.strtolower($plugin).'.php';
      require_once (CLASSES.$filename);
      $this->HelperList[]=$plugin;
    }
  }

  protected function initPlugins() {
    foreach ($this->HelperList as $plugin) {
      $classname = $plugin.'_smarty';
      $plugin = "__{$plugin}";
      $this->$plugin  = new $classname($this->smarty);
    }
  }
}

?>