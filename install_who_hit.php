<?php

/*
*
* Creates the tables required by the plugin on the wordpress site's database
* table `hits` stores the page names and visit counts for the pages
* table `hitinfo` stores the visitor's IP address, browser type and of first visit
*
*/

$info_tbl 	= 'hitinfo';
$hit_tbl 	=	'hits';

$create_info_tbl = mysql_query("CREATE TABLE IF NOT EXISTS $info_tbl(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), ip_address VARCHAR(30), user_agent VARCHAR(50), datetime VARCHAR(25))");
if ( !$create_info_tbl )
{
    die("Could create table $info_tbl :" . mysql_error());
}

$create2 = mysql_query("CREATE TABLE IF NOT EXISTS $hit_tbl(page char(100),PRIMARY KEY(page),count int(15))");
if (!$create2)
{
    die("Could create table $hit_tbl :" . mysql_error());
}

?>