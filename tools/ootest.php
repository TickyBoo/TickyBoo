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
    $this->id = 5;
    $a = new a();
    //$a->id = 1;
    $a->echoId();
  }
  
}

$b = new b;
$b->echo2();


?>