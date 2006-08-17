/* 
UploadImage.js

This plugin is for use with Drupal, and the image module.

Gordon Heydon (gordon@heydon.com.au)
*/


function UploadImage(editor, params) {
  this.editor = editor; 
  editor._insertImage = function(image) {
		var outparam = null;
		if (typeof image == "undefined") {
			image = this.getParentElement();
			if (image && !/^img$/i.test(image.tagName))
				image = null;
		}
		if (image) outparam = {
			f_url    : HTMLArea.is_ie ? editor.stripBaseURL(image.src) : image.getAttribute("src"),
			f_alt    : image.alt,
			f_border : image.border,
			f_align  : image.align,
			f_vert   : image.vspace,
			f_horiz  : image.hspace
		};
		this._popupDialog("../plugins/UploadImage/popups/insert_image.php", function(param) {
			if (!param) {	// user must have pressed Cancel
				return false;
			}
			var img = image;
			if (!img) {
				var sel = editor._getSelection();
				var range = editor._createRange(sel);
				editor._doc.execCommand("insertimage", false, param.f_url);
				if (HTMLArea.is_ie) {
					img = range.parentElement();
					// wonder if this works...
					if (img.tagName.toLowerCase() != "img") {
						img = img.previousSibling;
					}
				} else {
					img = range.startContainer.previousSibling;
				}
			} else {
				img.src = param.f_url;
			}
			for (field in param) {
				var value = param[field];
				switch (field) {
			    	case "f_alt"    : img.alt	 = value; break;
			    	case "f_border" : img.border = parseInt(value || "0"); break;
			    	case "f_align"  : img.align	 = value; break;
			    	case "f_vert"   : img.vspace = parseInt(value || "0"); break;
		  		  case "f_horiz"  : img.hspace = parseInt(value || "0"); break;
				}
			}
		}, outparam);
	};
};

UploadImage._pluginInfo = {
  name      : "UploadImage",
  developer : "Gordon Heydon",
  license   : "htmlarea"
};