<?php
$fid_photo = -1;
$fid_media = -1;

function ourmedia_nodeapi($node, $op, $mtype) {
  switch($op) {
    case 'rss item':
      if($node->type == $mtype) {
        if ($node->type == 'textmedia'){
          $file = get_file_for_id($node->text->text_fileid);
          $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
        }
        if($node->type == 'imagemedia'){
          $file = get_file_for_id($node->image->image_fileid);
          $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
        }         
        if($node->type == 'audiomedia'){
          $file = get_file_for_id($node->audio->audio_fileid);
          $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
        }
        if($node->type == 'videomedia'){
          $file = get_file_for_id($node->video->video_fileid);
          $mediaurl = ia_download_url($node->ia_identifier, $file->filename);
        }
	
        return array(array('key' => 'enclosure',
                         'attributes' => array('url' => $mediaurl, 'length' => $file->filesize, 'type' => $file->filemime)));
      }
  }
}

/**
 * Implementation of validation function
    Validates media (Audio,Video,Image,Text) files     
 */
function ourmedia_validate($node, $mtype) {  
  global $fid_media; 
  global $fid_photo;   

  if ($mtype == 'textmedia') {
    $media_fileid   = 'text_fileid'; 
    $media_hidden_fileid = 'text_hidden_fileid';
    $upload_directory = 'texts';
  }  
  if ($mtype == 'imagemedia') {
    $media_fileid   = 'image_fileid'; 
    $media_hidden_fileid = 'image_hidden_fileid';
    $upload_directory = 'images';
  }   
  if ($mtype == 'audiomedia') {
    $media_fileid   = 'audio_fileid'; 
    $media_hidden_fileid = 'audio_hidden_fileid';
    $upload_directory = 'audios';
  }   
  if ($mtype == 'videomedia') {
    $media_fileid   = 'video_fileid'; 
    $media_hidden_fileid = 'video_hidden_fileid';
    $upload_directory = 'videos';
  }    

/*
  $sid = $_SESSION['sid'];
  if ($_POST['edit']) {
    // Checking to display the file upload form.
    if ((!$_SESSION[$sid]) && (!$node->nid)) {
      if (file_exists("/tmp/{$sid}_qstring")) {
        $qstr = join("",file("/tmp/{$sid}_qstring")); 
        parse_str($qstr);
        $k = count($file['name']);

        $basepath = $file['tmp_name'][0];        
        $fid = db_next_id('{files}_fid');
        $fid_audio = $fid;
        $destination_file = basename($file['name'][0]);
        $dest_array = explode('/', $destination_file);    
        $destination_file = $dest_array[count($dest_array) - 1];
        $dest_array = explode('\\', $destination_file);    
        $destination_file = $dest_array[count($dest_array) - 1];
        $dest = variable_get('upload_path', $upload_directory).'/'. $destination_file;        
        file_copy($basepath, $dest);
        watchdog('bug', "the basepath is $basepath");
        $filename = basename($basepath);
        $filepath = $basepath; // $upload_directory."/".$filename;        
        db_query("INSERT INTO {files} (fid, nid, filename, filepath, filemime, filesize, list) VALUES (%d, %d, '%s', '%s', '%s', %d, %d)",
            $fid, $node->nid, $filename, $filepath, $file['filemime'], $file['size'][0], $node->list[$key]);

        // Entering the $fid in the $_SESSION[$sid] to make a check on $fid.   
        $_SESSION[$sid] = $fid;
        
      }
      else {
        form_set_error($media_fileid, "Please upload a file ");
        return;
      }
    }
  }
  if($_SESSION[$sid]) {
    $fid_media = $_SESSION[$sid];
  }
  */
  if($_FILES['edit']['name'][$media_fileid]) {
    $fid = validate_url($node, $media_fileid, $mtype);
    if ($fid) {
      $fid_media = $fid;
    }
  }
  else if(! $fid_media) {
    $fid_media = $_POST['edit'][$media_hidden_fileid];
  }
  
  

  // File is a mandatory field. If the file has never been uploaded ($fid_audio will be null in this case), and the node has not been created ($node->nid is not set), it's a POST data ($_FILES['edit'] is set) and the video file is not present in the $_FILES array, it is an error case  
  if (isset($_FILES['edit']) && ! $fid_media && ! $node->nid && ! $_FILES['edit']['name'][$media_fileid]) {
    form_set_error($media_fileid, t("Specify a valid media file"));
    return;
  }

  if ($mtype == 'audio' || $mtype == 'text' || $mtype == 'video') {
    if ($_FILES['edit']['name']['photo_fileid']) {
      $fid = validate_url($node, 'photo_fileid',$mtype);
      if ($fid) {
        $fid_photo = $fid;
      }
    }
    else if (! $fid_photo) {
      $fid_photo = $_POST['edit']['photo_hidden_fileid'];
    }
  }
}

/**
 * Validates url of the selected media file
 */
function validate_url($node, $fileid_type,$mtype) {
  global $fid_photo;
  global $fid_media; 
  
  if ($mtype == 'textmedia') {   
    $mediatype = 'text';
    $type = 'texts';
  }
  if ($mtype == 'imagemedia') {  
    $mediatype = 'imagemedia';
    $type = 'images';
  }
  if ($mtype == 'videomedia') {   
    $mediatype = 'video';
    $type = 'videos';
  }  
  if ($mtype == 'audiomedia') {  
    $mediatype = 'audio';
    $type = 'audios';
  }  
  $file_path = $_FILES['edit']['name'][$fileid_type];
  if ($file_path) {
    $upload_file = file_check_upload ($fileid_type);
    if (! $upload_file) {
      form_set_error($fileid_type, t("Specify a valid file"));
      return;
    }
    else {
      $filename = preg_replace('/[^A-Za-z0-9._]/','', $upload_file->filename); 
      $dest = variable_get('upload_path', $type).'/'.$filename;
      
      if ($file = file_save_upload ($fileid_type, $dest, FALSE)) {
        $supported_file_mimes = get_supported_filemime();
        $url = file_create_url($dest);
      }
      else {
        form_set_error($fileid_type, t("The file could not be saved"));
        return;
      }
    }
    switch ($fileid_type) {
      case $media_fileid:
        $fid = $fid_media;
        break;
      case 'photo_fileid':
        $fid = $fid_photo;
        break;
    }  

    if ($fid) {
      db_query("DELETE FROM {files} WHERE fid = %d", $fid);
    }
    $fid = db_next_id('{files}_fid');
    db_query("INSERT INTO {files} (fid, nid, filename, filepath, filemime, filesize, list) 
              VALUES (%d, %d, '%s', '%s', '%s', %d, %d)", 
              $fid, $node->nid, $file->filename, $file->filepath, $file->filemime, 
              $file->filesize, TRUE);
  }
  else {
    if ($fid_media && $fileid_type == $media_fileid) {
      $fid = $fid_media;
    }
    if ($fid_photo && $fileid_type == 'photo_fileid') {
      $fid = $fid_photo;
    }
    else {
      $result = db_query("SELECT %s FROM {$mediatype} WHERE nid = %d", $fileid_type, $node->nid);
      $fid = db_result($result);
    }
  }
  return $fid;
}


