<?php

$installed = get_option('whtp_installed');
if (!$installed){
	add_option('whtp_installed','true','','yes');
	add_option('whtp_vc_updated','false','','yes'); //visiting countries updated	
}
/*
* check if table hits exists then rename to the new name
* if can't rename, just create an new table and export the data to it
* this is for the old V1.0, essential to rename the tables
* alternatively, manually rename table `hits` to `whtp_hits`
*/
if ( whtp_table_exists("hits") ){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	if ( !whtp_rename_table("hits", "whtp_hits") ){
		if ( !whtp_table_exists( "whtp_hits" ) ) {
			create_whtp_hits_table();
		}
		whtp_export_hits();
	}	
}
/*
* check if table hitinfo exists then rename to the new name
* this is for the old V1.0, essential to rename the tables
* alternatively, manually rename table `hitinfo` to `whtp_hitinfo`
*/
if ( whtp_table_exists( "hitinfo" ) ) { 
	if ( !whtp_rename_table("hitinfo", "whtp_hitinfo") ){
		if ( !whtp_table_exists( "whtp_hitinfo" ) ){
			create_whtp_hitinfo_table();
		}
		whtp_export_hitinfo();
	}
}
/*
*
* Creates the tables required by the plugin on the wordpress site's database
* table `whtp_hits` stores the page names and visit counts for the pages
* table `whtp_hitinfo` stores the visitor's IP address, browser type and of first visit
*
*/
create_whtp_hits_table();
create_whtp_hitinfo_table();
create_whtp_visiting_countries();
create_whtp_user_agents();
create_ip_2_location_country();
create_whtp_ip_hits_table();

/*
* Upgrade the database from the old versions
* Also helps if the create functions failed;
*/
upgrade_database();
/*
* Update old user agents from earlier versions
* from the format; Mozilla/5.0 (Windows NT 6.0; rv:9.0.1) Gecko/20100
* to the format; Mozilla Firefox
*/
update_old_user_agents();

/*
* Update visiting countries
* If an IP is registered on the database but is not included / counted on the visiting_countries database
* count it or add 1 to the country where the IP belongs
*/
update_visiting_countries();
/*
* make directory to save backups
*/
//whtp_make_backup_dir ();





function whtp_rename_table($old_table_name, $new_table_name){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	if ( dbDelta( "ALTER TABLE `" .  $old_table_name . "` RENAME TO `" . $new_table_name ."`" ) ){
		return true;
	}
	else return false;
}
/*
* Create table `whtp_hits` if not exists
* Create table `whtp_hitinfo` if not exists
*/


function upgrade_database(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;
	$wpdb->hide_errors();
	
	create_whtp_hits_table();
	create_whtp_hitinfo_table();
	create_whtp_user_agents();
	create_ip_2_location_country();
	create_whtp_ip_hits_table();
	create_whtp_visiting_countries();
	
	//
	change_old_hits_table();
	
	//add_action('plugins_loaded', 'whtp_print_error');
}

