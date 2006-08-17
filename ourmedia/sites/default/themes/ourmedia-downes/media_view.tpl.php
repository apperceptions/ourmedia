<?php
/*
    This code has been re-written by Isha Dawar and merges the 4 tpls for imagemedia,
    textmedia, audiomedia and videomedia
   */ 
  $country = $user->profile_country;
  $region = $user->profile_region;
  $city = $user->profile_city;
  $homepage = $user->profile_homepage;
  $blog_url = $user->profile_blogurl;
  $blog_title = $user->profile_blogtitle;
  if (!$blog_title) {
	$blog_title = $blog_url;
  }
  if( ($node->ia_identifier)) {
    if ($node->type == 'textmedia'){
      $file = get_file_for_id($node->text->text_fileid);
      $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
      $text_data->texturl = $mediaurl;
    }
    if($node->type == 'imagemedia'){
      $file = get_file_for_id($node->image->image_fileid);
      $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
      $image_data->imageurl = $mediaurl;
    }         
    if($node->type == 'audiomedia'){
      $file = get_file_for_id($node->audio->audio_fileid);
      $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
      $audio_data->audiourl = $mediaurl;
    }
    if($node->type == 'videomedia'){
      $file = get_file_for_id($node->video->video_fileid);
      $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
      $video_data->videourl = $mediaurl;
    }
  }
  else {
    // print the Congratulations message - saying that the media will appear shortly  
    $url = "<a href = 'http://www.ourmedia.org/node/$node->nid'>http://www.ourmedia.org/node/$node->nid</a>";
    $feedback_url ="http://www.ourmedia.org/missingmedia?nid=$node->nid";
    print "<div id=\"metadata-block\">Congratulations! This file has been sent to Ourmedia and the Internet Archive for free hosting and storage. Please be patient -- it should appear soon at this media page: $url <br><br> It can take anywhere from a few minutes to a few hours for your page to go live. (Soon that waiting period should be reduced considerably.) <br><br> If your media does not appear within 24 hours, please fill out our Missing media form:<br> <a href='$feedback_url'>$feedback_url</a> <br><br> When your media page appears, you'll be able to link directly to the
video, audio or photo (say, if you want to include it in your blog). Just visit the media page and copy and paste the link found at \"This media file's URL: Link.\"</div>";
    return;    
  }
?>

