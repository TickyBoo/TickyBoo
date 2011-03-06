<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 */

/**
 * @author Chris Jenkins
 * @copyright 2008
 */

if (!defined('ft_check')) {die('System intrusion ');}
class Gui_smarty {

	/**
	* Base URL
	*
	* @var string
	*/
	var $width      = '95%';
	var $FormDepth  = 0;
	var $_ShowLabel = True;
	var $gui_name   = 'gui_name';
	var $gui_value  = 'gui_value';
	var $gui_name_width  = '30%';
  public $guidata = array();

  function __construct  ($smarty){

    $smarty->register_object("gui",$this);
    $smarty->assign_by_ref("gui",$this);

    $smarty->register_function('ShowFormToken', array($this,'showFormToken'));
    $smarty->register_function('valuta', array($this,'valuta'));
    $smarty->register_function('print_r', array($this,'print_r'));
    $smarty->register_function('printMsg', array($this,'printMsg'));
    $smarty->register_modifier('clean', 'smarty_modifier_clean');
    $smarty->register_function('weeksofyear', 'weeksofyear');

  }

  function printMsg($params, $smarty) {
    $addspan     = is($params['addspan'],true);
    return printMsg($params['key'],null, $addspan );
  }

  function url($params, $smarty, $skipnames){
    GLOBAL $_SHOP;
    if (isset($params['surl'])) {
      return $_SHOP->root_secured.'?'.$params['surl'];
    } elseif (isset($params['url'])) {
      return $_SHOP->root.'?'.$params['url'];
    } else {
      If (!is_array($skipnames)) {$skipnames= array();}
    //  print_r($params);
      $urlparams ='';
      foreach ($params as $key => $value) {
        if ($params['secure']) {
          unset($params['secure']);
          $secure = true;
        } else $secure = false;
        if (!in_array($key,$skipnames)) {
          $urlparams .= (($urlparams)?'&':'').$key.'='.$value;
        }
      }
      $urlparams .= (($urlparams)?'?':'').$urlparams;

      If ($secure) {
        $result = $_SHOP->root_secure;
      } elseif (isset($params['url'])) {
        $result = $_SHOP->root_secure;
      }

      return $result.$urlparams;
     // print_r($urlparams);

   //   return makeURL($params['action'], $urlparams, $params['controller'], $params['module']);
    }
  }

  /**
    *   Smarty {currenturl} plugin
    *
    *   Type:      function
    *   Name:      currenturl
    *   Purpose:   returns the url with the new and merged parameters
    *   Parameters:   - a key=value pair for the parameter string
    *
    *   ChangeLog:
    *   - 1.0 initial release
    *
    *   @version 1.0
    *   @author Bastian Friedrich
    *   @param array
    *   @param Smarty
    *   @return string
    */

   function currenturl($params, &$smarty, $skipnames)
   {
   	  global $_SHOP;
      $queryHash   = Array();

      //   write the parameters of the current url
      //   in a key-value pair hash/array
      foreach(explode('&', $_SERVER['QUERY_STRING']) as $value)
      {
         $results = explode('=', $value);

         //   continue by empty key-value pair
         if (empty($results[0]) &&
            empty($results[1]))
         {
            continue;
         }

         $queryHash[$results[0]] = $results[1];
      }

      //   merge parameters from the current url
      //   with the new parameters
      //   notice: the same keys will be overwritten
      $paramHash         = array_merge(   $queryHash,
                                 $params);
      $paramStringHash   = Array();
    If (!is_array($skipnames)) {$skipnames= array();}
      //   write the new hash/array in the query
      //   syntax
      foreach ($paramHash as $key => $value)
      {
      	if (!in_array($key,$skipnames)) {
         array_push($paramStringHash, $key.'='.$value);
      }
      }

      //   return the url with the new parameters
      return $_SERVER['PHP_SELF'].'?'.implode('&', $paramStringHash);
   }

  function print_r($params, $smarty) {
    return '<pre>'.print_r($params['var'],true).'</pre>';
  }

  function fillArr($params, $smarty)
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


