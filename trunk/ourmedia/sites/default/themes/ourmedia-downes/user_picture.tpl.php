<?php
  if (variable_get('user_pictures', 0)) {
    if ($account->picture && file_exists($account->picture)) {
      $picture = file_create_url($account->picture);
    }
    else if (variable_get('user_picture_default', '')) {
      $picture = variable_get('user_picture_default', '');
    }

    if ($picture) {
      $alt = t('%user\'s picture', array('%user' => $account->name ? $account->name : variable_get('anonymous', 'Anonymous')));
      $picture = theme('image', $picture, $alt, $alt, "width = \"80px\"", false);
      if ($account->uid) {
        $picture = l($picture, "user/$account->uid", array('title' => t('View user profile.')));
      }
      print "<div class=\"picture\">$picture</div>";
    }
  }
?>
