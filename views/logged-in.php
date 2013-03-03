<h1><?php echo $lang['loggedin_title'];?></h1>
<?php if ($result == 1 && $form['reply']){?>
	<p class="lead"><?php echo $form['reply'];?></p>
<?php } ?>
<p>
	<a class="btn btn-primary"
	   href="http://<?php echo $form['uamip'].':'.$form['uamport'];?>/logoff"
	   title="<?php echo $lang['logout'];?>">
		<?php echo $lang['logout'];?></a>
</p>