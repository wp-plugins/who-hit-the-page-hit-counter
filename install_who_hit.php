<?php

/*
*
* Creates the tables required by the plugin on the wordpress site's database
* table `hits` stores the page names and visit counts for the pages
* table `hitinfo` stores the visitor's IP address, browser type and of first visit
*
*/
$installed = get_option('whtp_installed');

if($installed == true && $installed!=""){
	$alter = mysql_query ( "ALTER TABLE whtp_hitinfo ADD ip_status VARCHAR( 10 ) NOT NULL DEFAULT 'active' AFTER id" );
	if(!$alter){
		if(add_option('whtp_installed','true','','yes')){
			
		}
	}
}
else{
	add_option('whtp_installed','true','','yes');
	$create_hits_tbl = mysql_query("CREATE TABLE IF NOT EXISTS whtp_hits(page char(100),PRIMARY KEY(page),count int(15))");
	if (!$create_hits_tbl)
	{
		die("Could create table Information table.");
	}
	$create_info_tbl = mysql_query("CREATE TABLE IF NOT EXISTS whtp_hitinfo(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), ip_address VARCHAR(30), ip_status VARCHAR( 10 ) NOT NULL DEFAULT 'active', ip_total_visits INT(15) DEFAULT 0, user_agent VARCHAR(50), datetime_first_visit VARCHAR(25), datetime_last_visit VARCHAR(25))");
	if ( !$create_info_tbl ){	
		
	}
}

?>