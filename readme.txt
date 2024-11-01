=== FV WP Link Robot ===
Contributors: FolioVision
Donate link: http://foliovision.com/donate/
Tags: seo, link robot, resources, directory, backlinks
Requires at least: 2.9
Tested up to: 3.4.1
Stable tag: trunk

FV WP Link Robot is an advanced Link Directory managment system with backlink checking and SEO status information about your partners' sites.

== Description ==

It's based on the original WordPress Link Directory plugin by Alec Tang. So are there any reasons why you should use our improved version?

* Support for redirects - great in situation, when your link partner moves his link directory to some other URL.
* SEO information about you link partner's site included (PR, number of cached pages, Alexa rating, ...). Refresh this information with just a single click.
* Improved backlink searching algorithm.
* Don't delete your links, just move it the trash and you can always recycle it!
* Improved user interface - no popups used.
* Source was redone from scratch according to highest WordPress standards.

**[Download now!](http://foliovision.com/seo-tools/wordpress/plugins/wp-link-robot)**

[Support](http://foliovision.com/seo-tools/wordpress/plugins/wp-link-robot) |
[Installation](http://foliovision.com/seo-tools/wordpress/plugins/wp-link-robot/installation) |
[Usage](http://foliovision.com/seo-tools/wordpress/plugins/wp-link-robot/illustrated-user-guide) | 

== Installation ==

There aren't any special requirements for FV WP Link Robot to work, and you don't need to install any additional plugins.

   1. Download and unpack zip archive containing the plugin.
   2. Upload the wp-link-robot directory into wp-content/plugins/ directory of your wordpress installation.
   3. Go into Wordpress plugins setup in Wordpress administration interface and activate FV WP Link Robot plugin.
   4. Go into Wordpress Tools menu, open Link Robot, and configure your site details in the Settings tab.
   5. To show the list of links sorted in categories, just place the shortcode [wp_link_robot_genlinks] into the page where you wish the list of links to appear, type the page slug in the settings tab under Main directory options. Then go to Settings -> Permalinks and click "Save Changes".
   6. Also if you want to allow your visitors to submit their links, just place shortcode [wp_link_robot_addlinks] in the page and a form for link submission will be placed there.

   
== Frequently Asked Questions ==

= I created a categories, added links, placed the code into the page but my links are still not showig up. =

Please check if you set correctly the 'Directory slug name' in the setting tab. If you're using default permalink structure it should be left blank, but if other option is used, you should write the page slug here.

= After installing and activating the pugin I get an error saying: The plugin does not have a valid header. =

We know about this issue and we are working on it. However this does not affect the functionality of the plugin. Just go to your plugins section and activate the plugin there.

== Screenshots ==

1. Link management screen
2. Links can be organized into catgerories and subcategories
3. Adding new links

== Changelog ==

= 0.6 =
* First version released.

= 0.6.1 =
* keywords for categories fixed
* editing link fixed

= 0.6.3 =
* no permalinks structure fixed
 
= 0.6.4 =
* 'back' link fixed
* directories with subdirectories are now showing up

= 0.6.5 =
* 'back' link fixed for no permalinks
* subdirectories fixed

= 0.6.6 =
* flush rules removed
* rewriting rule for pages fixed

= 0.6.8 =
* listing subcategories fixed

= 0.6.9 =
* pagination in backend fixed

= 0.6.10 =
* z-index fixed
* rejecting links fixed

= 0.6.11 =
* proper shortcode support
* fix for canonical links

== Configuration ==

Once the plugin is uploaded and activated, there will be a submenu of tools menu called Link robot. In that submenu, you can manage your links and configure settings. The only information you need to fill is:
* My reciprocal url is the url that appears in your partners directories, e.g. "http://www.example.com"
* Directory's name - name of your directory where the your partners links will be displayed
* Directory slug name - once you create a page where the links will be displayed, type here the slug name

All other options are preconfigured, feel free to change them as you wish, or leave them in the default mode.

== Upgrade Notice ==

Initial release.
