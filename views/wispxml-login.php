<?php echo '<?xml version="1.0\" encoding="UTF-8"?>';?>
<!--
	<WISPAccessGatewayParam
	  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	  xsi:noNamespaceSchemaLocation=\"http://www.acmewisp.com/WISPAccessGatewayParam.xsd\">
	<AuthenticationReply>
		<MessageType>120</MessageType>
		<ResponseCode>201</ResponseCode>
		<?php if (UAM_SECRET && UAM_PWORD) {
		    $resultsurl = 'http://'.$form['uamip'].':'.
			    $form['uamport'].'/logon?username='.$form['username'].
			    '&password='.$papdata['password'];
		} else {
		    $resultsurl = 'http://'.$form['uamip'].':'.
			    $form['uamport'].'/logon?username='.$form['username'].
			    '&response='.$papdata['response'].'&userurl='.$form['userurl'];
		} ?>
		<LoginResultsURL><?php echo $resultsurl;?></LoginResultsURL>
    </AuthenticationReply>
</WISPAccessGatewayParam>
-->