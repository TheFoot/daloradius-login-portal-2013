<?php
	// Grab flash messages
	$alert = false; $error = false;
	if ($utils->getFlash('pagealert', true)){$alert = $utils->getFlash('pagealert');}
	if ($utils->getFlash('pageerror', true)){$error = $utils->getFlash('pageerror');}
?>
<div id="oad-page-message-bar" class="<?php echo ($alert||$error)?'':'hidden';?>">
	<div class="alert alert-success fade <?php echo ($alert) ? ' in': ' hidden';?>"
	     id="oad-messagebar-notify">
		<button type="button" class="close">×</button>
		<span><?php echo $alert;?></span>
	</div>
	<div class="alert alert-error fade <?php echo ($error ? ' in': ' hidden');?>"
	     id="oad-messagebar-error">
		<button type="button" class="close">×</button>
		<span><?php echo $error;?></span>
	</div>
</div>