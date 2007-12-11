WordPress Versioning Plugin v19.2

UPDATED AT:    http://benjismith.net/index.php/2006/08/03/disheartening-wordpress-bug/
ORIGINAL URL:  http://watershedstudio.com/portfolio/software/wp_versioning.html

-------
INSTALL
-------

1. FTP the wp-versioning directory to ../wp-content/plugins/

2. Activate the plugin

3. Go to the admin panel under Options > Versioning

4. Click on the link to run the install script

----------
CHANGE LOG
----------

On April 22, 2005, Brian Groce ( http://briangroce.com ) released version 1.0 beta.

On August 4, 2006, Benji Smith ( http://benjismith.net ) released version 19.2,
with the following changes:

Bugs Fixed:

* The PHP was coded with the assumption that REGISTER_GLOBALS would be set to
  TRUE. That's a dumb assumption, so I changed the code to use the
  $HTTP_GET_VARS global array.

* For any querystring parameters, I'm now using mysql_escape_chars() to prevent
  SQL injection attacks.

Better UI:

* More aesthetically-pleasing table layout.

* Betterly-named column headers

* Snapshots are now displayed in a format that preserves both the html markup 
  and the newlines in the post, making it a little easier to choose which
  snapshot to rollback to.

New Features:

* It's now possible to delete snapshots, to keep from clogging up the database
  with a bunch of unnecessary snapshots
