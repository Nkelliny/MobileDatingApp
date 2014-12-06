<?php
header("Content-type: application/xml");
$xmlData = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<userinfo>
<?php
if($udata != "na")
{
	?>
	<user>
	<userid><?php echo $udata->id; ?></userid>
	<nickname><?php echo $udata->nickname; ?></nickname>
	<seeking><?php echo $this->my_usersmanager->getPfieldValue($udata->seeking); ?></seeking>
	<looking><?php echo $this->my_usersmanager->getPfieldValue($udata->lookingfor); ?></looking>
	<gender><?php echo $this->my_usersmanager->getPfieldValue($udata->gender); ?></gender>";
	<age><?php echo ($udata->age != "" ? $udata->age : $this->my_usersmanager->birthday($udata->dob)); ?></age>
	<height><?php echo $this->my_usersmanager->getPfieldValue($pdata->user_height); ?></height>
	<weight><?php echo $this->my_usersmanager->getPfieldValue($padata->user_weight); ?></weight>
	<facebook><?php echo $pdata->facebook; ?></facebook>
	<twitter><?php echo $pdata->twitter; ?></twitter>
	<linkedin><?php echo $pdata->linkedin; ?></linkedin>
	<bio><?php echo $pdata->bio; ?></bio>
	<msgcnt><?php echo rand(0,5); ?></msgcnt>
	<distance>0</distance>
	<favstatus>0</favstatus>
	<headline><?php echo $udata->headline; ?></headline>
	<ethnicity><?php echo $this->my_usersmanager->getPfieldValue($pdata->user_ethn); ?></ethnicity>
	<match_age><?php echo $pdata->match_age; ?></match_age>
    <?php
	$raw_path = $this->my_usersmanager->getProfilePicFromId($udata->id);
	$mystring = $raw_path;
	$findme   = "facebook.com";
	$pos = strpos($mystring, $findme);
	if ($pos === false) 
	{
		$ppic = "http://www.xxxxxx.com".$raw_path;
	}
	else
	{
		$ppic = $raw_path;
	}
	?>
	<pic><?php echo $ppic; ?></pic>
	<online><?php echo $this->my_usersmanager->getOnlineStatus($udata->id); ?></online>
	</user>
    <?php
}
else
{
	?>
	<user>
	<userid>na</userid>
	</user>
    <?php
}
?>
</userinfo>