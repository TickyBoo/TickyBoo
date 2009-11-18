<?php

class a {
  const MYCLASS =  __CLASS__;
  var $ida;
  
  function echoId(){
    print_r(self::MYCLASS)  ;
  }
}


class b extends a {
  
  var $idb = 123;
   
  function echo2(){  
    self::echoId();      echo ':';
    print_r(self::MYCLASS)  ;
  }
  
}
function test () {
  print ($b);
}

$b = new b();
$GLOBALS['b'] = $b;

$b->echo2();
echo '-';
b::echo2();
echo '-';
test();
?>