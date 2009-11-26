<?php
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
require_once("admin/AdminView.php");


class TemplateView extends AdminView{
  function show_pdf() {
    if ($_GET['action'] == 'view' and $_SESSION['_TEMPLATE_tab']=='2'){
      $query = "SELECT * FROM Template WHERE template_id="._esc($_GET['template_id']);
      if ($row = ShopDB::query_one_row($query)){
        $this->template_view($row, $row['template_type']);
        return 1;
      }
    }
    return 0;
  }
  
  
  function template_view ($data, $type) {
    global $_SHOP,  $_COUNTRY_LIST;
    if (!isset($_COUNTRY_LIST)) {
      if (file_exists($_SHOP->includes_dir."/lang/countries_". $_SHOP->lang.".inc")){
        include_once("lang/countries_". $_SHOP->lang.".inc");
      }else {
        include_once("lang/countries_en.inc");
      }
    }
   	$name = $data['template_name'];
    switch ($data['template_type']) {
      case 'systm':
        require_once('templatedata.php');
      	$order['is_member']     = ($order['user_status']==2);
        $order['active']        = (empty($order['active']));
        $order['link']          = '{HTML-ActivationCode}';
        $order['activate_code'] = '{ActivationCode}';
        $order['new_password']  = '{NewPassword}' ;
     	case 'email':
        require_once('templatedata.php');
      	require_once('classes/htmlMimeMail.php');
        require_once("classes/TemplateEngine.php");
        if (!$tpl = TemplateEngine::getTemplate($name)) {
          return false;
       	}
        
     		$lang = is($_GET['lang'], $_SHOP->lang);
        
        if (!in_array($lang, $tpl->langs )) {
          $lang = $tpl->langs[0];
        }
        $_GET['lang'] = $lang; 
           
        $email = &new htmlMimeMail();
        $tpl->build($email, $order, $lang);
        $email = $email->asarray() ;
        $langs = array();
        foreach($tpl->langs as $lng) {
          $langs[$lng] = (isset($_SHOP->langs_names[$lng]))?$_SHOP->langs_names[$lng]:$lng;
        }       
    		
        echo "<form method='GET' name='frmEvents' action='{$_SERVER['PHP_SELF']}'>\n";
        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td colspan='2' class='admin_list_title' >" . $data["template_name"] . "</td></tr>";
        $this->print_select_assoc ("lang", $_GET, $err, $langs, "onchange='javascript: document.frmEvents.submit();'");
    		$this->print_field('email_from',htmlspecialchars($email['headers']['From']));
        $this->print_field('email_to',htmlspecialchars(implode(',', $tpl->to)));
        $this->print_field_o('email_cc',htmlspecialchars($email['headers']['Cc']));
        $this->print_field_o('email_bcc',htmlspecialchars($email['headers']['Bcc']));
        $this->print_field_o('email_return',htmlspecialchars($email['return_path']));
        $this->print_field('email_subject',htmlspecialchars($email['headers']['Subject']));
        echo "<tr><td colspan='2' class='admin_name'>" .con('email_text'). "</td></tr>";
        echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed;padding:10px;'>" .
				nl2br(htmlspecialchars($email["text"])) . "</td></tr>";
				
 			  echo "<tr><td colspan='2' class='admin_name'>" .con('email_html'). "</td></tr>";
        echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed;padding:10px;'>" .
          nl2br(htmlspecialchars($email["html"])) . "</td></tr>";
        		
       	echo "</table>\n";
        echo "<input type='hidden' name='action' id='action' value='view'>
          <input type='hidden' name='template_id' id='' value='{$data['template_id']}'>
          </form>";

        echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . con('admin_list') . "</a></center>";
          break;
      case 'swiftmail':
        break;
      case 'pdf2':
        require_once("classes/TemplateEngine.php");
        require_once("html2pdf/html2pdf.class.php");
        require_once('templatedata.php');
			
        $paper_size=$_SHOP->pdf_paper_size;
			  $paper_orientation=$_SHOP->pdf_paper_orientation;
			  $_SHOP->lang = is($_SHOP->lang,'en');
			  $te  = new TemplateEngine();
			  $pdf = new html2pdf(($paper_orientation=="portrait")?'P':'L', $paper_size, $_SHOP->lang);
			        
			  // file_put_contents  ( 'test.txt'  , print_r(array($order, $seat),true));
			  if($tpl =& $te->getTemplate($name)){
          $tpl->write($pdf, $order, false); //
        }else{
          echo "<div class=err>".con('no_template')." : $name</div>";
		      return FALSE;
		    }
		    $order_file_name = "pdf_".$data['template_name'].'.pdf';
        $pdf->output($order_file_name, 'I');
		    break;
      default:
        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td colspan='2' class='admin_list_title' >" . $data["template_name"] . "</td></tr>";

        $this->print_field('template_ts', $data);
        $this->print_field('template_status', $data);

        echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed; padding:10px;'>" .
          nl2br(htmlspecialchars($data["template_text"])) . "</td></tr>";

        echo "</table>\n";
        echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . con('admin_list') . "</a></center>";
    	}
	}
  
