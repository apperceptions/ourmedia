<?php

function phptemplate_user_profile($account, $fields) {
  // Pass to phptemplate, including translating the parameters to an associative
  // array. The element names are the names that the variables will be
  // assigned within your template.

  // load user profile
  $account = get_profile ($account);

  // get buddy list
  $buddylist = buddylist_get_buddies($account->uid);
	
  // get buddies of
  if (user_access('view buddy lists'))
  {
    $cnt = variable_get('buddylist_prof_buddies', 5);
    $sql = 'SELECT b.uid, u.name FROM {buddylist} b INNER JOIN {users} u ON b.uid = u.uid WHERE b.buddy = %d ORDER BY u.changed DESC';
    $result = db_query_range($sql, $account->uid, 0, $cnt);
    while ($row = db_fetch_object($result)) {
      $buddiesof[$account->uid][$row->uid] = (object) array('uid' => $row->uid, 'name' => $row->name, 'mail' => $row->mail);
    }
  }
  return _phptemplate_callback('user_profile', array('account' => $account, 'buddylist' => $buddylist, 'buddiesof' => $buddiesof[$account->uid], 'fields' => $fields));
}

function phptemplate_homepage_view($welcome_title, $welcome_text_para1, $welcome_text_para2, $featured_videos, $music, $audio, $text,$blog)
{
	return _phptemplate_callback('homepage_view', array('welcome_title' => $welcome_title, 'welcome_text_para1' => $welcome_text_para1, 'welcome_text_para2' => $welcome_text_para2,
	'featured_videos' => $featured_videos, 'music' => $music, 'audio' => $audio, 'text' => $text, 'blog' => $blog));
}

/**
 * This method is identical to profile_load_profile but since php complains about
 * call by reference, we return a copy of the profile information
 */
function get_profile($user) {
  $result = db_query('SELECT f.name, f.type, v.value FROM {profile_fields} f INNER JOIN {profile_values} v ON f.fid = v.fid WHERE uid = %d', $user->uid);
  while ($field = db_fetch_object($result)) {
    if (empty($user->{$field->name})) {
      $user->{$field->name} = _profile_field_serialize($field->type) ? unserialize($field->value) : $field->value;
    }
  }
  return $user;
}

function get_user_picture($account) {
  if (variable_get('user_pictures', 0)) {
    if ($account->picture && file_exists($account->picture)) {
      $picture = file_create_url($account->picture);
    }
    else if (variable_get('user_picture_default', '')) {
      $picture = variable_get('user_picture_default', '');
    }

    if ($picture) {
      $alt = t('%user\'s picture', array('%user' => $account->name ? $account->name : variable_get('anonymous', 'Anonymous')));
      $output = "<img id=\"mypage-profilepicture\" src='".$picture."' alt='".htmlspecialchars($alt, ENT_QUOTES)."'>";
      return $output;
    }
  }
}

function get_private_msg($thisuser) {
  global $user;
  if ($user->uid == $thisuser->uid) {
    return;
  }
  $name = $thisuser->profile_firstname;
  if (!$name) {
    $name = $thisuser->name;
  }
  if (user_access('access private messages') && (isset($thisuser->privatemsg_allow) ? $thisuser->privatemsg_allow : 1)) {
    return l(t("Send $name a message"), "privatemsg/msgto/$thisuser->uid");
  }
}

function get_add_remove_buddy_link($thisuser) {
  global $user;
  $name = $thisuser->profile_firstname;
  if (!$name) {
    $name = $thisuser->name;
  }
  
  $_SESSION['buddylist_op_destination'] = $_SERVER['HTTP_REFERER'];
  if (@in_array($thisuser->uid, array_keys(buddylist_get_buddies($user->uid))) && user_access('maintain buddy list')) {
    return l(t('Remove %name from my buddy list', array('%name' => $name)), 'buddy/delete/'. $thisuser->uid);
  }
  else {
    if ($user->uid != $thisuser->uid && user_access('maintain buddy list')) {
      return l(t('Add %name to my buddy list', array('%name' => $name)), 'buddy/add/'. $thisuser->uid);
    }
  }
}

function get_og_blog_entries($gid) {
  $output = '';
  if ($gid) {
    $result = db_query("SELECT n.nid, n.sticky, n.created FROM {node} n, {node_access} na WHERE n.nid = na.nid AND n.type = 'blog' AND na.gid = %d AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC LIMIT 3", $gid);
    while ($node = db_fetch_object($result)) {
      $output .= node_view(node_load(array('nid' => $node->nid)), 1);
    }
  }
  return $output;
}

