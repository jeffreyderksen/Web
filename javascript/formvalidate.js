// Deze code wordt gebruikt om te kijken of sommige velden van het register formulier juist ingevuld zijn.
$(document).ready(function() {
 // Door middel van een JQuery functie wordt het formulier gevalideerd
$('#signup').validate({
	// Stelt vast welke velden ingevuld moeten zijn
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
       	rangelength: [5,25] // Zorgt ervoor dat de username niet korter is dan 5 tekens en niet langer is dan 25 tekens
       },
	 email: 
	 {
        required: true,
        email: true // Kijkt of de juiste opmaak is gebruikt voor het email adres
     },
     pword: 
     {
        required: true,
        rangelength:[6,16] // Zorgt ervoor dat het password niet korter is dan 6 tekens en niet langer is dan 16 tekens
     },
     confirmpword: 
     {
    	 equalTo:'#pword' // Kijkt of het password in beide velden gelijk is.
     }
   }, //end rules
   // Hier worden de berichten opgesteld die weergeven worden als het veld niet aan de eisen voldoet
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
        rangelength: ' Password must be between 6 and 16 characters long.'
      },
      confirmpword: 
      {
        equalTo: ' The two passwords do not match.'
      }
   }, // end messages
   // Geeft opmaak van de berichten in het formulier aan. 
   errorPlacement: function(error,element)
   {
	   error.insertAfter(element); // Zorgt dat het bericht na een element wordt weergeven
   } // end errorPlacement
  }); // end validate
}); // end ready


