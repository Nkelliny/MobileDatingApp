function getStatesAff(country)
{
	var params = "country=" + country;
	var url = "/ajax/getStatesAff";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length",params.length);
	http.setRequestHeader("Connection","Close");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			document.getElementById('states').innerHTML = html;
		}
	}
	http.send(params);
}

function getCitiesAff(sid)
{
	var country = document.getElementById('country').value;
	var state = sid;
	var params = "country=" + country + "&state=" + state;
	var url = "/ajax/getCitiesAff";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length",params.length);
	http.setRequestHeader("Connection","Close");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			document.getElementById('cities').innerHTML = html;
		}
	}
	 http.send(params);
}

function checkEmailAff()
{
	var mailCheck = document.getElementById('jemail').value;
	document.getElementById('ckmail').innerHTML = 'Checking Email...';
	if(mailCheck != null && mailCheck != "")
	{
		var params = "em="+mailCheck;
		var url = "/ajax/checkEmail";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.setRequestHeader("Content-length",params.length);
		http.setRequestHeader("Connection","Close");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var res = http.responseText;
				if(res == "ok")
				{
					document.getElementById('ckmail').innerHTML = '<img src="/images/icons/ok.png" width="16" height="16" border="0" />';
					document.getElementById('joinBtn').disabled = false;
				}
				else
				{
					document.getElementById('ckmail').innerHTML = '<br /><img src="/images/icons/notok.png" width="16" height="16" border="0" /> Email is in use, please choose a different email.';
				}
			}
		}
		http.send(params);
	}
	else
	{
		document.getElementById('ckmail').innerHTML = '<br/><img src="/images/icons/notok.png" width="16" height="16" border="0" /> Please enter an email address!';
	}
}