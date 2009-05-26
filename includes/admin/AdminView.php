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

require_once("classes/AUIComponent.php");
require_once("admin/AdminPage.php");

class AdminView extends AUIComponent {
    var $page_width = 800;
    var $title = "Administration";
    var $ShowMenu = true;

    function AdminView ($width=0)
    {
       if ($width) {
         $this->width = $width;
       }
    }

    function extramenus(&$menu){}
       
    function drawall() {

        // width=200 for menu ...Change it to your preferd width;
        // 700 total table
        $page = new AdminPage($this->page_width, $this->title);
        if ($this->ShowMenu) {
          require_once ("admin/adminmenu.php");
          $menu[] = new MenuAdmin();
          $this->extramenus($menu);
          $page->setmenu($menu);
        }
        $page->setbody($this);
        $page->draw();
    }
    
    function print_field ($name, &$data, $prefix='') {

        echo "<tr><td class='admin_name' width='40%'>$prefix" , con($name) , "</td>
              <td class='admin_value'>",(is_array($data))?$data[$name]:$data ,"</td></tr>\n";
    }

    function print_field_o ($name, &$data)
    {
        if ($data[$name]) {
            $this->print_field($name, $data);
        }
    }

    function print_input ($name, &$data, &$err, $size = 30, $max = 100, $suffix = '')
    {
        echo "<tr><td class='admin_name'  width='40%'>$suffix" . con($name) . "</td>
              <td class='admin_value'><input type='text' name='$name' value='" . htmlspecialchars($data[$name], ENT_QUOTES) . "' size='$size' maxlength='$max'>
              <span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }

    function save_paper_format ($name, &$data, &$err)
    {
        if ($data[$name . '_option'] == 1) {
            $data[$name . '_size'] = '';
            $data[$name . '_orientation'] = '';
        } else if ($data[$name . '_option'] == 2) {
            $data[$name . '_size'] = $data[$name . '_size_std'];
        } else {
            $ns = $name . '_size';
            $nsc = $name . '_size_cst';
            $data[$ns][0] = (float)$data[$nsc][0];
            $data[$ns][1] = (float)$data[$nsc][1];
            $data[$ns][2] = (float)$data[$nsc][2];
            $data[$ns][3] = (float)$data[$nsc][3];
        }
    }