/**
 * Implementation of media form
   Generates form for the selected media file 
 */
function ourmedia_mediaform(&$node, &$param , $mtype) {
  global $fid_photo;
  global $fid_media;
  
  $param['options'] = array('enctype' => 'multipart/form-data');
  $output .= "<script>
  function showdiv(div_id) {
    document.getElementById('basic').style.display = 'none';
    document.getElementById('advanced').style.display = 'none';
    document.getElementById('movies').style.display = 'none';
    document.getElementById('basicTab').className = '';
    document.getElementById('advancedTab').className = '';
    document.getElementById('moviesTab').className = '';
    switch (div_id) {
      case 'basic':
        document.getElementById('basic').style.display = 'block';
        document.getElementById('basicTab').className = 'active';
        break;
      case 'advanced':
        document.getElementById('advanced').style.display = 'block';
        document.getElementById('advancedTab').className = 'active';
        break;
      case 'movies':
        document.getElementById('movies').style.display = 'block';
        document.getElementById('moviesTab').className = 'active';
        break;
      }
    }
  </script>";

  $taxonomy_form = get_ourmedia_taxonomy_node_form($node, $mtype);

  if (function_exists('metadata_form')) {
    $metadata_form = metadata_form($node);
  }
  $media_form = get_ourmedia_specific_fields($node,$mtype);
  
  //do formatting here
  $output .= "<div>To publish, you must be registered at both the Internet Archive and Ourmedia.
              <a href=\"help/register\">Details</a>.</div>";
  
  if ($mtype == 'videomedia') {
    $output .= "<div>If your video is larger than 10MB, please use the <a href=\"tools\">
                Ourmedia publisher tool</a>.</div>";    
    
    $thirdtab = 'Movies, TV shows, etc.';
    $mediatype = 'Video type';
    $genre = 'Video genre';
    $hidden_fileid = 'video_hidden_fileid';
    $mediagroup_name = 'videogroup';
  }
  if ($mtype == 'textmedia') {
      
    $thirdtab = 'Books, screenplays, articles, etc.'; 
    $mediatype = 'Text type';
    $genre = 'Text genre';
    $hidden_fileid ='text_hidden_fileid';
    $mediagroup_name = 'textgroup';
  }
  if ($mtype == 'imagemedia') {  
    $output .= "<div>Images wider than 320 pixels will be scaled down; a link to the full-size
                image will be added to the page.</div>";  
            
    $mediatype = 'Image type';
    $thirdtab = 'Artwork, cartoons';
    $genre = 'Cartoon genre'; 
    $hidden_fileid ='image_hidden_fileid';
    $mediagroup_name = 'imagegroup';
  } 
  if ($mtype == 'audiomedia') {
    $output .= "<div>If your audio file is larger than 10MB, please use the <a href=\"tools\">
                Ourmedia publisher tool</a>.</div><div>To publish, you must be registered at
                both the Internet Archive and Ourmedia. <a href=\"help/register\">Details</a>.
                </div>";  
      
    $thirdtab = 'Albums, radio shows, etc.';
    $mediatype = 'Audio Type'; 
    $genre = 'Music genre'; 
    $hidden_fileid ='audio_hidden_fileid';
    $mediagroup_name = 'audiogroup';
  }
  
  $media_upload_box = $media_form[$mediagroup_name];

  if($mtype == 'videomedia') {  
    $output .= "<div style=\"min-height: 150px; width: 540px;\"><div style=\"float:left; width: 65%;\">" . $media_upload_box . "</div>";
    $output .= "<div style=\"float:right; width: 33%;\">" . $taxonomy_form[$mediatype] . $metadata_form['metadata_other_type'] ."</div></div>";
  }
  else if($mtype == 'audiomedia') {
    $output .= "<div style=\"min-height: 110px; width: 540px;\"><div style=\"float:left; width: 55%;\">" . $media_upload_box . "</div>";
    $output .= "<div style=\"float:right; width: 40%;\">" . $taxonomy_form[$mediatype] . $metadata_form['metadata_other_type'] ."</div></div>";
  }
  else if($mtype == 'textmedia') {
    $output .= "<div style=\"min-height: 120px; width: 540px;\"><div style=\"float:left; width: 65%;\">" . $media_upload_box . "</div>";
    $output .= "<div style=\"float:right; width: 30%;\">" . $taxonomy_form[$mediatype] . $metadata_form['metadata_other_type'] ."</div></div>";
  }
  else if($mtype == 'imagemedia') {
    $output .= "<div style=\"min-height: 110px; width: 540px;\"><div style=\"float:left; width: 65%;\">" . $media_upload_box . "</div>";
    $output .= "<div style=\"float:right; width: 30%;\">" . $taxonomy_form[$mediatype] . $metadata_form['metadata_other_type'] ."</div></div>";
  }
  
  $output .= $metadata_form['metadata_author'];
  $output .= $metadata_form['metadata_description'];
  if (function_exists('get_taxonomy_otf_fields')) {
    $output_temp = "<div style=\"width: 520px; min-height: 240px;\"><div style=\"float:left;
                    width: 50%;\">" . $taxonomy_form['Keywords'] . "</div>";
    $output_temp .= "<div style=\"float:right; width: 50%;\">" .
                     get_taxonomy_otf_fields($node, 'Keywords') . "</div></div>";
  }
  else {
    $output_temp .= $taxonomy_form['Keywords'];
  }
  $output .= $output_temp;
  $output .= "<ul class=\"tabs primary\"><li id='basicTab' class=\"active\"><a href='' 
              class=\"active\" onclick=\"showdiv('basic'); return false;\">Basic Details
              </a></li><li id='advancedTab' ><a href='' onclick=\"showdiv('advanced'); 
              return false;\">Advanced Details</a></li><li id='moviesTab'><a href='' 
              onclick=\"showdiv('movies'); return false;\">".$thirdtab."</a></li></ul>";
  
  if (function_exists('creativecommons_form')) {
    $basic .= creativecommons_form($node);
  }
  $basic .= $metadata_form['metadata_copyright_holder'];
  $basic .= $metadata_form['metadata_other_copyright_holders'];
  $basic .= $metadata_form['metadata_copyright_statement'];
  if ($mtype == 'textmedia' || $mtype == 'videomedia' || $mtype == 'audiomedia') {  
    $basic .= $media_form['photo'];
    $basic .= $media_form['photo_credit'];
  }  
  $basic .= $metadata_form['metadata_posted_by'];
  $basic .= $metadata_form['metadata_first_appeared'];
  $basic .= $metadata_form['metadata_date_created'];
  if ($mtype == 'imagemedia') {  
    $basic .= $taxonomy_form['Images'];
  }    
  if ($node->nid && $mtype == 'videomedia') {
    $basic_temp = "<div style=\"width: 520px; height: 40px;\"><div style=\"float:left;
                   width: 50%;\">" . $media_form['file_size'] . "</div>";
    $basic_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['length'] .
                   "</div></div>";
    $basic .= $basic_temp;

    $basic_temp = "<div style=\"width: 520px; height: 130px;\"><div style=\"float:left; 
                   width: 50%;\">" . $media_form['videoformat'] . "</div>";
    $basic_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['framerate'] . 
                   "</div></div>";
    $basic .= $basic_temp;
  }
  if ($mtype == 'textmedia') {  
    $basic_temp = "<div style=\"width: 520px; height: 80px;\"><div style=\"float:left; width:50%;\">" . $media_form['file_size'] . "</div>";
    $basic_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['textformat'] . "</div></div>";
    $basic .= $basic_temp;
  }
  if ($node->nid && $mtype == 'imagemedia') {
    $basic_temp = "<div style=\"width: 520px; height: 76px;\"><div style=\"float:left; width: 50%;\">" . $media_form['file_size'] . "</div>";
    $basic_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['image_resolution'] . "</div></div>";
    $basic .= $basic_temp;
  } 
  if ($node->nid && $mtype == 'audiomedia') {  
    $basic_temp = "<div style=\"width: 520px; height: 40px;\"><div style=\"float:left; width: 50%;\">" . $media_form['file_size'] . "</div>";
    $basic_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['length'] . 
                   "</div></div>";
    $basic .= $basic_temp;

    $basic_temp = "<div style=\"width: 520px; height: 130px;\"><div style=\"float:left; width: 35%;\">" . $media_form['audioformat'] . "</div>";
    $basic_temp .= "<div style=\"float:left; width: 30%;\">" . $media_form['sampling_rate'] .
                   "</div>";
    $basic_temp .= "<div style=\"float:right; width: 30%;\">" . $media_form['recording_mode'] .
                   "</div></div>";
    $basic .= $basic_temp; 
  }
  
  $basic = form_group("<font size=+1>" . t('Basic Details') . "</font>", $basic);
  $output .= "<div id=\"basic\" style=\"display:block\"> $basic </div><br/>";

  $advanced = $metadata_form['metadata_credits'];
  $advanced .= $metadata_form['metadata_location'];
  $advanced .= $metadata_form['metadata_people_depicted'];
  $advanced_temp = "<div style=\"width: 500px; height: 240px;\"><div style=\"float:left; width: 35%;\">" . $taxonomy_form['Intended Purpose'] . "</div>";
  $advanced_temp .= "<div style=\"float:left; width: 30%;\">" . $taxonomy_form['Main Language'] . "</div>";
  $advanced_temp .= "<div style=\"float:right; width: 30%;\">" . $taxonomy_form['Age Groups'] . "</div></div>";
  $advanced .= $advanced_temp;
  $advanced .= $metadata_form['metadata_other_purpose'];
  $advanced .= $metadata_form['metadata_mature_content'];
  $advanced .= $metadata_form['metadata_reviews'];
  $advanced .= $metadata_form['metadata_equipment_used'];
  if ($mtype == 'videomedia') {  
    $advanced .= $metadata_form['metadata_original_format'];
  }  
  if ($mtype == 'videomedia' || $mtype == 'imagemedia') {    
    $advanced .= $metadata_form['metadata_monochromatic'];
  }
  if ($mtype == 'audiomedia') {  
    $advanced .= $metadata_form['metadata_originalaudioformat'];
  }       
  $advanced .= $metadata_form['metadata_purchase_info'];
  $advanced .= $metadata_form['metadata_more_info'];

  if ($mtype == 'imagemedia') {
    $advanced_temp = "<div style=\"width: 520px; height: 93px;\"><div style=\"float:left; width: 50%;\">" . $media_form['image_gps_coordinates'] . "</div>";
    $advanced_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['image_film_type'] . "</div></div>";
    $advanced .= $advanced_temp;
  
    if($node->nid) {
      $advanced_temp = "<div style=\"width: 520px; height: 50px;\"><div style=\"float:left; width: 50%;\">" . $media_form['camera_model'] . "</div>";
      $advanced_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['image_bit_depth'] . "</div></div>";
      $advanced .= $advanced_temp;

      $advanced_temp = "<div style=\"width: 520px; height: 50px;\"><div style=\"float:left; width: 50%;\">" . $media_form['camera_aperture'] . "</div>";
      $advanced_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['camera_shutter_speed'] . "</div></div>";
      $advanced .= $advanced_temp;
  
      $advanced_temp = "<div style=\"width: 520px; height: 50px;\"><div style=\"float:left; width: 50%;\">" . $media_form['camera_iso_equivalent'] . "</div>";
      $advanced_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['camera_focal_length'] . "</div></div>";
      $advanced .= $advanced_temp;
    }
  
    $advanced_temp = "<div style=\"width: 520px; height: 50px;\"><div style=\"float:left; width: 50%;\">" . $media_form['image_is_digital'] . "</div>";
    if($node->nid) {
      $advanced_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['image_flash_used'] . "</div></div>";
    }
    $advanced .= $advanced_temp;  
  }  
  if ($advanced) {
    $advanced = form_group("<font size=+1>" . t('Advanced Details (optional)') . "</font>", $advanced);
    $output .= "<div id=\"advanced\" style=\"display:none\"> $advanced </div>";
  }
  
  if($mtype == 'audiomedia') {
    $min_height = "260px";
  }
  else if($mtype == 'videomedia') {
    $min_height = "85px";
  }
  else if($mtype == 'textmedia') {
    $min_height = "70px";
  }
  else if($mtype == 'imagemedia') {
    $min_height = "100px";
  }
  
  if ($mtype == 'imagemedia') {
    $movies_temp = "<div style=\"width: 520px; min-height: 95px;\"><div style=\"float:left; width: 50%;\">" . $media_form['image_school'] . "</div>";
    $movies_temp .= "<div style=\"float:right; width: 50%;\">" . $media_form['image_medium'] . "</div></div>";
    $movies .= $movies_temp;    
  }    
  $movies_temp = "<div style=\"width: 550px; min-height: " . $min_height . ";\"><div style=\"float:left; width: 40%;\">" .
			$taxonomy_form[$genre] . "</div>";
  $movies_temp .= "<div style=\"float:right; width: 55%;\">" . 
			 $metadata_form['metadata_othergenre'] . "</div></div>";
  $movies .= $movies_temp;
  
  if ($mtype == 'textmedia') {
    $movies .= $metadata_form['metadata_publisher'];
    $movies .= $metadata_form['metadata_publisher_url'];
    $movies .= $metadata_form['metadata_first_published'];
  }
  if ($mtype == 'videomedia' || $mtype == 'audiomedia') {
    $movies .= $metadata_form['metadata_releasedate'];
    $movies .= $metadata_form['metadata_rating'];
  }
  if ($mtype == 'videomedia' || $mtype == 'audiomedia' || $mtype == 'textmedia') {
    $movies .= $metadata_form['metadata_unique_identifier'];
  }
  if ($mtype == 'audiomedia') {    
    $movies .= $metadata_form['metadata_album'];
  } 
  if ($mtype == 'videomedia') { 
    $movies .= $metadata_form['metadata_producer'];
  }  
  if ($mtype == 'videomedia' || $mtype == 'audiomedia') {
    $movies .= $metadata_form['metadata_production_company'];
  }
  if ($mtype == 'videomedia' || $mtype == 'audiomedia' || $mtype == 'textmedia') {  
    $movies .= $metadata_form['metadata_distributor'];  
  } 
  if ($mtype == 'videomedia' || $mtype == 'audiomedia') {     
    $movies .= $metadata_form['metadata_is_clip'];
    $movies .= $metadata_form['metadata_full_version_url'];
    $movies .= $metadata_form['metadata_other_versions'];
  }  
  if ($mtype == 'videomedia' || $mtype == 'audiomedia' || $mtype == 'textmedia') {
    $movies .= $metadata_form['metadata_syndication'];
  }
  if ($mtype == 'videomedia' || $mtype == 'audiomedia') {
    $movies .= $metadata_form['metadata_transcript'];  
  }
  if ($mtype == 'imagemedia') {  
    $movies .= $metadata_form['metadata_other_versions'];
  }    
  $movies .= $metadata_form['metadata_sponsor'];

  if ($movies) {
    $movies = form_group("<font size=+1>" . t("$thirdtab")."(optional)".  "</font>", $movies);
    $output .= "<div id=\"movies\" style=\"display:none\"> $movies </div>";
  }

  $output .= $metadata_form['metadata_notes'];
  $output .= form_hidden($hidden_fileid, $fid_media); 
    
  if ($mtype == 'videomedia' || $mtype == 'audiomedia' || $mtype == 'textmedia') {  
    $output .= form_hidden('photo_hidden_fileid', $fid_photo);
  }

  $output .= "<b>Ourmedia is about sharing works YOU created. If you did not create the work, have written permission to upload it (including Creative Commons licenses), or have a fair use exemption, DO NOT POST IT HERE.</b><br/><br/>";
      
  $output .= "<b>" . t('I certify that I am the copyright owner or have the legal right to upload this material. I have read the ') . l(t('rules'), 'rules') . ".</b><br/><br/>";
  return $output;
}

