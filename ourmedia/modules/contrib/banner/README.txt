Overview
--------
The banner.module allows you to display ads on your Drupal website.  It
randomly displays the banners, and automatically tracks how many times each
is displayed and clicked.

Users of your website can be given ownership of banners, and be allowed to
modify certain settings and view statistics.


Requirements
------------
Drupal 4.5.x
PHP 4.3.0 or greater


Features
--------
 - Administrative features:
   o Supports many image types, flash animations, and text-based ads
   o Supports displaying multiple banners on one page
   o Supports scheduling when a banner is auto-enabled/disabled
   o Provides 'chance' mechansim to increase odds a given banner is displayed
   o Counts banner views/clicks
   o Provides overview page w/ statistics
   o Limit banner views and/or clicks (then auto-disable)
   o Permissions (not view banners, administer, user edit)
   o Provides filecaching for optimal performance
   o Utilizes javascript to rotate banners on cached pages
   o Multiple banner status, only 'enabled' are displayed.
     (Others:  disabled, blocked, pending, day limit reached, etc...)
   o Must approve user-uploaded banners
   o Can customize daily/weekly banner notification emails
   o Can send automatic renewal reminder for ads soon to expire

 - User features:
   o Can view/edit own banners if have 'manage banners' permission
   o Can view/edit all banners if have 'administer banners' permission
   o Can upload new banners in 'pending' state
   o Can manually enable/disable administratively approved banners
   o Provides daily banner statistics (views, clicks, %)
   o Can limit maximum daily views for each banner
   o Can enable daily notification email
   o Provides weekly banner statistics (views, clicks, %)
   o Can limit maximum weekly views for each banner
   o Can enable weekly notification email


Installation
------------
Please refer to the INSTALL file for installation directions.


Credits
-------
 - Core functionality originally written by:
    Marco Molonari
 - Maintaned by, user functionality by, enhancements by:
    Jeremy Andrews


Bugs and Suggestions
--------------------
Bug reports, support requests, feature requests, etc, should be posted to
banner module project page:
http://drupal.org/project/banner

Additional support can be found on the banner module mailing list:
http://www.kerneltrap.org/mailman/listinfo/drupal-banner
