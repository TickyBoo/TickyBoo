<?php

class a {
  
  var $id;
  
  function echoId(){
    echo $this->id;
  }
}


class b {
  
  var $id;
   
  function echo2(){  
    echo 'B:';
    $this->id = 5;
    //$a = new a();
    $a->id = 1;
    a::echoId();
    a::echoId();
  }
  
}
 echo 'tedt:';
$b = new b();
$b->echo2();


?>