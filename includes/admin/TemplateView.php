<?php
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
require_once("functions/datetime_func.php");

class TemplateView extends AdminView{
  function template_view (&$data)
  {
    $template_id = $data["template_id"];
    if ($data["template_status"] == 'new'){
      $title = "NEW";
      $alt = "NEW";
      $src = "images/new.jpg";
    }else if ($data["event_status"] == 'error'){
      $title = "ERROR";
      $alt = "ERROR";
      $src = "images/error.jpg";
    }else if ($data["event_status"] == 'comp'){
      $title = "COMPILED";
      $alt = "COMPILED";
      $src = "images/comp.jpg";
    }

    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td colspan='2' class='admin_list_title' >" . $data["template_name"] . "</td></tr>";

    $this->print_field('template_id', $data);
    $this->print_field('template_name', $data);
    $this->print_field('template_type', $data);
    $this->print_field('template_ts', $data);
    $this->print_field('template_status', $data);

    echo "<tr><td colspan='2' class='admin_value' style='border:#cccccc 2px dashed;background-color:#ededed;padding:10px;'>" . nl2br(htmlspecialchars($data["template_text"])) . "</td></tr>";

    echo "</table>\n";
    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";
  }

  function template_form (&$data, &$err, $title)
  {
    global $_SHOP;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>" . $title . "</td></tr>";

    $this->print_field('template_id', $data);
    $this->print_input('template_name', $data, $err, 30, 100);
    $this->print_select ("template_type", $data, $err, array("email", "pdf"));
    echo "<tr><td class='admin_value' colspan='2'>\n
    <textarea rows='40' cols='96' name='template_text'>" .htmlspecialchars($data['template_text'], ENT_QUOTES) ."</textarea>
    <span class='err'>{$err['template_text']}</span>
    </td></tr>";                      //, ENT_QUOTES)

    if ($data['template_id']){
      echo "<input type='hidden' name='template_id' value='{$data['template_id']}'/>\n";
      echo "<input type='hidden' name='action' value='update'/>\n";
    }else{
      echo "<input type='hidden' name='action' value='insert'/>\n";
    }

    echo "<tr><td align='center' class='admin_value' colspan='2'>
    <input type='submit' name='submit' value='" . save . "'>
    <input type='reset' name='reset' value='" . res . "'></td></tr>";
    echo "</table></form>\n";

    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";

  }

  function template_check (&$data, &$err)
  {
   // echo nl2br(htmlspecialchars(print_r($data,true)));
    if (empty($data['template_name'])){
      $err['template_name'] = mandatory;
    }
    if (empty($data['template_type'])){
      $err['template_type'] = mandatory;
    }
    if (empty($data['template_text'])){
      $err['template_text'] = mandatory;
    }

    return empty($err);
  }

  function compile_all ()
  {
    global $_SHOP;
    $query = "SELECT template_name FROM Template order by template_name";
    if (!$res = ShopDB::query($query)){
      return;
    } while ($row = shopDB::fetch_assoc($res)){
      $this->compile_template($row['template_name']);
    }
  }

  function template_list ()
  {
    global $_SHOP;
    $query = "SELECT * FROM Template order by template_type, template_name";
    if (!$res = ShopDB::query($query)){
      return;
    }

    $alt = 0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='6' align='center'>" . template_title . "</td></tr>\n";

    $img_pub['new'] = 'images/new.png';
    $img_pub['error'] = 'images/error.png';
    $img_pub['comp'] = 'images/compiled.png';

    while ($row = shopDB::fetch_assoc($res)){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item'><img src='{$img_pub[$row['template_status']]}'></td>\n";
//      echo "<td class='admin_list_item'>{$row['template_id']}</td>\n";
      echo "<td class='admin_list_item' width='10%'>{$row['template_type']}</td>\n";
      echo "<td class='admin_list_item' width='75%'>{$row['template_name']}</td>\n";
      echo "<td class='admin_list_item nowarp=nowarp'>
            <a class='link' href='{$_SERVER['PHP_SELF']}?action=view&template_id={$row['template_id']}'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a>\n";
      echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&template_id={$row['template_id']}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";
      echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&template_id={$row['template_id']}\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a></td>\n";
      echo "</tr>";
      $alt = ($alt + 1) % 2;
    }
    echo "</table>\n";

    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>" . add . "</a></center>";
    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=compile_all'>" . compile_all . "</a></center>";
  }

  function compile_template ($name)
  {
    global $_SHOP;
    require_once("classes/TemplateEngine.php");
    $te = new TemplateEngine;
    if (!$te->getTemplate($name, 0)){
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

  function draw ()
  {
    global $_SHOP;

    if ($_POST['action'] == 'insert'){
      if (!$this->template_check($_POST, $err)){
        $this->template_form($_POST, $err, template_add_title);
      }else{
        $query = "INSERT Template (template_name,template_type,template_text,template_status)
     VALUES ('" . $this->q($_POST['template_name']) . "','" . $this->q($_POST['template_type']) . "',
     '" . $this->q($_POST['template_text']) . "','new')";
        if (!ShopDB::query($query)){
          return 0;
        }

        if ($this->compile_template($_POST['template_name'])){
          $this->template_list();
        }else{
          $this->template_form($_POST, $err, template_add_title);
        }
      }
    }elseif ($_POST['action'] == 'update'){
      if (!$this->template_check($_POST, $err)){
        $this->template_form($_POST, $err, template_update_title);
      }else{
        $query = "UPDATE Template SET
    template_name='" . $this->q($_POST['template_name']) . "',
    template_type='" . $this->q($_POST['template_type']) . "',
    template_text='" . $this->q($_POST['template_text']) . "',
    template_status='new'
    WHERE template_id='{$_POST['template_id']}'"; 
        // echo $query;
        if (!ShopDB::query($query)){
          return 0;
        }

        if ($this->compile_template($_POST['template_name'])){
          $this->template_list();
        }else{
          $this->template_form($_POST, $err, template_update_title);
        }
      }
    }elseif ($_GET['action'] == 'add'){
      $this->template_form($row, $err, template_add_title);
    }elseif ($_GET['action'] == 'edit'){
      $query = "SELECT * FROM Template WHERE template_id='{$_GET['template_id']}'";
      if (!$row = ShopDB::query_one_row($query)){
        return 0;
      }
      $this->template_form($row, $err, template_update_title);
    }elseif ($_GET['action'] == 'view'){
      $query = "SELECT * FROM Template WHERE template_id='{$_GET['template_id']}'";
      if (!$row = ShopDB::query_one_row($query)){
        return 0;
      }
      $this->template_view($row);
    }elseif ($_GET['action'] == 'remove' and $_GET['template_id'] > 0){
      $query = "DELETE FROM Template WHERE template_id='{$_GET['template_id']}'";
      if (!ShopDB::query($query)){
        return 0;
      }
      $this->template_list();
    }elseif ($_GET['action'] == 'compile_all'){
      $this->compile_all();
      $this->template_list();
    }else{
      $this->template_list();
    }
  }
}

?>