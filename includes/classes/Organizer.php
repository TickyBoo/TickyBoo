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

require_once("classes/ShopDB.php");
require_once("functions/file_func.php");
class Organizer{
  
  function copy ($data) {
    global $_SHOP;
    
    $orig_id=$data['organizer_id'];
    
    if(!$org_1=Organizer::load($orig_id)){
      echo "<div class=error>".cannot_copy_organizer."</div>";
      return FALSE;
    }
  
    if(!$id=Organizer::create($data)){
      echo "<div class=error>".cannot_copy_organizer."</div>";
      return FALSE;
    }
     
    file_cpr($_SHOP->user_dir."/".$org_1['organizer_nickname'],
    		       $_SHOP->user_dir."/".$data['organizer_nickname']);
    /*{
      echo "<div class=error>".cannot_copy_organizer."</div>";
      @Organizer::remove($id);
      return FALSE;
    }*/

    //copy templates
    $query="insert into Template (template_name, template_type, template_text, template_organizer_id)
            select template_name, template_type, template_text , $id
            from Template
            where template_organizer_id='$orig_id'";

    ShopDB::query($query);
    
    
    
    return $id;	    
  }
  
  function load ($organizer_id){
    $query = "select * from Organizer where organizer_id='$organizer_id'";
    
    return ShopDB::query_one_row($query);
  }
  
  function rename ($organizer_id,$new_name){
    $org=Organizer::load($organizer_id);
  
    if($new_name==$org['organizer_nickname']){return;}
  
    $query="UPDATE Organizer SET organizer_nickname='$new_name'
            WHERE organizer_id='$organizer_id'";
    
    ShopDB::query($query);
    
    $query="UPDATE Admin SET admin_login='$new_name'
            WHERE admin_id='$organizer_id'";
    
    ShopDB::query($query);

    global $_SHOP;
    
    rename($_SHOP->user_dir."/".$org['organizer_nickname'],
           $_SHOP->user_dir."/$new_name");
    
    Organizer::_update_smarty_dirs($org['organizer_nickname'],$new_name);
    
  }
  
  function create ($data){
    global $_SHOP;
    
    $login = $data['organizer_nickname'];
    
    if(isset($data['organizer_place'])){
      $first=TRUE;
      foreach($data['organizer_place'] as $ort){
        if($first){$orte.=$ort;}else{$orte.=",".$ort;}
	$first=FALSE;
      }
    }else{
      $orte="";
    }
    
    $query="INSERT Organizer (organizer_name,
                              organizer_address,
			      organizer_plz,
			      organizer_ort, 
			      organizer_email, 
			      organizer_fax, 
			      organizer_phone,
            organizer_nickname,
			      organizer_currency,
			      organizer_state,
			      organizer_country,
			      organizer_place) VALUES (".
			      ShopDB::quote($data['organizer_name']).",
			      ".ShopDB::quote($data['organizer_address']).",
	  		    ".ShopDB::quote($data['organizer_plz']).",
			      ".ShopDB::quote($data['organizer_ort']).",
			      ".ShopDB::quote($data['organizer_email']).",
 	   		    ".ShopDB::quote($data['organizer_fax']).",
			      ".ShopDB::quote($data['organizer_phone']).",
			      ".ShopDB::quote($data['organizer_nickname']).",
			      ".ShopDB::quote($data['organizer_currency']).",
			      ".ShopDB::quote($data['organizer_state']).",
			      ".ShopDB::quote($data['organizer_country']).",
			      '$orte')";

    if(!ShopDB::query($query)){
      return 0;
    }

    if($id=shopDB::insert_id()){
      $query="insert into Admin (admin_login,admin_password,admin_id,admin_status) VALUES (".
      ShopDB::quote($data['organizer_nickname']).",
      ".ShopDB::quote(md5($data['password1'])).",
      ".ShopDB::quote($id).",'organizer')";
    
      if(!ShopDB::query($query)){
        return FALSE;
      }
    }  

    $userdir=$_SHOP->user_dir."/$login";
    if(!mkdir($userdir,$_SHOP->dir_mode)){ 
      echo "<div class=error>".cannot_create_dir." $userdir</div>"; 
    }

    $dir=$userdir."/web";
    if(!mkdir($dir,$_SHOP->dir_mode)){ 
      echo "<div class=error>".cannot_create_dir." $dir</div>"; 
    }

    $dir=$userdir."/web/files";
    if(!mkdir($dir,$_SHOP->dir_mode)){ 
      echo "<div class=error>".cannot_create_dir." $dir</div>"; 
    }

    $dir=$userdir."/templates";
    if(!mkdir($dir,$_SHOP->dir_mode)){ 
      echo "<div class=error>".cannot_create_dir." $dir</div>"; 
    }

    Organizer::_update_smarty_dirs(null,$login);

    return $id; 
  }

  function remove ($organizer_id){
    global $_SHOP;
  
    $query="select * from Organizer where organizer_id='$organizer_id'";
    if(!$org=ShopDB::query_one_row($query)){
      echo "<div class=error>".cannot_delete_organizer."</div>";
      return 0;
    }  
    $query="DELETE Organizer,Admin 
            FROM Organizer,`Admin` 
            WHERE organizer_id=admin_id AND organizer_id='$organizer_id'";
    if(!ShopDB::query($query)){
      echo "<div class=error>".cannot_delete_organizer."</div>";
      return 0;
    }   
    

		$login = $org['organizer_nickname'];
    if(!file_rmdirr($_SHOP->user_dir."/$login")){
      echo "<div class=error>".cannot_remove_dir."</div>"; 
      return 0;
    }

    $query="delete from Template where template_organizer_id='$organizer_id'";
    ShopDB::query($query);
    
    Organizer::_update_smarty_dirs($login,null);
    
    return 1;
  }

  function _update_smarty_dirs ($login_old,$login_new){
    global $_SHOP;
    if(!$login_old and $login_new){  

      $tmp_dir=$_SHOP->tmp_dir.'/'.$login_new;
      if(!mkdir($tmp_dir,$_SHOP->dir_mode)){
        echo "<div class=error>".cannot_create_dir." $tmp_dir</div>";  
      } 

      $tpl_c_dir=$tmp_dir.'/templates_c';
      if(!mkdir($tpl_c_dir,$_SHOP->dir_mode)){
        echo "<div class=error>".cannot_create_dir." $tpl_c_dir</div>";  
      } 

      $cache_dir=$tmp_dir.'/cache';
      if(!mkdir($cache_dir,$_SHOP->dir_mode)){
        echo "<div class=error>".cannot_create_dir." $cache_dir</div>";  
      } 
      
    }else if($login_old and $login_new){
      rename( $_SHOP->tmp_dir.'/'.$login_old,
              $_SHOP->tmp_dir.'/'.$login_new);
    }else if($login_old and !$login_new){
      file_rmdirr($_SHOP->tmp_dir.'/'.$login_old);
    }
  }
}
?>