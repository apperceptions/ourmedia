IA: Integration with the Internet Archive (http://www.archive.org/)

NOTE : *requires* flexinode (for now, anyway)


To use this module:

* place ia.module in the appropriate modules directory
* install ia.mysql
* go to admin -> settings -> ia and map the content types & fields 


current features:

* Allows users to enter their IA contrib account password
* Allows dist auth using your IA contributor account (email + password)
* Submits files to IA automatically (if the user has entered their
  contrib password).

TODO:
* poll IA for files uploaded to the collection & import as nodes

