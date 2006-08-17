
<!-- Begin Homepage_View -->

<script language="javascript" src = "<?php print path_to_theme() ?>/homepage.js"></script>
<div id="homepage">

<!-- Welcome Text -->
<div id="welcome">
  <div id="welcome-alpha"> <!-- Places 'alpha' image in background -->
    <h2><?php print "$welcome_title"?></h2>
	<ul>
<li><b>Publish and store video, audio and other media that <u>you</u> created!</b></li>
<li><b>Share and discover independent media. Connect to a global community!</b></li>
<li><b>Learn how to create citizens media. Free storage & bandwidth forever!</b></li>
<li><b>Do NOT post other artists' copyrighted works without permission. Ourmedia is about showcasing <u>your</u> creativity! <a href="user/register" title="why join">Register now</a>!</b></li>
</ul>
  </div>
</div>
<div id="need_ia_account"><img src="<?php print path_to_theme() ?>/requires-tab.jpg"></div>
<!-- Media Area -->
<div id="media_area">

  <div class="movie_media">
 
    <iframe id="featuredVideo" border="2" bordercolor="#fff" frameborder="0" scrolling="no"
      src="<?php print path_to_theme() ?>/featured_video.php?video=<?php print $featured_videos[0]['video_url'] ?>&bigscreenshot=<?php print $featured_videos[0]['big_screenshot_url']?>"
      width="334" height="278">
    </iframe>
  </div>
  <input id= "videourl" name="videourl" type="hidden" value=<?php print $featured_videos[0]['video_url'] ?> />
  <div class="media_description" id="media_description">

    <div class="media_artist"><span class="media_item"><?php print $featured_videos[0]['artist_name'][0] ?></span>:
    <?php print $featured_videos[0]['artist_name'][1] ?><br/>

    <span class="media_item"><?php print $featured_videos[0]['media_title'][0] ?></span>:
    <?php print $featured_videos[0]['media_title'][1] ?></div>

    <div class="media_file"><span class="media_item"><?php print $featured_videos[0]['media_type'][0] ?></span>:
    <?php print $featured_videos[0]['media_type'][1] ?><br/>

    <span class="media_item"><?php print $featured_videos[0]['field_custom3'][0] ?></span>:
    <?php print $featured_videos[0]['field_custom3'][1] ?><br/>

    <span class="media_item"><?php print $featured_videos[0]['field_custom2'][0] ?></span>:
    <?php print $featured_videos[0]['field_custom2'][1] ?><br/>

    <span class="media_item"><?php print $featured_videos[0]['field_custom1'][0] ?></span>:
    <?php print $featured_videos[0]['field_custom1'][1] ?><br/><br />    
   </div>
   <div class="media_playbtn">     
     <img src="<?php print path_to_theme() ?>/white-play.gif" onclick="playselectedvideo('<?php print path_to_theme() ?>')" >
   </div>
   <div class="media_goto"> 
    GO TO <a href="<?php print $featured_videos[0]['media_page_url']?>">media page</a><br>

    GO TO <a href="<?php print $featured_videos[0]['artist_media_page_url'] ?>">member page</a>
    </div>
  </div>
</div>


<!-- Thumbnail and Preview Area -->
<div class="thumbnail_area">
<!-- Thumbnails -->
<?php
	    $i = 0;
	    foreach($featured_videos as $current_video) {
	      $img_preview_url = $current_video['preview_url'];
	      $img_video_url = $current_video['video_url'];
	      $img_title_label = $current_video['media_title'][0];
	      $img_title = $current_video['media_title'][1];
	      $img_artist_label = $current_video['artist_name'][0];
	      $img_artist = $current_video['artist_name'][1];
	      $img_media_type_label = $current_video['media_type'][0];
	      $img_media_type = $current_video['media_type'][1];
	      $img_artist_media_page_url = $current_video['artist_media_page_url'];
  	      $img_media_page_url = $current_video['media_page_url'];
	      $img_custom1_label = $current_video['field_custom1'][0];
	      $img_custom1 = $current_video['field_custom1'][1];
	      $img_custom2_label = $current_video['field_custom2'][0];
	      $img_custom2 = $current_video['field_custom2'][1];
	      $img_custom3_label = $current_video['field_custom3'][0];
	      $img_custom3 = $current_video['field_custom3'][1];
	      //$label_file = path_to_theme() . "/images/label-". $img_media_type .".gif";
	      $label_file = path_to_theme()."/label-".strtolower(str_replace(" ", "", $img_media_type)) .".gif";
	      print "<!-- label file is $label_file -->";
	      if (file_exists($label_file)) {
                $media_type_img = $label_file;
	      }
	      else {
                $media_type_img = path_to_theme() . "/images/label-video.gif";
              }
              $path2theme = path_to_theme();
              print "   <div class=\"featured_video\"><img class=\"tmb\" src=\"$img_preview_url\" width='62px' height='45px' onclick='updatefeaturedvideo(\"$img_preview_url\", \"$img_video_url\",\"$img_title_label\",\"$img_title\",\"$img_artist_label\",\"$img_artist\",\"$img_media_type_label\",\"$img_media_type\",\"$img_artist_media_page_url\", \"$img_media_page_url \", \"$img_custom1_label\", \"$img_custom1\", \"$img_custom2_label\", \"$img_custom2\", \"$img_custom3_label\", \"$img_custom3\", \"$path2theme\")' /><br /><img src=$media_type_img /></div>";
              $i++;
	      if($i > 14) {	break; }
	    }
	  ?>

</div>

<!-- Media List Area -->
<div class="medialist_area">



    <!-- Current Music -->
  <div class="media_list"><h3>MUSIC</h3>
  <?php foreach($music as $current_music) {?>
    <div class="media_recent">
    <p class="l"><span class="media_item"><?php print $current_music['media_author'] ?></span></p>
    <p class="l"><?php print $current_music['media_title'] ?></p>
    <p class="l">
    <span style="float:right;margin-right: 5px;"><a href="<?php print $current_music['media_url'] ?>">Play <?php print $current_music['format'] ?></a></span>
    <a href="<?php print $current_music['media_page_url'] ?>">media page</a></p>
    </div>
  <?php } ?>
  </div>

    <!-- Current Audio -->
  <div class="media_list"><h3>AUDIO</h3>
  <?php foreach($audio as $current_audio) {?>
    <div class="media_recent">
    <p class="l"><span class="media_item"><?php print $current_audio['media_title'] ?></span></p>
    <p class="l"><?php print $current_audio['field_custom1'] ?></p>
    <p class="l">
    <span style="float:right;margin-right: 5px;"><a href="<?php print $current_audio['media_url'] ?>">Play <?php print $current_audio['format'] ?></a></span>
    <a href="<?php print $current_audio['media_page_url'] ?>">media page</a></p>
    </div>
  <?php } ?>
  </div>

    <!-- Current Text -->
  <div class="media_list"><h3>TEXT</h3>
  <?php foreach($text as $current_text) {?>
    <div class="media_recent">
    <p class="l"><span class="media_item"><?php print $current_text['media_title'] ?></span></p>
    <p class="l"><?php print $current_text['field_custom1'] ?></p>
    <p class="l">
    <span style="float:right;margin-right: 5px;"><a href="<?php print $current_text['media_url'] ?>">Open <?php print $current_text['format'] ?></a></span>
    <a href="<?php print $current_text['media_page_url'] ?>">media page</a></p>
    </div>
  <?php } ?>
  </div>




</div> <!-- End Media List Area -->

<!-- Weblog -->
<div id="weblog">
<h1>Ourmedia Weblog</h1>
<?php print $blog ?>
</div>
</div>
