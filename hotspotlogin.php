<?php

	/**
	 * This is the central script for processing all authentication activity
	 *
	 * It is called on first page load, and on auth callbacks
	 * It is also called via ajax for subsequent non-auth requests
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
	$form['called']         = $utils->getRequestVar('called');
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

	// Clean form inputs
	array_map(array($db, 'escape'), $form);

	// Process auth actions
	switch ($result){
		case 1: // Successful login

			// Store login cookie
			setcookie(
				$cookiename,
				json_encode($_SESSION['daloauth']),
				(time() + $cookieexpire),
				'/'
			);

			$utils->redirect($postloginurl);
			break;

		case 2: // Unsuccessful login
			$utils->setFlash('pageerror', $lang['login_failed']);
			buildView();
			break;

		case 3: // Logout and clear auth cookie
			setcookie(
				$cookiename,
				json_encode($_SESSION['daloauth']),
				(time() - 10),
				'/'
			);
			$utils->redirect($exturl);
			break;

		case 4: // Already logged in
		case 12:
			$utils->redirect($postloginurl);
			break;

		case 5: // Nothing tried yet

			// Process standard form actions
			switch ($form['action']){
				case 'login':
					doLogin($form);
					break;

				case "register":
					registerNewUser();
					break;

				case "getsecurityquestion":
					$question = getSecurityQuestion($form['username']);
					if ($question === false){
						sendJSONError($lang['forgot_password_no_question']);
					} else {
						sendJSONResponse(array(
							"question"  => $question
						));
					}
					break;

				case "fetchpassword":
					$form['answer'] = $utils->getRequestVar('answer');
					$form['answer'] = $db->escape($form['answer']);
					$answer = getPassword($form['username'], $form['answer']);
					if ($answer === false){
						sendJSONError($lang['forgot_password_invalid_answer']);
					} else {
						sendJSONResponse(array(
							"password"  => $answer
						));
					}
					break;

				default:
				    buildView();
			}
			break;
	}


	exit(0);
