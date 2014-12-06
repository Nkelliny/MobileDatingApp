<?php
header("Content-type: application/xml");
$xmlData = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
//print_r($field_values);
//exit;
?>
<pfields>
	<?php
	foreach($field_values as $key=>$value)
	{
		?>
        <field>
        <name><?php echo $key; ?></name>
        <label><?php echo $field_values[$key]['label']; ?></label>
        <vals>
        <?php
		$vals_list = array();
		foreach($field_values[$key]['values'] as $v)
		{
			$vals_list[] = $v['txt'];
		}
		$nvals = implode(',',$vals_list);
		echo $nvals;
		?>
        </vals>
        </field>
        <?php
	}
	?>
</pfields>