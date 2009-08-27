<?php
require_once('classes/log.php');

class TestOfLogging extends UnitTestCase {
    function TestOfLogging() {
        $this->UnitTestCase('Log class test');
    }
    function setUp() {
        @unlink('test.log');
    }
    function tearDown() {
        @unlink('test.log');
    }
    function getFileLine($filename, $index) {
        $messages = file($filename);
        return $messages[$index];
    }
    function testCreatingNewFile() {
        $log = new Log('test.log');
        $this->assertFalse(file_exists('test.log'), 'No file created before first message');
        $log->message('Should write this to a file');
        $this->assertTrue(file_exists('test.log'), 'File created');
    }
    function testAppendingToFile() {
        $log = new Log('test.log');
        $log->message('Test line 1');
        $this->assertWantedPattern('/Test line 1/', $this->getFileLine('test.log', 0));
        $log->message('Test line 2');
        $this->assertWantedPattern('/Test line 2/', $this->getFileLine('test.log', 1));
    }
}
?>
