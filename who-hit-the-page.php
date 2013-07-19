<?php
/*
Plugin Name: Who Hit The Page - Hit Counter
Plugin URI: http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter
Description: Who Hit The Page - Hit Counter lets you know who hit your page by adding an invisible page hit counter on your website, so you know how many times a page has been visited and also know the IP addresses of your visitors. This plugin will also register the visitor's browser type. Place <code>[whohit]-Page name or identifier-[/whohit]</code> on any page to count visitors of that page.
Version: 1.2
Author: mahlamusa
Author URI: http://3pxwebstudios.co.nf
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
 
 
register_activation_hook(__FILE__,'whtp_installer');
register_deactivation_hook(__FILE__,'whtp_remove');

include('count_who_hit.php');

function whtp_installer(){
	require_once('install_who_hit.php');
}
function whtp_remove(){
	require_once('uninstall_who_hit.php');
}


/*
*
*
*/
if (is_admin()){
	function whtp_admin_menu(){		
		$icon_uri = plugins_url("images/icon.png");
		add_object_page( 'Who Hit The Page', 'Who Hit The Page', 'administrator', 'whtp-admin-menu','whtp_object_page_callback');		
		//add_management_page('Who Hit The Page', 'Who Hit The Page', 'administrator', 'whtp-admin-menu','whtp_object_page_callback');
	}
	add_action('admin_menu','whtp_admin_menu');	
	/*
	* Submenu callback functions
	*/	
	function whtp_object_page_callback(){
		include('view_who_hit.php');
	}	
}
/*
* Hit counter short code
* add [wphitcounter]Page name or title[/wphitcounter] to the page you want visitors counted
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
	$link = '<a href="http://3pxwebstudios.co.nf" rel="bookmark" title="Wordpress plugins and web design resources" target="_blank">Wordpress plugins by 3px Web Studios</a>';
	return $link;
}
add_shortcode('whlinkback','whtp_link_bank');
?>