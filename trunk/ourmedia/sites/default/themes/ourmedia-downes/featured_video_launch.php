<body bgcolor="#CECECE">

<?php


    //**************************************************************************************
	// This page recreated by michael sullivan (ourmedia.org) 
	// The homepage media editor tool will now use different media players baed on file extension of the media (url to media that was entered in edit tool)
	// Below is code that detects the file extensions (note: must not be a funky string, only .ext).  Below that there are simple 'if' statements followed by output code which we need to customize and add more of to cover all media formats.  This can evenutally be done in admin tool if we setup more variabbles.  no time for that now.  
	// Let's test this out... seems to work!  ms | 01-20-05
	//***************************************************************************************	


			$mediaURL = ($_GET['video']);
			$extension = ''; 
			$parts = split('\.', $_GET['video']); 
			if (count($parts) > 1) $extension = end($parts); 
			if (!$extension && count($parts) > 2) $ext = prev($parts);

//if ($extension == "jpg") {	
//	echo "<p align=\"center\"><img border=\"0\" src=\"$mediaURL\" ></p>";
//		}

if ($extension == "jpg") {	
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" enablejavascript=\"true\"></embed></center>";
}

if ($extension == "mov") {	
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}
		
if ($extension == "avi") {
	echo "<center><EMBED type=\"application/x-mplayer2\" pluginspage=\"http://microsoft.com/windows/mediaplayer/en/download/\" id=\"mediaPlayer\" name=\"mediaPlayer\" displaysize=\"4\" autosize=\"-1\" bgcolor=\"darkblue\" showcontrols=\"true\" showtracker=\"-1\" showdisplay=\"0\" showstatusbar=\"-1\" videoborder3d=\"-1\" width=\"285\" height=\"270\" src=\"$mediaURL\" autostart=\"true\" designtimesp=\"5311\ loop=\"true\"></EMBED></center>";
      }
	
if ($extension == "mp4") {
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}

if ($extension == "mp3") {
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"15\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}

if ($extension == "mpeg") {
	echo "<center><embed PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\" src=\"QT.mov\" QTSRC=\"$mediaURL\" width=\"320\" height=\"260\" autoplay=\"true\" controller=\"true\" enablejavascript=\"true\"></embed></center>";
		}

if ($extension == "rm") {
	echo "<center><embed type=\"audio/x-pn-realaudio-plugin\" src=\"$mediaURL\" width=320 height=270 controls=ImageWindow console=one autostart=true></embed></center>";
		}

if ($extension == "ram") {
	echo "<center><embed type=\"audio/x-pn-realaudio-plugin\" src=\"$mediaURL\" width=320 height=270 controls=ImageWindow console=one autostart=true></embed></center>";
		}

if ($extension == "ra") {
	echo "add real media audio code";
		}

//else  ($extension == "") {
//	echo "add simple link to download file if format is not supported by embed tags.";
//		}

?>