<div class="node<?php print ($static) ? " static" : ""; ?>">
  <?php if ($page == 0): ?>
  <div class="comment">
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2></div>
  <?php endif; ?>

  <?php if ($picture): ?><?php print $picture ?>
  <?php endif; ?>

  <div class="main-content">
    <?php
      if ($node->type == 'blog') {
        $content = ($main && $node->teaser) ? $node->teaser : "<div><i>$node->teaser</i></div><br/><div>$node->body</div>";
      }
      print $content;
    ?>
  </div>
<?php
  if ($links) {
    global $user;
    if($node->type == 'blog' || $node->type == 'audiomedia' || $node->type == 'imagemedia' || $node->type == 'textmedia' || $node->type == 'videomedia' || $node->type == 'story' || $node->type == 'forum') {
      if ($user->uid != $node->uid) {
        $write_to_author_link = "privatemsg/msgto/$node->uid";
      }
      $abuse_report_link = "help/report-abuse";
      $links = $links . " | " . l(t('report abuse'), $abuse_report_link);
      if ($write_to_author_link) {
        $links .=  " | ". l(t('write to author'), $write_to_author_link);
      }
    }

    if (($node->type == 'blog' || $node->type == 'story') && $node->uid == $user->uid) {
      $links .= " | ". l(t('edit this post'), "node/$node->nid/edit");
    }
  }
?>
<?php if ($links): ?>
    <?php if ($picture): ?>
      <br class="clear" />
    <?php endif; ?>
    <div class="links"><?php print $links ?></div>
<?php endif; ?>
</div>
  