/**
 * Generates field values for the selected media file
*/
function get_ourmedia_field_value($node, $field, $mtype) {  
  $value = NULL;
  if(isset($node->$field)) {
    $value = $node->$field;
  }
  else {
    if($mtype == 'textmedia') {
      $text_object = $node->text;
      if (isset($text_object->$field)) {
        $value = $text_object->$field;
      }
    }
    else if($mtype == 'imagemedia') {
      $image_object = $node->image;
      if (isset($image_object->$field)) {
        $value = $image_object->$field;
      }
    }
    else if($mtype == 'audiomedia') {
      $audio_object = $node->audio;
      if (isset($audio_object->$field)) {
        $value = $audio_object->$field;
      }
    }
    else if($mtype == 'videomedia') {
      $video_object = $node->video;      
      if (isset($video_object->$field)) {
        $value = $video_object->$field;
      }
    }        
  }
    
  return $value;
}


/**
 * Generates specfic fields for the selected media file
*/
function get_ourmedia_specific_fields($node, $mtype) {
  global $fid_photo;
  global $fid_media;  
    
  if ($mtype == 'textmedia') {
    $media_fileid = 'text_fileid';
    $media = 'text';
    $mediaurl = $node->text->texturl;
    $photourl = $node->text->photourl;
    $text = t('Enter the book, publication or article that you want to publish');
    $mediagroupname  = 'textgroup';
    $mediaformat = 'textformat';
  }
  if ($mtype == 'imagemedia') {
    $media_fileid = 'image_fileid';
    $media = 'image';
    $mediaurl = $node->image->imageurl;
    $text = t('Enter the photo, painting, portrait you want to publish.');
    $mediagroupname  = 'imagegroup';
    $mediaformat = 'imageformat';
  } 
  if ($mtype == 'videomedia') {
    $media_fileid = 'video_fileid';
    $mediaurl = $node->video->videourl;
    $photourl = $node->video->photourl;
    $media = 'video';
    $text = t('If you have a QuickTime movie, you may activate these controls:');
    $mediagroupname  = 'videogroup';
    $mediaformat = 'videoformat';
  }
  if ($mtype == 'audiomedia') {
    $media_fileid = 'audio_fileid';
    $media = 'audio';
    $mediaurl = $node->audio->audiourl;
    $photourl = $node->audio->photourl;
    $text = t('Select the file you want to upload');
    $mediagroupname  = 'audiogroup';
    $mediaformat = 'audioformat';
  }  
       
  $media_specific_fields = array();
  if(! $node->nid) {
    if($mtype == 'audiomedia') {
      $mediagroup = form_file(t('I want to publish this '). $media, $media_fileid, 30, t($text), TRUE);
    }
    else {
      $mediagroup = form_file(t('I want to publish this '). $media, $media_fileid, 35, t($text), TRUE);
    }
  }
  else {
    $mediagroup .= "<br />";
  }
  if ($fid_media) {
    $url = get_file_url($fid_media);
  }
  else {
    $url = $mediaurl;
  }
  if ($url) {
    $mediagroup .= t("Click <a href=\"$url\">here</a> to see current selection.");
  }
  if ($mtype == 'videomedia') {
    $mediagroup .= "<div style=\"width: 115px; float: left;\">" . form_checkbox(
              t('I want controller to be hidden'), 'quicktime_controller', 1,
			  get_ourmedia_field_value($node, 'quicktime_controller',$mtype) ? TRUE : FALSE) . "</div>";
    $mediagroup .= "<div style=\"width: 115px; float: left;\">" . form_checkbox(
              t('I want my movie to loop'), 'quicktime_loop', 1, get_ourmedia_field_value($node,
			  'quicktime_loop', $mtype) ? TRUE : FALSE) . "</div>";
    $mediagroup .= "<div style=\"width: 115px; float: left;\">" . form_checkbox(
              t('I want my movie to Autoplay'), 'quicktime_autoplay', 1, 
			  get_ourmedia_field_value($node, 'quicktime_autoplay', $mtype) ? TRUE : FALSE) . "</div>";
    $mediagroup .= "<div>" . l('How this works', 'help/quicktime') ."</div>";
  }
  $media_specific_fields[$mediagroupname] = $mediagroup;
  $photo = form_file(t('Accompanying photo'), 'photo_fileid', 60, 
         t('Submit an optional photo to appear in the main media window of your video page.'));

  if ($fid_photo) {
    $url = get_file_url($fid_photo);
  }
  else {
    $url = $photourl;
  }
  if ($url) {
    $photo .= t("Current selection is <a href=\"$url\">$url</a>");
  }
  $photo .= form_textfield(t('Photo credit'), 'photo_credit', get_ourmedia_field_value($node, 
		'photo_credit',$mtype), 60, 255, t('Who took this photo?'));
  $media_specific_fields['photo'] = $photo;
  
  $media_specific_fields['file_size'] = form_textfield(t('File size'), 'file_size', 
		                            get_ourmedia_field_value($node, 'file_size',$mtype),
						            60, 255, t('Example: 2.8 MB, 680 KB, etc.'));
  
  if ($mtype == 'videomedia' || $mtype == 'audiomedia') {
    $media_specific_fields['length'] = form_textfield(t('Length'), 'length', 
    get_ourmedia_field_value($node, 'length',$mtype), 60, 255, t('Hours:Minutes:Seconds'));
  }
  
  $media_specific_fields[$mediaformat] = form_textfield(t('Format'), $mediaformat,
     get_ourmedia_field_value($node, $mediaformat,$mtype), 60, 255,
     t('What application/codec is used to play the file? Example: Quicktime, Real'));

  if ($mtype == 'videomedia') {  
    $media_specific_fields['framerate'] = form_textfield(t('Frame rate'), 'framerate',
    get_ourmedia_field_value($node, 'framerate', $mtype), 60, 255, 
    t('How many frames per second (example: 15, 30)'));
  } 

  if ($mtype == 'audiomedia') {  
    $media_specific_fields['sampling_rate'] = form_textfield(t('Sampling rate'), 
    'sampling_rate', get_ourmedia_field_value($node, 'sampling_rate',$mtype), 60, 255,
    t('Specify sampling rate if known (eg, 22,050Hz)'));

    $media_specific_fields['recording_mode'] = form_select(t('Recording mode'),
    'recording_mode', get_ourmedia_field_value($node, 'recording_mode',$mtype), array('Mono' => 'Mono',
     'Stereo' => 'Stereo', 'Multitrack' => 'Multitrack'), 'Specify recording mode if known');
  }  

  if ($mtype == 'imagemedia') {      
    $media_specific_fields['image_resolution'] = form_textfield(t('Image resolution'),
    'image_resolution', get_ourmedia_field_value($node, 'image_resolution',$mtype), 60, 255,
    t('The total number of pixels in final image<br/> (eg, an image 1600 x 1200 pixels contains 1.92 million pixels)'));
    
    $media_specific_fields['camera_model'] = form_textfield(t('Camera model'), 'camera_model',
    get_ourmedia_field_value($node, 'camera_model',$mtype), 60, 255, 
    t('What is the model of the camera used to take this image?'));
    
    $media_specific_fields['image_bit_depth'] = form_textfield(t('Bit depth / File format'),
    'image_bit_depth', get_ourmedia_field_value($node, 'image_bit_depth',$mtype), 60, 255, 
    t('eg: 24 bit color bit map'));
    
    $media_specific_fields['camera_aperture'] = form_textfield(t('Aperture'), 'camera_aperture',
    get_ourmedia_field_value($node, 'camera_aperture',$mtype), 60, 255, t('Camera aperture'));
    
    $media_specific_fields['camera_shutter_speed'] = form_textfield(t('Shutter speed'), 
    'camera_shutter_speed', get_ourmedia_field_value($node, 'camera_shutter_speed',$mtype),
    60, 255, t('Shutter speed of the camera'));
    
    $media_specific_fields['camera_iso_equivalent'] = form_textfield(t('ISO equivalent'),
    'camera_iso_equivalent', get_ourmedia_field_value($node, 'camera_iso_equivalent', $mtype),
    60, 255, t('eg: 1600'));
    
    $media_specific_fields['camera_focal_length'] = form_textfield(t('Focal length'), 
    'camera_focal_length', get_ourmedia_field_value($node, 'camera_focal_length', $mtype), 60,
    255, t('What is the focal length of the camera?'));
    
    $media_specific_fields['image_flash_used'] = form_checkbox(t('Flash used?'), 
    image_flash_used, 1, (get_ourmedia_field_value($node, 'image_flash_used',$mtype) == 1) ? TRUE : FALSE, t(''));
         
    $media_specific_fields['image_gps_coordinates'] = form_textfield(t('Image GPS Coordinates'),
    'image_gps_coordinates', get_ourmedia_field_value($node, 'image_gps_coordinates', $mtype), 
    60, 255, t('What are the GPS coordinates of the image that is being published?'));

    $media_specific_fields['image_film_type'] = form_textfield(t('Film type'), 'image_film_type',
    get_ourmedia_field_value($node, 'image_film_type', $mtype), 60, 255, 
    t('What is the type of the film used to take this image?'));          
  
    $media_specific_fields['image_school'] = form_textfield(t('Art genre'), 'image_school',
    get_ourmedia_field_value($node, 'image_school',$mtype), 60, 255, 
    t('What school or genre best describes this work? (eg, contemporary, Pop art)'));
  
    $media_specific_fields['image_medium'] = form_textfield(t('Medium'), 'image_medium',
    get_ourmedia_field_value($node, 'image_medium',$mtype), 60, 255, t('eg: pen and ink, digital tools'));
             
    $media_specific_fields['image_is_digital'] = form_checkbox(t('Digital image?'), 
    image_is_digital, 1, (get_ourmedia_field_value($node, 'image_is_digital',$mtype) == 1) ? TRUE : FALSE, t(''));      
  }
  return $media_specific_fields;
}


