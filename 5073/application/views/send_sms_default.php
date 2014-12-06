<?php $this->load->view('default_header'); ?>
<!-- load counter functions -->
    <script type="text/javascript" src="/js/counter_funcs.js"></script>
    <!-- set the maximum character length value for the field -->
    <script type="text/javascript">
    /*<![CDATA[*/
        // declare how many characters you want to allow
        var MAX = 160;
	/**
	 * Onsubmit handler for form.
	 * This function allows display of custom alert message if too
	 * many characters are entered
	 */
	function check_char_length(elem_id, max_len)
	{
	    if(check_content_length(elem_id, max_len)) 
		{
			return true;
	    } 
		else 
		{
			// too many chars
			alert("Maximum input length is " + max_len + " characters");
			return false;
	    }
	}
    /*]]>*/
    </script>
<script type="text/javascript">
function cksmssend()
{
	var err = 0;
	var msg = '';
	if(document.getElementById('type').value == "na")
	{
		err = 1;
		msg += "Please select the type!<br />";
	}
	if(document.getElementById('msg').value == "")
	{
		err = 1;
		msg += "You need to add a message!<br />";
	}
	if(document.getElementById('num').value == "")
	{
		err = 1;
		msg += "You need to enter how many peope to send to!<br />";
	}
	if(document.getElementById('year').value == "na" || document.getElementById('month').value == "na" || document.getElementById('day').value == "" || document.getElementById('hour').value == "na" || document.getElementById('min').value == "na" || document.getElementById('sec').value == "na")
	{
		err = 1;
		msg += "Please select all the date fields!<br />";
	}
	if(err == 1)
	{
		alert('You have not completed the form correctly!');
		document.getElementById('sendres').innerHTML = msg;
	}
	else
	{
		document.getElementById('btn').value = 'Sending...';
		document.getElementById('btn').disabled = true;
		// send sms
		var type = document.getElementById('type').value;
		var msg = document.getElementById('msg').value;
		var amt = document.getElementById('num').value;
		var when = document.getElementById('year').value+''+document.getElementById('month').value+''+document.getElementById('day').value+''+document.getElementById('hour').value+''+document.getElementById('min').value+''+document.getElementById('sec').value;
		var params = "type="+type+"&msg="+msg+"&amt="+amt+"&when="+when;
		var url = "/5073/index.php/ajax/sendsms";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var html = http.responseText;
				document.getElementById('sendres').innerHTML = html;
			}
		}
		http.send(params);
	}
}
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <form id="smsfrm" name="smsfrm/" method="post" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="50%">Select Number Type:    
      <label for="type"></label>
      <select name="type" id="type">
        <option value="thai">Thai Numbers Only</option>
        <option value="nonthai">Non Thai Numbers Only</option>
        <option value="all">All Numbers</option>
    </select></td>
    <td rowspan="6" valign="top"><h3>Results</h3>
    <div id="sendres"></div></td>
  </tr>
  <tr>
    <td>Message To Send:<br />
    <textarea name="msg" placeholder="Message to send" id="msg" cols="45" rows="5" onkeyup="update_counter('text_counter', 'msg', MAX)"></textarea><br />
    <span id="text_counter">160</span> characters left</td>
    </tr>
  <tr>
    <td>Amount:
    <input placeholder="Amount of numbers to use" type="text" name="num" id="num" /></td>
  </tr>
  <tr>
    <td>Time to send:
      <?php
	  $cy = date('Y',time());
	  $cm = date('m',time());
	  $cd = date('d',time());
	  $ch = date('H',time());
	  $cmi = date('i',time());
	  $cs = date('s',time());
	  ?>
      <select name="year" id="year">
      <option value="na">Year</option>
      <option value="<?php echo $cy; ?>" selected="selected"><?php echo $cy; ?></option>
      <option value="14">2014</option>
    </select>
      <select name="month" id="month">
    	<option value="na">Month</option>
        <option value="<?php echo $cm; ?>" selected="selected"><?php echo $cm; ?></option>
        <?php
		for($a=1;$a<13;$a++)
		{
			?>
            <option value="<?php echo ($a<10 ? '0'.$a : $a); ?>"><?php echo ($a<10 ? '0'.$a : $a); ?></option>
            <?php
		}
		?>
    </select>
      <select name="day" id="day">
      <option value="na">Day</option>
      <option value="<?php echo $cd; ?>" selected="selected"><?php echo $cd; ?></option>
      <?php
	  for($a=1;$a<32;$a++)
	  {
		  ?>
          <option value="<?php echo ($a<10 ? '0'.$a : $a); ?>"><?php echo ($a<10 ? '0'.$a : $a); ?></option>
          <?php
	  }
	  ?>
    </select>
      <select name="hour" id="hour">
    <option value="na">Hour</option>
    <option value="<?php echo $ch; ?>" selected="selected"><?php echo $ch; ?></option>
    <?php
	for($a=0;$a<24;$a++)
	{
		?>
        <option value="<?php echo ($a<10 ? '0'.$a : $a); ?>"><?php echo ($a<10 ? '0'.$a : $a); ?></option>
        <?php
	}
	?>
    </select>
      <select name="min" id="min">
      <option value="na">Min</option>
      <option value="<?php echo $cmi; ?>" selected="selected"><?php echo $cmi; ?></option>
      <?php
	  for($a=0;$a<60;$a++)
	  {
		  ?>
          <option value="<?php echo ($a<10 ? '0'.$a : $a); ?>"><?php echo ($a<10 ? '0'.$a : $a); ?></option>
          <?php
	  }
	  ?>
    </select>
      <select name="sec" id="sec">
      <option value="na">Sec</option>
      <option value="<?php echo $cs; ?>" selected="selected"><?php echo $cs; ?></option>
      <?php
	  for($a=0;$a<60;$a++)
	  {
		  ?>
          <option value="<?php echo ($a<10 ? '0'.$a : $a); ?>"><?php echo ($a<10 ? '0'.$a : $a); ?></option>
          <?php
	  }
	  ?>
    </select></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td><input name="btn" id="btn" type="button" value="Send" onclick="cksmssend();" /></td>
    </tr>
</table>
</form>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
