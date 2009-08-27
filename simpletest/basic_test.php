<?php
require_once(dirname(__FILE__) .'/../includes/classes/basics.php');
require_once(dirname(__FILE__) .'/../includes/config/init_common.php');
require_once(dirname(__FILE__) .'/../includes/config/init.php');

class TestOfBasic extends UnitTestCase {
    function TestOfBasic() {
        $this->UnitTestCase('Basics.php test');
    }

    function testBasicFormatDate() {
       GLOBAL $_SHOP;
       $this->dump('php:'.phpversion ());
       $this->dump(get_loc($_SHOP->lang));
       $this->dump(setlocale(LC_ALL, NULL));

       $this->assertIdentical('01 01 2009', formatdate('2009-01-05',"%a %d %b %Y"));
       $this->assertIdentical('01 01 2009', formatdate('05-01-2009',"%a %d %m %Y"));
       $this->assertIdentical('01 01 2009', formatdate('05/01/2009',"%a %b %Y"));
       $this->assertIdentical('01 01 2009', formatdate('05.05.2009',"%a %j %Y"));
    }
}
?>
