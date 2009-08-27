<?php
if (! defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', 'simpletest/');
}
error_reporting(E_ALL);
require_once(SIMPLE_TEST . 'autorun.php');
class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile(dirname(__FILE__) .'/basic_test.php');
    }
}
?>