<?php

/*
* Hit counter function
* call the function on the page you want the counter to work on
* Pass a page name to the function, this name will be used to identify the page amongst others
* so you will know which page has how many hits
*/

function who_hit_the_page( $page ) {
	
	$page = $page;
	$ip_address	= $_SERVER["REMOTE_ADDR"];	# visitor's ip address 
	
	#count if the IP is not denied
	if ( !ip_is_denied ( $ip_address ) ) {
		# try detecting wordpress host, then deny it
		if ( !deny_wordpress_host_ip() ){
			whtp_count_hits( $page );
			whtp_hit_info();
		}
	}
	/*
	* Future functionality
	*
	* Collapse or expand all IP details
	* Geolocate IP address
	* User IP address and all browsers used
	*
	*
	* Divide IP's Total Hits to the number of browsers associated to that IP	
	*/
}

/*
* Check if the page has been visited then
* update or create new counter
*/
function whtp_count_hits( $page ){	
	if(mysql_num_rows(mysql_query("SELECT page FROM whtp_hits WHERE page = '$page'")) > 0) {
		$update_counter = mysql_query(	"UPDATE whtp_hits SET count = count+1 WHERE page = '$page'"	);
	}
	else {
		$insert_new_count = mysql_query("INSERT INTO whtp_hits (page, count)VALUES ('$page', '1')");
	}
}

function whtp_reset_page_count( $page = ""){
	
	if ( $page == ""){
		$page = stripslashes( $_POST['reset_page'] );
	}
	if( $page != "all" ){
		#don't reset all but specific page
		$update_page = mysql_query("UPDATE whtp_hits SET count = 0 WHERE page = '$page'");
		if ( $update_page ){
			echo '<div class="success-msg">The count for the page "' .$page . '" has been reset successfully.</div>'; 
		}else{
			echo '<div class="error-msg">Failed to reset count for page "'. $page . '"' .': ' . mysql_error() . '</div>';
		}
	}else{
		#reset all
		$update_all = mysql_query("UPDATE whtp_hits SET count = 0 ");
		if ( $update_all ) {
			whtp_reset_ip_info("all");
			echo '<div class="success-msg">The count for "All" pages has been reset successfully.</div>';
		}
		else{
			echo '<div class="error-msg">Failed to reset count for "All" pages' . mysql_error() . '</div>';
		}
	}
	
	$page = "";
}

# reset ip counters
function whtp_reset_ip_info( $reset = ""){	

	$ip_address = $_POST['ip_address'];	
	if ($reset == ""){
		$reset_ip = stripslashes( $_POST['reset_ip'] );
	}else{
		$reset_ip = $reset;
	}
	if ($reset_ip != "all" ){
		# reset specific
		$reset_ip_visit = mysql_query( "UPDATE whtp_hitinfo SET ip_total_visits = 0 WHERE ip_address='$ip_address'" );
		if ( $reset_ip_visit ) {
			echo '<div class="success-msg">The count for ip address: ' . $ip_address . ' has been reset successfully.</div>';
		}
		else{
			echo '<div class="error-msg">Failed to reset count for IP: ' . $ip_address  . mysql_error() . '</div>';
		}
	}
	elseif($reset_ip == "all" ){
		# reset all ip counters
		$reset_all = mysql_query( "UPDATE whtp_hitinfo SET ip_total_visits = 0" );
		if ( $reset_all ) {
			echo '<div class="success-msg">The count for "All" IPs has been reset successfully.</div>';
		}
		else{
			echo '<div class="error-msg">Failed to reset count for "All" IPs' . mysql_error() . '</div>';
		}
	}
	$_POST['ip_address'] = "";
}

# delete ip address
function whtp_delete_ip (){
	
	$ip_address = stripslashes($_POST['delete_this_ip']);	
	$delete_ip = stripslashes ( $_POST['delete_ip'] );
	
	if ( $delete_ip == "this_ip" ) {
		$del = mysql_query ("DELETE FROM whtp_hitinfo WHERE ip_address='$ip_address'");
		if ( $del ){
			echo '<div class="success-msg">The IP address ' . $ip_address . ' has been removed from the database</div>';
		}else{
			echo '<div class="error-msg">Failed to remove IP from database : ' . mysql_error() . '</div>';
		}
	}
	elseif ( $delete_ip == "all" ){
		$del = mysql_query ("DELETE FROM whtp_hitinfo");
		if ( $del ){
			echo '<div class="success-msg">All IP addresses have been removed from the database</div>';
		}else{
			echo '<div class="error-msg">Failed to remove IP addresses from database : ' . mysql_error() . '</div>';
		}	
	}
	$_POST['delete_this_ip'] = "";
}

