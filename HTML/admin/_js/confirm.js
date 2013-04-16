//confirm bericht bij actie's die uitgevoerd worden.
function confirmMessage(t)
{
	var c = confirm("Are you sure?");
	
	if(c)
	{
		t.form.submit();
	}
}