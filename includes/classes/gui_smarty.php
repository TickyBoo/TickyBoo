<?php

/**
 * @author Chris Jenkins
 * @copyright 2008
 */

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
	var $errors     = array();
  public $guidata = array();

  function __construct  (&$smarty){

    $smarty->register_object("gui",$this);
    $smarty->assign_by_ref("gui",$this);

    $smarty->register_function('ShowFormToken', array($this,'showFormToken'));
    $smarty->register_function('valuta', array($this,'valuta'));
    $smarty->register_function('print_r', array($this,'print_r'));
    $smarty->register_modifier('clean', 'smarty_modifier_clean');

  }

  function url($params, $smarty, $skipnames)
  {
    GLOBAL $_SHOP;
    If (isset($params['url'])) {
      return $_SHOP->root.$params['url'];
    } else {
      If (!is_array($skipnames)) {$skipnames= array();}
    //  print_r($params);
      $urlparams ='';
      foreach ($params as $key => $value) {
        if (!in_array($key,array('action','controller','module')) and
            !in_array($key,$skipnames)) {
          $urlparams .= (($urlparams)?'&':'').$key.'='.$value;
        }
      }
   //   $urlparams = substr($urlparams,1);
     // print_r($urlparams);
      return makeURL($params['action'], $urlparams, $params['controller'], $params['module']);
    }
  }

  function print_r ($params,&$smarty) {
    return nl2br(print_r($params['var'],true));
  }
  
  function fillarr ($params,&$smarty)
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


  function showFormToken ($params, &$smarty) {
    $name = is($params['name'],'FormToken');
    if (!isset($_SESSION['tokens'][$name])) {
      $_SESSION['tokens'][$name]['n'] = md5(mt_rand());
    }
    $_SESSION['tokens'][$name]['t'] = time();
    $token = $_SESSION['tokens'][$name]['n'];
    $name = $name.'_'.base_convert(mt_rand(), 10,36);
    return "<input type='hidden' name='___{$name}' value='".htmlspecialchars(sha1 ($name.'-'.$token.'-'.$_SERVER["REMOTE_ADDR"]))."'/>";
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
	
   function setdata($params, &$smarty) //($name, $width = 0, $colspan = 2)
  {
    If( isset($params['data'])) {
      $this->guidata = $params['data'];
    }
    If( isset($params['errors'])) {
      $this->errors = $params['errors'];
    }

  }
  function StartForm ($params, &$smarty) //($name, $width = 0, $colspan = 2)
  {
    $name     = is($params['name']);
    $title    = is($params['title']);
    $width    = is($params['width'],$this->width);
    $class    = is($params['class'],'gui_form');
    $enctype  = is($params['enctype'],'application/x-www-form-urlencoded');
    $method   = is($params['method'],'post');
    $onsubmit = is($params['onsubmit'],'');
    $url      = is($params['action']);//$this->_URL( $params, $smarty, array('name','class','width','method','title','enctype','onsubmit', 'data' ));
    If( isset($params['data'])) {
      $this->guidata = $params['data'];
    }
    $return = "<table class='$class' width='$width' border=0 cellspacing='1' cellpadding='4'>\n";
    if ($title) {
      $return .= "<tr><th class='$class_title' colspan='2'>$title</th></tr>\n";
    }
    If ($method <> 'node') {
      $return .= "<form action='$url' name='$name' method='post' enctype='$enctype'";
      $return .= ($onsubmit)?" onsubmit ='$onsubmit'>":">";
      $return .= self::showFormToken( $params, $smarty);
      $this->FormDepth ++;
      $this->_ShowLabel = True;
    }
    return $return;
  }

  function EndForm($params, &$smarty) //($colspan = 2)
  {
    $name     = is($params['name'],'submit');
    $align    = is($params['align'],'center');
    $title    = is($params['title'], con('gui_save','submit'));
    $class    = is($params['class'],'gui_value');
    $noreset  = is($params['noreset'], false);
    $onclick  = is($params['onclick'],'');
    $return = "<tr><td align='$align' class='$class' colspan='2'>\n".
              "<input type='submit' name='$name' value='" . $title . "'>";
    if (!$noreset) {
      $return .= "&nbsp; <input type='reset' name='reset' value='" . con('gui_reset','reset') . "'>\n";
    }
    $return .= "</td></tr>\n";
    if ($this->FormDepth) {
      $return .= "</form>\n";
      $this->FormDepth --;
    }
    return $return. "</table>\n";
  }

  function SetShowLabel($params, &$smarty) {
    $this->_ShowLabel =is($params['set'],$this->_ShowLabel);
  }

  private function showlabel($name, $value, $nolabel=false) {
    if ($this->_ShowLabel and !$nolabel) {
      $return = "<tr><td class='{$this->gui_name}' width='30%'>" . con($name) . "</td>".
                "    <td class='{$this->gui_value}'>{$value}";
      if (isset($this->errors[$name])) {
        $return .= "<span class='error'>{$this->errors[$name]}</span>";
      }
      return $return."</td></tr>\n";
    } else {
      return $value;
    }

  }

  function view ($params, &$smarty) //$name, &$data, $prefix = ''*/)
  {
    $name = is($params['name']);
    $Option = is($params['option']);
    $value  = is($params['value'],$this->guidata[$name]);
    $nolabel  = is($params['nolabel'],false);
    If (!$Option or $this->values[$name]) {
      return $this->showlabel($name, $value, $nolabel);
    }
  }
  function hidden ($params, &$smarty) //$name, &$data, $size = 30, $max = 100)
  {
    $name = is($params['name'] );
    $value  = is($params['value'],$this->guidata[$name]);
    return "<input type='hidden' name='$name' value='" . htmlspecialchars($value, ENT_QUOTES) ."'>\n";
  }

  function input ($params, &$smarty) //$name, &$data, $size = 30, $max = 100)
  {
    $name = is($params['name'] );
    $type = is($params['type'], 'text');
    $size = is($params['size'], 30);
    $max  = is($params['maxlength'] ,100);
    $value  = is($params['value'],$this->guidata[$name]);

    return $this->showlabel($name, "<input type='$text' name='$name' value='" . htmlspecialchars($value, ENT_QUOTES) .
           "' size='$size' maxlength='$max'>");
  }

  function checkbox ($params, &$smarty) //($name, &$data, &$err, $size = '', $max = '')
  {
    $name = is($params['name']    );
    if ($this->guidata[$name]) {
      $chk = 'checked';
    }
    return $this->showlabel($name, "<input type='checkbox' name='$name' value='1' $chk>");
  }

  function area ($params, &$smarty) //($name, &$data, &$err, $rows = 6, $cols = 40,  = '')
  {
    $name = is($params['name']   );
    $rows = is($params['rows'], 6);
    $cols = is($params['cols'],40);
    return $this->showlabel($name, "&nbsp;</td></tr><tr><td colspan=2><textarea rows='$rows' cols='$cols' name='$name'>" . htmlspecialchars($this->guidata[$name], ENT_QUOTES) . "</textarea>");
  }

  function inputtime ($params, &$smarty) //($name, &$data, &$err,  = '')
  {
    $name = is($params['name']    );
    $timeselect = new DateTimeSelect('t', $name, $this->guidata[$name],0);
    return $this->showlabel($name, $timeselect->selectbox);
  }

  function inputdate ($params, &$smarty) //($name, &$data, &$err,  = '')
  {
    $name = is($params['name']    );
    $type = is($params['type'],'d' );
    $range = is($params['range'],5    );
    $timeselect = new DateTimeSelect($type, $name, $this->guidata[$name],$range);
    return $this->showlabel($name, $timeselect->selectbox);
  }

  function viewurl ($params, &$smarty) //($name, &$data,  = '')
  {
    $name = is($params['name']    );
    return $this->showlabel($name, "<a href='{$this->guidata[$name]}' target='blank'>{$this->guidata[$name]}</a>",$params['nolabel']);
  }

  function selection ($params, &$smarty) //($name, &$data, &$err, $opt)
  {
    $name = is($params['name']);
    $opt  = is($params['options']);
    $prefix = is($params['prefix']);
    $mult =   is($params['multiselect']);
    $con  =   is($params['con']);
    $nokey =  is($params['nokey'], false);
    $nolabel = is($params['nolabel'], false);
    $mult = ($mult)?'multiple':'';

    If (!is_array($opt)) {
      $opt  = explode('|',$opt);
    }
//    print_r($opt);
    // $val=array('both','rows','none');
    $sel[$this->guidata[$name]] = " selected ";

    $return = "<select name='$name' $mult>\n";

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

    return $this->showlabel($name, $return. "</select>", $nolabel);
  }

  protected function Loadcountrys() {
    global $_SHOP,  $_COUNTRY_LIST;
    if (!isset($_COUNTRY_LIST)) {
      If (file_exists(INC."lang".DS."countries_". $_SHOP->lang.".inc")){
        include_once(INC."lang".DS."countries_". $_SHOP->lang.".inc");
      }else {
        include_once(INC."lang".DS."countries_en.inc");
      }
    }
  }
  function GetCountry($name){
    global $_SHOP, $_COUNTRY_LIST;
    self::Loadcountrys();
    return $_COUNTRY_LIST[$val];
  }

  function viewcountry($params, &$smarty){
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

  function selectcountry($params, &$smarty) { //($sel_name, $selected, &$err){
    global $_SHOP,  $_COUNTRY_LIST;
    $this->Loadcountrys();
    $params['options'] = $_COUNTRY_LIST;
    return $this->selection($params, $smarty);
  }

  protected function LoadStates() {
    global $_SHOP,  $_STATE_LIST;
    if (!isset($_STATE_LIST)) {
      If (file_exists(INC."lang".DS."states_". $_SHOP->lang.".inc")){
        include_once(INC."lang".DS."states_". $_SHOP->lang.".inc");
      }
    }
  }
  
  function viewState($params, &$smarty){
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


  function selectState($params, &$smarty) { //($sel_name, $selected, &$err){
    global $_SHOP,  $_STATE_LIST;
    $this->LoadStates();
    if (isset($_STATE_LIST)) {
      $params['options'] = $_STATE_LIST;
      return $this->selection($params, $smarty);
    } else {
      return $this->input($params, $smarty);
    }
  }

  function selectcolor ($params, &$smarty) //($name, &$data, &$err)
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

    return $this->showlabel($name, $return."</select>");
  }

  function viewfile ($params, &$smarty) //($name, &$data, &$err, $type = 'img',  = '')
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
      return $this->showlabel($name, $return, $params['nolabel']);
    }
  }

  function inputfile ($params, &$smarty) //($name, &$data, &$err, $type = 'img',  = '')
  {
    $name = is($params['name']);
    $type = is($params['type'],'img');

    if (!$this->guidata[$name]) {
        return $this->showlabel($name, "<input type='file' name='$name'>");
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
                       "<input type='checkbox'  name='remove_$name' value='1'>" . con("remove_image")."<br>");
    }
  }
