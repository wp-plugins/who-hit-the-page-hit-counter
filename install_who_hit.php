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
}

create_whtp_hits_table();
create_whtp_hitinfo_table();
	
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

# create hits table
function create_whtp_hits_table(){
	$create_hits_tbl = mysql_query("CREATE TABLE IF NOT EXISTS whtp_hits(page char(100) NOT NULL DEFAULT 'No Identifier',PRIMARY KEY(page),count int(15) DEFAULT 0)");
	if (!$create_hits_tbl)
	{
		//die("Could create table Information table.");
	}
}
# create hitinfo table
function create_whtp_hitinfo_table(){
	$create_info_tbl = mysql_query("CREATE TABLE IF NOT EXISTS whtp_hitinfo(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), ip_address VARCHAR(30), ip_status VARCHAR( 10 ) NOT NULL DEFAULT 'active', ip_total_visits INT(15) DEFAULT 0, user_agent VARCHAR(50), datetime_first_visit VARCHAR(25), datetime_last_visit VARCHAR(25))");
	if ( !$create_info_tbl ){	
		//die something
	}
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