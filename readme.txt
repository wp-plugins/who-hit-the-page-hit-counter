=== Who Hit The Page - Hit Counter ===
Contributors: mahlamusa
Donate link: http://example.com/
Tags: hit counter, visit counter, visitor stats, ip statistics, statistics, ip counter, browser detector
Requires at least: 2.0.2
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Count the number of visitors on your wordpress site, know their IP addresses and browser types.

== Description ==

Who Hit The Page - Hit Counter lets you know who hit your page by adding an invisible page hit counter on your website, so you know how many times a page has been visited and also know the IP addresses of your visitors and the number of visits per IP adrdress along with the time of first and last visit. This plugin will also register the visitor's browser type

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the folder/directory named `who-hit-the-page` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. 
 * Place [whohit]Page name/identifier[/whohit] on the page or post you want visitors counted - e.g. place `[whohit]About Us[/whohit]`
 on your `About Us` page to see how many people visited that page.
 * Place `<?php who_hit_the_page( "Page Name" ); ?>` on your theme if you are a developer.
 * Optional: link to us by placing [whlinkback] in a wordpress page or post, or `<?php whtp_link_bank(); ?>` on your template files
4. Visit one page you placed the shortcode once and go to your wordpress admin and click on 'Who Hit The Page' on the left to see your new statistics.

See the Arbitrary Information for more details

== Frequently Asked Questions ==

= Where do I see the visitors' statistics after installing the plugin? =

On your wordpress admin - Go to your admin and look for 'Who Hit The Page' on the main menu on the left, click on it you will see - also see Screenshot-1.

= Do my website visitors see the page hits? =

No! Who Hit The Page -  Hit Counter is an invisible hit counter, it doesn't show visitors that they are counted, instead it helps you know about your visitors by registering their information so you will know where they are and what browser they use to view your website.

== Screenshots ==

1. Screenshot-1.jpg - Shows the plugin's main menu link; the highlighted/selected menu button is what you will click on to view your website's statisitics
2. Screenshot-1.jpg - Shows what appears on the plugin's first use, when there are still no visitors yet, or the pages to be counted are not yet specified by the use of the shortcode
3. Screenshot-3.jpg - Shows pages that have been visited along with the number of visits for each page, thats the page visited and the number of hits for that page
4. Screenshot-4.jpg - Shows visitor's information: the ip address, total visits for that Ip address, browser used, time of first and last visit. Only unique IP addresses are shown
5. Screenshot-5.jpg - Shows information and support links and an update subsrciption form

== Changelog ==
= 1.1 =

Feature update 
* Now the plugin will log the time of first and last visit per IP address
* The total hits per IP address will now be counted
* If the visitor uses different browsers or user agents, the last one used will be shown

= 1.0 =
* No current changes to the plugin, v1.0 is the first release.

== Upgrade Notice ==

= No upgrade necessary. =

== Arbitrary section ==

The hit counter does not have a visible display on your website, but instead counts the visitors and register the user information on your wordpress database.
You will be able to see the stats on your wordpress admin, just click on the `Who Hit The Page` link on the side menu of your wordpress admin page. 

* Place the following shortcode snippet in any page or post you wish visitors counted on. <code>[whohit]-Page name or identifier-[/whohit]</code> 
- please remember to replace the `<code>-Page name or identifier-</code>` with the name of the page you placed the shortcode on, 
if you like you can put in anything you want to use as an identifier for the page.

* For example: On our <a href="http://3pxwebstudios.co.nf/about-us">about us page</a> we placed <code>[whohit]About Us[/whohit]</code>
and on our <a href="http://3pxwebstudios.co.nf/web-hosting">web hosting</a> page we placed <code>[whohit]Web Hosting[/whohit]</code>. 

* Please note that what you put between [whohit] and [/whohit] doesn\'t need to be the same as the page name - that means
for our <a href="http://3pxwebstudios.co.nf/web-design-and-development">website design and development page</a> we can use
 <code>[whohit]Development[whohit]</code> instead of the whole <code>[whohit]website design and development page[whohit]</code> string, 
 its completely up to you what you put as long as you will be able to see it on your admin what page has how many visits.


== A brief Markdown Example ==

Here's a link to [3px Web Studios](http://3pxwebstudios.co.nf/ ""),to [WordPress Hit Counter's documentation](3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter/ "")

