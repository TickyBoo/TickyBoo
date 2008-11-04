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

require_once("classes/ShopDB.php");
class Category {
 function select ($event_id,$only_published=TRUE,$with_stats=FALSE){
   if($only_published){
     $pub="and category_status='pub'";
   }

   if($with_stats){
     $stats_table=", Category_stat ";
     $stats_cond=" and  category_id=cs_category_id ";
   }
   
   $query="select * from Category $stats_table where category_event_id='$event_id' $pub $stats_cond order by category_price";
   if($res=ShopDB::query($query)){
     return $res;
   }else{
     user_error(shopDB::error());
     return FALSE;
   }
   
 }
 
 function load ($cat_id,$only_published=TRUE){
   if($only_published){
     $pub="and category_status='pub'";
   }
   
   $query="select * from Category LEFT JOIN PlaceMap2 ON category_pm_id=pm_id where category_id='$cat_id' $pub"; 
	   
   if($res=ShopDB::query($query)){
     return shopDB::fetch_object($res);
   }else{
     return FALSE;
   }
 }

 function load_full ($cat_id, $only_published=TRUE){
   if($only_published){
     $pub="and category_status='pub'";
   }
   
   $query="select * from Category LEFT JOIN PlaceMap2 ON category_pm_id=pm_id,Event,Ort where 
           category_id='$cat_id' and 
	   category_event_id=event_id and event_ort_id=ort_id $pub";
	    
   if($res=ShopDB::query($query)){ 
     return shopDB::fetch_object($res);
   }else{
     return FALSE;
   }
 }

   function print_cat_prices ($cat_names,$cat_prices){
   
     echo "<table width='400' border='0' cellpadding='5'>";
     echo "<tr><td colspan='2'><b>".categories_prices."</b></td></tr>";
     echo "<tr><td>".tickets."</td>";
     foreach($cat_names as $name){
       echo "<td>$name</td>";
     } 
     echo "</tr>";
     echo "<tr><td>".prices."</td>";
     foreach($cat_prices as $price){
       echo "<td>$price</td>";
     } 
     echo "</tr>";
     echo "</table><br>";
     echo prices_chf;
  }
  
  function print_form ($cat_ids,$cat_names,$cat_prices){
   echo "<form name='catselect' method='post' action='places.php'>";
   echo "<table width='400' border='0' cellpadding='5'>";
   echo "<tr><td>".choice_cat."</td><td><select name='category'>";
   $count=sizeof($cat_ids);
   for ($i=0;$i<$count;$i++){
     echo "<option value='".$cat_ids[$i]."'>".
          $cat_names[$i]." - CHF ".$cat_prices[$i]."</option>";
   }
   echo "</select></td></tr>";
   echo "<tr><td colspan='2'>
   <input type='submit' name='submit_cat' value='weiter'></td></tr>";
   echo "</table>";
   echo "</form><br>";
  }
}
?>