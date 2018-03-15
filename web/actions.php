<?php
ini_set('session.gc_maxlifetime', 86400);

include("inc/db.php");
require_once("inc/sessions.php");
$sess = new SessionManager();
session_start();

include("inc/global_vars.php");
include("inc/functions.php");

// ini_set('error_reporting', E_ALL); 

$a = $_GET['a'];

switch ($a)
{
	case "test":
		test();
		break;
	
	case "my_account_update":
		my_account_update();
		break;
		
	case "my_account_update_photo":
		my_account_update_photo();
		break;
		
	case "set_status_message":
		set_status_message();
		break;
		
			
// default
				
	default:
		home();
		break;
}

function home(){
	die('access denied to function name ' . $_GET['a']);
}

function test(){
	echo '<h3>$_SESSION</h3>';
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_POST</h3>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_GET</h3>';
	echo '<pre>';
	print_r($_GET);
	echo '</pre>';
	echo '<hr>';
}

function my_account_update(){
	global $whmcs, $site;
	
	$user_id 						= $_SESSION['account']['id'];
	
	$firstname 						= clean_string(addslashes($_POST['firstname']));
	$lastname 						= clean_string(addslashes($_POST['lastname']));
	$companyname 					= clean_string(addslashes($_POST['companyname']));
	$email 							= clean_string(addslashes($_POST['email']));
	$phonenumber 					= clean_string(addslashes($_POST['phonenumber']));
	$address_1 						= clean_string(addslashes($_POST['address1']));
	$address_2 						= clean_string(addslashes($_POST['address2']));
	$address_city 					= clean_string(addslashes($_POST['city']));
	$address_state 					= clean_string(addslashes($_POST['state']));
	$address_zip 					= clean_string(addslashes($_POST['postcode']));
	$address_country 				= clean_string(addslashes($_POST['country']));

	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	
	$postfields["action"] 			= "updateclient";
	$postfields["clientid"] 			= $user_id;
	$postfields["firstname"] 		= $firstname;
	$postfields["lastname"] 			= $lastname;
	$postfields["companyname"] 		= $companyname;
	$postfields["email"] 			= $email;
	$postfields["phonenumber"] 		= $phonenumber;
	$postfields["address1"] 			= $address_1;
	$postfields["address2"] 			= $address_2;
	$postfields["city"] 				= $address_city;
	$postfields["state"] 			= $address_state;
	$postfields["postcode"] 			= $address_zip;
	$postfields["country"] 			= $address_country;
	
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
		
	if($results["result"]=="success") {
		status_message('success', 'Your account details have been updated.');
	}else{
		status_message('danger', 'There was an error updating your account details.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function my_account_update_photo(){
	global $whmcs, $site;
	$user_id 					= $_SESSION['account']['id'];

	$fileName = $_FILES["file1"]["name"]; // The file name
	
	$fileName = str_replace('"', '', $fileName);
	$fileName = str_replace("'", '', $fileName);
	$fileName = str_replace(' ', '_', $fileName);
	$fileName = str_replace(array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '+', ';', ':', '\\', '|', '~', '`', ',', '<', '>', '/', '?', '§', '±',), '', $fileName);
	// $fileName = $fileName . '.' . $fileExt;
	
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if (!$fileTmpLoc) { // if file not chosen
		echo "Please select a photo to upload first.";
		exit();
	}
	
	// check if folder exists for customer, if not create it and continue
	if (!file_exists('uploads/'.$user_id) && !is_dir('uploads/'.$user_id)) {
		mkdir('uploads/'.$user_id);
	} 
	
	// handle the uploaded file
	if(move_uploaded_file($fileTmpLoc, "uploads/".$user_id."/".$fileName)){
		
		// insert into the database
		mysql_query("UPDATE user_data SET `avatar` = '".$site['url']."/uploads/".$user_id."/".$fileName."' WHERE `user_id` = '".$user_id."' ") or die(mysql_error());		
		
		// report
		echo "<font color='#18B117'><b>Upload Complete</b></font>";
		
	}else{
		echo "ERROR: Oops, something went very wrong. Please try again or contact support for more help.";
		exit();
	}	
}

function set_status_message(){
	$status 				= $_GET['status'];
	$message			= $_GET['message'];
	
	status_message($status, $message);
}

function site_add(){
	$uid			= $_SESSION['account']['id'];
	$site			= clean_string($_POST['site']);
	
	$input = mysql_query("INSERT INTO user_sites 
		(`added`, `uid`, `site`)
		VALUE
		('".time()."', '".$uid."', '".$site."')") or die(mysql_error());
		
	if($input) {
		status_message('success', 'Your site has been added.');
	}else{
		status_message('danger', 'There was an error adding your site, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}





function my_videos_upload(){
	$billing_id 					= $_SESSION['account']['billing_id'];
	$user_id 					= $_SESSION['account']['user_id'];

	$fileName = $_FILES["file1"]["name"]; // The file name
	$fileExt = end((explode(".", $fileName)));
	if($fileExt == array('exe','php','iso','dmg','msi')){
		echo "ERROR: Invalid file format used. $fileExt files are not allowed.";
		exit();
	};
	
	$fileName = str_replace('"', '', $fileName);
	$fileName = str_replace("'", '', $fileName);
	$fileName = str_replace(' ', '_', $fileName);
	$fileName = str_replace(array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '+', ';', ':', '\\', '|', '~', '`', ',', '<', '>', '/', '?', '§', '±',), '', $fileName);
	// $fileName = $fileName . '.' . $fileExt;
	
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if (!$fileTmpLoc) { // if file not chosen
		echo "ERROR: Please browse for a file before clicking the upload button.";
		exit();
	}
	
	// check if folder exists for customer, if not create it and continue
	if (!file_exists('uploads/'.$user_id) && !is_dir('uploads/'.$user_id)) {
		mkdir('uploads/'.$user_id);
		// mkdir('uploads/'.$user_id.'/thumbs');
	} 
	
	// handle the uploaded file
	if(move_uploaded_file($fileTmpLoc, "uploads/".$user_id."/".$fileName)){
		
		// insert into the database
		$name = addslashes($_POST['video_name']);
		$cat = $_POST['cat'];
		$input = mysql_query("INSERT INTO user_videos 
			(`added`, `uid`, `caption`, `filename`, `filesize`, `video_id`, `cat`)
			VALUE
			('".time()."', '".$user_id."', '".$name."', '".$fileName."', '".$fileSize."', '".uniqid()."', '".$cat."')") or die(mysql_error());
		
		if($fileExt!="mp4"){

			echo '<br><strong>Converting video</strong> ... please wait';
			
			// convert video to mp4
			$type 		= 'mp4';
			$quality 	= '800000';
			$audio 		= '22050';
			$size 		= '512x384';
			$path		= "uploads/".$user_id."/".$fileName;
	
			if($fileExt == '3pg'){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -sameq -ab 64k -ar 44100 ".$path.".".$type;
			}
			
			if($fileExt == "mov"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -acodec copy -vcodec copy ".$path.".".$type;
			}
			
			if($fileExt == "flv"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vcodec flv -f flv -r 30 -b ".$quality." -ab 128000 -ar ".$audio." -s ".$size." ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
		
			if($fileExt == "avi"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vcodec mjpeg -f avi -acodec libmp3lame -b ".$quality." -s ".$size." -r 30 -g 12 -qmin 3 -qmax 13 -ab 224 -ar ".$audio." -ac 2 ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
			
			if($fileType == "mp3"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vn -acodec libmp3lame -ac 2 -ab 128000 -ar ".$audio."  ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
			
			if($fileExt == "mp4"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName."  -vcodec mpeg4 -r 30 -b ".$quality." -acodec aac -strict experimental -ab 192k -ar ".$audio." -ac 2 -s ".$size." ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
			
			if($fileExt == "wmv"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vcodec wmv1 -r 30 -b ".$quality." -acodec wmav2 -ab 128000 -ar ".$audio." -ac 2 -s ".$size." ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
			
			if($fileExt == "ogg"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vcodec libtheora -r 30 -b ".$quality." -acodec libvorbis -ab 128000   -ar ".$audio." -ac 2 -s ".$size." ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
			
			if($fileExt == "webm"){
				$call = "/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vcodec libvpx  -r 30 -b ".$quality." -acodec libvorbis -ab 128000   -ar ".$audio." -ac 2 -s ".$size." ".$path.".".$type." -y 2> log/".$fileName.".txt";
			}
			
			// $call="/usr/bin/ffmpeg -i uploads/".$user_id."/".$fileName." -vcodec flv -f flv -r 30 -b ".$quality." -ab 128000 -ar ".$audio." -s ".$size." ".$path.".".$type." -y 2> log/".$fileName.".txt";
			
			$convert = (popen($call." >/dev/null &", "r"));
			pclose($convert);
		}
	
		// report
		echo "<br><br><font color='#18B117'><b>Upload Complete</b></font><br><br>You may upload another video now or <a href='?c=my_videos'>return to your videos</a>";
		
	}else{
		echo "ERROR: Oops, something went very wrong. Please try again or contact support for more help.";
		exit();
	}	
}

function my_gallery_delete(){
	$user_id			= $_SESSION['account']['user_id'];	
	$photo_id			= $_GET['id'];
	
	$query = "SELECT * FROM user_photos WHERE uid = '".$user_id."' AND id = '".$photo_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){	
		$filename = $row['filename'];
	}
	
	mysql_query("DELETE FROM user_photos WHERE uid = '".$user_id."' AND id = '".$photo_id."' ");
	
	exec("rm -rf uploads/".$user_id."/".$filename);
	exec("rm -rf uploads/".$user_id."/thumbs/".$filename);
	
	go('dashboard.php?c=my_gallery&status=success&message=Photo has been removed from your gallery.');
}

function my_gallery_profile_photo(){
	$user_id			= $_SESSION['account']['user_id'];	
	$photo_id			= $_GET['id'];
	
	$query = "SELECT * FROM user_photos WHERE uid = '".$user_id."' AND id = '".$photo_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){	
		$filename = $row['filename'];
	}
	
	$query = "UPDATE users SET photo = 'https://boudoirsocial.com/uploads/".$user_id."/thumbs/".$filename."' WHERE user_id = '".$user_id."' ";
	mysql_query($query) or die(mysql_error());
	
	go('dashboard.php?c=my_gallery&status=success&message=Photo has been set as your profile photo.');
}

function tip_member(){
	$from						= username_to_uid($_GET['from']);
	$to							= username_to_uid($_GET['to']);
	$amount						= $_GET['amount'];
	
	$billing_id 					= $_SESSION['account']['billing_id'];
	$user_id 					= $_SESSION['account']['user_id'];
	
	$query = "SELECT credit FROM users WHERE user_id = '".$from."' " ;
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$from_balance = $row['credit'];
	}
	if($amount > $from_balance){
		die('You do not have enough credits to tip '.$amount.' credits. Please add more credits.');
	}else{
		$query = "SELECT credit FROM users WHERE user_id = '".$to."' " ;
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$to_balance = $row['credit'];
		}
		$new_balance = $to_balance + $amount;
		
		mysql_query("UPDATE users SET credit = '".$new_balance."' WHERE user_id = '".$to."' ");
		
		$input = mysql_query("INSERT INTO user_transactions 
		(`added`, `from`, `to`, `amount`)
		VALUE
		('".time()."', '".$from."', '".$to."', '".$amount."')") or die(mysql_error());
		
		die('You have tipped your model '.$amount.' credits.');
	}
	
}

function get_livecam_session_id(){
	$username			= uid_to_username($_GET['uid']);
	
	// get livecam status
	$query = "SELECT * FROM boudoirsocial_livecam.vc_session WHERE username = '".$username."' " ;
	$result = mysql_query($query) or die(mysql_error());
	$livecam = mysql_num_rows($result);
	if($livecam == 1){
		while($row = mysql_fetch_array($result)){
			echo $row['id'];
		}
	}else{
		echo 0;
	}	
}

function get_current_balance(){
	$credit = credit_balance();
	
	echo $credit;	
}

function purchase_item(){
	$from 					= $_SESSION['account']['user_id'];	
	$to						= $_GET['m'];
	$type					= $_GET['t'];
	
	if($type == 'paid_videos'){
		$field = 'paid_video_charge';
	}
	if($type == 'paid_photos'){
		$field = 'paid_photo_charge';
	}
	
	$query = "SELECT ".$field." FROM users WHERE user_id = '".$to."' " ;
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$amount 				= $row[$field];
	}
	
	$bill_user				= bill_member_do($from, $to, $amount, $type);
	if($bill_user == 'not_enough_credit'){
		go($_SERVER['HTTP_REFERER'].'&status=danger&message=You need more credits to make this purchase.');
	}else{
		go($_SERVER['HTTP_REFERER'].'&status=success&message=Purchase complete.');
	}
}

function message_member(){
	$from 					= $_SESSION['account']['user_id'];
	$to						= $_GET['to'];
	$amount					= 0.40;
	$type					= 'private_message';
	
	$subject				= addslashes($_POST['message_subject']);
	$message				= addslashes($_POST['message_message']);
	$message				= nl2br($message);
	
	// add message to db
	$input = mysql_query("INSERT INTO user_messages 
		(`added`, `from`, `to`, `subject`, `message`, `status`)
		VALUE
		('".time()."', '".$from."', '".$to."', '".$subject."', '".$message."', 'unread')") or die(mysql_error());
		
	// only bill user accounts and not models
	if($_SESSION['account']['account_type'] == 1 || $_SESSION['account']['account_type'] == 2){
		$bill_user				= bill_member_do($from, $to, $amount, $type);
	}
	
	if($bill_user == 'not_enough_credit'){
		go($_SERVER['HTTP_REFERER'].'&status=danger&message=You need more credits to send a message.');
	}else{
		if(!empty($_POST['redirect'])){
			go($_POST['redirect'].'&status=success&message=Message sent.');
		}else{
			go($_SERVER['HTTP_REFERER'].'&status=success&message=Message sent.');
		}
	}
}