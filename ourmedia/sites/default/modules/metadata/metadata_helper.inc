<?php

function get_field_name_value($node, $field) {
  if(isset($node->$field['name'])) {
    $value = $node->$field['name'];
  }
  else if (isset($node->metadata[$field['name']])) {
    $value = $node->metadata[$field['name']];
  }
  return $value;
}

function metadata_text_form($node, $field) {
  $value = get_field_name_value($node, $field);
  return form_textfield(t($field['title']), $field['name'], $value, 60, 128, t($field['helptext']), NULL, $field['required']);
}

function metadata_textarea_form($node, $field) {
  $value = get_field_name_value($node, $field);
  return form_textarea(t($field['title']), $field['name'], $value, 60, 5, t($field['helptext']), NULL, $field['required']);
}

function metadata_checkbox_form($node, $field) {
  $value = get_field_name_value($node, $field);
  if(!isset($value)) {
    $value = 0;
  }
  return form_checkbox(t($field['title']), $field['name'], 1, ($value == 1) ? TRUE : FALSE, $field['helptext']);
}

function metadata_date_form($node, $field) {
  $fieldname = $field['name'];
  $month_field = $fieldname . 'month';
  $day_field = $fieldname . 'day';
  $year_field = $fieldname . 'year';
  if(isset($node->$month_field)) {
    $value = gmmktime(0, 0, 0, $node->$month_field, $node->$day_field, $node->$year_field);
  }
  else if(isset($node->metadata[$month_field])) {
    $value = gmmktime(0, 0, 0, $node->metadata[$month_field], $node->metadata[$day_field], $node->metadata[$year_field]);
  }
  return form_item(t($field['title']), metadata_format_date($value, $fieldname), t($field['helptext']), NULL, false);
}

/**
 * A copy of event_form_date() with some modification to remove hour and minutes.
 */
function metadata_format_date($timestamp, $prefix = '') {
  // Construct the start time select boxes.
  $months = array(1 => t('January'), t('February'), t('March'), t('April'), t('May'), t('June'), t('July'), t('August'), t('September'), t('October'), t('November'), t('December'));
  for ($i = 1; $i <= 31; $i++) $days[$i] = $i;

  if (!isset($timestamp)) {
    $timestamp = time();
    // Round to nearest hour:
    $timestamp -= $timestamp % (60 * 60);
  }
  $timestamp = (int)$timestamp;

  // Use format_date(), it handles user timezone and locale.
  $year = format_date($timestamp, 'custom', 'Y');
  $month = format_date($timestamp, 'custom', 'm');
  $day = format_date($timestamp, 'custom', 'd');

  $when .= "<div style=\"height: 42px; width: 49%\">";
  $when .= "<div style=\"float: left; padding-right: 5px;\">" . form_select('', $prefix .'month', $month, $months) ."</div>";
  $when .= "<div style=\"float: left; padding-right: 5px;\">" . form_select('', $prefix .'day', $day, $days) ."</div>";
  $when .= "<div style=\"float: left; padding-right: 5px;\">" . form_textfield('', $prefix . 'year', $year, 4, 4, NULL, NULL, TRUE) . "</div>";
  $when .= '</div>';

  return $when;
}

function metadata_text_save($node, $field_name) {
  if(isset($node->$field_name))
  {
    db_query("DELETE FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
    db_query("INSERT INTO {metadata_data} (nid, type, value) VALUES (%d, '%s', '%s')", $node->nid, $field_name, strip_tags($node->$field_name));
  }
}

function metadata_date_save($node, $field_name) {
  $month_field = $field_name . 'month';
  $day_field = $field_name . 'day';
  $year_field = $field_name . 'year';
  db_query("DELETE FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
  if ($node->$month_field) {
    $result = gmmktime(0, 0, 0, $node->$month_field, $node->$day_field, $node->$year_field);
    if($result) {
      db_query("INSERT INTO {metadata_data} (nid, type, value) VALUES (%d, '%s', '%s')", $node->nid, $field_name, $result);
    }
  }
}

function metadata_field_load($node, $field_name) {
  $result = db_query("SELECT value FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
  if($result) {
    $meta_object = db_fetch_object($result);
    return $meta_object->value;
  }
}

function metadata_field_delete($node, $field_name) {
  db_query("DELETE FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
}

function metadata_get_text_fields($node, $field) {
  $field_name = $field['name'];
  $field_title = $field['title'];
  if(isset($node->$field_name)) {
    $value = $node->$field_name;
  }
  else {
    $result = db_query("SELECT value FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
    if($result && db_num_rows($result) > 0) {
      $meta_object = db_fetch_object($result);
      $value = $meta_object->value;
    }
  }
  if($value) {
    return array('title' => $field_title, 'value' => $value);
  }
}

function metadata_text_view($node, $field) {
  $text_fields = metadata_get_text_fields($node, $field);
  if($text_fields) {
    return "<div><b>" .$text_fields['title'] ."</b>: " . $text_fields['value'] ."</div>";
  }
}

function metadata_get_checkbox_fields($node, $field) {
  $field_name = $field['name'];
  $field_title = $field['title'];
  if(isset($node->$field_name)) {
    $checkbox_display = ($node->$field_name == 1) ? t('Yes') : t('No');
  }
  else {
    $result = db_query("SELECT value FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
    if($result && db_num_rows($result) > 0) {
      $meta_object = db_fetch_object($result);
      $checkbox_display = ($meta_object->value == 1) ? t('Yes') : t('No');
    }
  }
  if($checkbox_display) {
    return array('title' => $field_title, 'value' => $checkbox_display);
  }
}

function metadata_checkbox_view($node, $field) {
  $checkbox_fields = metadata_get_checkbox_fields($node, $field);
  return "<div><b>" . $checkbox_fields['title'] ."</b> " . $checkbox_fields['value']. "</div>";
}

function metadata_get_date_fields($node, $field) {
  $field_name = $field['name'];
  $field_title = $field['title'];
  $month_field = $field_name . 'month';
  $day_field = $field_name . 'day';
  $year_field = $field_name . 'year';

  if(isset($node->$month_field)) {
    $value = gmmktime(0, 0, 0, $node->$month_field, $node->$day_field, $node->$year_field);
  }
  else {
    $result = db_query("SELECT value FROM {metadata_data} WHERE nid = %d AND type = '%s'", $node->nid, $field_name);
    if($result && db_num_rows($result) > 0) {
      $meta_object = db_fetch_object($result);
      $value = $meta_object->value;
    }
  }
  if($value) {
    return array('title' => $field_title, 'value' => format_date((int)$value, "custom", "Y-m-d") );
  }
}

function metadata_date_view($node, $field) {
  $date_fields = metadata_get_date_fields($node, $field);
  return "<div><b>" . $date_fields['title'] ."</b>: " . $date_fields['value'] ."</div>";
}

?>
