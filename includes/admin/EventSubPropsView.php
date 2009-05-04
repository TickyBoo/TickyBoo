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

require_once("admin/EventViewCommon.php");
require_once("classes/Event.php");

class EventSubPropsView extends EventViewCommon {

function subevent_form (&$data, &$err, $title){
  global $_SHOP;

  if(!$main=Event::load($data['event_main_id'],FALSE) ){return FALSE;}

  echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

  $this->form_head($title."<br>({$main->event_id}) {$main->event_name} - {$main->ort_name}");

  $this->print_date('event_date',$data,$err);
  If (!$data['event_id']){
     $this->print_select_pm('event_pm_ort_id', $data, $err);
  } else {
     $this->print_field('ort_name',$data );
  }
  $this->print_time('event_time',$data,$err);
  $this->print_time('event_open',$data,$err);

  If ($data['event_id']){
      $check=$this->_check('event_name',$main,$data);
      $this->print_input('event_name',$data,$err,30,100,$check);

      $check=$this->_check('event_short_text',$main,$data);
      $this->print_area('event_short_text',$data,$err,3,40,$check);

      $check=$this->_check('event_text',$main,$data);
      $this->print_area('event_text',$data,$err,6,40,$check);

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
      $this->insert_event($_POST);
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


  if((isset($data['event_time-h']) and strlen($data['event_time-h'])>0) or
     (isset($data['event_time-m']) and strlen($data['event_time-m'])>0))
  {
    $h=$data['event_time-h'];
    $m=$data['event_time-m'];
    if(!is_numeric($h) or $h<0 or $h>23){$err['event_time']=invalid;}
    else if(!is_numeric($m) or $h<0 or $m>59){$err['event_time']=invalid;}
    else{$data['event_time']="$h:$m";}
  }
  if(!$main->event_time and  !$data['event_time']){$err['event_time']=mandatory;}

  if($data['action']=='insert_sub'){
    if($data['event_pm_ort_id']=='copy_main_pm' and $main->event_pm_id){
      $data['event_pm_id']=$main->event_pm_id;
      $data['event_ort_id']=$main->event_ort_id;
    }elseif($data['event_pm_ort_id']){
      list($event_pm_id,$event_ort_id)=explode(',',$data ['event_pm_ort_id']);
      $data['event_pm_id']=$event_pm_id;
      $data['event_ort_id']=$event_ort_id;
    }else{
      $err['event_pm_ort_id']=mandatory;
    }
  }

  if((isset($data['event_open-h']) and strlen($data['event_open-h'])>0) or
    (isset($data['event_open-m']) and strlen($data['event_open-m'])>0))
  {
    $h=$data['event_open-h'];
    $m=$data['event_open-m'];
    if(!is_numeric($h) or $h<0 or $h>23){$err['event_open']=invalid;}
    else if(!is_numeric($m) or $h<0 or $m>59){$err['event_open']=invalid;}
    else{$data['event_open']="$h:$m";}
  }
  //if(!$main->event_open and  !$data['event_open']){$err['event_open']=mandatory;}

  if((isset($data['event_date-y']) and strlen($data['event_date-y'])>0) or
     (isset($data['event_date-m']) and strlen($data['event_date-m'])>0) or
     (isset($data['event_date-d']) and strlen($data['event_date-d'])>0))
  {
    $y=$data['event_date-y'];
    $m=$data['event_date-m'];
    $d=$data['event_date-d'];

    if(!checkdate($m,$d,$y)){$err['event_date']=invalid;}
    else{$data['event_date']="$y-$m-$d";}
  }else{
    $err['event_date']=mandatory;
  }
  if(!$main->event_date and  !$data['event_date']){$err['event_date']=mandatory;}

//print_r($err);
  return empty($err);
}

########################################################

  function insert_event (&$data){
    global $_SHOP;

    require_once('classes/Event.php');

    $event=Event::new_from_main($data['event_main_id'], FALSE);
    if(empty($event)){return;}

    $event->event_date=$data['event_date'];
    if($data['event_time']){$event->event_time=$data['event_time'];}
    if($data['event_open']){$event->event_open=$data['event_open'];}
    $event->event_pm_id=$data['event_pm_id'];
    $event->event_ort_id=$data['event_ort_id'];

    if(!$event_id=$event->save()){
      echo "<div class=error>".event_not_inserted."<div>";
      return;}

    if(!$this->photo_post($_POST,$event_id)){
      echo "<div class=error>".img_loading_problem."<div>";
    }

    if(!$this->photo_post_ort($_POST,$event_id)){
      echo "<div class=error>".img_loading_problem."<div>";
    }

    if(!$this->mp3_post($_POST,$event_id)){
      echo "<div class=error>".mp3_loading_problem."<div>";
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
    $this->_set('event_order_limit',$data,$event,$main);
    $this->_set('event_template',$data,$event,$main);
    $this->_set('event_time',$data,$event,$main);
    $this->_set('event_image',$data,$event,$main);
    $this->_set('event_ort_image',$data,$event,$main);
    $this->_set('event_mp3',$data,$event,$main);

    if(!$event->save()){
      echo "<div class=error>". event_not_updated. shopDB::error() ."<div>";
      return FALSE;}

    if(!$this->photo_post($data,$data['event_id'])){
      echo "<div class=error>".img_loading_problem."<div>";
    }

    if(!$this->photo_post_ort($data,$data['event_id'])){
      echo "<div class=error>".img_loading_problem."<div>";
    }

    if(!$this->mp3_post($data,$data['event_id'])){
      echo "<div class=error>".mp3_loading_problem."<div>";
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