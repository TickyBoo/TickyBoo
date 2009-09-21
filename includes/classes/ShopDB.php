<?php
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

define("DB_DEADLOCK", 1213);

class ShopDB {
    // /
    // new SQLi extenstions
    function init ()
    {
        global $_SHOP;
        unset($_SHOP->db_errno);
        unset($_SHOP->db_error);

        if (!isset($_SHOP->link)) {
          if (isset($_SHOP->db_name)) {
            $DB_Hostname = $_SHOP->db_host;
            $pos = strpos($DB_Hostname,':');
            if ($pos!= false) {
              $port = substr($DB_Hostname,$pos+1);
              $DB_Hostname = substr($DB_Hostname,0, $pos);
            } else {
              $port = 3306;
            }
            $link = new mysqli($DB_Hostname, $_SHOP->db_uname, $_SHOP->db_pass, $_SHOP->db_name, $port);
            /*
             * This is the "official" OO way to do it,
             * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
             */
            if ($link->connect_error) {
                die('Connect Error (' . $link->connect_errno . ') '
                        . $link->connect_error);
            }

            /*
             * Use this instead of $connect_error if you need to ensure
             * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
             */
            if (mysqli_connect_error()) {
                die('Connect Error (' . mysqli_connect_errno() . ') '
                        . mysqli_connect_error());
            }
            $_SHOP->link = $link;
//            ShopDB::checkdatabase(true, false);
            return true;
          } else {
             echo 'db init - ';
             Print_r($_SHOP);
             die ("No connection settings");
          }
        }
        return true;
    }
    // just requires PHP5 and MySQLi And mysql >4.1
    
    function close(){
        global $_SHOP;

        if (isset($_SHOP->link)) {
           $_SHOP->link->close();
        }
    }

    function GetServerInfo () {
        global $_SHOP;
        if (!$_SHOP->link) {
           self::init();
        }
        return mysqli_get_server_info($_SHOP->link);
    }
    
    function begin ($name='')
    {
        global $_SHOP;
        if (!isset($_SHOP->db_trx_startedi)) {
            unset($_SHOP->db_errno);
            unset($_SHOP->db_error);
            if (!$_SHOP->link) {
                self::init();
            }
            if ($_SHOP->link->autocommit(false)) {
               $_SHOP->db_trx_startedi = 1;
               self::dblogging("[Begin {$name}]");
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                self::dblogging("[Begin {$name}]Error: $_SHOP->db_error");
                return false;
            }
        } else {
            $_SHOP->db_trx_startedi++;
            self::dblogging("[Begin {$name}] {$_SHOP->db_trx_startedi}");
            return true;
        }
    }
    function commit ($name='')
    {
        global $_SHOP;
        if ($_SHOP->db_trx_startedi==1) {
            unset($_SHOP->db_errno);
            unset($_SHOP->db_error);
            if ($_SHOP->link->commit()) {
                $_SHOP->link->autocommit(true);
                unset($_SHOP->db_trx_startedi);
                self::dblogging("[Commit {$name}]");
                return true;
            } else {
                user_error($_SHOP->db_error= $_SHOP->link->error);
                self::dblogging("[Commit {$name}]Error: $_SHOP->db_error");
            }
        } elseif ($_SHOP->db_trx_startedi > 1) {
            self::dblogging("[Commit {$name}] {$_SHOP->db_trx_startedi}");
            $_SHOP->db_trx_startedi--;
            return true;
        } else {
            self::dblogging("[Commit {$name}] - no transaction");
        }
    }
    function rollback ($name='')
    {
        global $_SHOP;
        if ($_SHOP->db_trx_startedi) {
            unset($_SHOP->db_errno);
            unset($_SHOP->db_error);
            if ($_SHOP->link->rollback()) {
                $_SHOP->link->autocommit(true);
                self::dblogging("[Rollback {$name}] {$_SHOP->db_trx_started}");
                unset($_SHOP->db_trx_startedi);
                return true;
            } else {
                user_error($_SHOP->db_error= $_SHOP->link->error);
                self::dblogging("[rollback {$name}]Error: $_SHOP->db_error");
            }
        }  else {
            self::dblogging("[Rollback {$name}] no transaction");
        }
    }

    function query($query)
    {
        global $_SHOP;
        // echo  "QUERY: $query <br>";
        if (!isset($_SHOP->link)) {
            self::init();
        }
		// Optionally allow extra args which are escaped and inserted in place of ?
  			if(func_num_args() > 1)
  			{
  				$args = func_get_args();
  				foreach($args as &$item)
  					$item = self::quote($item);
  				$query = vsprintf(str_replace('?', '%s', $query), array_slice($args, 1));
  			}
        unset($_SHOP->db_errno);
        unset($_SHOP->db_error);

        $res = $_SHOP->link->query($query);
        if (!$res) {
            $_SHOP->db_errno  = $_SHOP->link->errno ;
            $_SHOP->db_error  = mysqli_error($_SHOP->link);
            self::dblogging("[Error: {$_SHOP->db_errno}] ".$query);
            self::dblogging($_SHOP->db_error);
            if ($_SHOP->db_errno == DB_DEADLOCK) {
                $_SHOP->db_trx_started = false;
            }
        }
        return $res;
    }

