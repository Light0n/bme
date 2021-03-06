=== User Role Editor ===
Contributors: shinephp
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=vladimir%40shinephp%2ecom&lc=RU&item_name=ShinePHP%2ecom&item_number=User%20Role%20Editor%20WordPress%20plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: user, role, editor, security, access, permission, capability
Requires at least: 4.0
Tested up to: 4.7.3
Stable tag: 4.32.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

User Role Editor WordPress plugin makes user roles and capabilities changing easy. Edit/add/delete WordPress user roles and capabilities.

== Description ==

With User Role Editor WordPress plugin you can change user role (except Administrator) capabilities easy, with a few clicks.
Just turn on check boxes of capabilities you wish to add to the selected role and click "Update" button to save your changes. That's done. 
Add new roles and customize its capabilities according to your needs, from scratch of as a copy of other existing role. 
Unnecessary self-made role can be deleted if there are no users whom such role is assigned.
Role assigned every new created user by default may be changed too.
Capabilities could be assigned on per user basis. Multiple roles could be assigned to user simultaneously.
You can add new capabilities and remove unnecessary capabilities which could be left from uninstalled plugins.
Multi-site support is provided.

To read more about 'User Role Editor' visit [this page](http://www.shinephp.com/user-role-editor-wordpress-plugin/) at [shinephp.com](http://shinephp.com)


Do you need more functionality with quality support in a real time? Do you wish to remove advertisements from User Role Editor pages? 
[Buy Pro version](https://www.role-editor.com). 
[User Role Editor Pro](https://www.role-editor.com) includes extra modules:
<ul>
<li>Block selected admin menu items for role.</li>
<li>Hide selected front-end menu items for no logged-in visitors, logged-in users, roles.</li>
<li>Block selected widgets under "Appearance" menu for role.</li>
<li>Show widgets at front-end for selected roles.</li>
<li>Block selected meta boxes (dashboard, posts, pages, custom post types) for role.</li>
<li>"Export/Import" module. You can export user roles to the local file and import them then to any WordPress site or other sites of the multi-site WordPress network.</li> 
<li>Roles and Users permissions management via Network Admin  for multisite configuration. One click Synchronization to the whole network.</li>
<li>"Other roles access" module allows to define which other roles user with current role may see at WordPress: dropdown menus, e.g assign role to user editing user profile, etc.</li>
<li>Manage user access to editing posts/pages/custom post type using posts/pages, authors, taxonomies ID list.</li>
<li>Per plugin users access management for plugins activate/deactivate operations.</li>
<li>Per form users access management for Gravity Forms plugin.</li>
<li>Shortcode to show enclosed content to the users with selected roles only.</li>
<li>Posts and pages view restrictions for selected roles.</li>
</ul>
Pro version is advertisement free. Premium support is included.

== Installation ==

Installation procedure:

1. Deactivate plugin if you have the previous version installed.
2. Extract "user-role-editor.zip" archive content to the "/wp-content/plugins/user-role-editor" directory.
3. Activate "User Role Editor" plugin via 'Plugins' menu in WordPress admin menu. 
4. Go to the "Users"-"User Role Editor" menu item and change your WordPress standard roles capabilities according to your needs.

== Frequently Asked Questions ==
- Does it work with WordPress in multi-site environment?
Yes, it works with WordPress multi-site. By default plugin works for every blog from your multi-site network as for locally installed blog.
To update selected role globally for the Network you should turn on the "Apply to All Sites" checkbox. You should have superadmin privileges to use User Role Editor under WordPress multi-site.
Pro version allows to manage roles of the whole network from the Netwok Admin.

To read full FAQ section visit [this page](http://www.shinephp.com/user-role-editor-wordpress-plugin/#faq) at [shinephp.com](shinephp.com).

== Screenshots ==
1. screenshot-1.png User Role Editor main form
2. screenshot-2.png Add/Remove roles or capabilities
3. screenshot-3.png User Capabilities link
4. screenshot-4.png User Capabilities Editor
5. screenshot-5.png Bulk change role for users without roles
6. screenshot-6.png Assign multiple roles to the selected users

To read more about 'User Role Editor' visit [this page](http://www.shinephp.com/user-role-editor-wordpress-plugin/) at [shinephp.com](shinephp.com).

= Translations =

If you wish to check available translations or help with plugin translation to your language visit this link
https://translate.wordpress.org/projects/wp-plugins/user-role-editor/


== Changelog ==
= [4.32.2] 17.03.2017 =
* Fix: "Users->Grant Roles" button did not work with switched off option "Count Users without role" at "Settings->User Role Editor->Additional Modules" tab. "JQuery UI" library was not loaded.
* Update: minimal PHP version was raised to 5.3.

= [4.32.1] 09.03.2017 =
* Fix: URL to users.php page was built incorrectly after bulk roles assignment to the users selected at the 1st page of a users list.

= [4.32] 09.03.2017 =
* New: Button "Grant Roles" allows to "Assign multiple roles to the selected users" directly from the "Users" page.
* Update: singleton template was applied to the main User_Role_Editor class. While GLOBALS['user-role-editor'] reference to the instance of User_Role_Editor class is still available for the compatibility purpose, call to User_Role_Editor::get_instance() is the best way now to get a reference to the instance of User_Role_Editor class.
* Fix: Missed 'unfiltered_html' capability is shown now at the 'General' capabilities group too.

= [4.31.1] 06.01.2017 =
* Fix: WP transients get/set were removed from URE_Own_Capabilities class. It leaded to the MySQL deadlock in some cases.
* Update: Base_Lib::get_request_var() sanitizes user input by PHP's filter_var() in addition to WordPress core's esc_attr().

= [4.31] 14.12.2016 =
* New: It's possible to remove unused user capabilities by list.
* Fix: There was no support for installations with the hidden/changed URL to wp-admin. URE uses 'admin_url()' now to get and check admin URL, instead of direct comparing URL with 'wp-admin' string.
* Fix: Deprecated capabilities were shown in some cases at the 'Core' group even with "Show deprecated capabilities" mode switched off.
* Update: Capability groups CSS classes are prefixed with 'ure-' in order to minimize possible CSS conflicts with other plugins/themes which may load styles with the same classes globally and break URE's markup.

= [4.30] 01.12.2016 =
* Update: compatible with WordPress 4.7
* New: "Granted Only" checkbox to the right from the "Quick Filter" input control allows to show only granted capabilities for the selected role or user.

= [4.29] 10.11.2016 =
* New: User Role Editor own user capabilities are grouped separately under Custom capabilities.
* Update: URE_Lib::is_super_admin() uses WordPress core is_super_admin() for multisite setup only. Superadmin is a user with 'administrator' role in the case of single site WordPress installation.  
  This is the difference with the WordPress core which counts as a superadmin (for single site WP installation) any user with a 'delete_users' capability.
* Update: BaseLib::option_selected() calls were replaced with the calls of a similar selected() function from WordPress core.

= [4.28] 20.10.2016 =
* New: WooCommerce plugin user capabilities (if exist) are grouped separately under Custom capabilities.
* Update: Temporally raised permissions flag is taken into account when checking, if user has a superadmin privileges. WordPress is_super_admin() function was replaced with custom wrapper to define if current user is a real superadmin or just a local admin with the temporally raised (add/edit users pages) permissions.

= [4.27.2] 15.09.2016 =
* Update: There was a conflict with plugins which use a '|' character at the custom user capabilities: e.g. 'Nginx Helper | Config' from "Nginx Helper' plugin.
* Fix: PHP notice was removed: Undefined property: URE_Role_View::$multisite in wp-content/plugins/user-role-editor/includes/classes/view.php on line 143
* Fix: WordPress multisite: Settings link under the URE plugin at the plugins list leads to the network admin now, not to the the single site settings page, which does not exist.
* Fix: WordPress multisite: conflict with "Visual Composer" plugin was resolved: single site administrators could now use Visual Composer editor.
* Fix: WordPress multisite: changed role name was not replicated to other sites when user clicked "Update" with "Apply to All Sites" option turned ON.

= [4.27.1] 22.08.2016 =
* Update: There was a conflict with plugins which use a '/' character at the custom user capabilities: e.g. vc_access_rules_backend_editor/disabled_ce_editor from Visual Composer.
* Update: add/delete, escape, validate user capability code extracted from URE_Lib to the separate URE_Capability class

= [4.27] 18.08.2016 =
* New: Total/Granted counters were added to the capabilities groups titles.
* New: "Columns" drop-down menu allows to change capabilities section layout to 1, 2 or 3 columns.
* New: Capabilities section is limited in height and has independent scrollbar.
* Update: User Role Editor page markup was updated to use more available space on page.
* Update: URE_Ajax_Processor class allows to differentiate required user permissions according to action submitted by user.
* Fix: CSS updated to exclude text overlapping at capabilities groups section when custom post type name is not fitted into 1 line.
* Fix: required JavaScript files were not loaded at "Network Admin->Settings->User Role Editor" page.

= [4.26.3] 25.07.2016 =
* Fix: Selecting a sub-group/list of caps does make the ure_select_all_caps checkbox select all within that group, but checking that box when at the "All" top-level group did not work.
* Fix: Notice: Undefined property: URE_Role_View::$apply_to_all

= [4.26.1] 14.07.2016 =
* Fix: some bugs, like 'undefined property' notices, etc.

= [4.26] 14.07.2016 =
* New: User capabilities were groupd by functionality for more convenience.
* Update: URE_KEY_CAPABILITY constant was changed from 'ure_edit_roles' to 'ure_manage_options'. To make possible for non-admin users access to the User Role Editor without access to the 'administrator' role and users with 'administrator' role.
* Update: User receives full access to User Role Editor under WordPress multisite if he has 'manage_network_plugins' capability instead of 'manager_network_users' as earlier. This allows to give user ability to edit network users without giving him access to the User Role Editor.
* Update: Multisite: use WordPress's global $current_site->blog_id to define main blog ID instead of selecting the 1st one from the sorted list of blogs.
* Update: use WP transients at URE_Lib::_get_post_types() to reduce response time.
* Update: various internal optimizations.

= [4.25.2] 03.05.2016 =
* Update: Enhanced inner processing of available custom post types list.
* Update: Uses 15 seconds transient cache in order to not count users without role twice when 'restrict_manage_users' action fires.
* Update: URE fires action 'profile_update' after direct update of user permissions in order other plugins may catch such change.
* Update: All URE's PHP classes files renamed and moved to the includes/classes subdirectory

= [4.25.1] 15.04.2016 =
* Fix: Selected role's capabilities list was returned back to old after click "Update" button. It was showed correctly according to the recent updates just after additional page refresh.
* Update: deprecated function get_current_user_info() call was replaced with wp_get_current_user().

= [4.25] 02.04.2016 =
* Important security update: Any registered user could get an administrator access. Thanks to [John Muncaster](http://johnmuncaster.com/) for discovering and wisely reporting it.
* URE pages title tag was replaced from h2 to h1, for compatibility with other WordPress pages.
* Fix: "Assign role to the users without role" feature ignored role selected by user.
* Fix: PHP fatal error (line 34) was raised at uninstall.php for WordPress multisite.
* Update: action priority 99 was added for role additional options hook action setup.


Click [here](https://www.role-editor.com/changelog)</a> to look at [the full list of changes](https://www.role-editor.com/changelog) of User Role Editor plugin.


== Additional Documentation ==

You can find more information about "User Role Editor" plugin at [this page](http://www.shinephp.com/user-role-editor-wordpress-plugin/)

I am ready to answer on your questions about plugin usage. Use [plugin page comments](http://www.shinephp.com/user-role-editor-wordpress-plugin/) for that.
