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

<!-- BEGIN HEADER -->
<div id="header">
  <?php if ($logo) : ?>
  <a href="<?php print url() ?>" title="Index Page"><img src="<?php print($logo) ?>" alt="Logo" /></a>
  <?php endif; ?>

  <?php if (is_array($secondary_links)) : ?>
    <div id="secondary">
    <?php foreach ($secondary_links as $link): ?>
      <li><?php print $link?></li>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
<!-- END HEADER -->

<!-- BEGIN MENUBAR -->
<div id="top-nav">
 
   <!-- BEGIN LOGIN -->
   <span id="login">
   <?php if ($user->uid) : ?>
    Logged in as: <?php print l($user->name,'user/'.$user->uid); ?> | <?php print l("logout","logout"); ?>
    <?php else : ?>
       <?php print l("Register","user/register"); ?> | Login: <form action="user/login" method="post"><input type="hidden" name="edit[destination]" value="user" /><input type="text" maxlength="64" class="form-text" name="edit[name]" id="edit-name" size="15" value="" /><input type="password" class="form-password" maxlength="64" name="edit[pass]" id="edit-pass" size="15" value="" /><input type="submit" class="form-submit" name="op" value="Log in"  /></form>
    <?php endif; ?>
   </span>
   <!-- END LOGIN -->
 
 <?php if (is_array($primary_links)) : ?>
    <ul id="primary">
    <?php foreach ($primary_links as $link): ?>
      <li><?php print $link?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</div>
<!-- END MENUBAR -->

<div id="leftSide">
	<?php if ($sidebar_left != ""): ?>

			 <div class="sidebar" id="sidebar-left"> 
			 <div class="sidebar-inner_left" id="sidebar-inner-left">
				<?php print $sidebar_left ?>
				</div>
			 </div> 
			<?php endif; ?>
</div>

<div id="rightSide">   
	<?php if ($sidebar_right != ""): ?>
		<div class="sidebar" id="sidebar-right">
			
				<?php print $sidebar_right ?>
		</div>
		<?php endif; ?>		
</div>
<div id="center">
<!-- <?php print $layout ?> -->
				<?php print $breadcrumb ?>
				<?php if ($title != ""): ?>
					<h2 class="content-title"><?php print $title ?></h2>
				<?php endif; ?>
				<?php if ($tabs != ""): ?>
					<?php print $tabs ?>
				<?php endif; ?>
				
				<?php if ($mission != ""): ?>
				 <div id="mission"><?php print $mission ?></div>
				<?php endif; ?>
				
				<?php if ($help != ""): ?>
					<p id="help"><?php print $help ?></p>
				<?php endif; ?>
				
				<?php if ($messages != ""): ?>
					<div id="message"><?php print $messages ?></div>
				<?php endif; ?>
				
				<!-- start main content -->
				<?php print($content) ?>
				<!-- end main content -->
				<!-- mainContent -->	

</div>


<div id="footer-partners">
 <!--  <?php if ($footer_message) : ?>
    <p><?php print $footer_message;?></p>
  <?php endif; ?>
Validate <a href="http://validator.w3.org/check/referer">XHTML</a> or <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>. -->
<!-- footer -->	
<!-- Begin Partner Footer Logos-->
<p align="center"><b><font face="Tahoma">Ourmedia Partners<br></font></b><a href="mission/partners"><img border="0" src="<?php print($directory) ?>/partners_logos_1.gif"></a></td>
</div> 




 <?php print $closure;?>
  </body>
</html>
