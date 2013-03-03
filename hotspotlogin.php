<?php

	/**
	 * This is the central script for processing all authentication activity
	 *
	 * It is called on first page load, then via ajax for subsequent requests
	 *
	 * @copyright 2013 Bluepod Media Ltd
	 * @author Barry Jones <barry@onalldevices.com>
	 */

	// Initialisation
	require_once('php/app.php');
	$loginpath  = $_SERVER['PHP_SELF'];
	$reloadurl  = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

	// Grab request params
	$form = array();
	$isxhr                  = $utils->getRequestVar('isxhr');
	$form['action']         = $utils->getRequestVar('action');
	$form['callback']       = $utils->getRequestVar('callback');
	$form['username']       = $utils->getRequestVar('username');
	$form['password']       = $utils->getRequestVar('password');
	$form['challenge']      = $utils->getRequestVar('challenge');
	$form['button']         = $utils->getRequestVar('button');
	$form['logout']         = $utils->getRequestVar('logout');
	$form['prelogin']       = $utils->getRequestVar('prelogin');
	$form['res']            = $utils->getRequestVar('res');
	$form['uamip']          = $utils->getRequestVar('uamip');
	$form['uamport']        = $utils->getRequestVar('uamport');
	$form['userurl']        = $utils->getRequestVar('userurl');
	$form['timeleft']       = $utils->getRequestVar('timeleft');
	$form['redirurl']       = $utils->getRequestVar('redirurl');
	$form['reply']          = $utils->getRequestVar('reply');
	$form['userurl']        = $utils->getRequestVar('userurl');

	// Force SSL
	if (!($_SERVER['HTTPS'] == 'on')) {
		if ($isxhr){
			sendJSONError($lang['errornonssl']);
		} else {
			$utils->setFlash('pageerror', $lang['errornonssl']);
			buildErrorView();
		}
		exit(0);
	}

	// Process login status
	switch($form['res']) {
		case 'success':     $result =  1; break; // If login successful
		case 'failed':      $result =  2; break; // If login failed
		case 'logoff':      $result =  3; break; // If logout successful
		case 'already':     $result =  4; break; // If tried to login while already logged in
		case 'notyet':      $result =  5; break; // If not logged in yet
		case 'smartclient': $result =  6; break; // If login from smart client
		case 'popup1':      $result = 11; break; // If requested a logging in pop up window
		case 'popup2':      $result = 12; break; // If requested a success pop up window
		case 'popup3':      $result = 13; break; // If requested a logout pop up window
		default: $result = 0; // Default: It was not a form request
	}

	// Check for daemon error
	if ($result == 0){
		if ($isxhr){
			sendJSONError($lang['daemon_error']);
		} else {
			$utils->setFlash('pageerror', $lang['daemon_error']);
			buildErrorView($lang['title_loginfailed']);
		}
		exit(0);
	}

	// Process auth actions
	switch ($result){
		case 1: // Successful login
			$utils->redirect('http://onalldevices.com');
			break;

		case 2: // Unsuccessful login
			$utils->setFlash('pageerror', $lang['login_failed']);
			buildView();
			break;

		case 3: // Logout
			buildView();
			break;

		case 4: // Already logged in
		case 12:
			$utils->redirect('http://onalldevices.com');
			break;

		case 5: // Nothing tried yet
			// Process standard form action
			switch ($form['action']){
				case 'login':

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

					// Redirect to auth URL
					$utils->redirect($authurl);

					break;
				default:
				    buildView();
			}
			break;
	}


	exit(0);
