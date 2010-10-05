<?PHP
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

class import_xml extends AdminView {

  function cp_form (&$data,&$err){
		global $_SHOP;

    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
		$this->form_head(con('import_xml_title'));

		echo "<tr><td class='admin_name'  width='40%'>".con('import_xml_file')."</td>
					<td class='admin_value'><input type='file' name='import_xml_file'></td></tr>";

		echo "
		<tr><td align='center' class='admin_value' colspan='2'>
    	<input type='hidden' name='run' value='{$_REQUEST['run']}'>
  		<input type='submit' name='submit' value='".con('import_xml_submit')."'></td></tr>
		</table></form>";
  }


  function execute (){
    global $_SHOP;
//    print_r($_FILES);echo 'testing';
		if(!empty($_FILES['import_xml_file']) and !empty($_FILES['import_xml_file']['name']) and !empty($_FILES['import_xml_file']['tmp_name'])){
			require_once('classes/class.xmldata.php');
			echo con('import_xml_title')." : ".$_FILES['import_xml_file']['name']." ... ";
	//		flush();
			$result = xmldata::xml2sql($_FILES['import_xml_file']['tmp_name'], true);

			echo con('done');;
			return false;
    }
  }

  function draw (){
    $this->cp_form($_GET,$this->err);
  }

  function import_now($xml_values) {
    foreach ($xml_values as $db) {///print_r($db);
        $org = is($db['organizer'],$db['Organizer']);//print_r($org);
        $fields= array('organizer_name', 'organizer_address', 'organizer_address1', 'organizer_zip'=>'organizer_plz',
                       'organizer_city' => 'organizer_ort', 'organizer_state', 'organizer_country', 'organizer_email',
                       'organizer_fax', 'organizer_phone', 'organizer_place',
                       'organizer_currency', 'organizer_logo','organizer_username'=>'organizer_nickname');
        if (! $org_id = addrecord('organizers',$fields , $org)) exit;
        $venue_ids = array();
        $event_ids = array();
        $group_ids = array();

        $venues = is($db['ort'],$db['Ort']);
        $keys = array_keys($venues);
        if (is_string($keys[0])) $venues = array($venues);
        $fields= array('venue_googleurl'=>'ort_pm', 'venue_created'=>'ort_created', 'venue_name'=>'ort_name', 'venue_phone'=>'ort_phone',
                       'venue_url'=>'ort_url', 'venue_image'=>'ort_image', 'venue_address'=>'ort_address', 'venue_address1'=>'ort_address1',
                       'venue_zip'=>'ort_zip', 'venue_city'=>'ort_city', 'venue_state'=>'ort_state', 'venue_country'=>'ort_country',
                       'venue_fax'=>'ort_fax', 'venue_organizer_id'=>'organizer_id');
        foreach($venues as $rec) {
          $rec['organizer_id'] = $org_id;
        //  print_r($rec);
          echo $rec['ort_id'], ' |> ' ;
          if (!$venue_ids[$rec['ort_id']] = addrecord('venues',$fields , $rec)) exit;
        }
        print_r($venue_ids); echo "<br>\n";
        $venues = is($db['event_group'],$db['Event_group']);
        if (is_array($venues)){
          $keys = array_keys($venues);
          if (is_string($keys[0])) $venues = array($venues);
          $fields= array( 'eventgroup_name'=>'event_group_name', 'eventgroup_description'=>'event_group_description',
                          'eventgroup_image'=>'event_group_image', 'eventgroup_status'=>'event_group_status',
                          'eventgroup_start_date'=>'event_group_start_date', 'eventgroup_end_date'=>'event_group_end_date',
                          'eventgroup_type'=>'event_group_type', 'eventgroup_organizer_id'=>'organizer_id');
          foreach($venues as $rec) {//'event_id',
            $rec['organizer_id']        = $org_id;
            echo $rec['event_group_id'], ' |> ' ;
            if (!$group_ids[$rec['event_group_id']] = addrecord('eventgroups', $fields, $rec)) exit;
          }
        }
        print_r($group_ids); echo "<br>\n";

        $venues = is($db['event'],$db['Event']);
        $keys = array_keys($venues);
        if (is_string($keys[0])) $venues = array($venues);
        $fields= array( 'event_created', 'event_name', 'event_text', 'event_short_text', 'event_url', 'event_image',
                        'event_venue_id'=>'event_ort_id', 'event_timestamp', 'event_open', 'event_end', 'event_group_id',
                        'event_mp3', 'event_type', 'event_organizer_id'=>'organizer_id');
        foreach($venues as $rec) {//'event_id',
//        'eventgroup_id'=>'event_group_id', //'event_id',
          $rec['organizer_id']        = $org_id;
          $rec['event_ort_id']        = $venue_ids[$rec['event_ort_id']];
          $rec['event_group_id']      = $group_ids[$rec['event_group_id']];
          echo $rec['event_id'], ' |> ' ;
          if (!$event_ids[$rec['event_id']] = addrecord('events', $fields, $rec)) exit;
        }
        print_r($event_ids); echo "<br>\n";

        $venues = is($db['category'],$db['Category']);
        $keys = array_keys($venues);
        if (is_string($keys[0])) $venues = array($venues);

        $fields= array ('category_event_id', 'category_price', 'category_name','category_organizer_id'=>'organizer_id');
        foreach($venues as $rec) {//'event_id',
          $rec['organizer_id']        = $org_id;
          $rec['category_event_id']   = $event_ids[$rec['category_event_id']];
          if (!addrecord('categories', $fields, $rec)) exit;
        }
     }
  }

  function addrecord($table, $fields, $data) {
			$query='insert into `'.$table.'` set ';
			$next=true;
      foreach($fields as $field=>$value){
        if (!is_string($field)) $field = $value;

        if (!is_array($data[$value]) and !is_null($data[$value])) {
   				if(!$next) $query.=', ';
          $query.='`'.$field.'`='._esc($data[$value]);
				  $next=false;
        }
			}
      echo $table,' || ';
			echo MySQL::query($query),' || ';
      echo  $id =MySQL::insert_ID(), "<br>\n";
      return $id;
  }



}
?>