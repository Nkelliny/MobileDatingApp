<section id="content" class="loginbox">
  <form action="<?php echo site_url('login'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <p>
      <label>Username:</label>
      <br />
      <input type="text" class="text" name="email" id="pass" value="" />
    </p>
    <p>
      <label>Password:</label>
      <br />
      <input name="pass" id="pass" type="password" class="text" value="" />
    </p>
    <p class="formend">
      <input type="submit" class="submit" value="Login" />
      &nbsp;
      <input type="checkbox" class="checkbox" checked="checked" id="rememberme" />
      <label for="rememberme">Remember me</label>
    </p>
  </form>
</section>
<!-- .loginbox ends -->