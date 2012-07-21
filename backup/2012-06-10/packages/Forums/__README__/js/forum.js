function add_link()
{
	var link = prompt("Please enter the url?","http://");
	return "[url="+link+"]"+link+"[/url]";
}
function add_img()
{
	var link = prompt("Please enter the url of the picture?","http://");
	return "[img]"+link+"[/img]";
}