    function insert_id()
    {
        global $_SHOP;
        if (!$_SHOP->link) {
            self::init();
        }
        return $_SHOP->link->insert_id;
    }

    function query_one_row ($query, $assoc = true){
       $assoc = ($assoc)? MYSQLI_ASSOC:MYSQLI_NUM;
        if ($result = self::query($query) and $row = $result->fetch_array($assoc)) {
            return $row;
        }
    }

    function lock ($name, $time = 30)
    {
        $query_lock = "SELECT GET_LOCK('SHOP_$name','$time')";
        if ($res = self::query($query_lock) and $row = $res->fetch_array()) {
            return $row[0];
        }
    }

    function unlock ($name)
    {
        $query_lock = "SELECT RELEASE_LOCK('SHOP_$name')";
        self::query($query_lock);
    }

    function affected_rows()
    {
      global $_SHOP;
        if (!isset($_SHOP->link)) {
            self::init();
        }
        return $_SHOP->link->affected_rows;
    }

    function fetch_array($result)
    {
      global $_SHOP;
      if ($result)
        return $result->fetch_array() ;
    }

    function fetch_assoc($result)
    {
      if ($result)
        return $result->fetch_assoc();
    }

    function fetch_object($result)
    {
      if ($result)
        return $result->fetch_object();
    }

    function fetch_row($result)
    {
      if ($result)
        return $result->fetch_row()  ;
    }

    function num_rows($result)
    {
      if ($result)
        return $result->num_rows ;
    }

    function error() {
      global $_SHOP;
      return $_SHOP->db_error;
      }
    
    function errno(){
      global $_SHOP;
      return $_SHOP->db_errno;
      }

    function quote ($s, $quote=true)
    {
        $str = self::escape_string($s);
        return (!isset($s) or is_null($s)) ? 'NULL' : (($quote)?"'".$str."'":$str);
    }


		function quoteParam($var) { return self::quote($_REQUEST[$var]); }

    function escape_string($escapestr ){
      global $_SHOP;
      if (!get_magic_quotes_gpc ()) {
        if (!isset($_SHOP->link)) {
           self::init();
        }
        return $_SHOP->link->real_escape_string($escapestr);
      } else { //echo "get_magic_quotes_gpc<br>\n";
        return $escapestr;
      }
    }

    function freeResult($result) {
      if (isset($_SHOP->link) and isset($result)) {
        $_SHOP->link->free;

      }
    }
    function tblclose($result) {
      if (isset($_SHOP->link) and isset($result)) {
        $result->close();

      }
    }

  	//function to find the number of fields in a recordSet
  	function num_fields($result) {
  		return $result->field_count;
  	}

  	//function to find the field flags in a recordSet
  	function field_flags($result,$i) {
  		$fld_array = $result->fetch_field_direct($i);
  		if($fld_array->flags & 2)
  			return "primary_key";
  		else
  			return "";
  	}

  	//function to find the field name from recordSet
  	function field_name($result,$i) {
  		$fld_array = $result->fetch_field_direct($i);
  		return $fld_array->orgname;
  	}

  	//function to find the alias field name from recordSet
  	function alias_field_name($result,$i) {
  		$fld_array = $result->fetch_field_direct($i);
  		return $fld_array->name;
  	}

  	//function to find the table of a field name from recordSet
  	function field_table($result,$i) {
  		$fld_array = $result->fetch_field_direct($i);
  		return $fld_array->orgtable;
  	}

    /*
    *
    * Checks if a given table exists in the active database . Returns true if the table exists, false otherwise .
    *
    * @access private
    * @return boolean
    * @param string $tablename The table name to check for
    **/

    function FieldList ($TableName, $prefix = '')
    {
        $Fields = Array ();

        $result = self::Query("SHOW COLUMNS FROM `$TableName`" . ((!empty($prefix))?" LIKE '$prefix%'":""));

        if (!$result) {
            return false;
        } while ($row = self::fetch_row($result)) {
            $Fields[] = $row[0];
        }

        $result->Free;

        Return $Fields;
    }

    function FieldExists ($tablename, $Fieldname)
    {
        $Fields = self::FieldList ($tablename);

        if (($tables) && in_array($Fieldname, $Fields)) {
            return true;
        }else {
            return false;
        }
    }

