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
require_once "classes/xml2php.php";

class EmailSwiftCompiler {

  var $res=array(); //result of execution, indexed by language

  var $mode=0; //0 normal 1 text

  var $stack=array(); // local stack for various purposes
  var $vars=array(); //variables are collected for informative purposes
  var $args='data'; //name of the parameter array where variables are stored
  var $langs = array();

  var $deflang=0;
  var $errors=array();
  
  //Email Vars.
  private $emailArray = array();
  private $emailTo = array(); //$email => $firstname $lastname 
  private $emailCC = array(); //array($email=>$name,$email2)
  private $emailBCC = array();
  private $emailFrom = array(); //$email => $firstname $lastname
  private $emailDefLang = '';
  private $emailLangs = array();
  private $emailTemplates = array();

  function EmailSwiftCompiler (){
  }
  
  private function buildVars($emailArray){
    if(is_string($emailArray)){
      $emailArray = unserialize($emailArray);
    }
    $this->emailArray = &$emailArray;
    $this->emailTo = array(is($emailArray['email_to_name'],''),is($emailArray['email_to_email'],$_SHOP->organizer_data->organizer_email));
    
  }
  
  public function test($emailArray){
    
  }
  
  public function generate($emailArray){
    $this->buildVars($emailArray);
    require_once LIBS.'swift'.DS.'swift_required.php';
    
  }

  public function compile ($emailArray, $newClassName){
    
    if($this->generate($emailArray)){
      $xyz =
      '/*this is a generated code. do not edit!
      produced '.date("C").'
      */
      
      class '.$newClassName.' {
        var $object_id;
        var $engine;
        var $langs = array('.$langs.');
        
        function '.$newClassName.'(){}
      
        '.$this->build.'
      }
      ';
      //    echo ($xyz);
        return $xyz;
    }else{
        return FALSE;
    }
  }
}
?>