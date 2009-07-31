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

class EventViewCommon extends AdminView {
    function print_select_pm ($name, &$data, &$err, $main = 'main')
    {
        global $_SHOP;
        $query = "SELECT pm_id,ort_id,pm_ort_id,pm_name,ort_name
                  FROM Ort LEFT JOIN PlaceMap2 ON pm_ort_id=ort_id
                  where pm_event_id IS NULL
                  order by ort_name";
        if (!$res = ShopDB::query($query)) {
            return;
        }

        echo "<tr><td class='admin_name'>" . con($name) . "</td>
              <td class='admin_value'><select name='$name'>\n";

        if ($data[$name]) {
            $sel[$data[$name]] = 'selected';
        } else {
            $sel['no_pm'] = 'selected';
        }

        if ($main == 'main') {
            echo "<option value='no_pm' {$sel['no_pm']}></option>";
        } elseif ($main == 'has_def') {
            echo "<option value='copy_main_pm' {$sel['no_pm']}>(" . copy_main_pm . ")</option>";
        } while ($row = shopDB::fetch_assoc($res)) {
            if ($row['ort_id'] != $ort_id) {
                $ort_id = $row['ort_id'];
                echo "<option value='0,{$row['ort_id']}' {$sel[$row['pm_id']]}>{$row['ort_name']} - " . agenda_only . "</option>\n";
            }
            if ($row['pm_id']) {
                echo "<option value='{$row['pm_id']},{$row['pm_ort_id']}' {$sel[$row['pm_id']]}>{$row['ort_name']} - {$row['pm_name']}</option>\n";
            }
        }

        echo "</select><span class='err'>{$err[$name]}</td></tr>\n";
    }

