<?PHP
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 */

require_once("admin/AdminView.php");
require_once("classes/ShopDB.php");

class ImpExpView extends AdminView {

	//selected export
	var $runtype ;
	var $runobject;

	function ImpExpView(){
    If (isset($_REQUEST['import_type'])) {
      $this->runtype = 'import_';
    } else
    If (isset($_REQUEST['export_type'])) {
         $this->runtype = 'export_';
    }//  else echo "c";
    if ($this->runtype) {
      $runlist = $this->load($this->runtype);
      $run=$_REQUEST[$this->runtype.'type'];
      if(in_array($run,$runlist)){
			  $runclass = $this->runtype.$run;
	  		require_once("admin/$runclass.php");
		  	$this->runobject = new $runclass;
		  }// else echo "a";
	  }//  else echo "b";
 }
	

	function load($filetype) {
    global $_SHOP;
    $content = array();
    $dir = $_SHOP->includes_dir.'/admin';
	  if ($handle = opendir($dir))
		   {
		   while (false !== ($file = readdir($handle)))
          {
             if ($file != "." && $file != ".." && !is_dir($dir.$file) && preg_match("/^{$filetype}(.*?\w+).php/", $file, $matches))
                { $content[] .=  $matches[1];}
          }
		   closedir($handle);
  	}
//  print_r($content );
    return $content;
  }


	function export_list(){
		$this->list_head(export_admin_title,1,'95%');
		$alt=0;
		$types = $this->load('export_');
		foreach($types as $type){
     echo "<tr class='admin_list_row_$alt'> 
           <td class='admin_list_item'>
					 <a href='?export_type=$type' class='link'>".$this->con('export_'.$type)."</a>
					 </td></tr>";
		}
		echo "</table>\n";
	}

	function import_list(){
		$this->list_head(import_admin_title,1,'95%');
		$alt=0;
		$types = $this->load('import_');
		foreach($types as $type){
      echo "<tr class='admin_list_row_$alt'>
            <td class='admin_list_item'>
	  	      <a href='?import_type=$type' class='link'>".$this->con('import_'.$type)."</a>
	 				  </td></tr>";
		}
		echo "</table>\n";
	}

  function execute (){
		if($this->runobject){
      if ($this->runtype=='import_'){
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
      echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";
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