<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function sendData()
{
	var nm = document.getElementById('directmail-481a6cdb-first_name').value;
	var em = document.getElementById('directmail-481a6cdb-subscriber_email').value;
	var params = "nm="+nm+"&em="+em;
	var url = "/ajax/svdroid";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			//alert('success');
		}
	}
	http.send(params);
}
</script>
<div id="slide_content">
  <div class="bcontent"> 
    <!-- BEGIN DIRECT MAIL SUBSCRIBE FORM -->
    
    <link rel="stylesheet" type="text/css" href="https://www.dm-mailinglist.com/subscribe_forms/embed.css?f=481a6cdb&sbg=1" media="all">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="https://www.dm-mailinglist.com/subscribe_forms/localized.js" charset="UTF-8"></script>
<script src="https://www.dm-mailinglist.com/subscribe_forms/subscribe_embed.js" charset="UTF-8"></script>

<div>
	<form method="post" action="https://www.dm-mailinglist.com/subscribe" data-directmail-use-ajax="1" data-form-id="481a6cdb" class="directmail-subscribe-form" accept-charset="UTF-8">
		<input type="hidden" name="email"></input>
		<input type="hidden" name="form_id" value="481a6cdb"></input>
		<table class="directmail-main-table">
		<tr>
			<td class="directmail-form-title" colspan=1>Android Release Notification</td>
		</tr>
		<tr>
			<td class="directmail-form-description" colspan=1>As the app is still in development, but coming soon if you sign up here we will notifiy you when it's available for download</td>
		</tr>
		<tr>
			<td>
			<label class="directmail-form-label directmail-default-no-label" for="directmail-481a6cdb-first_name">First Name:</label>
			<input type="text" id="directmail-481a6cdb-first_name" name="first_name" value="" placeholder="First Name" class="" ></input>
			</td>
		</tr>
		<tr>
			<td>
			<label class="directmail-form-label directmail-default-no-label" for="directmail-481a6cdb-subscriber_email">Email*:</label>
			<input type="email" id="directmail-481a6cdb-subscriber_email" name="subscriber_email" value="" placeholder="Email*" class="directmail-required-field" required="required" onblur="sendData();"></input>
			</td>
		</tr>
		<tr> 
			<td>                                 
				<input type="submit" value="Subscribe"></input>
			</td>
		</tr>
		</table>
	</form>    
</div>

    
    <!-- END DIRECT MAIL SUBSCRIBE FORM --> 
  </div>
</div>
<?php $this->load->view('default_footer'); ?>