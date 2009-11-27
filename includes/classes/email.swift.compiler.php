<?PHP
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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

if (!defined('ft_check')) {die('System intrusion ');}
class EmailSwiftCompiler {

  var $mode=0; //0 normal 1 text
  
  var $vars=array(); //variables are collected for informative purposes
  var $args='data'; //name of the parameter array where variables are stored
  
  var $errors=array();
  
  private $varsBuilt = false;
  
  //Email Vars.
  private $emailArray = null;
  private $emailTo = null; //$email => $firstname $lastname 
  private $emailCC = null; //array($email=>$name,$email2)
  private $emailBCC = null;
  private $emailFrom = null; //$email => $firstname $lastname
  private $emailDefLang = 0;
  private $emailLangs = null;
  private $emailTemplates = null;

  function EmailSwiftCompiler (){
  }
  
  function build ($swiftInstance, $data, $lang=0, $testme=false){
    $emailArray = $this->sourcetext;
    $this->data = $data;
    //Build vars
    $this->buildVars();
    $this->emailVarsToValues();
    require_once (LIBS.'swift'.DS.'swift_required.php');
    if(!is_object($swiftInstance)){
      $swiftInstance = Swift_Message::newInstance();
    }
    $swift = &$swiftInstance;
    
    $swift->setFrom();
    
    
    return $swift;  
  }
  
  private function buildVars($emailArray){
    global $_SHOP;
    if(is_string($emailArray)){
      $emailArray = unserialize($emailArray);
    }
    $this->emailArray = &$emailArray;
    //To
    $this->emailTo = array(
      is($emailArray['email_to_email'],'$user_email')=>
      is($emailArray['email_to_name'],'$user_firstname $user_lastname'));
    //From
    $this->emailFrom = array(
      is($emailArray['email_from_email'],$_SHOP->organizer_data->organizer_email)=>
      is($emailArray['email_from_name'],''));
    //CC
    foreach($emailArray['emails_cc'] as $email=>$name){
      if(trim($email)<>'' && trim($name)<>''){
        $this->emailCC[$email]=$name;
      }elseif(trim($email)<>''){
        $this->emailCC[]=$email;
      }
    } unset($email,$name);
    //BCC
    foreach($emailArray['emails_bcc'] as $email=>$name){
      if(trim($email)<>'' && trim($name)<>''){
        $this->emailBCC[$email]=$name;
      }elseif(trim($email)<>''){
        $this->emailBCC[]=$email;
      }
    }
    //Default Lang
    $this->emailDefLang = is($emailArray['email_def_lang'],0);
    if(trim($this->emailDefLang)==''){
      $this->emailDefLang = 0;
    }
    //check templates
    if(is($emailArray['email_templates'],false)){
      foreach($emailArray['email_templates'] as $lang=>$fields){
        $this->emailLangs[] = $lang;
        $this->emailTemplates[$lang]=$fields;
      }
    }
    $this->varsBuilt = true;
  }
  
  public function compile ($emailArray, $newClassName){
    $this->buildVars($emailArray);
    $ret=
    '/*this is a generated file. do not edit!
    produced '.date("l dS of F Y h:i:s A").'  
    */    
    require_once("classes/email.swift.compiler.php");
    
    class '.$newClassName.' extends EmailSwiftCompiler {
      function write($swiftInstance, $data, $lang=0, $testAddress=""){
        $this->build($swiftInstance, $data, $lang, $testAddress);
      }
    }';
    //  echo "<pre>$ret</pre>";
    //return $ret;
    return false;
  }
  
  private function emailVarsToValues(){
    array_walk($this->emailTo,array(&$this,'recVarToVals'));
    array_walk($this->emailFrom,array(&$this,'recVarToVals'));
    array_walk($this->emailCC,array(&$this,'recVarToVals'));
    array_walk($this->emailBCC,array(&$this,'recVarToVals'));
  }
  
  public function test($emailArray){
    
  }
  
  public function generate($emailArray){
    $this->buildVars($emailArray);
    require_once (LIBS.'swift'.DS.'swift_required.php');
    
  }
  
  private function varToArg ($val){
    return; //"\"".$this->replace_vars(str_replace('"','\"',$val),1)."\"";
  }
  
  
  private function recVarToVals(&$value,&$key){
    $value = $this->varsToValues($value);
    $key = $this->varsToValues($key);
  }
  
  /**
   * EmailSwiftCompiler::varsToValues()
   * 
   * Takes a string with $varibles and replaces the var with the $data['varible'] value
   *  
   * @return String with $varbles converted to values.
   */
  private function varsToValues($string){
    return preg_replace_callback('/\$(\w+)/',array(&$this,'replaceVar'),str_replace('"','\"',$string));
      
  }
  
  /**
   * EmailSwiftCompiler::replaceVar()
   * 
   * Will replace the matched string with the value from data. 
   * 
   * @param mixed $matches 
   * @return
   */
  private function replaceVar($matches){
    array_push($this->vars,$matches[1]);
    $value = is($this->data[$matches[1]],$matches[0]);
    return ''.$value.'';
  }
}
?>