<div id = "page-top">
  <?php if($node->type == 'imagemedia') { ?>
  <div id = "imagepage-top">  
  <?php } ?>
  <?php if($node->type != 'imagemedia') { ?>
  <div id = "videopage-top">
    <div id = "video-title">
      <h1><?php print($node->title) ?></h1>
    </div>
  <?php } ?>
    <!-- The display panel for the video -->
    <?php if($node->type == 'imagemedia') { ?>
      <div id="image-panel"><center>
        <?php          
          print show_image($image_data);          
        ?>
        </center><div id="imgtop" style="position: relative; bottom: 0px; left: 10px; width: 300px;"><a href="<?php print($mediaurl); ?>">Link to full size image</a></div>
      </div>    
    <?php } ?>
    <?php if($node->type != 'imagemedia') { ?>
      <div id = "video-panel">
        <?php          
          if ($node->type == 'videomedia'){
            print show_video($video_data);
          }
          if ($node->type == 'audiomedia'){
            print show_audio($audio_data);
          } 
        ?>                
        <div style="position: relative; bottom: 0px; left: 10px; width: 300px;">This media file's URL: <a href="<?php print($mediaurl); ?>">Link</a></div>
      </div>
    <?php } ?>

    <!-- The video author information -->
    <?php if($node->type != 'imagemedia') { ?>
      <div id = "video-author">
        <div class="metadata-contentrow"><span class="fieldname">Author</span>: <?php print $metadata['metadata_author']['value']; ?></div>
        <?php if ($city || $region || $country) { ?>
          <div class="metadata-contentrow"><span class="fieldname">Residence</span>: <?php if ($city) print "$city, "; if ($region) print "$region, "; if ($country) print $country; ?></div>
        <?php } ?>
        <?php if ($homepage || $blog_url) { ?>
          <?php if ($homepage) { ?>
            <div class="metadata-contentrow"><a href="<?php print ($homepage) ?>"><span class="fieldname">Home page </span></a></div>
          <?php } ?>
          <?php if ($blog_url) { ?>
            <div class="metadata-contentrow"><a href="<?php print($blog_url) ?>">Blog</a></div>
          <?php } ?>
        <?php } ?>
	    <div class="comingsoon"> Ratings coming soon! </div>
      </div>
    <?php } ?>
  </div>

  <div id="videopage-bottom">  
  <!-- The video details -->
  <div id = "video-details">  
  <!-- Video title/format etc -->
  <div class = "metadata-block">  
  <?php if ($taxonomy['Text type']) {
          $curr_taxonomy = 'Text type';
        }
        if ($taxonomy['Image type']) {
          $curr_taxonomy = 'Image type';
        }
        if ($taxonomy['Audio Type']) {
          $curr_taxonomy = 'Audio Type';
        }
        if ($taxonomy['Video type']) {
          $curr_taxonomy = 'Video type';
        }
        if (($node->type != 'imagemedia') && ($taxonomy["$curr_taxonomy"])){
          //foreach($taxonomy["$curr_taxonomy"] as $term) {
            $term = $taxonomy[$curr_taxonomy][0];
            print "<div class=\"metadata-contentrow\">";
            print "<b>" . strtoupper($term->name) . "</b>";
            print "</div>";
          //}
        }  
  ?>
  <div class="metadata-block">
    <?php if($node->type == 'imagemedia') { ?>
      <div id="imgtitle"><?php print $node->title ?></div>      
    <?php } ?>
    <?php if($node->type != 'imagemedia') { ?>
      <div class="metadata-contentrow"><span class="fieldname">Title</span>: <?php print $node->title ?> </div>
    <?php } ?>
    <?php if ($node->type == 'imagemedia') { ?>
      <div class="metadata-contentrow"><span class="fieldname">Creator</span>: <?php print $metadata['metadata_author']['value']; ?></div>
      <?php if ($city || $region || $country) { ?>
        <div class="metadata-contentrow"><span class="fieldname">Residence</span>: <?php if ($city) print "$city, "; if ($region) print "$region, "; if ($country) print $country; ?></div>
      <?php } ?>
      <?php if ($homepage || $blog_url) { ?>
        <?php if ($homepage) { ?>
          <div class="metadata-contentrow"><a href="<?php print ($homepage) ?>"><span class="fieldname">Home page </span></a></div>
        <?php } ?>
        <?php if ($blog_url) { ?>
          <div class="metadata-contentrow"><a href="<?php print($blog_url) ?>">Blog</a></div>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  </div>
  <?php if ($node->type == 'textmedia') { 
          $nodetype = $node->text;
          $nodeformat = $nodetype->textformat;
        }     
  ?>
  <?php if ($node->type == 'imagemedia') { 
          $nodetype = $node->image;
        }     
  ?>
  <?php if ($node->type == 'audiomedia') { 
          $nodetype = $node->audio;
          $nodeformat = $nodetype->audioformat;  
        }     
  ?>
  <?php if ($node->type == 'videomedia') { 
          $nodetype = $node->video;
          $nodeformat = $nodetype->videoformat;  
        }     
  ?>
  <?php
    $file_size = $nodetype->file_size;
    if($file_size > 1000000) {
      $file_size = round($file_size / 1000000, 1) . " MB";
    }
    else if($file_size > 1000) {
      $file_size = round($file_size / 1000, 2) . " KB";
    }
    else {
      $file_size = $file_size . " bytes";
    }
  ?>
  
  <?php if (($node->type != 'imagemedia') && ($nodetype->file_size)) {?>
          <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">File size</span>: ".  $file_size ?></div>
  <?php }?>
  <?php if (($node->type == 'imagemedia'|| $node->type == 'videomedia' || $node->type == 'audiomedia' ) && ($nodetype->length)) {?>
           <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Length</span>: ". $nodetype->length ?></div>
  <?php }?>       
  <?php if ($node->type == 'textmedia' || $node->type == 'audiomedia' || $node->type == 'videomedia') { ?>
          <?php if ($nodeformat) {?>
                  <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Format</span>: ". $nodeformat ?></div>
          <?php }?>
  <?php } ?>
  </div>
      
    <div class = "metadata-block">
    <?php
      if (($node->type == 'imagemedia') && ($taxonomy["$curr_taxonomy"])){
        //foreach($taxonomy["$curr_taxonomy"] as $term) {
            $term = $taxonomy[$curr_taxonomy][0];
            print "<div id=\"imgtitle\">";
            print strtoupper($term->name);
            print "</div>";
        //}
      }
    ?>
    <div class="metadata-contentrow"><span class="fieldname">DESCRIPTION</span></div>
    <div class="metadata-contentrow"><?php print $metadata['metadata_description']['value'] ?></div>
    </div>
    <?php if (($metadata['metadata_mature_content']['value'] == t('Yes')) ||
              ($taxonomy['Age Groups']) ||
              ($metadata['metadata_date_created']['value']) ||
              ($metadata['metadata_location']['value']) ||
              ($metadata['metadata_people_depicted']['value']) ||
              ($metadata['metadata_posted_by']['value']) ||
              ($metadata['metadata_first_appeared']['value'])
              ) { ?>
    
    <div class = "metadata-block">
    <?php if ($metadata['metadata_mature_content']['value'] == t('Yes')) {?>
            <div class="metadata-contentrow"><span class=\"fieldname\"><b>Mature content</b></span></div>
    <?php }?>
    <?php if ($taxonomy['Age Groups']) {
            print "<div class=\"metadata-contentrow\">";
            print "<span class=\"fieldname\">Suitable for ages</span>";
            print "</div>";
            foreach($taxonomy['Age Groups'] as $term) {
              print "<div class=\"metadata-contentrow\">";
              print ($term->name);
              print "</div>";
            }
          }
      ?>
      <?php if ($metadata['metadata_date_created']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Date created</span>: ". $metadata['metadata_date_created']['value']; ?></div>
      <?php }?>
      <?php if ($metadata['metadata_location']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Created where</span>: ". $metadata['metadata_location']['value']; ?></div>
      <?php }?>
      <?php if ($metadata['metadata_people_depicted']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">People depicted: </span>: ". $metadata['metadata_people_depicted']['value']; ?></div>
      <?php }?>
      <?php if ($metadata['metadata_first_appeared']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">First appeared at</span>: <a href=\"" . $metadata['metadata_first_appeared']['value'] . "\">this site</a>"; ?></div>
      <?php }?>
      <?php if ($metadata['metadata_posted_by']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Posted by</span>: ". $metadata['metadata_posted_by']['value']; ?></div>
      <?php }?>
      <?php if ($taxonomy['Intended Purpose']) {
              print "<div class=\"metadata-contentrow\">";
              print "<span class=\"fieldname\">Purpose</span>";
              print "</div>";
              foreach($taxonomy['Intended Purpose'] as $term) {
                print "<div class=\"metadata-contentrow\">";
                print ($term->name);
                print "</div>";
              }
            }
      ?>
    </div>
   <?php } ?>

    <div class="metadata-block">
    <div class="metadata-block">  
    <?php if (( $node->type == 'videomedia' || $node->type == 'audiomedia' )&& ($metadata['metadata_is_clip']['value'] == t('Yes')) ){?>
            <div class="metadata-contentrow"><span class=\"fieldname\">Media clip/trailer</span></div>
    <?php }?>
    <?php if ($metadata['metadata_credits']['value']) {?>
            <div class = "metadata-block">
            <div class="metadata-contentrow"><span class="fieldname">Credits</span></div>
            <div class="metadata-contentrow"><?php print $metadata['metadata_credits']['value'] ?></div>
            </div>
    <?php } ?>
    <?php if ($metadata['metadata_producer']['value']) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Producer</span>: ". $metadata['metadata_producer']['value']; ?></div>
    <?php }?>
    <?php if ($metadata['metadata_production_company']['value']) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Production company</span>: ". $metadata['metadata_production_company']['value']; ?></div>
    <?php }?>
    <?php if ($metadata['metadata_distributor']['value']) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Distributed by</span>: ". $metadata['metadata_distributor']['value']; ?></div>
    <?php }?>
    <?php if ($metadata['metadata_syndication']['value']) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Syndication</span>: ". $metadata['metadata_syndication']['value']; ?></div>
    <?php }?>
    <?php if ($metadata['metadata_other_versions']['value']) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Other versions</span>: ". $metadata['metadata_other_versions']['value']; ?></div>
    <?php }?>
    <?php if (($node->type == 'audiomedia' || $node->type == 'videomedia' || $node->type == 'imagemedia') && ($metadata['metadata_equipment_used']['value'])) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Equipment used to create work</span>: ". $metadata['metadata_equipment_used']['value']; ?></div>
    <?php }?>
    <?php if (($node->type == 'videomedia' ) && ($metadata['metadata_original_format']['value'])) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Video format</span>: ". $metadata['metadata_original_format']['value']; ?></div>
    <?php }?>
    <?php if ($taxonomy['Text genre']) {
            $current_taxonomy = 'Text genre';
          }
          if ($taxonomy['Cartoon genre']) {
            $current_taxonomy = 'Cartoon genre';
          }
          if ($taxonomy['Audio genre']) {
            $current_taxonomy = 'Audio genre';
          }
          if ($taxonomy['Video genre']) {
            $current_taxonomy = 'Video genre';
          } 
          if ($taxonomy["$current_taxonomy"]) {
            print "<div class=\"metadata-contentrow\">";
            print "<span class=\"fieldname\">$current_taxonomy: </span>";
	    $term_dta = $taxonomy["$current_taxonomy"][0];
            print ($term_dta->name);
            print "</div>";
          }
      ?>     
      <?php if ($taxonomy['Images']) {
              print "<div class=\"metadata-contentrow\">";
              print "<span class=\"fieldname\">Images</span>";
              print "</div>";
              foreach($taxonomy['Images'] as $term) {
                print "<div class=\"metadata-contentrow\">";
                print ($term->name);
                print "</div>";
              }
            }
       ?>
      <?php if (($node->type == 'videomedia' || $node->type == 'audiomedia' ) && ($metadata['metadata_rating']['value'])) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Rating</span>: ". $metadata['metadata_rating']['value']; ?></div>
      <?php }?>
      <?php if (($node->type == 'textmedia' || $node->type == 'audiomedia' || $node->type == 'imagemedia' ) && ($metadata['metadata_unique_identifier']['value'])) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Identifier</span>: ". $metadata['metadata_unique_identifier']['value']; ?></div>
      <?php }?>
      <?php if (($node->type == 'videomedia' ) && ($metadata['metadata_identifier']['value'])) {?>
               <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Identifier</span>: ". $metadata['metadata_identifier']['value']; ?></div>
      <?php }?>    
      <?php if (($node->type == 'videomedia' )&& ($metadata['metadata_monochromatic']['value'] == t('Yes'))) {?>
              <div class="metadata-contentrow"><span class=\"fieldname\">Black and white</span></div>
      <?php }?>
      <?php if (( $node->type == 'audiomedia' ) && ($node->audio->sampling_rate)) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Sampling rate</span>: ". $node->audio->sampling_rate ?></div>
      <?php }?>
      <?php if (( $node->type == 'audiomedia' ) && ($node->audio->recording_mode)) {?>
               <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Recording mode</span>: ". $node->audio->recording_mode ?></div>
      <?php }?>
      <?php if (($node->type == 'videomedia' || $node->type == 'audiomedia' ) && ($metadata['metadata_transcript']['value'])) {?>
               <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Transcript</span>: ". $metadata['metadata_transcript']['value']; ?></div>
      <?php }?> 
      <?php if (($node->type == 'videomedia' ) && ($node->video->framerate)) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Frame rate</span>: ". $node->video->framerate ?></div>
      <?php }?>
      <?php if ($metadata['metadata_reviews']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Reviews, commentaries, awards</span>: ". $metadata['metadata_reviews']['value']; ?></div>
      <?php }?>
      <?php if ($metadata['metadata_purchase_info']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Purchase information</span>: ". $metadata['metadata_purchase_info']['value']; ?></div>
      <?php }?>      
      <?php if ($metadata['metadata_sponsor']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Sponsor, client or underwriter</span>: ". $metadata['metadata_sponsor']['value']; ?></div>
      <?php }?>
      <?php if ($metadata['metadata_more_info']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">More information</span>: <a href=". $metadata['metadata_more_info']['value']. ">Link</a>"; ?></div>
      <?php }?>
      <?php if ($taxonomy['Main Language']) {
              print "<div class=\"metadata-contentrow\">";
              print "<span class=\"fieldname\">Language</span>";
              print "</div>";
              foreach($taxonomy['Main Language'] as $term) {
                print "<div class=\"metadata-contentrow\">";
                print ($term->name);
                print "</div>";
              }
            }
      ?>
      <?php if ($metadata['metadata_notes']['value']) {?>
              <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Additional notes</span>: ". $metadata['metadata_notes']['value']; ?></div>
      <?php }?>
    </div>
    
    <?php if (($node->type == 'videomedia' || $node->type == 'audiomedia' )&& ($metadata['metadata_releasedate']['value'])) {?>
            <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">First appeared on</span>: ". $metadata['metadata_releasedate']['value']; ?>
            </div>
    <?php }?>                 
    </div>
    <?php if ($node->type == 'imagemedia'){ ?>
            <div class = "metadata-block">
            <?php if($node->image->file_size) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">File size</span>: ".  $file_size; ?></div>
            <?php }?>
            <?php if ($node->image->image_resolution) {
                    $current_image_resolution = $node->image->image_resolution;
                    if(is_numeric($current_image_resolution)) {
                      $current_image_resolution =  number_format($current_image_resolution);
                    }
            ?>        
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Resolution</span>: ". $current_image_resolution . " pixels" ?></div>
            <?php }?>
            <?php if ($node->image->camera_model) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Camera model</span>: ". $node->image->camera_model ?></div>
            <?php }?>
            <?php if ($node->image->image_film_type) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Film type</span>: ". $node->image->image_film_type ?></div>
            <?php }?>
            <?php if ($node->image->image_is_digital) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Digital image?</span>: Yes"?></div>
            <?php } ?>
            <?php if ($node->image->image_flash_used) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Flash used?</span> Yes"?></div>
            <?php } ?>
            <?php if ($node->image->image_bit_depth) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Bit depth / File format</span>: ". $node->image->image_bit_depth ?></div>
            <?php }?>        
            <?php if ($node->image->camera_aperture) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Aperture</span>: ". $node->image->camera_aperture ?></div>
            <?php }?>
            <?php if ($node->image->camera_shutter_speed) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Shutter speed</span>: ". $node->image->camera_shutter_speed ?></div>
            <?php }?>
            <?php if ($node->image->camera_iso_equivalent) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">ISO equivalent</span>: ". $node->image->camera_iso_equivalent ?></div>
            <?php }?>
            <?php if ($node->image->camera_focal_length) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Focal length</span>: ". $node->image->camera_focal_length ?></div>
            <?php }?>
            <?php if ($node->image->image_gps_coordinates) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">GPS Coordinates</span>: ". $node->image->image_gps_coordinates ?></div>
            <?php }?>        
            <?php if ($node->image->image_school) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">School</span>: ". $node->image->image_school ?></div>
            <?php }?>
            <?php if ($node->image->image_medium) {?>
                    <div class="metadata-contentrow"><?php print "<span class=\"fieldname\">Medium</span>: ". $node->image->image_medium ?></div>
            <?php }?>
      </div>
      <?php } ?>
      
    <div class = "metadata-block"> 
    <div class = "metadata-contentrow"><span class="fieldname">My other works</span></div>
    <?php  
      $other_work = get_other_media_for_user($node);
      if (count($other_work) > 0) {
        foreach ($other_work as $work_data) {
          $type_link = l($work_data->title, "node/".$work_data->nid);
          print "<div class=\"metadata-contentrow\">$type_link</div>";
        }
      }
      else {
        print "<div class=\"metadata-contentrow\">No other media published.</div>";
      }
      ?>
    </div>
 </div>
 
   <div id="rightblock">
    <!-- Tags -->
    <div class="greywhiteblock">
    <div class="greywhiteblocktitle">Tags</div>
    <div id="tags">
    <?php
          if ($taxonomy['Keywords']) {
            foreach($taxonomy['Keywords'] as $term) {
              print "<div class=\"metadata-contentrow\">";
              print (l($term->name, "taxonomy/term/".$term->tid));
              print "</div>";
            }
          }
          else {
            print "No tags defined";
          }
        ?>
    	</div>
    </div>
    <!-- Copyright -->
    <div class="greywhiteblock">
    <div class="greywhiteblocktitle">How you may use this work</div>
	<div id = "copyright">
    <?php print get_copyright_text($node, $metadata); ?>
    </div>
    </div>
    <!-- Related Media -->
    <div class="greywhiteblock">
    <div class="greywhiteblocktitle">Related Media</div>
    <div id="relatedmedia">
    Coming soon!
    </div>
    </div>
</div> <!-- end rightblock -->
</div> <!-- end videopage-bottom -->
<div id="end">
</div>
</div>

<?php
  if ($node->type == 'imagemedia') {
    $work_type = 'StillImage';
  }
  else if ($node->type == 'textmedia') {
    $work_type = 'Text';
  }
  else if ($node->type == 'audiomedia') {
    $work_type = 'Sound';
  }
  else if ($node->type == 'videomedia') {
    $work_type = 'MovingImage';
  }
?>

<!--

<rdf:RDF xmlns="http://web.resource.org/cc/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<Work rdf:about="">
    <license rdf:resource="<?php print $node->cc->license_uri?>" />
<dc:type rdf:resource="<?php print ("http://purl.org/dc/dcmitype/$work_type") ?>" />
</Work>
</rdf:RDF>

-->

