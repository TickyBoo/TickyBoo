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

require_once("classes/Category.php");
require_once("classes/Place.php");

class PlacesMap {
 
 var $category_id;
 
 function PlacesMap ($category_id,$show_limit=TRUE, $show_info=TRUE){//,$ort,$event){
   $this->category_id=$category_id;
   $this->show_limit=$show_limit;
   $this->show_info = $show_info;
   //$this->ort=$ort;
   //$this->event=$event;
 }

function margins ($shift){
  for($i=0;$i<$shift;$i++){
    $ml=$i%$shift;
    $mr=$shift-$ml-1;
    if ($ml>1){
      $tml="<td colspan='$ml' class='pm_none'><img src='images/dot.gif' width=5 height=10></td>\n";
    }else if($ml==1){
      $tml="<td class='pm_none'><img src='images/dot.gif' width=5 height=10></td>\n";
    }else{
      $tml="";
    }
    if ($mr>1){
      $tmr="<td colspan='$mr' class='pm_none'><img src='images/dot.gif' width=5 height=10></td>\n";
    }else if($mr==1){
      $tmr="<td class='pm_none'><img src='images/dot.gif' width=5 height=10></td>\n";
    }else{
      $tmr="";
    }
    $res[]=array($tml,$tmr);
  }
  return $res;
}


  function draw (){
    include_once("check_login.php");

    $category=$this->category_id;
 
    if(!$cat=Category::load_full($category)){
      return;
    }

    echo "<table class='pm_box'><tr><td>";
    $this->title($cat);
    echo "<form name='f' action='{$_SERVER['PHP_SELF']}' method='post'>";
    echo "<center>
          <table class='pm_table' cellpadding=0 cellspacing=0>\n";

    if($cat->category_numbering=='none'){
    
      echo "<tr><td class='category_item'>".choice_qty."</td><td class='category_item'>
      <input type='text' name='place' size='4' maxlength='4' align='right'>
      <input type='hidden' name='numbering' value='none'></td></tr>";
    }else{ 

    if(!$places=Place::select($category)){
      return;
    }

    if($cat->category_numbering=='both'){
      $both=TRUE;
    }else{
      $both=FALSE;
    }

    $map=str_replace(array("\n","\r"," "),"",$cat->pm_data);
    $height=$cat->pm_height;
    $width=$cat->pm_width;
    $shift=$cat->pm_shift;
    
  
	  
    if($shift>1){
      $cspan=" colspan='$shift' ";

      $ruler=$shift*($width+1)-1;
      echo "<tr>\n";
      for($i=0;$i<$ruler;$i++){
        echo"<td class='pm_ruler'><img src='images/dot.gif' width=5 ></td>";
      }
      echo "</tr>\n";
      $margin=$this->margins($shift);
    }  


    $i=0;
    for ($y=0;$y<$height;$y++){
      echo "<tr>\n";
      echo $margin[$y%$shift][0];
      for($x=0;$x<$width;$x++){
    
        $p=$map{$i++};
        if($p){
          $row=shopDB::fetch_array($places);
          
	  if($both){
  	    $id=$row['place_id'];
	  }else{
	    $id=$row['place_row_nr'];
	  } 
	  
          $status=$row['place_status'];
          if($status=='free'){
            echo "<td class='pm_free' $cspan ><input class='pm_check' type='checkbox' name='place[]' value='$id'>";
          }else{
            echo "<td class='pm_occupied' $cspan><img src='images/dot.gif' width=10 height=10>";
          }
        }else{
          echo "<td class='pm_none' $cspan><img src='images/dot.gif' width=10 height=10>";
        }
        echo "</td>\n";    
      }
      echo $margin[$y%$shift][1];
      echo "</tr>\n";
    }
    }
    
    echo "</table>\n";
    

    echo  "<input type='submit' name='submit' value='".reservate."'>
	  </center>
  
          <input type='hidden' name='category' value='{$cat->category_id}'>
          <input type='hidden' name='event' value='{$cat->category_event_id}'>
          <input type='hidden' name='action' value='addtocart'>

          </form>
	  "; 
    echo "</td></tr></table>";
 }
 
  function title (&$cat){
    require_once("functions/datetime_func.php");
  
    $date=formatDate($cat->event_date);
    $time=formatTime($cat->event_time);


    echo "<table width='100%' cellpadding='0' cellspacing='0' class='pm_info'>\n
      <tr><td class='ort_title' align='center'>{$cat->event_name}</td></tr>
      <tr><td class='event_title' align='center'>$date - $time</td></tr>
      <tr><td class='event_title' align='center'>{$cat->ort_name} - {$cat->category_name} ({$cat->category_price})</td></tr>";
    if($this->show_info){  
      echo"
      <tr><td class='choice_info' align='center'>".info1."</td></tr>
      <tr><td class='choice_info' align='center'>".info2."</td></tr>";
    }  
  
    
    if($this->show_limit and $cat->event_order_limit){
      echo "<tr><td class='choice_info' align='center'>".event_order_limit." {$cat->event_order_limit}</td></tr>";
    }

    if($cat->category_numbering=='rows'){
      
      echo "<tr><td class='choice_info' align='center'><b>".category_numbering_rows."</b></td></tr>";
    }

    echo "</table>\n";
  }
}
?>