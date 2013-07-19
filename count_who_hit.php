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

	if(mysql_num_rows(mysql_query("SELECT page FROM whtp_hits WHERE page = '$page'")) > 0) {
		$update_counter = mysql_query(	"UPDATE whtp_hits SET count = count+1 WHERE page = '$page'"	);
	}
	else {
		$insert_new_count = mysql_query("INSERT INTO whtp_hits (page, count)VALUES ('$page', '1')");
	}
	
	/*
	* start gathering unique user data
	* and update the table
	*/

	$ip_address			= $_SERVER["REMOTE_ADDR"];
	$user_agent 		= $_SERVER["HTTP_USER_AGENT"];
	$date_ftime 		= date("Y/m/d") . ' ' . date('H:i:s');
	$date_ltime			= date("Y/m/d") . ' ' . date('H:i:s');
	
	/*
	* first check if the IP is in database
	* if the ip is not in the database, add it in
	* otherwise update
	*/
	$query  =  mysql_query( "SELECT ip_address FROM whtp_hitinfo WHERE ip_address = '$ip_address'" );
	if ( $query ){	
		if( !mysql_num_rows( $query ) ) {
			$insert_hit_info = mysql_query("INSERT INTO whtp_hitinfo (ip_address, ip_total_visits, user_agent, datetime_first_visit, datetime_last_visit) VALUES('$ip_address' , 0, '$user_agent','$date_ftime','$date_ltime' )");
		}
		else{
			$update_ip_visit = mysql_query( "UPDATE whtp_hitinfo SET ip_total_visits = ip_total_visits+1, user_agent='$user_agent', datetime_last_visit = '$date_ltime' WHERE ip_address='$ip_address'" );
		}
	}
	/*
	* Future functionality
	*
	* User IP address and all browsers used
	* count Total Hits per IP
	* Then divide IP's Total Hits to the number of browsers associated to that IP
	*/
}
?>