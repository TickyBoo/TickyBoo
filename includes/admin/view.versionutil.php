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
require_once("admin/class.adminview.php");

class VersionUtilView extends AdminView{

  function form ($data, $err, $title){
    global $_SHOP;

    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>";
  	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

    $this->print_input('organizer_name'   ,$data, $err,25,100);
    $this->print_input('organizer_address',$data, $err,25,100);
    $this->print_input('organizer_plz'    ,$data, $err,25,100);
    $this->print_input('organizer_ort'    ,$data, $err,25,100);
    $this->print_input('organizer_state'  ,$data, $err,25,100);
    $this->print_countrylist('organizer_country', $data, $err);
    $this->print_input('organizer_phone'  ,$data, $err,25,100 );
    $this->print_input('organizer_fax'    ,$data, $err,25,100 );
    $this->print_input('organizer_email'  ,$data, $err,25,100 );
    $this->print_input('organizer_currency',$data, $err,4,3 );

    $this->print_file('organizer_logo'     ,$data, $err);
    $this->form_foot();
  }

  function draw () {
    print_r($_POST);
    if(isset($_POST['are_you_sure']) && isset($_POST['action']) ){
      if($_POST['are_you_sure']==1 && $_POST['action']=='reinstall'){
        $this->install_update(true);
      }
    }else{
      $this->view();  
    }
    
  }
  
  private function view () {
    global $_SHOP;
    $data['curr_ver'] = CURRENT_VERSION;
    
 	  $this->form_head( con('version_checker'),$this->width,2);
    echo "<tr><td class='admin_name'>".con('avaliable_version')."</td><td class='admin_value'>".$this->getLatestVersion()."</td></tr>";
    $this->print_field('curr_ver',$data);
    $this->print_field('curr_build',str_replace('$','',INSTALL_REVISION));
    $this->print_field('InfoWebVersion',  $_SERVER['SERVER_SOFTWARE']);
    $this->print_field('InfoPhpVersion',  phpversion ());
    $this->print_field('InfoMysqlVersion',ShopDB::GetServerInfo ());
    if(function_exists('curl_version')){
      $curlVersion = curl_version();
    }else{
      $curlVersion['version'] = con('missing');
    }
    $this->print_field('InfoCurlVersion',$curlVersion['version']);
    if(function_exists('file_get_contents')){
      $this->print_field('InfoGetFileFunction',con('enabled'));  
    }else{
      $this->print_field('InfoGetFileFunction',con('disabled'));
    }
    if(function_exists('zip_open')){
      $this->print_field('InfoZipLibInstalled',con('enabled'));  
    }else{
      $this->print_field('InfoZipLibInstalled',con('disabled'));
    }
    echo '</table><br/>';
    
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
    $this->form_head(con('down_install_again'),$this->width,2);
    $this->print_hidden('reinstall','true');
    $this->print_hidden('action','reinstall');
    $data['are_you_sure'] = '0';
    $this->print_checkbox('are_you_sure',$data,$err);
    echo "<tr><td colspan=\"2\" >".$this->show_button('submit','reinstall',1)."</td></tr>";
    
    $this->form_foot(2,null,null);
  }
  
  
  private function install_update($force = false){
    
    //Download File
    $data = file_get_contents("http://localhost/pb62.zip");
    if(!$data){
      echo con('file_download_failed');
      return false;
    }
    echo con('file_downloaded');
    
    //Create a unique name and path to save file
    $name = "latest".md5(rand(0,100)).".zip";
    $path = INC."temp".DS.$name;
    
    //Save File
    file_put_contents($path, $data);
    echo con('file_saved');
    
    //Get unzipper class
    require_once(LIBS."zip".DS."unzip.lib.php");
    $zip = new SimpleUnzip();
    $entries = $zip->ReadFile($path);
    
    //Create Install directory (normaly root as your updating this install!)
    $installDir = ROOT;    
    mkdir($installDir);
    echo con('install_dir')." : ".$installDir; 
    
    /* */
    foreach ($entries as $entry){
      mkdir($installDir.DS.$entry->Path);
      $entryPath = $installDir.$entry->Path .DS.$entry->Name;
      
      echo $entryPath;
      
      if(!empty($entry->Data)){
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
        //$fh = fopen($entryPath, 'w', false);
        //fwrite($fh,$entry->Data); //DO NOT!! COMMENT OUT, WITHOUT COMMENTING OUT THE LINE ABOVE! BAD THINGS HAPPEN!!!!!!
        //fclose($fh);
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
        echo " .... ".con('updated')."<br />";
      }else{
        echo " ".con('not_updated')."<br />";
      }
    }
    /* */
    
    if(!$err){
      $this->addJQuery('window.location.replace("../inst/");');  
    }
    
    unlink($path);
    echo con("file_deleted");
  }
  
  
}
?>