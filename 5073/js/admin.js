function updateSettings(id)
{
	var val = '';
	if(id < 8)
	{
		val = document.getElementById('val_'+id).value;
	}
	else if(id == 8)
	{
		var hrs = document.getElementById('hrs_'+id).value;
		var mins = document.getElementById('mins_'+id).value;
		val = hrs + "|" + mins;
	}
	else if(id == 9)
	{
		var days = document.getElementById('days_'+id).value;
		var hrs = document.getElementById('hours_'+id).value;
		var mins = document.getElementById('mins_'+id).value;
		val = days + "|" + hrs + "|" + mins; 
	}
	var params = "id="+id+"&val="+val;
	var url = "/5073/index.php/ajax/updatesettings";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			alert('The setting has been changed!');
		}
	}
	http.send(params);
}

function getAdCities(code)
{
	document.getElementById('cities').innerHTML = "Loading Cities....";
	var params = "code="+code;
	var url = "/5073/index.php/ajax/getadcities";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			document.getElementById('cities').innerHTML = html;
		}
	}
	http.send(params);
}

function updateSubPrice(id)
{
	var price = document.getElementById('price_'+id).value;
	var params = "id="+id+"&price="+price;
	var url = "/5073/index.php/ajax/updatesubprice";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				alert('The price has been changed');
			}
			else
			{
				alert('Error, please refresh the page and try again.');
			}
		}
	}
	http.send(params);
}

function updateContactKey(id,val)
{
	var params = "id="+id+"&value="+val+"";
	var url = "/5073/index.php/ajax/updatecontactkey";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				alert('Key Updated');
			}
			else
			{
				alert('Error, please refresh the page and try again.');
			}
		}
	}
	http.send(params);
}

function setKey(val)
{
	var params = "val="+val;
	var url = "/5073/index.php/ajax/setkey";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			document.getElementById('add_key').value = html;
		}
	}
	http.send(params);
}

function setMainPicFix(uid,imgid)
{
	var params = "uid="+uid+"&img="+imgid;
	//alert(params);
	var url = "/5073/index.php/ajax/setmainpicfix";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				window.location.reload();
			}
			else
			{
				alert('somethings wrong knobby, try again!');
				window.location.reload();
			}
		}
	}
	http.send(params);
}

function doUserSearch()
{
	var stype = document.getElementById('stype').value;
	var str = document.getElementById('val').value;
	var params = "stype="+stype+"&val="+str;
	var url = "/5073/index.php/ajax/usersearch";
	if(str == "")
	{
		alert('You need to enter a value in the text field knobby!');
	}
	else
	{
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var html = http.responseText;
				if(html != "na")
				{
					document.getElementById('search_res').innerHTML = html;
					document.getElementById('search_res').style.display = 'block';
				}
				else
				{
					alert('No results found knobby, try again!');
				}
			}
		}
		http.send(params);
	}
}

function imgRotate(id)
{
	var params = "id="+id;
	var url = "/5073/index.php/ajax/imgRotate";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html != "Facebook Image, can not be rotated.")
			{
				document.getElementById('img_'+id).innerHTML = html;
			}
			else
			{
				alert('Facebook Image, can not be rotated.');
			}
		}
	}
	http.send(params);
}

function checkAll(bx) {
  var cbs = document.getElementsByTagName('input');
  for(var i=0; i < cbs.length; i++) {
    if(cbs[i].type == 'checkbox') {
      cbs[i].checked = bx.checked;
    }
  }
}

function setUserType(type,id)
{
	var params = "id="+id+"&type="+type;
	var url = "/5073/index.php/ajax/setUserType";
	var http = new XMLHttpRequest();
	//alert(params);
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				alert('Updated');
			}
		}
	}
	http.send(params);
}

function switchGender(gender,id)
{
	var params = "id="+id+"&gender="+gender;
	var url = "/5073/index.php/ajax/updateGender";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				alert('Gender Changed');
			}
			else
			{
				alert('Gender Not Changed');
			}
		}
	}
	http.send(params);
}

