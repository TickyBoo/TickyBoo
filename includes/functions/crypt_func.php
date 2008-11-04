<?php

	function ssl_crypt($data,$key){
		global $_SHOP;
		
		if(strlen($data)==0){
		  user_error('empty data');
			return FALSE;
		}
		
		if($_SHOP->crypt_mode=='seal'){
		  return _seal($data,$key);
		}else{
			return _crypt($data,$key);
		}
	}

	function ssl_decrypt($data,$key,$pwd=''){
		global $_SHOP;
		
		if(strlen($data)==0){
		  user_error('empty data');
			return FALSE;
		}
		
		if($_SHOP->crypt_mode=='seal'){
		  return _open($data,$key,$pwd);
		}else{
			return _decrypt($data,$key,$pwd);
		}
	}

	function _openssl_error(){
	  while($err=openssl_error_string()){
		  user_error($err);
		}
		return FALSE;
	}
	
	function _str_split($string,$length=1){
		$parts = array();
		while ($string) {
			array_push($parts, substr($string,0,$length) );
			$string = substr($string,$length);
		}
		return $parts;
	}
	
	
	function _crypt($info,$key){		
		if($pk = openssl_get_publickey($key)){

			$parts=_str_split($info,53);	
			
			foreach($parts as $part){
				if(!openssl_public_encrypt($part, $sealed, $pk)){
					return _openssl_error();
				}
				$crypts[]=base64_encode($sealed);
			}
			
			openssl_free_key($pk);	
			
			return implode(',',$crypts);
		}
		return _openssl_error();
	}

	function _decrypt($cinfo,$pkey,$pwd){		
		if($pk = openssl_get_privatekey($pkey,$pwd)){

			$crypts=explode(',',$cinfo);
			
			foreach($crypts as $crypt){
				if(!openssl_private_decrypt(base64_decode($crypt), $i, $pk)){
					return _openssl_error();
				}
				$info.=$i;
			}
			
			openssl_free_key($pk);	
			
			return $info;
		}
		
		return _openssl_error();
	}


	function _seal($info,$key){		
		if($pk = openssl_get_publickey($key)){

      

			if(!$sealres=openssl_seal($info, $sealed, $ekeys, array($pk))){
			  return _openssl_error();
			}			

			openssl_free_key($pk);	
			
			return base64_encode($sealed).",".base64_encode($ekeys[0]);
		}
		
		return _openssl_error();
	}

	function _open($cinfo_ekey,$pkey,$pwd){		
		if($pk = openssl_get_privatekey($pkey,$pwd)){

			list($cinfo,$ekey)=explode(',',$cinfo_ekey);
			$cinfo=base64_decode($cinfo);
			$ekey=base64_decode($ekey);
			
			if(!$sealres=openssl_open($cinfo, $info, $ekey, $pk)){
			  return _openssl_error();
			}			

			openssl_free_key($pk);	
			
			return $info;
		}
		
		return _openssl_error();
	}

	
?>