  function template_form_swift(&$data, &$err, $title, $type) {
    global $_SHOP;
    
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>" . $title . "</td></tr>";
    
    $data['template_array'] = unserialize($data['template_text']);
    //$data['template_text'] = htmlspecialchars($data['template_text'], ENT_QUOTES);

    $this->print_field_o('template_id', $data);
    $this->print_field('template_type', $type );
    
    $this->print_input('template_name', $data, $err, 30, 100);
    $this->print_input("email_to_name", $data['template_array'], $err, 30, 100);
    $this->print_input("email_to_email", $data['template_array'], $err, 30, 100);
    $this->print_input("email_from_name", $data['template_array'], $err, 30, 100);
    $this->print_input("email_from_email", $data['template_array'], $err, 30, 100);
    
    $this->print_multiRowField('emails_cc',$data['template_array'], $err, 30, 100, true);
    $this->print_multiRowField('emails_bcc',$data['template_array'], $err, 30, 100, true);
    
    $this->print_input("email_def_lang", $data['template_array'], $err, 10, 5);
    
    $fields = array('template_subject'=>array('type'=>'text','size'=>'60','max'=>'150'),
      'template_text'=>array('type'=>'textarea','cols'=>'70','rows'=>'10'),
      'template_html'=>array('type'=>'textarea','cols'=>'70','rows'=>'10')
    );
    //$data['template_array']['email_templates'] = array('en'=>array('template_group'=>'en','template_text'=>'hello email body'));
    $this->print_multiRowGroup('email_templates',$data['template_array'],$err , $fields);
    
    
//    $this->print_select ("template_type", $data, $err, array("email", "pdf2"));   //"pdf",
    
    /*echo "<tr><td class='admin_value' colspan='2'><span class='err'>{$err['template_text']}</span>\n
    <textarea rows='20' cols='96' name='template_text'>" .$data['template_text'] ."</textarea>

    </td></tr>";*/

    if ($data['template_id']){
      echo "<input type='hidden' name='template_id' value='{$data['template_id']}'/>\n";
      echo "<input type='hidden' name='action' value='update'/>\n";
    }else{
      echo "<input type='hidden' name='action' value='insert'/>\n";
    }

    echo "<tr><td align='center' class='admin_value' colspan='2'>
    <input type='submit' name='submit' value='" . con('save') . "'>
    <input type='reset' name='reset' value='" . con('res') . "'></td></tr>";
    echo "</table></form>\n";

    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";

  }

