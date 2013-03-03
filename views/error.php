<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h1><?php echo ($title ? $title : $lang['chillispot']);?></h1>
		<div class="alert alert-error fade in">
			<span><?php echo $utils->getFlash('pageerror');?></span>
		</div>
		<a href="<?php echo $reloadurl;?>" title="<?php echo $lang['login'];?>"
		   class="btn btn-inverse btn-large btn-block"><?php echo $lang['login'];?></a>
	</div>
	<div class="span3"></div>
</div>
