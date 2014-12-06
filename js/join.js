function getStatesJoin(country)
{
	var params = "country=" + country;
	var url = "/ajax/getStates";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	//http.setRequestHeader("Content-length",params.length);
	//http.setRequestHeader("Connection","Close");
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

function ckEmail(val)
{
	var mailCheck = val;
	if(mailCheck != null && mailCheck != "")
	{
		var params = "em="+mailCheck;
		var url = "/ajax/checkEmail";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var res = http.responseText;
				if(res == "ok")
				{
					document.getElementById('epass').value = 'y';
					document.getElementById('email').style.backgroundColor = '#FFF';
				}
				else
				{
					document.getElementById('epass').value = 'n';
					alert('Email is in use, or not valid, please choose a different email.');
					document.getElementById('email').style.backgroundColor = "#FF0000";
				}
			}
		}
		http.send(params);
	}
}

function alphanumeric(inputtxt)  
{   
	var letters = /^[0-9a-zA-Z]+$/;  
	if(inputtxt.match(letters))  
	{   
		return true;  
	}  
	else  
	{   
		return false;  
	}  
}  

function ckNick(val)
{
	var numck = alphanumeric(val);
	if(numck)
	{
		// check str length
		var length = val.length;
		if(length <= 15 && length >= 4)
		{
			var params = "nick=" + val;
			var url = "/ajax/checknickname";
			var http = new XMLHttpRequest();
			http.open('POST',url,true);
			http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			http.onreadystatechange = function()
			{
				//alert(http.status);
				if(http.readyState == 4 && http.status == 200)
				{
					var html = http.responseText;
					if(html != "ok")
					{
						document.getElementById('npass').value = 'n';
						alert(html);
						document.getElementById('nickname').style.backgroundColor = "#FF0000";
					}
					else
					{
						document.getElementById('npass').value = 'y';
						document.getElementById('nickname').style.backgroundColor = "#FFF";
					}
				}
			}
			http.send(params);
		}
		else
		{
			document.getElementById('npass').value = 'n';
			alert('Your nickname needs to be between 4 and 15 letters / numbers.');
			document.getElementById('nickname').style.backgroundColor = "#FF0000";
		}
	}
	else
	{
		document.getElementById('npass').value = 'n';
		alert('Please only letters and numbers for your nickname.');
		document.getElementById('nickname').style.backgroundColor = "#FF0000";
	}
}

function ckPass(val)
{
	var length = val.length;
	if(length < 6 && length > 12)
	{
		document.getElementById('ppass').value = 'n';
		document.getElementById('pass').style.backgroundColor = '#FF0000';
	}
	else
	{
		document.getElementById('ppass').value = 'y';
		document.getElementById('pass').style.backgroundColor = '#FFF';
	}
}

function ckHead(val)
{
	// check email
	ckEmail(document.getElementById('email').value);
	// check username
	ckNick(document.getElementById('nickname').value);
	// check password
	ckPass(document.getElementById('pass').value);
	var msg = 'ok';
	if(val != "" && val != "")
	{
		var length = val.length;
		if(length >= 20 && length <= 200)
		{
			// check for bad words
			var params = "str=" + val;
			var url = "/ajax/checkHeadline";
			var http = new XMLHttpRequest();
			http.open('POST',url,true);
			http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			http.onreadystatechange = function()
			{
				//alert(http.status);
				if(http.readyState == 4 && http.status == 200)
				{
					var html = http.responseText;
					if(html != "ok")
					{
						document.getElementById('hpass').value = 'n';
						msg = "The text you entered in your headline has unwanted words!";
					}
					else
					{
						document.getElementById('hpass').value = 'y';
						document.getElementById('headline').style.backgroundColor = "#FFF";
					}
				}
			}
			http.send(params);
		}
		else
		{
			// not long enough
			//msg = 'Your headline needs to be at least 20 letters long.';
			//document.getElementById('hpass').value = 'n';
			document.getElementById('hpass').value = 'y';
			document.getElementById('headline').style.backgroundColor = "#FFF";
		}
	}
	else
	{
		// no headline added
		//msg = 'You need to add a headline!';
		//document.getElementById('hpass').value = 'n';
		document.getElementById('hpass').value = 'y';
		document.getElementById('headline').style.backgroundColor = "#FFF";
	}
	return msg;
}

