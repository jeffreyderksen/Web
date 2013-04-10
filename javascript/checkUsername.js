function check(){
	
	var username = document.getElementById("uname").value;
	var xhr = new XMLHttpRequest();
	
	if(username!='')
	{
		
		
		var url = "http://localhost/web/html/frontend/usernameCheck.php?un=";
		url += username;
		xhr.onreadystatechange=function()
		  {
		  if (xhr.readyState==4 && xhr.status==200)
		    {
			  	var data = xhr.responseText;
				$('#status').text(data);
		    }
		  }
		
		xhr.open("GET",url,true);
		xhr.send();
		
		
		
		
	} else
	{
		$('#status').text('');
	}
	
}
