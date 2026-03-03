$(document).ready(function(){

  //jQuery time
  var current_fs, next_fs, previous_fs; //fieldsets
  var left, opacity, scale; //fieldset properties which we will animate
  var animating; //flag to prevent quick multi-click glitches
    // hide ajax loading gif
  $('#ajaxLoading').hide();


// custom arguments for jquery validation
  let args = {
      // Specify validation rules
      rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        name: "required",
        email: {
          required: true,
          // Specify that email should be validated
          // by the built-in "email" rule
          email: true
        },
        password: {
          required: true,
          minlength: 8
        },

        confirm_password: {
            required: true,
            equalTo: "#pass"
        }
      },
      // Specify validation error messages
      messages: {
        name: "Please enter your firstname",
        pass: {
          required: "Please provide a password",
        },

        confirm_password: {
          required: "Please confirm your password",
          equalTo: "Password didn't match"
        },
        email: "Please enter a valid email address"
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

  var form = $("form[name='sponsor-signup']");
  form.validate(args)
  toastr.options = {
      preventDuplicates: true,
      positionClass: "toast-bottom-left",
      timeOut: 0,
    }


  $(".next").click(function(){
    

    // form.validate(args)

    if(form.valid()){
      if(animating) return false;
      animating = true;
    
      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //activate next step on progressbar using the index of next_fs
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
      
      //show the next fieldset
      next_fs.show(); 
      //hide the current fieldset with style
      current_fs.animate({opacity: 0}, {
        step: function(now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring next_fs from the right(50%)
          left = (now * 50)+"%";
          //3. increase opacity of next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'transform': 'scale('+scale+')',
            'position': 'absolute'
          });
          next_fs.css({'left': left, 'opacity': opacity});
        }, 
        duration: 800, 
        complete: function(){
          current_fs.hide();
          animating = false;
        }, 
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    }else{
      console.log(form.validate(args).valid())
      // return false
    }
  });

  $(".previous").click(function(){
    if(animating) return false;
    animating = true;
    
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();
    
    //de-activate current step on progressbar
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
    
    //show the previous fieldset
    previous_fs.show(); 
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
      step: function(now, mx) {
        //as the opacity of current_fs reduces to 0 - stored in "now"
        //1. scale previous_fs from 80% to 100%
        scale = 0.8 + (1 - now) * 0.2;
        //2. take current_fs to the right(50%) - from 0%
        left = ((1-now) * 50)+"%";
        //3. increase opacity of previous_fs to 1 as it moves in
        opacity = 1 - now;
        current_fs.css({'left': left});
        previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
      }, 
      duration: 800, 
      complete: function(){
        current_fs.hide();
        animating = false;
      }, 
      //this comes from the custom easing plugin
      easing: 'easeInOutBack'
    });
  });

  $("#submit").click(function(){
   return ajaxSubmit(form);
    // return false;
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
        url: window.location.origin + '/signup-sponsor',
        method: 'POST',
        data: formValues,

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
})