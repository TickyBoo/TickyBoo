<?php
/**
%%%%
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
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
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
  global $_SHOP;
//  echo '-'.$pad."<br>\n";
  echo $configpath = realpath(dirname(__FILE__)."/includes/config/");

  if (file_exists($configpath."init_config.php"))
    {
    require_once($configpath."init_config.php");
    }
  else
    {
    echo "<a href='inst/index.php'>Install me now!</a>";
    exit;
    }

	if (ini_get('register_globals')) {

//		echo "Set register_globals to OFF please. Bye.";
//		exit;

		// Emulate register_globals off
		if (ini_get('register_globals')) {
			 $superglobals = array($_SERVER, $_ENV,
					 $_FILES, $_COOKIE, $_POST, $_GET);
			 if (isset($_SESSION)) {
					 array_unshift($superglobals, $_SESSION);
			 }
			 foreach ($superglobals as $superglobal) {
					 foreach ($superglobal as $global => $value) {
							 unset($GLOBALS[$global]);
					 }
			 }
		}

	}

 //Shopping cart and place reservation delay
  //how many times the place can stay reserved
  $_SHOP->res_delay=660;

  //the same value for the shopping cart, usually smaller
  $_SHOP->cart_delay=$_SHOP->res_delay-60;

  $_SHOP->includes_dir=$_SHOP->install_dir."/includes";

  //this folder contains font files required by pdf templates
  //it should be writable by php
  $_SHOP->font_dir=$_SHOP->includes_dir."/fonts";

  //temporary folder
  //should be writeable by php
  $_SHOP->tmp_dir=$_SHOP->includes_dir."/tmp";

  //root of users simple url
  $_SHOP->user_root=$_SHOP->root;

  //root of users secure url
  $_SHOP->user_root_secured=$_SHOP->root_secured;

  //where smarty templates and other tpl related stuff lives
  $_SHOP->tpl_dir=$_SHOP->includes_dir."/tpl";
  
  // this selects the theme that you like to use.
  $_SHOP->theme_dir = $_SHOP->tpl_dir . "/theme/default";

	//default paper size and orientation for pdf files
	//paper size: 'a4', 'legal', etc..or  array(x0,y0,x1,y1), in points
  //or  array(width,height), in centimeters
	//paper orientation: portrait, landscape
	//see ezpdf docs (readme.pdf) for possible values
  $_SHOP->pdf_paper_size="A4";
  $_SHOP->pdf_paper_orientation="portrait";

	//where uploaded files lives (event images, ..)
	//should be writable
  $_SHOP->user_files_url=$_SHOP->root."files/";
  $_SHOP->user_files_dir=$_SHOP->install_dir."/files";

  //mode for directories and files created by phpMyTicket
  $_SHOP->dir_mode=0755;
  $_SHOP->file_mode=0644;

  //external url connection settings, used by connect_func.php
    //choose one of settings:
    
    //1.use libCurl (php should be compiled with libCurl)
    $_SHOP->url_post_method='libCurl';
    
    //2.use php function fsocketopen():
    //$_SHOP->url_post_method='fso';
    
    //3.use external curl command:
    //$_SHOP->url_post_method='curl';
    //$_SHOP->url_post_curl_location='/usr/bin/curl';
    
    set_include_path($_SHOP->install_dir.'/includes'.
    PATH_SEPARATOR.$_SHOP->install_dir.'/includes/pear'.
    PATH_SEPARATOR.get_include_path());
    
    
    ini_set("magic_quotes_runtime",0);
  ini_set('allow_call_time_pass_reference',0);
	//emulates magic_quotes_gpc off
  if (get_magic_quotes_gpc()) {
     function stripslashes_deep($value)
     {
         $value = is_array($value) ?
                     array_map('stripslashes_deep', $value) :
                     stripslashes($value);

         return $value;
     }

     $_POST = array_map('stripslashes_deep', $_POST);
     $_GET = array_map('stripslashes_deep', $_GET);
     $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
  }

	//default organizer_id (attavismus)
	$_SHOP->organizer_id='3';


	//accepted languages
	$_SHOP->langs=array('en');
	$_SHOP->langs_names=array(
	'en'=>'English',
	'de'=>'Deutsch',
	'nl'=>'Nederlands'
	);

	$_SHOP->langs_locales=array(
	'sv'=>'sv_SE'
	);
	$_SHOP->is_admin = false;
?>