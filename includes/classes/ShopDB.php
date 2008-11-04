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
    function initi ()
    {
        global $_SHOP;

        if (!isset($_SHOP->link) and isset($_SHOP->db_name)) {
            $_SHOP->link = new mysqli($_SHOP->db_host, $_SHOP->db_uname, $_SHOP->db_pass, $_SHOP->db_name)
            or die ("Could not connect: " . mysqli_connect_errno());
            ShopDB::dblogging("[II] -----------------------------\n");
        } else {
            die ("No connection settings");
        }
    }
    // just requires PHP5 and MySQLi And mysql >4.1
    function begini ()
    {
        global $_SHOP;
        if (!isset($_SHOP->db_trx_startedi)) {
            if (!$_SHOP->link) {
                ShopDB::initi();
            }
            if ($_SHOP->link->autocommit(false)) {
//                ShopDB::dblogging("[BeginI]\n");
                $_SHOP->db_trx_startedi = 1;
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                ShopDB::dblogging("[BeginI]Error: $_SHOP->db_error\n");
                return false;
            }
        } else {
            $_SHOP->db_trx_startedi++;
            return true;
        }
    }
    function commiti ()
    {
        global $_SHOP;
        if ($_SHOP->db_trx_startedi==1) {
            if ($_SHOP->link->commit()) {
                $_SHOP->link->autocommit(true);
                unset($_SHOP->db_trx_startedi);
                ShopDB::dblogging("[CommitI]\n");
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                ShopDB::dblogging("[CommitI]Error: $_SHOP->db_error\n");
            }
        } elseif  ($_SHOP->db_trx_startedi > 1) {$_SHOP->db_trx_startedi--;}
    }
    function rollbacki ()
    {
        global $_SHOP;
        if ($_SHOP->db_trx_started) {
            if ($_SHOP->link->rollback()) {
                $_SHOP->link->autocommit(true);
                unset($_SHOP->db_trx_started);
                ShopDB::dblogging("[RollbackI]\n");
                return true;
            } else {
                user_error($_SHOP->db_error= mysqli_error($_SHOP->link));
                ShopDB::dblogging("[RollbackI]Error: $_SHOP->db_error\n");
            }
        }
    }

    function queryi($query)
    {
        global $_SHOP;
        // echo  "QUERY: $query <br>";
        if (!isset($_SHOP->link)) {
            ShopDB::initi();
        }
        $res = $_SHOP->link->query($query);
        if (!$res) {
            ShopDB::dblogging("[Error:] ".$query."\n");
            ShopDB::dblogging($_SHOP->db_error= mysqli_error($_SHOP->link));
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
            ShopDB::initi();
        }
        return $_SHOP->link->insert_id;
    }

    function queryi_one_row ($query)
    {
        if ($result = ShopDB::queryi($query) and $row = mysqli_fetch_array($result)) {
            return $row;
        }
    }
    function quotei ($s)
    {
        return "'" . shopDB::escape_string($s) . "'";
    }
    
    function init ()
    {
        return shopDB::initi();
    }

    function query ($query)
    {
      return shopDB::queryi($query);
    }

    function lock ($name, $time = 30)
    {
        $query_lock = "SELECT GET_LOCK('SHOP_$name','$time')";
        if ($res = ShopDB::query($query_lock) and $row = $res->fetch_array()) {
            return $row[0];
        }
    }

    function unlock ($name)
    {
        $query_lock = "SELECT RELEASE_LOCK('SHOP_$name')";
        ShopDB::query($query_lock);
    }

    function query_one_row ($query)
    {
        if ($result = ShopDB::query($query) and $row = $result->fetch_array()) {
            return $row;
        }
    }

    function quote ($s)
    {
        return shopDB::quotei($s);
    }

    function begin ()
    {
        return shopDB::begini();
    }

    function commit ()
    {
        return ShopDB::commiti();
    }

    function rollback ()
    {
        return shopDB::rollbacki();
    }

    function affected_rows()
    {
      global $_SHOP;
        if (!isset($_SHOP->link)) {
            ShopDB::initi();
        }
        return $_SHOP->link->affected_rows  ;
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
            ShopDB::initi();
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

        $result = ShopDB::Queryi("SHOW COLUMNS FROM `$TableName`" . ((!empty($prefix))?" LIKE '$prefix%'":""));

        if (!$result) {
            return false;
        } while ($row = ShopDB::fetch_row($result)) {
            $Fields[] = $row[0];
        }

        $result->Free;

        Return $Fields;
    }

    function FieldExists ($tablename, $Fieldname)
    {
        $Fields = ShopDB::FieldList ($tablename);

        if (($tables) && in_array($Fieldname, $Fields)) {
            return true;
        }else {
            return false;
        }
    }

    function TableList ($prefix = '')
    {
        $tables = Array ();

        $result = ShopDB::Queryi("SHOW TABLES" . ((!empty($prefix))?" LIKE '$prefix%'":""));

        if (!$result) {
            return false;
        } while ($row = ShopDB::fetch_row($result)) {
            $tables[] = $row[0];
        }

        $result->Free;

        Return $tables;
    }


    function TableExists ($tablename)
    {
        $tables = ShopDB::TableList ();

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
            If (ShopDB::TableExists("$tablename")) {
                $sql = "";
                $tblFields = ShopDB::FieldList($tablename);
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
                $result = ShopDB::queryi($sql, false);
                if (!$result) $error .= '<B>' .ShopDB::error () . '</b><br>' . $sql . '<br>';
            }
        }
        return $error;
    }

    function Upgrade_Autoincrements()
    {
        $error = '';
        $Struction = shopDB::TableList();

        foreach ($Struction as $tablename ) {
            $keys = ShopDB::query_one_row("Show index from ".$tablename);
            if (isset($keys) and ($keys['Key_name'] === "PRIMARY")) {
              $Value = ShopDB::query_one_row("select max(".$keys['Column_name'].") from ".$tablename);
              ShopDB::queryi("alter table ".$tablename." auto_increment=".$value[$keys['Column_name']]);
            }
        }
        return $error;
    }
    function dblogging($debug)
    {
        global $_SHOP;
        $handle=fopen($_SHOP->install_dir."/includes/tmp/shopdb.log","a");
        fwrite($handle,$debug);
        fclose($handle);

    }
}

?>