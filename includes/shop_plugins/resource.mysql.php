<?php


function smarty_resource_mysql_source($tpl_name, &$tpl_source, $smarty)
{
  $tpl_sql="
     SELECT tpl_source
     FROM  templates
     WHERE tpl_name="._esc($tpl_name)."
     AND   tpl_context ="._esc($smarty->context)."
     LIMIT 1;
  ";
  if ($row = ShopDB::Query_one_row($tpl_sql)) {
        $tpl_source = $row['tpl_source'];
        return(true);
     }
  #}

  return(false);
} # smarty_read_template()

/**
* When was the template resource last modified?
* If the template's timestamp changes, Smarty re-compiles the template resource.
* @param $tpl_name string Name of the template (Address reference)
* @param $tpl_source string Source of the template (Address reference)
* @param $smarty Smarty The Smarty object (Address reference)
* @return boolean Is the last modification timestamp of the template changed?
*/
function smarty_resource_mysql_timestamp($tpl_name, &$tpl_timestamp, $smarty)
{
  $success=false;
  if ($row = ShopDB::Query_one_row("
     SELECT UNIX_TIMESTAMP(tpl_timestamp) AS tpl_timestamp
     FROM  templates
     WHERE tpl_name="._esc($tpl_name)."
     LIMIT 1;
     ")) {
        $tpl_timestamp = $row['tpl_timestamp'];
        $success=true;
  }
  return($success);
} # smarty_template_timestamp()

?>