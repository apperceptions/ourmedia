README.txt
----------

Glossary helps newbies understand the jargon which always crops up when
specialists talk about a topic. Doctors discuss CBC and EKG and CCs.
Web developers keep talking about CSS, P2P, XSLT, etc. This is all
intimidating for newbies.

The glossary module scans posts for glossary terms (including synonyms).
The glossary indicator is inserted after every found term, or the term
itself is turned into an indicator depending on the site settings. By hovering
over the indicator, users may learn the definition of that term. Clicking
the indicator leads the user to that term presented within the whole
glossary or directly to the detailed description of the term, if
available.

The glossary uses Drupal's built in taxonomy feature, so you can organize
your terms in a Drupal vocabulary. This allows you to create hierarchical
structures, synonyms and relations. Glossary terms are represented with
the taxonomy terms in the glossary vocabulary. Descriptions are used to
provide a short explanation of the terms. You can attach nodes to the
terms to provide detailed explanation on the keywords.

This module also works with nicelinks.module, which will give you pretty
hover-over glossary term descriptions on reasonably modern browsers
(while degrading properly on older ones).

Feel free to improve this module and upload your improvements to Contrib.

Requirements
------------

This module requires the 4.5.x version of Drupal.

Installation
------------

1. Copy the glossary.module, the glossary.css and optionally the glossary.gif
   files to the Drupal modules/ directory. Drupal should automatically
   detect the module. Enable the module on the modules' administration page.

2. Glossary terms are managed as vocabularies within the taxonomy.module.
   To get started with glossary, create a new vocabulary on the
   taxonomy administration page. The vocabulary need not be associated
   with any modules, though you can attach detailed description to terms
   by adding nodes to the terms, so it might be a good idea to associate
   the vocabulary with the "story" module. Add a few terms to the vocabulary.
   The term title should be the glossary entry, the description should be
   the explanation of that term. You can make use of the hierarchy,
   synonym, and related terms features. These features impact the display
   of the glossary when viewed in an overview.

3. Next, you have to setup the input formats you want to use the glossary with.
   At the input formats page select an input format to configure. Select the
   Glossary filter checkbox and press Save configuration. Now select the
   configure filters tab and select the vocabulary and apply other settings.

4. If you would like to use the glossary icon, a default one is included
   with this module. 


Upgrading
---------

If you are upgrading from Glossary 4.4.0: instead of one global configuration
for the Glossary module, each input filter can now have its own configuration.
Go to the input filters page and configure the Glossary as described in 
step 3 above. You do not have to change anything in your taxonomy.

If you are upgrading from a CVS HEAD Glossary: many problems can be
prevented by flushing your cache. Connect to your database and issue the
command 'DELETE from cache'.
   

Authors
-------

More improvements by Frodo Looijaard <drupal [at] frodo.looijaard.name>
Many improvements by Gabor Hojtsy <goba [at] php.net>
Modified extensively by Al Maw <drupal-glossary [at] almaw.com>.
Originally written by Moshe Weitzman <weitzman [at] tejasa.com>. Much help from killes.
