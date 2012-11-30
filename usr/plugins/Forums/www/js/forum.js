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