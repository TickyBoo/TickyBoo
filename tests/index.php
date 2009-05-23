<?php
if (! defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', 'simpletest/');
}
require_once(SIMPLE_TEST . 'autorun.php');
class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile(dirname(__FILE__) .'/log_test.php');
        $this->addFile(dirname(__FILE__) .'/clock_test.php');
    }
}
?>