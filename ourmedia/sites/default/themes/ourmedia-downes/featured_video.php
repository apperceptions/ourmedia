<!-- <body bgcolor="#CECECE"> -->
<body bgcolor="#fff">

<?php

    //**************************************************************************************
	// This page recreated by michael sullivan (ourmedia.org) 
	// The homepage media editor tool will now use different media players based on file extension of the media (url to media that was entered in edit tool)
	// Below is code that detects the file extensions (note: must not be a funky string, only .ext).  Below that there are simple 'if' statements followed by output code which we need to customize and add more of to cover all media formats.  This can evenutally be done in admin tool if we setup more variabbles.  no time for that now.  
	// Let's test this out... seems to work!  ms | 01-20-05
       // to-d0:  add object tags
	//***************************************************************************************	



			$mediaURL = ($_GET['video']);
            $screenshotURL = ($_GET['bigscreenshot']);
			//$artistURL = ($_GET['artist_media_page_url']);
			$extension = ''; 
			if($screenshotURL){
				$parts = split('\.', $screenshotURL);	
			}
			else{
				$parts = split('\.', $mediaURL);				
			}
			 
			if (count($parts) > 1) $extension = end($parts); 
			if (!$extension && count($parts) > 2) $ext = prev($parts);


//use this when we use naming convention for featured media. ie. featured media will be stored in special directory with the name featured_media.ext
//in the hopepage editor, for the #1 slot, you enter the image in the 'media url' field that is supposed to be displayed on homepage. when clicked, the featured_media.ext will load in that same space.
//this method would be temporary until the tool itself is properly extended later.  

//if ($extension == "jpg") {	
//	echo "<p align=\"center\"><a href=\"/path/to/featured_media/featured_media.ext\" target=\"_self\"><img border=\"0\" src=\"$mediaURL\" ></a></p>";
//		}

// temporary hardocoded featured media (bisbee), expeditiously hacked: 
$extension = strtolower($extension);
if ($extension == "jpg") {	
	echo "<p align=\"center\"><a href=\"$mediaURL\" target=\"_self\"><img border=\"0\" src=\"$screenshotURL\" ></a></p>";
		}

if ($extension == "gif") {	
	echo "<p align=\"center\"><a href=\"http://www.ourmedia.org/user/13\" target=\"_top\"><img border=\"0\" src=\"$mediaURL\" ></a></p>";
		}

if ($extension == "png") {	
	echo "<p align=\"center\"><a href=\"http://www.ourmedia.org/user/13\" target=\"_top\"><img border=\"0\" src=\"$mediaURL\" ></a></p>";
		}

//if ($extension == "jpg") {	
//	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" enablejavascript=\"true\"></embed></center>";
//}

if ($extension == "mov") {	
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}
		
if ($extension == "avi") {
	echo "<center><EMBED type=\"application/x-mplayer2\" pluginspage=\"http://microsoft.com/windows/mediaplayer/en/download/\" id=\"mediaPlayer\" name=\"mediaPlayer\" displaysize=\"4\" autosize=\"-1\" bgcolor=\"darkblue\" showcontrols=\"true\" showtracker=\"-1\" showdisplay=\"0\" showstatusbar=\"-1\" videoborder3d=\"-1\" width=\"320\" height=\"260\" src=\"$mediaURL\" autostart=\"true\" designtimesp=\"5311\ loop=\"true\"></EMBED></center>";
      }

if ($extension == "divx") {
	echo "<center><object classid=\"clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616\" width=\"320\" height=\"212\" codebase=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\"><param name=\"src\" value=\"$mediaURL\" /><embed type=\"video/divx\" src=\"$mediaURL\" autoPlay=\"true\" width=\"320\" height=\"212\" pluginspage=\"http://go.divx.com/plugin/download/\"></embed></object></center>";
      }

if ($extension == "wmv") {
    echo "<center><embed src=\"$mediaURL\" width=320 height=260 autoplay=false controller=true enablejavascript=true></embed></center>";
  }	

if ($extension == "mp4") {
    echo "<OBJECT CLASSID=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" CODEBASE=\"http://www.apple.com/qtactivex/qtplugin.cab\" WIDTH=\"320\" HEIGHT=\"260\" ><PARAM NAME=\"src\" VALUE=\"$mediaURL\"><PARAM NAME=\"autoplay\" VALUE=\"false\" ><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></OBJECT>";
  }

if ($extension == "m4v") {
    echo "<OBJECT CLASSID=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" CODEBASE=\"http://www.apple.com/qtactivex/qtplugin.cab\" WIDTH=\"320\" HEIGHT=\"260\" ><PARAM NAME=\"src\" VALUE=\"$mediaURL\"><PARAM NAME=\"autoplay\" VALUE=\"false\" ><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></OBJECT>";
  }


if ($extension == "mp3") {
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"15\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}

if ($extension == "mpeg" || $extension == "mpg") {
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"Sample.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}

if ($extension == "rm") {
	echo "<center><embed type=\"audio/x-pn-realaudio-plugin\" src=\"$mediaURL\" width=320 height=260 controls=ImageWindow console=one autostart=true></embed></center>";
		}

if ($extension == "ram") {
	echo "<center><embed type=\"audio/x-pn-realaudio-plugin\" src=\"$mediaURL\" width=320 height=260 controls=ImageWindow console=one autostart=true></embed></center>";
		}

if ($extension == "ra") {
	echo "add real media audio code";
		}

if ($extension == "swf") {
	echo "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"320\" height=\"240\"> <param name=\"movie\" value=\"$mediaURL\"> <param name=\"quality\" value=\"high\"><param name=\"bgcolor\" value=\"#FFFFFF\"> <param name=\"loop\" value=\"true\">  <EMBED src=\"$mediaURL\" quality=\"high\" bgcolor=\"#FFFFFF\" width=\"320\" height=\"240\" loop=\"true\" type=\"application/x-shockwave-flash\"  pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"></EMBED></OBJECT>";
}
if ($extension == "flv") {
    $output = <<<ENDOFPLAYER
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="320" height="268" id="320x268_v6" align="middle" wmode="transparent">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="/sites/ourmedi.org/files/flash/flvplayer.swf?file=$mediaURL" />
<param name="quality" value="high" />
<param name="wmode" value="transparent" />
<embed src="/sites/ourmedia.org/files/flash/flvplayer.swf?file=$mediaURL" swliveconnect="true" quality="high" width="320" height="268" name="320x268_v6" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></embed>
      </object>
ENDOFPLAYER;
echo $output;
  }

?>
