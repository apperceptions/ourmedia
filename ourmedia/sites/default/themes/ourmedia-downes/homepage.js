/* Updates the image in the preview section. It also saves the necessary meta-data about
 * the media in the hidden fields to be used later
 */
 function updatepreview(imgpath, videourl, titlelabel, title, artistlabel,
  artist, mediatypelabel, mediatype, artistmediapage, mediapage, custom1label, custom1,
  custom2label, custom2, custom3label, custom3) {
    document.getElementById("previewImage").src=imgpath;
    document.getElementById("previewImageText").innerHTML = "<span class= \"media_item\">" + title + "</span><br/>" +artist + "<br/>" + custom1 + "<br/>" + custom2 + "<br/>";
    document.getElementById("videoDescription").innerHTML = mediatype;
  
    document.getElementById("videourl").value = videourl;
    document.getElementById("titlelabel").value = titlelabel;
    document.getElementById("title").value = title;
    document.getElementById("artistlabel").value = artistlabel;
    document.getElementById("artist").value = artist;
    document.getElementById("mediatypelabel").value = mediatypelabel;
    document.getElementById("mediatype").value = mediatype;
    document.getElementById("artistmediapage").value = artistmediapage;
    document.getElementById("mediapage").value = mediapage;
    document.getElementById("custom1label").value = custom1label;
    document.getElementById("custom1").value = custom1;
    document.getElementById("custom2label").value = custom2label;
    document.getElementById("custom2").value = custom2;
    document.getElementById("custom3label").value = custom3label;
    document.getElementById("custom3").value = custom3;
}

/* Plays the media in the video iframe and also updates the other metadata about
 * the object in the section next to it. It gets those values from the hidden
 * fields.
 */
function playmedia(pathtotheme) {

  document.getElementById("media_description").innerHTML = "<div class=\"media_artist\"><span class=\"media_item\">" +
    document.getElementById("artistlabel").value + "</span>:" +
    document.getElementById("artist").value +
    "<br/><span class=\"media_item\">" + document.getElementById("titlelabel").value + "</span>: " +
    document.getElementById("title").value + "</div><div class=\"media_file\"><span class=\"media_item\">" + document.getElementById("mediatypelabel").value + "</span>: " + document.getElementById("mediatype").value + "<br/> <span class=\"media_item\">" + document.getElementById("custom1label").value + "</span>: " +
    document.getElementById("custom1").value + "<br/><span class=\"media_item\">" + document.getElementById("custom2label").value + "</span>: " +
    document.getElementById("custom2").value + "<br/><span class=\"media_item\">" + document.getElementById("custom3label").value + "</span>: " +
    document.getElementById("custom3").value + "<br/><br/>Download, share or learn more on " + document.getElementById("artist").value + "'s <a href=\"" + document.getElementById("mediapage").value + "\"> media page</a><br> Also see artist's <a href=\"" + document.getElementById("artistmediapage").value + "\">member page</a></div>";

  document.getElementById("featuredVideo").src = pathtotheme +
  "/featured_video.php?video=" + document.getElementById("videourl").value;
  document.getElementById("featuredVideo").location = document.getElementById("featuredVideo").src;
}function updatefeaturedvideo(imgpath, videourl, titlelabel, title, artistlabel, artist,                              mediatypelabel, mediatype, artistmediapage, mediapage,                              custom1label, custom1, custom2label, custom2, custom3label, custom3, path2theme) {
  media_artist_innerHTML = "<span class=\"media_item\">" +
   artistlabel + "</span>: " + artist + "<br/>" + "<span class=\"media_item\">" +
   titlelabel + "</span>: " + title;

  media_file_innerHTML = "<span class=\"media_item\">" + 
    mediatypelabel + "</span>: " + mediatype + "<br/>" + "<span class=\"media_item\">" + 
    custom3label + "</span>: " + custom3 + "<br/>" + "<span class=\"media_item\">" + 
    custom2label + "</span>: " + custom2 + "<br/>" + "<span class=\"media_item\">" + 
    custom1label + "</span>: " + custom1 + "<br/><br/>";
  
  media_goto_innerHTML = 
    "GO TO <a href=\"" + mediapage + "\">media page</a><br>" +
    "GO TO <a href=\"" + artistmediapage + "\">member page</a>";
    
  document.getElementById("media_description").innerHTML = 
                      "<div class=\"media_artist\">" + media_artist_innerHTML + "</div>" +
                      "<div class=\"media_file\">" + media_file_innerHTML + "</div>" +  
                      "<div class=\"media_playbtn\"><img src=\"" + path2theme + "/white-play.gif\" onclick=\"playselectedvideo('" + path2theme + "')\" ></div>" +
                      "<div class=\"media_goto\">" + media_goto_innerHTML + "</div>";                     

  document.getElementById("videourl").value = videourl;
  document.getElementById("featuredVideo").src = path2theme + "/featured_video.php?video=" + videourl;  document.getElementById("featuredVideo").location = document.getElementById("featuredVideo").src;}
// plays the selected video in iframe when the white play button is clicked
function playselectedvideo(pathtotheme) {
  document.getElementById("featuredVideo").src = pathtotheme +   "/featured_video.php?video=" + document.getElementById("videourl").value;    document.getElementById("featuredVideo").location = document.getElementById("featuredVideo").src;
}
