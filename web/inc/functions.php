<?php

function check_whmcs_status($userid){
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["responsetype"] 		= "json";
	$postfields["action"] 			= "getclientsproducts";
	$postfields["clientid"] 			= $userid;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$data = json_decode($data);
	$api_result = $data->result;
	// $clientid = $data->clientid;
	// $product_name = $data->products->product[0]->name;
	$product_status = strtolower($data->products->product[0]->status);
	
	if($product_status != 'active'){
		
		// forward to billing area
		$whmcsurl = "https://billing.boudoirsocial.com/dologin.php";
		$autoauthkey = "admin1372";
		$email = clean_string($_SESSION['account']['email']);
		
		$timestamp = time(); 
		$goto = "clientarea.php";
		
		$hash = sha1($email.$timestamp.$autoauthkey);
		
		$url = $whmcsurl."?email=$email&timestamp=$timestamp&hash=$hash&goto=".urlencode($goto);
		
		go($url);
	}
}

function account_details($billing_id){
	global $whmcs;
	
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["action"] 			= "getclientsdetails";
	$postfields["clientid"] 			= $billing_id;	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$data = explode(";",$data);
	foreach ($data AS $temp) {
	  	$temp = explode("=",$temp);
	  	$results[$temp[0]] = $temp[1];
	}
	
	$results['product_ids']			= get_product_ids($billing_id);
	
	$results['products']				= check_products($billing_id);
	
	if($results["result"] == "success") {		
		// get local account data 
		$query = "SELECT * FROM user_data WHERE user_id = '".$billing_id."' " ;
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)){	
			$results['account_type']			= $row['account_type'];
			$results['avatar']				= $row['avatar'];
		}
		
		return $results;
	} else {
		// error
		die("billing API error: unable to access your account data, please contact support");
	}	
	
}

function check_products($billing_id){
	global $whmcs, $site;
	
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["responsetype"] 		= "json";
	$postfields["action"] 			= "getclientsproducts";
	$postfields["clientid"] 			= $billing_id;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$data = json_decode($data);
	$api_result = $data->result;
	
	return $data->products->product;
	// $clientid = $data->clientid;
	// $product_name = $data->products->product[0]->name;
	//$product_status = strtolower($data->products->product[0]->status);
}

function get_other_user_details($billing_id){
	/*
	global $whmcs;
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["action"] 			= "getclientsdetails";
	$postfields["clientid"] 			= $billing_id;	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$data = explode(";",$data);
	foreach ($data AS $temp) {
	  $temp = explode("=",$temp);
	  $results[$temp[0]] = $temp[1];
	}
	
	if ($results["result"]=="success"){
		*/
		// get local account data 
		$query = "SELECT * FROM users WHERE billing_id = '".$billing_id."' " ;
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$results['user_id'] 			= $row['user_id'];
			$results['username'] 		= stripslashes($row['username']);
			$results['gender'] 			= $row['gender'];
			$results['dob']				= $row['dob'];
			$results['account_type'] 	= $row['account_type'];
			$results['last_login']		= $row['last_login'];
			$results['photo'] 			= $row['photo'];
			if(empty($row['photo'])){
				if($row['gender'] == 'male'){
					$results['photo'] = 'img/male_avatar.jpg';
				}elseif($row['gender'] == 'female'){
					$results['photo'] = 'img/female_avatar.png';
				}else{
					$results['photo'] = 'img/default_avatar.jpg';
				}
			}
			
			$results['paid_photo_charge'] 	= stripslashes($row['paid_photo_charge']);
			$results['paid_video_charge'] 	= stripslashes($row['paid_video_charge']);
			
			$results['per_min_video_fee'] 	= stripslashes($row['per_min_video_fee']);
			$results['per_min_phone_fee'] 	= stripslashes($row['per_min_phone_fee']);	
						
			$results['tagline'] 				= stripslashes($row['tagline']);
			$results['description'] 			= stripslashes($row['description']);
			$results['verified'] 			= $row['verified'];
			
			$results['facebook'] 			= stripslashes($row['facebook']);
			$results['twitter'] 				= stripslashes($row['twitter']);
			$results['skype'] 				= stripslashes($row['skype']);
			$results['youtube'] 				= stripslashes($row['youtube']);
			
			$results['city'] 				= stripslashes($row['city']);
			$results['state'] 				= stripslashes($row['state']);
			$results['country'] 				= stripslashes($row['country']);
			
		}
		
		// livecam online status
		$query = "SELECT * FROM boudoirsocial_livecam.vc_session WHERE username = '".$results['username']."' " ;
		$result = mysql_query($query) or die(mysql_error());
		$livecam = mysql_num_rows($result);
		if($livecam == 1){
			$results['livecam'] = 'online';
		}else{
			$results['livecam'] = 'offline';
		}	
		
		return $results;
	// }
}

