<div id="passwordremindform" class="row-fluid userform userview">
	<div class="span12 label label-inverse align-center">

		<!-- Greeting first.. -->
		<h1><?php echo $lang['welcome_title'];?></h1>
		<div class="alert alert-warning"><h3><?php echo $lang['please_answer_security_question'];?></h3></div>

		<form class="login form-horizontal" action="hotspotlogin.php" method="post">
			<div class="control-group">
				<label class="control-label" for="oad-reminderquestion"><?php echo $lang['question'];?></label>
				<div class="controls">
					<span id="oad-reminderquestion" class="input-large uneditable-input"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-reminderquestion"><?php echo $lang['login_email'];?></label>
				<div class="controls">
					<input type="text" id="oad-reminderanswer" name="answer" autofocus
					       class="input-large" required placeholder="<?php echo $lang['answer'];?>">
				</div>
			</div>

			<div class="control-group">
				<div class="action-controls">
					<button type="submit" class="btn btn-inverse btn-block btn-large"><?php echo $lang['show_password'];?></button>
					<button id="oad-cancel-passwordreminder" type="button" class="btn btn-block"><?php echo $lang['register_cancel'];?></button>
				</div>
			</div>

		</form>

	</div>
</div>