/**
 * Generates taxonomy for the selected media file
*/
function get_ourmedia_taxonomy_node_form($node = '', $mtype = 'videomedia', $help = NULL, $name = 'taxonomy') {
  if (module_exist('taxonomy')) {
    if (!$node->taxonomy) {
      if ($node->nid) {
        $terms = array_keys(taxonomy_node_get_terms($node->nid));
      }
      else {
        $terms = 0;
      }
    }
    else {
      $terms = $node->taxonomy;
    }
   $c = db_query("SELECT v.*, n.type FROM {vocabulary} v INNER JOIN {vocabulary_node_types} n ON v.vid = n.vid WHERE n.type = '%s' ORDER BY v.weight, v.name", $mtype);
    while ($vocabulary = db_fetch_object($c)) {
      $result[$vocabulary->name] = taxonomy_form($vocabulary->vid, $terms, $help, $name);
    }
  }
  return $result ? $result : array();
}

/**
 * Get taxonomy for the selected node   
*/

function get_terms_for_node($nid) {
  static $terms;

  if (module_exist('taxonomy')) {
    if(!isset($terms[$nid])) {
      $result = db_query('SELECT v.name as vocab, t.* FROM {term_data} t, {term_node} r, {vocabulary} v WHERE r.tid = t.tid AND t.vid = v.vid AND r.nid = %d ORDER BY weight, name', $nid);
      $terms[$nid] = array();
      while ($term = db_fetch_object($result)) {
        $terms[$nid][$term->vocab][] = $term;
      }
    }
  }
  return $terms[$nid];
}

