<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

require_once(INC.'classes'.DS.'model.ort.php');
require_once(TEST_PATH.    'models/_model.test.php');
class TestOfOrtModel extends TestOfModels {

  public $model = false;

  function __construct() {
    parent::__construct('Test Ort model class');
  }

  function setUp() {
    $this->model = new ort ;
    list($this->_tableName, $this->_idName, $this->_columns) = $this->model->_test();
  }

  function testortload() {
/*    if ($this->model) {
      $this->assertNotIdentical($this->model->$_tableName, '' );
      $this->assertTrue(Shopdb::TableExists($this->model->_tableName));

      $this->defs = & ShopDB::FieldListExt($this->model->tableName);
      $cols = $this->model->$_columns;

      foreach($cols as $key) {
        $type = model::getFieldtype($key);
        $this->assertTrue(array_key_exists($key, $this->defs), $key);
        if ($value->Null == 'YES')
          $this->assertEqual($type, model::MDL_MANDATORY, "Mandatory for $key missing. %s");
      }
    }*/
  }
}

?>