/**
 * Application namespace
 */
window.consega = {};
(function(ns) {

	$(document).ready(function(){

		// Widgets
		ns.viewarea            = $('#viewarea');
		ns.loginform           = $('#loginform');
		ns.registerform        = $('#registrationform');
		ns.reminderform        = $('#passwordremindform');
		ns.messagebar          = $('#oad-page-message-bar');
		ns.messagebar_alert    = $('#oad-messagebar-notify');
		ns.messagebar_error    = $('#oad-messagebar-error');

		// Start off showing login
		ns.showLoginForm();

		// Detect existing credentials
		var cookieauth = $.cookie(ns.cookiename);
		if (cookieauth){
			try {
				var auth = $.parseJSON(cookieauth);

				// Attempt login using stored credentials
				consega.showLoginForm();
				$('input[name=username]', consega.loginform).val(auth.username);
				$('input[name=password]', consega.loginform).val(auth.password);
				$('button[type=submit]', consega.loginform).html(consega.lang.loggingin_title).click();

			} catch(e){
				// Invalid cookie, remove it
				$.removeCookie(ns.cookiename);
			}


		}

		// Handler for register button
		$('#oad-open-registration').on('click', function(e){
			ns.showRegistrationForm();
		});

		// Handler for cancel buttons
		$('#oad-cancel-registration, #oad-cancel-passwordreminder').on('click', function(e){
			ns.showLoginForm();
		});

		// Handler for forgot password button
		$('#oad-open-forgotpassword').on('click', function(){
			ns.showForgotPasswordForm();
		});

		// Alert/error close handler
		$('button.close', ns.messagebar_alert).on('click', function(){
			ns.messagebar_alert.fadeOut('fast', function(){
				consega.messagebar.addClass('hidden');
			});
		});
		$('button.close', ns.messagebar_error).on('click', function(){
			ns.messagebar_error.fadeOut('fast', function(){
				consega.messagebar.addClass('hidden');
			});
		});

		// Registration handler
		ns.registerform.on('submit', function(e){
			e.preventDefault();
			ns.doRegistration();
			return false;
		});

		// Forgot password form handler
		ns.reminderform.on('submit', function(e){
			e.preventDefault();
			ns.showPassword();
			return false;
		});

	});

	// Perform a login
	ns.doRegistration = function(){

		// Dim form and action button
		this.viewarea.css('opacity', .3);

		// Make request
		$.ajax({
			"url"           : $('form', this.registerform).attr('action'),
			"type"          : 'post',
			"dataType"      : 'json',
			"data"      : $('form:eq(0)', this.registerform).serialize(),
			"error"     : function(a, b, c){
				consega.showError('Communication error: '.c);
			},
			"complete"  : function(){
				consega.viewarea.css('opacity', 1);
			},
			"success"   : function(data){
				if (!data.success){
					if (data.show_login){
						consega.showLoginForm();
						$('input[name=username]', consega.loginform).val(data.username);
					}
					consega.showError(data.error);
				} else {

					// Show the login form, populate the fields and post
					consega.showLoginForm();
					$('input[name=username]', consega.loginform).val(data.username);
					$('input[name=password]', consega.loginform).val(data.password);
					$('button[type=submit]', consega.loginform).html(consega.lang.loggingin_title).click();

				}
			}
		});
	}

	// Show the users password
	ns.showPassword = function(){

		// Grab some stuff
		var data = {
			"res"       : 'notyet',
			"isxhr"     : 1,
			"action"    : 'fetchpassword',
			"username"  : $('input[name=username]', this.loginform).val(),
			"answer"    : $('#oad-reminderanswer', this.reminderform).val()
		};

		// Check an answer has been given
		if (data.answer.length == 0){
			this.showError(this.lang.enter_reminderanswer);
			return false;
		}

		// Dim form and action button
		this.viewarea.css('opacity', .3);

		// Make request
		$.ajax({
			"url"           : $('form', this.loginform).attr('action'),
			"type"          : 'get',
			"dataType"      : 'json',
			"data"          : data,
			"error"         : function(a, b, c){
				consega.showError('Communication error: '.c);
			},
			"complete"      : function(){
				consega.viewarea.css('opacity', 1);
			},
			"success"       : function(data){
				if (!data.success){
					consega.showError(data.error);
				} else {

					// Show the password in a modal
					$('#oad-remind-showpassword').html(data.password);
					$('#oad-password-modal-close').off('click').on('click', function(e){
						e.preventDefault();
						$('#oad-password-modal').modal('hide');

						// Auto-login
						consega.showLoginForm();
						$('input[name=password]', consega.loginform).val(data.password);
						$('button[type=submit]', consega.loginform)
							.html(consega.lang.loggingin_title).click();

						return false;
					});
					$('#oad-password-modal').modal({
						"backdrop"      : true
					});

				}
			}
		});

	}

	// Show the registration form
	ns.showRegistrationForm = function(){
		$('.userview', this.viewarea).hide();
		this.registerform.fadeIn('slow');
	};

	// Show the login form
	ns.showLoginForm = function(){
		$('.userview', this.viewarea).hide();
		this.loginform.fadeIn('slow');
	};

	// Show the password reminder form
	ns.showForgotPasswordForm = function(){

		// Check we have an email address
		var username = $('input[name=username]', consega.loginform).val();
		if (username.length == 0){
			this.showError(this.lang.enter_username);
			return false;
		}

		// Dim form and action button
		this.viewarea.css('opacity', .3);

		// Fetch the security question
		$.ajax({
			"url"           : $('form', this.loginform).attr('action'),
			"type"          : 'get',
			"dataType"      : 'json',
			"data"          : {
				"res"       : 'notyet',
				"isxhr"     : 1,
				"action"    : 'getsecurityquestion',
				"username"  : username
			},
			"error"     : function(a, b, c){
				consega.showError('Communication error: '.c);
			},
			"complete"  : function(){
				consega.viewarea.css('opacity', 1);
			},
			"success"   : function(data){
				if (!data.success){
					consega.showError(data.error);
				} else {

					// Show the password reminder form
					$('#oad-reminderquestion').html(data.question);
					$('.userview', consega.viewarea).hide();
					consega.reminderform.fadeIn('slow');

				}
			}
		});

	};

	// Show an alert
	ns.showAlert = function(v_msg){
		this.messagebar_alert.fadeIn().removeClass('hidden').addClass('in')
			.find('span').html(v_msg);
		this.messagebar.removeClass('hidden');
		this.scrollTo(this.messagebar.position().top);
	};

	// Show an error
	ns.showError = function(v_msg){
		this.messagebar_error.fadeIn().removeClass('hidden').addClass('in')
			.find('span').html(v_msg);
		this.messagebar.removeClass('hidden');
		this.scrollTo(this.messagebar.position().top);
	};

	// Scroll the body element
	ns.scrollTo = function(v_top, o_scrolloptions){
		var a_scrolloptions = $.extend({
			"duration"      : 400,
			"easing"        : 'swing'
		}, o_scrolloptions);
		$('html, body').animate(
			{"scrollTop": v_top},
			a_scrolloptions.duration,
			a_scrolloptions.easing
		);
		return this;
	} // scrollTo()

})(consega);
