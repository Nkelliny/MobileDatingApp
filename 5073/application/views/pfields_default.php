<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>Profile Fields</h2>
    <p>
    <label>Add New Fields<label>
    </p>
<form id="newFld" name="newFld" action="<?php echo site_url('pfields/addfld'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <p>
    <label>Name</label> <input type="text" class="text" id="fld_name" name="fld_name" />
    <label>Site Text</label> <input type="text" class="text" id="fld_txt" name="fld_txt" />
    </p>
    <p>
	<input type="submit" id="addFldBtn" name="addFldBtn" value="Add New Field" />
    </p>
    </form>
    <hr size="1" width="100%" />
<p>
    <label>Existing Fields & Values</label>
    </p>
    <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th align="center"><div align="center">Field Name
          </td>
        </div>
        <th align="center"><div align="center">Edit
          </td>
        </div>
        <th align="center"><div align="center">Status
          </td>
        </div>
        <th align="center"><div align="center">On Site
          </td>
        </div>
        <th align="center"><div align="center">On Mobile
          </td>
        </div>
        <th align="center"><div align="center">Delete
          </td>
        </div>
      </tr>
    </thead>
    <tbody>
    <?php
	foreach($flds as $f)
	{
		?>
      <tr>
        <td><?php echo $f->name; ?></td>
        <td align="center"><div align="center"><a href="<?php echo site_url('pfields/edit/'.$f->id); ?>"><img src="/5073/images/msg_info.png" width="16" height="16" border="0" /></a></div></td>
        <td align="center">
          <div align="center">
            <?php
		if($f->status == "1")
		{
			?>
            <a href="javascript:void(0);" onclick="setPfieldStatus(<?php echo $f->id; ?>,2);"><img src="/5073/images/msg_success.png" width="16" height="16" border="0" /></a>
            <?php
		}
		else
		{
			?>
            <a href="javascript:void(0);" onclick="setPfieldStatus(<?php echo $f->id; ?>,1);"><img src="/5073/images/close.png" width="16" height="16" border="0" /></a>
            <?php
		}
		?>
        </div></td>
        <td align="center">
          <div align="center">
            <?php
		if($f->site == "y")
		{
			?>
            <a href="javascript:void(0);" onclick="pfieldOnSite(<?php echo $f->id; ?>,'n');"><img src="/5073/images/msg_success.png" width="16" height="16" border="0" /></a>
            <?php
		}
		else
		{
			?>
            <a href="javascript:void(0);" onclick="pfieldOnSite(<?php echo $f->id; ?>,'y');"><img src="/5073/images/close.png" width="16" height="16" border="0" /></a>
            <?php
		}
		?>
        </div></td>
        <td align="center">
          <div align="center">
            <?php
		if($f->mobile == "y")
		{
			?>
            <a href="javascript:void(0);" onclick="pfieldOnMobile(<?php echo $f->id; ?>,'n');"><img src="/5073/images/msg_success.png" width="16" height="16" border="0" /></a>
            <?php
		}
		else
		{
			?>
            <a href="javascript:void(0);" onclick="pfieldOnMobile(<?php echo $f->id; ?>,'y');"><img src="/5073/images/close.png" width="16" height="16" border="0" /></a>
            <?php
		}
		?>
        </div></td>
        <td align="center"><div align="center"><img src="/5073/images/error.gif" width="16" height="16" border="0" /></div></td>
      </tr>
        <?php
	}
	?>
    </tbody>
    </table>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
