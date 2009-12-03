<?php
/*********************** %%%copyright%%% *****************************************
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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


if (!defined('ft_check')) {die('System intrusion ');}
class Model {
  /**
  * The name of this model
  * @var string
  */
  const MDL_NONE      = 0;
  const MDL_MANDATORY = 1;
  const MDL_IDENTIFY  = 2;

  protected $_idName;
  protected $_tableName;
  protected $_columns = array();

  function __construct($filldefs= false){
    if ((!$this->_columns || $filldefs) && $_tableName) {
      $defs = & ShopDB::FieldListExt($this->tableName);
      if (!$this->_columns) {
        foreach($defs as $key => $value) {
          If ($key != $this->_idName) {
            if ($value->Null == 'NO') $key = '*'.$key;
            $this->_columns[]  = $key;
          }
        }
      }
      if ($filldefs) {
        foreach($defs as $key => $value) {
          If ($key != $this->_idName) {
            $this->$key = $value->Default;
          }
        }
      }
    }
  }

  function clear(){
    $defs = & ShopDB::FieldListExt($this->tableName);
    foreach($defs as $key => $value) {
      If ($key != $this->_idName) {
        $this->$key = $value->Default;
      } else
        $this->$key = null;
    }
  }

  function save ($id = null, $exclude=null){
    $idKey = $this->idKey;
    if(isset($id) and $id and $idKey) $this->$idKey = $id;
    if($this->id){
      return $this->update($exclude);
    }else{
     return  $this->insert($exclude);
    }
  }

  function insert($exclude=null){
  // $this->_idName
  // unset($this->$this->_idName);
    $values  = join(",", $this->quoteColumnVals($exclude));
    $query = "INSERT INTO `{$this->_tableName}` SET $values ";
    if (ShopDB::query($query)) {
      $this->{$this->_idName} = ShopDB::insert_id();
      return $this->id;
    } else
      return false;
  }

  function update($exclude=null){
    $values  = join(",", $this->quoteColumnVals($exclude));

    $sql = "UPDATE `{$this->_tableName}` SET $values";
    if ($this->_idName){
      $sql .= " WHERE `{$this->_idName}` = "._esc($this->id)  ;
    }
    $sql .= " LIMIT 1";
    if (ShopDB::query($sql)) {
      return ($this->_idName) ? $this->id : true; // Not always correct due to mysql update bug/feature
    } else
      return false;
  }

  function quoteColumnVals() {
    $vals = array();
    foreach($this->_columns as $key) {
      if ($val= $this->_set($key)) {
        $vals[] = $val;
      }
    }
    return $vals;
   }

  function _set ($key, $value='~~~'){
    $type= self::getFieldtype($key);
    if ($key == $this->idName) {
      return null;
    } elseif($value =='~~~'){
       If (isset($this->$key)) {
         $value = $this->$key;
       } else
         return null;
    } elseif ($type == self::MDL_IDENTIFY) {
      if ($value === 0)
        $value = null;
    }
    return "`{$key}`="._esc($value);
  }

  function delete()  {
    if (!$this->id) return addWarning('Cant_delete_without_id');

    ShopDB::query("DELETE FROM `{$this->_tableName}`
                   WHERE `{$this->_idName}` = "._esc($this->id));
    return ShopDB::affected_rows();
  }

  Function CheckValues ($arr) {
    foreach($this->_columns as $key){
      if (self::getFieldtype($key)== self::MDL_MANDATORY) {
        if ((!isset($arr[$key]) || empty($arr[$key])) && ( (!isset($this->$key) || empty($this->$key)))) {
          addError($key, 'mandatory');
        }
      }
    }
    return (!hasErrors());
  }

  function _abort ($str=''){
    if ($str)  addWarning ($str);
    if (ShopDB::isTxn()) ShopDB::rollback($str);
    return false; // exit;
  }

  static function getFieldtype(&$key){
    $type= substr($key,0,1);
    if ($type == '#') {
      $key = substr($key,1);
      return self::MDL_IDENTIFY;
    } elseif ($type == '*') {
      $key = substr($key,1);
      return self::MDL_MANDATORY;
    }
    return self::MDL_NONE;
  }

  function fillPost($nocheck=false)    { return $this->_fill($_POST,$nocheck); }
  function fillGet($nocheck=false)     { return $this->_fill($_GET ,$nocheck); }
  function fillRequest($nocheck=false) { return $this->_fill($_REQUEST ,$nocheck); }

  function _fill($arr , $nocheck=true)  {
    if(is_array($arr) and ($nocheck or $this->CheckValues ($arr))) {
      foreach($arr as $key => $val)
        $this->$key = $val;
      return true;
    }
    return false;
   }

  function fillFilename (&$array, $name, $removefile= false) {
    global $_SHOP;
    $remove = 'remove_' . $name;
    if (isset($array[$remove])) {
      if ($removefile) {
        unlink( $_SHOP->files_dir . DS  .$this->$name);
      }
      $array[$name] = '';
    } elseif (!empty($_FILES[$name]) and !empty($_FILES[$name]['name']) and !empty($_FILES[$name]['tmp_name'])) {
      if (!preg_match('/\.(\w+)$/', $_FILES[$name]['name'], $ext)) {
        return addError($name,'img_loading_problem_match');
      }

      $ext = strtolower($ext[1]);
      if (!in_array($ext, $_SHOP->allowed_uploads)) {
        return addError($name,'img_loading_problem_ext');
      }
      echo  $_FILES[$name]['tmp_name'],  $_SHOP->files_dir . DS, $doc_name = strtolower($name) . '_' . 'zzz'. '.' . $ext, ' <br> ';

      if (!move_uploaded_file ($_FILES[$name]['tmp_name'], $_SHOP->files_dir .DS. $doc_name)) {
        return addError($name,'img_loading_problem_copy');
      }

      chmod($_SHOP->files_dir . DS . $doc_name, $_SHOP->file_mode);
      $array[$name] = $doc_name;
//
    }
    return true;
  }

  function fillDate(&$array, $name) {
    $data = (array)$this;
		if ( (isset($array["$name-y"]) and strlen($array["$name-y"]) > 0) or
         (isset($array["$name-m"]) and strlen($array["$name-m"]) > 0) or
         (isset($array["$name-d"]) and strlen($array["$name-d"]) > 0) ) {
			$y = $array["$name-y"];
			$m = $array["$name-m"];
			$d = $array["$name-d"];

			if ( !checkdate($m, $d, $y) ) {
        addError($name, 'invalid');
			} else {
				$array[$name] = "$y-$m-$d";
			}
		}
    return true;
  }

  function fillTime(&$data, $name) {
    global $_SHOP;
    $data = (array)$this;
		if ( (isset($data[$name.'-h']) and strlen($data[$name.'-h']) > 0) or
         (isset($data[$name.'-m']) and strlen($data[$name.'-m']) > 0) ) {
			$h = $data[$name.'-h'];
			$m = $data[$name.'-m'];
			if ( !is_numeric($h) or $h < 0 or $h >= $_SHOP->input_time_type ) {
        addError($name, 'invalid');
			} elseif ( !is_numeric($m) or $h < 0 or $m > 59 ) {
        addError($name, 'invalid');
			} else {
        if (isset($data[$name.'-f']) and $data[$name.'-f']==='PM') {
          $h = $h + 12;
        }
			  $data[$name] = "$h:$m";
        return true;
			}
      return false;
		}
    return true;
  }

  /**
   * When a something is requested instead of talking directly to the var in the class
   * it is called via the __get method.
   *
   * @param $key : the parameters name.
   * Last Updated : 15/11/2008 01:30 CJ
   */
  function __get($key) {
    if ($key==='id') {
      $_idName = $this->_idName;
      return $this->$_idName;
    }/* elseif(substr($key, 0, 2) == '__') {
      return htmlspecialchars($this->data[substr($key, 2)]);
    } elseif (array_key_exists($key, $this->extra)) {
      return $this->extra[$key];
    }else{
      return parent::__get($key);
    }*/
  }

  function _myErrorHandler($errno, $errstr, $errfile, $errline) {
    if($errno!=2){
      addWarning("$errno $errstr $errfil $errline");
    }
  }

  function _dyn_load($name){
    set_error_handler(array(&$this,'_myErrorHandler'));
    $res=include_once($name);
    restore_error_handler();
    return $res;
  }

  function _test() {
    return array($this->_tableName, $this->_idName, $this->_columns);
  }
}

?>