<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>

<h2>Add New Advert</h2>
<form id="addAdvertFrm" name="addAdvertFrm" method="post" action="<?php echo site_url('advert/doadd'); ?>" enctype="multipart/form-data">
  <p>
    <label>Advert Name</label><br />
    <input type="text" id="name" name="name" class="text" size="50" />
  </p>
  <p>
    <label>Advert Owner</label><br />
    <input type="text" class="text" name="owner" id="owner" size="50" />
  </p>
  <p>
    <label>Advert For</label><br />
    <select id="adfor" name="adfor">
      <option value="na">-Select-</option>
      <?php
	foreach($adfor as $a)
	{
		?>
      <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
      <?php
	}
	?>
    </select>
  </p>
  <p>
    <label>Advert Type</label><br />
    <select id="type" name="type">
      <option value="na">-Select-</option>
      <?php
	foreach($type as $a)
	{
		?>
      <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
      <?php
	}
	?>
    </select>
  </p>
  <p>
    <label>Advert Possition</label><br />
    <select id="poss" name="poss">
      <option value="na">-Select-</option>
      <?php
	foreach($poss as $a)
	{
		?>
      <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
      <?php
	}
	?>
    </select>
  </p>
  <p>
    <label>Advert Size</label><br />
    <select id="size" name="size">
      <option value="na">-Select-</option>
      <?php
	foreach($size as $a)
	{
		?>
      <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
      <?php
	}
	?>
    </select>
  </p>
  <p>
    <label>Advert Path</label><em>(Path to xxxxxx Click Code)</em><br />
    <input type="text" class="text" id="path" name="path" size="50" />
  </p>
  <p>
    <label>Advert Outlink</label><em>(Link to send user on click update)</em><br />
    <input type="text" class="text" id="outlink" name="outlink" size="50" />
  </p>
  <p>
    <label>Advert Gender / Genders</label><br />
    <label><input type="checkbox" id="gender[]" name="gender[]" value="17" checked="checked" /> Female</label>
    <label><input type="checkbox" id="gender[]" name="gender[]" value="18" checked="checked" /> Male</label>
    <label><input type="checkbox" id="gender[]" name="gender[]" value="19" checked="checked" /> Ladyboy</label>
  </p>
  <p>
    <label>Advert Country</label><br />
    <select id="country" name="country" onchange="getAdCities(this.value);">
    <option value="na">-Select-</option>
    <?php
	foreach($countries as $c)
	{
		?>
        <option value="<?php echo $c->code; ?>"><?php echo $c->name; ?></option>
        <?php
	}
	?>
    </select>
  </p>
  <p>
  <label>Advert Cities</label><br />
  <table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="cities" style="float:left; width:100%;"></div></td>
  </tr>
</table>
  </p>
  <p>
    <label>Advert Impressions Limit</label><br />
    <input type="text" class="text" id="adlimit" name="adlimit" size="20" />
  </p>
  <p>
    <label>Advert File</label><em>Image / Video</em><br /> 
    <input name="adfile" id="adfile" type="file" />
    </p>
  <p>
    <label>Advert Code / Text</label><br />
    <textarea name="adcode" id="adcode" cols="70" rows="5"></textarea>
  </p>
  <p><input type="submit" class="submit tiny" value="Add New Advert" /></p>
  <p>&nbsp;</p>
</form>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
