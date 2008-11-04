<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {assign} compiler function plugin
 *
 * Type:     compiler function<br>
 * Name:     assign<br>
 * Purpose:  assign a value to a template variable
 * @link http://smarty.php.net/manual/en/language.custom.functions.php#LANGUAGE.FUNCTION.ASSIGN {assign}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> (initial author)
 * @author messju mohr <messju at lammfellpuschen dot de> (conversion to compiler function)
 * @param string containing var-attribute and value-attribute
 * @param Smarty_Compiler
 */
function smarty_function_fillarr ($params,&$smarty)
{
    if (!isset($params['var'])) {
        $compiler->_syntax_error("assign: missing 'var' parameter", E_USER_WARNING);
        return;
    }
    if (!isset($params['count'])) {
        $compiler->_syntax_error("assign: missing 'count' parameter", E_USER_WARNING);
        return;
    }

    if (!isset($params['value'])) {
        $compiler->_syntax_error("assign: missing 'value' parameter", E_USER_WARNING);
        return;
    }
    If (isset($params['clear'])) {
       $data = array();
    }
    else
    If (is_array($smarty->get_template_vars($params['var']))) {
       $data = $smarty->get_template_vars($params['var']);
    }
    $x = $params['count'];
    for ($i = 0; $i < $x  ; $i++) {
       $data[] = $params['value'];
    }

    $smarty->assign($params['var'],$data);
    return;
}

/* vim: set expandtab: */

?>
