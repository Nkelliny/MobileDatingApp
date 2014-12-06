function removeBlock(blocked,blockedby)
{
	var params = "blocked=" + blocked + "&blockedby="+blockedby;
	var url = "/ajax/removeBlock";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			alert('The User Has Been Un - Blocked.');
			window.location.reload();
		}
	}
	http.send(params);
}

function doPhotoComment()
{
	document.getElementById('photoCommBtn').value = "Sending Comment, Please Wait...";
	document.getElementById('photoCommBtn').disabled = true;
	var to   = document.getElementById('owner').value;
	var from = document.getElementById('sender').value;
	var msg  = document.getElementById('picComment').value;
	var pid  = document.getElementById('pid').value;
	var url = "/ajax/doPhotoComment";
	var params = "to="+to+"&from="+from+"&msg="+msg+"&pid="+pid;
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var res = http.responseText;
			//alert(res);
			var resa = res.split("jsplit");
			if(resa[0] == "n")
			{
				document.getElementById('picComment').value = "";
				alert(resa[1]);
				document.getElementById('photoCommBtn').value = "Add Comment";
				document.getElementById('photoCommBtn').disabled = false;
			}
			else
			{
				document.getElementById('picComment').value = "";
				document.getElementById('photoCommBtn').value = "Add Comment";
				document.getElementById('photoCommBtn').disabled = false;
				//alert(resa[1]);
				document.getElementById('photoComments').innerHTML = resa[2];
			}
		}
	}
	http.send(params);
}

function doBlock(blocked,blockedby)
{
	var params = "blocked=" + blocked + "&blockedby="+blockedby;
	var url = "/ajax/doBlock";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			alert('The User Has Been Blocked.');
			window.location.reload();
		}
	}
	http.send(params);
}

function recoverPassword()
{
	var email = document.getElementById('remail').value;
	if(email != "")
	{
		var params = "email=" + email;
		var url = "/ajax/recoverPass";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var html = http.responseText;
				document.getElementById('forgot').innerHTML = html;
			}
		}
		http.send(params);	
	}
}

function getCitiesDT(state)
{
	var params = "country="+document.getElementById('country').value+"&state="+state;
	var url = "/ajax/getCitiesDT";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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

function getStatesDT(country)
{
	var params = "country=" + country;
	var url = "/ajax/getStatesDT";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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

function getStatesSearch(country)
{
	var params = "country=" + country;
	var url = "/ajax/getStatesSearch";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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

function getCitiesSearch(state)
{
	var country = document.getElementById('country').value;
	if(state != "0")
	{
		var params = "con_id=" + country + "&sta_id=" + state;
		var url = "/ajax/getCitiesSearch";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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
}

function getStatesJoin(country)
{
	var params = "country=" + country;
	var url = "/ajax/getStates";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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

function getMatchStates(mstate,mcity)
{
	var country = document.getElementById('match_country').value;
	if(country != "0")
	{
		var params = "con_id=" + country + "&mstate="+mstate+"&mcity="+mcity;
		var url = "/ajax/getmatchstates";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var html = http.responseText;
				document.getElementById('match_states').innerHTML = html;
			}
		}
		 http.send(params);
	}
}

function getStates(cid,sid,cty)
{
	var country = document.getElementById(cid).value;
	var params = "con_id=" + country + "&sdiv=" + sid + "&cdiv="+cid+"&cty="+cty;
	var url = "/ajax/getstates";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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

function getMatchCities(mcity)
{
	var country = document.getElementById('match_country').value;
	var state = document.getElementById('match_state').value;
	if(state != "0")
	{
		var params = "con_id=" + country + "&sta_id=" + state + "&mcity=" + mcity;
		var url = "/ajax/getmatchcities";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var html = http.responseText;
				document.getElementById('match_cities').innerHTML = html;
			}
		}
		 http.send(params);
	}
}

function getCities(cid,sid,cty)
{
	var country = document.getElementById(cid).value;
	var state = document.getElementById(sid).value;
	var params = "con_id=" + country + "&sta_id=" + state + "&sdiv=" + sid + "&cdiv="+cid+"&cty="+cty;
	var url = "/ajax/getcities";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
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

function checkNickname(val)
{
	var nick = val;
	//alert(nick);
	if(nick != "")
	{
		var params = "nick=" + nick;
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
					alert(html);
					document.getElementById('joinBtn').disabled = true;
				}
				else
				{
					document.getElementById('joinBtn').disabled = false;
				}
			}
		}
		http.send(params);
	}
}

function showInterest(tid,fid)
{
	var params = "tid="+tid+"&fid="+fid;
	var url = "/ajax/showinterest";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var res = http.responseText;
			var resa = res.split(",");
			if(resa[0] != "n")
			{
				//document.getElementById('cur_hearts').innerHTML = resa[1];
				document.getElementById('sint').innerHTML = "You have Shown Interest!";
			}
			else
			{
				alert('You have already shown interest to this user!');
			}
		}
	}
	http.send(params);
}