function Navigation($params, &$smarty) //($offset, $matches, $url, $stepsize=10)
  {
    $name     = is($params['name'],'offset');
    $offset   = is($params['offset'],0);
    $matches  = is($params['count'],0);
    $stepsize = is($params['length'],10);
    $maxpages = is($params['maxpages'],10);
    $params['action'] = is($params['action'],$this->action);
   // If ($matches<=$stepsize ) {return "";}

    $url     = $this->url( $params, $smarty, array('name',$name,'maxpages','count','length'));

    $breaker = ( strpos($url,'?')===false)?'?':'&';
    $output = '';
    if ($offset<0) {$offset=0;}
    if ($offset !=0)
      {
      $output .= "<a href='".$url.$breaker.$name."= 0'>".con('nav_first')."</a>&nbsp;";
      $output .= "<a href='".$url.$breaker.$name."=".($offset-$stepsize)."'>".con('nav_prev')."</a>&nbsp;";
      }
    else
      {
      $output .= con('nav_first')."&nbsp;";
      $output .= con('nav_prev')."&nbsp;";
      }

    $offpages=intval($offset/$stepsize);
    if ($offset%$stepsize) {$offpages++;}

    $pages=intval($matches/$stepsize);
    if ($matches%$stepsize) {$pages++;}
    $start = 1;
    if ($offpages >= intval($maxpages/2))
         {
         $start = $offpages - intval($maxpages/2);
         If ($start < 2) $start =2;
         //if ($start >= $pages-$maxpages) $start = $pages-$maxpages;
         $output .= '...&nbsp;';
         }
    for ($i=$start;$i<=$pages;$i++)
         {
         if (($i-$start == $maxpages) and ($i<$pages))
             {
             $output .= '...&nbsp;';
             break;
             }
         if ($offpages+1 == $i)
             {
             $output .= "<b>$i</b>&nbsp;";
             }
         else
             {
             $output .= "<a href='".$url.$breaker.$name."=".($stepsize*($i-1))."'>".$i."</a>&nbsp;";
             }
         }
    if (!($offset+$stepsize >= $matches))
         {
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

  function captcha($params, &$smarty) //($name)
  {
    //print_r($smarty);
    $name = is($params['name']);
    return $this->showlabel(con('captcha'),
           "  <table cellpadding='0' cellspacing='0' width='100%'>\n".
           "  <tr><td valign='top'>\n".
           "     <input type='hidden' name='__nospam__' value='".base64_encode($name)."' >\n".
           "     <input type='text' name='user_nospam' size='10' maxlength='10' value='' ><br>\n".
           "     <sup>".con('captcha_info')."</sup>\n".
           "  </td><td align='right'>\n".
           "     <img src='".makeURL('Captcha/'.$name)."' alt=''  border=1>\n".
           "  </td></tr>\n".
           "</table>\n");
    }
    
  function delayedLocation($params, &$smarty) { //($url){
      $url = $this->view->_URL($params);
      return "<SCRIPT LANGUAGE='JavaScript'>
            <!-- Begin
                 function runLocation() {
                   location.href='{$url}';
                 }
                 window.setTimeout('runLocation()', 1500);
            // End -->\n</SCRIPT>\n";
      }
  function valuta ($params,&$smarty)
  {
    global $_SHOP;

    if (isset($params['code'])) {
      $valuta = $params['code'];
    }
    else {
      $valuta = $_SHOP->organizer_data->organizer_currency;
    }

    $valuta = (isset($this->valutas[$valuta]))?$this->valutas[$valuta]:$valuta;
    $valuta = (!empty($params['value']))?$valuta.' '.$params['value']:$params['value'].' '.$valuta;

    if(!empty($params['assign'])){
      $smarty->assign($params['assign'],$valuta);
    }else{
      return $valuta;
    }
  }

  function print_set ($params, &$smarty) //($name, &$data, $table_name, $column_name, $key_name, $file_name)
  {
      $ids = explode(",", $this->guidata);
      $set = array();
      if (!empty($ids) and $ids[0] != "") {
          foreach($ids as $id) {
              $query = "select $column_name from $table_name where $key_name='$id'";
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
              return "<a class='link' href='$file_name?action=view&$key_name=" . $value["id"] . "'>" . $value[$column_name] . "</a><br>";
          }
      }
      return "</td></tr>\n";
  }

  protected function user_url($data)
  {
      global $_SHOP;
      return $_SHOP->root . $data;
  }

  protected function user_file ($path)
  {
      return ROOT. 'files'. DS . $path;
  }

/* this does not belong here anymore where to put this then?
  function file_post ($params, &$smarty) //($data, $id, $table, $name,  = '_image')
  {
      global $_SHOP;
      $name = is($params['name']);
      $suffix = is($params['name'], '_image');

      $img_field = $name.$suffix ;
      $id_field  = $name . '_id';

      if ($this->guidata['remove_' . $name .$suffix]==1) {
          $query = "UPDATE $table SET $img_field='' WHERE $id_field='$id'";
//            unlink( $_SHOP->files_dir . "/" .$this->guidata['remove_' . $name ]);

      } else
      if (!empty($_FILES[$img_field]) and !empty($_FILES[$img_field]['name']) and !empty($_FILES[$img_field]['tmp_name'])) {
          if (!preg_match('/\.(\w+)$/', $_FILES[$img_field]['name'], $ext)) {
              return false;
          }

          $ext = strtolower($ext[1]);
          if (!in_array($ext, $_SHOP->allowed_uploads)) {
              return false;
          }

          $doc_name = $img_field . '_' . $id . '.' . $ext;

          if (!move_uploaded_file ($_FILES[$img_field]['tmp_name'], $_SHOP->files_dir . "/" . $doc_name)) {
              return false;
          }

          chmod($_SHOP->files_dir . "/" . $doc_name, $_SHOP->file_mode);
          $query = "UPDATE $table SET $img_field='$doc_name' WHERE $id_field='$id'";
      }

      if (!$query or ShopDB::query($query)) {
         return true;
      }
      return false;
  }
*/

}


function smarty_modifier_clean($string, $type='ALL') {
  return clean($string, $type);
}

?>