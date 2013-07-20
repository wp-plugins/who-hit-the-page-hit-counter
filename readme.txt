=== Who Hit The Page - Hit Counter ===
Contributors: mahlamusa
Donate link: http://example.com/
Tags: hit counter, visit counter, visitor stats, ip statistics, statistics, ip counter, browser detector
Requires at least: 2.0.2
Tested up to: 3.5.1
Stable tag: 1.3.2
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

= How do I discount myself as a visitor = 

Go to the counters page on your admin and click the "-1" button corresponding to the page(s) you visited, if you have visited one page morethan once, then keep pressing the "-1" button until you are satisfied that page is discounted exactly by the number of your own visits on that page

= How do I Deny my own computer's IP address =

If you know the IP address of your own devices like Home/Work Computer, you can go to "Who Hit The Page" then "Denied IPs" and 'Enter IP address to add to deny list' and click on 'Add To Deny List' to continuously disallow the plugin to count visits from that IP address. But Make sure this is a static IP address - meaning it doesn't change over time, otherwise you will have to keep updating your denied IP list.

== Screenshots ==

1. Screenshot-1.jpg - Shows the plugin's main menu link; the highlighted/selected menu button is what you will click on to view your website's statisitics, and there is also a link labeled "Denied IPs" this is the page for creating an IP deny list so you can restrict some IPs from being counted when visiting your website.

2. Screenshot-1.jpg - Shows what appears on the plugin's first use, when there are still no visitors yet, or the pages to be counted are not yet specified by the use of the shortcode

3. Screenshot-3.jpg - Shows pages that have been visited along with the number of visits for each page, thats the page visited and the number of hits for that page and also action buttons for resestting, deleting, or discount page visits

4. Screenshot-4.jpg - Shows visitor's information: the ip address, total visits for that Ip address, browser used, time of first and last visit and action buttons to "deny", "delete" or "reset" count ip count. Only unique IP addresses are shown

5. Screenshot-5.jpg - Shows the IP deny table and a form to add a new IP address to the deny list. IPs in this list will not be counted when visiting your website

6. Screenshot-6.jpg - Shows information and support links and an update subsrciption form

== Changelog ==

= 1.3.2 =

* Bug fix
* Attempt to recover page hits and hit info from the installation of version 1.0 - data not truly lost from upgrade

= 1.3.1 =

* Fixed the counter bug - counters must work now

= 1.3 =

* Major Feature Update
New Feature added
- IP deny List, you can now deny an IP address from being counted
- Discount , If you visit your own site and you are counted, you can now discount yourself by clicking on "-1" next to page identifier you visited.
- Separae page for denied IPs

= 1.2 =

* Bug Fixes
* New Features
	- Reset Individual/All Page Counters
    - Reset Individual/All IP total counts
    - Permanently delete page/ip counter information from database
* New Design/Layout

= 1.1 =

Feature update 
* Now the plugin will log the time of first and last visit per IP address
* The total hits per IP address will now be counted
* If the visitor uses different browsers or user agents, the last one used will be shown

= 1.1 =

* Bug Fixes
* Register last used user agent or browser for each IP
* Register time of last visit

= 1.0 =

* No current changes to the plugin, v1.0 is the first release.

== Upgrade Notice ==

= Upgrade to the latest version to enjoy the new features requested by our users and maybe yours too =

* Major Feature Update
	- IP deny List, you can now deny an IP address from being counted - Separate page for denied IPs
	- Discount , If you visit your own site and you are counted, you can now discount yourself by clicking on "-1" next to page identifier you visited.	
    - Reset Individual/All Page Counters
    - Reset Individual/All IP total counts
    - Permanently delete page/ip counter information from database
    - Now the plugin will log the time of first and last visit per IP address
    - The total hits per IP address will now be counted
    - If the visitor uses different browsers or user agents, the last one used will be shown
    - Register last used user agent or browser for each IP
	- Register time of last visit
* Bug Fixes
* New Design/Layout

== Arbitrary section ==

The hit counter does not have a visible display on your website, but instead counts the visitors and register the user information on your wordpress database.
You will be able to see the stats on your wordpress admin, just click on the `Who Hit The Page` link on the side menu of your wordpress admin page. 

* Place the following shortcode snippet in any page or post you wish visitors counted on. <code>[whohit]-Page name or identifier-[/whohit]</code> 
- please remember to replace the `<code>-Page name or identifier-</code>` with the name of the page you placed the shortcode on, 
if you like you can put in anything you want to use as an identifier for the page.

* For example: On our [About Us Page](http://3pxwebstudios.co.nf/about-us/ "") we placed <code>[whohit]About Us[/whohit]</code>
and on our [web hosting](http://3pxwebstudios.co.nf/web-hosting/ "") page we placed <code>[whohit]Web Hosting[/whohit]</code>. 

* Please note that what you put between [whohit] and [/whohit] doesn\'t need to be the same as the page name - that means
for our [website design and development page](http://3pxwebstudios.co.nf/web-design-and-development/ "") we can use
 <code>[whohit]Development[whohit]</code> instead of the whole <code>[whohit]website design and development page[whohit]</code> string, 
 its completely up to you what you put as long as you will be able to see it on your admin what page has how many visits.


== Links You may need to visit ==

Here's a link to [3px Web Studios](http://3pxwebstudios.co.nf/ ""),to [WordPress Hit Counter's documentation](3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter/ "")