    function print_paper_format ($name, &$data, &$err)
    {
        $papers = array('A4', 'LETTER', 'LEGAL' , '4A0', '2A0', 'A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'B0', 'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10', 'C0', 'C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8', 'C9', 'C10', 'RA0', 'RA1', 'RA2', 'RA3', 'RA4', 'SRA0', 'SRA1', 'SRA2', 'SRA3', 'SRA4', 'LETTER', 'LEGAL', 'EXECUTIVE', 'FOLIO');

        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
            <td class='admin_value'>
        		<table>";

                if (empty($data[$name . '_size'])) {
                    $sel_opt[1] = 'checked="checked"';
                } else if (!is_array($data[$name . '_size'])) {
                    $sel_opt[2] = 'checked="checked';
                    $sel_size[$data[$name . '_size']] = 'selected';
                    $sel_ort[$data[$name . '_orientation']] = 'selected';
                } else {
                    $sel_opt[3] = 'checked="checked';
                    $nsc = $data[$name . '_size'];
                }

                echo "<tr>
        		<td><label><input type=radio name='" . $name . "_option' value=1 {$sel_opt[1]}>" . con("page_format_default") . "</label></td>
        		<td>&nbsp;</td>
        		</label>
        		</tr>";

                echo "<tr>
        		<td><label><input type=radio name='" . $name . "_option' value=2 {$sel_opt[2]}>" . con("page_format_std") . " : </label></td>
        		<td><select name='" . $name . "_size_std'>";

                foreach($papers as $paper) {
                    echo "<option value='$paper' {$sel_size[$paper]}>$paper</option>";
                }

                echo "</select>

        		<select name='" . $name . "_orientation'>
        		<option value='portrait' " . $sel_ort['portrait'] . ">" . con("page_orientation_portrait") . "</option>
        		<option value='landscape' " . $sel_ort['landscape'] . ">" . con("page_orientation_landscape") . "</option>
        		</select>
        		</td>
        		</tr>";

                echo "<tr>
        		<td><label><input type=radio name='" . $name . "_option' value=3 {$sel_opt[3]}>" . con("page_format_custom") . "(pt) : </label></td>
        		<td>
        		x1<input name='" . $name . "_size_cst[0]' size=3 value='" . $nsc[0] . "'>
        		y1<input name='" . $name . "_size_cst[1]' size=3 value='" . $nsc[1] . "'>
        		x2<input name='" . $name . "_size_cst[2]' size=3 value='" . $nsc[2] . "'>
        		y2<input name='" . $name . "_size_cst[3]' size=3 value='" . $nsc[3] . "'>
        		</td>
        		</tr>

        		</table>

            <span class='err'>{$err[$name]}</span>
            </td></tr>\n";
    }

    function print_checkbox ($name, &$data, &$err, $size = '', $max = '')
    {
        if ($data[$name]) {
            $chk = 'checked';
        }
        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
                <td class='admin_value'><input type='checkbox' name='$name' value='1' $chk>
                <span class='err'>{$err[$name]}</span>
                </td></tr>\n";
    }

    function print_area ($name, &$data, &$err, $rows = 6, $cols = 40, $suffix = '')
    {
        echo "<tr><td class='admin_name'>$suffix" . con($name) . "</td>
                <td class='admin_value'><textarea rows='$rows' cols='$cols' name='$name'>" . htmlspecialchars($data[$name], ENT_QUOTES) . "</textarea>
                <span class='err'>{$err[$name]}</span>
                </td></tr>\n";
    }

    function print_large_area ($name, &$data, &$err, $rows = 20, $cols = 80, $suffix = '', $class='')
    {
        echo "<tr><td colspan='2' class='admin_name'>$suffix" . con($name) . "</td></tr>
                <tr><td colspan='2' class='admin_value'><textarea rows='$rows' cols='$cols' id='$name' name='$name' $class>" . htmlspecialchars($data[$name], ENT_QUOTES) . "</textarea>
                <span class='err'>{$err[$name]}</span>
                </td></tr>\n";
    }

    function print_set ($name, &$data, $table_name, $column_name, $key_name, $file_name)
    {
        $ids = explode(",", $data);
        $set = array();
        if (!empty($ids) and $ids[0] != "") {
            foreach($ids as $id) {
                $query = "select $column_name from $table_name where $key_name="._esc($id);
                if (!$row = ShopDB::query_one_row($query)) {
                    // user_error(shopDB::error());
                    return 0;
                }
                $row["id"] = $id;
                array_push($set, $row);
            }
        }
        echo "<tr><td class='admin_name'>" . con($name) . "</td>
    <td class='admin_value'>";
        if (!empty($set)) {
            foreach ($set as $value) {
                echo "<a class='link' href='$file_name?action=view&$key_name=" . $value["id"] . "'>" . $value[$column_name] . "</a><br>";
            }
        }
        echo "</td></tr>\n";
    }

    function print_time ($name, &$data, &$err, $suffix = '')
    {
        if (isset($data[$name])) {
            $src = $data[$name];
            list($h, $m, $s) = explode(":", $src);
        } else {
            $h = $data["$name-h"];
            $m = $data["$name-m"];
            $s = $data["$name-s"];
        }
        echo "<tr><td class='admin_name'>$suffix" . con($name) . "</td>
             <td class='admin_value'>
             <input type='text' name='$name-h' value='$h' size='2' maxlength='2' onKeyDown=\"TabNext(this,'down',2)\" onKeyUp=\"TabNext(this,'up',2,this.form['$name-m'])\"> :
             <input type='text' name='$name-m' value='$m' size='2' maxlength='2'>
             <span class='err'>{$err[$name]}</span>
             </td></tr>\n";
    }

    function print_date ($name, &$data, &$err, $suffix = '')
    {
        if (isset($data[$name])) {
            $src = $data[$name];
            list($y, $m, $d) = explode("-", $src);
        } else {
            $y = $data["$name-y"];
            $m = $data["$name-m"];
            $d = $data["$name-d"];
        }
        $nm = $name . "-m";
        echo "<tr><td class='admin_name'>$suffix" . con($name) . "</td>
              <td class='admin_value'>
              <input type='text' name='$name-d' value='$d' size='2' maxlength='2' onKeyDown=\"TabNext(this,'down',2)\" onKeyUp=\"TabNext(this,'up',2,this.form['$nm'])\" > -
              <input type='text' name='$name-m' value='$m' size='2' maxlength='2' onKeyDown=\"TabNext(this,'down',2)\" onKeyUp=\"TabNext(this,'up',2,this.form['$name-y'])\"> -
              <input type='text' name='$name-y' value='$y' size='4' maxlength='4'> (dd-mm-yyyy)
              <span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }

    function print_url ($name, &$data, $prefix = '')
    {
        echo "<tr><td class='admin_name' width='40%'>$prefix" . con($name) . "</td>
    <td class='admin_value'>
    <a href='{$data[$name]}' target='blank'>{$data[$name]}</a>
    </td></tr>\n";
    }

    function print_select ($name, &$data, &$err, $opt)
    {
        // $val=array('both','rows','none');
        $sel[$data[$name]] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
              <td class='admin_value'>
               <select name='$name'>\n";

        foreach($opt as $v) {
            echo "<option value='$v'{$sel[$v]}>" . con($name . "_" . $v) . "</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }

    function print_select_assoc ($name, &$data, &$err, $opt, $mult = false)
    {
        // $val=array('both','rows','none');
        $sel[$data[$name]] = " selected ";
        if ($mult) {
            $mu = 'multiple';
        }

        echo "<tr><td class='admin_name'  width='40%' $mu>" . con($name) . "</td>
  <td class='admin_value'>
   <select name='$name'>\n";

        foreach($opt as $k => $v) {
            echo "<option value='$k'{$sel[$k]}>".con($v)."</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
  </td></tr>\n";
    }

    function print_color ($name, &$data, &$err)
    {
        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
        <td class='admin_value'>
        <select name='$name'>\n";

        $act = $data[$name];

        for($r = 16;$r < 256;$r += 64) {
            for($g = 16;$g < 256;$g += 64) {
                for($b = 16;$b < 256;$b += 64) {
                    $color = '#' . dechex($r) . dechex($g) . dechex($b);
                    if ($act == $color) {
                        echo "<option value='$color'style='color:$color;' selected>$color</option>\n";
                    } else {
                        echo "<option value='$color'style='color:$color;'>$color</option>\n";
                    }
                }
            }
        }

        echo "</select>";
    }

    function view_file ($name, &$data, &$err, $type = 'img', $prefix = '')
    {
        global $_SHOP;

        if ($data[$name]) {
            $src = $this->user_url($data[$name]);
            echo "<tr><td class='admin_name'  width='40%'>$prefix" . con($name) . "</td>";
            if ($type == 'img') {
                echo "<td class='admin_value'><img width=300 src='$src'>";
            } else {
                echo "<td class='admin_value'><a class=link href='$src'>{$data[$name]}</a>";
            }
            echo "</td></tr>\n";
        }
    }

    function print_file ($name, &$data, &$err, $type = 'img', $suffix = '')
    {
        global $_SHOP;

        if (!$data[$name]) {
            echo "\n<tr><td class='admin_name'  width='40%'>$suffix" . con($name) . "</td>
            <td class='admin_value'><input type='file' name='$name'><span class='err'>{$err[$name]}</span></td></tr>\n";
        } else {
            $src = $this->user_url($data[$name]);

            echo "<tr><td class='admin_name'  width='40%'>$suffix" . con($name) . "</td>
            <td class='admin_value'>";

            if ($type == 'img') {
                echo "<img width=300 src='$src'> ";
            } else {
                echo "<a href='$src'>{$data[$name]}</a>";
            }

            echo "</td></tr><tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
            <td class='admin_value'><input type='file' name='$name'><span class='err'>{$err[$name]}</span></td></tr>";
            echo "<tr><td class='admin_name'  width='40%'>" . con("remove_image") . "</td>
            <td class='admin_value'><input type='checkbox'  name='remove_$name' value='1'>" . yes . "</td></tr>\n";
        }
    }

    function form_head ($name, $width = 0, $colspan = 2)
    {
        echo "<table class='admin_form' width='" . ($width?$width:$this->width) . "' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='$colspan' >$name</td></tr>";
    }

    function form_foot($colspan = 2)
    {
        echo "<tr><td align='center' class='admin_value' colspan='2'>
          <input type='submit' name='submit' value='" . save . "'>
          <input type='reset' name='reset' value='" . res . "'></td></tr>";
        echo "</table>\n";
    }

    function list_head ($name, $colspan, $width = 0)
    {
        echo "<table class='admin_list' width='" . ($width?$width:$this->width) . "' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='$colspan' align='center'>$name</td></tr>\n";
    }

    function q ($val)
    {
        return shopDB::escape_string($val);
    }

    function file_post ($data, $id, $table, $name, $suffix = '_image')
    {
        global $_SHOP;

        $img_field = $name . $suffix;
        $id_field = $name . '_id';

        if ($data['remove_' . $name . $suffix]==1) {
            $query = "UPDATE $table SET $img_field='' WHERE $id_field='$id'";
//            unlink( $_SHOP->files_dir . "/" .$data['remove_' . $name . $suffix]);
            
        } else
        if (!empty($_FILES[$img_field]) and !empty($_FILES[$img_field]['name']) and !empty($_FILES[$img_field]['tmp_name'])) {
            if (!preg_match('/\.(\w+)$/', $_FILES[$img_field]['name'], $ext)) {
                return false;
            }

            $ext = strtolower($ext[1]);
            if (!in_array($ext, $_SHOP->allowed_uploads)) {
                return false;
            }

            $doc_name = $img_field . '_' . $id . '.' . $ext;

            if (!move_uploaded_file ($_FILES[$img_field]['tmp_name'], $_SHOP->files_dir . "/" . $doc_name)) {
                return false;
            }

            chmod($_SHOP->files_dir . "/" . $doc_name, $_SHOP->file_mode);
            $query = "UPDATE $table SET $img_field='$doc_name' WHERE $id_field='$id'";
        }

        if (!$query or ShopDB::query($query)) {
           return true;
        }
        return false;
    }

    function user_url($data)
    {
        global $_SHOP;
        return $_SHOP->files_url . $data;
    }

    function user_file ($path)
    {
        global $_SHOP;
        return $_SHOP->files_dir . $path;
    }

    function _myErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if ($errno != 2) {
            echo "$errno $errstr $errfil $errline";
        }
    }

    function dyn_load($name)
    {
        set_error_handler(array(&$this, '_myErrorHandler'));
        $res = include_once($name);
        restore_error_handler();

        return $res;
    }
    function delayedLocation($url){
        echo "<SCRIPT LANGUAGE='JavaScript'>
              <!-- Begin
                   function runLocation() {
                     location.href='{$url}';
                   }
                   window.setTimeout('runLocation()', 1500);
              // End -->\n";
        echo "</SCRIPT>\n";
    }

  function print_countrylist($sel_name, $selected, &$err){
  global $_SHOP,  $_COUNTRY_LIST;

    if (!isset($_COUNTRY_LIST)) {
      If (file_exists($_SHOP->includes_dir."/lang/countries_". $_SHOP->lang.".inc")){
        include_once("lang/countries_". $_SHOP->lang.".inc");
      }else {
        include_once("lang/countries_en.inc");
      }
    }
    if($_SHOP->lang=='de'){
  	  if(empty($selected)){$selected='CH';}
    }else{
   	  if(empty($selected)){$selected='US';}
    }

    echo "<select name='$sel_name'>";
    $si[$selected]=' selected';
    foreach ($_COUNTRY_LIST as $key=>$value){
      echo "<option value='$key' {$si[$key]}>$value</option>";
    }
    echo "</option>";
    echo "<div class='error'>{$err[$sel_name]}</div>";
  }

  function getCountry($val){
    global $_SHOP, $_COUNTRY_LIST;
    $val=strtoupper($val);
    return $_COUNTRY_LIST[$val];
  }
}

?>