/*
function get_terms_for_node($nid) {
  static $terms;

  if (module_exist('taxonomy')) {
    if(!isset($terms[$nid])) {
      $result = db_query('SELECT t.* FROM {term_data} t, {term_node} r WHERE r.tid = t.tid  AND r.nid = %d ORDER BY weight, name', $nid);
      $terms[$nid] = array();
      while ($term = db_fetch_object($result)) {
        $terms[$nid][$term->$key]= $term;
      }
    }
  }
  return $terms[$nid];
}*/

/**
 * Implementation of view function    
*/
function ourmedia_view(&$node, $teaser, $mtype) {
  global $fid_photo;
  global $fid_media;

  if ($mtype == 'textmedia') {  
    $mediaurl = $text_data->texturl; 
    $photourl = $text_data->photourl;
    $media = $node->text;
    $media_field = $text_data->textformat;
    $media_view = 'text_view';		
  }
  if ($mtype == 'imagemedia') {  
    $mediaurl = $text_data->imageurl;
    $media = $node->image; 
    $media_view = 'image_view';
  }
  if ($mtype == 'audiomedia') {  
    $mediaurl = $text_data->audiourl; 
    $photourl = $text_data->photourl;
    $media = $node->audio;
    $media_field = $audio_data->sampling_rate;  
    $media_view = 'audio_view';
  }
  if ($mtype == 'videomedia') {  
    $mediaurl = $text_data->videourl; 
    $photourl = $text_data->photourl;
    $media = $node->video;
    $media_field = $video_data->quicktime_loop;	
    $media_view = 'video_view';
  }         
  if (function_exists('metadata_get_fields')) {
    $media_metadata = metadata_get_fields($node);
  }
  $media_taxonomy = get_terms_for_node($node->nid);

  $media_data = $node;
  if (!isset($media_field)) {
    $media_data = $media;
  }
  else {
    $mediaurl = get_file_url($fid_media);
    if ($mtype == 'textmedia' || $mtype == 'videomedia' || $mtype == 'audiomedia') {
      $photourl = get_file_url($fid_photo);
    }  
  }
  $node->body .= theme($media_view, $node, $media_data, $media_metadata, $media_taxonomy);
}


