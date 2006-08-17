<?php
  $name = $account->name;
  $full_name = $account->profile_fullname;
  $first_name = $account->profile_firstname;
  if (!$first_name) {
    $first_name = $name;
  }
  $country = $account->profile_country;
  $region = $account->profile_region;
  $city = $account->profile_city;
  $aim = $account->profile_aim;
  $icq = $account->profile_icq;
  $msn = $account->profile_msn;
  $yahoo = $account->profile_yahoo;
  $jabber = $account->profile_jabber;
  $work_title = $account->profile_worktitle;
  $organization = $account->profile_organization;
  $work_homepage = $account->profile_work_homepage;
  $privatemsg_allow = $account->privatemsg_allow;
  $motto = $account->profile_motto;
  $bio = $account->profile_biography;
  $mail = $account->mail;
  $blog_url = $account->profile_blogurl;
  $blog_title = $account->profile_blogtitle;
  if (!$blog_title) {
	$blog_title = $blog_url;
  }
  foreach ($account->og_groups as $key => $val) {
    $og_groups[] = l($val['title'], "node/$key");
  }

?>


<!-- Begin My-Page Specific Content -->

<div id="mypage-main">

	<!-- Top Panel -->
	<div id="mypage-toppanel">

		<!-- Image -->
		<div id="mypage-imagepanel">
			<?php print(get_user_picture($account)); ?>
			<?php if ($work_homepage) { ?>
				<div class="font-small"><br/><a href="<?php print($work_homepage);?>">Home Page</a></div>
			<?php } ?>
            <?php if ($blog_url) { ?>
                <div class="font-small"><a href="<?php print($blog_url); ?>">Blog:</a> <?php print($blog_title) ?></div>
            <?php } ?>
            <div id="motto-text"><?php print($motto)?></div>
		</div>
		<!-- Profile Details -->
		<div id="mypage-profile-details">
			<span class="profile-name"><?php print($full_name)?><br /></span>
			<span class="profile-city"><?php print($city);if ($city && $region) {
				print(" , ");} print($region."<br />".$country."<br />");
			?><br /></span>
            <?php if ($bio) { ?>
                <span class="font-small"><b>BIO:</b><br><?php print($bio) ?></span>
            <?php } ?>
			<span class="font-small">
        		<br/><br/>Ourmedia member since <?php print(date("m/Y",$account->created)) ?> <br/><br/>
        		<?php print(get_private_msg($account)) ?><br />
				<?php print(get_add_remove_buddy_link($account))?><br /><br />
				<?php if ($aim || $yahoo || $msn || $icq || $jabber) { ?>Contact <?php print($first_name) ?> by:<br /><?php } ?>
				<?php if ($aim) { ?><b>AIM: </b><?php print($aim)?><br /><?php } ?>
				<?php if ($yahoo) { ?><b>Yahoo: </b><?php print($yahoo)?><br /><?php } ?>
				<?php if ($msn) { ?><b>MSN: </b><?php print($msn)?><br /><?php } ?>
				<?php if ($icq) { ?><b>ICQ: </b><?php print($icq)?><br /><?php } ?>
				<?php if ($jabber) { ?><b>Jabber: </b><?php print($jabber)?><br /><?php } ?><br />
			</span>
		</div>
	</div>
</div>