# delete page
function whtp_delete_page( $delete_page = ""){
	
	if ($delete_page == ""){
		$delete_page = stripslashes( $_POST['delete_page'] );
	}
	
	if ( $delete_page != "all" ){
		$del = mysql_query ("DELETE FROM whtp_hits WHERE page='$delete_page'");
		if ( $del ){
			echo '<div class="success-msg">The page "' . $delete_page . '" has been deleted from your page counters records.</div>';
		}else{
			echo '<div class="error-msg">Failed to remove page "' . $delete_page . '"\'s counts.</div>';
		}
	}
	else{
		$del = mysql_query ("DELETE FROM whtp_hits");
		if ( $del ){
			echo '<div class="success-msg">All page counts have been deleted from the hit counter table. New entries will be made when users visit your pages again. If you no longer wish to count visits on certain pages, go to the page editor and remove the [whohit]..[/whohit] shortcode. If you don\'t want to see this table, click "Delete All" to remove the pages.</div>';
		}else{
			echo '<div class="error-msg">Failed to remove page counts.</div>';
		}
	}
}
/*
* start gathering unique user data
* and update the table
*/
function whtp_hit_info(){
	
	$ip_address			= $_SERVER["REMOTE_ADDR"];	# visitor's ip address
	$user_agent 		= $_SERVER["HTTP_USER_AGENT"]; # visitor's user agent
	$date_ftime 		= date("Y/m/d") . ' ' . date('H:i:s'); # visitor's first visit
	$date_ltime			= date("Y/m/d") . ' ' . date('H:i:s'); # visitor's last visit
	
	/*
	* first check if the IP is in database
	* if the ip is not in the database, add it in
	* otherwise update
	*/
	
	$query  =  mysql_query( "SELECT ip_address FROM whtp_hitinfo WHERE ip_address = '$ip_address'" );
	if ( $query ){	
		if( !mysql_num_rows( $query ) ) {
			$insert_hit_info = mysql_query("INSERT INTO whtp_hitinfo (ip_address, ip_total_visits, user_agent, datetime_first_visit, datetime_last_visit) VALUES('$ip_address' , 1, '$user_agent','$date_ftime','$date_ltime' )");
		}
		else{
			$update_ip_visit = mysql_query( "UPDATE whtp_hitinfo SET ip_total_visits = ip_total_visits+1, user_agent='$user_agent', datetime_last_visit = '$date_ltime' WHERE ip_address='$ip_address'" );
		}
	}
}

/*
* detect wordpress install host
* if host is the same as referrer, or host is the site url, we deny
* The host or developer's IP is not counted
*/
function deny_wordpress_host_ip(){
	
	$local 		= $_SERVER['HTTP_HOST'];	# this host's name
	$siteurl 	= get_option('siteurl');	# wordpress site url
	$ref 		= $_SERVER['HTTP_REFERER']; # referrer host name	
	$rem 		= $_SERVER['REMOTE_ADDR'];  # visitor's ip address
	
	if ( isset ( $_SERVER['SERVER_ADDR'] ) ) {
		$local_addr	= $_SERVER['SERVER_ADDR'];  # this host's ip address
	}
	
	$deny = false;
	# if local = remote, then its wordpress host, deny
	if ( $local_addr == $rem ) {
		$deny = true;
		return $deny;
	}
	/*
	* try to see if the host name is not the referrer name
	* by exploding host name and referrer into array and comparing indexes of those arrays
	*/	
	$refarr = explode("/", $ref);
	$localarr = explode("/", $local);
	
	# 1. if hostname is in the referrer array, or referrer in hostname array, deny
	if ( in_array( $local, $refarr ) || in_array ( $ref, $localarr ) ){	
		$deny = true;#echo "<br />Another deny rule<br />";
	}	
	# 2. If the index 'localhost' is the same as 'referrerhost' then deny
	if ( $refarr[2] == $localarr[2] ){		
		$deny = true;#echo "Another deny rule.... ";
	}
	# 3. Almost similar to 1 above 	
	if ( $refarr[2] == $local || $localarr[2] == $ref){		
		$deny = true;#echo " Another deny rule " . $refarr[2] ;
	}	
	# 4. explode siteurl into an array and compare index 2 with localhost
	$url = explode ( "/", $siteurl );
	if ( $url[2] == $local ) {		
		$deny = true;#echo "<br />We found local host, deny IP<br />";	
	}	
	# 5. If referrer is the site url or if the admin is browing or previewing pages
	if ( $siteurl == $ref || $local == $ref ){		
		$deny = true;#echo "Deny IP Address";
	}
	return $deny;
}

# check if an IP is denied
function ip_is_denied ( $ip_address ){
	$denied_result	= mysql_query("SELECT * FROM whtp_hitinfo WHERE ip_status='denied' AND ip_address='$ip_address' LIMIT 1");
	
	if ( $denied_result ){
		if ( mysql_num_rows ( $denied_result ) == 1 ){
			return true;
		}
		else {
			return false;
		}
	}
	else{
		echo mysql_error();
		return false;	
	}
}

# set an IP's status as denied
function whtp_deny_ip(){
	$ip_address = stripslashes( $_POST['ip_address'] );
	
	$allow = mysql_query( "UPDATE `whtp_hitinfo` SET `ip_status` = 'denied' WHERE ip_address='$ip_address'" );
	
	if ( $allow ) {
		echo '<div class="success-msg">Denied . The IP "' . $ip_address . '" has been denied and will not be counted the next time it visits your website.</div>';
	}else{
		echo '<div class="error-msg">Failed to Deny "' . $ip_address . '" ' .mysql_error() . ' </div>';
	}
}
/*
* Discount a page's counter by -1
*
*/
function whtp_discount_page(){
	$page = stripslashes( $_POST['discount_page'] );
	$discount_page = mysql_query(	"UPDATE whtp_hits SET count = count-1 WHERE page = '$page'"	);
	
	if ( $discount_page ) {
		echo '<div class="success-msg"><p>Discounted</b> . The Page "' . $page . '" has been discounted by (1). If you have visited the page more than once, then continue to Discount yourself on this page, otherwise Discount yourself on other pages you have visited</div>';
	}else{
		echo '<div class="error-msg">Failed to Discount on the page "' . $page . '" ' .mysql_error() . ' </div>';
	}
}

# updates signup form
function whtp_signup_form(){?>
<form action="" method="post" id="signup">
    <input type="hidden" name="whtpsubscr" value="y" />
    <label for="asubscribe_email">Enter your email address to subscribe to updates</label>
    <input type="email" placeholder="e.g. <?php echo get_option('admin_email'); ?>" name="asubscribe_email" value="" /><br />
    <input type="submit" value="Subscribe to updates" class="button-primary"/>
</form>
<?php }
?>