/**
 * Generates url for the file
*/
function get_file_url($fid) {
  $result = db_query("SELECT filepath from {files} where fid = %d", $fid);
  if ($result && db_num_rows($result) > 0) {
      $output = file_create_url(db_result($result));
  }
  return $output;
}

/**
 * Implementation of theme function
 * Generates theme for the media file
*/
function theme_ourmedia_view($node, $media_data, $media_metadata, $media_taxonomy,$mtype) {
  if ($mtype == 'textmedia') {
    $media_data = '$text_data';
    $mediaurl = $text_data->texturl;
    $mediasize = $text_data->file_size;
    $mediaformat = $video_data->textformat;
    $type = 'text';
  } 
  if ($mtype == 'imagemedia') {  
    $media_data = '$image_data';
    $mediaurl = $image_data->imageurl;
    $mediasize = $image_data->file_size;
    $type = 'image';
  }  
  if ($mtype == 'videomedia') {
    $media_data = '$video_data';
    $mediaurl = $video_data->videourl;
    $mediasize = $video_data->file_size;
    $mediaformat = $video_data->videoformat;
    $type = 'video';
  }  
  if ($mtype == 'audiomedia') {  
    $media_data = '$audio_data';
    $mediaurl = $audio_data->audiourl;
    $mediasize = $audio_data->file_size;
    $type = 'audio';
    $mediaformat = $audio_data->audioformat;
  }     

  if (isset($media_data)) {
    if ($mtype == 'imagemedia') {
      $output .= show_image($image_data);
    }
    if ($mtype == 'videomedia') {
      $output .= show_video($video_data);
    }
    if ($mediaurl) {
      $output .= "<div><a href=\"$mediaurl\">Media Download</a></div>";
    }
    if (($media_data->photourl) && ($mtype == 'textmedia' || $mtype == 'audiomedia' ||
	 $mtype == 'videomedia' )) {
      $output .= "<div><img src=\"$media_data->photourl\" width=\"320\" height=\"260\"/>
                  </div>";
    }  
    if (($media_data->photo_credit) && ($mtype == 'textmedia' || $mtype == 'audiomedia' ||
         $mtype == 'videomedia')) {
      $output .= "<div><b>Photo Credit</b>: $media_data->photo_credit </div>";
    }
    if ($mediasize) {
      $output .= "<div><b>File Size</b>: $mediasize </div>";
    }
    if ($mtype == 'audiomedia') {
      if ($media_data->length) {
        $output .= "<div><b>Length</b>: $media_data->length</div>";
      }
      if ($media_data->audioformat) {
        $output .= "<div><b>audio Format</b>: $media_data->audioformat</div>";
      }
      if ($media_data->sampling_rate) {
        $output .= "<div><b>Sampling rate</b>: $media_data->sampling_rate</div>";
      }
      if ($media_data->recording_mode) {
        $output .= "<div><b>Recording Mode</b>: $media_data->recording_mode</div>";
      }
    } 
    if (($mediaformat) && ($mtype == 'textmedia' || $mtype == 'audiomedia' ||
         $mtype == 'videomedia' )) { 
      $output .= "<div><b>Media Format</b>: $mediaformat</div>";
    }
    if (($media_data->framerate) && ($mtype == 'videomedia')){
      $output .= "<div><b>Frame rate</b>: $media_data->framerate</div>";
    }
    if (($media_data->length) && ($mtype == 'videomedia')) {
      $output .= "<div><b>Length</b>: $media_data->length</div>";
    }
  }
  return $output;
}


