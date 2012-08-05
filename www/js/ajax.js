// TODO: maybe try this: http://photomunchers.appspot.com/js/util.js


// ------------------------------ PRIVATE TO GET THE REQUEST OBJECT ------------------------------ //
function createXmlHttpRequest()
{
	var xmlHttp = null;
	
	try
	{
		//Firefox, Opera 8.0+, Safari
		xmlHttp = new XMLHttpRequest();
	}
	catch(e)
	{
		//Internet Explorer
		try
		{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e) { }
		}
	}
	return xmlHttp;
}


function handleResponse(request)
{
	// TODO: parse request.responseXML here
	return "asds";
}


//------------------------------ THE ACTUAL CALL ------------------------------ //
function MakeGETRequest(url, callback)
{
	var request = createXmlHttpRequest();

	if (!request)
	{
		return false;
	}
	
	request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			callback(request.responseText);
		}
	};
	request.open("GET", url, true); 
	try
	{
		request.send(null);
	}
	catch (e) { }
}

function MakePOSTRequest(url, params, callback)
{
	var request = createXmlHttpRequest();

	if (!request)
	{
		return false;
	}
	
	request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			callback(request.responseText);
		}
	};
	
	request.open("POST", url, true); 

	//Send the proper header information along with the request
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");	

	try
	{
		request.send(params);
	}
	catch (e) { }
}

function liveSearch(url, params, textBoxId, resultId)
{
	elBox	= document.getElementById(textBoxId);
	el		= document.getElementById(resultId);
	MakePOSTRequest(url, params, function(response)
	{
		el.style.visibility ='visible';	// show
		el.style.display='block';
		el.innerHTML = '';				// empty element
		
		var result = eval('(' + response + ')');
		for (var i=0;i<result.length; i++)
		{
			var uniqueId = 'uniqueName'+i;
			var line = '<div id="'+uniqueId+'" class="livesearchResultRows" onclick="document.getElementById(\''+textBoxId+'\').value=document.getElementById(\''+uniqueId+'\').innerHTML; document.getElementById(\''+resultId+'\').style.display=\'none\';" >'+result[i]+"</div>";
			el.innerHTML += line;
		}
	});
}

/************************************** CURSOR FUNCTIONS **************************************/
/* return the position of the cursor */
function getCaretPosition (oField)
{
	// Initialize
	var iCaretPos = 0;

	// IE Support
	if (document.selection)
	{
		// Set focus on the element
		oField.focus ();
		// To get cursor position, get empty selection range
		var oSel = document.selection.createRange ();
		// Move selection start to 0 position
		oSel.moveStart ('character', -oField.value.length);
		// The caret position is selection length
		iCaretPos = oSel.text.length;
	}
	// Firefox support
	else if (oField.selectionStart || oField.selectionStart == '0')
	{
		iCaretPos = oField.selectionStart;
	}
	// Return results
	return (iCaretPos);
}
function setCaretPosition (oField, iCaretPos)
{
	// IE Support
	if (document.selection)
	{
		// Set focus on the element
		oField.focus ();
	
		// Create empty selection range
		var oSel = document.selection.createRange ();
		// Move selection start and end to 0 position
		oSel.moveStart ('character', -oField.value.length);
	
		// Move selection start and end to desired position
		oSel.moveStart ('character', iCaretPos);
		oSel.moveEnd ('character', 0);
		oSel.select ();
	}
	// Firefox support
	else if (oField.selectionStart || oField.selectionStart == '0')
	{
		oField.selectionStart = iCaretPos;
		oField.selectionEnd = iCaretPos;
		oField.focus ();
	}
}
