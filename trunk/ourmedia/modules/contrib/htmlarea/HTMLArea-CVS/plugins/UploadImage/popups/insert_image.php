<?php 
require_once('../../../start_drupal.inc'); 

global $user;

if (user_access("upload images") && module_exist("image") && node_access("create", "image") && $_POST["form_action"] == "upload") {
	$edit = $_POST["edit"];
	$edit = array_merge($edit, array('uid' => $user->uid, 'name' => $user->name, 'type' => 'image'));
	$edit["taxonomy"][] = $_POST['tid'];
        if ($edit["title"] == "") {
          $edit["title"] = $_FILES["edit"]["name"]["image"];
        }
	$newnode = node_validate($edit);
	$newnode->nid = node_save($newnode);
}
?>
<html>
<head>
<title>Drupal Insert Image</title>
<script type="text/javascript" src="../../../popups/popup.js"></script>
<script type="text/javascript">
<!--
window.resizeTo(600, 100);

function Init() {
  __dlg_init();
  var param = window.dialogArguments;
  if (param) {
      document.getElementById("f_url").value = param["f_url"];
      document.getElementById("f_alt").value = param["f_alt"];
      document.getElementById("f_border").value = param["f_border"];
      document.getElementById("f_align").value = param["f_align"];
      document.getElementById("f_vert").value = param["f_vert"];
      document.getElementById("f_horiz").value = param["f_horiz"];
      document.preview.src = param.f_url;
  }
  document.getElementById("f_url").focus();
};