/**
 * Implementation of insert function 
   Insert values into the media files 
*/
function ourmedia_insert($node,$mtype) {
  global $fid_media;
  global $fid_photo;

/*
  if (!$fid_media) {
    $sid = $_SESSION['sid'];
    $fid_media = $_SESSION[$sid];
  }
  */
  db_query("UPDATE {files} SET nid = %d WHERE fid = %d", $node->nid, $fid_media);  
  if ($mtype == 'textmedia' || $mtype == 'videomedia' || $mtype == 'audiomedia') {  
    db_query("UPDATE {files} SET nid = %d WHERE fid = %d", $node->nid, $fid_photo);
  }
  
  require_once('getid3/getid3.php');

  $result = db_query("SELECT filepath, filesize FROM {files} WHERE nid = %d", $node->nid);
  $remotefile = db_fetch_object($result);
  $remotefilename = $remotefile->filepath;
  $remotefilesize = $remotefile->filesize;
         
  // Initialize getID3 engine
  $getID3 = new getID3;
  $ThisFileInfo = $getID3->analyze($remotefilename);  

  if($mtype == 'audiomedia') {   
    db_query("INSERT INTO {audio} (nid, audio_fileid, photo_fileid, photo_credit, file_size, length, audioformat, sampling_rate, recording_mode) VALUES (%d, %d, %d, '%s', '%s', '%s', '%s', '%s', '%s')", 
    $node->nid, $fid_media, $fid_photo, $node->photo_credit, $remotefilesize, $ThisFileInfo['playtime_string'], $ThisFileInfo['fileformat'], $ThisFileInfo['audio']['sample_rate'], $ThisFileInfo['audio']['channelmode']);
  }
  else if($mtype == 'videomedia') {
    db_query("INSERT INTO {video} (nid, video_fileid, quicktime_controller, quicktime_loop, quicktime_autoplay, photo_fileid, photo_credit, file_size, length, videoformat, framerate) VALUES (%d, %d, %d, %d, %d, %d, '%s', '%s', '%s', '%s', '%s')", 
    $node->nid, $fid_media, $node->quicktime_controller, $node->quicktime_loop, $node->quicktime_autoplay, $fid_photo, $node->photo_credit, $remotefilesize, $ThisFileInfo['playtime_string'], $ThisFileInfo['fileformat'], $ThisFileInfo['video']['frame_rate']);
  }
  else if($mtype == 'imagemedia') {
    db_query("INSERT INTO {imagemedia} (nid, image_fileid, file_size, image_resolution, image_gps_coordinates, image_film_type, camera_model, image_bit_depth, image_school, image_medium, camera_aperture, camera_shutter_speed, camera_iso_equivalent, camera_focal_length, image_is_digital, image_flash_used) VALUES (%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, %d)", 
    $node->nid, $fid_media, $remotefilesize, $ThisFileInfo['video']['resolution_x']*$ThisFileInfo['video']['resolution_y'], $node->image_gps_coordinates, $node->image_film_type, $ThisFileInfo['jpg']['exif']['IFD0']['Model'], $ThisFileInfo['fileformat'], $node->image_school, $node->image_medium, $ThisFileInfo['jpg']['exif']['EXIF']['ApertureValue'],$ThisFileInfo['jpg']['exif']['EXIF']['ShutterSpeedValue'], $ThisFileInfo['encoding'], $ThisFileInfo['jpg']['exif']['EXIF']['FocalLength'], $node->image_is_digital, $ThisFileInfo['jpg']['exif']['EXIF']['FlashPixVersion']);
  }
  else if($mtype == 'textmedia') {
    db_query("INSERT INTO {text} (nid, text_fileid, photo_fileid, photo_credit, file_size, 
    textformat) VALUES (%d, %d, %d, '%s', '%s', '%s')", $node->nid, $fid_media, $fid_photo,
    $node->photo_credit, $remotefilesize, $node->textformat);
  }
  
  /*
  // Code to delete the tmp folder files
  $sid = $_SESSION['sid'];
  if (file_exists("/tmp/{$sid}_qstring")) {
    $qstr = join("",file("/tmp/{$sid}_qstring"));
    unlink("/tmp/{$sid}_qstring");
    parse_str($qstr);
    $k = count($file['name']);
    $basepath = $file['tmp_name'][0];
    unlink("$basepath");
  }*/
}

