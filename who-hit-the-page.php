<?php
/*
Plugin Name: Who Hit The Page - Hit Counter
Plugin URI: http://whohit.co.za/who-hit-the-page-hit-counter
Description: Lets you know who visted your pages by adding an invisible page hit counter on your website, so you know how many times a page has been visited in total and how many times each user identified by IP address has visited each page. You will also know the IP addresses of your visitors and relate the IP addresses to the country of the visitor and all browsers used by that IP/user.
Version: 1.4.1
Author: mahlamusa
Author URI: http://lindeni.co.za
Plugin URI: http://whohit.co.za
License: GPL
*/
/*
 * Copyright © 2012 3px Web Studios. All rights reserved.
 * <mahlamusa@gmail.com> 
 * <3pxwebstudios@gmail.com>
 * <www.3pxwebstudios.co.nf>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */
 
include('count_who_hit.php');
include('who_hit_processor.php');
include('who_hit_functions.php');

if ( version_compare( $wp_version, '3.8', '<' ) ){
	add_action( 'admin_head', 'whtp_compatibility_css' );
}

register_activation_hook(__FILE__,'whtp_installer');
register_deactivation_hook(__FILE__,'whtp_remove');



function whtp_installer(){
	require_once('install_who_hit.php');
}
function whtp_remove(){
	require_once('uninstall_who_hit.php');
}
/*
* If current user is administrator, then add the menus and actions
*
*/
if (is_admin()){
	function whtp_admin_menu(){		
		$icon_uri = plugins_url("images/icon.png");
		add_object_page( 'Who Hit The Page', 'Who Hit The Page', 'administrator', 'whtp-admin-menu','whtp_object_page_callback');	
		add_submenu_page('whtp-admin-menu','View All Details','View All Details','administrator','whtp-view-all','whtp_view_all_callback');
		add_submenu_page('whtp-admin-menu','Visitor Stats','Visitor Stats','administrator','whtp-visitor-stats','whtp_visitors_stats_callback');
		add_submenu_page('whtp-admin-menu','Denied IPs','Denied IPs','administrator','whtp-denied-ips','whtp_denied_submenu_callback');
		add_submenu_page('whtp-admin-menu','Export / Import','Export / Import','administrator','whtp-import-export','whtp_export_import_submenu_callback');
		add_submenu_page('whtp-admin-menu','Settings','Settings','administrator','whtp-settings','whtp_settings_submenu_callback');
		add_submenu_page('whtp-admin-menu','Help','Help','administrator','whtp-help','whtp_help_submenu_callback');
		//add_management_page('Who Hit The Page', 'Who Hit The Page', 'administrator', 'whtp-admin-menu','whtp_object_page_callback');
	}
	add_action('admin_menu','whtp_admin_menu');	
	/*
	* Submenu callback functions
	*/
	function whtp_object_page_callback(){
		include('who_hit_stats_summary.php');
	}
	function whtp_denied_submenu_callback(){
		include('who_hit_denied_ips.php'); //admin page
	}
	
	function whtp_visitors_stats_callback(){
		include('view_visitor_info.php'); //admin page
	}
	function whtp_view_all_callback(){
		 include('view_who_hit.php');//admin page
	}
	function whtp_export_import_submenu_callback(){
		 include('who_hit_export_import.php');//admin page
	}
	function whtp_settings_submenu_callback(){
		 include('who_hit_settings.php');//admin page
	}
	function whtp_help_submenu_callback(){
		 include('who_hit_help.php');//admin page
	}
}
/*
* Hit counter short code
* add [whohit]Page name or title[/whohit] to the page you want visitors counted
*/
function who_hit_the_page_short_code( $atts=null, $content=null ){
	extract(shortcode_atts(array('id'=>''),$atts));
	if ( $content != ""){
		$page = $content;
	}
	who_hit_the_page( $page );
}
add_shortcode('whohit','who_hit_the_page_short_code');

/*
Link to us
*/
function whtp_link_bank(){
	$link = '<a href="http://lindeni.co.za" rel="bookmark" title="Wordpress plugins and web design resources" target="_blank">Wordpress plugins by Mahlamusa Mahlalela</a>';
	return $link;
}
add_shortcode('whlinkback','whtp_link_bank');
/*
* admin Menus
*/
function whtp_admin_styles(){
	echo '<link rel="stylesheet" href="' . plugins_url ( 'style_who_hit.css', __FILE__ ) . '" />';
}
add_action( 'admin_head', 'whtp_admin_styles' );

function whtp_compatibility_css(){
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/colors-rtl.min.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/colors.min.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/common-rtl.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/common.css', __FILE__ ).'" />';
	echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/dashboard.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/dashboard-rtl.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/ie-rtl.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/ie.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/ie.min.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/wp-admin-rtl.min.css', __FILE__ ).'" />';
	//echo '<link rel="stylesheet" href="'.plugins_url( 'wp-admin-css/wp-admin.min.css', __FILE__ ).'" />';
}
?>