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
require_once("admin/class.adminview.php");

class ImpExpView extends AdminView {

	//selected export
	var $runtype ;
	var $runobject;

	function __construct($width=0){
    parent::__construct($width);
    If (isset($_REQUEST['import_type'])) {
      $this->runtype = 'import';
    } else
    If (isset($_REQUEST['export_type'])) {
         $this->runtype = 'export';
    }//  else echo "c";
    if ($this->runtype) {
     $runlist = $this->load($this->runtype);
      $run=$_REQUEST[$this->runtype.'_type'];//
      if(in_array($run,$runlist)){
			  $runclass = $this->runtype.'_'.$run;
	  		require_once($_SHOP->includes_dir.DS.'admin'.DS.'transports'.DS."{$this->runtype}.{$run}.php");
		  	$this->runobject = new $runclass;
		  } else {
        addWarning($this->runtype.'_script_not_found');
      }
	  }//  else echo "b";
 }


	function load($filetype) {
    global $_SHOP;
    $content = array();
    $dir = $_SHOP->includes_dir.DS.'admin'.DS.'transports';
	  if ($handle = opendir($dir))
		   {
		   while (false !== ($file = readdir($handle)))
          {
             if ($file != "." && $file != ".." && !is_dir($dir.$file) && preg_match("/^{$filetype}.(.*?\w+).php/", $file, $matches))
                { $content[] .=  $matches[1];}
          }
		   closedir($handle);
  	}
//  print_r($content );
    return $content;
  }


	function export_list(){
		$this->list_head(con('export_admin_title'),1,'98%');
		$alt=0;
		$types = $this->load('export');
		foreach($types as $type){
     echo "<tr class='admin_list_row_$alt'>
           <td class='admin_list_item'>
					 <a href='?export_type=$type' class='link'>".con('export_'.$type)."</a>
					 </td></tr>";
		}
		echo "</table>\n";
	}

	function import_list(){
		$this->list_head(con('import_admin_title'),1,'98%');
		$alt=0;
		$types = $this->load('import');
		foreach($types as $type){
      echo "<tr class='admin_list_row_$alt'>
            <td class='admin_list_item'>
	  	      <a href='?import_type=$type' class='link'>".con('import_'.$type)."</a>
	 				  </td></tr>";
		}
		echo "</table>\n";
	}

  function execute (){
		if($this->runobject){
      if ($this->runtype=='import'){
   			return $this->runobject->import();
      } else {
			  return $this->runobject->export();
      }
		}
		return FALSE;
  }

  function draw (){
		if($this->runobject){
    	$this->runobject->setwidth($this->width);
			$this->runobject->draw();
      echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . con('admin_list') . "</a></center>";
		}else{
      echo "<table border=0 cellspacing='0' cellpadding='0' width='{$this->width}'>
            <tr><td width=50%><tr><td valign='top'>\n";
		    $this->export_list();
		  echo "</td><td align='right' valign='top'>\n";
		    $this->import_list();
      echo "</td></tr></table>\n";
		}
  }
}
?>