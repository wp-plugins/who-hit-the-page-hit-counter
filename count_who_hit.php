<?php

/*
* Hit counter function
* call the function on the page you want the counter to work on
* Pass a page name to the function, this name will be used to identify the page amongst others
* so you will know which page has how many hits
*/

function who_hit_the_page( $page ) {
	
	/*
	* Check if the page has been visited then
	* update or create new counter
	*/

	if(mysql_num_rows(mysql_query("SELECT page FROM hits WHERE page = '$page'")) > 0) {
		$update_counter = mysql_query(	"UPDATE hits SET count = count+1 WHERE page = '$page'"	);
	}
	else {
		$insert_new_count = mysql_query("INSERT INTO hits (page, count)VALUES ('$page', '1')");
	}
	
	/*
	* start gathering unique user data
	* and update the table
	*/

	$ip_address			= $_SERVER["REMOTE_ADDR"];
	$user_agent 		= $_SERVER["HTTP_USER_AGENT"];
	$date_time 			= date("Y/m/d") . ' ' . date('H:i:s');
	
	/*
	* first check if the IP is in database
	* if the ip is not in the database, add it in
	*/
	$query  =  mysql_query( "SELECT ip_address FROM hitinfo WHERE ip_address = '$ip_address'" );
	if ( $query ){		
		if( !mysql_num_rows( $query )) {
			$insert_hit_info = mysql_query("INSERT INTO hitinfo (ip_address, user_agent, datetime) VALUES('$ip_address' , '$user_agent','$date_time' ) ");
		}
	}
}
?>