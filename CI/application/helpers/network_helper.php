<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('network_getclientip')) {
    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
if ( ! function_exists('network_getclientip')) {
	function network_getclientip()
	{
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	 
	    return $ipaddress;
	}
}

if ( ! function_exists('network_get')) {
	function network_get($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();
			$data=null;
			if (is_array($content)) {
				$data = http_build_query($content);
			}

			if (is_string($data)) {
				curl_setopt($curl, CURLOPT_URL, $url."?".$data);
			} else {
				curl_setopt($curl, CURLOPT_URL, $url);
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			} else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_post')) {
	function network_post($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();
			$headers=array();
			$headers[]='withuroad:1';
			if (is_array($content)) {
				$data = http_build_query($content);
			}

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);

			curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_postxml')) {
	function network_postxml($url, $content, $ishttps = false)
	{
		if (function_exists("curl_init")) {

			$curl = curl_init();

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			} else {
				curl_close($curl);
				return $ret_data;
			}

		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_postforjson')) {
	function network_postforjson($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();

			if (is_array($content)) {
				$content = json_encode($content);
			}

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:application/json",
													     "Accept:application/json"));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_postreturnstatuscode')) {
	function network_postreturnstatuscode($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();

			if (is_array($content)) {
				$content = json_encode($content);
			}

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:application/json",
													     "Accept:application/json"));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ishttps);

			$ret_data = curl_exec($curl);
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $httpCode;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('postforxintaiprotocal')) {

	function postforxintaiprotocal($baseurl, $interfacename, $systemno, $password, $clientid, $bodycontent)
	{
		if (function_exists("curl_init")) {

			$curl = curl_init();

			date_default_timezone_set('Asia/Shanghai');
			$datetime = date("YmdHis");
			$date = date("Ymd");
			$transNo = $interfacename.$date."00000000";

			$signInfo = strtoupper(md5(strtoupper(md5($systemno.$transNo.$datetime)).$password));

			$postString = array("time" => $datetime,
								"body" => $bodycontent,
								"signInfo" => $signInfo,
								"systemNo" => $systemno,
								"id" => $transNo,
								"clientId" => $clientid);

			$content = json_encode($postString);

			$url = $baseurl.$interfacename."/";

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:application/json",
													     "Accept:application/json"));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				if ($ret_data) {
				 	$response = json_decode($ret_data, true);

				 	if ($response['returnCode'] == "1") {
				 		return array("status" => "1",
				 					 "data" => $response['body']);
				 	} else {
				 		return array("status" => "0",
				 					 "msg" => $response['returnMessage']);
				 	}

				} else {
					return false;
				}
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_xwwwformurlencoded_post')) {

	function network_xwwwformurlencoded_post($url, $data)
	{
		
		$postdata = http_build_query ( $data );

		$opts = array (
				'http' => array (
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postdata 
				) 
		);
		
		
		$context = stream_context_create ( $opts );
		
		$result = file_get_contents ( $url, false, $context );
		
		return $result;

	}	
}