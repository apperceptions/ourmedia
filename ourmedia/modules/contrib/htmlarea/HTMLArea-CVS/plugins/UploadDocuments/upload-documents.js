/* 
UploadImage.js

This plugin is for use with Drupal, and the filestore2 module.

Gordon Heydon (gordon@heydon.com.au)
*/


function UploadDocuments(editor, params) {
  this.editor = editor; 
  editor._createLink = function(link) {
    var editor = this;
	  var outparam = null;
	  if (typeof link == "undefined") {
		  link = this.getParentElement();
		  if (link && !/^a$/i.test(link.tagName))
			  link = null;
	  }
	  if ( ! link ) {
		  var sel = editor._getSelection();
		  var range = editor._createRange(sel);
		  var compare = 0;
		  if (HTMLArea.is_ie) {
			  compare = range.compareEndPoints("StartToEnd", range);
		  } else {
			  compare =range.compareBoundaryPoints(range.START_TO_END, range);
		  }
		  if (compare == 0 ) {
			  alert("You need to select some text before creating a link");
			  return;
		  }
	  }
	  if (link) {
		  outparam = {
			  f_href   : HTMLArea.is_ie ? editor.stripBaseURL(link.href) : link.getAttribute("href"),
			  f_title  : link.title,
			  f_target : link.target,
			  f_usetarget : editor.config.makeLinkShowsTarget
		  };
	  } else {
		  outparam = {
			  f_href : '',
			  f_title : '',
			  f_target : '',
			  f_usetarget : editor.config.makeLinkShowsTarget
		  };
	  }
	  this._popupDialog("../plugins/UploadDocuments/popups/insert_link.php", function(param) {
		  if (!param)
			  return false;
		  var a = link;
		  if (!a) try {
			  editor._doc.execCommand("createlink", false, param.f_href);
			  a = editor.getParentElement();
			  var sel = editor._getSelection();
			  var range = editor._createRange(sel);
			  if (!HTMLArea.is_ie) {
				  a = range.startContainer;
				  if (!/^a$/i.test(a.tagName)) {
					  a = a.nextSibling;
					  if (a == null)
					  	a = range.startContainer.parentNode;
				  }
			  }
		  } catch(e) {}
		  else {
			  var href = param.f_href.trim();
			  editor.selectNodeContents(a);
			  if (href == "") {
				  editor._doc.execCommand("unlink", false, null);
				  editor.updateToolbar();
				  return false;
			  }
			  else {
				  a.href = href;
			  }
		  }
		  if (!(a && /^a$/i.test(a.tagName)))
			  return false;
		  a.target = param.f_target.trim();
		  a.title = param.f_title.trim();
		  editor.selectNodeContents(a);
		  editor.updateToolbar();
		}, outparam);
	};
};

UploadDocuments._pluginInfo = {
  name      : "UploadDocuments",
  developer : "Gordon Heydon",
  license   : "htmlarea"
};