function ckBio(val)
{
	msg = 'ok';
	if(val != "" && val != "")
	{
		var length = val.length;
		var msg = "ok";
		if(length >= 50 && length <= 250)
		{
			// check for bad words
			// check for bad words
			var params = "str=" + val;
			var url = "/ajax/checkHeadline";
			var http = new XMLHttpRequest();
			http.open('POST',url,true);
			http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			http.onreadystatechange = function()
			{
				//alert(http.status);
				if(http.readyState == 4 && http.status == 200)
				{
					var html = http.responseText;
					if(html != "ok")
					{
						document.getElementById('bpass').value = 'n';
						msg = 'The text you entered in your bio has unwanted words!';
					}
					else
					{
						document.getElementById('bpass').value = 'y';
					}
				}
			}
			http.send(params);
		}
		else
		{
			// not long enough
			//msg = 'Your bio needs to be at least 50 letters long.';
			document.getElementById('bpass').value = 'y';
		}
	}
	else
	{
		// no bio added
		//msg = 'You need to add a bio.';
		document.getElementById('bpass').value = 'y';
	}
	return msg;
}

function ckJoin(val)
{
	document.getElementById('joinBtn').innerHTML = 'Sending Info... Please Wait...';
	var msg = "";
	var err = 0;
	// check headline
	var hline = document.getElementById('headline').value;
	var hcheck = ckHead(hline);
	if(hcheck != "ok")
	{
		msg += hcheck+"\n";
		err = 1;
		document.getElementById('headline').style.backgroundColor = '#FF0000';
	}
	// check bio
	var bio = document.getElementById('bio').value;
	var bcheck = ckBio(bio);
	if(bcheck != "ok")
	{
		msg += bcheck+"\n";
		err = 1;
		document.getElementById('bio').style.backgroundColor = '#FF0000';
	}
	// re-check all values
	if(document.getElementById('epass').value == "n")
	{
		msg += "Please check your email.\n";
		err = 1;
		document.getElementById('email').style.backgroundColor = '#FF0000';
	}
	if(document.getElementById('npass').value == "n")
	{
		msg += "Please check your nickname.\n";
		err = 1;
		document.getElementById('nickname').style.backgroundColor = '#FF0000';
	}
	if(document.getElementById('ppass').value == "n")
	{
		msg += "Please check your password.\n";
		err = 1;
		document.getElementById('pass').style.backgroundColor = '#FF0000';
	}
	if(document.getElementById('country_now').value == "0")
	{
		msg += "Please select your country.\n";
		err = 1;
		document.getElementById('country_now').style.backgroundColor = '#FF0000';
	}
	
	if(document.getElementById('month').value == "0" || document.getElementById('day').value == "0" || document.getElementById('year').value == "0")
	{
		msg += "You need to enter your date of birth.\n";
		err = 1;
		document.getElementById('month').style.backgroundColor = '#FF0000';
		document.getElementById('day').style.backgroundColor = '#FF0000';
		document.getElementById('year').style.backgroundColor = '#FF0000';
	}
	if(document.getElementById('gender').value == "na")
	{
		msg += "You need to select your gender.\n";
		err = 1;
		document.getElementById('gender').style.backgroundColor = '#FF0000';
	}
	// check agree
	if(document.getElementById('agree').checked)
	{
		// agree
	}
	else
	{
		msg += "You need to agree to the terms of service.\n";
		err = 1;
		document.getElementById('agree').style.backgroundColor = '#FF0000';
	}
	if(err == 1)
	{
		alert(msg);
		document.getElementById('joinBtn').innerHTML = '<a href="javascript:void(0);" onclick="ckJoin();"><img src="/images/design/join_free_btn.png" width="178" height="34" alt="Join Free" title="Join Free" border="0" /></a>';
	}
	else
	{
		// check photo
		if(document.getElementById('profile_pic').value == "")
		{
			var cf = confirm('You have not added a profile pic! You will get noticed up to 50% more with a pic! Click ok to continue with out adding one.');
			if(cf)
			{
				document.getElementById('join2').submit();
				document.getElementById('joinBtn').innerHTML = 'Sending Info... Please Wait...';
			}
		}
		else
		{
			document.getElementById('join2').submit();
			document.getElementById('joinBtn').innerHTML = 'Sending Info... Please Wait...';
		}
	}
}