=== Profile Details TSW ===
Contributors:      tradesouthwest
Donate link:       https://paypal.me/tradesouthwest
Tags: user, profile, users, profiles, list, user list, login, members, table
Requires at least: 4.8
Requires PHP:      7.2
Requires CP:       1.4
Stable tag:        1.0.4
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-3.0.html
Plugin URI:        https://themes.tradesouthwest.com/wordpress/plugins/profile-details-tsw/

Creates easy to view user profile details.

== Description ==
Creates easy to view user profile details, in various views for public or private listing of user profiles and user details. Provides a list or table view of all users registered to a site.  Option to uniquely name the title of each publicly displayed user field to match the website objectives. Uses default WP fields such as avatar, nice_name, website, registered date, email as well as several additional custom fields that can be setup in the control panel of this plugin. Custom categories or objectives for users profile can also be added. 
Includes three shortcodes, one to place on a page which will show the controlled profile in a table format; and another to display all data for each user on a private page.

== Features == 
* List all users in your site on the front end or just on admin (private) page.
* Category tags can be added to users.
* Easier to view than the default WP User Profiles.
* Only view mode on the front end.
* Set what details can be displayed.
* Potentially shows Public displayed name, First/Last name, EMail, Website, Bio info, Profile pic, tags.
* Before and After Content widgets included.
* Supports comments.
* Admin private notation box
* TODO logged last date/time (GDPR Required)

== Screenshots ==
1. Listing fields
2. Admin Page with settings
3. Admin Page Three
4. Editor meta boxes
5. Basic listing page
6. Single listing page
7. Single listing with images

== Installation ==
This section describes how to install the plugin and get it working.
1. Upload `profile-details-tsw.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create pages with appropriate names and Add Shortcodes to Pages.
- To display GRID view:  [profile_details_grid] 
- To display TABLE view:  [profile_details_table] 
- To display individual&#39;s PROFILES:  [profile_details_profile]
- To display CATEGORY view:  [profile_details_category] 
4. Optional, add CATEGORY shortcode to page or use widget to display categories.
5. Go through settings and add field names and page view preferrences.

== Frequently Asked Questions ==
Q.: Where is the controls for this plugin?
A.: You will find the setup under menu page Settings > Profile Details. 

Q.: Can I change the listing style?
A.: If you know CSS then you can use any CSS editor to add a selctor in your Customizer or and other stylesheet. Using the Inspector on your browser it is easy to look up the selector name. Most all of OnList selectors start with onlist-.

== Upgrade Notice ==
 * ****************************************************************
 * You might need to flush your permalink rule. Do this by going to 
 * Settings > Permalinks and clicking the Save Changes button 
 * **********************************************************
== Changelog ==
1.0.4
* grid title chngd to grid-titles
* removed single helper files
* added many escape sequences

1.0.32
* fixed activator function
* changed author links

1.0.31
* added hidden class to login names.

1.0.3
* added options

1.0.21
* initial release
