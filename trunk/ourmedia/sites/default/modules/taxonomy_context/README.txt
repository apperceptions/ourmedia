taxonomy_context.module
README.txt

Description
------------

With version 4.5, taxonomy_context uses the _help() hook for output
instead of drupal_set_message() and so no longer requires editing 
to theme's display of messages.

The taxonomy_context module enables you to display several useful
types of information drawn from taxonomy terms.  By default in Drupal,
when you bring up a taxonomy term you get a list of "node" (stories, 
static pages, books, etc.) associated with that term--but nothing to
describe the term itself.  But for organizational websites one often
wants to display information like a title and description of the 
current section.  So this module includes several methods for
displaying information about taxonomy terms:
* breadcrumbs
* the current term
* listings of sub-terms of the current term

The module also includes a context-sensitive menu block for each 
vocabulary.

While it should be possible to use the module in other contexts, it's 
primarily suited to use with vocabularies where there is (a) a single
term hierarchy and (b) only one term per node.

Configuration
-------------
 
To configure the module, click administration > configuration 
> taxonomy_context.

Use the settings to select whether term and subterm info is displayed.
You can also disable the automatic styling of term info (see INSTALL).

Developer Usage
---------------

Navigation Buttons

Taxonomy context will generate context-sensitive top navigation buttons/
tabs.  Sample request:

  $buttons = taxonomy_context_buttons();

The $buttons variable will contain output with graphical navigation
buttons based on the first-level taxonomy terms in a given vocabulary.
You could reference this, e.g., in a theme.

Getting Context

The function taxonomy_context_get_context() can be invoked from any
theme or module to get the context of the current taxonomy and node.
It takes no parameters and returns an object with the following
properties:
tid: the current term's ID.  If a node is being displayed, this is
  the ID of the (first) term associated with the node.
root_tid: the ID of the top-level term in the hierarchy in which 
nid: the current node's ID; null if none
vid: the ID of the current term or node's vocabulary.