</div>  <!-- Closes center content, My page-specific formatting follows) -->
	<!-- Buddies -->

	<div id="mypage-buddylist">
		<div class="mypage-buddies">
			<h3><?php print($first_name) ?>'s Buddies (<?php print(count($buddylist)); ?>)</h3>
			<div class="mypage-buddylistnodes">
				<span class="font-small">
				<?php
					if ($buddylist) { foreach ($buddylist as $buddy) { print(format_name($buddy)." "); } }
					else { print ("No buddies yet"); } ?>
				</span>
			</div>
		</div>
        </div>
        <div id="mypage-buddylist">
		<div class="mypage-buddies">
			<h3>People who call <?php print($first_name) ?> their buddy (<?php print(count($buddiesof)); ?>)</h3>
			<div class="mypage-buddylistnodes">
				<span class="font-small">
				<?php
					if ($buddiesof) { foreach ($buddiesof as $buddy) { print(format_name($buddy)." "); } }
					else { print ("No one yet calls $first_name their buddy");} ?>
				</span>
			</div>
		</div>
	</div>
	<!-- My Groups Section -->
        <div id="mypage-buddylist">
		<div class="mypage-buddies">
			<h3><?php print($first_name) ?>'s Groups (<?php print(count($og_groups)) ?>)</h3>
			<div class="mypage-buddylistnodes">
				<span class="font-small">
				<?php
					if ($og_groups) {
					  print theme('item_list', $og_groups);
					}
					else { print ("Not subscribed to any group yet.");} ?>
				</span>
			</div>
		</div>
	</div>
<!-- Published Media -->

<div id="mypage-media">
	<div id="mypage-published-media">
		<h3><?php print($first_name) ?>'s Published Media</h3>

		<!-- Video -->
		<?php $videos = get_media_for_user($account,'videomedia'); ?>
		<?php if(count($videos) > 0) { ?>
		<div class="mypage-media-titlerow">
			<div class="mypage-media-titleprop">DOWNLOADS</div>
			<div class="mypage-media-titleprop">SIZE</div>
			<div class="mypage-media-titleprop">FILE</div>
            <div class="mypage-media-titlename">Video</div>
		</div>

      <?php foreach ($videos as $videoitem) { ?>
		<!-- Note: title must be less than 150 px long or it will screw up formatting... -->
		<!-- Recommend abbreviating titles at, say, 20 characters --->
		<!-- Also, what are the proper names for size, download elements? -->
		<!-- &nbsp; in field forces box to appear even if empty -->
		<div class="mypage-media-contentrow">
		  <div class="mypage-media-contentname"><?php print($videoitem['title']) ?></div>
		  <div class="mypage-media-contentprop"><?php print($videoitem['filename']) ?>&nbsp;</div>
		  <div class="mypage-media-contentprop"><?php print($videoitem['filesize']) ?>&nbsp;</div>
		  <div class="mypage-media-contentprop-last"><?php print($videoitem['downloads']) ?>&nbsp;</div>
		</div>
      <?php } ?>
      <?php if (count($videos) > 9) { $link = l("more", "allmedia&uid=$account->uid"); print "<div class=\"more-link\">$link</div>"; } ?>
      <?php } ?>

		<!-- Audio -->
    <?php $audios = get_media_for_user($account,'audiomedia'); ?>
    <?php if(count($audios) > 0) { ?>
		<div class="mypage-media-titlerow">
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-titlename">Audio</div>
		</div>

      <?php foreach ($audios as $audioitem) { ?>
		<!-- Note: title must be less than 150 px long or it will screw up formatting... -->
		<!-- Recommend abbreviating titles at, say, 20 characters --->
		<!-- Also, what are the proper names for size, download elements? -->

		<div class="mypage-media-contentrow">
		  <div class="mypage-media-contentname"><?php print($audioitem['title']) ?></div>
		  <div class="mypage-media-contentprop"><?php print($audioitem['filename']) ?>&nbsp;</div>
		  <div class="mypage-media-contentprop"><?php print($audioitem['filesize']) ?>&nbsp;</div>
			<div class="mypage-media-contentprop-last"><?php print($audioitem['downloads']) ?>&nbsp;</div>
		</div>
      <?php } ?>
      <?php if (count($audios) > 9) { $link = l("more", "allmedia&uid=$account->uid"); print "<div class=\"more-link\">$link</div>"; } ?>
    <?php } ?>

		<!-- Images -->
    <?php $images = get_media_for_user($account,'imagemedia'); ?>
		<?php if(count($images) > 0) { ?>
		<div class="mypage-media-titlerow">
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-titlename">Images</div>
		</div>

      <?php foreach ($images as $imageitem) { ?>
		<div class="mypage-media-contentrow">
		  <div class="mypage-media-contentname"><?php print($imageitem['title']) ?></div>
		  <div class="mypage-media-contentprop"><?php print($imageitem['filename']) ?>&nbsp;</div>
		  <div class="mypage-media-contentprop"><?php print($imageitem['filesize']) ?>&nbsp;</div>
			<div class="mypage-media-contentprop-last"><?php print($imageitem['downloads']) ?>&nbsp;</div>
		</div>
      <?php } ?>
      <?php if (count($images) > 9) { $link = l("more", "allmedia&uid=$account->uid"); print "<div class=\"more-link\">$link</div>"; } ?>
    <?php } ?>

		<!-- Text -->
		<?php $texts = get_media_for_user($account,'textmedia'); ?>
		<?php if(count($texts) > 0) { ?>
		<div class="mypage-media-titlerow">
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-spaceprop">&nbsp;</div>
			<div class="mypage-media-titlename">Text</div>
		</div>

      <?php foreach ($texts as $textitem) { ?>
		<!-- Note: title must be less than 150 px long or it will screw up formatting... -->
		<!-- Recommend abbreviating titles at, say, 20 characters --->
		<!-- Also, what are the proper names for size, download elements? -->
		<div class="mypage-media-contentrow">
		  <div class="mypage-media-contentname"><?php print($textitem['title']) ?></div>
		  <div class="mypage-media-contentprop"><?php print($textitem['filename']) ?>&nbsp;</div>
		  <div class="mypage-media-contentprop"><?php print($textitem['filesize']) ?>&nbsp;</div>
			<div class="mypage-media-contentprop-last"><?php print($textitem['downloads']) ?>&nbsp;</div>
		</div>
      <?php } ?>
      <?php if (count($texts) > 9) { $link = l("more", "allmedia&uid=$account->uid"); print "<div class=\"more-link\">$link</div>"; } ?>
    <?php } ?>
    <?php if (!count($videos) && !count($audios) && !count($texts) && !count($images)) { ?>
	 <div class="mypage-media-contentrow">
      No media published yet.
     </div>
    <?php } else {?>
    <div class="xml-icon"><a href="mediarss/user/<?= $account->uid?>"><img src="misc/rss.gif" width="36" height="14" alt="XML feed" title="XML feed" /></a>&nbsp;&nbsp;  <a href="mediarss/user/<?= $account->uid?>"><img src="misc/mRSS.gif" width="36" height="14" alt="media RSS feed" title="media RSS feed" /></a>&nbsp;&nbsp; Subscribe to member's media uploads <a href="http://www.ourmedia.org/node/41292">(What's this?)</a></div>
    <?php } ?>
	</div>
