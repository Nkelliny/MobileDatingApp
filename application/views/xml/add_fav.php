<?php
header("Content-type: application/xml");
$xmlData = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<results>
	<res>
        <value><?php echo $msg; ?></value>
    </res>
</results>