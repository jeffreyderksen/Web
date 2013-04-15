// Deze functie wordt opgeroepen zodra er getypt wordt in de username input van het register formulier
function check(){
	
	var username = document.getElementById("uname").value;
	var xhr = new XMLHttpRequest(); // Maakt een object die de verbinding tussen javascript en php mogelijk maakt 
	
	if(username!='') // Kijkt of er iets ingevoerd is in het veld
	{		
		// Stuurt de username naar usernameCheck.php en vraagt een response op.
		var url = "http://jefderk.nl/gametriangle/html/frontend/usernameCheck.php?un=";
		url += username;
		xhr.onreadystatechange=function()
		  {
		  if (xhr.readyState==4 && xhr.status==200)
		    {
			  	var data = xhr.responseText;
				$('#status').text(data); // Het bericht of de username wel/niet in gebruik is, wordt weergeven in een div met id status. Deze is normaal leeg
		    }
		  }
		
		xhr.open("GET",url,true);
		xhr.send();		
	} else // Als het veld leeg is, is div met id status leeg.
	{
		$('#status').text('');
	}
	
}
