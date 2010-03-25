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

  private function view($data) {
    global $_SHOP;
    $data['curr_ver'] = CURRENT_VERSION;

 	  $this->form_head( con('version_checker'), $this->width, 2);
    echo "<tr><td class='admin_name'>".con('avaliable_version')."</td><td class='admin_value'>".$this->getLatestVersion()."</td></tr>";
    echo "<tr><td class='admin_name'>".con('donator_avaliable_version')."</td><td class='admin_value'>".$this->getLatestVersion(true)."</td></tr>";
    $this->print_field('curr_ver', $data);
    $this->print_field('curr_build',str_replace('$','',INSTALL_REVISION));
    $this->print_field('InfoWebVersion',  $_SERVER['SERVER_SOFTWARE']);
    $this->print_field('InfoPhpVersion',  phpversion ());
    $this->print_field('InfoMysqlVersion',ShopDB::GetServerInfo ());
    if(function_exists('curl_version')){
      $curlVersion = curl_version();
    }else{
      $curlVersion['version'] = con('version_missing');
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
    $this->form_head(con('download_update'),$this->width,2);
    $this->print_hidden('reinstall','true');
    $this->print_hidden('action','reinstall');
    $this->print_input('shopconfig_ftusername',$data, $err);
  	$this->print_password('shopconfig_ftpassword',$data, $err);
    $this->print_checkbox('shopconfig_keepdetails',$data, $err);
    $data['are_you_sure'] = '0';
    $this->print_checkbox('are_you_sure',$data,$err);
    $data['are_you_sure_apply_update'] = '0';
    $this->print_checkbox('are_you_sure_apply_update',$data,$err);
    //  echo "<tr><td colspan=\"2\" >".$this->show_button('submit',',1)."</td></tr>";
    require_once(LIBS."file".DS."filescan.class.php");
    echo "<tr><td colspan=\"2\" class='admin_name' >".con('downloaded_update_files')."</td></tr>";
    echo "<tr><td colspan=\"2\" >";
    $files = FileScan::scanDirRec(UPDATES,"zip");
    foreach($files as $file){
      echo $file['name']."<br />";
    }
    echo "</td></tr>";

    $this->form_foot(2,null,'update');
  }

  function draw () {
  ///  print_r($_POST);
    if(isset($_POST['are_you_sure']) && isset($_POST['action']) ){
			if($_POST['shopconfig_keepdetails']==='1' && !empty($_POST['shopconfig_ftusername']) && !empty($_POST['shopconfig_ftpassword'])){
				$query="UPDATE `ShopConfig` SET
            shopconfig_keepdetails='1',  
            shopconfig_ftusername="._esc($_POST['shopconfig_ftusername']) ;
            if(isset($_POST['shopconfig_ftpassword'])){ 
              $query .= " , shopconfig_ftpassword="._esc(base64_encode($_POST['shopconfig_ftpassword'])); 
            } 
	      		$query .= " limit 1 ";

				if(!ShopDB::query($query)){
          addWarning('update_error');
				}else{
          addNotice('Options_saved');
        }
			}elseif($_POST['shopconfig_keepdetails']==='0'){
        $query="UPDATE `ShopConfig` SET 
            shopconfig_ftusername='', 
            shopconfig_ftpassword='', 
            shopconfig_keepdetails='0'  
        		limit 1 ";
        if(!ShopDB::query($query)){
          addWarning('update_error');
        }else{
          addNotice('Options_saved');
        }
			}
      
      if($_POST['are_you_sure']==='1' && $_POST['action']=='reinstall'){
        $query="SELECT * FROM `ShopConfig` limit 1";
        $row=ShopDB::query_one_row($query);
        $ftu = $ftp = null;
    		if($row && !empty($row['shopconfig_ftusername']) && !empty($row['shopconfig_ftpassword'])){
    		  $ftu = $row['shopconfig_ftusername'];
          $ftp = $row['shopconfig_ftpassword'];
    		}
        if(!empty($_POST['shopconfig_ftusername']) && !empty($_POST['shopconfig_ftpassword'])){
          $ftu = $_POST['shopconfig_ftusername'];
          $ftp = base64_encode($_POST['shopconfig_ftpassword']);
        }
        
        $this->install_update(true,$ftu,$ftp);
      }
    }
    $query="SELECT * FROM `ShopConfig` limit 1";
		if($row=ShopDB::query_one_row($query)){
		  unset($row['shopconfig_ftpassword']);
		}
    $this->view($row);

  }



  private function install_update($force = false, $ftu=null, $ftp=null){
    
    $dlLink = $this->getLatestVersion(false,true,$ftu,$ftp);
    
    //Set options for file download
    $ctxOpt['http'] = array();
    if(!empty($_SHOP->shopconfig_proxyaddress) && !empty($_SHOP->shopconfig_proxyport)){
      $ctxOpt['http']['proxy'] = "tcp://".$_SHOP->shopconfig_proxyaddress.":".$_SHOP->shopconfig_proxyport; 
    }
    $ctx = stream_context_create($ctxOpt);
    //Download File
    $data = file_get_contents($dlLink,false,$ctx);
    
    
    if(!$data){
      addWarning('file_download_failed');
      return false;
    }
    addNotice('file_downloaded');

    //Create a unique name and path to save file
    $name = "latest".md5(rand(0,100)).".zip";
    mkdir(UPDATES);
    $path = UPDATES.$name;

    //Save File
    file_put_contents($path, $data);
    addNotice('file_saved');
    
    
    // If user wants to blankly overwrite there site with update.
    if(isset($_POST['are_you_sure_apply_update']) && $_POST['are_you_sure_apply_update'] === '1'){
      
      //Get unzipper class
      require_once(LIBS."zip".DS."unzip.lib.php");
      $zip = new SimpleUnzip();
      $entries = $zip->ReadFile($path);
  
      //Create Install directory (normaly root as your updating this install!)
      $installDir = ROOT;
      mkdir($installDir);
      addNotice('install_dir'," : ".$installDir);
  
      /* */
      foreach ($entries as $entry){
        mkdir($installDir.$entry->Path);
        $entryPath = $installDir.$entry->Path .DS.$entry->Name;
  
        echo $entryPath;
  
        if(!empty($entry->Data)){
          //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
          $fh = fopen($entryPath, 'w', false);
          fwrite($fh,$entry->Data); //DO NOT!! COMMENT OUT, WITHOUT COMMENTING OUT THE LINE ABOVE! BAD THINGS HAPPEN!!!!!!
          fclose($fh);
          //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!//
          echo " .... ".con('updated')."<br />";
        }else{
          echo " ".con('not_updated')."<br />";
        }
      }
      /* */
  
      unlink($path);
      addNotice("file_deleted");
    }
    
  }
}
?>