/*
*
*
*/
function change_old_hits_table(){
	global $wpdb;
	$exists =  $wpdb->query("SHOW COLUMNS FROM `whtp_hits` LIKE 'page_id'");
	
	if ( !$exists ){
		$wpdb->query( "ALTER TABLE `whtp_hits` DROP PRIMARY KEY" );
		$wpdb->query( "ALTER TABLE `whtp_hits` ADD `page_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST" );	
		$wpdb->query( "ALTER TABLE `whtp_hits` MODIFY `page` VARCHAR( 100 ) NOT NULL" );
	}
}
/*
* Update old user agents into browser names
*/
function update_old_user_agents(){
	/**/global $wpdb;
		
	$user_agents = $wpdb->get_results( "SELECT ip_address, user_agent FROM whtp_hitinfo" );
	if ( count($user_agents) > 0 ){
		foreach ( $user_agents as $uagent ) {
			$ua = whtp_browser_info();
			$browser = $ua['name'];
			$ip = $uagent->ip_address;
			if ( $uagent->user_agent != $browser ){
				$update_browser = $wpdb->update(
					"whtp_hitinfo", array("user_agent"=>$browser),
					array("ip_address"=>$ip),
					array("%s","%s")
				);
			}
		}
	}
	/*add_action('plugins_loaded', 'whtp_print_error');*/
}
# create hits table
function create_whtp_hits_table(){
	/*global $wpdb;
	$wpdb->hide_errors();*/
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta("CREATE TABLE IF NOT EXISTS `whtp_hits` (
  `page_id` int(10) NOT NULL AUTO_INCREMENT,
  `page` varchar(100) NOT NULL,
  `count` int(15) DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5");
	//$wpdb->query( $sql );
}
# create hitinfo table
function create_whtp_hitinfo_table(){
	/*global $wpdb;
	$wpdb->hide_errors();*/
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta("CREATE TABLE IF NOT EXISTS `whtp_hitinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(30) DEFAULT NULL,
  `ip_status` varchar(10) NOT NULL DEFAULT 'active',
  `ip_total_visits` int(15) DEFAULT '0',
  `user_agent` varchar(50) DEFAULT NULL,
  `datetime_first_visit` varchar(25) DEFAULT NULL,
  `datetime_last_visit` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7");
	
	
	// run the sql query
	//$wpdb->query( $sql );
}

function create_whtp_visiting_countries(){
	/*global $wpdb;
	$wpdb->hide_errors();
	
	$wpdb->query( */
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta("CREATE TABLE IF NOT EXISTS `whtp_visiting_countries` (
  `country_code` char(2) NOT NULL,
  `count` int(11) NOT NULL,
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"  );

/*$wpdb->print_error();*/
}


function create_whtp_ip_hits_table(){
	/*global $wpdb;
	$wpdb->hide_errors();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$wpdb->query( */
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta("CREATE TABLE IF NOT EXISTS `whtp_ip_hits` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip_id` int(11) NOT NULL,
  `page_id` int(10) NOT NULL,
  `datetime_first_visit` datetime NOT NULL,
  `datetime_last_visit` datetime NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" );
}
/*
* Create user agents table
*/
function create_whtp_user_agents(){
	/*global $wpdb;
	$wpdb->hide_errors();
	
	$sql = */
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta("
	CREATE TABLE IF NOT EXISTS `whtp_user_agents` (
  `agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(20) NOT NULL,
  `agent_details` text NOT NULL,
  PRIMARY KEY (`agent_id`),
  UNIQUE KEY `agent_name` (`agent_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60");
		
	//$wpdb->query( $sql );
}

function create_ip_2_location_country(){
	/*global $wpdb;
	$wpdb->hide_errors();
	
	$sql = '*/
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta("CREATE TABLE IF NOT EXISTS `whtp_ip2location` (
  `ip_from` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `ip_to` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `decimal_ip_from` int(11) NOT NULL,
  `decimal_ip_to` int(11) NOT NULL,
  `country_code` char(2) COLLATE utf8_bin DEFAULT NULL,
  `country_name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  KEY `idx_ip_from` (`ip_from`),
  KEY `idx_ip_to` (`ip_to`),
  KEY `idx_ip_from_to` (`ip_from`,`ip_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	
	//$wpdb->query( $sql );
}

/*
* Functions to export the old `hits` and `hitinfo` tables to the new `whtp_hits` and `whtp_hitinfo` tables
* First run the function `whtp_table_exists()` to check if the table exists, then
* Start the export if both the source and destination tables exists
* If the destinatio table doesn't exist, create it and run the export again
*/

# check if a table exists in the database
function whtp_table_exists ( $tablename ){
	global $wpdb;
	
	if ( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") )
		$table_exists = true;
	else
		$table_exists = false;
		
		
	/**/$dbname  = DB_NAME; # wordpress database name
	$tables = mysql_query("SHOW TABLES FROM $dbname");
	while ($table = mysql_fetch_row( $tables ) ){
		$arr[] = $table[0];
	}

	if ( in_array( $tablename, $arr ) ){
		$table_exists = true;
	}
	else{
		$table_exists = false;
	}
	return $table_exists;
}

# export hits data to whtp_hits
function whtp_export_hits(){
	global $wpdb;
	$wpdb->hide_errors();
	
	$hits = $wpdb->get_results("SELECT * FROM `hits`, ARRAY_A");
	if ( count($hits ) > 0){
		$message = "";
		$exported = false;
		foreach( $hits as $hit ){
			$insert = $wpdb->insert("whtp_hits", array("page"=>$hit['page'],"count"=>$hit['count']), array("%s", "%d"));
			if( !$insert ){
				$exported = false;
			}else{
				$exported = true;
			}
		}		
	}
	if ($exported == true) {
		$wpdb->query( "DROP TABLE IF EXISTS `hits`" );
	}
}

#export hitinfo data to whtp_hitinfo table
function whtp_export_hitinfo(){
	global $wpdb;
	$wpdb->hide_errors();
	
	$hitsinfo = $wpdb->get_results("SELECT * FROM hitinfo");
	if( count($hitsinfo) > 0){
		$message 	= "";
		$exported	= false;
		foreach( $hitsinfo as $info ){	
			$insert = $wpdb->insert(
				"whtp_hitinfo", 
				array(
					"ip_address"=>$info->ip_address,
					"ip_status"=>'active',
					"user_agent"=>$info->user_agent,
					"datetime_first_visit"=>$info->datetime,
					"datetime_last_visit"=>$info->datetime
				), 
				array("%s","%s","%s","%s","%s") 
			);
			if ( !$insert ){
				$exported = false;
			}
			else{
				$exported = true;
			}
		}
	}
	
	if ($exported == true) {
		$wpdb->query("DROP TABLE IF EXISTS `hitinfo`");
	}
}
?>