<div id="registrationform" class="row-fluid userview userform">
	<div class="span12 label label-inverse align-center">

		<!-- Greeting first.. -->
		<h1><?php echo $lang['welcome_title'];?></h1>
		<div class="alert alert-warning"><h3><?php echo $lang['please_register'];?></h3></div>
		<form class="registration form-horizontal" action="hotspotlogin.php" method="post">

			<input type="hidden" name="isxhr" value="1" />
			<input type="hidden" name="action" value="register" />
			<input type="hidden" name="res" value="notyet" />

			<div class="control-group">
				<label class="control-label" for="oad-firstname">
					<span class="required">*</span>
					<?php echo $lang['firstname'];?>
				</label>
				<div class="controls">
					<input type="text" id="oad-firstname" name="firstname" autofocus
					       class="input-large" required placeholder="<?php echo $lang['firstname'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-lastname">
					<span class="required">*</span>
					<?php echo $lang['lastname'];?>
				</label>
				<div class="controls">
					<input type="text" id="oad-lastname" class="input-large" name="lastname"
					       required placeholder="<?php echo $lang['lastname'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-age"><?php echo $lang['age'];?></label>
				<div class="controls">
					<input type="number" id="oad-age" class="input-large" name="age" min="1" max="100"
					       placeholder="<?php echo $lang['age'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-gender"><?php echo $lang['gender'];?></label>
				<div class="controls">
					<select id="oad-gender" class="input-large" placeholder="ff" name="gender">
						<option value=""><?php echo $lang['gender'];?></option>
						<option value="M"><?php echo $lang['gender_male'];?></option>
						<option value="F"><?php echo $lang['gender_female'];?></option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-telephone"><?php echo $lang['telephone'];?></label>
				<div class="controls">
					<input type="tel" id="oad-telephone" class="input-large" name="telephone"
					       placeholder="<?php echo $lang['telephone'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-email">
					<span class="required">*</span>
					<?php echo $lang['email'];?>
				</label>
				<div class="controls">
					<input type="email" id="oad-email" class="input-large" name="email"
					       required placeholder="<?php echo $lang['email'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-password">
					<span class="required">*</span>
					<?php echo $lang['password'];?>
				</label>
				<div class="controls">
					<input type="password" id="oad-password" class="input-large" name="password"
					       required placeholder="<?php echo $lang['password'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-question">
					<span class="required">*</span>
					<?php echo $lang['question'];?>
				</label>
				<div class="controls">
					<input type="text" id="oad-question" class="input-large" name="question"
					       required placeholder="<?php echo $lang['question'];?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="oad-answer">
					<span class="required">*</span>
					<?php echo $lang['answer'];?>
				</label>
				<div class="controls">
					<input type="text" id="oad-answer" class="input-large" name="answer"
					       required placeholder="<?php echo $lang['answer'];?>">
				</div>
			</div>
			<div class="control-group">
				<div class="action-controls">
					<button type="submit" class="btn btn-inverse btn-block btn-large"><?php echo $lang['register'];?></button>
					<button id="oad-cancel-registration"  type="button" class="btn btn-block"><?php echo $lang['register_cancel'];?></button>
				</div>
			</div>
		</form>

	</div>
</div>