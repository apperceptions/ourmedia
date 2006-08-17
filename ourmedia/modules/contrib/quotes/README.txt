quotes.module
README.txt
$Id: README.txt,v 1.3 2004/11/12 02:44:00 jhriggs Exp $

The quotes module allows users to maintain a list of quotations that
they find notable, humorous, famous, infamous, or otherwise worthy of
sharing with website visitors. The quotes can be displayed in any
number of administrator-defined blocks. These blocks will display
randomly-selected quotes based on the restrictions of each
block. Blocks can be configured to restrict to certain nodes, roles,
users, or categories.

The quotes module stores quotes in the SQL database. To create the
database table, the module must be installed using the quotes module
administration page (admin/quotes). The same page can be used for
upgrading as necessary.

The display of quotes is themeable using two functions,
theme_quotes_quote() which displays a single quote/author and
theme_quotes_page() which displays a list of quotes. The default
implementation of theme_quotes_quote() uses two CSS classes to allow
you to control the display of quotes. The quote itself uses
"quotes-quote". The author/attribution uses "quotes-author".

NOTE: Use of Drupal's cache (in "site configuration") is discouraged
as a static quote will be cached, and anonymous users will not see a
random quote. It will only be updated when a logged-in user makes a
change that invalidates the cache. If you have any ideas on getting
around this limitation, please let me know.

Files
  - quotes.module
      the actual module (PHP source code)

  - install.inc
      installation functions (PHP source code)

  - README.txt (this file)
      general module information

  - INSTALL.txt
      installation/configuration instructions

  - CREDITS.txt
      information on those responsible for this module

  - TODO.txt
      feature requests and modification suggestions

  - CHANGELOG.txt
      change/release history for this module

  - LICENSE.txt
      the license (GNU General Public License) covering the usage,
      modification, and distribution of this software and its
      accompanying files
