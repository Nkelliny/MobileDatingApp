<?php
	include("dbcon.php");
	
	if(@$_REQUEST['value'])
	{
		$value = mysql_escape_string($_REQUEST['value']);
		$value = strip_tags($value);
	
		$query = "insert into heat_ratings (rating) values ('$value')";
		mysql_query( $query);
	}
		
	$result=mysql_query("select sum(rating) as rating from heat_ratings");
	$row=mysql_fetch_array($result);
	
	$rating=$row['rating'];
	
	$quer 		= mysql_query("select rating from heat_ratings");
	$all_result = mysql_fetch_assoc($quer);
	$rows_num   = mysql_num_rows($quer);
	
	if($rows_num > 0)
	{
		$get_rating = floor($rating/$rows_num);
	}?>
	
	<div id="CurrentRating">
		<div class="rating_value"><?php echo ((@$get_rating) ? @$get_rating : '0')?>/5 <span><?php echo $rows_num?> times rated</span></div>
		<?php if(@$value){?>
		<div id="note">You gave <?php echo @$value?> Hearts.</div>
		<?php }else{?>
		<div id="note">Click To Give Upto 5 Hearts</div>
		<?php }?>
	</div>
	
