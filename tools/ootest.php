<?php

class albert {
  static $MYCLASS =  __CLASS__;
  protected static $_test = __CLASS__;


  var $ida;

  protected static function echoId(){
    print_r(debug_backtrace())  ;echo __METHOD__;

  }
}


class bert extends albert {
  static $MYCLASS =  __CLASS__;
  protected static $_test = __CLASS__;

  var $idb = 123;

  static function echo2(){
    self::echoId();
    echo ':';echo __METHOD__;

    print_r(debug_backtrace())  ;
  }

}
bert::echo2();
?>