function updateMessage(id)
{
	var msg = document.getElementById('msg_'+id).value;
	var params = "id="+id+"&msg="+msg;
	var url = "/5073/index.php/ajax/updateMessage";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				alert('Message Updated');
			}
		}
	}
	http.send(params);
}

function deleteMessage(id)
{
	var params = "id="+id;
	var url = "/5073/index.php/ajax/deleteMessage";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			if(html == "ok")
			{
				document.getElementById('msg_row_'+id).style.display = 'none';
			}
		}
	}
	http.send(params);
}

function setImageStatus(img,status)
{
	var params = "img="+img+"&status="+status;
	var url = "/5073/index.php/ajax/setImageStatus";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			alert('The image status has been changed.');
			document.getElementById('image_'+img).style.display = 'none';
		}
	}
	http.send(params);
}

function setHomeImage(id)
{
	var val = 2;
	if(document.getElementById('site_'+id).checked)
	{
		val = 1;
	}
	var params = "id="+id+"&val="+val;
	var url = "/5073/index.php/ajax/setHomeImage";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			//alert(html);
			if(html == "ok")
			{
				//alert('The image status has been changed.');
				//document.getElementById('img_'+id).style.display = 'none';
			}
			else
			{
				alert('error');
			}
		}
	}
	http.send(params);
}

function setMailerImage(id)
{
	var val = 2;
	if(document.getElementById('mailer_'+id).checked)
	{
		val = 1;
	}
	var params = "id="+id+"&val="+val;
	var url = "/5073/index.php/ajax/setMailerImage";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			//alert(html);
			if(html == "ok")
			{
				//alert('The image status has been changed.');
				//document.getElementById('img_'+id).style.display = 'none';
			}
			else
			{
				alert('error');
			}
		}
	}
	http.send(params);
}


function deleteImage(id)
{
	var params = "id="+id;
	var url = "/5073/index.php/ajax/deleteImage";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			//alert(html);
			if(html == "ok")
			{
				//alert('The image status has been changed.');
				document.getElementById('img_'+id).style.display = 'none';
			}
			else
			{
				alert('error');
			}
		}
	}
	http.send(params);
}

function setUserProfileStatus(val,uid)
{
	var params = "val="+val+"&uid="+uid;
	var url = "/5073/index.php/ajax/setUserProfileStatus";
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
			alert('The users profile status has been changed.');
		}
	}
	http.send(params);
}

function setUserStatus(val,uid)
{
	var params = "val="+val+"&uid="+uid;
	var url = "/5073/index.php/ajax/setUserStatus";
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
			alert('The users status has been changed.');
			if(val == "1")
			{
				try
				{	
					document.getElementById('urow_'+uid).style.backgroundColor = "#CCCCCC";
				}
				catch(err){}
			}
		}
	}
	http.send(params);
}

function isMod(uid)
{
	var val = 0;
	if(document.getElementById('ismod_'+uid).checked)
	{
		val = 1;
	}
	var params = "val="+val+"&uid="+uid;
	var url = "/5073/index.php/ajax/isMod";
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
			// status changed
		}
	}
	http.send(params);
}

function setAdStatus(id,status)
{
	var params = "id="+id+"&status="+status;
	var url = "/5073/index.php/ajax/setAdStatus";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length",params.length);
	http.setRequestHeader("Connection","Close");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			// status changed
			alert('Ad status has been changed');
			if(status == 3)
			{
				window.location.reload();
			}
		}
	}
	http.send(params);
}

function doCommentStatus(id,status)
{
	var params = "id="+id+"&status="+status;
	var url = "/5073/index.php/ajax/doCommentStatus";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length",params.length);
	http.setRequestHeader("Connection","Close");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			// status changed
			alert('Comment status has been changed');
			if(status == 3)
			{
				window.location.reload();
			}
		}
	}
	http.send(params);
}