    function print_select_tpl ($name, &$data, &$err, $suffix = '')
    {
        global $_SHOP;

        $query = "SELECT template_name FROM Template
                  WHERE template_type='pdf2'
                  ORDER BY template_name";

        if (!$res = ShopDB::query($query)) {
            return false;
        }

        $sel[$data[$name]] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>$suffix" . con($name) . "</td>
                <td class='admin_value'>
                  <select name='$name'>
                   <option value=''></option>\n";

        while ($v = shopDB::fetch_row($res)) {
            $value = htmlentities($v[0], ENT_QUOTES);
            echo "<option value='$value' " . $sel[$v[0]] . ">{$v[0]}</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }

    function print_select_group ($name, &$data, &$err)
    {
        global $_SHOP;

        $query = "SELECT event_group_id,event_group_name
                  FROM Event_group
            	  ORDER BY event_group_name";
        if (!$res = ShopDB::query($query)) {
            return false;
        }

        $sel[$data[$name]] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
              <td class='admin_value'>
               <select name='$name'>
               <option value=''></option>\n";

        while ($v = shopDB::fetch_row($res)) {
            echo "<option value='{$v[0]}' " . $sel[$v[0]] . ">{$v[1]}</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }

    function print_pay ($name, &$data, &$err)
    {
        if (!(strpos($data[$name], 'CC') === false)) {
            $chk_cc = "checked";
        }
        if (!(strpos($data[$name], 'POST') === false)) {
            $chk_post = "checked";
        }

        echo "<tr><td class='admin_name' rowspan='2'>" . con($name) . "<div class='err'>{$err[$name]}</div></td>
              <td class='admin_value'><label><input type='checkbox' name='{$name}[1]' value='CC' $chk_cc>" . con('payment_cc') . "</label></td>
              </tr><tr><td class='admin_value'><label><input type='checkbox' name='{$name}[2]' value='POST' $chk_post>" . con('payment_post') . "</label></td></tr>\n";
    }

    function print_select_ort ($name, &$data, &$err)
    {
        $query = "SELECT * FROM Ort";
        if (!$res = ShopDB::query($query)) {
            return;
        }

        echo "<tr><td class='admin_name'>" . con($name) . "</td>
    <td class='admin_value'><select name='$name'>\n";

        $sel[$data[$name]] = 'selected';

        while ($row = shopDB::fetch_assoc($res)) {
            echo "<option value='{$row['ort_id']}' {$sel[$row['ort_id']]}>{$row['ort_name']}</option>\n";
        }

        echo "</select></td></tr>\n";
    }

    function photo_post($data, $event_id)
    {
        return $this->file_post($data, $event_id, 'Event', 'event');
    }

    function mp3_post ($data, $event_id)
    {
        return $this->file_post($data, $event_id, 'Event', 'event', '_mp3');
    }

    function photo_post_ort ($data, $event_id)
    {
        return $this->file_post($data, $event_id, 'Event', 'event', '_ort_image');
    }

    function get_event_types () {
       global $_SHOP;
       return $_SHOP->event_type_enum;
    }

    function select_types ($name, &$data, &$err) {
        global $_SHOP;
        $sel[$data["$name"]] = " selected ";
        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
              <td class='admin_value'> <select name='$name'>";
        $types = $_SHOP->event_type_enum;
        // print_r($types);
        foreach($types as $k => $v) {
            echo "<option value='" . $v . "' " . $sel[$v] . ">" . con($v) . "</option>\n";
        }
        echo "</select><span class='err'>{$err[$name]}</span></td></tr>\n";
    }
    
    function print_type ($name, &$data)
    {
        echo "<tr><td class='admin_name' width='40%'>" . con($name) . "</td>
              <td class='admin_value'>" . con($data[$name]) . "
              </td></tr>\n";
    }
    //mychanges

    function print_subtitle($name)
    {
    	echo "<tr>
    			<td colspan=2>$name</td>
    		  </tr>";
    }
    function print_select_recurtype($name,$data)
    {
    	$type_list = array("nothing","daily");

    	echo "<tr><td class='admin_name' width='40%'>".con($name)."</td>
    			<td  class='admin_value' ><select id='event_recur_type' name={$name} onchange='changeRecurType(this.value)'>\n";
    	foreach ($type_list as $item) {
    		echo "<option ".(($data["$name"] == $item) ? "selected" : '')." value={$item}>".con("recure_$item")."</option>\n";
    	}
    	echo "</select></td></tr>\n";
    	echo "<tr>
              <td colspan='2' style='padding:0px;margin:0px;'>
                 <table id='recur_table' border=0 width='100%'>\n";
    }

    function print_select_recurdays($dsp_name,$name)
    {
    	echo "<tr><td class='admin_name' width='40%'>$dsp_name</td>
    			<td  class='admin_value'><div  style='float:left;' id='$name'> <select name='$name'>\n";
    	for($i=1;$i<8;$i++) {
    		echo "<option>".$i."</option>\n";
    	}
    	echo "</select></div><div style='float:left;padding-left:2px;' id='$name-suffix'> days</div></td></tr>\n";
    }

    function print_recur_enddate($dsp_name,$name) {
    	echo "<tr><td  class='admin_value' colspan='2' style='padding:0px;margin:0px;'>\n";
    	echo "<table id='recur_table' border=1 width='100%'><tr><td class='admin_name' width='40%'>$dsp_name</td>
			<td  class='admin_value' ><input type='text' name='$name'>";
    	echo "</td></tr>\n";

    }

    function print_days_selection(&$data,&$err) {
    	echo "
          <tr>
    			  <td class='admin_name' width='40%'>".con('recure_days_selection')."</td>
    		    <td class='admin_value'>
    		    	<table id='day_options'>
    		    		<tr>
    		    			<td class='admin_name'>
  			    				<input type='checkbox' name='opt_sunday' value='0'>Sunday
	  		    			</td>
		  	    			<td class='admin_name'>
			      				<input type='checkbox' name='opt_monday' value='1'>Monday
			      			</td>
			      			<td class='admin_name'>
			    	  			<input type='checkbox' name='opt_tuesday' value='2'>Tuesday
			    		  	</td>
			    		  </tr>
			    		  <tr>
  			    			<td class='admin_name'>
  			    				<input type='checkbox' name='opt_wednesday' value='3'>Wednesday
  			    			</td>
  			    			<td class='admin_name'>
  			    				<input type='checkbox' name='opt_thursday' value='4'>Thursday
  			    			</td>
  			    			<td class='admin_name'>
  			    				<input type='checkbox' name='opt_friday' value='5'>Friday
  			    			</td>
  			    	  </tr>
			    		  <tr>
			    		  	<td class='admin_name'>
			    		  		<input type='checkbox' name='opt_saturday' value='6'>Saturday
			    		  	</td>
		    		  	</tr>
			      	</table>
     		    	<span class='err'>{$err['opt_days']}</span>
     		  	</td>
			   </tr>\n";
   }
    function Print_Recure_end(){
			   echo "
           </table>
			   </td>
	    </tr>\n";
    }
    
    function printRecurChangeScript() {
    	echo "<script type='text/javascript'>
    			changeRecurType();
    			function changeRecurType() {
    				type = document.getElementById('event_recur_type').value;
    				if(type == 'daily') {
   				    document.getElementById('recur_table').style.display='';
    				} else {
    					document.getElementById('recur_table').style.display='none';
    				}
    			}
    		  </script>
    		";
    }
    function printFindSubEventsScript(){
		echo "<script type='text/javascript'>
				document.getElementsByClassName = function(clsName){
				    var retVal = new Array();
				    var elements = document.getElementsByTagName('*');
				    for(var i = 0;i < elements.length;i++){
				        if(elements[i].className.indexOf(' ') >= 0){
				            var classes = elements[i].className.split(' ');
				            for(var j = 0;j < classes.length;j++){
				                if(classes[j] == clsName)
				                    retVal.push(elements[i]);
				            }
				        }
				        else if(elements[i].className == clsName)
				            retVal.push(elements[i]);
				    }
				    return retVal;
				}
    			function checkAllSubEvents(mainEventId) {
					var varNum = document.getElementsByClassName(mainEventId);
					if(varNum){
						for(var i = 0;i < varNum.length;i++){
							if(document.getElementById(mainEventId).checked==true)
								varNum[i].checked = 'checked';
							else
								varNum[i].checked = '';
						}
					}
    			}
    		  </script>";
    }

   function print_hidden ($name, $value='', $size = 30, $max = 100, $suffix = '')
    {
        echo "<tr>
              <td>
              	<input type='hidden' id='$name' name='$name' value='" . $value . "' size='$size' maxlength='$max'>
              </td></tr>\n";
    }

    function print_subtitle_with_button($name,$data)
    {
    	echo "<tr>
    			<td>$name</td><td align='right'>
	              <span >
	              	<input type='hidden' id='hdnDivNo' name='hdnDivNo' value='{$data}' size='5' maxlength='100'>
	              	<a class='link' href='javascript: addMoreDiscount();'>Add more discounts....</a>
	              </span>
    			</td>
    		  </tr>";
    }

    function print_select_num ($name, &$data, &$err, $opt, $num='')
    {
        if(isset($data[$name][$num]))
        	$sel[$data[$name][$num]['type']] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>" . $this->con($name) . "</td>
              <td class='admin_value'>
               <select name='".$name."[".$num."][type]'>\n";

        foreach($opt as $v) {
            echo "<option value='$v'{$sel[$v]}>" . $this->con($name . "_" . $v) . "</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }
   function print_input_num ($name, &$data, &$err, $size = 30, $max = 100, $num='', $suffix = '')
    {
    	echo "<tr><td class='admin_name'  width='40%'>$suffix" . $this->con($name) ."</td>
              <td class='admin_value'><input type='text' name='".$name."[".$num."][value]' value='"; if(isset($data[$name][$num])) echo htmlspecialchars($data[$name][$num]['value'], ENT_QUOTES); echo "' size='$size' maxlength='$max'>
              <span class='err'>{$err[$name]}</span>
              </td></tr>\n";
			  
    }

}

?>