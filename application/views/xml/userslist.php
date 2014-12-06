<?php
header("Content-type: application/xml");
$xmlData = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<userinfo>
<?php
foreach($users as $u)
{
	?>
	<user>
        <userid><?php echo $u['userid']; ?></userid>
        <nickname><?php echo $u['nickname']; ?></nickname>
        <seeking><?php echo $u['seeking']; ?></seeking>
        <looking><?php echo $u['looking']; ?></looking>
        <gender><?php echo $u['gender']; ?></gender>
        <age><?php echo $u['age']; ?></age>
        <height><?php echo $u['height']; ?></height>
        <weight><?php echo $u['weight']; ?></weight>
        <facebook><?php echo $u['facebook']; ?></facebook>
        <twitter><?php echo $u['twitter']; ?></twitter>
        <linkedin><?php echo $u['linkedin']; ?></linkedin>
        <bio><?php echo $u['bio']; ?></bio>
        <msgcnt><?php echo $u['msgcnt']; ?></msgcnt>
        <distance><?php echo $u['distance']; ?></distance>
        <favstatus><?php echo $u['favstatus']; ?></favstatus>
        <pic><?php echo $u['pic']; ?></pic>
        <online><?php echo $u['online']; ?></online>
    </user>
    <?php
}
?>
</userinfo>