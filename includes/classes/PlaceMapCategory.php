<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

class PlaceMapCategory Extends Model {
  protected $_idName    = 'category_id';
  protected $_tableName = 'Category';
  protected $_columns   = array( '#category_id', '#category_event_id', 'category_price', 'category_name', 
                                 'category_pm_id', '#category_pmp_id', '*category_ident', '*category_numbering', 
                                 'category_size', 'category_max', 'category_min', 'category_template', 
                                 '*category_color', 'category_data');

  var $category_id;
  var $category_name;
  var $category_price;
  var $category_template;
  var $category_color;
  var $category_size;

  function create($category_pm_id=0,
                  $category_name=0,
                  $category_price=0,
                  $category_template=0,

                  $category_color=0,
                  $category_numbering=0,
                  $category_size=0,
                  $category_event_id=null )
  {
    $new = new PlaceMapCategory;
      $new->category_pm_id=$category_pm_id;
      $new->category_name=$category_name;
      $new->category_price=$category_price;
      $new->category_template=$category_template;
      $new->category_color=$category_color;
      $new->category_numbering=$category_numbering;
      $new->category_size=$category_size;
      $new->category_event_id=(int)$category_event_id;
    return $new;
  }

  function save (){
    if(!$this->category_id) { 
      if ($this->category_numbering<>'none') {
        $this->category_size = 0;
      }
      if(!$this->category_ident){
        $this->category_ident=$this->_find_ident($this->category_pm_id);
      }
    }
    return parent::save();
  }

  function load ($category_id){
    global $_SHOP;
    $query="select * 
            from Category LEFT JOIN Color ON category_color=color_id 
            where category_id="._esc($category_id);

    if($res=ShopDB::query_one_row($query)){
      $new_category=new PlaceMapCategory;
      $new_category->_fill($res);

      return $new_category;
    }
  }

  function loadFull ($category_id){
    global $_SHOP;

    $query="select * 
           from Category LEFT JOIN Color ON category_color=color_id
                         LEFT JOIN Event ON event_id=category_event_id
            where category_id="._esc($category_id);

    if($res=ShopDB::query_one_row($query)){
      $new_category=new PlaceMapCategory;
      $new_category->_fill($res);

      return $new_category;
    }
  }


