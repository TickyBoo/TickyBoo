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
function generate_barcode ($type,$value){
  switch($type){
  
    case "code39":
      return "*".$value."*";
      break;
    case "code128A":
      return code128A($value);
      break;   
    case "code128B":
      return code128A($value);
      break;   

    default:
      return $value;
  }
  break;
}
/**
code128A encoding, for BarFonts: each char c in result is c+32 wrt Code128 spec
barfonts: http://user.it.uu.se/~jan/barfonts/
*/
function code128A ($input){

  /*
    spec: http://www.adams1.com/pub/russadam/128code.html

    on va travailler avec l'input ASCII tout le temps 

    1. prepend  STARTA+32
    2. calculate the checksum C and append C+32
    3. append STOP+32
    4. return it
  */  

  $STARTA=103;
  $STOP=106;

  $chk=$STARTA;
  $size=strlen($input);

  for($i=0;$i<$size;$i++){
    $chk+=($i+1)*(ord($input{$i})-32);
  }
  $chk=($chk%103);
  
  if($chk+32>=127){$chk+=97;}else{$chk+=32;}

  return chr($STARTA+97).$input.chr($chk).chr($STOP+97);
}

/**
code128B encoding, for BarFonts: each char c in result is c+32 wrt Code128 spec
barfonts: http://user.it.uu.se/~jan/barfonts/
*/
function code128B ($input){

  /*
    spec: http://www.adams1.com/pub/russadam/128code.html

    on va travailler avec l'input ASCII tout le temps 

    1. prepend  STARTB+32
    2. calculate the checksum C and append C+32
    3. append STOP+32
    4. return it
  */  

  $STARTB=104;
  $STOP=106;

  $chk=$STARTB;
  $size=strlen($input);

  for($i=0;$i<$size;$i++){
    $chk+=($i+1)*(ord($input{$i})-32);
  }
  $chk=($chk%103);

  if($chk+32>=127){$chk+=97;}else{$chk+=32;}

  return chr($STARTB+97).$input.chr($chk).chr($STOP+97);
}
?>