/**
 * Implementation of update function 
   Update the fields of media files 
*/
function ourmedia_update($node, $mtype) {
  global $fid_photo;
  global $fid_media;
  
  if ($mtype == 'textmedia') {   
    db_query("UPDATE {text} SET photo_fileid = %d, photo_credit = '%s',
		 file_size = '%s', textformat = '%s' WHERE nid = %d", $fid_photo,
		 $node->photo_credit, $node->file_size, $node->textformat, $node->nid);
  }
  if ($mtype == 'imagemedia') {    
    db_query("UPDATE {imagemedia} SET file_size = '%s',
		 image_resolution = '%s', image_gps_coordinates = '%s', image_film_type = '%s',
		 camera_model = '%s', image_bit_depth = '%s', image_school = '%s', 
		 image_medium = '%s', camera_aperture = '%s', camera_shutter_speed = '%s', 
		 camera_iso_equivalent = '%s', camera_focal_length = '%s', image_is_digital = %d, 
		 image_flash_used = %d WHERE nid = %d", $node->file_size, 
		 $node->image_resolution, $node->image_gps_coordinates, $node->image_film_type,
		 $node->camera_model, $node->image_bit_depth, $node->image_school,
		 $node->image_medium, $node->camera_aperture, $node->camera_shutter_speed, 
		 $node->camera_iso_equivalent, $node->camera_focal_length, $node->image_is_digital,
		 $node->image_flash_used, $node->nid);
  }    
  if ($mtype == 'videomedia') {
    db_query("UPDATE {video} SET quicktime_controller = %d, quicktime_loop
		 = %d, quicktime_autoplay = %d, photo_fileid = %d, photo_credit = '%s', 
		 file_size = '%s', length = '%s', videoformat = '%s', framerate = '%s' WHERE
		 nid = %d", $node->quicktime_controller, $node->quicktime_loop,
		 $node->quicktime_autoplay, $fid_photo, $node->photo_credit, $node->file_size, 
		 $node->length, $node->videoformat, $node->framerate, $node->nid);
  }
  if ($mtype == 'audiomedia') {
    db_query("UPDATE {audio} SET photo_fileid = %d, photo_credit = '%s', 
		 file_size = '%s', length = '%s', audioformat = '%s', sampling_rate = '%s',
		 recording_mode = '%s' WHERE nid = %d", $fid_photo, $node->photo_credit,
		 $node->file_size, $node->length, $node->audioformat, $node->sampling_rate,
		 $node->recording_mode, $node->nid);
  }
}

function ourmedia_upload($node) {
  if ($_POST['sessionid']) {
    $sid = $_POST['sessionid'];
  }
  else {
    $sid = md5(uniqid(rand()));
  }
  $_SESSION['sid'] = $sid;
  $output .= "<input type=\"hidden\" name=\"sessionid\" value=\"$sid\" />";

  // Checking when the form enters into the view and nid is present.
  if ($node->nid) {
    $output .= '<b>Attached file</b>'."<br>";
    $result = db_fetch_object(db_query("SELECT filename,filesize FROM {files} WHERE nid=$node->nid"));
    $output .= "File name :: $result->filename"."<br>";
    $output .= "File size :: $result->filesize";
  }
  // Checking when the form enters into the preview and nid is not generated but the file is uploaded.
  elseif ($_SESSION[$sid] && (!$node->nid)) {
    $file_id = $_SESSION[$sid];
    $output .= '<b>Attached file</b>'."<br>";
    $result = db_fetch_object(db_query("SELECT filename,filesize FROM {files} WHERE (fid=$file_id)"));
    $output .= "File name :: $result->filename"."<br>";
    $output .= "File size :: $result->filesize";
  }
  else {
    $output .= "<iframe name=\"progress\" width =\"350\" height=\"125\" align=\"center\" frameborder=\"0\" src=\"sites/staging.ourmedia.org/modules/media/upload.php?sessionid=".$sid."\">
	   </iframe>";	   
  }
  return $output;
}

?>
