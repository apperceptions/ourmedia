<?php
// $Id: banner_file.php,v 1.9 2004/08/17 00:42:16 jeremy Exp $

  $key = (int)$_GET["key"];
  $pos = (int)$_GET["pos"];
  $cache_file = "misc/".(int)$key.".banner.cache";
  
  if (!$fd = @fopen($cache_file, "r+")) {
    // just to make the output valid JS...
    die("error = 'banner cache file not found.';");
  }
  flock($fd, LOCK_EX);
  $contents = fread($fd, filesize($cache_file));
  rewind($fd);

  $struct = unserialize($contents);
  $last_updated = $struct[2];

  $random = mt_rand(0, (count($struct[0][$pos]) - 1));
  $banner_id = $struct[0][$pos][$random];
  $this_banner = $struct[1][$banner_id];

  // add a view
  $struct[1][$banner_id]->views++;
  $struct[1][$banner_id]->day_views++;
  $struct[1][$banner_id]->week_views++;
  
  // once every minute update views in db
  if ($last_updated < (time() - 60)) {
    include_once "includes/bootstrap.inc";
    foreach ($struct[1] as $id => $banner) {
      db_query("UPDATE {banner} SET views = %d, day_views = %d, week_views = %d WHERE id = %d", $banner->views, $banner->day_views, $banner->week_views, $id);
    }

    $struct[2] = time();
  }

  // dump back in cache
  $data = serialize($struct);
  fwrite($fd, $data, strlen($data));
  flock($fd, LOCK_UN);
  fclose($fd);
  
  // if needed, disable this banner
  if ($this_banner->max_views > 0 && ($this_banner->views + 1) >= $this_banner->max_views) {
    include_once "includes/bootstrap.inc";
    include_once "modules/banner.module";
    // reached maximum views, set status (5) "blocked"
    db_query("UPDATE {banner} SET status = 5 WHERE id = %d", $banner_id);
    _banner_refresh_cache();
  }
  else if ($this_banner->day_max_views > 0 && ($this_banner->day_views + 1) >= $this_banner->day_max_views) {
    include_once "includes/bootstrap.inc";
    include_once "modules/banner.module";
    // reached day's maximum views, set status (2) "day's limit reached"
    db_query("UPDATE {banner} SET status = 2 WHERE id = %d", $banner_id);
    _banner_refresh_cache();
  }
  else if ($this_banner->week_max_views > 0 && ($this_banner->week_views + 1) >= $this_banner->week_max_views) {
    include_once "includes/bootstrap.inc";
    include_once "modules/banner.module";
    // reached week's maximum views, set status (3) "week's limit reached"
    db_query("UPDATE {banner} SET status = 3 WHERE id = %d", $banner_id);
    _banner_refresh_cache();
  }

  echo $this_banner->html;
?>
