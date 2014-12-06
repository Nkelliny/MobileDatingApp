<?php
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>xxxxxx - Admin Panel Interface</title>
<meta name="description" content="." />
<meta name="keywords" content="." />
<meta name="viewport" content="width=device-width" />
<link rel="stylesheet" href="/5073/css/style.css">
<link rel="stylesheet" href="/5073/css/responsive.css">
<link rel="stylesheet" href="/5073/css/visualize.css">
<link rel="stylesheet" href="/5073/css/date_input.css">
<link rel="stylesheet" href="/5073/css/jquery.minicolors.css">
<link rel="stylesheet" href="/5073/css/jquery.wysiwyg.css">
<link rel="stylesheet" href="/5073/css/jquery.fancybox.css">
<link rel="stylesheet" href="/5073/css/tipsy.css">
<!--[if lt IE 9]>
<link rel="stylesheet" href="/5073/css/ie.css">
<script src="/5073/js/html5shiv.js"></script>
<![endif]-->
<link rel="apple-touch-icon-precomposed" href="/images/ios_icon.png" />
<script type="text/javascript" src="/5073/js/admin.js"></script>
</head>
<body>
<header>
  <h1><a href="<?php echo site_url(); ?>">xxxxxx</a></h1>
  <?php 
	if($this->session->userdata('adauth') == "yes")
	{
	?>
  <form action="" method="post" class="searchform">
    <input type="text" class="text" value="Search..." />
    <input type="submit" class="submit" value="" />
  </form>
  	<section class="userprofile">
  	  <ul>
  	    <li><a href="#"><?php echo $this->my_usersmanager->getNickname($this->session->userdata('uid')); ?></a>
  	      <ul>
  	        <li><a href="#">Profile</a></li>
  	        <li><a href="#">Messages</a></li>
  	        <li><a href="<?php echo site_url('login/logout'); ?>">Logout</a></li>
  	      </ul>
  	    </li>
  	  </ul>
  	</section>
  	<!-- .userprofile ends --> 
	<?php
	}
	?>
</header>
<!-- #header ends -->
<?php 
if($this->session->userdata('adauth') == "yes")
{
	$this->load->view('side_menu'); ?>
	<section id="content">
	<div class="breadcrumb"> 
	<!-- <a href="#">Adminium</a> &raquo; <a href="#">Section title</a> &raquo; <a href="#">Subsection title</a> &raquo; <a href="#">Page title</a> --> 
	</div>
	<!-- .breadcrumb ends -->
	<?php
}
?>    