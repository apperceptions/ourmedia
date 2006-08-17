The SXIP module implements an authentication hook for the Sxip
Networks [1] identity network. This will allow a Drupal site to share
users with any other site that also implements SXIP logins. 

The SXIP module ties into Drupal's existing authentication
structure. It has the following capabilities: 

  * any user with a SXIP account can use a "sxip in" button to
    authenticate with the Drupal site 
  * if the user has an existing account on the Drupal site (determined
    by matching email addresses with the SXIP persona), the existing
    account is re-used 

You can get an overview of how the SXIP network works [2] from the SXIP site.

REQUIREMENTS

In addition to this module, you will need to download the PHP
Membersite Development Kit [2] 

SPONSORS
This module was developed by James Walker of Bryght.com [3], with
support from the following: 

    * SXIP [1]

[1] http://www.sxip.com
[2] http://www.sxip.com/press_sxipworks.html
[3] http://www.bryght.com
