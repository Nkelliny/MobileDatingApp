<html>
<head>
<script type="text/javascript">
function showFrm(frm)
{
	
	//alert('/mapps/test/frm/'+frm);
	document.getElementById('frmframe').src = '/mapps/test/frm/'+frm+'/'+Math.floor(Math.random()*10000000);
}
</script>
</head>
<body>
<?php
if($type == "na")
{
	?>
<form id="test" name="test">
<table width="975" border="0" cellspacing="3" cellpadding="3" align="center">
  <tr>
  <td colspan="2">xxxxxx Service Call Test Page</td>
  </tr>
  <tr>
    <td valign="top">Select Service Call: <select id="service" name="service" onChange="showFrm(this.value);">
      <option value="na">-Select-</option>
      <option value="appstart">appstart</option>
      <option value="appload">appload</option>
      <option value="storecontacts">storecontacts</option>
      <option value="getcontacts">getcontacts</option>
      <option value="addpromo">addpromo</option>
      <option value="picapproved">picapproved</option>
      <option value="syncp">syncp</option>
      <option value="reactivate">reactivate</option>
      <option value="deleteprofile">deleteprofile</option>
      <option value="unblockeveryone">unblockeveryone</option>
      <option value="updateViewCnt">updateViewCnt</option>
      <option value="distance">distance</option>
      <option value="onlinestatus">onlinestatus</option>
      <option value="updateProfile">updateProfile</option>
      <option value="uploadPhoto">uploadPhoto</option>
      <option value="getProfileValues">getProfileValues</option>
      <option value="getUsersList">getUsersList</option>
      <option value="supportcode">supportcode</option>
      <option value="startnew">startnew</option>
      <option value="updateLastActive">updateLastActive</option>
      <option value="getFavs">getFavs</option>
      <option value="updateLocation">updateLocation</option>
      <option value="getProfiles">getProfiles</option>
      <option value="getUserProfile">getUserProfile</option>
      <option value="makeFavorite">makeFavorite</option>
      <option value="blockFriend">blockFriend</option>
      <option value="unfav">unfav</option>
      <option value="loadtext">loadtext</option>
      <option value="report">report</option>
      <option value="sendPushNotificationsApple">sendPushNotificationsApple</option>
      <option value="countries">countries</option>
      <option value="getcities">getcities</option>
      <option value="userslistlocation">userslistlocation</option>
      <option value="appsubscribe">appsubscribe</option>
      <option value="appsubscribetest">appsubscribetest</option>
      <option value="checksubscribe">checksubscribe</option>
      <option value="sendPushNotificationsDroid">sendPushNotificationsDroid</option>
      <option value="apptexttest">AppTextTest</option>
      <option value="favslist">Favs List</option>
      </select> 
      </form>   
      <iframe src="/mapps" width="600" height="800" frameborder="0" id="frmframe" name="frmframe"></iframe>      </td>
    <td width="622" valign="top"><iframe src="/mapps" width="600" height="800" frameborder="0" id="resframe" name="resframe" style="overflow-x:auto; overflow-y:hidden;"></iframe></td>
  </tr>
  </table>
<?php
}
else if($type == "storecontacts")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Contacts:<br />
    <textarea id="contacts" name="contacts" cols="40" rows="5"></textarea><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == 'sendPushNotificationsDroid')
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input type="text" id="msg" name="msg" value=""><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == 'appstart')
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Device ID:<input type="text" id="devid" name="devid" /><br>
    Lat:<input type="text" id="lat" name="lat" /><br>
    Lon:<input type="text" id="lon" name="lon" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
	<?php
}
else if($type == 'appload')
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input type="hidden" id="test" name="test" value="ok" />
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "getcontacts")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "addpromo")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Promo Code:<input type="text" id="promocode" name="promocode" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "picapproved")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "syncp")
{
	?>
     <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Sync Code:<input type="text" id="sync_code" name="sync_code" /><br>
    Email:<input type="text" id="email" name="email" /><br>
    Type:<select id="type" name="type">
    <option value="na">-Select-</option>
    <option value="rec">Get Sync Code</option>
    <option value="msync">Email Sync Code</option>
    <option value="sen">Sync Account</option>
    </select>
    <br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "uploadPhoto")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>B" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Image:<input name="pic" type="file"><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "reactivate")
{
		?>
        <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Email:<input type="text" id="email" name="email" /><br>
    Code:<input type="text" id="code" name="code" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
        <?php
}
else if($type == "deleteprofile")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "unblockeveryone")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "getProfileValues")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "getUsersList")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    List Type:<select id="ltype" name="ltype">
    <option value="">Default</option>
    <option value="r">Recent</option>
    <option value="e">Everyone</option>
    <option value="n">Near By</option>
    <option value="o">Online</option>
    </select><br>
    Limit: <input type="text" id="limit" name="limit" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "supportcode")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "startnew")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
	<?php
}
else if($type == "updateLastActive")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call"><br ><em>Does not return anything</em>
    </form>
    <?php
}
else if($type == "getFavs")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "updateLocation")
{
	?>
     <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Lat:<input type="text" id="lat" name="lat" /><br>
    Lon:<input type="text" id="lon" name="lon" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "getUserProfile")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    User ID2:<input type="text" id="userid2" name="userid2" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "makeFavorite")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    User ID2:<input type="text" id="userid2" name="userid2" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "blockFriend")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    User ID2:<input type="text" id="userid2" name="userid2" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "unfav")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    User ID2:<input type="text" id="userid2" name="userid2" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "loadtext")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="application/x-www-form-urlencoded">
    Name:<input type="text" id="name" name="name" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "report")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    User ID2:<input type="text" id="userid2" name="userid2" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "getProfiles")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Userids:<input type="text" id="userids" name="userids" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
	<?php
}
else if($type == "sendPushNotificationsApple")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>Test" target="resframe" method="post" enctype="multipart/form-data">
    User ID:<input type="text" id="userid" name="userid" /><br>
    Type:<select id="type" name="type" />
    <option value="1">Message From User (data = na)</option>
    <option value="2">Photo Approved Declined (data = 1=true,0=false)</option>
    <option value="3">Profile Update (data = na)</option>
    <option value="4">Custom Message (data = na)</option>
    <option value="5">Favorite (data = na)</option>
    </select>
    <br>Data:<input type="text" name="data" id="data" />
    <br>Message:<input type="text" id="text" name="text" />
    <br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
	<?php
}
else if($type == "countries")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    No values passed to this service.
    <br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "getcities")
{
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Country (code):<input type="text" id="country" name="country" /><br>
    Text:<input type="text" id="txt" name="txt" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "userslistlocation")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Userid:<input type="text" id="userid" name="userid" /><br>
    City (id):<input type="text" id="city" name="city" /><br>
    Distance (5,10,20,50, 100):<input type="text" id="distance" name="distance" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "appsubscribe")
{
	// 19303
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Userid:<input type="text" id="userid" name="userid" /><br>
    Transaction Recipt:<br>
<textarea name="transaction_receipt" cols="40" rows="5" id="transaction_receipt"></textarea><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "appsubscribetest")
{
	// 19303
	?>
    <form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Userid:<input type="text" id="userid" name="userid" /><br>
    Transaction Recipt:<br>
<textarea name="transaction_receipt" cols="40" rows="5" id="transaction_receipt"></textarea><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "checksubscribe")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Userid:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "apptexttest")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Userid:<input type="text" id="userid" name="userid" /><br>
    Key:<input type="text" id="key" name="key" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else if($type == "favslist")
{
	?>
	<form id="mtest" name="mtest" action="/mapps/<?php echo $type; ?>" target="resframe" method="post" enctype="multipart/form-data">
    Userid:<input type="text" id="userid" name="userid" /><br>
    <input name="btn" type="submit" value="Test Service Call">
    </form>
    <?php
}
else
{
	echo $type;
}
?>
</body>
</html>