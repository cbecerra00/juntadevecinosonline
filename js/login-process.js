$(document).ready(function(){
	/* handling form validation */
	$("#login-form").validate({
		rules: {
			password: {
				required: true,
			},
			username: {
				required: true,
				email: true
			},
		},
		messages: {
			password:{
			  required: "Please enter your password"
			 },
			username: "Please enter your email address",
		},
		submitHandler: submitForm	
	});	

	/* Handling login functionality */
	function submitForm() {		
		var data = $("#login-form").serialize();
		$.ajax({				
			type : 'POST',
			url  : 'login-process.php',
			data : data,
			beforeSend: function(){	
				$("#error").fadeOut();
				$("#btn-login").html('<span class="fa fa-spinner"></span> &nbsp; sending ...');
			},
			success : function(response){			
				if($.trim(response) === "1"){
					console.log('dddd');									
					$("#error").html('<div class="alert alert-success"> <i class="fa fa-check-circle"></i> &nbsp; Signing In... !</div>').show();
					//setTimeout(' window.location.href = "dashboard.php"; ',2000);
					window.location.href = "dashboard.php?c=1";
				} else {	
					$("#error").fadeIn(1000, function(){						
						$("#error").html('<div class="alert alert-danger"> <i class="fa fa-exclamation-circle"></i> &nbsp; '+response+' !</div>').show();
					});
				}
			}
		});
		return false;
	}
});