$(window).load(function(){

	$("#update_user").validate({
		rules: {
			user_firstname	: 	{ required :true },
			user_lastname	:	{ required : true },
			user_address	:	{ required : true },
			user_zip 	:	{ required : true },
			user_city 	: 	{ required : true },
			user_phone 	: 	{ minlength : 9, maxlength : 14 },
			user_fax 	: 	{ minlength : 9,	maxlength : 14 },
			user_email 	: 	{ required : true, email :true },
			old_password 	: 	{ required : true, minlength : 6 }
		},
		messages : {
			user_firstname : { required : lang.required },
			user_lastname : { required : lang.required	},
			user_address : { required : lang.required },
			user_zip : { required : lang.required },
			user_city : { required : lang.required	},
			user_phone : { minlength : lang.phone_short, maxlength : lang.phone_long },
			user_fax : { minlength : lang.fax_short, maxlength : lang.fax_long },
			user_email : { required : lang.required, email : "Please enter a valid email address" },
			old_password : { required : lang.required,	minlength : lang.pass_short },
		},
		errorClass: "form-error",
		success: "form-valid"
	});
	$("#user-register").validate({
		rules: {
			user_firstname	: 	{ required :true },
			user_lastname	:	{ required : true },
			user_address	:	{ required : true },
			user_zip 	:	{ required : true },
			user_city 	: 	{ required : true },
			user_phone 	: 	{ digits : true, minlength : 9, maxlength : 14 },
			user_fax 	: 	{ digits : true, minlength : 9, maxlength : 14 },
			user_email 	: 	{ required : true, email :true },
			user_email2 	: 	{ required : true, email :true, equalTo : "#email" },
			password1 	: 	{ required : true, minlength : 6 },
			password2 	: 	{ required : true, minlength : 6, equalTo: "#password" },
			user_nospam :	{ required : true },
			check_condition	:	{ required : true}
		},
		messages : {
			user_firstname : { required : lang.required },
			user_lastname : { required : lang.required	},
			user_address : { required : lang.required },
			user_zip : { required : lang.required },
			user_city : { required : lang.required	},
			user_phone : { minlength : lang.phone_short, maxlength : lang.phone_long, digits : lang.not_number },
			user_fax : { minlength : lang.fax_short, maxlength : lang.fax_long, digits : lang.not_number },
			user_email : { required : lang.required, email : lang.email_valid },
			user_email2 : { required : lang.required, email : lang.email_valid, equalTo: lang.email_match },
			password1 : { required : lang.required,	minlength : lang.pass_short },
			password2 : { required : lang.required,	minlength : lang.pass_short, equalTo: lang.pass_match },
			user_nospam :	{ required : lang.required },
			check_condition	:	{ required : "Please accept the terms"}
		},
		errorClass: "form-error",
		success: "form-valid",
		errorPlacement: function(error, element) {
	 		if (element.attr("name") == "check_condition")
		   		error.insertAfter("#condition_link");
		 	else
		   		error.insertAfter(element);
		}
	});
	$("#user-login").validate({
		rules: {
			password : { required : true, minlength : 6 },
			username : { required : true, email :true }
		},
		messages : {
			password : { required : " ", minlength : " " },
			username : { required : " ", email : " " }	
		},
		errorClass: "form-error"
		
	});
	//$(":input[name='user_phone']").mask("00000000000000");
	$(":input[type='submit']").addClass("submit");
});