  function template_form (&$data, &$err, $title, $type) {
    global $_SHOP;


    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>" . $title . "</td></tr>";

    $data['template_text'] = htmlspecialchars($data['template_text'], ENT_QUOTES);

    $this->print_field_o('template_id', $data);
    $this->print_field('template_type', $type );
    If ($type == 'systm') {
      $this->print_field('template_name', $data);
      echo "<input type='hidden' name='template_name' value='{$data['template_name']}'/>\n";
    } else {
      $this->print_input('template_name', $data, $err, 30, 100);
    }
//    $this->print_select ("template_type", $data, $err, array("email", "pdf2"));   //"pdf",
    
    echo "<tr><td class='admin_value' colspan='2'><span class='err'>{$err['template_text']}</span>\n
    <textarea rows='20' cols='96' name='template_text'>" .$data['template_text'] ."</textarea>

    </td></tr>";

    if ($data['template_id']){
      echo "<input type='hidden' name='template_id' value='{$data['template_id']}'/>\n";
      echo "<input type='hidden' name='action' value='update'/>\n";
    }else{
      echo "<input type='hidden' name='action' value='insert'/>\n";
    }

    echo "<tr><td align='center' class='admin_value' colspan='2'>
    <input type='submit' name='submit' value='" . con('save') . "'>
    <input type='reset' name='reset' value='" . con('res') . "'></td></tr>";
    echo "</table></form>\n";

    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";

  }

  function template_check (&$data, &$err)
  {
   // echo nl2br(htmlspecialchars(print_r($data,true)));
    if (empty($data['template_name'])){
      $err['template_name'] = con('mandatory');
    }
  		if(!preg_match("/^[_0-9a-zA-Z-]+$/", $data['template_name'])){
      	$err['template_name']=con('invalid');
  		}

    if (empty($data['template_text'])){
      $err['template_text'] = con('mandatory');
    }

    return empty($err);
  }

  function compile_all ()
  {
    global $_SHOP;
    $query = "SELECT template_name FROM Template where template_type <> 'PDF' order by template_name ";
    if (!$res = ShopDB::query($query)){
      return;
    } while ($row = shopDB::fetch_assoc($res)){ 
    //echo "compile: {$row['template_name']}<br>\n";
      $this->compile_template($row['template_name']);
    }
  }

  function template_list ($type)
  {
    global $_SHOP;
    $query = "SELECT * FROM Template
             where template_type = '{$type}'
             order by template_type, template_name";
    if (!$res = ShopDB::query($query)){
      return;
    }

    $alt = 0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='6' align='center'>" . con('template_title') . "</td></tr>\n";

    $img_pub['new'] = 'images/new.png';
    $img_pub['error'] = 'images/error.png';
    $img_pub['comp'] = 'images/compiled.png';

    while ($row = shopDB::fetch_assoc($res)){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item' width='20'><img src='{$img_pub[$row['template_status']]}'></td>\n";
//      echo "<td class='admin_list_item'>{$row['template_id']}</td>\n";
      //echo "<td class='admin_list_item' width='10%'>{$row['template_type']}</td>\n";
      echo "<td class='admin_list_item' >{$row['template_name']}</td>\n";
      $target = ($type=='pdf2')?'target="_blank"':'';
      echo "<td class='admin_list_item' width='60' nowarp=nowarp'>
            <a class='link' {$target}  href='{$_SERVER['PHP_SELF']}?action=view&template_id={$row['template_id']}'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a>\n";
      if ($row['template_type'] !=='pdf') {
        echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&template_id={$row['template_id']}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";
      }
      if ($row['template_type'] !=='systm') {
        echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&template_id={$row['template_id']}\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a>";
      }          
      echo "</td>\n";
      echo "</tr>";
      $alt = ($alt + 1) % 2;
    }
    echo "</table>\n";

    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>" . add . "</a></center>";
    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=compile_all'>" . compile_all . "</a></center>";
  }

	function compile_template ($name){
		global $_SHOP;
    	require_once("classes/TemplateEngine.php");
    	$te = new TemplateEngine;
    	if(!$te->getTemplate($name, true)){
      		echo "<div class=err>'$name': ";
      		if ($te->errors){
        		foreach($te->errors as $error){
          			echo "$error<br>";
        		}
      		}
      		echo compilation_failed;
      		echo "</div>";
      		return false;
    	}else{
      		echo "<div class=success>'$name': " . compilation_succeed . "</div>";
      		return true;
    	}
	}

