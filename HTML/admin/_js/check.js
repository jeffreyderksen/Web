//functie die bekijkt of de pagina die de administrator toe wilt voegen bestaat in de database
function checkPageExist() 
{
	var content_menu = document.getElementById("content_menu").value;
	var xhr = new XMLHttpRequest();

	if (content_menu != '') 
	{
		var url = "http://jefderk.nl/gametriangle/html/admin/include/check.php?content_menu=";
		url += content_menu;
		
		xhr.open("GET", url, true);
		xhr.send();
		
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