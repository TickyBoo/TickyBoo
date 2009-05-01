<?php

session_set_save_handler(array('session',"_open"),
                         array('session',"_close"),
                         array('session',"_read"),

                         array('session',"_write"),
                         array('session',"_destroy"),
                         array('session',"_clean"));

class session  {

function _open()
{
  return ShopDB::init();
}


function _close()
{
  return true;
}

function _read($id)
{
  $sql = "SELECT data
          FROM   sessions
          WHERE  id = ". _esc($id);
  if ($result = ShopDB::query($sql))
  {
    if (ShopDB::num_rows($result))
    {
      $record = ShopDB::fetch_assoc($result);

      return $record['data'];
    }
  }

  return '';
}

function _write($id, $data)
{
  $access = time();

  $sql = "REPLACE
          INTO    sessions
          VALUES  ("._esc($id).", "._esc($access).", ". _esc($data).")";

  return ShopDB::query($sql);
}

function _destroy($id)
{
  echo "destroy session - ";
  $sql = "DELETE
          FROM   sessions
          WHERE id = "._esc($id);
  return ShopDB::query($sql);
}

function _clean($max)
{

  $old = time() - $max;

  $sql = "DELETE
          FROM   sessions
          WHERE  access < "._esc($old);
  return ShopDB::query($sql);
}
}
/*
This requires an existing table named sessions, whose format is as follows:

mysql> DESCRIBE sessions;
+--------+------------------+------+-----+---------+-------+
| Field  | Type             | Null | Key | Default | Extra |
+--------+------------------+------+-----+---------+-------+
| id     | varchar(32)      |      | PRI |         |       |
| access | int(10) unsigned | YES  |     | NULL    |       |
| data   | text             | YES  |     | NULL    |       |
+--------+------------------+------+-----+---------+-------+
This database can be created in MySQL with the following syntax:

CREATE TABLE sessions
(
    id varchar(32) NOT NULL,
    access int(10) unsigned,
    data text,
    PRIMARY KEY (id)
);
*/
?>