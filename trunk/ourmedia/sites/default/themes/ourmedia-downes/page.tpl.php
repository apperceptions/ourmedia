<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
 
<head>

<title><?php print $head_title ?></title>
<meta http-equiv="Content-Style-Type" content="text/css" />
<?php print $head ?>
<?php print $styles ?>

</head>

<body <?php print theme("onload_attribute"); ?>>

<!-- Begin Header -->
<div id="header">

  <!-- Secondary Links -->
  <?php if (is_array($secondary_links)): $count=0;?>
  <div id="secondary">
    <?php foreach ($secondary_links as $link):
      if ($count>0): print " | "; endif; 	 $count++;
      print "$link\n";
    endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Logo -->
  <?php if ($logo) : ?>
  <a href="<?php print url() ?>" title="Index Page"><img src="<?php print($logo) ?>" alt="OurMedia" /></a>
  <?php endif; ?>

</div>
<!-- End Header -->

<!-- Begin Navbar -->
<div id="navbar">

  <!-- Login -->
  <span id="login">
  <?php global $user; ?>
  <?php if ($user->uid) : ?>
  Logged in as: <?php print l($user->name,'user/'.$user->uid); ?> | <?php print l("logout","logout"); ?>
  <?php else : ?>
  <?php print l("Register","user/register"); ?> | Login: <form action="user/login" method="post"><input type="hidden" name="edit[destination]" value="user" /><input type="text" maxlength="64" class="form-text" name="edit[name]" id="edit-name" size="15" value="" /><input type="password" class="form-password" maxlength="64" name="edit[pass]" id="edit-pass" size="15" value="" /><input type="submit"  name="op" value="Log in"  /></form>
  <?php endif; ?>
  </span>

  <!-- Primary Links -->
  <?php if (is_array($primary_links)) : ?>
  <ul id="primary">
  <?php foreach ($primary_links as $link): ?>
    <li><?php print $link?></li>
  <?php endforeach; ?>
  </ul>
  <?php endif; ?>

</div>
<!-- End Navbar -->

<div id="shadow">
&nbsp;
</div>


<!-- Begin Left Column -->

<?php if ($sidebar_left != ""): ?>
  <div id="left_column"><?php print $sidebar_left ?></div>
<?php endif; ?>


<!-- End Left Column -->

<!-- Begin Right Column -->

<div id="right_column">
  <div id="inside_ourmedia">Inside Ourmedia.org</div>
  <?php if ($sidebar_right != ""): ?>
    <?php print $sidebar_right ?>
  <?php endif; ?>
</div>

<!-- End Right Column -->

<!-- Begin Center Column -->
<div id="center_column">

  <!-- <?php print $layout ?> -->

  <!-- Title Bar -->
  <?php if ($title != "Ourmedia Homepage"):?>
  <div id="center_content">
     <div id="title_bar">
        <h1><?php print $title ?></h1>
        <?php print $breadcrumb; ?>
     </div>

    <!-- Tabs -->
    <?php if ($tabs != ""): ?>
    <?php print $tabs ?>
    <?php endif; ?>

     <!-- Main page content -->
     <div id="center_body">
  <?php endif; ?>



  <!-- Mission removed from here -->


  <!-- Help -->
  <?php if ($help != ""): ?>
  <?php print $help ?>
  <?php endif; ?>

  <!-- Messages -->
  <?php if ($messages != ""): ?>
  <?php print $messages ?>
  <?php endif; ?>

  <!-- Main content -->

  <?php print($content) ?>
  <!-- mainContent -->

  <?php if ($title != "Ourmedia Homepage"):?>
    <!-- End Center Body -->
    </div>
  <?php endif; ?>

  <!-- Begin Footer -->
  <div id="footer">
    <!-- Begin Partner Footer Logos-->
    <h2><a href="mission/partners">Sponsors and partners</a></h2>
    <a href="http://www.archive.org/"><img border="0" src="<?php print($directory) ?>/ia.GIF"></a><a href="http://www.outhink.com"><img border="0" src="<?php print($directory) ?>/spinxpress.gif"></a><a href="http://www.creativecommons.org/"><img border="0" src="<?php print($directory) ?>/cc.GIF"></a><a href="http://www.socialtext.com"><img border="0" src="<?php print($directory) ?>/socialtext.gif"></a><a href="http://www.broadbandmechanics.com/"><img border="0" src="<?php print($directory) ?>/bbm.GIF"></a>
    
    <!-- Secondary Links -->
    <?php if (is_array($tertiary_links)): $count=0;?>
    <div id="tertiary">
      <?php foreach ($tertiary_links as $link):
        if ($count>0): print " | "; endif; 	 $count++;
        print "$link\n";
      endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Closure -->
    <?php print $closure;?>

    <!-- Footer Message -->
    <?php if ($footer_message) : ?><?php print $footer_message;?><?php endif; ?>

    <!-- Temporary Storage -->
    <!-- Mission -->
  </div>
  <!-- End Footer -->


</div>
<!-- End Center Column -->




<!-- Uncomment to test code for IE box sixze offset -->
<!--div class="sample_div"-->
<!--Test-->
<!--/div-->

</body>
</html>
