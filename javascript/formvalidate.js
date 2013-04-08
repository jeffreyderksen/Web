$(document).ready(function() {
 $('#signup').validate({
 rules: {
     fname: 
     {
    	required: true
     },
     lname: 
     {
     	required: true
      },
      uname: 
      {
       	required: true,
       	rangelength: [5,25]
       },
	 email: 
	 {
        required: true,
        email: true
     },
     pword: 
     {
        required: true,
        rangelength:[8,16]
     },
     confirmpword: 
     {
    	 equalTo:'#pword'
     }
   }, //end rules
   messages: 
   {
	   fname: 
	   {
	        required: ' Please type in your first name'
	   },
	   lname: 
	   {
	        required: ' Please type in your last name'
	   },
	   uname: 
	   {
	        required: ' You need to submit a username for your account',
	        rangelength: ' Your username must be between 5 and 25 characters long'
	   },
	   email: 
	   {
         required: " Please supply an e-mail address.",
         email: " This is not a valid email address."
       },
      pword: 
      {
        required: ' Please type a password',
        rangelength: ' Password must be between 8 and 16 characters long.'
      },
      confirmpword: 
      {
        equalTo: ' The two passwords do not match.'
      }
   }, // end messages
   errorPlacement: function(error,element)
   {
	   error.insertAfter(element);
   } // end errorPlacement
  }); // end validate
}); // end ready


