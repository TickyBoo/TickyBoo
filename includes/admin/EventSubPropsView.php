<?PHP
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

require_once("admin/EventViewCommon.php");
require_once("classes/Event.php");

class EventSubPropsView extends EventViewCommon {

function subevent_form (&$data, &$err, $title){
  global $_SHOP;

  if(!$main=Event::load($data['event_main_id'],FALSE) ){return FALSE;}

  echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

  $this->form_head($title.": ({$main->event_id}) {$main->event_name} - {$main->ort_name}");

  $this->print_date('event_date',$data,$err);

  if($_REQUEST['action'] == "add_sub" || $_REQUEST['action'] == "insert_sub") {
    $this->print_select_recurtype("event_recur_type",$data);
    $this->print_date('event_recur_end', $data, $err);
  	$this->print_days_selection($data,$err);
  	$this->Print_Recure_end();
  	$this->printRecurChangeScript();
  }

  If (!$data['event_id']){
     $this->print_select_pm('event_pm_ort_id', $data, $err);
  } else {
     $this->print_field('ort_name',$data );
  }
  $this->print_time('event_time',$data,$err);
  $this->print_time('event_open',$data,$err);
  $this->print_time('event_end',$data,$err);

  If ($data['event_id']){
      $check=$this->_check('event_name',$main,$data);
      $this->print_input('event_name',$data,$err,30,100,$check);

      $check=$this->_check('event_short_text',$main,$data);
      $this->print_area('event_short_text',$data,$err,3,55,$check);

      $check=$this->_check('event_text',$main,$data);
      $this->print_large_area('event_text',$data,$err,6,95,$check);

      $check=$this->_check('event_url',$main,$data);
      $this->print_input('event_url',$data,$err,30,100,$check);

      $check=$this->_check('event_order_limit',$main,$data);
      $this->print_input('event_order_limit',$data,$err,30,100,$check);

      $check=$this->_check('event_template',$main,$data);
      $this->print_select_tpl('event_template',$data,$err,$check);

      $check=$this->_check('event_image',$main,$data);
      $this->print_file('event_image',$data,$err,'img',$check);

      $check=$this->_check('event_mp3',$main,$data);
      $this->print_file('event_mp3',$data,$err,'mp3',$check);
      echo "\n<input type='hidden' name='action' value='update_sub'/>\n";
      echo "<input type='hidden' name='event_id' value='{$data['event_id']}'/>\n";
   } else {
      echo "<input type='hidden' name='action' value='insert_sub'/>\n";
   }

   //recurrence
  $this->form_foot();

  echo "<input type='hidden' name='event_main_id' value='{$data['event_main_id']}'/>\n";
  echo "</form>\n";
  echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>".admin_list."</a></center>";
  return true;
}

########################################################
########################################################
########################################################
function draw (){
  global $_SHOP;
  if($_POST['action']=='insert_sub'){
    if($this->event_check($_POST, $err)){
      if($_POST['event_recur_type'] == "nothing")
      $this->insert_event($_POST);
      else
      	$this->insert_recur_event($_POST);
      $main = Event::load($_POST['event_id'],FALSE);
      $main = (array)$main;
    } else {$main = $_POST;}
    return !$this->subevent_form($main,$err,event_add_title);

  }elseif($_POST['action']=='update_sub'){
    if(!$this->event_check($_POST,$err)){
      return !$this->subevent_form($_POST,$err,event_update_title);
    }else{
      $this->update_event($_POST);
      return true;
    }
  }elseif($_GET['action']=='add_sub' and $_GET['event_main_id']){
      return !$this->subevent_form($_GET,$err,event_add_title);
  }

  elseif($_GET['action']=='edit_sub' and $_GET['event_id']){
    $main = Event::load($_GET['event_id'],FALSE);
    $main = (array)$main;
    return !$this->subevent_form($main, $err, event_update_title);
  }

  else{
    return true;
  }

}
########################################################
########################################################
########################################################


