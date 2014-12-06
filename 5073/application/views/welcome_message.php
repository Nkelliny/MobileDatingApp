<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
<h2>xxxxxx App Stats</h2>
<div style="float:left; width:100%;">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td><em>&nbsp;</em></td>
      <td bgcolor="#0066FF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Male Free</div></td>
      <td bgcolor="#FF66CC"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Female Free</div></td>
      <td bgcolor="#CC33FF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Ladyboy Free</div></td>
      <td bgcolor="#999999"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Male Pro</div></td>
      <td bgcolor="#CCCCCC"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Female Pro</div></td>
      <td bgcolor="#FFFFFF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">LadyBoy Pro</div></td>
    </tr>
	<?php
    $total_active_usershr = ($tagirlshr + $taguyshr + $taladyboyshr);
    $res = ($tagirlshr / $total_active_usershr) * 100;
    $per_active_girlshr = round($res,2);
    $res = ($taguyshr / $total_active_usershr) * 100;
    $per_active_guyshr = round($res,2);
    $res = ($taladyboyshr / $total_active_usershr) * 100;
    $per_active_ladyboyshr = round($res,2);
	// pro
	$total_active_usershr_pro = ($tagirlshr_pro + $taguyshr_pro + $taladyboyshr_pro);
    $res = @($tagirlshr_pro / $total_active_usershr_pro) * 100;
    $per_active_girlshr_pro = round($res,2);
    $res = @($taguyshr_pro / $total_active_usershr_pro) * 100;
    $per_active_guyshr_pro = round($res,2);
    $res = @($taladyboyshr_pro / $total_active_usershr_pro) * 100;
    $per_active_ladyboyshr_pro = round($res,2);
    ?>
    <tr>
      <td>Live Stats<br />
      <em>(Updates Every 10sec)</em></td>
      <td><div id="live_guys"></div></td>
      <td><div id="live_girls"></div></td>
      <td><div id="live_ladyboys"></div></td>
      <td><div id="live_guys_pro"></div></td>
      <td><div id="live_girls_pro"></div></td>
      <td><div id="live_ladyboys_pro"></div></td>
    </tr>
    <tr>
      <td>Users Active in the last hour<br />
        <em>(Free <?php echo $total_active_usershr; ?> Highest <?php echo $highest_free_hour; ?> | Pro <?php echo $total_active_usershr_pro; ?> Highest <?php echo $highest_free_hour_pro; ?>)</em></td>
      <td><?php echo $taguyshr; ?> <em>(<?php echo $per_active_guyshr; ?>%)</em></td>
      <td><?php echo $tagirlshr; ?> <em>(<?php echo $per_active_girlshr; ?>%)</em></td>
      <td><?php echo $taladyboyshr; ?> <em>(<?php echo $per_active_ladyboyshr; ?>%)</em></td>
      <td><?php echo $taguyshr_pro; ?> <em>(<?php echo $per_active_guyshr_pro; ?>%)</em></td>
      <td><?php echo $tagirlshr_pro; ?> <em>(<?php echo $per_active_girlshr_pro; ?>%)</em></td>
      <td><?php echo $taladyboyshr_pro; ?> <em>(<?php echo $per_active_ladyboyshr_pro; ?>%)</em></td>
    </tr>
	<?php
  $total_active_users = ($tagirls + $taguys + $taladyboys);
  $res = ($tagirls / $total_active_users) * 100;
  $per_active_girls = round($res,2);
  $res = ($taguys / $total_active_users) * 100;
  $per_active_guys = round($res,2);
  $res = ($taladyboys / $total_active_users) * 100;
  $per_active_ladyboys = round($res,2);
  // pro stats
  $total_active_users_pro = ($tagirls_pro + $taguys_pro + $taladyboys_pro);
  $res = ($tagirls_pro / $total_active_users_pro) * 100;
  $per_active_girls_pro = round($res,2);
  $res = ($taguys_pro / $total_active_users_pro) * 100;
  $per_active_guys_pro = round($res,2);
  $res = ($taladyboys_pro / $total_active_users_pro) * 100;
  $per_active_ladyboys_pro = round($res,2);
  ?>
    <tr>
      <td>Users Active in the last 24 hours<br />
        <em>(Free <?php echo $total_active_users; ?> Highest <?php echo $highest_24; ?> | Pro <?php echo $total_active_users_pro; ?> Highest <?php echo $highest_24_pro; ?>)</em></td>
      <td><?php echo $taguys; ?> <em>(<?php echo $per_active_guys; ?>%)</em></td>
      <td><?php echo $tagirls; ?> <em>(<?php echo $per_active_girls; ?>%)</em></td>
      <td><?php echo $taladyboys; ?> <em>(<?php echo $per_active_ladyboys; ?>%)</em></td>
      <td><?php echo $taguys_pro; ?> <em>(<?php echo $per_active_guys_pro; ?>%)</em></td>
      <td><?php echo $tagirls_pro; ?> <em>(<?php echo $per_active_girls_pro; ?>%)</em></td>
      <td><?php echo $taladyboys_pro; ?> <em>(<?php echo $per_active_ladyboys_pro; ?>%)</em></td>
    </tr>
    <?php
  $total_users = ($tguys + $tgirls + $tladyboys);
  $res = ($tgirls / $total_users) * 100;
  $percent_girls = round($res,2); 
  $res = ($tguys / $total_users) * 100;
  $percent_guys = round($res,2);
  $res = ($tladyboys / $total_users) * 100;
  $percent_ladyboys = round($res,2);
  // pro stats
  $total_users_pro = ($tguys_pro + $tgirls_pro + $tladyboys_pro);
  $res = ($tgirls_pro / $total_users_pro) * 100;
  $percent_girls_pro = round($res,2); 
  $res = ($tguys_pro / $total_users_pro) * 100;
  $percent_guys_pro = round($res,2);
  $res = ($tladyboys_pro / $total_users_pro) * 100;
  $percent_ladyboys_pro = round($res,2);
  ?>
    <tr>
      <td>Total Users<br />
        <em>(Free <?php echo $total_users; ?> | Pro <?php echo $total_users_pro; ?>)</em></td>
      <td><?php echo $tguys; ?> <em>(<?php echo $percent_guys; ?>%)</em></td>
      <td><?php echo $tgirls; ?> <em>(<?php echo $percent_girls; ?>%)</em></td>
      <td><?php echo $tladyboys; ?> <em>(<?php echo $percent_ladyboys; ?>%)</em></td>
      <td><?php echo $tguys_pro; ?> <em>(<?php echo $percent_guys_pro; ?>%)</em></td>
      <td><?php echo $tgirls_pro; ?> <em>(<?php echo $percent_girls_pro; ?>%)</em></td>
      <td><?php echo $tladyboys_pro; ?> <em>(<?php echo $percent_ladyboys_pro; ?>%)</em></td>
    </tr>
    <tr>
      <td>Seeking Men</td>
      <td><?php echo $men_seeking_men; ?></td>
      <td><?php echo $w_seeking_men; ?></td>
      <td><?php echo $l_seeking_men; ?></td>
      <td><?php echo $men_seeking_men_pro; ?></td>
      <td><?php echo $w_seeking_men_pro; ?></td>
      <td><?php echo $l_seeking_men_pro; ?></td>
    </tr>
    <tr>
      <td>Seeking Women</td>
      <td><?php echo $men_seeking_women; ?></td>
      <td><?php echo $w_seeking_women; ?></td>
      <td><?php echo $l_seeking_women; ?></td>
      <td><?php echo $men_seeking_women_pro; ?></td>
      <td><?php echo $w_seeking_women_pro; ?></td>
      <td><?php echo $l_seeking_women_pro; ?></td>
    </tr>
    <tr>
      <td>Seeking Ladyboys</td>
      <td><?php echo $men_seeking_ladyboy; ?></td>
      <td><?php echo $w_seeking_ladyboy; ?></td>
      <td><?php echo $l_seeking_ladyboy; ?></td>
      <td><?php echo $men_seeking_ladyboy_pro; ?></td>
      <td><?php echo $w_seeking_ladyboy_pro; ?></td>
      <td><?php echo $l_seeking_ladyboy_pro; ?></td>
    </tr>
  </table>