  function loadAll ($pm_id){
    global $_SHOP;
    $query="select * from Category LEFT JOIN Color ON category_color=color_id where category_pm_id=$pm_id";

    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_assoc($res)){
        $new_cat=new PlaceMapCategory;
        $new_cat->_fill($data);
        $cats[$new_cat->category_ident]=$new_cat;
      }
    }

    return $cats;
  }

  function loadAll_event ($event_id){
    global $_SHOP;
    $query="select * 
            from Category LEFT JOIN Color ON category_color=color_id
            where category_event_id=".esc($event_id);

    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_assoc($res)){
        $new_cat=new PlaceMapCategory;
        $new_cat->_fill($data);
        $cats[$new_cat->category_ident]=$new_cat;
      }
    }

    return $cats;
  }

  function delete ($category_id=0){
    global $_SHOP;

    if(!$cat=PlaceMapCategory::load($category_id)){
      echo  "remove_me not: 1";
      return;
    }
    $seats = shopDB::query_on_row("select count(*) from Seats 
                                   where seat_category_id ={$category_id}", false);
    if ($seats[0]>0) {
      echo '<div class=error>'.con('Category_delete_failed_seats_exists').'</div>';
      return false;
    }
    

    if(!ShopDB::begin('delete category: '.$category_id)){
        echo '<div class=error>'.con('Cant_Start_transaction').'</div>';
        return FALSE;
    }

    $query="DELETE c.*, cs.*
            FROM Category c LEFT JOIN Category_stat cs
            ON c.category_id = cs.cs_category_id
            WHERE c.category_id={$category_id}";
    if(!ShopDB::query($query)){
      return placemap::_abort(con('Category_delete_failed'));
    }
    
    require_once('classes/PlaceMapPart.php');
    if($pmps=PlaceMapPart::loadAll($cat->category_pm_id) and is_array($pmps)){
      foreach($pmps as $pmp){
        if($pmp->delete_category($cat->category_ident)){
          $pmp->save();
        }
      }
    }

    If (!ShopDB::commit('Category deleted')) {return false;}
    return TRUE;
  }


  function change_size($new_size){
    return $this->increment_size($new_size-$this->category_size);
  }

  function increment_size($delta){
    global $_SHOP;

    if($delta==0){
      echo "#ERR-NOSIZEDIFF(1)";
      return FALSE;
    }
      if($this->category_status!='nosal'){
        echo "#ERR-NOTUNPUBCAT(2)";
      return FALSE;
    }
    if($this->category_numbering!='none'){
      echo "#ERR-CNTCHGNUMSTS(3)";
      return FALSE;
    }
    $new_category_size=$this->category_size+$delta;
    if($new_category_size<=0){
      echo "#ERR-CATSIZE<0(4)";
      return FALSE;
    }

    if(!ShopDB::begin('resize category')){
      echo "#ERR-TRSAXNOTSTRT(5)";
      return FALSE;
    }
    $query="SELECT * FROM Category_stat
    WHERE cs_category_id='{$this->category_id}'
    FOR UPDATE";

    if(!$cs=ShopDB::query_one_row($query)){
      ShopDB::rollback('cant lock Category_stat');
      echo "#ERR-NOCATSTAT(6)";
      return FALSE;
    }

    if(($delta+$cs['cs_free'])<0){
      ShopDB::rollback('Size is to small category');
      echo "#ERR-TOSMALL(8)";
      return FALSE;
    }
    $new_cs_total=$new_category_size;
    $new_cs_free=$delta+$cs['cs_free'];

    $query="SELECT * FROM Event_stat
    WHERE es_event_id='{$this->category_event_id}'
    FOR UPDATE";

    if(!$es=ShopDB::query_one_row($query)){
      ShopDB::rollback('cant lock event_stat');
      echo "#ERR-NOEVNTSTAT(7)";
      return FALSE;
    }
    if(($delta+$es['es_free'])<0){
      ShopDB::rollback('Size to small for event');
      echo 9;return FALSE;;
    }

    $new_es_total=$delta+$es['es_total'];
    $new_es_free=$delta+$es['es_free'];


    if($delta>0){
      require_once('classes/Seat.php');
      for($i=0;$i<$delta;$i++){
        if(!Seat::publish($this->category_event_id,0,0,0,0,$this->category_id)){
          ShopDB::rollback('Cant publish new seats');
          echo 10;return FALSE;;
        }
      }
    }else{
      $limit=-$delta;

      $query="DELETE FROM Seat
                where seat_category_id='{$this->category_id}'
                and seat_event_id='{$this->category_event_id}'
                and seat_status='free'
                LIMIT $limit";

      if(!ShopDB::query($query)){
        ShopDB::rollback('Cant delete old seats');
        echo 11;return FALSE;;
      }


      if(shopDB::affected_rows()!=$limit){
        ShopDB::rollback('Different No off seats removed');
        echo 12;return FALSE;;
      }
    }

    $query="UPDATE Category_stat SET 
              cs_free='$new_cs_free',
              cs_total='$new_cs_total'
            WHERE cs_category_id='{$this->category_id}'
            LIMIT 1";

    if(!ShopDB::query($query)){
      ShopDB::rollback('cant update category_stat');
      echo 13;return FALSE;;
    }

    if(shopDB::affected_rows()!=1){
      ShopDB::rollback('category_stat not changes');
      echo 14;return FALSE;;
    }

    $query="UPDATE Event_stat SET 
              es_free='$new_es_free',
              es_total='$new_es_total'
            WHERE es_event_id='{$this->category_event_id}'
            LIMIT 1";

    if(!ShopDB::query($query)){
      ShopDB::rollback('Cant update event_stat');
      echo 15;return FALSE;;
    }

    if(shopDB::affected_rows()!=1){
      ShopDB::rollback('event_stat not changes');
      echo 16;return FALSE;;
    }

    $this->category_size=$new_category_size;

    if(!$this->save()){
      ShopDB::rollback('cant save category');
      echo 17;return FALSE;;
    }

    ShopDB::commit('Category resized');
    return TRUE;
  }
  /* ??? this code need to be checked !!!! */
  function _find_ident ($pm_id){
    global $_SHOP;
    $query="select category_ident 
            from Category 
            where category_pm_id="._esc($pm_id);
    if(!$res=ShopDB::query($query)){return;}
    while($i=shopDB::fetch_assoc($res)){
      $ident[$i['category_ident']]=1;
    }

    $category_ident=1;
    while($ident[$category_ident]){$category_ident++;}
    return $category_ident;
  }

  function getCategoryNumbering($category_id = 0){
    if ($that and $this->category_numbering) {
      return $this->category_numbering;
    } else {
      $query="select category_numbering 
              from Category 
              where category_id="._esc($category_id);
      if(!$res=ShopDB::query_one_row($query)){return;}
      return $res['category_numbering'];
    }
  }
  
  static function create_stat($cs_category_id,$cs_total,$cs_free=-1) {
    if ($cs_free==-1) $es_free = $cs_total;
     $query="insert into Category_stat set 
              cs_category_id="._esc($cs_category_id).",
              cs_free="       ._esc($cs_free).",
              cs_total="      ._esc($cs_total);
    
    if(ShopDB::query($query)){
      return TRUE;
    }
  }

  function dec_stat ($cs_category_id,$count){
    $query="UPDATE Category_stat SET cs_free=cs_free-$count 
            WHERE cs_category_id="._esc($cs_category_id)." LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }   
  }

  function inc_stat ($cs_category_id,$count){
    $query="UPDATE Category_stat SET cs_free=cs_free+$count 
            WHERE cs_category_id="._esc($cs_category_id)." LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }  
  }
}
?>