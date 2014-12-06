<?php
$cpg = $this->uri->segment(1);
$cpgb = $this->uri->segment(2,'na');
$query = $this->db->query('SELECT * FROM `admin_pages` WHERE `status` = "1"');
$pages = $query->result();
$query = $this->db->query('SELECT `apgs` FROM `users` WHERE `id` = "'.$this->session->userdata('uid').'"');
$row = $query->row();
$cpgs = explode(',',$row->apgs);
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
        <?php
		foreach($pages as $p)
		{
			if($p->section == "Pages / Content")
			{
				if(in_array($p->id,$cpgs))
				{
					?>
                	<li><a href="<?php echo $p->url; ?>"><?php echo $p->name; ?></a></li>
                	<?php
				}
			}
		}
		?>
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
        <?php
		foreach($pages as $p)
		{
			if($p->section == "Photos")
			{
				if(in_array($p->id,$cpgs))
				{
				?>
                <li><a href="<?php echo $p->url; ?>"><?php echo $p->name; ?></a></li>
                <?php
				}
			}
		}
		?>
      </ul>
    </li>
    <!-- <li><a href="#"><img src="/5073/images/nav/calendar.png" alt="" /> Calendar</a></li> -->
    <?php
    if(($cpg == "users" || $cpg == "reported" || $cpg == "subscriptions") && ($cpgb != "contactkeys" && $cpgb != "contactnumbers" && $cpgb != "sendsms"))
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
        <?php
		foreach($pages as $p)
		{
			if($p->section == "Users")
			{
				if(in_array($p->id,$cpgs))
				{
				?>
                <li><a href="<?php echo $p->url; ?>"><?php echo $p->name; ?></a></li>
                <?php
				}
			}
		}
		?>
      </ul>
    </li>
    <?php
	if($cpg == "promos" || $cpg == "advert" || $cpg == "pfields" || $cpg == "etemps" || $cpg == "apns" || $cpg == "translate")
	{
		$settings_class = '';
	}
	else
	{
		$settings_class = ' class="collapse"';
	}
	?>
    <li><a href="#" <?php echo $settings_class; ?>><img src="/5073/images/nav/settings.png" alt="" /> Settings</a>
    <ul>
    <?php
	foreach($pages as $p)
	{
		if($p->section == "Settings")
		{
			if(in_array($p->id,$cpgs))
				{
			?>
            <li><a href="<?php echo $p->url; ?>"><?php echo $p->name; ?></a></li>
            <?php
				}
		}
	}
	?>
    </ul>
    </li>
    <?php
	if($cpgb == "contactkeys" || $cpgb == "contactnumbers" || $cpgb == "sendsms")
	{
		$contact_class = '';
	}
	else
	{
		$contact_class = ' class="collapse"';
	}
	?>
    <li><a href="#" <?php echo $contact_class; ?>><img src="/5073/images/nav/settings.png" alt="" /> SMS / Contacts</a>
    <ul>
    <?php
	foreach($pages as $p)
	{
		if($p->section == "SMS / Contacts")
		{
			if(in_array($p->id,$cpgs))
				{
			?>
            <li><a href="<?php echo $p->url; ?>"><?php echo $p->name; ?></a></li>
            <?php
				}
		}
	}
	?>
    </ul>
    </li>
    <li><a href="#"><!-- <span>12</span> --><img src="/5073/images/nav/support.png" alt="" /> Support</a></li>
  </ul>
</aside>
<!-- aside ends -->