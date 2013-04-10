function checkPageExist() 
{
	var content_menu = document.getElementById("content_menu").value;
	var xhr = new XMLHttpRequest();

	if (content_menu != '') 
	{
		var url = "http://localhost/web/html/admin/include/check.php?content_menu=";
		url += content_menu;
		
		xhr.open("GET", url, true);
		xhr.send();
		
		//var query = "?un=" username;
		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState == 4 && xhr.status == 200) 
			{
				var data = xhr.responseText;
				if (data) 
				{
					$('#validation-text').html('<p style="color: green">Beschikbaar</p>');
					$('#button-submit').removeAttr("disabled");
				} 
				else 
				{
					$('#validation-text').html('<p style="color: red">Niet beschikbaar</p>');
					$('#button-submit').attr('disabled', 'true');
				}
			}
		}
	} 
	else
	{
		$('#validation-text').text('');
	}
}

/*function checkUsername()
{
	var member_uname = document.getElementById("member_uname").value;
	var xhr = new XMLHttpRequest();

	if (member_uname != '') 
	{
		var url = "http://localhost/web/html/include/check.php?member_uname=";
		url += member_uname;
		
		xhr.open("GET", url, true);
		xhr.send();
		
		//var query = "?un=" username;
		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState == 4 && xhr.status == 200) 
			{
				var data = xhr.responseText;
				if (data) 
				{
					$('#validation-text').html('<p style="color: green">Beschikbaar</p>');
					$('#submit').removeAttr("disabled");
				} 
				else 
				{
					$('#validation-text').html('<p style="color: red">Niet beschikbaar</p>');
					$('#submit').attr('disabled', 'true');
				}
			}
		}
	} 
	else
	{
		$('#validation-text').text('');
	}
}

function checkAdminUsername()
{
	var admin_uname = document.getElementById("admin_uname").value;
	var xhr = new XMLHttpRequest();

	if (admin_uname != '') 
	{
		var url = "http://localhost/web/html/include/check.php?admin_uname=";
		url += admin_uname;
		
		xhr.open("GET", url, true);
		xhr.send();
		
		//var query = "?un=" username;
		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState == 4 && xhr.status == 200) 
			{
				var data = xhr.responseText;
				if (data) 
				{
					$('#validation-text').html('<p style="color: green">Beschikbaar</p>');
					$('#submit').removeAttr("disabled");
				} 
				else 
				{
					$('#validation-text').html('<p style="color: red">Niet beschikbaar</p>');
					$('#submit').attr('disabled', 'true');
				}
			}
		}
	} 
	else
	{
		$('#validation-text').text('');
	}
}*/