$Id: README.txt,v 1.2.2.1 2004/11/18 11:49:26 gordon Exp $

HTMLarea
--------

This is a module that allows for easy use of the javascript software
htmlarea. The module itself is used to setup all the textareas within
drupal will get converted to htmlarea editors, which make it alow easier to
add htmlarea, and other things including images to nodes in drupal.

If you would like to see a demo please see

http://www.heydon.com.au/?q=htmlareademo

Combined with my version of the image module you can upload and store images
in drupal and then select it later from categories.

Upload Images
-------------

If you want to use the UploadImage plugin you will need to install the
version of the image module that is in my sandbox - sandbox/gordon.  I have
not yet had the time to port the required modifications from my version to
the core version or image, but basically all images are referenced by the
url image/view/n and will return the imame.

To run the upload images, or upload documents plugins you will need to apply
the patches in the patch directory.

Special Thanks
--------------

Jadah - drumbeatinsight.com - for sponsoring the UploadImage plugin.
