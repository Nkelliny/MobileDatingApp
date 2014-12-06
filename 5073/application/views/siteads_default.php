<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <table width="100%" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td><fieldset>
    <legend>Ad Sizes</legend>
    </fieldset></td>
    <td><fieldset>
    <legend>Ad Possitions</legend>
    </fieldset></td>
    <td><fieldset>
    <legend>Ad Types</legend>
    </fieldset></td>
  </tr>
  <tr>
  <td colspan="3">
  <form id="addAd" name="addAd" action="<?php echo site_url('siteads/add'); ?>" method="post" enctype="multipart/form-data">
  <fieldset>
  <legend>Add new ad</legend>
  <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="22%"><div align="right">Select Ad Type:</div></td>
    <td width="30%"><select name="type" id="type">
      <option value="image" selected="selected">Image</option>
      <option value="code">Code</option>
      <option value="flash">Flash</option>
    </select></td>
    <td width="20%"><div align="right">Select Ad Size:</div></td>
    <td width="28%"><select name="size" id="size">
      <option value="728x90" selected="selected">728x90</option>
      <option value="468x60">468x60</option>
      <option value="250x250">250x250</option>
      <option value="320x50">320x50</option>
    </select></td>
  </tr>
  <tr>
    <td><div align="right">Select Ad Poss:</div></td>
    <td><select name="poss" id="poss">
    <option value="header" selected="selected">Header</option>
      <option value="mobile">Mobile</option>
      <option value="footer">Footer</option>
      <option value="sidebar_a">Sidebar A</option>
      <option value="sidebar_b">Sidebar B</option>
      <option value="sidebar_c">Sidebar C</option>
      <option value="sidebar_d">Sidebar D</option>  
      <option value="mid_a">Mid A</option>
      <option value="mid_b">Mid B</option>
      <option value="mid_c">Mid C</option>
      <option value="mid_d">Mid D</option>
    </select></td>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Ad Image:</div></td>
    <td><input type="file" name="image" id="image" /></td>
    <td><div align="right">Ad Code:</div></td>
    <td><textarea name="adcode" id="adcode" cols="45" rows="5"></textarea></td>
  </tr>
  <tr>
    <td><div align="right">Country:</div></td>
    <td><select name="country" id="country">
      <option value="0">All</option>
      <?php
	  foreach($countries as $c)
	  {
		  ?>
          <option value="<?php echo $c->Country_str_code; ?>"><?php echo $c->Country_str_name; ?></option>
          <?php
	  }
	  ?>
    </select></td>
    <td><div align="right">Gender:</div></td>
    <td><select name="gender" id="gender">
      <option value="0" selected="selected">All</option>
      <option value="1">Female</option>
      <option value="2">Male</option>
      <option value="8">Ladyboy</option>
    </select></td>
  </tr>
  <tr>
    <td><div align="right">Out Link:</div></td>
    <td><input type="text" name="outlink" id="outlink" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center">
      <input type="submit" name="button" id="button" value="Submit" />
    </div></td>
    </tr>
</table>
  </fieldset>
  </form>
  </td>
  </tr>
  <tr>
  <td colspan="4">
  <fieldset>
  <legend>Current Ads</legend>
  <?php
	/*
	[id] => 1
	[owner] => 1
	[path] => /images/a_pics/468x60.jpg
	[imp] => 126
	[clicks] => 0
	[outlink] => http://dev2.xxxxxx.com/join
	[size] => 468x60
	[poss] => header
	[type] => image
	[status] => 2
	*/
  ?>
  <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td><div align="center">Possition</div></td>
    <td><div align="center">Type</div></td>
    <td><div align="center">Size</div></td>
    <td><div align="center">Clicks</div></td>
    <td><div align="center">Impressions</div></td>
    <td><div align="center">Outlink</div></td>
    <td><div align="center">Ad</div></td>
    <td><div align="center">Status</div></td>
  </tr>
  <?php
  $x=0;
  foreach($ads as $a)
  {
	  ?>
  		<tr <?php echo ($x%2==0 ? '' : 'bgcolor="#CCCCCC"'); ?>>
    	<td><div align="center"><?php echo $a->poss; ?></div></td>
    	<td><div align="center"><?php echo $a->type; ?></div></td>
    	<td><div align="center"><?php echo $a->size; ?></div></td>
    	<td><div align="center"><?php echo $a->clicks; ?></div></td>
    	<td><div align="center"><?php echo $a->imp; ?></div></td>
    	<td><div align="center"><?php echo $a->outlink; ?></div></td>
    	<td><div align="center">
        <?php
		if($a->type == "image")
		{
			?>
            <a href="<?php echo $a->path; ?>" target="_blank">View Ad</a>
            <?php
		}
		else if($a->type == "code")
		{
			?>
            <textarea cols="40" rows="5"><?php echo $a->path; ?></textarea>
            <?php
		}
		?>
        </div></td>
    	<td><div align="center"><select id="adStatus" name="adStatus" onchange="setAdStatus(<?php echo $a->id; ?>,this.value);">
        <option value="1" <?php echo ($a->status == "1" ? 'selected="selected"' : ''); ?>>Active</option>
        <option value="2" <?php echo ($a->status == "2" ? 'selected="selected"' : ''); ?>>Inactive</option>
        <option value="3" <?php echo ($a->status == "3" ? 'selected="selected"' : ''); ?>>Delete</option>
        </select></div></td>
  		</tr>
        <?php
		$x++;
  }
  ?>
</table>
  </fieldset>
  </td>
  </tr>
</table>
    <?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>