function onOK() {
  var required = {
    "f_url": "You must enter the URL"
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
  var fields = ["f_url", "f_alt", "f_align", "f_border",
                "f_horiz", "f_vert"];
  var param = new Object();
  for (var i in fields) {
    var id = fields[i];
    var el = document.getElementById(id);
    param[id] = el.value;
  }
  __dlg_close(param);
  return false;
};

function onCancel() {
  __dlg_close(null);
  return false;
};

function onPreview() {
  var f_url = document.getElementById("f_url");
  var url = f_url.value;
  if (!url) {
    alert("You have to enter an URL first");
    f_url.focus();
    return false;
  }
  document.preview.src = url;
  return false;
};

function onNodeDisplay(url) {
	document.preview.src = document.MM_p[0].src
	var el = document.getElementById('f_url');
	el.value = url;
	document.preview.src = el.value;
};

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
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
form p {
  margin-top: 5px;
  margin-bottom: 5px;
}
.fl { width: 9em; float: left; padding: 2px 5px; text-align: right; }
.fr { width: 6em; float: left; padding: 2px 5px; text-align: right; }
fieldset { padding: 0px 10px 5px 5px; }
select, input, button { font: 11px Tahoma,Verdana,sans-serif; }
button { width: 70px; }
.space { padding: 2px; }

.title { background: #ddf; color: #000; font-weight: bold; font-size: 120%; padding: 3px 10px; margin-bottom: 10px;
border-bottom: 1px solid black; letter-spacing: 2px;
}
form { padding: 0px; margin: 0px; }
</style>
</head>
<body onload="Init();MM_preloadImages('<?php print _htmlarea_get_jsdir(); ?>/plugins/UploadImage/popups/please_wait.png')"> 
<div class="title">Drupal Insert Image</div> <?php $messages = drupal_get_messages();
if ($messages) { ?>
<div class="messages"><?php foreach ($messages as $message) {
    list($message, $type) = $message;
    $output .= "<strong>". t("Status") ."</strong>: $message<hr />";
  }?></div><?php }?>
<!--- new stuff ---> 
<form method="post" enctype="multipart/form-data" name="form1" id="form1"> 
  <table width="100%"> 
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="2"> 
          <tr> 
            <td><fieldset> 
              <legend>Classes</legend> 
              <select name="tid" id="tid" style="width: 15em " onChange="this.form.submit();"><?php 
if (isset($_POST["tid"])) {
    $tid = $_POST["tid"];
}	?>
<?php foreach(taxonomy_get_vocabularies("image") as $k => $v) {?>
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
              </fieldset></td> 
          </tr> 
          <tr> 
            <td><fieldset> 
              <legend>Images</legend> 
              <select name="nodes_list" size="20" id="nodes_list" style="width:15em " onChange="onNodeDisplay(this.value)">
                <?php 
if (!empty($tid)) {
    $result = db_query("SELECT n.nid, n.title FROM {node} n INNER JOIN {term_node} r ON n.nid = r.nid WHERE r.tid IN ('$tid') AND n.status = '1' AND n.type = 'image' ORDER BY sticky DESC, created DESC");
}
else {
    $result = db_query("SELECT n.nid, n.title FROM {node} n WHERE n.status = '1' AND n.type = 'image' ORDER BY sticky DESC, created DESC");
}
while($node = db_fetch_object($result)) {
    print '<option value="'. url("image/$node->nid") .'">'. $node->title ."</option>\n";
}

?>
</select>
</fieldset></td> 
</tr> 
        </table></td> 
      <td> <table border="0" width="100%" style="padding: 0px; margin: 0px"> 
          <tbody> 
            <tr> 
              <td style="width: 7em; text-align: right">Image URL:</td> 
              <td><input type="text" name="url" id="f_url" style="width:70%" title="Enter the image URL here"<?php 
                if ($_POST["form_action"] == "upload" && !form_get_errors()) {
	          $imgpath = url("image/$newnode->nid");
                  print " value=\"$imgpath\"";
                }
                ?> />
                <button name="preview" onclick="return onPreview();"
      title="Preview the image in a new window">Preview</button></td> 
            </tr> 
            <tr> 
              <td style="width: 7em; text-align: right">Alternate&nbsp;text:</td> 
              <td><input type="text" name="alt" id="f_alt" style="width:100%" title="For browsers that don't support images" /></td> 
            </tr> 
          </tbody> 
        </table> 
        <p /> 
        <fieldset style="float: left; margin-left: 5px;"> 
        <legend>Layout</legend> 
        <div class="space"></div> 
        <div class="fl">Alignment:</div> 
        <select size="1" name="align" id="f_align" title="Positioning of this image"> 
          <option value=""                             >Not set</option> 
          <option value="left"                         >Left</option> 
          <option value="right"                        >Right</option> 
          <option value="texttop"                      >Texttop</option> 
          <option value="absmiddle"                    >Absmiddle</option> 
          <option value="baseline" selected="1"        >Baseline</option> 
          <option value="absbottom"                    >Absbottom</option> 
          <option value="bottom"                       >Bottom</option> 
          <option value="middle"                       >Middle</option> 
          <option value="top"                          >Top</option> 
        </select> 
        <p /> 
        <div class="fl">Border&nbsp;thickness:</div> 
        <input type="text" name="border" id="f_border" size="5" title="Leave empty for no border" /> 
        <div class="space"></div> 
        </fieldset> 
        <fieldset style="float:right; margin-right: 5px;"> 
        <legend>Spacing</legend> 
        <div class="space"></div> 
        <div class="fr">Horizontal:</div> 
        <input type="text" name="horiz" id="f_horiz" size="5" title="Horizontal padding" /> 
        <p /> 
        <div class="fr">Vertical:</div> 
        <input type="text" name="vert" id="f_vert" size="5" title="Vertical padding" /> 
        <div class="space"></div> 
        </fieldset> 
        <br clear="all" /> <?php if (user_access("upload images") && module_exist("image") && node_access("create", "image")) {?>
				<table width="100%" style="margin-bottom: 0.2em">
				  <tr>
				    <td><fieldset>
				      <legend>Upload File</legend>
				      <table width="100%">
				        <tr>
				          <td align="right">File to upload</td>
				          <td><input name="edit[image]" type="file" id="image" size="35"></td>
			          </tr>
				        <tr>
				          <td align="right">Description</td>
				          <td><input name="edit[title]" type="text" id="title" style="width:70% ">
                    <input name="form_action" type="hidden" id="form_action">				            
                    <button type="button" name="upload" onClick="document.form1.form_action.value = 'upload'; document.form1.submit();">Upload</button></td>
			          </tr>
			        </table>
				      </fieldset></td>
			    </tr>
			  </table><?php } ?>
				<table width="100%" style="margin-bottom: 0.2em"> 
          <tr> 
            <td valign="bottom"> <p>Image Preview:</p>
              <p><img name="preview" src="<?php 
                if ($imgpath) {
                  print $imgpath;
                }
                elseif ($_POST["nodes_list"]) {
                  print "image/" .$_POST["nodes_list"] ."?res=300x200";
		}
		else {
                  print _htmlarea_get_jsdir(); ?>/plugins/UploadImage/popups/default.png<?php }?>" width="300" height="200" alt=""></p></td> 
            <td valign="bottom" style="text-align: right"> <button type="button" name="ok" onclick="return onOK();">OK</button>
            <br>
<button type="button" name="cancel" onclick="return onCancel();">Cancel</button></td> 
          </tr> 
        </table></td> 
    </tr> 
  </table> 
</form> 
</body>
</html>
