var rIsIE = navigator.appName.indexOf("Microsoft") != -1;
function getFlexApp(movieName){
	if(rIsIE){
		return window[movieName];
	} else {
		
		if(document[movieName].length != undefined){
			return document[movieName][1];
		} else {
			return document[movieName];
		}
	}
}

//function sntfB(value){
	//thisMovie("rmp3").sntf(value);
//}

//function snff(value){
	//sntfB(value);
//}
function showChat(jid,nickname,show,status) 
{ 
	//alert('showChat called');
	if(jid == null)
	{ 
		alert("Please select a person, or maybe I screwed up."); 
	} 
	else
	{ 
		// check for existing div
		var ck = document.getElementById('chat_1');
		if(!ck)
		{
			//var div = document.createElement('div');
			//div.id = 'cb_1';
			var html = "<div id='chat_1' style='float:right; width:260px; border-left:#CCC 1px solid;'>";
			html += "<div id='chat_1_top' style='float:left; width:100%; height:26px; background-color:#CCC;'>";
			html += "<div id='chat_1_name' style='float:left; width:70%; padding-left:15px; padding-top:5px;'><a href='#' id='chat_1_max'>"+nickname+"</a></div>";
			html += "<div id='chat_1_close_btn' class='chat_1_close_btn' style='float:right; width:10%; padding-top:5px; text-align:center;'><a href='#' id='chat_1_min'>-</a></div>";
			html += "</div>";
			html += "<div id='chat_1_txt' style='float:left; width:100%; height:230px; overflow-x:hidden; overflow-y:scroll;'></div>";
			html += "<div id='chat_1_input' style='float:left; width:100%; height:25px; padding-top:5px; background-color:#CCC;'>";
			html += "<input type='hidden' id='jid' name='jid' value='"+jid+"' />";
			html += "<input type='text' id='msg' name='msg' size='25' style='padding-left:5px; border:none; background-color:#CCC;' />";
			html += "&nbsp;<a href='#' id='send_1_msg'>Send</a></div>";
			html += "</div>";
			$('#chats').append($(html));
			$('#chat_1_min').click(function () {
				document.getElementById('chat_1').style.width = '120px';
				document.getElementById('chat_1_top').style.width = '120px';
				//document.getElementById('chat_1_name').style.width = '120px';
				document.getElementById('chat_1_txt').style.display = "none";
				document.getElementById('chat_1_input').style.display = "none";
			});
			
			$('#chat_1_max').click(function () {
				document.getElementById('chat_1').style.width = '260px';
				document.getElementById('chat_1_top').style.width = '100%';
				//document.getElementById('chat_1_name').style.width = '200px';
				document.getElementById('chat_1_txt').style.display = "block";
				document.getElementById('chat_1_input').style.display = "block";
			});
			$('#send_1_msg').click(function () {
				sendChatMsg(1);
			});
		}
	} 
} 

function sendChatMsg(id)
{
	var msg = document.getElementById('msg').value;
	var jid = document.getElementById('jid').value;
	//alert('Sending: ' + msg + ' from: ' + jid);
	getFlexApp("xxxxxxChat").msgFromSite(msg,jid);
}

function addPerson() 
{ 
	var name = document.getElementById('txtName').value; var age = document.getElementById('txtAge').value; 
	var sex = document.getElementById('selSex').value; 
	thisMovie('xxxxxxChat').addPerson(name, age, sex); 
}