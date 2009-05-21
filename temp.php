<?php

 function wp_entities($string, $encode = 1){

$a = (int) $encode;
$original = array("'"   ,"\""   ,"#"    ,"("    ,")");
$entities = array("&%39","&%34;","&%35;","&#40;","&#41;");

if($a == 1) {
    $string = htmlentities( $string);
    return str_replace($original, $entities, $string);
}else {
    $string = html_entity_decode ( $string);
    return str_replace($entities, $original, $string);
}}


  echo "<input value='".wp_entities(('()<>'))."' />";

  $testme = wp_entities("temp.php?getme=1234&datte=sjfy"."\"><script>alert('xss')</script><foo ");
  //phpinfo();
  print_r($_GET);
  
  
?>
<html>

 <body>

  <form  method='post' action="<?php echo $testme ?>">

   <input type="hidden" name="submitted" value="1" />

   <input type="submit" value="Submit!" />

  </form>

 </body>

</html>
