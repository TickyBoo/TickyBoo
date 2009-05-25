<?php
class Payment {
	
  public $handling;
  public $extras    = array();
  public $mandatory = array();
  
	function __construct (&$handling) {
 		$this->handling = &$handling;
  }

  function __get($name) {
    if ($this->handling and ($result = $this->handling->$name)) {
      return $result;
    } else {
      return false;
    }
  }

	protected function __set($name, $value) {
		if ($this->handling) {
	  		return $this->handling->$name = $value;
		} else {
	  		return false;
		}
	}

	public function admin_view ( ){}

  	public function admin_form ( ){}
  	
  	public function admin_check(&$data, &$errors){
  		return $this->field_check($arr, $err);
	}

	function init (){}
	
	/**
	 * Used to check the manditory fields defined in the manditory array
	 */
	public function check (&$arr, &$err){
  		foreach($this->mandatory as $field){
  			if(empty($arr[$field])){$err[$field]=con('mandatory');}
  		}
    	return (count($err)==0);
  	}

	
	function on_handle($order, $new_status, $old_status, $field){
    return true;
  }

	function on_order_delete($order_id){
    return true;
  }
	
  function on_confirm(&$order){return '';}

  function on_submit(&$order, &$err ){}

  function on_return(&$order, $accepted ){
     return array('approved'=>$accepted,
                  'transaction_id'=>false,
                  'response'=> '');
  }

  function on_notify(&$order){}

  function on_check(&$order){ return false;}
  
  public function encodeCallback(){return "";}
  
  public function decodeCallback(){return true;}
  
//****************************************************************************//

	protected function encodeEPHCallback($ephCode){
		
		$code = base64_encode($this->handling_payment.':'.base_convert($this->handling_id,10,36).':'.$ephCode);
		
		return "callback=".urlencode($code);
	}
	
	/**
	 * Payment::decodeEPHCallback()
	 * 
	 * It will break down the callback hash, find which eph then check against its validation method
	 * to check that the handling id matches the settings within the eph.
	 * The handling object filled will then be returned on successfull decode and validation.
	 * 
	 * @return handling Object or null.
	 * @uses Handling
	 * @since 1.0b5
	 */
	public function decodeEPHCallback($callbackCode){
		require_once('classes/Handling.php');
		
		if (empty($callbackCode) and isset($_REQUEST['callback'])) $callbackCode =$_REQUEST['callback'];
		
		if(!empty($callbackCode)){
			
			$hand = null; //handling var
			
  			$text = base64_decode($callbackCode);
      		$code = explode(':',$text);
    		//  print_r( $text );
      		$code[1] = base_convert($code[1],36,10);
      		
      		if(is_numeric($code[1])){
	  			$hand = Handling::load($code[1]);	  			
	  		}
	  		if($hand == null){
	  			return null;
			}
	  		if($hand->is_eph()){
				if($hand->handling_payment != $code[0]){
					return null;
				}
				if($hand->isValidCallback($code[2])){
					return $hand;
				}
	  		}
	  		return null;
		}
	}

  protected function url_post ($url,&$data){
    global $_SHOP;

  	switch($_SHOP->url_post_method) {

  		case "libCurl": //php compiled with libCurl support

  			$result=$this->libCurlPost($url,$data);
  			break;


  		case "curl": //cURL via command line

  			$result=$this->curlPost($url,$data);
  			break;


  		case "fso": //php fsockopen();
  		default: //use the fsockopen method as default post method

  			$result=$this->fsockPost($url,$data);
  			break;
  	}

  	return $result;
  }

  //post transaction data using curl

  private function curlPost($url,&$data)  {

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

  private function libCurlPost($url,&$data)  {

  	//build post string

  	foreach($data as $i=>$v) {
  		$postdata.= $i . "=" . urlencode($v) . "&";
  	}


  	$ch=curl_init();

  	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE)  ;
  	curl_setopt($ch,CURLOPT_URL,$url);
  	curl_setopt($ch,CURLOPT_POST,1);
  	curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);

  	//Start ob to prevent curl_exec from displaying stuff.
  	$info =curl_exec($ch);
  	curl_close($ch);

  	return $info;

  }

  //posts transaction data using fsockopen.
  private function fsockPost($url,&$data) {

  	//Parse url
  	$web=parse_url($url);

  	//build post string
  	foreach($data as $i=>$v) {
  		$postdata.= $i . "=" . urlencode($v) . "&";
  	}

  	//Set the port number
  	if($web['scheme'] == "https") { $web['port']="443";  $ssl="ssl://"; } else { $web['port']="80"; }

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

  function dyn_load($name){
    $res = include_once($name);
    return $res;
  }
}
?>