</div>
<div style="float:left; width:100%; height:20px;">&nbsp;</div>
<div style="float:left; width:100%;">
<h2>Subscriptions</h2>
<?php //print_r($subscriptions); ?>
<table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Name</td>
    <td>Cost</td>
    <td>Amount</td>
    <td>Paid</td>
  </tr>
  <tr>
    <td><?php echo $subscriptions[0]->name; ?></td>
    <td>$<?php echo $subscriptions[0]->price; ?></td>
    <td><?php echo $subscriptions[0]->cnt; ?></td>
    <td>$<?php echo $subscriptions[0]->val; ?></td>
  </tr>
  <tr>
    <td><?php echo $subscriptions[1]->name; ?></td>
    <td>$<?php echo $subscriptions[1]->price; ?></td>
    <td><?php echo $subscriptions[1]->cnt; ?></td>
    <td>$<?php echo $subscriptions[1]->val; ?></td>
  </tr>
  <tr>
    <td><?php echo $subscriptions[2]->name; ?></td>
    <td>$<?php echo $subscriptions[2]->price; ?></td>
    <td><?php echo $subscriptions[2]->cnt; ?></td>
    <td>$<?php echo $subscriptions[2]->val; ?></td>
  </tr>
  <tr>
    <td><?php echo $subscriptions[3]->name; ?></td>
    <td>$<?php echo $subscriptions[3]->price; ?></td>
    <td><?php echo $subscriptions[3]->cnt; ?></td>
    <td>$<?php echo $subscriptions[3]->val; ?></td>
  </tr>
