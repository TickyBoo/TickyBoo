<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 */





class PlaceMapCategory{

  var $category_id;
  var $category_name;
  var $category_price;
  var $category_template;
  var $category_color;
  var $category_size;

  function PlaceMapCategory (	$category_pm_id=0,
  								$category_name=0,
                				$category_price=0,
                             	$category_template=0,
								$category_color=0,
			     				$category_numbering=0,
								$category_size=0,
								$category_event_id=0 )
  {
    if($category_pm_id){
      $this->category_pm_id=$category_pm_id;
      $this->category_name=$category_name;
      $this->category_price=$category_price;
      $this->category_template=$category_template;
      $this->category_color=$category_color;
      $this->category_numbering=$category_numbering;
      $this->category_size=$category_size;
      $this->category_event_id=$category_event_id;
    }
  }

  function save (){
    global $_SHOP;
    if($this->category_id){

      $query="update Category set
	        category_name=".ShopDB::quote($this->category_name).",
	        category_price=".ShopDB::quote($this->category_price).",
	        category_template=".ShopDB::quote($this->category_template).",
	        category_color=".ShopDB::quote($this->category_color).",
                category_numbering=".ShopDB::quote($this->category_numbering).",
	        category_size=".ShopDB::quote($this->category_size).",
	        category_event_id=".ShopDB::quote($this->category_event_id).",
	        category_pmp_id=".ShopDB::quote($this->category_pmp_id).",
	        category_data=".ShopDB::quote($this->category_data).",
		category_status=".ShopDB::quote($this->category_status)."
	      WHERE category_id='{$this->category_id}' ";
	}else{
		if(!$this->category_ident){
			$this->category_ident=$this->_find_ident($this->category_pm_id);
		}
         $query="insert into Category (
        		category_name,
    	        category_price,
    	        category_template,
	         	category_color,
	         	category_size,
		 		category_numbering,
		 		category_event_id,
		 		category_pmp_id,
		 		category_status,
		 		category_pm_id,
		 		category_data,
		 		category_ident
               ) VALUES (
	         ".ShopDB::quote($this->category_name).",
	         ".ShopDB::quote($this->category_price).",
	         ".ShopDB::quote($this->category_template).",
	         ".ShopDB::quote($this->category_color).",
	         ".ShopDB::quote($this->category_size).",
	         ".ShopDB::quote($this->category_numbering).",
	         ".ShopDB::quote($this->category_event_id).",
	         ".ShopDB::quote($this->category_pmp_id).",
	         'unpub',
	         ".ShopDB::quote($this->category_pm_id).",
	         ".ShopDB::quote($this->category_data).",
	         ".ShopDB::quote($this->category_ident).")";
    }

    if(ShopDB::query($query)){
      if(!$this->category_id){
        $this->category_id=ShopDB::insert_id();
      }

      return $this->category_id;

    }else{
      return FALSE;
    }
  }

  function load ($category_id){
    global $_SHOP;
    $query="select * from Category LEFT JOIN Color ON category_color=color_id where category_id=$category_id ";

    if($res=ShopDB::query_one_row($query)){
      $new_category=new PlaceMapCategory;
      $new_category->_fill($res);

      return $new_category;
    }
  }

  function load_full ($category_id){
    global $_SHOP;

    $query="select * from Category LEFT JOIN Color ON category_color=color_id LEFT JOIN Event ON event_id=category_event_id where category_id=$category_id ";

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
      while($data=shopDB::fetch_array($res)){
        $new_cat=new PlaceMapCategory;
        $new_cat->_fill($data);
        $cats[$new_cat->category_ident]=$new_cat;
      }
    }

    return $cats;
  }

  function loadAll_event ($event_id){
    global $_SHOP;
    $query="select * from Category LEFT JOIN Color ON category_color=color_id
            where category_event_id=$event_id";

    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_array($res)){
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

		require_once('classes/PlaceMapPart.php');
		if($pmps=PlaceMapPart::loadAll($cat->category_pm_id) and is_array($pmps)){
  		foreach($pmps as $pmp){
  		  if($pmp->delete_category($cat->category_ident)){
  				$pmp->save();
  			}
  		}
    }

    $query="delete from Category where category_id=$category_id limit 1";
    ShopDB::query($query);


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

		if(!ShopDB::begin()){
		  echo "#ERR-TRSAXNOTSTRT(5)";
		  return FALSE;
		}
		$query="SELECT * FROM Category_stat
		WHERE cs_category_id='{$this->category_id}'
		FOR UPDATE";

		if(!$cs=ShopDB::query_one_row($query)){
		  ShopDB::rollback();
			echo "#ERR-NOCATSTAT(6)";
			return FALSE;
		}

		$query="SELECT * FROM Event_stat
		WHERE es_event_id='{$this->category_event_id}'
		FOR UPDATE";

		if(!$es=ShopDB::query_one_row($query)){
		  ShopDB::rollback();
			echo "#ERR-NOEVNTSTAT(7)";
			return FALSE;
		}


    if(($delta+$cs['cs_free'])<0){
		  ShopDB::rollback();
			echo "#ERR-TOSMALL(8)";
			return FALSE;
		}

		$new_cs_total=$new_category_size;
		$new_cs_free=$delta+$cs['cs_free'];

    if(($delta+$es['es_free'])<0){
		  ShopDB::rollback();
			echo 9;return FALSE;;
		}

		$new_es_total=$delta+$es['es_total'];
		$new_es_free=$delta+$es['es_free'];


		if($delta>0){
			require_once('classes/Seat.php');
			for($i=0;$i<$delta;$i++){
				if(!Seat::publish($this->category_event_id,0,0,0,0,$this->category_id)){
					ShopDB::rollback();
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
				ShopDB::rollback();
				echo 11;return FALSE;;
			}


			if(shopDB::affected_rows($_SHOP->link)!=$limit){
				ShopDB::rollback();
				echo 12;return FALSE;;
			}
		}

		$query="UPDATE Category_stat
		SET cs_free='$new_cs_free',cs_total='$new_cs_total'
		WHERE cs_category_id='{$this->category_id}'
		LIMIT 1";

		if(!ShopDB::query($query)){
			ShopDB::rollback();
			echo 13;return FALSE;;
		}

		if(shopDB::affected_rows()!=1){
			ShopDB::rollback();
			echo 14;return FALSE;;
		}

		$query="UPDATE Event_stat
		SET es_free='$new_es_free',es_total='$new_es_total'
		WHERE es_event_id='{$this->category_event_id}'
		LIMIT 1";

		if(!ShopDB::query($query)){
			ShopDB::rollback();
			echo 15;return FALSE;;
		}

		if(shopDB::affected_rows($_SHOP->link)!=1){
			ShopDB::rollback();
			echo 16;return FALSE;;
		}

		$this->category_size=$new_category_size;

		if(!$this->save()){
			ShopDB::rollback();
		  echo 17;return FALSE;;
		}

		ShopDB::commit();
		return TRUE;
	}

  function _find_ident ($pm_id){
    global $_SHOP;
    $query="select category_ident from Category where category_pm_id=$pm_id";
    if(!$res=ShopDB::query($query)){return;}
    while($i=shopDB::fetch_array($res)){
      $ident[$i['category_ident']]=1;
    }

    $category_ident=1;
    while($ident[$category_ident]){$category_ident++;}
    return $category_ident;
  }

  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
  }
}

?>