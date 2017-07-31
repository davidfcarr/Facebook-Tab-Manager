=== Facebook Tab Manager ===
Contributors: davidfcarr, hplogsdon
Tags: facebook, fb, rich text editor, custom post type, social media, facebook tab, iframe
Donate link: http://facebooktabmanager.com
Requires at least: 3.0
Tested up to: 4.8
Stable tag: 3.4.2
Makes WordPress function as an editor for tabs you can embed in a Facebook page for your business, campaign, or organization.

== Description ==

The Facebook Tab Manager allows you to create landing pages and other types of content to be displayed within Facebook, particularly within the tabs that appear on Facebook business pages and pages for other types of organizations. This provides a way of putting more interesting layouts and functionality into your Facebook pages, without the need to get too deep into fancy programming.

The Facebook Tab Manager was specifically designed to take advantage of a recent Facebook page redesign that added support for iframe tabs on Facebook pages. Optionally, you can now also specify content to be displayed on an associated canvas page.

Tab content can include most any WordPress content, including output from Shortcodes and other plugin functions.

[__Facebook Tab Manager for WordPress Documentation, Blog and SSL Hosting__](http://tabmgr.com/)

[__Live examples at www.facebook.com/carrcomm__](http://www.facebook.com/carrcomm)

**Note:** Facebook requires all "apps" and page tabs to be displayed within facebook.com be available from both an HTTP and an HTTPS address. This means you must obtain an SSL certificate for your web domain and configure it on your server. When Facebook users browse the website in HTTPS mode, you need to be able to present your embedded content at an HTTPS address also.

As an alternative, Carr Communications offers a subscription service including WordPress accounts with Facebook Tab Manager and SSL security pre-configured at [__TabMgr.com__](https://tabmgr.com/).

== Installation ==

1. Upload the entire `facebook-tab-manager` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Create/edit content through the Facebook Tabs menu. The editing screen provides guidelines for how to fill in the Facebook Developer form to register your tab content as a Facebook app.
1. Optionally, copy the fbtab-theme folder to your `/wp-content/themes/` directory and modify as desired. Themes for use with Facebook Tab Manager are activated through the plugin's settings screen, independently of your site's theme for website visitors.

= Shortcode Options =

* To include a JavaScript widget, such as one of the [Facebook Social Plugins](http://developers.facebook.com/docs/plugins/), paste it into the WordPress editor in `Visual` mode and wrap it with `[fbtab]WIDGET-CONTENT-HERE[/fbtab]`. The shortcode processing function will fix the HTML entities the editor adds on angle brackets and quotation marks.
* To include blog post or page content, you can use `[fbtab query="QUERY-STRING"]` where the query string is something like query="posts_per_page=10" or query="p=1" or query="category_name=facebook-tab-manager" -- see the documentation for the [query_posts function](http://codex.wordpress.org/Function_Reference/query_posts) for possible values. You can also use a format attribute of format="headline" or format="excerpt"
* **LIKE Gating:** Early versions of this plugin emphasized the ability to show different content if the Facebook page a tab is embedded in has been "Liked" by the user and provided shortcodes for that purpose. This "Like Gate" functionality is no longer supported by Facebook.

== Frequently Asked Questions ==

= Where can I get more information about using Facebook Tab Manager? =

See the [plugin homepage](https://tabmgr.com/).

Also discussions on the [Carr Communications Facebook page](http://www.facebook.com/carrcomm).

== Screenshots ==

1. Custom tabs added to a Facebook business page.
2. When you create a Facebook tab in WordPress, the parameters you will need to register it on Facebook are displayed within the editor user interface.
3. The Facebook Developers website lets you create a new app.
4. On the Settings page, you record the parameters provided by Facebook Tab Manager. Facebook also provides an App ID number that you record in WordPress.
5. Enable the app to take it out of the developers "sandbox" mode.
6. Once you record the App ID in Facebook Tab Manager, follow the Add to Page link to associate it with a page that you manage.

== Credits ==

    Facebook Tab Manager
    Copyright (C) 2011 David F. Carr

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    See the GNU General Public License at <http://www.gnu.org/licenses/>.
	
== Changelog ==

= 3.4.2 =

* Factored out legacy code for showing different content depending on whether the tab is embedded in a page that has been "liked" (no longer supported by Facebook)
* Confirmed compatibility with WordPress 4.8

= 3.4 =

* Better able to use standard WordPress themes, including child themes, for content to be embedded in Facebook. Responsive themes and themes meant for display on an iPad or similar narrow format layout could be ideal.
* checked compatibility with WordPress 3.8.


= 3.3.5 =

Fixes the way the Reveal Tab utility handles resizing. Because these tabs are not associated with a single post, the App ID must be passed to the plugin as a query string on the URL entered into the Facebook app developer utility as the source of tab content. The App ID assigned by Facebook must be associated with the JavaScript used for resizing or it won't work properly.

= 3.3.4 =

Refinements to code for compatibility with standard WordPress theme coding.

= 3.3.2 =

Tweaked to make it easier to design a theme for use with Facebook Tab Manager more like a standard WordPress theme, or adapt a standard theme for use with Facebook tabs. Content area must be narrow enough to fit within the tab dimensions, but JavaScript to resize the height of the tab can be output from the standard wp_footer call. Code to surpress the operation of undesirable actions or filters will be loaded before theme is loaded. However, the plugin cannot control any theme-specific functionality.

Some of the standard checkbox options like "Open Links / Post Forms to New Window" will not function unless you include fbtab_head(); in the head section of your theme/template. Alternatively, you can link between fbtab content and have it displayed within the same frame and template, but if you link to standard pages you'll need to include ?fb=tab as a query string at the end of the link (otherwise, the content will appear within the frame but be displayed in your standard website template).

= 3.3 =

Updated code for resizing height of page tabs. It's now particularly important to record the App ID assigned by Facebook in the tab manager plugin, as this version of the code will not work without access to that information. Reveal or gate tabs created through the Reveal Tab Setup utility will also need to have an App ID assigned -- if you provided this previously you will have to enter it again for this release because it's handled differently (encoded into the url).

= 3.2 =

Update for Timeline page layout to support 810px tab width (usable width for images now more like 800 to 805 pixels). Existing tabs will continue to display in 520-pixel width layout until the layout setting is changed. Note that the change to 810 px width must also be implemented in the [Facebook developers utility](https://developers.facebook.com/apps).

= 3.1 =

* Improved the Reveal Tab Setup utility, particularly by adding the ability to register the App ID for a page tab and associate it with a particular reveal tab pair of posts. Since Facebook eliminated the application profile pages that used to display an Add to Page link, tab authoring tools like Facebook Tab Manager now must take over that responsibility. So once you register your tab content with Facebook, you need to come back to Facebook Tab Manager to record the App ID needed to link the two together.
* The editor now includes a "More code to add to footer" box you can use to add custom JavaScript or HTML. I've used it to add JQuery animation to the page, for example. An older technique for inserting that code inline using shortcodes is still supported.

= 3.03 =

Listing of fbtab posts available for editing now displays author login name and an Add to Page link (for adding your tab to one or more Facebook pages) or a Set App ID link if you have yet to record the App ID # set by Facebook for the tabs you have registered.

= 3.01, 3.02 = 

Bug fixes

= 3.0 =

Modified the roles and capabilities setup, which previously was set to parallel the 'page' rather than 'post' content type. The settings screen now includes checkbox options that let you make creation of Facebook tab content accessible to authors and contributors (contributors can submit posts for approval).

By default, fbtabs are still treated like pages rather than posts, meaning only editors and administrators can edit them, but at least one website owner contacted me to say that's not how he wanted it to work. So now you have a choice. Authoring and editing capabilities can also be such as edit_fbtabs can now also be set for any custom roles you may have created, separately from other types of posts, by other WordPress administration plugins or your own custom code.

= 2.9.9 =

Bug fix for settings screen (footer options).

= 2.9.8 =

Cleanup of deprecated WordPress functions and minor PHP coding errors.

= 2.9.7 =

Continuing to address recent changes in the Facebook platform, which made it necessary for Facebook Tab Manager to supply its own Add to Page function to let you connect your tab to a specific Facebook page.

The new Add to Page Links screen for admins lets you generate an Add to Page link for reveal tabs created with the Reveal Tab Setup utility, or tabs based on existing WordPress pages or posts using the ?fb=tab query string at the end of a URL.

= 2.9.6 =

Important update to keep pace with changes in the Facebook platform. Now, after recording the App ID # assigned by Facebook, you can record this in Facebook Tab Manager, and an "Add to Page" link will be displayed in the editor to allow you to add your tab to one or more pages. The previous installation method must be phased out because of Facebook's decision to eliminate application profile pages, which is where the Add to Page link used to be displayed.

= 2.9.5 =

Fix for sites that don't have pretty permalinks enabled.

= 2.9.4 =

2 new checkboxes.

* "Remove all but essential filters on post content" on Facebook Tab editor screen. Can be simpler than turning off filters individually.
* "Use redirect instead of AJAX / loading animation" on Reveal Tab Setup screen. The AJAX method is now the default. Works well for most content but may not work with some posts featuring inline JavaScript. Check here to use a redirect instead.

= 2.9.3 =

Misc. bugs

= 2.9.2 =

Renamed function that decodes Facebook signed request to prevent namespace conflicts with other plugins.

= 2.9.1 =

Fixes for alternate paths to the includes directory to make jQuery load properly for the preloader effect.

= 2.8.9 and 2.9 =

Bug fixes and tweaks. Reveal Tab function now uses JavaScript preloader by default. You can change it to a redirect by adding &redirect=1 to the end of the query string.

= 2.8.8 =

Bug fix in that fancy new theme / template code.

= 2.8.7 =

* Now supporting themes and custom templates that can be developed independently of the plugin. Should introduce more flexibility for those who want to alter the default presentation. The theme bundled with the plugin will be used by default, but you can also copy the fbtab-theme folder to wp-content/themes and modify as desired. Documentation at http://bit.ly/oTYyMB
* Previous hack for supporting custom templates deprecated.

= 2.8.6 =

Bug fix. Error in the code for saving customization settings.

= 2.8.5 =

* CSS changes to prevent display of scroll bars, even when too-wide content such as a large image is included (width set on body, with overflow hidden)
* Changed the way custom CSS is loaded into template. Should perform better.
* Reveal Tab Setup utility now displays 10 most recent combinations of Fan / Non-fan pages and corresponding URLs.

= 2.8.4 =

Update to Reveal Tab Setup utility. Looks like Facebook has made some platform changes that were preventing this from working properly, and I've made my own changes to compensate. This version also supports using a form plugin such as Contact Form 7 in the fan-only version of the tab, which wasn't working right previously.

= 2.8.3 =

Updating template.php code for better handling of wp_head and wp_footer output with filters selectively deactivated.

= 2.8.2 =

Session key set for Liked pages so site will "remember" that setting even after following a link or form post. Important for scenarios where you want to embed a form inside an fblike shortcode.

= 2.8.1 =

Correcting error in Reveal Tab Setup screen

= 2.8 =

* Fixed the fbtab / fblike shortcodes so they can contain other shortcodes - for example, to output a form only to be displayed to new people or only to fans of the page. Function now recursively calls do_shortcode to check if other shortcodes are embedded in the content. Thanks to Jason Lane for pointing out the error.
* Tested with WordPress 3.2.1

= 2.7.4 =

Thanks to H.P. Logsdon for some fixing some bugs I missed.

= 2.7.3 =

Reveal Tab Setup screen added. Includes a utility for constructing a special url pointing to alternate post IDs for fans and non-fans. On-screen documentation explains tradeoffs between this, shortcode methods.

= 2.7.2 =

You can now use ?fb=tab&minfilters=1 as a query string parameters to display existing content in the fbtab template with most filters on the_content turned off.

= 2.7.1 =

Bug fix. Some checkbox parameters weren't being saved properly.

= 2.7 =

Added Documentation as a submenu on Facebook Tabs. Pulls in content from the plugin home page on carrcommunications.com

= 2.6.9 =

Bug fix for CSS display, particularly in IE9

= 2.6.7 =

Fixed bug in CSS code that prevented custom styles from loading properly.

= 2.6.6 =

* Better instructions for secure canvas / secure tab setup.
* Clarified the UI for selectively deactivating filters on the body of a post, which may be appropriate for a blog page but not for Facebook.
* Fixed a bug in the function for deactivating filters.

= 2.6.5 =

Tweaked the template to reference https addresses for the the stylesheet and Facebook JavaScript library when https version of content is viewed. This is to prevent the Internet Explorer mixed content warning when Facebook users have https browsing turned on. This function will also attempt to correct src image and script references in the body of the post to https (only for local files within your domain).

= 2.6.2 =

* Added `[fblike]` shortcode as an alias to `[fbtab decode="1"]`. Recommended for situations where you are wrapping a block of text and image content inside a shortcode that indicates whether it should only be shown to people who have not yet liked, or who have liked your page. Example `[fblike like="0"]IMAGE TO SHOW TO NEW VISITORS[/fblike] [fblike like="1"]FAN-ONLY CONTENT[/fblike]`
* To display an existing page in the fbtab template, you can add the query string ?fb=tab to the end of the page url. You can also use resize=1 to invoke the resizing JavaScript. Example: http://www.carrcommunications.com/contact/davids-resume/?fb=tab&resize=1
* Users who don't like my template can now specify their own should be used instead. Only recommended for advanced users with their own plugin/template development skills.
* Better conformance with WordPress coding standards.

= 2.6.1 =

Changed custom post type setup in response to a report of a conflict with a theme that also uses custom post types.

= 2.6 =

* Several changes to keep up with changes in the structure of the Facebook app/tab registration form.
* Misc performance tweaks.

= 2.5 =

New input box: More code to add to head (Scripts, External Styles) - may be a better way of adding a single JavaScript or CSS include, as opposed to bringing in everything associated with wp_head. Template order is now wp_head output, CSS (default and added through interface), then additional head code.

= 2.4 =

Fix to options screen

= 2.3 =

Additional shortcode options

* Add like="1" to the fbtab shortcode if content should only be displayed to people who have liked the page
* Add like="0" to the fbtab shortcode if content should only be displayed to people who have NOT liked the page
* You can add an explanation in either of these two cases such as message="You must like this page before this super-special content will be displayed."

= 2.2 =

Better handling / explanation for checkboxes to turn off filters and actions that aren't appropriate for a Facebook tab.

= 2.1 =

Bug fix

= 2.0 =

Fixing template.php display for canvas pages

= 1.9 =

Correcting tab/canvas setup code.

= 1.8 =

* Added checkbox option to set resize / auto resize for tabs taller than 800 pixels
* You can now create both a tab and a canvas page for your application.

= 1.7 =

* Added options page for setting defaults, such as filters to ignore and CSS to apply
* Clarified documentation for how to fill out the Facebook Developers form
* Flush Rewrite Rules set to run every time on initialization (not supposed to be necessary, according to the documentation, but seems to work better on some configurations)

= 1.6 =

Refinements to shortcode function

= 1.5 =

Added `[fbtab]` Shortcode documented on the Installation section of this document. Makes it easier to integrate blog content and JavaScript widgets such as Facebook plugins.

= 1.4 =

* Added the ability to deactivate content filters when the fbtab template is displayed. This lets you eliminate plugin content modifications that are not appropriate for your Facebook tab.
* Updated the instructions for configuration on Facebook.

= 1.1, 1.2, 1.3 =

Fixes to default styles handling, directory locations

= 1.0 =

First public release February 2011

== Upgrade Notice ==

3.3.1 Fixes issues with tabs not resizing correctly. JavaScript needed to be updated to match latest Facebook libraries. 3.3.5 makes a related adjustment to the reveal tab utility.