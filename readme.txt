=== Admin Bar Menu Packer ===
Contributors: kazuyk
Tags: admin bar, admin, bar, admin-bar, adminbar, menu, packer, pack, toolbar, tool, remove, hide, admin bar menu packer
Requires at least: 3.1
Tested up to: 4.9
Stable tag: 0.7.4
Text domain: admin-bar-menu-packer
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gather many menu items on toolbar into one menu to prevent the bar from getting cluttered. Simple and easy but useful.

== Description ==

Some of themes or plugins add menu items to the toolbar (admin bar). These additional items may be useful, but sometimes you feel annoyed as the toolbar fills up with various items. Admin Bar Menu Packer gathers these items on the toolbar into one hamburger menu. The message "Howdy, username" next to the Avatar is also removed, but most WordPress built-in items remain as they are.

= Debug Mode =

If `WP_DEBUG` is enabled, this plugin will run in debug mode.
In debug mode, you can see:

* [Node ID popup] Each item in the hamburger menu has title attribute contains node ID
* [Debug menu] Additional item (*'All Nodes'*) which contains node IDs of all items on the toolbar

= Translations =

This plugin is currently available in English and Japanese (日本語).

== Installation ==

1. Upload the `/admin-bar-menu-packer/` directory into your WordPress plugins directory, or install the plugin through the WordPress plugins screen directly.

2. Activate the plugin through the 'Plugins' screen in WordPress.

== Frequently Asked Questions ==

= Which built-in items will be packed? =

The following built-in items will be packed:

* New (Post, Media, Page, User)
* Customize
* Edit (Edit Post, Edit Page)
* View

The following built-in items will NOT be packed:

* WordPress logo Menu (About WordPress, WordPress.org, etc.)
* Site-name Menu (Visit site, Dashboard, Themes, Widgets, etc.)
* Updates
* Comments
* User Account Menu

In addition, the items added to the right of toolbar by some plugins, such as Debug Bar, will not be packed.

= Can I select which items to be packed? =

No, you can not. The current version of this plugin does not have settings screen and there is no way to select items to be packed.

= The packed menu items are displayed strangely. =

Please let me know the following information:

* Item name(s)
* The name and version of theme or plugin that shows the item

= How can I enable debug mode? =

Please enable `WP_DEBUG`. To do this, set the value of it to *true* in your wp-config.php file:
`
define( 'WP_DEBUG', true );
`

Please note that it enables debug mode throughout WordPress and PHP errors or warnings will be shown inside of pages.

== Screenshots ==

1. Sample toolbar and packed menu

== Changelog ==

= 0.7.4 =
* Corrected text domain
* Excluded [BackWpUp](https://wordpress.org/plugins/backwpup/)
* Excluded "Edit Guide" of [VK All in One Expansion Unit](https://wordpress.org/plugins/vk-all-in-one-expansion-unit/) and [BizVektor](https://ja.wordpress.org/themes/bizvektor-global-edition/)

= 0.7.3 =
* Minor fix (syntax error under older PHP environment)

= 0.7.2 =
* Excluded [Query Monitor](https://wordpress.org/plugins/query-monitor/) from plugin's menu

= 0.7.1 =
* Excluded [Show Current Templates](https://wordpress.org/plugins/show-current-template/) from plugin's menu

= 0.7.0 =
* Initial release candidate