</div>

<!-- Weblog -->
<div id="weblog">
  <div class="mypage-nodes">
    <h1><?php print($first_name) ?>'s Blog</h1>
    <?php print(get_recent_blog_entries($account))?>
  </div>
</div>

<div class="mypage-postlist">

  <!-- Ourmedia Blog Posts -->
  <h2><?php print($first_name) ?>'s Ourmedia Blog Posts</h2>
  <?php $items = get_group_blog_posts_for_user($account); foreach ($items as $current_item) { ?>
  <div class="mypage-post-row">
    <div class="mypage-post-title"><?php print($current_item['timestamp']) ?></div>
    <div class="mypage-post-date"><?php print($current_item['title']) ?></div>
  </div>
  <?php } ?>

  <!-- Ourmedia Forum Posts -->
  <div class="mypage-postlist-divider">&nbsp;</div>
  <h2><?php print($first_name) ?>'s Forum Posts</h2>
  <?php $items = get_forum_topics_for_user($account); foreach ($items as $current_item) { ?>
  <div class="mypage-post-row">
    <div class="mypage-post-title"><?php print($current_item['timestamp']) ?></div>
    <div class="mypage-post-date"><?php print($current_item['title']) ?></div>
  </div>
  <?php } ?>
</div>

<div> <!-- Reopens center-content div, which will be closed by main page template -->
