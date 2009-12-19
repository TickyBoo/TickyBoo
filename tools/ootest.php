<?php

 abstract class albert {
  static $MYCLASS =  __CLASS__;
  protected static $_test = __CLASS__;


  var $ida =431;

   static function echo1(){
    echo '__METHOD__';
  }
}

class bert  extends albert {
  static $MYCLASS =  __CLASS__;
  protected static $_test = __CLASS__;

  var $idb = 123;

   function echo2(){
    echo ':';echo __METHOD__;

    print_r(debug_backtrace())  ;
  }
 
}

$new = new bert;
var_dump($new);

$new->echo1();
albert::echo1()
?>