function paction(t,f,ty)
{
	var params = "t="+t+"&f="+f+"&ty="+ty;
	var url = "/ajax/sendgift";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var res = http.responseText;
			var resa = res.split("mm");
			if(resa[0] == "n")
			{
				alert(resa[1]);
			}
			else
			{
				document.getElementById('cur_hearts').innerHTML = resa[2];
				alert(resa[1]);
			}
		}
	}
	http.send(params);
}

function showMessageFrm()
{
	if(document.getElementById('msg_block').style.display == "none")
	{
		document.getElementById('msg_block').style.display = "block";
	}
}

function doSendComment()
{
	var curBtn = document.getElementById('sendCommentBtn').value;
	document.getElementById('sendCommentBtn').value = 'Sending Message...';
	document.getElementById('sendCommentBtn').disabled = true;
	var frm = document.forms['sendComments'];
	var msg = frm.comment.value;
	var t = frm.t.value;
	var f = frm.f.value;
	var params = "t="+t+"&f="+f+"&msg="+msg;
	var url = "/ajax/dosendcomment";
	if(msg != "")
	{
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var res = http.responseText;
				//alert(res);
				var resa = res.split("jsplit");
				if(resa[0] == "n")
				{
					document.getElementById('comment').value = "";
					alert(resa[1]);
				}
				else
				{
					document.getElementById('comment').value = "";
					document.getElementById('sendCommentBtn').value = curBtn;
					document.getElementById('sendCommentBtn').disabled = false;
					//alert(resa[1]);
					document.getElementById('comments_block').innerHTML = resa[2];
				}
			}
		}
		http.send(params);
	}
	else
	{
		alert('Please enter a message to send!');
	}
}

function doSendMsg()
{
	var curBtn = document.getElementById('sendMsgBtn').value;
	document.getElementById('sendMsgBtn').value = 'Sending Message...';
	document.getElementById('sendMsgBtn').disabled = true;
	var frm = document.forms['sendMsgFrm'];
	var msg = frm.msg.value;
	var t = frm.t.value;
	var f = frm.f.value;
	var params = "t="+t+"&f="+f+"&msg="+msg;
	var url = "/ajax/dosendmsg";
	if(msg != "")
	{
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var res = http.responseText;
				//alert(res);
				var resa = res.split("mm");
				if(resa[0] == "n")
				{
					document.getElementById('msg').value = "";
					alert(resa[1]);
				}
				else
				{
					document.getElementById('msg').value = "";
					document.getElementById('sendMsgBtn').value = curBtn;
					document.getElementById('sendMsgBtn').disabled = false;
					alert(resa[1]);
				}
			}
		}
		http.send(params);
	}
	else
	{
		alert('Please enter a message to send!');
	}
}

function doSendMsgMob()
{
	var curBtn = document.getElementById('sendMsgBtn').value;
	document.getElementById('sendMsgBtn').value = 'Sending Message...';
	document.getElementById('sendMsgBtn').disabled = true;
	var frm = document.forms['sendMsgFrm'];
	var msg = frm.msg.value;
	var t = frm.t.value;
	var f = frm.f.value;
	var params = "t="+t+"&f="+f+"&msg="+msg;
	var url = "/ajax/dosendmsg";
	if(msg != "")
	{
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var res = http.responseText;
				alert(res);
				document.getElementById('msg').value = "";
				document.getElementById('msg_block').style.display = "none";
			}
		}
		http.send(params);
	}
	else
	{
		alert('Please enter a message to send!');
	}
}

function sendChatRequest(t,f,c)
{
	var params = "t="+t+"&f="+f+"&c="+c;
	var url = "/ajax/sendChatRequest";
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
				chatwindow = window.open('http://www.xxxxxx.com/chat.php?t='+t+'&f='+f+'&c='+c,'chatwin','menubar=0,resizable=0,location=0,directories=0,scrollbars=0,width=540,height=660'); 
				chatwindow.moveTo(10,10);
			}
		}
	}
	http.send(params);
}

function checkEmail(val)
{
	//var mailCheck = document.getElementById('jemail').value;
	//document.getElementById('ckmail').innerHTML = 'Checking Email...';
	var mailCheck = val;
	if(mailCheck != null && mailCheck != "")
	{
		var params = "em="+mailCheck;
		var url = "/ajax/checkEmail";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		//http.setRequestHeader("Content-length",params.length);
		//http.setRequestHeader("Connection","Close");
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
					alert('Email is in use, please choose a different email.');
					document.getElementById('joinBtn').disabled = true;
				}
			}
		}
		http.send(params);
	}
	else
	{
		document.getElementById('ckmail').innerHTML = '<br /><img src="/images/icons/notok.png" width="16" height="16" border="0" /> Please enter an email address!';
	}
}

function addFav(fav,owner)
{
	var params = "fav="+fav+"&owner="+owner;
	var url = "/ajax/addFav";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	//http.setRequestHeader("Content-length",params.length);
	//http.setRequestHeader("Connection","Close");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var res = http.responseText;
			if(res == "ok")
			{
				alert('They are now in your favoriates!');
			}
		}
	}
	http.send(params);
}