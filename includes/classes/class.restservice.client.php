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

if (!defined('ft_check')) {die('System intrusion ');}
require_once("class.xml2php.php");

class RestServiceClient {

	private $url; //the URL we are pointing at

	private $data = array(); //data we are going to send
	private $response; //where we are going to save the response

	public function __construct($url) {
		$this->url = $url;
	}

	//get the URL we were made with
	public function getUrl() {
		return $this->url;
	}

	//add a variable to send
	public function __set($var, $val) {
		$this->data[$var] = $val;
	}

	//get a previously added variable
	public function __get($var) {
		return $this->data[$var];
	}

	public function excuteRequest() {
    global $_SHOP;
    $this->siteUrl = $_SHOP->root;
    $this->siteVersion = CURRENT_VERSION;
   
		//work ok the URI we are calling
		//$uri = $this->url . $this->getQueryString();
    $postData = $this->getPostData();
    
    //set timeout so that you wont be waiting forever if our server is under heavy load.
    $ctx = stream_context_create(array(
      'http' => array(
        'timeout' => 1,
        'method' => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postData
        )
      )
    );
    
		//get the URI trapping errors
		$result = @file_get_contents($uri,0,$ctx);

		// Retrieve HTTP status code
		list($httpVersion, $httpStatusCode, $httpMessage) = explode(' ', $http_response_header[0], 3);

		//if we didn't get a '200 OK' then thow an Exception
		if ($httpStatusCode != 200) {
			throw new Exception('HTTP/REST error: ' . $httpMessage, $httpStatusCode);
		} else {
			$this->response = $result;
		}
	}

	public function getResponse() {
		return $this->response;
	}

  public function getArray(){
    return Xml2php::xml2array($this->getResponse());
  }

  public static function example(){

    $rws = new RestServiceClient('http://localhost/ft/cpanel/versions/latest.xml');
    //$rws->query = 'Donnie Darko';
    //$rws->results = 8;
    //$rws->appid = 'YahooDemo';
    $rws->excuteRequest();
    $rws->getResponse();
    return $rws->getArray();

  }

	//turn our array of variables to send into a query string
	protected function getQueryString() {
		$queryArray = array();

		foreach ($this->data as $var => $val) {
			$queryArray[] = $var . '=' . urlencode($val);
		}

		$queryString = implode('&', $queryArray);

		return '?' . $queryString;
	}
  
  /**
   * @author Christopher Jenkins
   * used for posting data
   */
  protected function getPostData(){
    $queryArray = array();
    
    foreach ($this->data as $var => $val) {
      $queryArray[$var] = $val;
		}
    
    return http_build_query($queryArray);
  }
}
?>