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

require_once("classes/Organizer.php");
require_once("admin/AdminView.php");
require_once("functions/file_func.php");

class FileView extends AdminView{

  function ls ($path, $title, $actions="all"){
    $d=dir($path);
    
    if($actions){
      echo "<form method='post' name='file_list' action='{$_SERVER['PHP_SELF']}'>\n";
    }

    $alt=0;
    echo "<table class='admin_list' width='500' cellpadding='3' cellspacing='0' border='0'>\n";
    echo "<tr><td class='admin_list_title' colspan='5' align='center'>$title</td></tr>\n";
    
    
    while(false!==($e=$d->read())){
      echo "<tr class='admin_list_row_$alt'><td class='admin_list_item' width='20'>";

      $de=$path."/".$e;
      $e_url=urlencode($e);
      $e_html=htmlentities($e);
      
      if($actions and ($e!='.' and $e!='..')){
        echo "<input type='checkbox' name='entry[]' value='$e_url'></td><td class='admin_list_item'>"; 
      }else{
        echo "&nbsp;</td><td class='admin_list_item'>"; 
      }

      if(is_dir($de)){
        echo "<a class='link' href='{$_SERVER['PHP_SELF']}?cd=$e_url'>$e_html/</a></td><td>&nbsp;</td>
	<td class='admin_list_item'>".date ("d F Y H:i:s", filemtime($de))."</td><td class='admin_list_item'>&nbsp;"; 
      }else if(is_link($de)){
        echo "$e_html</td><td class='admin_list_item' colspan='3'>&nbsp;"; 
      }else{
        echo "<a class='link' href='{$_SERVER['PHP_SELF']}?view=$e_url' target='view'>$e_html</a>
	</td><td class='admin_list_item' align='right'>".filesize($de)."</td>
	<td class='admin_list_item'>".date ("d F Y H:i:s", filemtime($de))."</td><td class='admin_list_item' align='right'><a href='{$_SERVER['PHP_SELF']}?view=$e_url' target='view'><img src='images/view.png' border='0'></a>
	<a href='{$_SERVER['PHP_SELF']}?download=$e_url' target='download'><img src='images/download.gif' border='0'></a>";
      }    
      echo "</td></tr>\n";
      $alt=($alt+1)%2;
    }
    
    if($actions){
  
      if($actions=='all'){
        $actions='delete';
      }
      $act=array_flip(explode(",",$actions));
      if(isset($act['delete'])){
        $acts.="<button type='submit' name='delete' value=1 onclick='javascript:if(confirm(\"".$this->con(sure_to_delete)."\")){document.forms.file_list.submit();return true;}else{return false;}'><img src='images/trash.gif'></button>";
      }
 
      if($acts){
        echo "<tr><td colspan=5 align=left>$acts</td></tr>";
      }
    }
    echo "</table>\n";
    
    if($actions){
      echo "</form>";
    }
  }

  function actions ($actions="all"){
      if($actions=='all'){
        $actions='mkdir,upload';
      }
      $act=array_flip(explode(",",$actions));
  
  
    if(isset($act['mkdir'])){
      $this->mkdir_form();
    }

    if(isset($act['upload'])){
      $this->upload_form();
    }
  }

  function mkdir_form (){
    echo "
    <form method=get action='{$_SERVER['PHP_SELF']}'>
    <table class='admin_list' width='500' cellpadding='5' cellspacing='0'>
    <tr><td class='admin_list_title' colspan='3' align='center'>".file_mkdir."</td></tr>
    <tr class='admin_list_row_0'>
    <td class='admin_list_item'>".file_dir_name."</td>
    <td class='admin_list_item'><input type=text name=mkdir size=10></td>
    <td class='admin_list_item'><input type=submit name=submit value='".create."'></td>
    </table>
    </form>";    
  }

  function upload_form (){
    echo "
    <form method='post' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>
    <table class='admin_list' width='500' cellpadding='5' cellspacing='0'>
    <tr><td class='admin_list_title' colspan='2' align='center'>".file_upload."</td></tr>
    <tr class='admin_list_row_0'>
    <td class='admin_list_item'>".file_name."</td>
    <td class='admin_list_item'><input type=file name=file1></td>
    </tr>

    <tr class='admin_list_row_1'>
    <td class='admin_list_item'>".file_name."</td>
    <td class='admin_list_item'><input type=file name=file2></td>
    </tr>
    <tr class='admin_list_row_0'>
    <td class='admin_list_item'>".file_name."</td>
    <td class='admin_list_item'><input type=file name=file3></td>
    </tr>

    <tr>
    <td class='admin_list_item' align='center' colspan='2'><input type=submit name=submit value='".upload."'>
    <input type=hidden name=upload value=1>
    </td>
    </tr>
    </table>
    </form>";    
  }


  function draw () { 
    global $_SHOP;

    if(!$org=Organizer::load($_SHOP->organizer_id)){
      echo "<div class=error>".organizer_not_found."</div>";
      return;
    }
    
    echo $root=$_SHOP->user_dir."/".$org['organizer_nickname']."/";
    if(!file_exists($root) or !is_dir($root)){
      echo "<div class=error>".file_not_found."</div>";
      return;
    }
    $len_root=strlen($root);
    
    $pwd=$_SESSION['_SHOP_FILE_VIEW_pwd'][$org['organizer_nickname']];
    if(!isset($pwd)){
      $pwd=$root;
    }
    
    
    if(isset($_GET['cd'])){
      $cd=$_GET['cd'];
      
      if($cd{0}=='/'){
        $new_pwd=realpath("$root/$cd/");
      }else{
        $new_pwd=realpath("$pwd/$cd/");
      }
      
      if(file_is_sub($root,$new_pwd)){
        $pwd=$new_pwd;
      }
    }else
    
    if(isset($_GET['mkdir'])){
      $mkdir=$_GET['mkdir'];
      
      if($mkdir{0}=='/'){
        $new_dir="$root/$mkdir";
      }else{
        $new_dir="$pwd/$mkdir";
      }
      mkdir($new_dir,$_SHOP->dir_mode);
    }else
    if(isset($_POST['delete']) and is_array($_POST['entry']) and !empty($_POST['entry'])){
      foreach($_POST['entry'] as $file){
        $rm_file="$pwd/$file";
	
	if(file_is_sub($root,$rm_file)){
	  file_rmdirr($rm_file);
	}
      }
    }else
    
    
    if(isset($_POST['upload']) and is_array($_FILES) and !empty($_FILES)){
      
      foreach($_FILES as $file){
         if(!empty($file['name'])and !empty($file['tmp_name'])){
           $name="$pwd/".$file['name'];
           move_uploaded_file ($file['tmp_name'],$name);
	   chmod($name,$_SHOP->file_mode);
         }
      }
    }
    
    
    $this->ls($pwd,$org['organizer_nickname'].":/".substr($pwd,$len_root));
    $this->actions();

    $_SESSION['_SHOP_FILE_VIEW_pwd'][$org['organizer_nickname']]=$pwd;
    
  }

}
?>