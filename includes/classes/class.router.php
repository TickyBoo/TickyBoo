<?php
/*********************** %%%copyright%%% *****************************************
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

/**
 * Contains the dispatcher-class. Here is where it all starts.
 *
 */

require_once ( dirname(dirname(__FILE__)).'/config/defines.php' );
require_once ( INC.'classes'.DS.'basics.php' );
if (file_exists(LIBS.'FirePHPCore'.DS.'fb.php' )) {
  require_once ( LIBS.'FirePHPCore'.DS.'fb.php' );
  FB::setEnabled(true);

}

class router {
	/**
	 * placeholer-array for all relevant variables a class may need later on (e.g. controller)
	 * [isAjax] => false (boolean, tells you if view got called with /ajax/)
	 * [url] => Array (
	 *       [url] => locations
	 *       [foo] => bar (if url read ?foo=bar)
	 * )
	 * [form] => Array (
	 * 	  (all post-variables, automatically dequoted if needed)
	 * )
	 * [controller] => main (name of the controller of this request)
	 * [action] => index (name of the view of this request)
	 * @var array
	 */

  static function draw($page, $module = 'web', $isAjax= false) {
    GLOBAL $action, $_SHOP;
    if (strpos($module,'/') === false) {
      $controller = 'shop';
    } else {
      $controller = substr($module,   strpos($module,'/')+1);
      $module     = substr($module,0, strpos($module,'/'));
    }
    if (strpos($page,'/') !== false) {
      $action = substr($page ,    strpos($page,'/')+1 );
      $page   = substr($page , 0, strpos($page,'/') );
    }
    if (isset($_REQUEST['action'])) {
      $action=$_REQUEST['action'];
    } elseif(!isset($action)){
      $action=false;
    }
    $_REQUEST['action'] = $action;
    $_GET['action']     = $action;
    $_POST['action']    = $action;
    //echo $controller,'-',$module, '-',$action;
/*
  	if ($action { 0 } == '_') {
  		throw new Exception('Controller [' . $params['controller'] . '] does not allow execution of action [' . $params['action'] . ']');
  	}
*/


    require_once ( INC.'config'.DS.'init_'.$module.'.php' );

		$classname = 'ctrl'.ucfirst($module).ucfirst($controller);
    require_once ( INC.'controller'.DS.'controller.'.$module.'.'.$controller.'.php' );
  	$c = new $classname($module);
    $c->draw($page, $action, $isAjax);
  }
/**
 * start the actual mvc-machinery
 * 1. constructs all needed params by calling constructParams
 * 2. Selects module, 'shop' by default
 * 2. loads the controller
 * 3. sets all needed variables of the controller
 * 4. calls constructClasses of the controller, which in turn constructs all needed models and components
 * 5. render the actual view and layout (if autoRender is true)
 * 6. return the output
 * @param string $url raw url string passed to the array (eg. /main/index/foo/bar)
 */
	function dispatch($url) {
    global $_SHOP;
		$params = self::constructParams($url);

//	Disabled for development.:	try {
  		$classname = 'Ctrl'.ucfirst(strtolower($params['controller']));
			$test = findClass($classname);
			if (empty($test)) {$classname = 'CtrlMain';}

			$c = new $classname;

			$_SHOP->user_root= $c->base = self::constructBase();
      //$_SHOP->user_root_secured=$_SHOP->root_secured;

			$c->params    = & $params['pass'];
			$c->action    = $params['action'];
			$c->module    = $params['module'];
			if (!isset($c->ctrlname)) {
			  $c->ctrlname  = $params['controller'];
      }

			if (in_array('return', array_keys($params)) && $params['return'] == 1) {
				$c->autoRender = false;
			}


			if ($params['action'] { 0 } == '_') {
				throw new Exception('Controller [' . $params['controller'] . '] does not allow execution of action [' . $params['action'] . ']');
			}

			$c->_constructClasses();


//print_r($c);

			if (isset ($params['isAjax']) && ($params['isAjax'] == 1)) {
				$c->layout = null;
			}

   		$c->_dispatch($params);


/* Disabled for development.

	} catch (Exception $e) {
			if (file_exists(INC . "templates" . DS . "theme" . DS . "error.thtml")) {
				include INC . "templates" . DS . "theme" . DS . "error.thtml";
			} else {
				header('HTTP/1.1 500 Internal Server Error');
				echo '<html><head><title>Error</title><body><h1>500 Internal Server Error</h1>',
        $e->getMessage(), '</body></html>';
			}
			return '';
		}

*/

		if ($params['isAjax'] == 1) {
			header('Content-Type: application/xml');
		}

		return $c->output;
	}

/**
 * extract,clean and dequote any given get/post-parameters
 * find out which controller and view we should use
 * @param string $url raw url (see dispatch())
 */
	private function constructParams($url) {
    global $_SHOP;

	  $params['module']     = 'shop';
		$params['controller'] = 'main';
		$params['action']     = 'index';

		if(!empty($url)) {
			$paramList = explode('/', $url);
			$_SHOP->UseRewriteURL = true;

  		//If first var is 'ajax' its an ajax page.
  		if (isset ($paramList[0]) && ($paramList[0]) == 'ajax') {
  			array_shift($paramList);
  			$params['isAjax'] = 1;
  		} else {
  			$params['isAjax'] = 0;
  		}

  		//if if the first input doesnt match the modules below then sets deafult to shop.

      $value = array_shift($paramList);
  		if (is_string($value) && !empty ($value) && file_exists(INC.'controllers'.DS.strtolower($value).DS)) {
  		  $params['module'] = strtolower($value);
  		}

      $value = array_shift($paramList);
  		if (is_string ($value) && !empty ($value)) {
  			$params['controller'] = strtolower($value);
  		}

      $value = array_shift($paramList);
  		if (is_string ($value) && !empty ($value)) {
  			$params['action'] = strtolower($value);
  		}
  		$params['pass'] = $paramList;

		}else{
			$_SHOP->UseRewriteURL = false;

			if(isset($_REQUEST['ajax']) && !empty($_REQUEST['ajax'])) {
  			$params['isAjax'] = 1;
  		} else {
  			$params['isAjax'] = 0;
			}
			if(isset($_REQUEST['mod']) && !empty ($_REQUEST['mod'])) {
				$params['module'] = $_REQUEST['mod'];
			}

			if(isset($_REQUEST['ctrl']) && !empty($_REQUEST['ctrl'] )) {
				$params['controller'] = $_REQUEST['ctrl'];

				if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
					$url = $_REQUEST['action'];
    			$paramList = explode('/', $url);
          $value = array_shift($paramList);
    			$params['action'] = $value;
    			$params['pass']   = $paramList;
				}
			}
		}
		$_REQUEST['ctlr']   = $params['controller'];
		$_REQUEST['action'] = $params['action'] ;
		$_REQUEST['mod']    = $params['module'];

		return $params;
	}

/**
 * tries to construct the base url under which this framework can be called from the browser. adds a "/" at the end
 */
	private function constructBase() {
		$base = 'http' . (env('https') != '' ? 's' : '') . '://' .
		env('SERVER_NAME') . (env('SERVER_PORT') != '80' ? (':' . env('SERVER_PORT')) : '') .
		(dirname(env('PHP_SELF')));
		if (substr($base, -1, 1) != '/') {
			$base .= '/';
		}
		return $base;
	}

} //class
?>