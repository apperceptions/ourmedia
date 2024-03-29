<?php
/*
  Drupal XML-RPC library. Based on the IXR - The Incutio XML-RPC Library - (c) Incutio Ltd 2002-2005
   Version 1.7 (beta) - Simon Willison, 23rd May 2005
   Site:   http://scripts.incutio.com/xmlrpc/
   Manual: http://scripts.incutio.com/xmlrpc/manual.php
   This version is made available under the GNU GPL License
*/

function xmlrpc_value($data, $type = FALSE) {
  $xmlrpc_value = new stdClass();
  $xmlrpc_value->data = $data;
  if (!$type) {
    $type = xmlrpc_value_calculate_type($xmlrpc_value);
  }
  $xmlrpc_value->type = $type;
  if ($type == 'struct') {
    /* Turn all the values in the array in to new xmlrpc_values */
    foreach ($xmlrpc_value->data as $key => $value) {
      $xmlrpc_value->data[$key] = xmlrpc_value($value);
    }
  }
  if ($type == 'array') {
    for ($i = 0, $j = count($xmlrpc_value->data); $i < $j; $i++) {
      $xmlrpc_value->data[$i] = xmlrpc_value($xmlrpc_value->data[$i]);
    }
  }
  return $xmlrpc_value;
}

function xmlrpc_value_calculate_type(&$xmlrpc_value) {
  $type = gettype($xmlrpc_value->data);
  switch ($type) {
    case 'boolean': case 'double':
      return $type;
    case 'integer':
      return 'int';
    case 'array':
      return range(0, count($xmlrpc_value->data) - 1) === array_keys($xmlrpc_value->data) ? 'array' : 'struct';
    case 'object':
      if ($xmlrpc_value->data->is_date) {
        return 'date';
      }
      if ($xmlrpc_value->data->is_base64) {
        return 'base64';
      }
      $xmlrpc_value->data = get_object_vars($xmlrpc_value->data);
      return 'struct';
    default:
      return string;
  }
}

function xmlrpc_value_get_xml($xmlrpc_value) {
  /* Return XML for this value */
  switch ($xmlrpc_value->type) {
    case 'boolean':
      return '<boolean>'. (($xmlrpc_value->data) ? '1' : '0') .'</boolean>';
      break;
    case 'int':
      return '<int>'. $xmlrpc_value->data .'</int>';
      break;
    case 'double':
      return '<double>'. $xmlrpc_value->data .'</double>';
      break;
    case 'string':
      return '<string>'. check_plain($xmlrpc_value->data) .'</string>';
      break;
    case 'array':
      $return = '<array><data>'."\n";
      foreach ($xmlrpc_value->data as $item) {
        $return .= '  <value>'. xmlrpc_value_get_xml($item) ."</value>\n";
      }
      $return .= '</data></array>';
      return $return;
      break;
    case 'struct':
      $return = '<struct>'."\n";
      foreach ($xmlrpc_value->data as $name => $value) {
        $return .= "  <member><name>". check_plain($name) ."</name><value>";
        $return .= xmlrpc_value_get_xml($value)."</value></member>\n";
      }
      $return .= '</struct>';
      return $return;
      break;
    case 'date':
      return xmlrpc_date_get_xml($xmlrpc_value->data);
      break;
    case 'base64':
      return xmlrpc_base64_get_xml($xmlrpc_value->data);
      break;
  }
  return FALSE;
}

function xmlrpc_message($message) {
  $xmlrpc_message = new stdClass();
  $xmlrpc_message->array_structs = array();   // The stack used to keep track of the current array/struct
  $xmlrpc_message->array_structs_types = array(); // Stack keeping track of if things are structs or array
  $xmlrpc_message->current_struct_name = array();  // A stack as well
  $xmlrpc_message->message = $message;
  return $xmlrpc_message;
}

function xmlrpc_message_parse(&$xmlrpc_message) {
  // first remove the XML declaration
  $xmlrpc_message->message = preg_replace('/<\?xml(.*)?\?'.'>/', '', $xmlrpc_message->message);
  if (trim($xmlrpc_message->message) == '') {
    return FALSE;
  }
  $xmlrpc_message->_parser = xml_parser_create();
  // Set XML parser to take the case of tags in to account
  xml_parser_set_option($xmlrpc_message->_parser, XML_OPTION_CASE_FOLDING, FALSE);
  // Set XML parser callback functions
  /* do not set object. $xmlrpc_message does not have member functions any more
  xml_set_object($xmlrpc_message->_parser, $xmlrpc_message); */
  xml_set_element_handler($xmlrpc_message->_parser, 'xmlrpc_message_tag_open', 'xmlrpc_message_tag_close');
  xml_set_character_data_handler($xmlrpc_message->_parser, 'xmlrpc_message_cdata');
  xmlrpc_message_set($xmlrpc_message);
  if (!xml_parse($xmlrpc_message->_parser, $xmlrpc_message->message)) {
    return FALSE;
  }
  xml_parser_free($xmlrpc_message->_parser);
  // Grab the error messages, if any
  $xmlrpc_message = xmlrpc_message_get();
  if ($xmlrpc_message->messagetype == 'fault') {
    $xmlrpc_message->fault_code = $xmlrpc_message->params[0]['faultCode'];
    $xmlrpc_message->fault_string = $xmlrpc_message->params[0]['faultString'];
  }
  return TRUE;
}

