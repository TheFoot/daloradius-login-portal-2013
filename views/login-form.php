<div id="loginform" class="row-fluid userform userview">
	<div class="span12 label label-inverse align-center">

		<!-- Greeting first.. -->
		<h1><?php echo $lang['welcome_title'];?></h1>
		<div class="alert alert-warning"><h3><?php echo $lang['please_login'];?></h3></div>
		<form class="login form-horizontal" action="" method="post">

			<!-- Auth fields -->
			<input type="hidden" name="isxhr" value="1" />
			<input type="hidden" name="action" value="login" />
			<input type="hidden" name="challenge" value="<?php echo $form['challenge'];?>" />
			<input type="hidden" name="uamip" value="<?php echo $form['uamip'];?>" />
			<input type="hidden" name="uamport" value="<?php echo $form['uamport'];?>" />
			<input type="hidden" name="userurl" value="<?php echo $form['userurl'];?>" />

			<div class="control-group">
				<label class="control-label" for="oad-username"><?php echo $lang['login_email'];?></label>
				<div class="controls">
					<input type="email" id="oad-username" name="username" autofocus
					       class="input-large" required placeholder="<?php echo $lang['login_email'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-password"><?php echo $lang['login_password'];?></label>
				<div class="controls">
					<input type="password" id="oad-password" class="input-large" name="password"
					       required placeholder="<?php echo $lang['login_password'];?>">
				</div>
			</div>
			<div class="control-group">
				<div class="action-controls">
					<button type="submit" class="btn btn-inverse btn-block btn-large"><?php echo $lang['login'];?></button>
					<button id="oad-open-registration" type="button" class="btn btn-block"><?php echo $lang['new_register'];?></button>
					<button id="oad-open-forgotpassword" type="button" class="btn btn-block"><?php echo $lang['forgot_password'];?></button>
				</div>
			</div>
		</form>

	</div>
</div>