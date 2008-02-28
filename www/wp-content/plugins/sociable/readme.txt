==== Sociable ====

Tags: social, bookmark, bookmarks, bookmarking, social bookmarking, social bookmarks
Contributors: Peter Harkins
URL: http://push.cx/sociable
Desc: Automatically add links on your posts to popular social bookmarking sites.

Download, Upgrading, Installation:

Upgrade

1. First deactivate Sociable
2. Remove the sociable directory

Install

3. Unzip the sociable.zip file. 
4. Upload the the sociable folder (not just the files in it!) in your
   wp-contents/plugins folder. If you're using FTP, use 'binary' mode.

Activate

5. In your WordPress administration, go to the Plugins page
6. Activate the Sociable plugin and a subpage for Sociable will appear
   in your Options menu.

If you find any bugs or have any ideas, please mail me.


Advanced Users:

Sociable hooks the_content() and the_excerpt() to display without
requiring theme editing. To heavily customize the display, use the admin
panel to turn off the display on all pages, then add calls to your theme
files:

 
// This is optional extra customization for advanced users
<?php print sociable_html(); ?> // all active sites
<?php print sociable_html(Array("Reddit", "del.icio.us")); ?> // only these sites if they are active