</table>
</div>
<div style="float:left; width:100%; height:20px;">&nbsp;</div>
<div style="float:left; width:100%;">
<h2>Deleted Users</h2>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td><em>&nbsp;</em></td>
      <td bgcolor="#0066FF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Male Free</div></td>
      <td bgcolor="#FF66CC"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Female Free</div></td>
      <td bgcolor="#CC33FF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Ladyboy Free</div></td>
      <td bgcolor="#999999"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Male Pro</div></td>
      <td bgcolor="#CCCCCC"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Female Pro</div></td>
      <td bgcolor="#FFFFFF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">LadyBoy Pro</div></td>
    </tr>
    <tr>
    <td>Total</td>
    <td><?php echo $del_users_m; ?></td>
    <td><?php echo $del_users_f; ?></td>
    <td><?php echo $del_users_l; ?></td>
    <td><?php echo $del_users_m_pro; ?></td>
    <td><?php echo $del_users_f_pro; ?></td>
    <td><?php echo $del_users_l_pro; ?></td>
    </tr>
    <tr>
    <td>Deleted Today</td>
    <td colspan="3">Free <?php echo $del_today; ?></td>
    <td colspan="3">Pro <?php echo $del_today_pro; ?></td>
    </tr>
</table>
</div>
<!-- pics stats -->
<div style="float:left; width:100%; height:20px;">&nbsp;</div>
<div style="float:left; width:100%;">
<h2>Photo Stats</h2>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td><em>&nbsp;</em></td>
      <td bgcolor="#0066FF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Male</div></td>
      <td bgcolor="#FF66CC"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Female</div></td>
      <td bgcolor="#CC33FF"><div align="center" style="color:#000; font-size:14px; font-weight:bold;">Ladyboy</div></td>
    </tr>
    <tr>
    <td>Total</td>
    <td>Total: <?php echo $mt; ?><br />
Has Pic: <?php echo $m_pics; ?><br />
Percent With Pics: <?php echo $m_pics_per; ?>%</td>
    <td>Total: <?php echo $ft; ?><br />
Has Pic: <?php echo $f_pics; ?><br />
Percent With Pics: <?php echo $f_pics_per; ?>%</td>
    <td>Total: <?php echo $lt; ?><br />
Has Pic: <?php echo $l_pics; ?><br />
Percent With Pics: <?php echo $l_pics_per; ?>%</td>
    </tr>
