<?php

/*
*
* Creates the tables required by the plugin on the wordpress site's database
* table `whtp_hits` stores the page names and visit counts for the pages
* table `whtp_hitinfo` stores the visitor's IP address, browser type and of first visit
*
*/
$installed = get_option('whtp_installed');
if (!$installed){
	add_option('whtp_installed','true','','yes');
	upgrade_database();
}


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
whtp_make_backup_dir ();


/*
* check if table hits exists then export
* this is for the old V1.0, essential to rename the tables
* alternatively, manually rename table `hits` to `whtp_hits`
*/
if ( whtp_table_exists("hits") ){
	if ( whtp_table_exists( "whtp_hits" ) ) {
		whtp_export_hits();
	}
}
/*
* check if table hitinfo exists then export
* this is for the old V1.0, essential to rename the tables
* alternatively, manually rename table `hitinfo` to `whtp_hitinfo`
*/
if ( whtp_table_exists( "hitinfo" ) ) { 
	if ( whtp_table_exists( "whtp_hitinfo" ) ){
		whtp_export_hitinfo();
	}
}

/*
* Create table `whtp_hits` if not exists
* Create table `whtp_hitinfo` if not exists
*/


function upgrade_database(){	
	mysql_query("ALTER TABLE `whtp_hits` DROP `page`");
	$alterhits = mysql_query("ALTER TABLE `whtp_hits` ADD `page_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
	$alter_hits = mysql_query("ALTER TABLE `whtp_hits` ADD `page` VARCHAR( 100 ) NOT NULL AFTER `page_id`");	
	
	create_whtp_hits_table();
	create_whtp_hitinfo_table();
	create_whtp_user_agents();
	create_ip_2_location_country();
	create_whtp_ip_hits_table();
	create_whtp_visiting_countries();
}

/*
* Update old user agents into browser names
*/
function update_old_user_agents(){
	$result = mysql_query( "SELECT * FROM whtp_hitinfo" );
	if ( $result ){
		while ( $row = mysql_fetch_array( $result ) ) {
			$ua = getBrowser ( $row['user_agent'] );
			$browser = $ua['name'];
			$ip = $row['ip_address'];
			if ( $row['user_agent'] != $browser ){ 
				$update_browser = mysql_query( "UPDATE whtp_hitinfo SET user_agent='$browser' WHERE ip_address='$ip'" );
			}
		}
	}	
}
# create hits table
function create_whtp_hits_table(){
	$create_hits_tbl = mysql_query("CREATE TABLE IF NOT EXISTS `whtp_hits` (
  `page_id` int(10) NOT NULL AUTO_INCREMENT,
  `page` varchar(100) NOT NULL,
  `count` int(15) DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5");
	if (!$create_hits_tbl)
	{
		//die("Could create table Information table.");
	}
}
# create hitinfo table
function create_whtp_hitinfo_table(){
	$create_info_tbl = mysql_query("CREATE TABLE IF NOT EXISTS `whtp_hitinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(30) DEFAULT NULL,
  `ip_status` varchar(10) NOT NULL DEFAULT 'active',
  `ip_total_visits` int(15) DEFAULT '0',
  `user_agent` varchar(50) DEFAULT NULL,
  `datetime_first_visit` varchar(25) DEFAULT NULL,
  `datetime_last_visit` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7");
	if ( !$create_info_tbl ){	
		//die something
	}
}

function create_whtp_visiting_countries(){
	$create = mysql_query ( "CREATE TABLE IF NOT EXISTS `whtp_visiting_countries` (
  `country_code` char(2) NOT NULL,
  `count` int(11) NOT NULL,
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"	);
	
}


function create_whtp_ip_hits_table(){
	$create = mysql_query("CREATE TABLE IF NOT EXISTS `whtp_ip_hits` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip_id` int(11) NOT NULL,
  `page_id` int(10) NOT NULL,
  `datetime_first_visit` datetime NOT NULL,
  `datetime_last_visit` datetime NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");	
}
/*
* Create user agents table
*/
function create_whtp_user_agents(){
	$sql = '
	CREATE TABLE IF NOT EXISTS `whtp_user_agents` (
  `agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(20) NOT NULL,
  `agent_details` text NOT NULL,
  PRIMARY KEY (`agent_id`),
  UNIQUE KEY `agent_name` (`agent_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60';

	$create = mysql_query( $sql );
}

function create_ip_2_location_country(){
	$create_query = 'CREATE TABLE IF NOT EXISTS `whtp_ip2location` (
  `ip_from` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `ip_to` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `decimal_ip_from` int(11) NOT NULL,
  `decimal_ip_to` int(11) NOT NULL,
  `country_code` char(2) COLLATE utf8_bin DEFAULT NULL,
  `country_name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  KEY `idx_ip_from` (`ip_from`),
  KEY `idx_ip_to` (`ip_to`),
  KEY `idx_ip_from_to` (`ip_from`,`ip_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin';
	
	$create = mysql_query( $create_query );
	return $create;

}

/*
* Functions to export the old `hits` and `hitinfo` tables to the new `whtp_hits` and `whtp_hitinfo` tables
* First run the function `whtp_table_exists()` to check if the table exists, then
* Start the export if both the source and destination tables exists
* If the destinatio table doesn't exist, create it and run the export again
*/

# check if a table exists in the database
function whtp_table_exists ( $tablename ){
	$dbname  = DB_NAME; # wordpress database name
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
	$select = mysql_query("SELECT * FROM `hits`");
	if ( $select ){
		$message = "";
		$exported = false;
		while ( $row = mysql_fetch_assoc( $select ) ){			
			$insert = mysql_query("INSERT into `whtp_hits`(`page`,`count`) VALUES ('" . $row['page']. "','" .$row['count']. "')");
			if( !$insert  ){
				$exported = false;
			}else{
				$exported = true;
			}
		}
		if ($exported == true) {
			mysql_query("DROP TABLE IF EXISTS `hits`");
		}
	}
}

#export hitinfo data to whtp_hitinfo table
function whtp_export_hitinfo(){
	$select = mysql_query("SELECT * FROM hitinfo");
	if( $select ){
		$message = "";
		$exported=false;
		while ( $row = mysql_fetch_assoc ( $select ) ){	
			$sql = "INSERT INTO `whtp_hitinfo` (`ip_address`, `ip_status`, `user_agent`, `datetime_first_visit`, `datetime_last_visit`) 
			VALUES ('" .$row['ip_address'] ."', 'active', '" .$row['user_agent']."', '" .$row['datetime']."', '" .$row['datetime'] ."')";		
			$insert = mysql_query ( $sql );
			if ( !$insert ){
				$exported = false;
			}
			else{
				$exported = true;
			}
		}
		if ($exported == true) {
			mysql_query("DROP TABLE IF EXISTS `hitinfo`");
		}
	}
}
?>