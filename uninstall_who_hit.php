<?php

/*
* Deletes the tables created during plugin activation
* All data will be lost
* tables deleted: `hits` & `hitinfo`
*/

// delete the hits information table
$drop 		= 'DROP TABLE IF EXISTS hitinfo';
$drop_now 	= mysql_query($drop);

if ( !$drop_now ){
	echo 'Failed to drop the hitinfo table' . mysql_error();
}

//delete the hists table
$drop2 		= 'DROP TABLE IF EXISTS hits';
$drop_now2 	= mysql_query($drop2);

if ( !$drop_now2 ){
	echo 'Failed to drop the hits table' . mysql_error();
}
?>