</table>
</div>
<div style="float:left; width:100%; height:20px;">&nbsp;</div>
<div style="float:left; width:100%;">
<h2>Weekly Joins</h2>
  <div class="stats_charts">
    <table class="stats" rel="line" cellpadding="0" cellspacing="0" width="100%">
      <thead>
        <tr>
          <td>&nbsp;</td>
          <th scope="col"><?php echo $day_7; ?></th>
          <th scope="col"><?php echo $day_6; ?></th>
          <th scope="col"><?php echo $day_5; ?></th>
          <th scope="col"><?php echo $day_4; ?></th>
          <th scope="col"><?php echo $day_3; ?></th>
          <th scope="col"><?php echo $day_2; ?></th>
          <th scope="col"><?php echo $day_1; ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>Male Free</th>
          <td><?php echo $day_7_m_j; ?></td>
          <td><?php echo $day_6_m_j; ?></td>
          <td><?php echo $day_5_m_j; ?></td>
          <td><?php echo $day_4_m_j; ?></td>
          <td><?php echo $day_3_m_j; ?></td>
          <td><?php echo $day_2_m_j; ?></td>
          <td><?php echo $day_1_m_j; ?></td>
        </tr>
        <tr>
          <th>Female Free</th>
          <td><?php echo $day_7_f_j; ?></td>
          <td><?php echo $day_6_f_j; ?></td>
          <td><?php echo $day_5_f_j; ?></td>
          <td><?php echo $day_4_f_j; ?></td>
          <td><?php echo $day_3_f_j; ?></td>
          <td><?php echo $day_2_f_j; ?></td>
          <td><?php echo $day_1_f_j; ?></td>
        </tr>
        <tr>
          <th>Ladyboy Free</th>
          <td><?php echo $day_7_l_j; ?></td>
          <td><?php echo $day_6_l_j; ?></td>
          <td><?php echo $day_5_l_j; ?></td>
          <td><?php echo $day_4_l_j; ?></td>
          <td><?php echo $day_3_l_j; ?></td>
          <td><?php echo $day_2_l_j; ?></td>
          <td><?php echo $day_1_l_j; ?></td>
        </tr>
        <!-- pro -->
        <tr>
          <th>Male Pro</th>
          <td><?php echo $day_7_m_j_pro; ?></td>
          <td><?php echo $day_6_m_j_pro; ?></td>
          <td><?php echo $day_5_m_j_pro; ?></td>
          <td><?php echo $day_4_m_j_pro; ?></td>
          <td><?php echo $day_3_m_j_pro; ?></td>
          <td><?php echo $day_2_m_j_pro; ?></td>
          <td><?php echo $day_1_m_j_pro; ?></td>
        </tr>
        <tr>
          <th>Female Pro</th>
          <td><?php echo $day_7_f_j_pro; ?></td>
          <td><?php echo $day_6_f_j_pro; ?></td>
          <td><?php echo $day_5_f_j_pro; ?></td>
          <td><?php echo $day_4_f_j_pro; ?></td>
          <td><?php echo $day_3_f_j_pro; ?></td>
          <td><?php echo $day_2_f_j_pro; ?></td>
          <td><?php echo $day_1_f_j_pro; ?></td>
        </tr>
        <tr>
          <th>Ladyboy Pro</th>
          <td><?php echo $day_7_l_j_pro; ?></td>
          <td><?php echo $day_6_l_j_pro; ?></td>
          <td><?php echo $day_5_l_j_pro; ?></td>
          <td><?php echo $day_4_l_j_pro; ?></td>
          <td><?php echo $day_3_l_j_pro; ?></td>
          <td><?php echo $day_2_l_j_pro; ?></td>
          <td><?php echo $day_1_l_j_pro; ?></td>
        </tr>
      </tbody>
    </table>
  
  <!-- .stats_charts ends -->
</div>
<div style="float:left; width:100%; height:20px;">&nbsp;</div>

