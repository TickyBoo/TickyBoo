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
require_once("classes/Event.php");
require_once ("functions/datetime_func.php");

class CategoriesInfo {

function CategoriesInfo ($event_id){
  $this->event_id=$event_id;
}
function draw () {
  include_once("check_login.php");

  $event_id=$this->event_id;

  if(!$event=Event::load($event_id)){
     return;
  }
  
  $this->print_event_details($event);
  
  if($categories=Category::select($event_id,TRUE,TRUE)){
    $cat_count=0;

    while($cat=shopDB::fetch_object($categories)){
      if(!($cat->cs_free>0)){
        $cat->category_sold=TRUE;
      }	
      $cats[]=$cat;
      
      $flag=1;
      $unnum_flag=($unnum_flag or ($cat->category_numbering=='none'));
      
    }
    if($flag){
      $this->print_cat_prices ($cats);
      if($unnum_flag){
        $this->print_cat_form_unnum ($cats,$event);
      }else{
        $this->print_cat_form ($cats);
      }
    }
  }

  if($event->event_ort_image){
    echo "<center><div class='ort_image'><img src='uploaded_images/".$event->event_ort_image."'></div></center>";
  }
  else
  if($event->ort_image){
    echo "<center><div class='ort_image'><img src='uploaded_images/".$event->ort_image."'></div></center>";
  }
}

function print_event_details ($event){
   $etime=formatTime($event->event_time);
   $otime=formatTime($event->event_open);
   $edate=formatDate($event->event_date);
   
   echo "<table width='500' border='0' cellpadding='5' class='event_details'>
   <tr><td colspan='3' class='event_details_titre'>{$event->event_name}</td></tr>
   <tr><td colspan='3'class='event_value'>".$event->event_text."</td></tr>
   <tr><td class='event_item'>".date."</td><td class='event_value'>$edate</td><td rowspan='5'>";
   if($event->event_image){
     echo "<img src='uploaded_images/".$event->event_image."'>";
   }
   echo "</td></tr>
   <tr><td class='event_item'>".open."</td><td class='event_value'>$otime</td></tr>
   <tr><td class='event_item'>".start."</td><td class='event_value'>$etime</td></tr>
   <tr><td class='event_item'>".ort."</td><td class='event_value'>{$event->ort_name}</td></tr>";
   if(!empty($event->ort_phone)){echo "<tr><td class='event_item'>".ort_phone."</td><td class='event_value'>{$event->ort_phone}</td></tr>";}

   if($url=$event->ort_url){
     echo "<tr><td class='event_item'>".ort_url."</td><td class='event_value'>
           <a class='event_url' href='$url'>$url</a></td></tr>";
   }	 

   echo "</table><br>"; 
 }

  function print_cat_prices ($all_cats){
     $colspan=5;//sizeof($cats)+1;
     echo "<table width='500' border='0' cellpadding='5' class='cat_details'>
     <tr><td colspan='$colspan' class='category_details'>".categories_prices."</td></tr>";

     
     $chunk_cats=array_chunk($all_cats,4);
     
     foreach($chunk_cats as $cats){

       echo "<tr><td class='category_item'>".tickets."</td>";
       for($i=0;$i<4;$i++){
         $cat=$cats[$i];
         echo "<td class='category_item'>{$cat->category_name}</td>";
       } 

       echo "</tr>


       <tr><td class='category_item'>".prices."</td>";
       for($i=0;$i<4;$i++){
         $cat=$cats[$i];
         if($cat->category_sold){
           echo "<td class='category_value'>".category_sold_out."</td>";
         }else{
           echo "<td class='category_value'>{$cat->category_price}</td>";
         } 
       } 
       echo "</tr>";
     }
     
     echo "<tr><td colspan='$colspan'
                 align='right' class='note'>".prices_chf."</td></tr>
           </table><br>";
   }
  
//  function print_cat_form ($cat_ids,$cat_names,$cat_prices,$ort_name,$event_name){
  function print_cat_form ($cats){
    echo "<form name='catselect' method='get' action='{$_SERVER['PHP_SELF']}'>
          <table class='cat_choice' width='500'  cellpadding='5' >
          <tr><td class='category_item'>".choice_cat."</td>
          <td><select name='category_id'>";
   
   foreach($cats as $cat){
     if(!$cat->category_sold){
       echo "<option value='{$cat->category_id}'>
          {$cat->category_name} - CHF {$cat->category_price}</option>";
	  
     }	  
   }
   
   echo "</select></td>
   </tr>
   <tr><td colspan='2' align='center' class='category_value'>
   <input type='submit' name='submit_cat' value='".weiter."'></td></tr>
   </table>
   </form><br>";
  }

//  function print_cat_form ($cat_ids,$cat_names,$cat_prices,$ort_name,$event_name){
  function print_cat_form_unnum ($cats,$event){
    echo "
    <script><!--
    var unnum_cats=new Array;\n";
    $i=0;
    foreach($cats as $cat){
      echo "unnum_cats[$i]='{$cat->category_numbering}';\n";
      $i++;
    }
   
    echo "

function getElement(id){
 if(document.all) {return document.all(id);}
 if(document.getElementById) {return document.getElementById(id);}
}


function setQtyShown(){
     
      if(cat_select_e=getElement('cat_select')){
        if(qty_e=getElement('qqq')){
          if(unnum_cats[cat_select_e.selectedIndex]=='none'){
            qty_e.style.display='block';  
          }else{
            qty_e.style.display='none';  
          }
        }
      }
    }
    --></script>
   
   <form name='catselect' method='get' action='{$_SERVER['PHP_SELF']}'>
   <table  class='cat_choice' cellpadding='5' width='500'>
   <tr><td class='category_item' colspan='2' >".choice_cat."</td></tr>
   <tr><td width='50%' align='right'><select name='category_id' onchange='setQtyShown()' id='cat_select'>";
   
   foreach($cats as $cat){
     if(!$cat->category_sold){
       echo "<option value='{$cat->category_id}'>
          {$cat->category_name} - CHF {$cat->category_price}</option>";
     }	  
   }

   echo "</select></td>
         <td class='category_item' align='left'><div id='qqq'  align='left'>x&nbsp;<input type='text' name='qty' size=4 maxlength=2>";
   if($event->event_order_limit>0){
     echo " (".event_order_limit.": ".$event->event_order_limit.") ";
   } 
   echo "</div></td>
	 </tr>
         <tr><td colspan='2' align='center' class='category_value'>
         <input type='submit' name='submit_cat' value='".weiter."'></td></tr>
         </table>
         </form><br>
         <script><!-- 
           setQtyShown();
         --></script>";
  }
}
?>