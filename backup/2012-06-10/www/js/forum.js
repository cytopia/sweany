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
function insertBBTag(elId, aTag, eTag)
{
	var input = document.getElementById(elId);
	input.focus();
	
	/* Internet Explorer */
	if (typeof document.selection != 'undefined')
	{
		/* 01) Insert format code */
		var range	= document.selection.createRange();
		var insText	= range.text;
		range.text	= aTag + insText + eTag;
		/* 02) Adjust cursor position */
		range		= document.selection.createRange();
		
		if (insText.length == 0)
		{
			range.move('character', -eTag.length);
		}
		else
		{
			range.moveStart('character', aTag.length + insText.length + eTag.length);      
		}
		range.select();
	}
	/* Gecko-based Browser */
	else if(typeof input.selectionStart != 'undefined')
	{
		/* 01) Insert format code */
		var start	= input.selectionStart;
		var end		= input.selectionEnd;
		var insText = input.value.substring(start, end);
		input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);
		/* 02) Adjust cursor position */
		var pos;
		if (insText.length == 0)
		{
			pos = start + aTag.length;
		}
		else
		{
			pos = start + aTag.length + insText.length + eTag.length;
		}
		input.selectionStart = pos;
		input.selectionEnd = pos;
	}
	/* All other Browsers*/
	else
	{
		/* 01). Check Insert position */
		var pos;
		var re = new RegExp('^[0-9]{0,3}$');
		while (!re.test(pos))
		{
			pos = prompt("Einfügen an Position (0.." + input.value.length + "):", "0");
		}
		if (pos > input.value.length)
		{
			pos = input.value.length;
		}
		/* 02) Insert format code */
		var insText = prompt("Bitte geben Sie den zu formatierenden Text ein:");
		input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value.substr(pos);
	}
}


function quickEditThread(threadId)
{
	var threadEl = document.getElementById('startThread');
	
	MakePOSTRequest("/Forums/ajax_get_quick_edit_thread_box/"+threadId, "threadId="+threadId, function(quickEditBox)
	{
		if (quickEditBox.length>3)
		{
			threadEl.innerHTML = quickEditBox;
		}
	});
}
function submitEditThread(threadId)
{
	var threadEl = document.getElementById('startThread');
	var body = document.getElementById('quickEditBoxText').value;

	MakePOSTRequest("/Forums/ajax_edit_thread/"+threadId, "threadId="+threadId+"&body="+body, function(newBodyToDisplay)
	{
		// something went wrong, we are receiving an error code
		// so just reload the page
		if ( !isNaN(newBodyToDisplay) )
		{
			window.location.reload();
		}
		// all right, update the post with the new update
		else
		{
			threadEl.innerHTML = newBodyToDisplay;
		}
	});
}

function quickEditPost(postId)
{
	var postEl = document.getElementById('post_'+postId);
	
	MakePOSTRequest("/Forums/ajax_get_quick_edit_post_box/"+postId, "postId="+postId, function(quickEditBox)
	{
		if (quickEditBox.length>3)
		{
			postEl.innerHTML = quickEditBox;
		}
	});
}
function submitEditPost(postId)
{
	var postEl = document.getElementById('post_'+postId);
	var body = document.getElementById('quickEditBoxText').value;

	MakePOSTRequest("/Forums/ajax_edit_post/"+postId, "postId="+postId+"&body="+body, function(newBodyToDisplay)
	{
		// something went wrong, we are receiving an error code
		// so just reload the page
		if ( !isNaN(newBodyToDisplay) )
		{
			window.location.reload();
		}
		// all right, update the post with the new update
		else
		{
			postEl.innerHTML = newBodyToDisplay;
		}
	});
}
function cancelEdit()
{
	window.location.reload();
}