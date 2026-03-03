$(document).ready(function(){

	var current_fs, next_fs, previous_fs; //fieldsets
	var opacity;
	var current = 1;
	var steps = $("fieldset").length;

	// hide ajax loading gif
	$('#ajaxLoading').hide();

	setProgressBar(current);
	// custom arguments for jquery validation
  	let args = {
      // Specify validation rules
      rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        name: "required",
        username: "required",
        email: { required: true, email: true },
        password: { required: true, minlength: 8},
        confirm_password: { required: true, equalTo: "#password" },
        school_id: { required: true, minlength: 1 },
        department: "required",
        program_type: { required: true, minlength: 1 },
        level: {required: true, number: true },
        phone: {required: true, number: true},

      },
      // Specify validation error messages
      messages: {
        name: "Enter your full name",
        username: "Please choose username",
        pass: {
          required: "Please provide a password",
        },
        confirm_password: {
          required: "Confirm your password",
          equalTo: "Password didn't match"
        },
        email: "Please enter a valid email address",
        school_id: "Please select a school",
        program_type: "Please select",
        level: {
          required: "Please enter current level",
          number: "Must be a valid number"
        },
        phone: {
          required: "Enter phone number",
          number: "Must be a valid phone number"
        },
      },

      // onkeyup: function(element) {$(element).valid()},
      // onfocusout: function(element) {$(element).valid()},
      // onChange: function(element) {$(element).valid()},
      // Make sure the form is submitted to the destination defined
      // in the "action" attribute of the form when valid
      submitHandler: function(form) {
        form.submit();
      }
    }

  var form = $("form[name='student-signup-msform']");
  form.validate(args)
  toastr.options = {
        preventDuplicates: true,
        positionClass: "toast-bottom-left",
        timeOut: 0,
    }

	$(".next").click(function(){
		if(form.valid()){
			current_fs = $(this).parent();
			next_fs = $(this).parent().next();

			//Add Class Active
			$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

			//show the next fieldset
			next_fs.show();
			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now) {
					// for making fielset appear animation
					opacity = 1 - now;

					current_fs.css({
						'display': 'none',
						'position': 'relative'
					});
					next_fs.css({'opacity': opacity});
				},
				duration: 500
			});
			setProgressBar(++current);
		}else{
			console.log(form.validate(args).valid())
		}
	});

	$(".previous").click(function(){

		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();

		//Remove class active
		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

		//show the previous fieldset
		previous_fs.show();

		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
		step: function(now) {
		// for making fielset appear animation
		opacity = 1 - now;

		current_fs.css({
		'display': 'none',
		'position': 'relative'
		});
		previous_fs.css({'opacity': opacity});
		},
		duration: 500
		});
		setProgressBar(--current);
	});

	function setProgressBar(curStep){
		var percent = parseFloat(100 / steps) * curStep;
		percent = percent.toFixed();
		$(".progress-bar").css("width",percent+"%")
	}

		
	$("#submit").click(function(){
		var terms = form.find('input[name="terms"]');
		if(!terms.is( ":checked")){
			toastr.info("You need to accept our terms and conditions")
			return false	
		}
		return ajaxSubmit(form);
	})

	function ajaxSubmit(form) {
			var formValues= form.serialize();
			console.log(formValues);

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},

			});

			$.ajax({
				url: window.location.origin + '/fastneutron/check',
				method: 'POST',
				data: formValues,
				dataType: "json",
				encode: true,

				beforeSend: function() {
				    $('#ajaxLoading').show();
				 },

				complete: function(){
			     	$('#ajaxLoading').hide();
			  	},
				
				success: function(res){
					console.log(res)
					toastr.success("account created successfully")
					window.location.replace(window.location.origin + '/dashboard' );
				},

				error: function(err){
					console.log(err)
					toastr.error('Please try again');
				 	validationErrors = err.responseJSON.errors
	                // console.log(err.responseJSON.errors)
	            	for(error in validationErrors){
	                	toastr.error(validationErrors[error][0]);
	            	}
				}
			});
		
	}

});