  function showFormToken ($params, $smarty) {
    global $_SHOP;
    $name  = str_replace('_','', is($params['name'],'FormToken'));
    $token = Secure::getFormToken($name, is($_SHOP->first_Token,false));
    $_SHOP->first_Token = false;
    return "<input type='hidden' name='___{$name}_{$token}' value='".htmlspecialchars(sha1 (md5(mt_rand()).'~'.$token.'~'.getIpAddress()))."'/>";
  }
/**
 * build a href link
 * @param string $title what the link should say
 * @param string url where the link should point (is automatically expanded if relative link)
 * @param array $htmlAttributes array of attributes of the link, for example "class"=>"dontunderline"
 * @param string an optional message that asks if you are really shure if you click on the link and aborts navigation if user clicks cancel, ignored if false
 * @param boolean $escapeTitle if we should run htmlspecialchars over the links title
 */

	/**
     * escape string stuitable for javascript or php output
     * @param string $s string to escape
     * @param bool $singleQuotes if single quotes should be escaped (true) or double-quotes (false)
     */
	private function escape($s,$singleQuotes=true) {
		if ($singleQuotes) {
		  return str_replace(array("'","\n","\r"),array('\\\'',"\\n",""), $s);
		}
    return str_replace(array('"',"\n","\r"),array('\\"',"\\n",""), $s);
	}

   function setData($params, $smarty) //($name, $width = 0, $colspan = 2)
  {
    If( isset($params['data'])) {
      $this->guidata = $params['data'];
    }
    If( isset($params['nameclass'])) {
      $this->gui_name = $params['nameclass'];
    }
    If( isset($params['valueclass'])) {
      $this->gui_value = $params['valueclass'];
    }
    If( isset($params['namewidth'])) {
      $this->gui_name_width = $params['namewidth'] ;
    }
  }
  function StartForm($params, $smarty) //($name, $width = 0, $colspan = 2)
  {
    $name     = is($params['name']);
    $id       = is($params['id']);
    $title    = is($params['title']);
    $table    = is($params['table'],true);
    $width    = is($params['width'],$this->width);
    $class    = is($params['class'],'gui_form');
    $enctype  = is($params['enctype'],'application/x-www-form-urlencoded');
    $method   = is($params['method'],'post');
    $onsubmit = is($params['onsubmit'],'');
    $url      = is($params['action']);//$this->_URL( $params, $smarty, array('name','class','width','method','title','enctype','onsubmit', 'data' ));
    If( isset($params['data'])) {
      $this->guidata = $params['data'];
    }
    $return ='';
    If ($method <> 'none') {
       $target       = is($params['target']);
       if ($target) $target = 'target="'.$target.'"';
      $return .= "<form action='$url' id='$id' name='$name' method='$method' enctype='$enctype' $target";
      $return .= ($onsubmit)?" onsubmit ='$onsubmit'>":">";
      $return .= self::showFormToken( $params, $smarty);
      $this->FormDepth ++;
      $this->_ShowLabel = True;
    }
    if($table){
      $return .= "<table class='$class' width='$width' border=0 cellspacing='1' cellpadding='4'>\n";
      if ($title) {
        $return .= "<tr><th class='$class_title' colspan='2'>$title</th></tr>\n";
      }
    }
    return $return;
  }

  function EndForm($params, $smarty) //($colspan = 2)
  {
    $name     = is($params['name'],'submit');
    $align    = is($params['align'],'right');
    $title    = is($params['title'], con('gui_save','submit'));
    $class    = is($params['class'], $this->gui_value);
    $noreset  = is($params['noreset'], false);
    $show  = is($params['showbuttons'], true);
    $onclick  = is($params['onclick'],'');
    if ($show) {
     $return = "<tr class='$class' ><td style='text-align:{$align};' colspan='2'>\n".
        //        $this->jbutton(array('url'=>'submit', 'name'=>$name, 'title'=>$title), $smarty );

    $return = "<input type='submit' name='$name'  id='$name' value='{$title}'  style='float:none;' >";
    if (!$noreset) {
        $return .= "&nbsp; ".
          "<input type='reset' name='reset'  id='reset' style='float:none;' >";
//      $this->jbutton(array('url'=>'reset', 'name'=>'reset'), $smarty );
    }
    $return .= "</td></tr>\n";
    }
    $return .=  "</table>\n";
    if ($this->FormDepth) {
      $this->FormDepth --;
      $return .= "</form>\n";
    }
    return $return;
  }

  function setShowLabel($params, $smarty) {
    $this->_ShowLabel =is($params['set'],$this->_ShowLabel);
  }

