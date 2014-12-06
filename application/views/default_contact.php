<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function handleContact()
{
	var spam = document.getElementById('username').value;
	var err = 0;
	var em = document.getElementById('email').value;
	var sb = document.getElementById('subject').value;
	var mg = document.getElementById('message').value;
	var msg = "The following fields are required! \n";
	if(spam == "")
	{
		if(em == "")
		{
			err = 1;
			msg += "Please enter your email address! \n";
		}
		if(sb == "na")
		{
			err = 1;
			msg += "Please choose subject! \n";
		}
		if(mg == "")
		{
			err = 1;
			msg += "You need to add a message! \n";
		}
		if(err == 1)
		{
			alert(msg);
		}
		else
		{
			document.getElementById('contactFrm').submit();
		}
	}
	else
	{
		// spam show thank you
		window.location.assign("http://www.xxxxxx.com/thanks")
	}
}
</script>
<link rel="stylesheet" type="text/css" href="/css/contact.css" media="all">
<div id="slide_content">
  <div class="bcontent">
    <form id="contactFrm" method="post" action="/contact/csub" enctype="application/x-www-form-urlencoded" class="directmail-subscribe-form" data-form-id="ad08c506">
      <table  class="directmail-main-table">
        <tr>
          <td><input type="text" name="firstname" id="firstname" placeholder="Enter Your Name" /></td>
        </tr>
        <tr>
          <td><input type="text" name="email" id="email" placeholder="Enter Your Email" /></td>
        </tr>
        <tr>
          <td><select name="subject" id="subject">
              <option value="na">Select Subject</option>
              <option value="General Question">General Question</option>
              <option value="IOS Help">IOS Help</option>
              <option value="Android Help">Android Help</option>
              <option value="Sync Issues">Sync Issues</option>
              <option value="Billing Support">Billing Support</option>
              <option value="Advertising">Advertising</option>
            </select></td>
        </tr>
        <tr>
          <td><textarea name="message" id="message" cols="45" rows="5" placeholder="Enter Your Message"></textarea></td>
        </tr>
        <tr>
          <td><div align="center">
          <input type="input" name="username" id="username" class="cxname" />
          <a href="javascript:void(0);" onclick="handleContact();"><img src="/images/design/dev/contact_btn.jpg" width="558" height="50" /></a>
              <!-- <input type="submit" name="button" id="button" value="Contact xxxxxx" /> -->
            </div></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php $this->load->view('default_footer'); ?>
