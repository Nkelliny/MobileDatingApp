<?php
header("Content-type: application/xml");
$xmlData = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<userinfo>
  <user>
    <userid><?php echo $user['userid']; ?></userid>
    <nickname><?php echo $user['nickname']; ?></nickname>
	<seeking><?php echo $user['seeking']; ?></seeking>
	<headline><?php echo $user['headline']; ?></headline>
	<looking><?php echo $user['looking']; ?></looking>
	<gender><?php echo $user['gender']; ?></gender>
	<age><?php echo $user['age']; ?></age>
	<height><?php echo $user['height']; ?></height>
	<weight><?php echo $user['weight']; ?></weight>
	<facebook><?php echo $user['facebook']; ?></facebook>
	<twitter><?php echo $user['twitter']; ?></twitter>
	<linkedin><?php echo $user['linkedin']; ?></linkedin>
	<bio><?php echo $user['bio']; ?></bio>
	<msgcnt><?php echo $user['msgcnt']; ?></msgcnt>
	<distance><?php echo $user['distance']; ?></distance>
	<favstatus><?php echo $user['favstatus']; ?></favstatus>
	<pic><?php echo $user['pic']; ?></pic>
	<online><?php echo $user['online']; ?></online>
  </user>
</userinfo>