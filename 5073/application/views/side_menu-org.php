<?php
$cpg = $this->uri->segment(1);
?>
<aside>
  <ul id="nav">
    <li><a href="<?php echo site_url(); ?>"><strong><img src="/5073/images/nav/dashboard.png" alt="" /> Dashboard</strong></a></li>
    <?php
	if($cpg == "comments" || $cpg == "keywords" || $cpg == "pages" || $cpg == "badwords" || $cpg == "messages" || $cpg == "faq")
	{
		$content_class = '';
	}
	else
	{
		$content_class = ' class="collapse"';
	}
	?>
    <li><a href="#" <?php echo $content_class; ?>><img src="/5073/images/nav/pages.png" alt="" /> Pages / Content</a>
      <ul>
        <li><a href="<?php echo site_url('pages'); ?>">Site Pages</a></li>
        <li><a href="<?php echo site_url('faq'); ?>">FAQ's</a></li>
        <!-- <li><a href="<?php //echo site_url('messages'); ?>">Messages</a></li> -->
        <!-- <li><a href="<?php //echo site_url('comments/pending'); ?>">Pending Comments</a></li> -->
        <!-- <li><a href="<?php //echo site_url('comments'); ?>">All Comments</a></li> -->
        <li><a href="<?php echo site_url('badwords'); ?>">Bad Word List</a></li>
  		<li><a href="<?php echo site_url('keywords'); ?>">Linked Keywords</a></li>
      </ul>
    </li>
    <?php
	if($cpg == "photos")
	{
		$photos_class = '';
	}
	else
	{
		$photos_class = ' class="collapse"';
	}
	?>
    <li><a href="#"<?php echo $photos_class; ?>><img src="/5073/images/nav/media.png" alt="" /> Photos</a>
      <ul>
        <li><a href="<?php echo site_url('photos'); ?>">Approve APP Photos</a></li>
        <li><a href="<?php echo site_url('photos/recent'); ?>">Recently Approved Photos</a></li>
  		<li><a href="<?php echo site_url('photos/choose/17'); ?>">Female Profile Pics</a></li>
        <li><a href="<?php echo site_url('photos/allimages/17'); ?>">All Female Pics</a></li>
  		<li><a href="<?php echo site_url('photos/choose/18'); ?>">Male Profile Pics</a></li>
        <li><a href="<?php echo site_url('photos/allimages/18'); ?>">All Male Pics</a></li>
  		<li><a href="<?php echo site_url('photos/choose/19'); ?>">Ladyboy Profile Pics</a></li>
        <li><a href="<?php echo site_url('photos/allimages/19'); ?>">All Ladyboy Pics</a></li>
  		<li><a href="<?php echo site_url('photos/mailer'); ?>">Mailer Photos</a></li>
        <li><a href="<?php echo site_url('photos/deleted'); ?>">Deleted Photos</a></li>
      </ul>
    </li>
    <!-- <li><a href="#"><img src="/5073/images/nav/calendar.png" alt="" /> Calendar</a></li> -->
    <?php
    if($cpg == "users" || $cpg == "reported")
	{
		$users_class = '';
	}
	else
	{
		$users_class = ' class="collapse"';
	}
    ?>
    <li><a href="#" <?php echo $users_class; ?>><img src="/5073/images/nav/users.png" alt="" /> Users</a>
      <ul>
        <!-- <li><a href="#">Add new user</a></li> -->
        <!-- <li><a href="#">User groups</a></li> -->
        <li><a href="<?php echo site_url('users/contacts'); ?>">Contacts</a></li>
        <li><a href="<?php echo site_url('users'); ?>">App Users</a></li>
        <!-- <li><a href="<?php //echo site_url('users/vip'); ?>">VIP Users</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/mobile'); ?>">App Users</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/mobilef'); ?>">App Female</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/mobilem'); ?>">App Male</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/mobilel'); ?>">App Ladyboy</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/banned'); ?>">Banned Users</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/suspended'); ?>">Suspended Users</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/suspicious'); ?>">Suspicious Users</a></li> -->
        <!-- <li><a href="<?php //echo site_url('users/all'); ?>">All Users</a></li> -->
        <li><a href="<?php echo site_url('users/export'); ?>">Mail List</a></li>
        <li><a href="<?php echo site_url('reported'); ?>">Reported Users</a></li>
      </ul>
    </li>
    <li><a href="#"><img src="/5073/images/nav/settings.png" alt="" /> Settings</a>
    <ul>
    <!-- <li><a href="<?php echo site_url('promos'); ?>">Promo Codes</a></li> -->
    <li><a href="<?php echo site_url('advert'); ?>">Ads</a></li>
    <li><a href="<?php echo site_url('pfields'); ?>">Profile Fields</a></li>
    <li><a href="<?php echo site_url('etemps'); ?>">Email Templates</a></li>
    <li><a href="<?php echo site_url('apns'); ?>">APNS</a></li>
    <li><a href="<?php echo site_url('translate');?>">Translations</a></li>
    </ul>
    </li>
    <li><a href="#"><img src="/5073/images/nav/settings.png" alt="" /> SMS / Contacts</a>
    <ul>
    <li><a href="<?php echo site_url('users/contactkeys'); ?>">Approve Keys</a></li>
    <li><a href="<?php echo site_url('users/contactnumbers'); ?>">Approve Numbers</a></li>
    <li><a href="<?php echo site_url('users/sendsms'); ?>">Send SMS</a></li>
    </ul>
    </li>
    <li><a href="#"><!-- <span>12</span> --><img src="/5073/images/nav/support.png" alt="" /> Support</a></li>
  </ul>
  <!-- <section class="status_box"> --> 
  <!--   <ul> --> 
  <!--     <li><a href="#" class="online" title="Online">Web server 1</a></li> --> 
  <!--     <li><a href="#" class="online" title="Online">Web server 2</a></li> --> 
  <!--     <li><a href="#" class="warning" title="Warning">DB server</a></li> --> 
  <!--     <li><a href="#" class="offline" title="Offline">Mail server</a></li> --> 
  <!--   </ul> --> 
  <!-- </section> --> 
</aside>
<!-- aside ends -->