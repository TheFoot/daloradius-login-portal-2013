<?php

	/**
	 * Initialise the app
	 */
	global $root;
	$root 		= dirname(dirname(__FILE__));
	require_once($root.'/php/_config.php');
	require_once($root.'/php/class.oad.utils.php');
	require_once($root.'/php/class.oad.database.php');
	session_start();

	// Debug
	if (DEV_DEBUG){
		ini_set("display_errors", 1);
		error_reporting(E_ALL);
	}

	// Pull in the radius config
	require_once($root.'/php/daloradius.conf.php');

	// Lang file
	require_once($root.'/lang/'.LANG.'.php');

	// Utility class
	global $utils;
	try {
		$utils = new c_oad_utils();
	} catch (Exception $e) {
		die('Error creating utility objects: '.$e->getMessage());
	}

	// Create a database connection
	global $db;
	try {
		$db = new c_oad_database();
	} catch (Exception $e) {
		die('Error creating local database instance: '.$e->getMessage());
	}
	if (!$db->connect(
		$configValues['CONFIG_DB_HOST'],
		$configValues['CONFIG_DB_USER'],
		$configValues['CONFIG_DB_PASS'],
		$configValues['CONFIG_DB_NAME']
	)){
		die('Error connecting to database: '.$db->getLastError());
	}

	// Function to build the view
	function buildView(){
		global $root, $lang, $nav, $utils, $form, $cookiename;
		include($root.'/views/html-header.php');
		include($root.'/views/top.php');
		include($root.'/views/login-form.php');
		include($root.'/views/registration-form.php');
		include($root.'/views/passwordremind-form.php');
		include($root.'/views/password-modal.php');
		include($root.'/views/html-footer.php');
	}

	// Function to build the error view
	function buildErrorView($title = false){
		global $root, $lang, $nav, $utils, $reloadurl;
		include($root.'/views/html-header.php');
		include($root.'/views/top.php');
		include($root.'/views/error.php');
		include($root.'/views/html-footer.php');
	}

	// Function to set a JSON error response
	function sendJSONError($error = '', $data = array()){
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', (time() - 60)).' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: 0');
		header('Content-type: application/json');
		echo json_encode(array_merge($data, array(
			"success"       => false,
			"error"         => $error
		)));
	}

	// Send normal json response
	function sendJSONResponse($data){
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', (time() - 60)).' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: 0');
		header('Content-type: application/json');
		echo json_encode(array_merge($data, array(
			"success"       => true,
			"error"         => ''
		)));
	}

	// Send jsonp response
	function sendJSONPResponse($data, $callback){
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', (time() - 60)).' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: 0');
		header('Content-type: application/javascript');
		echo $callback.'('.json_encode(array_merge($data, array(
			"success"       => true,
			"error"         => ''
		))).')';
	}

	// Get the auth for a login
	function getPapDetails($challenge, $password){
		$hexchal = pack("H32", $challenge);
		if (UAM_SECRET && strlen(UAM_SECRET) > 0) {
			$newchal = pack ("H*", md5($hexchal.UAM_SECRET));
		} else {
			$newchal = $hexchal;
		}
		$response = md5("\0" . $password . $newchal);
		$newpwd = pack("a32", $password);
		$pappassword = implode ("", unpack("H32", ($newpwd ^ $newchal)));
		return array(
			"password"      => $pappassword,
			"response"      => $response
		);
	}

	// Is the specified userid unique?
	function isUniqueUser($userid){

		global $db, $configValues;
		$sql = "select 1 from ".$configValues['CONFIG_DB_TBL_RADCHECK']." where ".
			"Username = '$userid'";
		return ($db->count($sql) == 0);

	}

	// Register new user
	function registerNewUser(){

		global $db, $utils, $lang, $configValues;

		// Check form values
		$formtype   = 'request'; // 'post';
		$postdata   = array();
		$postdata['firstname']  = $utils->getRequestVar('firstname', $formtype);
		$postdata['lastname']   = $utils->getRequestVar('lastname', $formtype);
		$postdata['age']        = $utils->getRequestVar('age', $formtype, 'int');
		$postdata['gender']     = $utils->getRequestVar('gender', $formtype);
		$postdata['telephone']  = $utils->getRequestVar('telephone', $formtype);
		$postdata['email']      = $utils->getRequestVar('email', $formtype);
		$postdata['password']   = $utils->getRequestVar('password', $formtype);
		$postdata['question']   = $utils->getRequestVar('question', $formtype);
		$postdata['answer']     = $utils->getRequestVar('answer', $formtype);

		// Validate required fields
		$failedfields = array();
		if (strlen($postdata['firstname']) == 0){
			$failedfields[] = $lang['firstname'];
		}
		if (strlen($postdata['lastname']) == 0){
			$failedfields[] = $lang['lastname'];
		}
		if (strlen($postdata['email']) == 0 || !$utils->validateEmailAddress($postdata['email'])){
			$failedfields[] = $lang['email'];
		}
		if (strlen($postdata['password']) == 0){
			$failedfields[] = $lang['password'];
		}
		if (strlen($postdata['question']) == 0){
			$failedfields[] = $lang['question'];
		}
		if (strlen($postdata['answer']) == 0){
			$failedfields[] = $lang['answer'];
		}
		if (count($failedfields) > 0){
			sendJSONError($lang['failed_fields'].': '.implode(', ', $failedfields));
			exit(0);
		}

		// Validate unique email address
		if (!isUniqueUser($postdata['email'])){
			sendJSONError($lang['not_unique_userid'], array(
				"show_login"    => true,
				"username"      => $postdata['email']
			));
			exit(0);
		}

		// Escape values
		array_map(array($db, 'escape'), $postdata);

		/*
	   * Create the new user
	   */
		$error = false;
		$db->beginTrans();
		try {

			// Add user to radcheck table
			$fields = array('id', 'Username', 'Attribute', 'op', 'Value');
			$data = array(
				0, $postdata['email'], 'ClearText-Password', ':=', $postdata['password']
			);
			$db->insert($configValues['CONFIG_DB_TBL_RADCHECK'], $fields, $data);
			if (strlen($db->getLastError())){
				throw new Exception ('Error adding user to radcheck table: '.$db->getLastError());
			}

			// Add user to radreply table
			$fields = array('id', 'Username', 'Attribute', 'op', 'Value');
			$data = array(
				0, $postdata['email'], 'Reply-Message', '=', $lang['welcome_title']
			);
			$db->insert($configValues['CONFIG_DB_TBL_RADREPLY'], $fields, $data);
			if (strlen($db->getLastError())){
				throw new Exception ('Error adding user to radreply table: '.$db->getLastError());
			}

			// Add user to userinfo table
			$fields = array('username', 'firstname', 'lastname', 'age', 'gender',
				'workphone', 'email', 'question', 'answer');
			$data = array(
				$postdata['email'], $postdata['firstname'], $postdata['lastname'], $postdata['age'],
				$postdata['gender'], $postdata['telephone'], $postdata['email'],
				$postdata['question'], $postdata['answer']
			);
			$db->insert($configValues['CONFIG_DB_TBL_DALOUSERINFO'], $fields, $data);
			if (strlen($db->getLastError())){
				throw new Exception ('Error adding user to userinfo table: '.$db->getLastError());
			}

			// Add user to the default group
			if (isset($configValues['CONFIG_GROUP_NAME']) && $configValues['CONFIG_GROUP_NAME'] != "") {
				$fields = array('UserName', 'GroupName', 'priority');
				$data = array(
					$postdata['email'],
					$configValues['CONFIG_GROUP_NAME'],
					$configValues['CONFIG_GROUP_PRIORITY']
				);
				$db->insert($configValues['CONFIG_DB_TBL_RADUSERGROUP'], $fields, $data);
				if (strlen($db->getLastError())){
					throw new Exception ('Error adding user to default group: '.$db->getLastError());
				}
			}

			// Success - commit changes
			$db->commitTrans();

		} catch (Exception $e){

			// Reverse the transaction
			if ($db->getInTrans()){$db->rollbackTrans();}
			$error = $e->getMessage();

		}
		$db->disconnect();

		// Send response back to callee
		$response = array(
			"username"      => $postdata['email'],
			"password"      => $postdata['password']
		);
		if (DEV_DEBUG){
			$response['debug'] = $postdata;
		}
		if ($error){
			sendJSONError($error);
		} else {
			sendJSONResponse($response);
		}

		exit;

	}

	// Perform a login
	function doLogin($form){

		global $utils;

		// Get PAP data
		$papdata = getPapDetails($form['challenge'], $form['password']);

		// Create auth url
		if (UAM_SECRET && UAM_PWORD) {
			$authurl = 'http://'.$form['uamip'].':'.
				$form['uamport'].'/logon?username='.$form['username'].
				'&password='.$papdata['password'];
		} else {
			$authurl = 'http://'.$form['uamip'].':'.
				$form['uamport'].'/logon?username='.$form['username'].
				'&response='.$papdata['response'].'&userurl='.$form['userurl'];
		}

		// Store credentials in session
		$_SESSION['daloauth'] = array(
			"username"      => $form['username'],
			"password"      => $form['password']
		);

		// Store AP MAC Address in session
		$_SESSION['dalo_ap_mac'] = $utils->getRequestVar('called');

		// Redirect to auth URL
		$utils->redirect($authurl);

	}

	// Fetch the security questiopn for a given username
	function getSecurityQuestion($userid){

		global $db, $configValues;

		$sql = "select question from ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].
			" where username = '$userid'";
		$users = $db->select($sql);
		if ($users === false || count($users) == 0){
			return false;
		} else {
			return $users[0]['question'];
		}

	}

	// Fetch the users password
	function getPassword($userid, $answer){

		global $db, $configValues;

		// First check the answer
		$sql = "select answer from ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].
			" where username = '$userid'";
		$users = $db->select($sql);
		if ($users === false || count($users) == 0){
			return false;
		} else {
			if (strtolower($users[0]['answer']) != strtolower($answer)){
				return false;
			}
		}

		// Retrieve the password
		$sql = "select value from ".$configValues['CONFIG_DB_TBL_RADCHECK'].
			" where username = '$userid'".
			" and attribute = 'ClearText-Password'";
		$pwd = $db->select($sql);
		if ($pwd === false || count($pwd) == 0){
			return false;
		} else {
			return $pwd[0]['value'];
		}

	}

	// Check the hotspots table for the current APs MAC address
	// and grab a local advert URL if set
	function getLocalAdvertSettings(){

		global $db, $configValues, $root;

		// Check we have an AP MAC stored
		$mac = $_SESSION['dalo_ap_mac'];
		if (!$mac || strlen($mac) == 0){
			return false;
		}

		// Fetch the hotspot local advert info
		$sql = "select local_ad_url, local_ad_caption, local_ad_image from ".
			$configValues['CONFIG_DB_TBL_DALOHOTSPOTS']." where mac = '".$_SESSION['dalo_ap_mac']."'";
		$rec = $db->select($sql);
		if ($rec === false || count($rec) == 0){
			return false;
		} else {
			$url = $rec[0]['local_ad_url'];
			$cap = $rec[0]['local_ad_caption'];
			$img = $rec[0]['local_ad_image'];

			// Only caption is allowed to be blank (caption may be embedded in image)
			if (strlen($url) > 0 && strlen($img) > 0 && file_exists($root.'/img/'.$img)){
				return array(
					"url"       => $url,
					"img"       => $img,
					"caption"   => $cap
				);
			} else {
				return false;
			}

		}

	}//$_SESSION['dalo_ap_mac']
