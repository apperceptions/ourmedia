<?php 
require_once('../../../start_drupal.inc'); 

global $user;

if (module_exist("filestore2") && node_access("create", "filestore2") && $_POST["form_action"] == "upload") {
  $edit = $_POST["edit"];
  $edit = array_merge($edit, array('uid' => $user->uid, 'name' => $user->name, 'type' => 'filestore2'));
  $edit["taxonomy"][] = $_POST['tid'];
  if ($edit["title"] == "" || !$edit["title"]) {
    $edit["title"] = $_FILES["edit"]["name"]["upload_file"];
  }
  $newnode = node_submit($edit, FALSE);
}
?>
<html>
<head>
<title>Insert/Modify Link</title>
<script type="text/javascript" src="../../../popups/popup.js"></script>
<script type="text/javascript">
    window.resizeTo(400, 600);

I18N = window.opener.HTMLArea.I18N.dialogs;

function i18n(str) {
  return (I18N[str] || str);
};

function onTargetChanged() {
  var f = document.getElementById("f_other_target");
  if (this.value == "_other") {
    f.style.visibility = "visible";
    f.select();
    f.focus();
  } else f.style.visibility = "hidden";
};

function Init() {
  __dlg_translate(I18N);
  __dlg_init();
  var param = window.dialogArguments;
  var target_select = document.getElementById("f_target");
  var use_target = true;
  if (param) {
    if ( typeof param["f_usetarget"] != "undefined" ) {
      use_target = param["f_usetarget"];
    }
    if ( typeof param["f_href"] != "undefined" ) {
      document.getElementById("f_href").value = param["f_href"];
      document.getElementById("f_title").value = param["f_title"];
      comboSelectValue(target_select, param["f_target"]);
      if (target_select.value != param.f_target) {
        var opt = document.createElement("option");
        opt.value = param.f_target;
        opt.innerHTML = opt.value;
        target_select.appendChild(opt);
        opt.selected = true;
      }
    }
  }
  if (! use_target) {
    document.getElementById("f_target_label").style.visibility = "hidden";
    document.getElementById("f_target").style.visibility = "hidden";
    document.getElementById("f_target_other").style.visibility = "hidden";
  }
  var opt = document.createElement("option");
  opt.value = "_other";
  opt.innerHTML = i18n("Other");
  target_select.appendChild(opt);
  target_select.onchange = onTargetChanged;
  document.getElementById("f_href").focus();
  document.getElementById("f_href").select();
};

function onOK() {
  var required = {
    // f_href shouldn't be required or otherwise removing the link by entering an empty
    // url isn't possible anymore.
    // "f_href": i18n("You must enter the URL where this link points to")
  };
  for (var i in required) {
    var el = document.getElementById(i);
    if (!el.value) {
      alert(required[i]);
      el.focus();
      return false;
    }
  }
  // pass data back to the calling window
  var fields = ["f_href", "f_title", "f_target" ];
  var param = new Object();
  for (var i in fields) {
    var id = fields[i];
    var el = document.getElementById(id);
    param[id] = el.value;
  }
  if (param.f_target == "_other")
    param.f_target = document.getElementById("f_other_target").value;
  __dlg_close(param);
  return false;
};

function onCancel() {
  __dlg_close(null);
  return false;
};

function onNodeDisplay(url) {
  var el = document.getElementById('f_href');
  el.value = url;
};

</script>
<base href="<?php print $base_url ?>" />
<style type="text/css">
html, body {
  background: ButtonFace;
  color: ButtonText;
  font: 11px Tahoma,Verdana,sans-serif;
  margin: 0px;
  padding: 0px;
}
body { padding: 5px; }
table {
  font: 11px Tahoma,Verdana,sans-serif;
}
select, input, button { font: 11px Tahoma,Verdana,sans-serif; }
button { width: 70px; }
table .label { text-align: right; width: 8em; }

.title { background: #ddf; color: #000; font-weight: bold; font-size: 120%; padding: 3px 10px; margin-bottom: 10px;
border-bottom: 1px solid black; letter-spacing: 2px;
}

#buttons {
      margin-top: 1em; border-top: 1px solid #999;
      padding: 2px; text-align: right;
}
</style>
</head>
<body onLoad="Init()">
<div class="title">Drupal Insert/Modify Link</div>
<form action="" method="post" enctype="multipart/form-data" name="form1">
  <table border="0" style="width: 100%;">
    <tr>
      <td class="label">URL:</td>
      <td><input type="text" id="f_href" style="width: 100%" /></td>
    </tr>
    <tr>
      <td class="label">Title (tooltip):</td>
      <td><input type="text" id="f_title" style="width: 100%" /></td>
    </tr>
    <tr>
      <td class="label"><span id="f_target_label">Target:</span></td>
      <td><select id="f_target">
          <option value="">None (use implicit)</option>
          <option value="_blank">New window (_blank)</option>
          <option value="_self">Same frame (_self)</option>
          <option value="_top">Top frame (_top)</option>
        </select>
        <input type="text" name="f_other_target" id="f_other_target" size="10" style="visibility: hidden" />
      </td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="2">
    <tr>
      <td><fieldset>
        <legend>Documents</legend>
        <select name="tid" id="tid" style="width: 100% " onChange="this.form.submit();">
          <?php 
							if (isset($_POST["tid"])) {
								$tid = $_POST["tid"];
							}	?>
          <?php foreach(taxonomy_get_vocabularies("filestore2") as $k => $v) {?>
          <option value=""><?php echo $v->name ?></option>
          <?php 
									$tree = taxonomy_get_tree($k);
									foreach($tree as $term) { 
										if(!isset($tid)) {
											$tid = $term->tid;
										}
									?>
          <option value="<?php echo $term->tid ?>"<?php if ($tid == $term->tid) {
									echo " selected";
								} ?>><?php echo '-'._taxonomy_depth($term->depth, '-').$term->name ?></option>
          <?php		
									}
									
								}?>
        </select>
        <select name="nodes_list" size="15" id="nodes_list" style="width:100% " onChange="onNodeDisplay(this.value)">
          <?php 
        if (!empty($tid)) {
	$result = db_query("SELECT n.nid, n.title, fs.filename FROM {node} n INNER JOIN {term_node} r ON n.nid = r.nid INNER JOIN {filestore2} f ON n.nid = f.nid INNER JOIN {fscache} fs ON f.fsid = fs.fsid WHERE r.tid IN ('$tid') AND n.status = '1' AND n.type = 'filestore2' ORDER BY n.sticky DESC, n.created DESC");
	while($node = db_fetch_object($result)) {
	?>
          <option value="<?php print url("filestore2/download/$node->nid/$node->filename") ?>"><?php print $node->title ?></option>
          <?php }
        } ?>
        </select>
        </fieldset></td>
    </tr>
    <tr>
      <td><fieldset>
        <legend>Upload Document</legend>
        Description
        <input type="text" name="edit[title]" style="width:100% ">
        Document to Upload
        <input name="edit[upload_file]" type="file" style="width:100% " size="40">
        </fieldset></td>
    </tr>
  </table>
  <div id="buttons">
    <input type="hidden" name="form_action" id="form_action" />
    <button type="button" name="upload" onClick='this.form.form_action.value = "upload"; this.form.submit();'>Upload</button>
    <button type="button" name="ok" onClick="return onOK();">OK</button>
    <button type="button" name="cancel" onClick="return onCancel();">Cancel</button>
  </div>
</form>
</body>
</html>
