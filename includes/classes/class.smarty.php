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

require(INC.'smarty/Smarty.class.php');

//$smarty->force_compile = true;

class MySmarty extends Smarty {
  public $layoutName = 'theme.html';
  public $ShowThema  = false;
  public $title ='testing';
  public $headerNote;
  public $footNote;
  public $buttons;
  public $controllor;
  public function __construct($controllor) {
    global $_SHOP;

    $this->exception_handler = array($this, exception_handler);
    $this->controllor = $controllor;

    parent::__construct();

    $this->caching        = false;
    $this->cache_id       = 'cache_';
    $this->cache_lifetime = 120;
    $this->config_dir   = INC . 'lang'.DS;
    $controllor->loadPlugins(array('gui'));
    if (isset($this->smarty->register)) {
      $this->smarty->register->templateFunction('showTheme', array(&$this,'_SetTheme'));
      $this->smarty->register->templateFunction('redirect', array(&$this,'_ReDirect'));
      $this->smarty->register->templateFunction('con', 'con');
      $this->smarty->register->block('menuBlock', array(&$this,'_setMenuBlock'));
      $this->smarty->register->prefilter(array(&$this,'Con_prefilter'));
    } else {
      $this->register_Function('showTheme', array(&$this,'_SetTheme'));
      $this->register_Function('redirect', array(&$this,'_ReDirect'));
//      $this->smarty->register_Function('con', 'con');
      $this->register_Block('menuBlock', array(&$this,'_setMenuBlock'));
//      $this->smarty->register_prefilter(array(&$this,'Con_prefilter'));

    }

    $this->_SHOP_db_res = array();
  }

  public function init($context='web') {
    global $_SHOP;

    $this->controllor->__gui->gui_name  ='TblLower';
    $this->controllor->__gui->gui_value ='TblHigher';

    $this->template_dir = array($_SHOP->tpl_dir.$context.DS.'custom'.DS,
                                $_SHOP->tpl_dir.$context.DS.'custum'.DS,
                                $_SHOP->tpl_dir.$context.DS);
 //   $this->default_resource_type = 'mysql';

    $this->cache_dir    = substr(TEMP, 0, -1);
    $this->compile_dir  = substr(TEMP,0, -1); // . '/web/templates_c/';
    $this->compile_id   = $context.'_'.$_SHOP->lang;

    $this->plugins_dir[]  = INC . "shop_plugins".DS;

    $this->assign('action', $_REQUEST['action']);   // This needs to be added later .'_action'
    $this->assign('_SHOP_root', $_SHOP->root);
    $this->assign('_SHOP_root_secured', $_SHOP->root_secured);
    $this->assign('_SHOP_lang', $_SHOP->lang);
    $this->assign('_SHOP_theme', $_SHOP->tpl_dir . "theme".DS. $_SHOP->theme_name.DS );
    $this->assign('_SHOP_themeimages', $_SHOP->images_url . "theme/". $_SHOP->theme_name.'/' );
    $this->assign("_SHOP_files", $_SHOP->files_url );//ROOT.'files'.DS
    $this->assign("_SHOP_images", $_SHOP->images_url);

    $this->assign('organizer_currency', $_SHOP->organizer_data->organizer_currency);
    $this->assign('organizer', $_SHOP->organizer_data);

   // $this->debugging = true;
    $this->debugging_ctrl = false;
  }

  function exception_handler ($exception) {
     echo ($exception->getMessage());

  }

  public function display($template, $cache_id = null, $compile_id = null, $parent = null) {
    $webContent = $this->fetch($template);

    if ($this->ShowThema) { //print_r($this);
      $this->assign('Title'  ,$this->title,true);


      $this->assign('HeaderNote' ,$this->headerNote,true);
      $this->assign('FootNote'   ,$this->footNote,true  );
      $this->assign('Buttons'    ,$this->buttons,true  );
      $this->assign('MenuBlock'  ,$this->menuBlock,true  );
      $this->assign('WebContent', $webContent);

      return parent::display($this->layoutName);
    } else {
      echo $webContent;
      if ($this->debugging) {
          Smarty_Internal_Debug::display_debug($this);
      }
    }
  }

  public function _setMenuBlock($params, $content, $smarty, $repeat) {
    $this->menuBlock =$content;
    return '';
  }

  public function _SetTheme( $params){
    If (isset($params['name'])) {
      $this->layoutName = $params['name'];
    }
    If (isset($params['title'])) {
      $this->title = $params['title'];
    }
    If (isset($params['header'])) {
      $this->headerNote = $params['teader'];
    }
    If (isset($params['footer'])) {
      $this->footNote = $params['footer'];
    }
    If (isset($params['set'])) {
      $this->ShowThema = ((strtoupper($params['set'])==='ON') or
                          (strtoupper($params['set'])==='TRUE'));
    } else {
      $this->ShowThema = true;
    }
  }

  public function _URL( $params, $smarty, $skipnames= array()){
    Global $_CONFIG;
    If (isset($params['url'])) {
      return $_CONFIG->root.$params['url'];
    } else {
      If (!is_array($skipnames)) {$skipnames= array();}
    //  print_r($params);
      $urlparams ='';
      foreach ($params as $key => $value) {
        if (!in_array($key,array('action','controller','module')) and
            !in_array($key,$skipnames)) {
          $urlparams .= (($urlparams)?'&':'').$key.'='.$value;
        }
      }
   //   $urlparams = substr($urlparams,1);
     // print_r($urlparams);
      return makeURL($params['action'], $urlparams, $params['controller'], $params['module']);
    }
  }

  public function _ReDirect( $params, $smarty){
    If (isset($params['_status'])) {
      $status = $params['_status'];
      unset($params['_status']);
    }
    $this->controller->redirect($this->_URL($params, $smarty), $status);
    die;
  }

  function Con_prefilter($source, $smarty) {
 //  echo preg_replace('/\!(\w+)\!/', "con('$1')", $source) ,"\n<br><hr><br>\n";
  //  echo  $source ,"\n<br><hr><br>\n";
     return preg_replace('/\!(\w+)\!/', 'con("$1")', $source);
  }

  public function Loadplugins($pluginList) {
    foreach ($pluginList as $plugin) {
      $filename = 'smarty.'.strtolower($plugin).'.php';
      require_once ($filename);
      $classname = $plugin.'_smarty';
      $plugin = "__{$plugin}";
      $this->$plugin  = new $classname($this);
    }
  }

}
?>