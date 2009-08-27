<?php
require_once(dirname(__FILE__) .'\..\includes\classes\basics.php');
require_once(dirname(__FILE__) .'\..\includes\config\init_common.php');
require_once(dirname(__FILE__) .'\..\includes\config\init.php');

class TestOfBasic extends UnitTestCase {
    function TestOfBasic() {
        $this->UnitTestCase('Basics.php test');
    }

    function testBasicFormatDate() {
       GLOBAL $_SHOP;
       $this->dump(localeConv());
       $this->dump(get_loc($_SHOP->lang));

       $this->assertIdentical('01 01 2009', formatdate('2009-01-01',"%a %e %b %Y"));
       $this->assertIdentical('01 01 2009', formatdate('01-01-2009',"%a %m %d %Y"));
       $this->assertIdentical('01 01 2009', formatdate('01/01/2009'."%a %b %Y"));
       $this->assertIdentical('01 01 2009', formatdate('01.01.2009',"%a %e %Y"));
    }
}
?>
