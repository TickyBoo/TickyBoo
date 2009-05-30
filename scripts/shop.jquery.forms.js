$(document).ready(function(){
	$("#update_user").validate(update_user);
	//$(":input[name='user_phone']").mask("00000000000000");
	
});

var update_user = {
	rules: {
		user_firstname : {
			required :true
		},
		user_lastname : {
			required : true
		},
		user_address : {
			required : true
		},
		user_zip : {
			required : true
		},
		user_city : {
			required : true
		},
		user_phone : {
			minlength : 9,
			maxlength : 14
		},
		user_fax : {
			minlength : 9,
			maxlength : 14
		},
		user_email : {
			required : true,
			email :true
		},
		old_password : {
			required : true,
			minlength : 6
		}
		
	},
	messages : {
		user_firstname : {
			required : "Required"
		},
		user_lastname : {
			required : "Required"
		},
		user_address : {
			required : "Required"
		},
		user_zip : {
			required : "Required"
		},
		user_city : {
			required : "Required"
		},
		user_phone : {
			minlength : "The phone number is too short",
			maxlength : "The phone number is too long"
		},
		user_fax : {
			minlength : "The fax number is too short",
			maxlength : "The fax number is too long"
		},
		user_email : {
			required : "Required",
			email : "Please enter a valid email address"
		},
		old_password : {
			required : "Required",
			minlength : "Your Password is too short."
		}
	},
	errorClass: "form-error",
	success: function(label) {
		label.addClass("form-valid").text("Valid")
   	},

	
};