  function event_check (&$data, &$err){
    global $_SHOP;


    if(!$main=Event::load($data['event_main_id'],FALSE)){return FALSE;}

		$this->Set_Time('event_time',$data,$err);
		$this->Set_Time('event_open',$data,$err);
		$this->Set_Time('event_end' ,$data,$err);
    $this->Set_Date('event_date',$data,$err);

    if(!$main->event_time and  !$data['event_time']){$err['event_time']=con('mandatory');}
    if(!$main->event_date and  !$data['event_date']){$err['event_date']=con('mandatory');}

    if($data['action']=='insert_sub'){
      if($data['event_pm_ort_id']=='copy_main_pm' and $main->event_pm_id){
        $data['event_pm_id']    =$main->event_pm_id;
        $data['event_ort_id']   =$main->event_ort_id;
      }elseif($data['event_pm_ort_id']){
        list($event_pm_id,$event_ort_id)=explode(',',$data ['event_pm_ort_id']);
        $data['event_pm_id']=$event_pm_id;
        $data['event_ort_id']=$event_ort_id;
      }else{
        $err['event_pm_ort_id']=con('mandatory');
      }
    }


    //checking the event recurrence date
    if(isset($data['event_recur_type']) && $data['event_recur_type'] != "nothing") {
      $this->Set_Date('event_recur_end',$data,$err);
     	if(stringDatediff($data['event_date'],$data['event_recur_end']) < 0) {
       	$err['event_recur_end'] = con('invalid');
      }
    }
    return empty($err);
  }

########################################################

  function insert_event (&$data){
    global $_SHOP;

    require_once('classes/Event.php');

    $event=Event::new_from_main($data['event_main_id'], FALSE);
    if(empty($event)){return;}

    if($data['event_time']){$event->event_time=$data['event_time'];}
    if($data['event_open']){$event->event_open=$data['event_open'];}
    if($data['event_end']) {$event->event_open=$data['event_end'];}
    
    $event->event_date=$data['event_date'];
    $event->event_pm_id=$data['event_pm_id'];
    $event->event_ort_id=$data['event_ort_id'];

    if(!$event_id=$event->save()){
      echo "<div class=error>".con('event_not_inserted')."</div>";
      return;}

    if(!$this->photo_post($_POST,$event_id)){
      echo "<div class=error>".con('img_loading_problem')."</div>";
    }

    if(!$this->photo_post_ort($_POST,$event_id)){
      echo "<div class=error>".con('img_loading_problem')."</div>";
    }

    if(!$this->mp3_post($_POST,$event_id)){
      echo "<div class=error>".con('mp3_loading_problem')."</div>";
    }
  }

########################################################
  function save_recur_event (&$data, $isnew ) {
	  $event_dates = $this->getEventRecurDates($data);
		foreach ($event_dates as $event_date) {
      $data['event_date'] = $event_date;
      $this->insert_event( $data, $isnew ) ;
		}
  }


  function update_event (&$data){
    global $_SHOP;

    $main=Event::load($data['event_main_id'], FALSE);
    if(empty($main)){return;}

    $event=Event::load($data['event_id'], FALSE);
    if(empty($event) ){return;}

    $event->event_date=$data['event_date'];
    $this->_set('event_short_text',$data,$event,$main);
    $this->_set('event_text',$data,$event,$main);
    $this->_set('event_name',$data,$event,$main);
    $this->_set('event_url',$data,$event,$main);
    $this->_set('event_open',$data,$event,$main);
    $this->_set('event_end',$data,$event,$main);
    $this->_set('event_order_limit',$data,$event,$main);
    $this->_set('event_template',$data,$event,$main);
    $this->_set('event_time',$data,$event,$main);
    $this->_set('event_image',$data,$event,$main);
    $this->_set('event_ort_image',$data,$event,$main);
    $this->_set('event_mp3',$data,$event,$main);

    if(!$event->save()){
      echo "<div class=error>". con('event_not_updated'). shopDB::error() ."</div>";
      return FALSE;
    }

    if(!$this->photo_post($data,$data['event_id'])){
      echo "<div class=error>".con('img_loading_problem')."</div>";
    }

    if(!$this->photo_post_ort($data,$data['event_id'])){
      echo "<div class=error>".con('img_loading_problem')."</div>";
    }

    if(!$this->mp3_post($data,$data['event_id'])){
      echo "<div class=error>".con('mp3_loading_problem')."</div>";
    }
  }

  function _chk ($name,$data,$main){
    if($main->$name!=$data[$name]){
      return "<img src='images/grun.png'>";
    }
  }

  function _check ($name,$main,$data){
    if($main->$name!=$data[$name]){$chk='checked';}
    return "<input type='checkbox' name='$name"."_chk' value=1 $chk align='middle' style='border:0px;'> ";
  }

  function _set ($name,$data,&$event,$main){
    if($data[$name.'_chk']){
      $event->$name=$data[$name];
    }else{
      $event->$name=$main->$name;
    }
  }
}
?>