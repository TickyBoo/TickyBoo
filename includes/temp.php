<?php

 function wp_entities($string, $encode = 1){

$a = (int) $encode;
$original = array("'"   ,"\""   ,"#"    ,"("    ,")");
$entities = array("&%39","&%34;","&%35;","&#40;","&#41;");

if($a == 1)
    return str_replace($original, $entities, $string);
else
    return str_replace($entities, $original, $string);
}


  echo "<input value='".wp_entities(htmlentities('()<>', ENT_QUOTES))."' />";
?>