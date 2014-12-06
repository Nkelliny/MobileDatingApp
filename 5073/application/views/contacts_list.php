<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>xxxxxx - Admin Panel Interface</title>
</head>
<body>
<?php
//print_r($contacts);
$clist = explode('||',$contacts->contacts);
//print_r($clist);
foreach($clist as $c)
{
	$info = explode(',',$c);
	foreach($info as $key=>$value)
	{
		$pts = explode(':',$value);
		?>
        <strong><?php echo $pts[0]; ?>:</strong> <?php echo @$pts[1]; ?><br />
        <?php
	}
	?>
    <br /><br />
	<?php
}
?>
</body>
</html>