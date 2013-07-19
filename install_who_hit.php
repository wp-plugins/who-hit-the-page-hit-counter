<?php

/*
*
* Creates the tables required by the plugin on the wordpress site's database
* table `hits` stores the page names and visit counts for the pages
* table `hitinfo` stores the visitor's IP address, browser type and of first visit
*
*/

$create_hits_tbl = mysql_query("CREATE TABLE IF NOT EXISTS whtp_hits(page char(100),PRIMARY KEY(page),count int(15))");
if (!$create_hits_tbl)
{
    die("Could create table $hit_tbl :" . mysql_error());
}

$create_info_tbl = mysql_query("CREATE TABLE IF NOT EXISTS whtp_hitinfo(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), ip_address VARCHAR(30), ip_total_visits INT(15) DEFAULT 0, user_agent VARCHAR(50), datetime_first_visit VARCHAR(25), datetime_last_visit VARCHAR(25))");
if ( !$create_info_tbl )
{
    die("Could create table $info_tbl :" . mysql_error());
}

?>