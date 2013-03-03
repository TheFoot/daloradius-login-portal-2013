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
		global $root, $lang, $nav, $utils, $form;
		include($root.'/views/html-header.php');
		include($root.'/views/top.php');
		include($root.'/views/login-form.php');
		include($root.'/views/registration-form.php');
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
	function sendJSONError($error = ''){
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', (time() - 60)).' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: 0');
		header('Content-type: application/json');
		echo json_encode(array(
			"success"       => false,
			"error"         => $error
		));
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
