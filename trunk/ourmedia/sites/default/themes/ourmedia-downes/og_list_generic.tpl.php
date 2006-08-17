<div class="content-panel">
  <div class="content-name">
  <?php 
    $type_display = $type;
    if($type_display == 'audiomedia') {
      $type_display = 'Audio';
      $btn_image = drupal_get_path('theme', 'ourmedia-downes') . "/images/post-audio-button.gif";
    }
    if($type_display == 'imagemedia') {
      $type_display = 'Image';
      $btn_image = drupal_get_path('theme', 'ourmedia-downes') . "/images/post-image-button.gif";
    }
    if($type_display == 'textmedia') {
      $type_display = 'Text';
      $btn_image = drupal_get_path('theme', 'ourmedia-downes') . "/images/post-text-button.gif";
    }
    if($type_display == 'videomedia') {
      $type_display = 'Video';
      $btn_image = drupal_get_path('theme', 'ourmedia-downes') . "/images/post-video-button.gif";
    }
    if($type_display == 'forum') {
      $type_display = 'Forum';
      $btn_image = drupal_get_path('theme', 'ourmedia-downes') . "/images/post-forum-button.gif";
    }
    if($type_display == 'blog') {
      $type_display = 'Group Weblog';
      $btn_image = drupal_get_path('theme', 'ourmedia-downes') . "/images/post-blog-button.gif";   
    }
  
    print $type_display;
  ?>
  </div>
  <?php
      if($type == 'blog') {
        $group_blogs = get_og_blog_entries($gid);
        print_r($group_blogs);
      }
  ?>    
  
  <div class="content-header">
    <span class="topic-title">Title</span>
    <span class="topic-author">Author</span>
    <span class="topic-replies">Replies</span>
    <span class="topic-lastpost">Last Post</span>
  </div>

  <?php
    $sql = og_get_home_nodes_sql($type);
    $result = db_query($sql, $gid);
    $count = 0;
    while($row = db_fetch_object($result)) {
      $count++;
      if(($type == 'blog') && ($count < 4)) {
        continue;
      }
      
      print "<div class=\"content-row\">\n";
      print "\t<span class=\"content-title\">" . l($row->title, "node/$row->nid") . "</span>";
      print "\t<span class=\"content-author\">" . format_name($row) . "</span>";
      print "\t<span class=\"content-replies\">" . $row->comment_count . "</span>";
      print "\t<span class=\"content-lastpost\">" . format_date($row->created, 'small') . "</span>";
      print "</div>\n";
    }
    print "<div class=\"content-row\">\n";
    $btn_text = "<a href=\"node/add/$type?edit[og_groups][]=$gid\"><img src=\"$btn_image\" /></a>";
    global $user;
    if($user->uid) {
      $result = db_query("SELECT * FROM {node_access} WHERE realm = 'og_uid' AND nid = %d AND gid = %d", $gid, $user->uid);
      if(db_num_rows($result) > 0) {
        print "\t<div class=\"post-button\">$btn_text</div>\n";
      }
    }
    print "</div>\n";
  ?>
</div>  