  private function showlabel($name, $value, $params=array()) {
    $nolabel  = is($params['nolabel'],false);
    $caption  = is($params['caption'],con($name));
    $mandatory = $params['mandatory'] ? "*" :"";
    if ($this->_ShowLabel and !$nolabel) {
      $return = "<tr id='{$name}-tr' class='shop-tr'><td id='{$name}-label' class='{$this->gui_name}' width='40%'>{$caption} {$mandatory}</td>".
                "    <td id='{$name}-value' class='{$this->gui_value}'>{$value}";
      $return .= printMsg($name);
      return $return."</td></tr>\n";
    } else {
      return $value;
    }
  }

  function view($params, $smarty) //$name, &$data, $prefix = ''*/)
  {
    $name = is($params['name']);
    $Option = is($params['option'], false);
    $value  = is($params['value'],$this->guidata[$name]);
    If (!$Option or $this->values[$name]) {
      return $this->showlabel($name, $value, $params);
    }
  }

  function hidden ($params, $smarty) //$name, &$data, $size = 30, $max = 100)
  {
    $name = is($params['name'] );
    $value  = is($params['value'],$this->guidata[$name]);
    return "<input type='hidden' id='$name' name='$name' value='" . htmlspecialchars($value, ENT_QUOTES) ."'>\n";
  }

  function input ($params, $smarty) //$name, &$data, $size = 30, $max = 100)
  {
    $name = is($params['name'] );
    $type = is($params['type'], 'text');
    $size = is($params['size'], 30);
    $max  = is($params['maxlength'] ,100);
    $value  = is($params['value'],$this->guidata[$name]);
	$disabled = $params['disabled'] ? "disabled" :"";
    return $this->showlabel($name, "<input type='$type' id='$name' name='$name' value='" . htmlspecialchars($value, ENT_QUOTES) .
           "' size='$size' maxlength='$max' $disabled>",$params);
  }

  function checkbox ($params, $smarty) //($name, &$data, &$err, $size = '', $max = '')
  {
    $name = is($params['name']    );
    if ($this->guidata[$name]) {
      $chk = 'checked';
    }
    return $this->showlabel($name, "<input type='checkbox' id='$name' name='$name' value='1' $chk>",$params);
  }

  function area ($params, $smarty) //($name, &$data, &$err, $rows = 6, $cols = 40,  = '')
  {
    $name = is($params['name']   );
    $rows = is($params['rows'], 6);
    $cols = is($params['cols'],80);
    return $this->showlabel($name, "&nbsp;</td></tr><tr><td colspan=2><textarea rows='$rows' cols='$cols' style='width:100%;' id='$name' name='$name'>" . htmlspecialchars($this->guidata[$name], ENT_QUOTES) . "</textarea>",$params);
  }

  function inputTime ($params, $smarty) //($name, &$data, &$err,  = '')
  {
    require_once('class.datetimeselect.php');
    $name = is($params['name']    );
    $timeselect = new DateTimeSelect('t', $name, $this->guidata[$name],0);
    return $this->showlabel($name, $timeselect->selectbox,$params);
  }

