<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>xxxxxx - Admin Panel Interface</title>
<meta name="description" content="." />
<meta name="keywords" content="." />
</head>
<body>
<form id="sp" name="sp" action="<?php echo site_url('users/sendpush'); ?>" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" id="uid" name="uid" value="<?php echo $uid; ?>" />
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="20%" valign="top"><div align="right">User:</div></td>
    <td width="80%" valign="top"><div align="left"><?php echo $uid; ?> <?php echo $this->my_usersmanager->getNickname($uid); ?></div></td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Message:</div></td>
    <td valign="top"><div align="left">
      <textarea name="msg" id="msg" cols="45" rows="5"></textarea>
    </div></td>
  </tr>
  <tr>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="snd" id="snd" value="Submit" />
    </div></td>
  </tr>
</table>
</form>
</body>
</html>