<?php

	/**
	 * Validate and register a new user with radius.
	 *
	 * This is an ajax handler, and returns a JSON response.
	 *
	 * @copyright 2013 Bluepod Media Ltd
	 * @author Barry Jones <barry@onalldevices.com>
	 *
	 */
	require_once('app.php');

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