  function button($params, $smarty ){
    global $_CONFIG;
    $name = is($params['name']    );
    $url  = is($params['url']    );
    $type  = is($params['type'], 2);
    $classes = is($params['classes'],'');
    $style   = is($params['style'],'');
    $idname  = is($params['id'], $name);
    $disabled= is($params['disable'],false);
    $onclick = is($params['onclick'], '');

    if(!empt($name,false)){
        return;
    }
    $button = false;
    $text = false;
    $icon = false;
    $disClass = '';
    $disAtr = '';

    //Find what to show
    if($type===1 || $type >= 3){
      $text = $name;
    }
    if(is($params['image'],false)){
      $icon = true;
      $image = $params['image'];
    }elseif($type===2 || $type >= 3){
      $iconArr = array(
        'add'=>array('image'=>'ui-icon-plusthick'),
        'edit'=>array('image'=>'ui-icon-pencil'),
        'view'=>array('image'=>'ui-icon-comment'),
        'list'=>array('image'=>'ui-icon-arrowthick-1-w'),
        'delete'=>array('image'=>'ui-icon-trash'),
        'remove'=>array('image'=>'ui-icon-trash'),
        'home'=>array('image'=>'ui-icon-home')
        );
      foreach($iconArr as $icoNm=>$iconDtl){
        $name2 = strtolower($name);
        if(preg_match('/'.$icoNm.'/',$name2)){
          $icon = $icoNm;
          $image = $iconDtl['image'];
          break;
        };
      }
      if(!$icon){
        $text = $name;
      }
    }
    //Is it a button?
    if($url=='submit' || $url=='reset'|| $url=='button'){
      $button = true;
    }
    //Extra options
    if ($onclick) {
      $onclick = ' onclick="'.$onclick.'"';
    }
    if($disabled){
      $disAtr = " disabled='disabled' ";
      $disClass = " ui-state-disabled ";
      if (!$button) $url = '';
      $onclick = '';
    }
    //Tooltip stuff
    $toolTipName = $this->hasToolTip($name);
    $hasTTClass = 'has-tooltip';
    $toolTipText = con($toolTipName);
    if(is($params['showtooltip'], false)===false){
      $hasTTClass = '';
      $title = is($params['title'],con($name));

    }elseif(empt($params['tooltiptext'],false)){
      $toolTipText = $params['tooltiptext'];
      $toolTipName = empt($toolTipName,$name."-tooltip");

    }elseif(!empt($toolTipName,false)){
      $hasTTClass = '';
      $title = con($name);
    }

    $alt     = is($params['alt'],is($title,con($name)));
    if ($alt) {
      $alt = " alt='{$alt}'";
    }
    $rtn = "";

    //If image bolt on image css for button
    if($icon && $image){
      $css = "{icons: {primary: '{$image}' }";
      if (!$text) $css .= ', text: false';
      $css .= '}';
     }else{ $css = ''; }
    if ($style) $style=" style='{$style}'";
    $class= trim("{$hasTTClass} {$classes} {$disClass}");
    if ($class) $class=" class='{$class}'";

    if(!$button){
      $rtn .= "<a id='{$idname}'{$class}{$style} href='".empt($url,'#')."' title='{$title}'{$onclick}{$alt}>";
    }else{
      $rtn .= "<button $disAtr type='{$url}' name='{$name}' id='{$idname}'{$class}{$style}{$onclick}{$alt}>";
    }
    $this->addJQuery("$(\"#{$idname}\").button({$css});");
/*    if($icon && $image && $text){
      $rtn .= "   <span class='ui-icon' style='background-image:url(\"{$_CONFIG->root}img/{$image}\"); background-position:center center; margin:-8px 5px 0 0; top:50%; left:0.6em; position:absolute;' title='{$title}' ></span>";
    }elseif($icon && $image){
      $rtn .= "   <span class='ui-icon' style='background-image:url(\"{$_CONFIG->root}img/{$image}\"); background-position:center center; ' title='{$title}' ></span>";
    }
*/
    if($text){
      $rtn .= con($text);
    } else {
      $rtn .= $title;
    }
     //Add on the Tooltip div for the text
/*    if(!empty($hasTTClass)){
      $rtn .= "<div id='{$toolTipName}' style='display:none;'>{$toolTipText}</div>";
    } */
    if(!$button){
      $rtn .= "</a>";
    }else{
      $rtn .= "</button>\n";
    }
    return $rtn;//. $disabled;
  }

  function hasErrors () {
    return hasErrors();
  }

  public function getJQuery(){
    return $this->jScript;
  }

  protected function addJQuery($script){
    $this->jScript .= $script."\n";
  }


  protected function hasToolTip($constantName){
    if( defined($constantName."-tooltip")){
      return false;
    }else{
      return $constantName."-tooltip";
    }
  }

  function inputDate ($params, $smarty) //($name, &$data, &$err,  = '')
  {
    require_once('class.datetimeselect.php');
    $name = is($params['name']    );
    $type = is($params['type'],'d' );
    $range = is($params['range'],5    );
    $timeselect = new DateTimeSelect($type, $name, $this->guidata[$name],$range);
    return $this->showlabel($name, $timeselect->selectbox, $params);
  }

  function viewUrl ($params, $smarty) //($name, &$data,  = '')
  {
    $name = is($params['name']    );
    return $this->showlabel($name, "<a href='{$this->guidata[$name]}' target='blank'>{$this->guidata[$name]}</a>",$params['nolabel'] ,$params);
  }