  function draw (){
    global $_SHOP;
    $types = array('systm','email','pdf2','swiftm','pdf');
    if(isset($_REQUEST['tab'])) {
      $_SESSION['_TEMPLATE_tab'] =(int) $_REQUEST['tab'];
   	}
    
    $query = "SELECT count(*) FROM Template
              where template_type = 'pdf'";

    $menu = array(
      con("templ_System")=>"?tab=0", 
      con("templ_email")=>'?tab=1',
      con("templ_pdf2")=>"?tab=2",
      con("templ_swiftm")=>'?tab=3'
    );
    
    if ($res = ShopDB::query_one_row($query, false) and $res[0] >0) {
      $menu[con("templ_pdf")]= "?tab=3";
   	}

    echo $this->PrintTabMenu($menu, (int)$_SESSION['_TEMPLATE_tab'], "left");

    $type =  $types[(int)$_SESSION['_TEMPLATE_tab']];

		if ($_POST['action'] == 'insert'){
		  if (!$this->template_check($_POST, $err)){
        		//if (get_magic_quotes_gpc ()) Shouldnt need to be done as this is done in init_common.
           		//	$_POST['template_text'] = stripslashes (  $_POST['template_text']);
           			$this->template_form($_POST, $err, template_add_title, $type);
			}else{
        		$query = "INSERT Template (template_name,template_type,template_text,template_status)
     					VALUES (" . _esc($_POST['template_name']) . "," . _esc($type) . ",
       					"._esc($_POST['template_text']).",'new')";
        		if (!ShopDB::query($query)){
          			return 0;
        		}
        		
        		if ($this->compile_template($_POST['template_name'])){
          			$this->template_list($type);
        		}else{
          			$this->template_form($_POST, $err, template_add_title, $type);
        		}
			}
  	}elseif ($_POST['action'] == 'update'){
      		if (!$this->template_check($_POST, $err)){
        		$this->template_form($_POST, $err, template_update_title, $type);
      		}else{
        		$query = "UPDATE Template SET
    					template_name=" . _esc($_POST['template_name']) . ",
					    template_type=" . _esc($type) . ",
					    template_text=" . _esc($_POST['template_text']) . ",
					    template_status='new'
					    WHERE template_id="._esc((int)$_POST['template_id']);
 				// echo $query;
        		if (!ShopDB::query($query)){
          			return 0;
        		}
        		
        		if ($this->compile_template($_POST['template_name'])){
          			$this->template_list($type);
        		}else{
          			//if (get_magic_quotes_gpc ()) this is done automaticaly by init_common now
             		//$_POST['template_text'] = stripslashes (  $_POST['template_text']);
          			$this->template_form($_POST, $err, template_update_title, $type);
        		}
      		}
  	}elseif ($_GET['action'] == 'add'){
 	      if($type=='swiftm'){
 	        $this->template_form_swift($row, $err, template_add_title, $type);
        }else{
  	      $this->template_form($row, $err, template_add_title, $type);
        }
    }elseif ($_GET['action'] == 'edit'){
      		$query = "SELECT * FROM Template WHERE template_id="._esc($_GET['template_id']);
      		if (!$row = ShopDB::query_one_row($query)){
        		return 0;
      		}
     		$this->template_form($row, $err, template_update_title, $type);
    }elseif ($_GET['action'] == 'view'){
      		$query = "SELECT * FROM Template WHERE template_id="._esc($_GET['template_id']);
      		if (!$row = ShopDB::query_one_row($query)){
        		return 0;
      		}
      		$this->template_view($row, $type);
    }elseif ($_GET['action'] == 'remove' and $_GET['template_id'] > 0){
      		$query = "DELETE FROM Template WHERE template_id="._esc($_GET['template_id']);
      		if (!ShopDB::query($query)){
        		return 0;
      		}
      		$this->template_list($type);
    }elseif ($_GET['action'] == 'compile_all'){
      		$this->compile_all();
      		$this->template_list($type);
    }else{
      		$this->template_list($type);
    }
  }
}

?>