<?php
// $Id: banner_db.php,v 1.7 2004/08/17 00:42:16 jeremy Exp $

  $pos = (int)$_GET["pos"];
  
  include_once "includes/bootstrap.inc";
  include_once "includes/common.inc";
  list($ballot, $banners) = _banner_get_struct();

  $random = mt_rand(0, (count($ballot[$pos]) - 1));
  $id = $ballot[$pos][$random];
  $banner = $banners[$id];

  $banner->views++;
  
  db_query("UPDATE {banner} SET views = views + 1, day_views = day_views + 1, week_views = week_views + 1 WHERE id = %d", $id);

  if ($banner->max_views > 0 && $banner->views >= $banner->max_views) {
    // reached maximum views, set status (5) "blocked"
    db_query("UPDATE {banner} SET status = 5 WHERE id = %d", $id);
  }
  else if ($banner->day_max_views > 0 && $banner->day_views >= $banner->day_max_views) {
    // reached day's maximum views, set status (2) "day's limit reached"
    db_query("UPDATE {banner} SET status = 2 WHERE id = %d", $id);
  }
  else if ($banner->week_max_views > 0 && $banner->week_views >= $banner->week_max_views) {
    // reached week's maximum views, set status (3) "week's limit reached"
    db_query("UPDATE {banner} SET status = 3 WHERE id = %d", $id);
  }

  echo $banner->html;
?>