  function selection ($params, $smarty) //($name, &$data, &$err, $opt)
  {
    $name = is($params['name']);
    $style = is($params['style']);
    $opt  = is($params['options']);
    $prefix = is($params['prefix']);
    $mult =   is($params['multiselect']);
    $size =   is($params['size']);
    $con  =   is($params['con']);
    $class  =   is($params['class']);
    $nokey =  is($params['nokey'], false);
    $nolabel = is($params['nolabel'], false);
    $mult = ($mult)?"multiple":'';
    $mult .= ($size)?" size='$size'":'';
    $value = is($params['value'], $this->guidata[$name]);
    if ($style) $style = "style='{$style}' ";
    if ($class) $class = "class='{$class}' ";
    If (!is_array($opt)) {
      $opt  = explode('|',$opt);
    }
//    print_r($opt);
    // $val=array('both','rows','none');
    $sel[$value] = " selected ";

    $return = "<select {$class}{$style} id='$name' name='$name' $mult value=$value>\n";

    foreach($opt as $v => $n) {
        if (is_array($n)) {
          list($v, $n) = $n;
        } elseif (strpos($n, '~')!==false) {
          list($v, $n) = explode('~',$n);
        } elseif($nokey) {
          $v = $n;
        }
        $cap = ($prefix or $con)?con($prefix.$n):$n;
        $return .= "<option value='". htmlspecialchars($v)."' {$sel[$v]}>" .  htmlspecialchars($cap) . "</option>\n";
    }

    return $this->showlabel($name, $return. "</select>", $params);
  }

  protected function loadCountrys() {
    global $_SHOP,  $_COUNTRY_LIST;
    if (!isset($_COUNTRY_LIST)) {
      If (file_exists(INC."lang".DS."countries_". $_SHOP->lang.".inc")){
        include_once(INC."lang".DS."countries_". $_SHOP->lang.".inc");
      }else {
        include_once(INC."lang".DS."countries_en.inc");
      }
    }
  }
  function getCountry($name){
    global $_SHOP, $_COUNTRY_LIST;
    self::Loadcountrys();
    return $_COUNTRY_LIST[$name];
  }


  function getCountryName($params){
    global $_SHOP, $_COUNTRY_LIST;
    $name     = is($params['name']);
    self::Loadcountrys();
    return $_COUNTRY_LIST[$name];
  }
  function viewCountry($params, $smarty){
    global $_SHOP, $_COUNTRY_LIST;
    $this->Loadcountrys();
    if (!isset($params['value'])){
      $name     = is($params['name']);
      $val=strtoupper($this->guidata[$name]);
    } else {
      $val=strtoupper($params['value']);
    }
    $params['value'] = $_COUNTRY_LIST[$val];
    return $this->view($params,$smarty);
  }

  function selectCountry($params, $smarty) { //($sel_name, $selected, &$err){
    global $_SHOP,  $_COUNTRY_LIST;
    $this->Loadcountrys();
    if (isset($params['DefaultEmpty'])) {
      $params['options'] = array_merge(array(''=>'['.con('select_country').']'), $_COUNTRY_LIST);
    }else{
      $params['options'] = $_COUNTRY_LIST;
    }
    return $this->selection($params, $smarty);
  }

  protected function loadStates() {
    global $_SHOP,  $_STATE_LIST;
    if (!isset($_STATE_LIST)) {
      If (file_exists(INC."lang".DS."states_". $_SHOP->lang.".inc")){
        include_once(INC."lang".DS."states_". $_SHOP->lang.".inc");
      }
    }
  }

  function viewState($params, $smarty){
    global $_SHOP, $_STATE_LIST;
    $this->LoadStates();
    $name     = is($params['name']);
    if (isset($_STATE_LIST)) {
      $val=strtoupper($this->guidata[$name]);
      $params['value'] = $_STATE_LIST[$val];
    } else {
      $params['value'] = $this->guidata[$name];
    }

    return $this->view($params, $smarty);
  }


  function selectState($params, $smarty) { //($sel_name, $selected, &$err){
    global $_SHOP,  $_STATE_LIST;
    $this->LoadStates();
    if (isset($_STATE_LIST)) {
      $params['options'] = $_STATE_LIST;
      return $this->selection($params, $smarty);
    } else {
      return $this->input($params, $smarty);
    }
  }

