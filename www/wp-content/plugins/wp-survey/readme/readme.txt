=== SurveyMe ===
Contributors: dcoda
Donate link: http://wordpress.dcoda.co.uk/donate/surveyme/
Tags: captcha, csv, feedback, form, mysql, php5.2, recaptcha, survey, table
Requires at least: 3.0.0
Tested up to: 3.0.4
Stable tag: 1.7.0

Easy to modify Survey Form

== Description ==

This plugin is only supported on PHP 5.2 or greater.

SurveyMe is an easy to use and easy to modify survey tool for WordPress. Written to be easy to use to none coders.

You can design many contact surveys with various type of data collection including checkboxes ,radio buttons, textboxes textareas and many more and allows you to choose which fields you want to be mandatory.
It also contains a simple CAPTCHA to help prevent SPAM or if you with you can use the more elaborate Recaptcha.

You able to access the survey data directly via its own MySQL table or download the data in CVS format to import into any spreadsheet package.
If you are having trouble and cannot find the answers in the <a href="http://wordpress.org/extend/plugins/wp-survey/faq/">FAQ</a> you can post your support questions <a href = "http://wordpress.org/tags/wp-survey">here</a>

If you find SurveyMe useful please rate it at <a href="http://formplugins.dcoda.co.uk/2011/01/19/plugins-update/">wordpress.org</a> and please consider making a <a href="http://wordpress.dcoda.co.uk/donate/surveyme/">donation</a> to help us set aside more hours to maintain SurveyMe 

SurveyMe is written by <a href='http://dcoda.co.uk'>dcoda</a>

You can check out our other plugins <a href="http://profiles.wordpress.org/users/dcoda/">here</a>

If you require a custom plugin you can contact us <a href="http://dcoda.co.uk/contact/">here</a> and maybe we could write it for you.

== Installation ==

1. Disable and delete any old versions of the plugin.
2. Copy the plugin folder to `wp-content/plugins`
3. Log in to WordPress as an administrator.
4. Enable the plugin in the `Plugins` admin screen.
5. Visit the admin page `Plugins->Settings->SurveyMe to configure and get further help.
== Frequently Asked Questions ==

= Can I change the collection form after the survey has started =

Yes. Each time the data is saved any new fields will be added to the end of the table, no fields will be deleted.

= How to I change the size of the fields =

This can be done in CSS.
Each field on the form is assigned a HTML id base on its name or function. To find out a fields ID the best way is to view the page source after creating the form.
You can override any default CSS to set the style of a field by adding to your stylesheet :

&#35;fieldID
{
	// your style goes here
}

= Where can I get more help =

The settings screen contain inbuilt help boxes however you can post support your questions at http://wordpress.org/tags/wp-survey?forum_id=10  = Where can I get more help =

The settings screen contain inbuilt help boxes however you can post support your questions  [here](http://wordpress.org/tags/wp-survey?forum_id=10)  

== Screenshots ==

1. Sample form.
2. Sample form with missing mandatory fields
3. Forms Admin.
4. Form Management.

== Upgrade Notice ==

= 1.6.0 =

Fixed problem with special characters in questions, Fixed problem with table name length

= 1.5.0 =

Major new reboot and rewrite for WordPress 3.0
== Copyright ==

(c) Copyright DCoda Limited, 2007 -, All Rights Reserved.

This code is released under the GPL license version 3 or later, available here:

[http://www.gnu.org/licenses/gpl.txt](http://www.gnu.org/licenses/gpl.txt)

There are so many possibly configurations of installation the plugin can be installed on we limit testing to a PHP 5.2+ Linux platform running the latest version of WordPress at the time of release but it is released WITHOUT ANY WARRANTY;
 without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
