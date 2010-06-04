<?php
/**
* Smarty plugin
* @package Smarty
* @subpackage plugins
*/

/**
* Smarty packerload function plugin
*
* File: function.packerload.php<br>
* Type: function<br>
* Name: packerload<br>
* Date: Jan 23, 2008<br>
* Purpose: join togther javascript and css into current file
* Install: Drop into the plugin directory, place into html
* <code>{packerload type='js' files='../js/user.js|../js/project.js'}</code>.
* Specify minify cachedir. Requires Minify ( http://code.google.co... and PHP 5.2.1+ )
* @author William D. Estrada <losthitchhiker [A t) gM ail dot C o m>
*
* @version 1
* @param string
* @param Smarty
*/

/**
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource...
* http://www.gnu.org/li...
*/

function smarty_function_minify($params, &$smarty)
{

// Retrieve the files to process
$files = $params['files'];

//Base Dir
$base = is($params['base'],false);

// Retrieve type of file
$type = $params['type'];

if($base){
  $url = "b=".$base."&";
}
$url .= "f=".$files;

// Check type and run Minify whether CSS or JS
switch ( $type )
{
case 'css':
$min = '<link type="text/css" rel="stylesheet" href="minify.php?'.$url.'" />';
break;

default:
$min = '<script type="text/javascript" src="minify.php?'.$url.'"></script>';
break;
}

// Return the packed file to be written to the HTML document
return $min;
}
?>