  function selectColor ($params, $smarty) //($name, &$data, &$err)
  {
    $name = is($params['name']);

    $return = "<select name='$name'>\n";

    $act = $this->guidata[$name];

    for($r = 16;$r < 256;$r += 64) {
        for($g = 16;$g < 256;$g += 64) {
            for($b = 16;$b < 256;$b += 64) {
                $color = '#' . dechex($r) . dechex($g) . dechex($b);
                if ($act == $color) {
                    $return .= "<option value='$color'style='color:$color;' selected>$color</option>\n";
                } else {
                    $return .= "<option value='$color'style='color:$color;'>$color</option>\n";
                }
            }
        }
    }

    return $this->showlabel($name, $return."</select>",$params);
  }

  function viewFile ($params, $smarty) //($name, &$data, &$err, $type = 'img',  = '')
  {
    $name = is($params['name']);
    $type = is($params['type'],'img');

    if ($this->guidata[$name]) {
      $src = $this->user_file($this->guidata[$name]);
      if ($type == 'img') {
        // NVDS: there must be some size checking here.
        $return = "<img  src='$src'>";
      } else {
        $return = "<a class=link href='$src'>{$this->guidata[$name]}</a>";
      }
      return $this->showlabel($name, $return, $params);
    }
  }

  function inputFile ($params, $smarty) //($name, &$data, &$err, $type = 'img',  = '')
  {
    $name = is($params['name']);
    $type = is($params['type'],'img');

    if (!$this->guidata[$name]) {
        return $this->showlabel($name, "<input type='file' name='$name'>",$params);
    } else {
      $src = $this->user_file($this->guidata[$name]);

      if ($type == 'img') {
         list($width, $height, $type, $attr) = getimagesize(ROOT.'files'.DS.$this->guidata[$name]);

         if (($width>$height) and ($width > 300)) {
           $attr = "width='300'";
         } elseif ($height > 250) {
           $attr = "height='250'";
         }

         $return = "<img $attr src='$src'>";
      } else {
          $return = "<a href='$src'>{$this->guidata[$name]}</a>";
      }
      return $this->showlabel($name, $return . "<br><br><input type='file' size=35 name='$name'>".
                       "<input type='checkbox'  name='remove_$name' value='1'>" . con("remove_image")."<br>",$params);
    }
  }

  function Navigation($params, $smarty) { //($offset, $matches, $url, $stepsize=10)
    $name     = is($params['name'],'offset');
    $offset   = is($params['offset'],0);
    $matches  = is($params['count'],0);
    $stepsize = is($params['length'],10);
    $maxpages = is($params['maxpages'],10);
    $params['action'] = is($params['action'],$this->action);
   // If ($matches<=$stepsize ) {return "";}

    //TODO: Should this be using a new pagnation method?
    $url     = $this->currenturl( $params, $smarty, array('name',$name,'maxpages','count','length','action'));

    $breaker = ( strpos($url,'?')===false)?'?':'&';
    $output = '';
    if ($offset<0) {$offset=0;}
    if ($offset !=0){
      $output .= "<a href='".$url.$breaker.$name."= 0'>".con('nav_first')."</a>&nbsp;";
      $output .= "<a href='".$url.$breaker.$name."=".($offset-$stepsize)."'>".con('nav_prev')."</a>&nbsp;";
    } else {
      $output .= con('nav_first')."&nbsp;";
      $output .= con('nav_prev')."&nbsp;";
      }


    $offpages=intval($offset/$stepsize);
    if ($offset%$stepsize) {$offpages++;}

    $pages=intval($matches/$stepsize);
    if ($matches%$stepsize) {$pages++;}
    $start = 1;
    if ($offpages >= intval($maxpages/2)){
         $start = $offpages - intval($maxpages/2);
         If ($start < 2) $start =2;
         //if ($start >= $pages-$maxpages) $start = $pages-$maxpages;
         $output .= '...&nbsp;';
         }
    for ($i=$start;$i<=$pages;$i++) {
      if (($i-$start == $maxpages) and ($i<$pages)) {
             $output .= '...&nbsp;';
             break;
             }
      if ($offpages+1 == $i){
             $output .= "<b>[$i]</b>&nbsp;";
      } else {
             $output .= "<a href='".$url.$breaker.$name."=".($stepsize*($i-1))."'>".$i."</a>&nbsp;";
             }
         }
    if (!($offset+$stepsize >= $matches)) {
         $output .= "<a href='".$url.$breaker.$name."=".($offset+$stepsize)."'>".con('nav_next')."</a>&nbsp;";
         $output .= "<a href='".$url.$breaker.$name."=".($matches-$stepsize)."'>".con('nav_last')."</a>&nbsp;";
         }
    else
     {
     $output .= con('nav_next')."\n";
     $output .= con('nav_last')."\n";
     }
    return '<center>'. $output.'</center>';
  }