<div style="float:left; width:100%;">
<h2>Monthly Joins</h2>
  
    <table class="stats" rel="line" cellpadding="0" cellspacing="0" width="100%">
      <thead>
        <tr>
          <td>&nbsp;</td>
          <th scope="col"><?php echo $m_7; ?></th>
          <th scope="col"><?php echo $m_6; ?></th>
          <th scope="col"><?php echo $m_5; ?></th>
          <th scope="col"><?php echo $m_4; ?></th>
          <th scope="col"><?php echo $m_3; ?></th>
          <th scope="col"><?php echo $m_2; ?></th>
          <th scope="col"><?php echo $m_1; ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>Male Free</th>
          <td><?php echo $m_7_m_j; ?></td>
          <td><?php echo $m_6_m_j; ?></td>
          <td><?php echo $m_5_m_j; ?></td>
          <td><?php echo $m_4_m_j; ?></td>
          <td><?php echo $m_3_m_j; ?></td>
          <td><?php echo $m_2_m_j; ?></td>
          <td><?php echo $m_1_m_j; ?></td>
        </tr>
        <tr>
          <th>Female Free</th>
          <td><?php echo $m_7_f_j; ?></td>
          <td><?php echo $m_6_f_j; ?></td>
          <td><?php echo $m_5_f_j; ?></td>
          <td><?php echo $m_4_f_j; ?></td>
          <td><?php echo $m_3_f_j; ?></td>
          <td><?php echo $m_2_f_j; ?></td>
          <td><?php echo $m_1_f_j; ?></td>
        </tr>
        <tr>
          <th>Ladyboy Free</th>
          <td><?php echo $m_7_l_j; ?></td>
          <td><?php echo $m_6_l_j; ?></td>
          <td><?php echo $m_5_l_j; ?></td>
          <td><?php echo $m_4_l_j; ?></td>
          <td><?php echo $m_3_l_j; ?></td>
          <td><?php echo $m_2_l_j; ?></td>
          <td><?php echo $m_1_l_j; ?></td>
        </tr>
        <!-- pro -->
        <tr>
          <th>Male Pro</th>
          <td><?php echo $m_7_m_j_pro; ?></td>
          <td><?php echo $m_6_m_j_pro; ?></td>
          <td><?php echo $m_5_m_j_pro; ?></td>
          <td><?php echo $m_4_m_j_pro; ?></td>
          <td><?php echo $m_3_m_j_pro; ?></td>
          <td><?php echo $m_2_m_j_pro; ?></td>
          <td><?php echo $m_1_m_j_pro; ?></td>
        </tr>
        <tr>
          <th>Female Pro</th>
          <td><?php echo $m_7_f_j_pro; ?></td>
          <td><?php echo $m_6_f_j_pro; ?></td>
          <td><?php echo $m_5_f_j_pro; ?></td>
          <td><?php echo $m_4_f_j_pro; ?></td>
          <td><?php echo $m_3_f_j_pro; ?></td>
          <td><?php echo $m_2_f_j_pro; ?></td>
          <td><?php echo $m_1_f_j_pro; ?></td>
        </tr>
        <tr>
          <th>Ladyboy Pro</th>
          <td><?php echo $m_7_l_j_pro; ?></td>
          <td><?php echo $m_6_l_j_pro; ?></td>
          <td><?php echo $m_5_l_j_pro; ?></td>
          <td><?php echo $m_4_l_j_pro; ?></td>
          <td><?php echo $m_3_l_j_pro; ?></td>
          <td><?php echo $m_2_l_j_pro; ?></td>
          <td><?php echo $m_1_l_j_pro; ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- .stats_charts ends -->
  <!-- languages -->
  <div style="float:left; width:100%; height:20px;">&nbsp;</div>
<div style="float:left; width:100%;">
<h2>Language Stats</h2>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td>Language Code</div></td>
      <td>Users</div></td>
    </tr>
    <?php
	foreach($langs as $key=>$value)
	{
    	?>
		<tr>
    	<td><?php echo $key; ?></td>
    	<td><?php echo $value; ?></td>
    	</tr>
    <?php
    }
        ?>
</table>
</div>
</div>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<script type="text/javascript">
function getLiveStats()
{
	//alert('get stats fired');
	var params = "sts=1";
	var url = "/5073/index.php/ajax/getlivestats";
	var http = new XMLHttpRequest();
	http.open('POST',url,true);
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.onreadystatechange = function()
	{
		//alert(http.status);
		if(http.readyState == 4 && http.status == 200)
		{
			var html = http.responseText;
			var vals = html.split("::");
			document.getElementById('live_guys').innerHTML = vals[0];
			document.getElementById('live_girls').innerHTML = vals[1];
			document.getElementById('live_ladyboys').innerHTML = vals[2];
			document.getElementById('live_guys_pro').innerHTML = vals[3];
			document.getElementById('live_girls_pro').innerHTML = vals[4];
			document.getElementById('live_ladyboys_pro').innerHTML = vals[5];
		}
	}
	http.send(params);
}
getLiveStats();
setInterval(function(){getLiveStats()},10000);
</script>
<?php $this->load->view('default_footer'); ?>