    function TableList ($prefix = ''){
        $tables = Array ();
        $result = self::Query("SHOW TABLES" . ((!empty($prefix))?" LIKE '$prefix%'":""));
        if (!$result) {
            return false;
        } 
        while ($row = self::fetch_row($result)) {
            $tables[] = $row[0];
        }
        $result->Free;
        Return $tables;
    }


    function TableExists ($tablename)
    {
        $tables = self::TableList ();

        if (($tables) && in_array($tablename, $tables)) {
            return true;
        }else {
            return false;
        }
    }

    function checkdatabase($update=false, $viewonly=false){
      global $_SHOP;
      $logfile = $_SHOP->tmp_dir.'databasehist.log';
      $dbstructfile = INC.'install'.DS.'install_db.php';
      if (!$update and file_exists($_SHOP->tmp_dir.'databasehist.log')) {
        $update = filemtime($logfile) < filemtime($dbstructfile);
      } else {
        $update = true;
      }

      if ($update) {
        require_once($dbstructfile);
        if ($errors = ShopDB::DatabaseUpgrade($tbls, true, $viewonly)) {
          $handle=fopen($logfile,"a");
          fwrite($handle, date('c',time()).": \n". print_r($errors,true). "\n");
          fclose($handle);
        }
        $result ='';
        foreach ($errors as $data) {
          $result .= $data['changes'];
          if ($data['error']) $result .= $data['error'];
        }
        If ($result) {
          require_once('admin'.DS.'AdminView.php');
          echo "
<style type='text/Css'>
.admin_name{
    background-color:#ededed;
    color:#999999;
    font-size: 12px;
    font-weight:bold;}
.admin_value{background-color:#fafafa}
.admin_form{border: #cccccc 1px solid;}
admin_list_title{font-size:16px; font-weight:bold;color:#555555;}

</style>";

          echo "<center>";
          AdminView::form_head(con('Update database structure'),'800',1);
          echo "<tr><td class='admin_value'>".nl2br(str_replace(' ','&nbsp;',$result));
          echo "</td></tr><tr><td align='center' class='admin_value'><form action='index.php'>
            <input type='submit' name='submit' value='" . con('home') . "'></form></td></tr>";
          echo "</table>\n";
          die();
        }
      }
    }


    /*
    * $DB_Struction needs to be a array with the tablename as key and a second array with fields/index's
    * like: Array(name, $definition)
    *   => Array('ID', 'int(11) NOT NULL auto_increment');
    *
    */
private static function TableCreateData( $tablename ) {
  $result = self::query_one_row('SHOW CREATE TABLE ' ."`$tablename`");
  if ($result) {
    $tables = $result['Create Table'];
  }
  unset($result);
  if ($tables) {
    $keys = array ( 'keys'=>array(),'fields'=>array());
    // Convert end of line chars to one that we want (note that MySQL doesn't return query it will accept in all cases)
    if (strpos($tables, "(\r\n ")) {
        $tables = str_replace("\r\n", "\n", $tables);
    } elseif (strpos($tables, "(\r ")) {
        $tables = str_replace("\r", "\n", $tables);
    }
    $tables = str_replace(" default ", " DEFAULT ", $tables);
    $tables = str_replace(" auto_increment", " AUTO_INCREMENT", $tables);
    $tables = str_replace(" on update ", " ON UPDATE ", $tables);

    // Split the query into lines, so we can easily handle it. We know lines are separated by $crlf (done few lines above).
    $sql_lines = explode("\n", $tables);
    $sql_count = count($sql_lines);
    // lets find first line with constraints
    for ($i = 1; $i < $sql_count; $i++) {
       $sql_line = trim($sql_lines[$i]);
       if (substr($sql_line,-1) ==',') $sql_line = substr($sql_line,0,-1);
       if (preg_match('/^[\s]*(CONSTRAINT|FOREIGN|PRIMARY|UNIQUE)*[\s]*(KEY)+/', ' '.$sql_line)) {
          $keys['keys'][] = str_replace('  ',' ',$sql_line);
       } else if (preg_match('/(ENGINE)+/', $sql_line)) {
       } else {
         $x = strpos( $sql_line,' ');
         $key = substr($sql_line,0,$x);
         if (strpos("`'\"", substr($key,0,1)) !== false) {
           $key = substr($key,1,-1);
         }
         $keys['fields'][$key] = substr($sql_line,$x);
       }
    }
  }
  Return $keys;
}

function DatabaseUpgrade($Struction, $logall =false, $viewonly=false) {
  $error = '';  $returning = array();
  foreach ($Struction as $tablename => $fields) {
    $update = false; $datainfo = ''; $error='';
    If ($tblFields = self::TableCreateData($tablename)) {
      $sql = "";
      $oldkey = '';
      $primary ='';
      $txt = '';
      foreach ($fields['fields'] as $key => $info) {
        if (stripos($info,'AUTO_INCREMENT') !== false) $primary = $key;
        if (!array_key_exists($key, $tblFields['fields'])) {
            $datainfo .= "Add $tablename.$key $info\n";
            $update = true;
            $sql .= ', ADD `' . $key . "` " . $info;
            $sql .= (($oldkey == '')?' FIRST':' AFTER ' . $oldkey)."\n";
        } elseif ((trim($info)) != (trim($tblFields['fields'][$key]))) {
            $datainfo .= "mod $tablename.$key:\n     {".$tblFields['fields'][$key]."}\n     {".$info."}\n";
            $update = true;
            $sql .= ', MODIFY `' . $key . "` " . $info."\n";
        }
        $oldkey = $key;
      }
      if (isset( $fields['remove'])) {
        foreach ($fields['remove'] as $key) {
          if (array_key_exists($key, $tblFields['fields'])) {
              $datainfo .= "del $tablename.$key:\n";
              $sql .= ', DROP COLUMN `' . $key . "`\n";
              $update = true;
              unset($tblFields['fields'][$key]);
          }
        }
      }


      foreach ($tblFields['fields'] as $key => $info) {
        if (!array_key_exists($key, $fields['fields'])) {
            $datainfo .= "Missing in $tablename: ".$key. $tblFields['fields'][$key].".\n";
        }
      }
      If ((isset($fields['key'])) and (count($fields['key']) > 0)) {
         foreach ($fields['key'] as $info){
           if (substr($info,0,1)!=='P')
           {
              $sql .= ', ADD ' . $info."\n";
              if (!in_array($info, $tblFields['keys'])) $update = true;
           } elseif (!in_array($info, $tblFields['keys'])) {
              $sql .= ', ADD ' . $info."\n";
              $update = true;
            } elseif ( stripos($info,"`$primary`")===false ) {
              $sql .= ', ADD ' . $info."\n";
           }
         }
      }
      If (isset($fields['key']) and isset($tblFields['key']) and
          count($fields['key']) <> count($tblFields['keys'])) $update = true;
      $sql = "ALTER TABLE `$tablename` " . substr($sql, 2);
      If ($update) {
        $sql1 ='';
        $datainfo .=  $tablename.': db-'. print_r($tblFields['keys'], true).' inst-'.print_r($fields['key'], true);
        If ((isset($tblFields['keys'])) and (count($tblFields['keys']) > 0)) {

          foreach ($tblFields['keys'] as  $info) {
            if (substr($info,0,1)!=='P') {
              $sql1 .= ', DROP '.str_replace('UNIQUE','', substr(trim($info),0,strpos($info,'(')-1))."\n";
            } elseif ( stripos($info,"`$primary`")===false ) {
                $sql1 .= ', DROP PRIMARY KEY'."\n";
            }
          }
        }
        If (!empty($sql1)){
          $result = self::query($sql1 = "ALTER TABLE `$tablename` " . substr($sql1, 2));
          if (!$result) {
            $error .= '$sql1\n<B>' .self::error ().".</b>\n\n";
          }
        }
      }

    } else {
      $update = true;
      $sql = '';
      $datainfo .= "Create table $tablename.";
      foreach ($fields['fields'] as $key => $info) {
         $sql .= ", `" . $key . "` " . $info."\n";
      }
      If ((isset($fields['key'])) and (count($fields['key']) > 0))
          foreach ($fields['key'] as $info) $sql .= ', ' . $info."\n";
      $sql = "CREATE TABLE `$tablename` (" . substr($sql, 2) . ")";
      if ($fields['engine']) $sql .= ' ENGINE='.$fields['engine']."\n";
    }
    If ($update) {
      
      If (!$viewonly) {
        $result = self::query($sql);
        if (!$result) {
          $error .= $sql."\n".self::error ()."\n\n";
        }
      }
      $returning[] = array( 'changes' => $datainfo, 'error' => $error);
      
    }
  }
//      self::Upgrade_Autoincrements();
//      self::dblogging("[SQLupdate:] Finnish \n");
  return $returning;
}

    function Upgrade_Autoincrements()
    {
        $error = '';
        $Struction = self::TableList();

        foreach ($Struction as $tablename ) {
            $keys = self::query_one_row("Show index from ".$tablename);
            if (isset($keys) and ($keys['Key_name'] === "PRIMARY")) {
              $Value = self::query_one_row("select max(".$keys['Column_name'].") from ".$tablename);
              self::query("alter table ".$tablename." auto_increment=".$value[$keys['Column_name']]);
            }
        }
        return $error;
    }
    function dblogging($debug)
    {
        global $_SHOP;
        $handle=fopen($_SHOP->tmp_dir."shopdb.log","a");
        fwrite($handle, date('c',time()).' '. $debug."\n");
        fclose($handle);

    }
}
?>