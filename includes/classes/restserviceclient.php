<?php

require_once("xml2php.php");

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
		//work ok the URI we are calling
		$uri = $this->url . $this->getQueryString();

		//get the URI trapping errors
		$result = @file_get_contents($uri);

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
    global $_SHOP;
    
    $this->siteUrl = $_SHOP->root;
    $this->siteVersion = CURRENT_VERSION;
    
		$queryArray = array();

		foreach ($this->data as $var => $val) {
			$queryArray[] = $var . '=' . urlencode($val);
		}

		$queryString = implode('&', $queryArray);

		return '?' . $queryString;
	}
}
?>