function xmlrpc_message_set($value = NULL) {
  static $xmlrpc_message;
  if ($value) {
    $xmlrpc_message = $value;
  }
  return $xmlrpc_message;
}

function xmlrpc_message_get() {
  return xmlrpc_message_set();
}

function xmlrpc_message_tag_open($parser, $tag, $attr) {
  $xmlrpc_message = xmlrpc_message_get();
  $xmlrpc_message->current_tag_contents = '';
  switch($tag) {
    case 'methodCall':
    case 'methodResponse':
    case 'fault':
      $xmlrpc_message->messagetype = $tag;
      break;
    /* Deal with stacks of arrays and structs */
    case 'data':
      $xmlrpc_message->array_structs_types[] = 'array';
      $xmlrpc_message->array_structs[] = array();
      break;
    case 'struct':
      $xmlrpc_message->array_structs_types[] = 'struct';
      $xmlrpc_message->array_structs[] = array();
      break;
  }
  xmlrpc_message_set($xmlrpc_message);
}

function xmlrpc_message_cdata($parser, $cdata) {
  $xmlrpc_message = xmlrpc_message_get();
  $xmlrpc_message->current_tag_contents .= $cdata;
  xmlrpc_message_set($xmlrpc_message);
}

function xmlrpc_message_tag_close($parser, $tag) {
  $xmlrpc_message = xmlrpc_message_get();
  $value_flag = FALSE;
  switch($tag) {
    case 'int':
    case 'i4':
      $value = (int)trim($xmlrpc_message->current_tag_contents);
      $value_flag = TRUE;
      break;
    case 'double':
      $value = (double)trim($xmlrpc_message->current_tag_contents);
      $value_flag = TRUE;
      break;
    case 'string':
      $value = $xmlrpc_message->current_tag_contents;
      $value_flag = TRUE;
      break;
    case 'dateTime.iso8601':
      $value = xmlrpc_date(trim($xmlrpc_message->current_tag_contents));
      // $value = $iso->getTimestamp();
      $value_flag = TRUE;
      break;
    case 'value':
      // "If no type is indicated, the type is string."
      if (trim($xmlrpc_message->current_tag_contents) != '') {
        $value = (string)$xmlrpc_message->current_tag_contents;
        $value_flag = TRUE;
      }
      break;
    case 'boolean':
      $value = (boolean)trim($xmlrpc_message->current_tag_contents);
      $value_flag = TRUE;
      break;
    case 'base64':
      $value = base64_decode(trim($xmlrpc_message->current_tag_contents));
      $value_flag = TRUE;
      break;
    /* Deal with stacks of arrays and structs */
    case 'data':
    case 'struct':
      $value = array_pop($xmlrpc_message->array_structs );
      array_pop($xmlrpc_message->array_structs_types);
      $value_flag = TRUE;
      break;
    case 'member':
      array_pop($xmlrpc_message->current_struct_name);
      break;
    case 'name':
      $xmlrpc_message->current_struct_name[] = trim($xmlrpc_message->current_tag_contents);
      break;
    case 'methodName':
      $xmlrpc_message->methodname = trim($xmlrpc_message->current_tag_contents);
      break;
  }
  if ($value_flag) {
    if (count($xmlrpc_message->array_structs ) > 0) {
      // Add value to struct or array
      if ($xmlrpc_message->array_structs_types[count($xmlrpc_message->array_structs_types)-1] == 'struct') {
        // Add to struct
        $xmlrpc_message->array_structs [count($xmlrpc_message->array_structs )-1][$xmlrpc_message->current_struct_name[count($xmlrpc_message->current_struct_name)-1]] = $value;
      } else {
        // Add to array
        $xmlrpc_message->array_structs [count($xmlrpc_message->array_structs )-1][] = $value;
      }
    } else {
      // Just add as a paramater
      $xmlrpc_message->params[] = $value;
    }
  }
  if (!in_array($tag, array("data", "struct", "member"))) {
    $xmlrpc_message->current_tag_contents = '';
  }
  xmlrpc_message_set($xmlrpc_message);
}

function xmlrpc_request($method, $args) {
  $xmlrpc_request = new stdClass();
  $xmlrpc_request->method = $method;
  $xmlrpc_request->args = $args;
  $xmlrpc_request->xml = <<<EOD
<?xml version="1.0"?>
<methodCall>
<methodName>{$xmlrpc_request->method}</methodName>
<params>

EOD;
  foreach ($xmlrpc_request->args as $arg) {
    $xmlrpc_request->xml .= '<param><value>';
    $v = xmlrpc_value($arg);
    $xmlrpc_request->xml .= xmlrpc_value_get_xml($v);
    $xmlrpc_request->xml .= "</value></param>\n";
  }
  $xmlrpc_request->xml .= '</params></methodCall>';
  return $xmlrpc_request;
}


