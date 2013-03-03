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
		ns.messagebar          = $('#oad-page-message-bar');
		ns.messagebar_alert    = $('#oad-messagebar-notify');
		ns.messagebar_error    = $('#oad-messagebar-error');

		// Start off showing login
		ns.showLoginForm();

		// Handler for register button
		$('#oad-open-registration').on('click', function(e){
			ns.showRegistrationForm();
		});

		// Handler for cancel button
		$('#oad-cancel-registration').on('click', function(e){
			ns.showLoginForm();
		});

		// Alert/error close handler
		$('button.close', ns.messagebar_alert).on('click', function(){
			ns.messagebar_alert.fadeOut('fast');
		});
		$('button.close', ns.messagebar_error).on('click', function(){
			ns.messagebar_error.fadeOut('fast');
		});

		// Registration handler
		ns.registerform.on('submit', function(e){
			e.preventDefault();
			ns.doRegistration();
			return false;
		});

	});

	// Perform a login
	ns.doRegistration = function(){

		// Dim form and action button
		this.viewarea.css('opacity', .3);

		// Make request
		$.ajax({
			"url"           : 'php/register-newuser.php',
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
					consega.showError(data.error);
				} else {

					// Show the login form, populate the fields and post
					consega.showLoginForm();
					$('input[name=username]', consega.loginform).val(data.username);
					$('input[name=password]', consega.loginform).val(data.password);
					$('button[type=submit]', consega.loginform).click();

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

	// Show an alert
	ns.showAlert = function(v_msg){
		this.messagebar_alert.fadeIn()
			.find('span').html(v_msg);
	};

	// Show an error
	ns.showError = function(v_msg){
		this.messagebar_error.fadeIn()
			.find('span').html(v_msg);
	};

})(consega);
