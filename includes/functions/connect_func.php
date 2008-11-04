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

function url_post ($url,&$data){
  global $_SHOP;

	switch($_SHOP->url_post_method) { 
	
		case "libCurl": //php compiled with libCurl support
		
			$result=libCurlPost($url,$data); 
			break;
		
		
		case "curl": //cURL via command line
		
			$result=curlPost($url,$data); 
			break; 
		
		
		case "fso": //php fsockopen(); 
		default: //use the fsockopen method as default post method
		
			$result=fsockPost($url,$data); 
			break; 
	}
	
	return $result;
}

//post transaction data using curl

function curlPost($url,&$data)  {

	global $_SHOP; 
	
	//build post string 
	
	foreach($data as $i=>$v) { 
		$postdata.= $i . "=" . urlencode($v) . "&"; 
	}
	
	
	//execute curl on the command line
	
	exec("{$_SHOP->url_post_curl_location} -d \"$postdata\" $url", $info);
	
	$info=implode("\n",$info); 
	
	return $info; 

}

//posts transaction data using libCurl 

function libCurlPost($url,&$data)  {

	//build post string 
	
	foreach($data as $i=>$v) { 
		$postdata.= $i . "=" . urlencode($v) . "&"; 	
	}
	
	
	$ch=curl_init(); 
	
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_URL,$url); 
	curl_setopt($ch,CURLOPT_POST,1); 
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata); 
	
	//Start ob to prevent curl_exec from displaying stuff. 
	ob_start(); 
	curl_exec($ch);
	
	//Get contents of output buffer 
	$info=ob_get_contents(); 
	curl_close($ch);
	
	//End ob and erase contents.  
	ob_end_clean(); 
	
	return $info; 

}

//posts transaction data using fsockopen. 
function fsockPost($url,&$data) { 
	
	//Parse url 
	$web=parse_url($url); 
	
	//build post string 
	foreach($data as $i=>$v) { 
		$postdata.= $i . "=" . urlencode($v) . "&"; 
	}
		
	//Set the port number
	if($web[scheme] == "https") { $web[port]="443";  $ssl="ssl://"; } else { $web[port]="80"; }  
	
	//Create connection
	$fp=@fsockopen($ssl . $web[host],$web[port],$errnum,$errstr,30); 
	
	//Error checking
	if(!$fp) { echo "$errnum: $errstr"; } 
	 
	//Post Data
	else { 
	 
		fputs($fp, "POST $web[path] HTTP/1.1\r\n"); 
		fputs($fp, "Host: $web[host]\r\n"); 
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
		fputs($fp, "Content-length: ".strlen($postdata)."\r\n"); 
		fputs($fp, "Connection: close\r\n\r\n"); 
		fputs($fp, $postdata . "\r\n\r\n"); 
	
		//loop through the response from the server 
		while(!feof($fp)) { $info.=@fgets($fp, 1024); } 
		
		//close fp - we are done with it 
		fclose($fp); 
		
		//break up results into a string
		//$info=implode(",",$info); 
	
	}
	
	return $info; 

}

?>