function xmlrpc_error($code = NULL, $message = NULL) {
  static $xmlrpc_error;
  if ($code) {
    $xmlrpc_error = new stdClass();
    $xmlrpc_error->is_error = TRUE;
    $xmlrpc_error->code = $code;
    $xmlrpc_error->message = $message;
  }
  return $xmlrpc_error;
}

function xmlrpc_error_get_xml($xmlrpc_error) {
    return <<<EOD
<methodResponse>
  <fault>
  <value>
    <struct>
    <member>
      <name>faultCode</name>
      <value><int>{$xmlrpc_error->code}</int></value>
    </member>
    <member>
      <name>faultString</name>
      <value><string>{$xmlrpc_error->message}</string></value>
    </member>
    </struct>
  </value>
  </fault>
</methodResponse>

EOD;
}

function xmlrpc_date($time) {
  $xmlrpc_date = new stdClass();
  $xmlrpc_date->is_date = TRUE;
  // $time can be a PHP timestamp or an ISO one
  if (is_numeric($time)) {
    $xmlrpc_date->year = date('Y', $time);
    $xmlrpc_date->month = date('m', $time);
    $xmlrpc_date->day = date('d', $time);
    $xmlrpc_date->hour = date('H', $time);
    $xmlrpc_date->minute = date('i', $time);
    $xmlrpc_date->second = date('s', $time);
  }
  else {
    $xmlrpc_date->year = substr($time, 0, 4);
    $xmlrpc_date->month = substr($time, 4, 2);
    $xmlrpc_date->day = substr($time, 6, 2);
    $xmlrpc_date->hour = substr($time, 9, 2);
    $xmlrpc_date->minute = substr($time, 12, 2);
    $xmlrpc_date->second = substr($time, 15, 2);
  }
  return $xmlrpc_date;
}

function xmlrpc_date_get_xml($xmlrpc_date) {
  return '<dateTime.iso8601>'. $xmlrpc_date->year . $xmlrpc_date->month . $xmlrpc_date->day .'T'. $xmlrpc_date->hour .':'. $xmlrpc_date->minute .':'. $xmlrpc_date->second .'</dateTime.iso8601>';
}

function xmlrpc_base64($data) {
  $xmlrpc_base64 = new stdClass();
  $xmlrpc_base64->is_base64 = TRUE;
  $xmlrpc_base64->data = $data;
  return $xmlrpc_base64;
}

function xmlrpc_base64_get_xml($xmlrpc_base64) {
  return '<base64>'. base64_encode($xmlrpc_base64->data) .'</base64>';
}

/**
 * Perform an XML-RPC request.
 *
 * @param $url
 *    An absolute URL of the XML-RPC server
 * @param $method
 *    The method to be executed
 * @param ...
 *   A variable number of arguments of the method.
 * @return
 *   The return value of the method on success or FALSE. On FALSE, see
 *   xmlrpc_errno() and xmlrpc_error_msg().
 */
function xmlrpc() {
  $args = func_get_args();
  $url = array_shift($args);
  $method = array_shift($args);
  $xmlrpc_request = xmlrpc_request($method, $args);
//  $request .= "Content-Type: text/xml$r";
  $result = drupal_http_request($url, array("Content-Type" => "text/xml"), 'POST', $xmlrpc_request->xml);
  if ($result->code != 200) {
    xmlrpc_error(-$result->code, $result->error);
    return FALSE;
  }
  $message = xmlrpc_message($result->data);
  // Now parse what we've got back
  if (!xmlrpc_message_parse($message)) {
    // XML error
    xmlrpc_error(-32700, t('parse error. not well formed'));
    return FALSE;
  }
  // Is the message a fault?
  if ($message->messagetype == 'fault') {
    xmlrpc_error($message->faultcode, $message->faultstring);
    return FALSE;
  }
  // Message must be OK
  return $message->params[0];
}

/**
 * Perform multiple calls in one request if possible.
 *
 * @param $url
 *    An absolute URL of the XML-RPC server
 * @param $calls
 *    An array of calls. Each call is an array, where the first element
 *    is the method name, further elements are the arguments.
 * @return
 *    An array of results.
 */
function xmlrpc_multicall() {
  $args = func_get_args();
  $url = $args[0];
  foreach ($args[1] as $call) {
    $method = array_shift($call);
    $calls[] = array(
      'methodName' => $method,
      'params' => $call
    );
  }
  return xmlrpc($url, 'system.multicall', $calls);
}

/**
 * Returns the last XML-RPC client error number
 */
function xmlrpc_errno() {
  $error = xmlrpc_error();
  return $error->code;
}

/**
 * Returns the last XML-RPC client error message
 */
function xmlrpc_error_msg() {
  $error = xmlrpc_error();
  return $error->message;
}
?>
