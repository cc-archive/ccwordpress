=== Search Unleashed ===
Contributors: johnny5
Donate link: http://urbangiraffe.com/about/support/
Tags: search, post, page, comment
Requires at least: 2.0
Tested up to: 2.4
Stable tag: trunk

Advanced WordPress search with full wildcard-supported searches, highlighting, and data taken from posts, pages, and comments *after* plugins

== Description ==

Extend the standard search with a full text search across everything (posts, pages, comments, authors, meta-data), with the output
from plugins included in the search.  Search phrases are highlighted, as are incoming searches from Google, Yahoo, and most other
search engines.

* Transparently extends WordPress search with a full text search that supports wildcards and logical operations
* Search through posts, pages, comments, meta-data, urls, and titles
* Data from plugins is included in the search - this is very important as previously any plugins that inserted data into
posts were not included in searches
* Everything can be configured - chose exactly what data you want to search
* Smart highlighting that shows a contextual snapshot of the search data
* Incoming search highlight.  Searches made through Google, Yahoo, Altavista, Baidu, Sogou, and MSN are all highlighted
* No changes are made to standard WordPress database tables
* Fast search
* Works with WP-Cache

The plugin is available in:
* English
* Deutsch by [Frank Bueltge](http://bueltge.de)
* Swedish by Olle Hellgren
* Russian by [Lecactus](http://lecactus.ru)
* Italiano by [Dario](http://dario.caregnato.net)

== Installation ==

The plugin is simple to install:

1. Download `search-unleashed.zip`
1. Unzip
1. Upload `search-unleashed` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin
1. Configure the plugin from `Manage/Search Unleashed`

You can find full details of installing a plugin on the [plugin installation page](http://urbangiraffe.com/articles/how-to-install-a-wordpress-plugin/).

== Frequently Asked Questions ==

= How does this work? =

An index is made of all appropriate data, including the output from any plugins.  Typically this does not occur as the standard
WordPress search is made against raw data, as opposed to data that has been processed by plugins.

Only relevant data is included in the index to keep the index as small and fast as possible.

The index is updated anytime a post is edited.

== Screenshots ==

1. Configure what data sources to search
2. Highlighted search results
3. Highlighted incoming searches

== Documentation ==

Full documentation can be found on the [Search Unleashed](http://urbangiraffe.com/plugins/search-unleashed/) page.

