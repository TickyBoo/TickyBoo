<?php
/**
 *
 * %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 * 	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
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

define("DB_DEADLOCK", 1213);

class ShopDB {
    // /
    // new SQLi extenstions
    function init ()
    {
        global $_SHOP;

        if (!isset($_SHOP->link)) {
          if (isset($_SHOP->db_name)) {
             $_SHOP->link = new mysqli($_SHOP->db_host, $_SHOP->db_uname, $_SHOP->db_pass, $_SHOP->db_name)
                            or die ("Could not connect: " . mysqli_connect_errno());
             ShopDB::checkdatabase(false, false);
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
    
    function begin ($name='')
    {
        global $_SHOP;
        if (!isset($_SHOP->db_trx_startedi)) {
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

        $res = $_SHOP->link->query($query);
        if (!$res) {
            $_SHOP->db_errno  =$_SHOP->link->errno ;
            self::dblogging("[Error: {$_SHOP->db_errno}] ".$query);
            self::dblogging($_SHOP->db_error= mysqli_error($_SHOP->link));
            $_SHOP->db_errno  =$_SHOP->link->errno ;

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

    function query_one_row ($query)
    {
        if ($result = self::query($query) and $row = $result->fetch_array(MYSQLI_ASSOC)) {
            return $row;
        }
    }
    function quote ($s)
    {
        return is_null($s) ? 'NULL' : "'" . self::escape_string($s) . "'";
    }
		function quoteParam($var) { return self::quote($_REQUEST[$var]); }

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

    function error()
      {
      return $_SHOP->db_error;
      }
    
    function errno()
      {
      return $_SHOP->db_errno;
      }

    function escape_string($escapestr ){
      global $_SHOP;
      if (!get_magic_quotes_gpc ()) {
        if (!isset($_SHOP->link)) {
           self::init();
        }
        return $_SHOP->link->real_escape_string($escapestr);
      } else { echo "get_magic_quotes_gpc<br>\n";
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

    function TableList ($prefix = '')
    {
        $tables = Array ();

        $result = self::Query("SHOW TABLES" . ((!empty($prefix))?" LIKE '$prefix%'":""));

        if (!$result) {
            return false;
        } while ($row = self::fetch_row($result)) {
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
      if (!$update and file_exists(INC.'tmp'.DS.'databasehist.log')) {
        $update = filectime($logfile) < filectime($dbstructfile);
      } else {
        $update = true;
      }

      if ($update) {
        require_once($dbstructfile);
        if ($errors = ShopDB::DatabaseUpgrade($tbls, true, $viewonly)) {
          $handle=fopen($logfile,"a");
          fwrite($handle, date('c',time()).": \n". $errors. "\n");
          fclose($handle);
        }
      }
    }


    /*
    * $DB_Struction needs to be a array with the tablename as key and a second array with fields/index's
    * like: Array(name, $definition)
    *   => Array('ID', 'int(11) NOT NULL auto_increment');
    *
    */
private static function TableCreateData( $tablename )
  {
    $result = self::query_one_row('SHOW CREATE TABLE ' ."`$tablename`");
    if ($result) {
      $tables = $result['Create Table'];
    }
    $keys = array ();
    unset($result);
    if ($tables) {
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
         if (preg_match('/^[\s]*(CONSTRAINT|FOREIGN|PRIMARY|UNIQUE)*[\s]+(KEY)+/', $sql_lines[$i])) {
            $keys['keys'][] = $sql_line;
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

    function DatabaseUpgrade($Struction, $logall =false, $viewonly=false)
    {
      $error = '';
      foreach ($Struction as $tablename => $fields) {
          $update = false;
          If ($tblFields = self::TableCreateData($tablename)) {
              $sql = "";
              $oldkey = '';
              foreach ($fields['fields'] as $key => $info) {
                if (!array_key_exists($key, $tblFields['fields'])) {
                    $update = true;
                    $sql .= ', ADD `' . $key . "` " . $info;
                    $sql .= (($oldkey == '')?' FIRST':' AFTER ' . $oldkey)."\n";
                } elseif ((trim($info)) != (trim($tblFields['fields'][$key]))) {
                    echo "mod: {".$tblFields['fields'][$key]."}<br>\n     {".$info."}<br>\n";
                    $update = true;
                    $sql .= ', MODIFY `' . $key . "` " . $info."\n";
                }
                $oldkey = $key;
              }
              if (isset( $fields['remove'])) {
                foreach ($fields['remove'] as $key) {
                  if (array_key_exists($key, $tblFields['fields'])) {
                      $sql .= ', DROP COLUMN `' . $key . "`\n";
                      $update = true;
                      unset($tblFields['fields'][$key]);
                  }
                }
              }


              foreach ($tblFields['fields'] as $key => $info) {
                if (!array_key_exists($key, $fields['fields'])) {
                    echo "Missing in $tablename: ".$key. $tblFields['fields'][$key].".<br>\n";
                }
              }
              $sql = "ALTER TABLE `$tablename` " . substr($sql, 2);
          } else {
              $update = true;
              $sql = '';               Print_r( $fields);
              foreach ($fields['fields'] as $key => $info) {
                 $sql .= ", `" . $key . "` " . $info."\n";
              }
              If ((isset($fields['key'])) and (count($fields['key']) > 0))
                  foreach ($fields['key'] as $info) $sql .= ', ' . $info."\n";
              $sql = "CREATE TABLE `$tablename` (" . substr($sql, 2) . ")";
              if ($fields['engine']) $sql .= ' ENGINE='.$fields['engine']."\n";
          }
          If ($update) {
             echo ($sql)."<br>\n";//nl3br(
//             self::dblogging("[SQLupdate:] ".$sql."\n");
             If (!$viewonly) {
               $result = self::query($sql);
               if (!$result) $error .= '<B>' .self::error ().":</b>\n";
             }
             if ($logall)  $error .= $sql."\n";
          }
      }
//      self::Upgrade_Autoincrements();
      self::dblogging("[SQLupdate:] Finnish \n");
      return $error;
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