  function tabBar($params , $smarty) {
    require_once('admin'.DS.'class.adminview.php');

    $TabBarid = is($params['TabBarid'],'TabBarid');
    $menuAlign = is($params['menuAlign'],'left');
    $menu  = is($params['menu']);

    if (isset($_REQUEST['tab'])) {
      $_SESSION[$TabBarid] = (int)$_REQUEST['tab'];
    } elseif (!isset($_SESSION[$TabBarid])) {
      $_SESSION[$TabBarid] = 0;
    }

    If (!is_array($menu)) {
      $opt  = explode('|',$menu);
      $a =0;
      $menu = array();
      foreach ($opt as $key) {
        $val  = explode('~',$key);
        $menu[$val[0]] = (int) is($val[1],$a); //"?tab=".
        $a++;
      }
    }
    if (isset($_REQUEST['tab'])) {
      $_SESSION[$TabBarid] = (int)$_REQUEST['tab'];
    } elseif (!isset($_SESSION[$TabBarid])) {
      $_SESSION[$TabBarid] = (int)reset($menu);
    }
  //  var_dump($_SESSION);
    $smarty->assign($TabBarid, $_SESSION[$TabBarid]);
    return  AdminView::PrintTabMenu($menu, $_SESSION[$TabBarid], $menuAlign);
  }

  function captcha($params, $smarty) //($name)
  {
    //print_r($smarty);
    $name = is($params['name']);
    return $this->showlabel(con('captcha'),
           "  <table cellpadding='0' cellspacing='0' width='350'>\n".
           "  <tr><td valign='top'>\n".
           "     <input type='hidden' name='_~nospam~_' value='".base64_encode($name)."' >\n".
           "     <input type='text' name='user_nospam' size='10' maxlength='10' value='' ><br>\n".
           "     <sup>".con('captcha_info')."</sup>\n".
           "  </td><td align='right'>\n".
           "     <img src='".makeURL('Captcha/'.$name)."' alt=''  border=1>\n".
           "  </td></tr>\n".
           "</table>\n",$params);
  }

  function delayedLocation($params, $smarty) { //($url){
      $url = $this->view->_URL($params);
      return "<SCRIPT LANGUAGE='JavaScript'>
            <!-- Begin
                 function runLocation() {
                   location.href='{$url}';
                 }
                 window.setTimeout('runLocation()', 1500);
            // End -->\n</SCRIPT>\n";
  }

  function valuta ($params, $smarty){
    global $_SHOP;

    $valuta = valuta($params['value'], $params['code']);

    if(!empty($params['assign'])){
      $smarty->assign($params['assign'],$valuta);
    }else{
      return $valuta;
    }
  }

  function print_set ($params, $smarty) //($name, &$data, $table_name, $column_name, $key_name, $file_name)
  {
      $ids = explode(",", $this->guidata);
      $set = array();
      if (!empty($ids) and $ids[0] != "") {
          foreach($ids as $id) {
              $query = "select $column_name as id from $table_name where $key_name='$id'";
              if (!$row = ShopDB::query_one_row($query)) {
                  // user_error(shopDB::error());
                  return 0;
              }
              $row["id"] = $id;
              array_push($set, $row);
          }
      }
      return "<tr><td class='gui_name'>" . con($name) . "</td>
  <td class='gui_value'>";
      if (!empty($set)) {
          foreach ($set as $value) {
              return "<a class='link' href='$file_name?action=view&$key_name={$value['id']}'>" . $value[$column_name] . "</a><br>";
          }
      }
      return "</td></tr>\n";
  }

  protected function user_url($data){
      global $_SHOP;
      return $_SHOP->root . $data;
  }

  protected function user_file ($path) {
      return ROOT. 'files'. DS . $path;
  }
}


function smarty_modifier_clean($string, $type='ALL') {
  return clean($string, $type);
}


  function weeksofyear($year){
    $result = idate("W",mktime(0,0,0,12,28, $year)); //idate('W', strtotime("31 dec ".is($params,$_SESSION['settings']['jaar'])));
    return $result;
  }

?>