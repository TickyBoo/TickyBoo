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

        if (!isset($_SHOP->link) and isset($_SHOP->db_name)) {
            $_SHOP->link = new mysqli($_SHOP->db_host, $_SHOP->db_uname, $_SHOP->db_pass, $_SHOP->db_name)
            or die ("Could not connect: " . mysqli_connect_errno());
            self::dblogging("[II] -----------------------------\n");
        } else {
            die ("No connection settings");
        }
    }
    // just requires PHP5 and MySQLi And mysql >4.1
    function begin ()
    {
        global $_SHOP;
        if (!isset($_SHOP->db_trx_startedi)) {
            if (!$_SHOP->link) {
                self::init();
            }
            if ($_SHOP->link->autocommit(false)) {
//                self::dblogging("[Begin]\n");
                $_SHOP->db_trx_startedi = 1;
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                self::dblogging("[Begin]Error: $_SHOP->db_error\n");
                return false;
            }
        } else {
            $_SHOP->db_trx_startedi++;
            return true;
        }
    }
    function commit ()
    {
        global $_SHOP;
        if ($_SHOP->db_trx_startedi==1) {
            if ($_SHOP->link->commit()) {
                $_SHOP->link->autocommit(true);
                unset($_SHOP->db_trx_startedi);
                self::dblogging("[Commit]\n");
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                self::dblogging("[Commit]Error: $_SHOP->db_error\n");
            }
        } elseif  ($_SHOP->db_trx_startedi > 1) {$_SHOP->db_trx_startedi--;}
    }
    function rollback ()
    {
        global $_SHOP;
        if ($_SHOP->db_trx_started) {
            if ($_SHOP->link->rollback()) {
                $_SHOP->link->autocommit(true);
                unset($_SHOP->db_trx_started);
                self::dblogging("[rollback]\n");
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                self::dblogging("[rollback]Error: $_SHOP->db_error\n");
            }
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
            self::dblogging("[Error:] ".$query."\n");
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
        if ($result = self::query($query) and $row = $result->fetch_array()) {
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

    function escape_string($escapestr )
      {
      global $_SHOP;
        if (!isset($_SHOP->link)) {
            self::init();
        }
      return $_SHOP->link->real_escape_string($escapestr);
    }

    function freeResult($result) {
      if (isset($_SHOP->link) and isset($result)) {
        $_SHOP->link->free;

      }
    }
    function close($result) {
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


    /*
    * $DB_Struction needs to be a array with the tablename as key and a second array with fields/index's
    * like: Array(name, $definition)
    *   => Array('ID', 'int(11) NOT NULL auto_increment');
    *
    */
    function DatabaseUpgrade($Struction)
    {
        $error = '';
        foreach ($Struction as $tablename => $fields) {
            if (!is_array($fields)) continue;
            $tblFields = array();
            $update = false;
//            echo $tablename.': '.nl2br(print_r($fields,true));
            If (self::TableExists("$tablename")) {
                $sql = "";
                $tblFields = self::FieldList($tablename);
                $oldkey = '';
                foreach ($fields['fields'] as $key => $info) {
                    if (!$tblFields or !in_array($key, $tblFields)) {
                        $update = true;
                        $sql .= ', ADD ' . $key . " " . $info;
                        $sql .= ($oldkey != '')?' FIRST':' AFTER ' . $oldkey;
                    }
                    $oldkey = $key;
                }
                $sql = "ALTER TABLE `$tablename` " . substr($sql, 2);
            } else {
                $update = true;
                $sql = '';
                foreach ($fields['fields'] as $key => $info) {
                   $sql .= ", `" . $key . "` " . $info."\n";
                }
                If ((isset($fields['key'])) and (count($fields['key']) > 0))
                    foreach ($fields['key'] as $key => $info) $sql .= ', ' . $info."\n";
                $sql = "CREATE TABLE `$tablename` (\n" . substr($sql, 2) . ")";
                if ($fields['auto_increment']) $sql .= ' AUTO_INCREMENT='.$fields['auto_increment'];
                if ($fields['engine']) $sql .= ' ENGINE='.$fields['engine'];
            }
            If ($update) {
               // echo nl2br($sql)."<br><br>\n";
                $result = self::query($sql, false);
                if (!$result) $error .= '<B>' .self::error () . '</b><br>' . $sql . '<br>';
            }
        }
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
        fwrite($handle,$debug);
        fclose($handle);

    }
}
?>