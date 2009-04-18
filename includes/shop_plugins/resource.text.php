function smarty_resource_text_template ($tpl_name, &$tpl_source, &$smarty_obj)
{
  $tpl_source = $this->template;
  return true;
}

function smarty_resource_text_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
{
  $tpl_timestamp =time();//$this->timestamp;
  return true;
}

function smarty_resource_text_secure($tpl_name, &$smarty_obj)
{
  // assume all templates are secure
  return true;
}

function smarty_resource_text_trusted($tpl_name, &$smarty_obj)
{
    // not used for templates
}