function percentage($val1, $val2, $precision) {
	$division = $val1 / $val2;
	$res = $division * 100;
	$res = round($res, $precision);
	return $res;
}

function clean_string($value){
    if ( get_magic_quotes_gpc() ){
         $value = stripslashes( $value );
    }
	// $value = str_replace('%','',$value);
    return mysql_real_escape_string($value);
}

function go($link = ''){
	header("Location: " . $link);
	die();
}

function url($url = '') {
	$host = $_SERVER['HTTP_HOST'];
	$host = !preg_match('/^http/', $host) ? 'http://' . $host : $host;
	$path = preg_replace('/\w+\.php/', '', $_SERVER['REQUEST_URI']);
	$path = preg_replace('/\?.*$/', '', $path);
	$path = !preg_match('/\/$/', $path) ? $path . '/' : $path;
	if ( preg_match('/http:/', $host) && is_ssl() ) {
		$host = preg_replace('/http:/', 'https:', $host);
	}
	if ( preg_match('/https:/', $host) && !is_ssl() ) {
		$host = preg_replace('/https:/', 'http:', $host);
	}
	return $host . $path . $url;
}

function post($key = null) {
	if ( is_null($key) ) {
		return $_POST;
	}
	$post = isset($_POST[$key]) ? $_POST[$key] : null;
	if ( is_string($post) ) {
		$post = trim($post);
	}
	return $post;
}

function get($key = null) {
	if ( is_null($key) ) {
		return $_GET;
	}
	$get = isset($_GET[$key]) ? $_GET[$key] : null;
	if ( is_string($get) ) {
		$get = trim($get);
	}
	return $get;
}

function debug($input) {
	$output = '<pre>';
	if ( is_array($input) || is_object($input) ) {
		$output .= print_r($input, true);
	} else {
		$output .= $input;
	}
	$output .= '</pre>';
	echo $output;
}

function debug_die($input) {
	die(debug($input));
}

function mysql_disconnect(){
	global $connection;
	mysql_close($connection);
}

function get_product_ids($uid){
	global $whmcs;
	$url 						= $whmcs['url'];
	$postfields["username"] 		= $whmcs['username'];
	$postfields["password"] 		= $whmcs['password'];
	$postfields["responsetype"] = "json";
	$postfields["action"] 		= "getclientsproducts";
	$postfields["clientid"] 		= $uid;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$data = json_decode($data);
	$api_result = $data->result;
		
	foreach($data->products->product as $product_data){
		$pids[] = $product_data->pid;
	}
	
	return $pids;
}

function status_message($status, $message){
	$_SESSION['alert']['status']			= $status;
	$_SESSION['alert']['message']		= $message;
}

function active_product_check($needles, $haystack){
   return !!array_intersect($needles, $haystack);
}

function show_my_profile_products($account_details){
	global $whmcs, $site;
	
	foreach($account_details['products'] as $product){
		$status = $product->status;
		if($status == 'Active'){
			$status = 'green';
		}else{
			$status = 'red';
		}
		
		echo '
			<tr>
				<td>'.$product->name.'</td>
				<td><span class="badge bg-'.$status.'">'.$product->status.'</span></td>
			</tr>
		';	
	}
}

function call_remote_content($url){
	echo file_get_contents($url);
}