function get_recent_blog_entries($account) {
  $output = '';
  if ($account->uid) {
    $result = pager_query(db_rewrite_sql("SELECT n.nid, n.sticky, n.created FROM {node} n WHERE type = 'blog' AND n.uid = %d AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10), 0, NULL, $account->uid);
    while ($node = db_fetch_object($result)) {
      $output .= node_view(node_load(array('nid' => $node->nid)), 1);
    }
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
    $output .= theme('xml_icon', url("blog/$account->uid/feed"));
  }
  return $output;
}

function get_forum_topics_for_user($account) {
  if (user_access('access content')) {
    $result = db_query_range(db_rewrite_sql("SELECT DISTINCT(n.nid), n.title, n.changed, l.last_comment_timestamp FROM {node} n INNER JOIN {node_comment_statistics} l ON n.nid = l.nid WHERE n.status = 1 AND n.uid = $account->uid AND n.type='forum' ORDER BY l.last_comment_timestamp DESC"), 0, variable_get('forum_block_num', '5'));
    $items = array();
    while ($node = db_fetch_object($result)) {
      $items[] = array('title' => l($node->title, 'node/'. $node->nid), 'timestamp' => date("m.d.Y H:i", $node->changed));
    }
  }
  return $items;
}

function get_group_blog_posts_for_user($account) {
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.sticky, n.changed, n.title FROM {node} n WHERE n.promote = 1 AND n.status = 1 AND n.uid = $account->uid ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $items = array();
  while ($node = db_fetch_object($result)) {
    $items[] = array('title' => l($node->title, 'node/'. $node->nid), 'timestamp' => date("m.d.Y H:i", $node->changed));
  }
  return $items;
}

function get_media_for_user($account, $type) {
  switch ($type) {
  case 'videomedia':
    $table_name = 'video';
    $file_column_name = 'video_fileid';
    break;
  case 'audiomedia':
    $table_name = 'audio';
    $file_column_name = 'audio_fileid';
    break;
  case 'textmedia':
    $table_name = 'text';
    $file_column_name = 'text_fileid';
    break;
  case 'imagemedia':
    $table_name = 'imagemedia';
    $file_column_name = 'image_fileid';
    break;
  }
//  $result = db_query("SELECT n.nid as nid,title,type,created, f.filesize as filesize, f.filename as filename FROM {node} n, {$table_name} t, {files} f WHERE n.status='1' AND n.uid=$account->uid AND n.type='$type' AND n.nid = t.nid AND t.$file_column_name = f.fid  ORDER BY created DESC LIMIT 10");
   $result = db_query("SELECT n.nid as nid,title,type,created, c.totalcount as totalcount, f.filesize as filesize, f.filename as filename FROM {$table_name} t,{node} n LEFT JOIN {node_counter} c using (nid),{files} f WHERE n.status='1' AND n.uid=$account->uid AND n.type='$type' AND n.nid = t.nid AND t.$file_column_name = f.fid  ORDER BY created DESC LIMIT 10");

  $items = array();
  if (db_num_rows($result)) {
	while ($node = db_fetch_object($result)) {
      $explosion = explode(".", $node->filename);
	  $ext = $explosion[count($explosion) - 1];
	  $items[] = array('title' => l($node->title, 'node/'.$node->nid), 'filename' => $ext, 'filesize' => $node->filesize, 'downloads' => $node->totalcount);
	}
  }
  return $items;
}

function phptemplate_user_picture($account) {

  return html_entity_decode(_phptemplate_callback('user_picture', array('account' => $account)));
}
/* Start video page theming */

function phptemplate_video_view($node, $video_data, $video_metadata, $video_taxonomy) {
  $user = user_load(array('uid' => $node->uid));
  return _phptemplate_callback('media_view', array('node' => $node, 'user' => $user, 'video_data' => $video_data, 'metadata' => $video_metadata, 'taxonomy' => $video_taxonomy));
}

function phptemplate_audio_view($node, $audio_data, $audio_metadata, $audio_taxonomy) {
  $user = user_load(array('uid' => $node->uid));
  return _phptemplate_callback('media_view', array('node' => $node, 'user' => $user, 'audio_data' => $audio_data, 'metadata' => $audio_metadata, 'taxonomy' => $audio_taxonomy));
}

function phptemplate_text_view($node, $text_data, $text_metadata, $text_taxonomy) {
  $user = user_load(array('uid' => $node->uid));
  return _phptemplate_callback('media_view', array('node' => $node, 'user' => $user, 'text_data' => $text_data, 'metadata' => $text_metadata, 'taxonomy' => $text_taxonomy));
}

function phptemplate_image_view($node, $image_data, $image_metadata, $image_taxonomy) {
  $user = user_load(array('uid' => $node->uid));
  return _phptemplate_callback('media_view', array('node' => $node, 'user' => $user, 'image_data' => $image_data, 'metadata' => $image_metadata, 'taxonomy' => $image_taxonomy));
}

function get_other_media_for_user($node) {
  $result = db_query("SELECT nid, title FROM node WHERE uid = %d AND type = '%s' AND nid <> %d", $node->uid, $node->type, $node->nid);
  $return = array();
  while ($object = db_fetch_object($result)) {
    $return[] = $object;
  }

  return $return;
}

function get_copyright_text($node, $metadata) {
  if($node->cc) {
    $license_type = $node->cc->license_type;
    $license_name = $node->cc->license_name;
    $license_uri = $node->cc->license_uri;
  }
  
  if($license_type == 'traditionalcopyright') {
    $license_name = 'Traditional Copyright';
    $license_uri = 'http://www.copyright.gov';
  }
  
  if($license_type == 'standard' || $license_type == 'recombo') {
    $is_cc = TRUE;
  }
  else {
    $is_cc = FALSE;
  }

  if($license_type == 'publicdomain') {
    $is_public = TRUE;
  }
  else {
    $is_public = FALSE;
  }
  
  if($license_type == 'traditionalcopyright') {
    $is_traditional = TRUE;
  }
  else {
    $is_traditional = FALSE;
  }

  if ($is_cc) {
    $share_it = "Yes, with attribution";
  }
  else if ($is_public || $license_type == 'gpl') {
    $share_it = "Yes";
  }
  else if($is_traditional){
    $share_it = "No";
  }
  else {
    $other_license = TRUE;
  }

  if (strstr($license_uri, '/by-nd/') || strstr($license_uri, '/by-nc-nd/') || $is_traditional) {
    $remix = "No";
  }
  else if($license_type == 'gpl') {
    $remix = "Yes";
  }
  else if(! $other_license) {
    $remix = "Yes";
  }
  
  if (strstr($license_uri, '/by/') || strstr($license_uri, '/by-nd/') || 
  strstr($license_uri, '/by-sa/') || strstr($license_uri, '/sampling/') || 
  strstr($license_uri, '/sampling+/') || $is_public) {
    $commercial_use = "Yes";
  }
  else if($license_type == 'gpl') {
    $commercial_use = "Yes";
  }
  else if(! $other_license) {
    $commercial_use = "No";
  }

  if($is_public) {
    $logo = path_to_theme() . "/public-domain.gif";
  }
  else if($is_cc) {
    $logo = "http://creativecommons.org/images/public/somerights20.gif";
  }
  else if($is_traditional) {
    $logo = path_to_theme(). "/trad-copy-2.png";
  }
  
  $copyright_holder = $metadata['metadata_copyright_holder']['value'];
  $copyright_statement = $metadata['metadata_copyright_statement']['value'];

  if($share_it) {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">Share it? </span>$share_it</div>";
  }
  if($remix) {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">Remix it? </span>$remix</div>";
  }
  if($commercial_use) {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">Use commercially? </span>$commercial_use</div>";
  }
  if ($copyright_holder) {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">Rights holder: </span>$copyright_holder</div>";
  }
  if($logo) {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">License: </span><br/>" . ($license_uri ? "<a href=\"$license_uri\">" : "") . "<img src=\"$logo\"/> <br/>$license_name" . ($license_uri ? "</a>" : "" ) . "</div>";
  }
  else {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">License: </span><br/>" . ($license_uri ? "<a href=\"$license_uri\">" : "") . $license_name . ($license_uri ? "</a>" : "") . "</div>";
  }
  if ($copyright_statement) {
    $output .= "<div class = \"metadata-contentrow\"><span class=\"fieldname\">Copyright statement </span>$copyright_statement</div>";
  }
  return $output;
}

function get_file_for_id ($fid) {
  $result = db_query("SELECT * FROM {files} WHERE fid = %d", $fid);
  if (db_num_rows($result)) {
    $file = db_fetch_object($result);
  }
  return $file;
}

/* End video page theming */

/* Begin Groups page theming */
function phptemplate_og_list_generic($gid, $type, $mode) {
  return _phptemplate_callback('og_list_generic', array('gid' => $gid, 'type' => $type, 'mode' => $mode));
}

/* End Groups page theming */
?>
