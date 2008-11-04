<?php
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 */

function formatDate($edate){
   global $_SHOP;
   ereg ("([0-9]{4})-([0-9]{2})-([0-9]{2})", $edate, $regs);
   //$lang=$_SERVER["INTERFACE_LANG"];
   $lang=$_SHOP->lang;
   setlocale(LC_TIME, get_loc($lang)); 
   $pdate= strftime ("%a %e %b %Y", mktime (0,0,0, $regs[2], $regs[3], $regs[1]));
   return $pdate; 
 }

function formatAdminDate($edate,$year4=true){
   ereg ("([0-9]{4})-([0-9]{2})-([0-9]{2})", $edate, $regs);
   If ($year4) {
     $pdate=$regs[3]."-".$regs[2]."-".$regs[1];
   } else {
     $pdate=$regs[3]."-".$regs[2]."-".substr($regs[1], -2);
   }
   return $pdate; 
 }

function formatTime($time){
  list($h,$m,$s)=split(":",$time);

  if(strlen($h) or strlen($m)){
    //return strftime("%X",mktime($h,$m));
		return $h."h".$m;
  }
}

function get_loc($lang){
  switch($lang){
    case "de":
      return "de_DE";
      break;
    case "en":
      return "en";
      break;
    case "fr":
      return "fr_FR";
      break;
    case "it":
      return "it_IT";
      break;
      
  
  }
}
?>