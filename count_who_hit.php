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
		$page = $page;
		whtp_count_hits( $page );
		whtp_hit_info( $page );		
		# try detecting wordpress host, then deny it
		# removed feature because it seems to work diferently on diferent hosts
		//if ( deny_wordpress_host_ip() ){} 
	}
}
/*
* Check if the page has been visited then
* update or create new counter
*/
function whtp_count_hits( $page ){
	global $wpdb;
	$page = $page;
	
	$ua = getBrowser(); //Get browser info
	$browser = $ua['name'];
		
	$page = $wpdb->get_var("SELECT page FROM whtp_hits WHERE page = '$page' LIMIT 1");
	
	if ($page ){
		$update = $wpdb->update("whtp_hits",array("count"=> "count+1"), array("page"=>$page),array("%d"),array("%s"));
		if ( !browser_exists($browser) ){
			add_browser($browser);
		}
	}
	else {
		$insert = $wpdb->insert("whtp_hits", array("page"=>$page,"count"=>1), array("%s", "%d"));
		$page_id = $wpdb->insert_id;
		$_SESSION['insert_page'] = $page_id;
		
		if ( !browser_exists($browser) ){
			add_browser($browser);
		}
	}
}

/*
* start gathering unique user data
* and update the table
*/
function whtp_hit_info( $page ){
	global $wpdb;
	$page = $page;
	
	$ip_address			= $_SERVER["REMOTE_ADDR"];	# visitor's ip address
	$date_ftime 		= date("Y/m/d") . ' ' . date('H:i:s'); # visitor's first visit
	$date_ltime			= date("Y/m/d") . ' ' . date('H:i:s'); # visitor's last visit
	
	$ua = getBrowser(); //Get browser info
	$browser = $ua['name'];
	
	$page_id = get_page_id( $page );
	/*
	* first check if the IP is in database
	* if the ip is not in the database, add it in
	* otherwise update
	*/
	
	$ip  =  $wpdb->get_var( "SELECT ip_address FROM whtp_hitinfo WHERE ip_address = '$ip_address'" );
	if ( !$ip || "" != $ip){	
		$insert_hit_info = $wpdb->insert("whtp_hitinfo", array("ip_address"=>$ip_address,"ip_total_visits"=>1,"user_agent"=>$browser,"datetime_first_visit"=>$date_ftime,"datetime_last_visit"=>$date_ltime), array("%s", "%d", "%s", "%s", "%s"));
			
			
		//insert other information
		$ip_id = $wpdb->insert_id;						
		$ua_id = get_agent_id($browser);
		$page_id = get_page_id ( $page );
		
		if ( !browser_exists($browser) ){
			add_browser($browser);
		}
		ip_hit($ip_id,$page_id,$date_ftime,$ua_id); //insert new hit			
	}
	else{
		$update_ip_visit = $wpdb->update( "whtp_hitinfo", array("ip_total_visits"=>"ip_total_visits+1", "user_agent"=>$browser, "datetime_last_visit"=>$date_ltime), array("ip_address"=>$ip_address), array("%d","%s","%s"));
		
		$ua_id = get_agent_id($browser);
		$ip_id = get_ip_id($ip_address);
		$page_id = get_page_id ( $page );
		
		if ( !browser_exists($browser) ){
			add_browser($browser);
		}
		ip_hit($ip_id,$page_id,$date_ftime,$ua_id); //always insert new hit
	}
	
	
	// get the country code corresponding to the visitor's IP
	$country_code = get_country_code( $ip_address );
	country_count( $country_code );
}


/*
*	Count country's visits
*/
function get_country_code( $visitor_ip ){
	global $wpdb;
	$select_country_code = "SELECT country_code FROM whtp_ip2location WHERE INET_ATON('" . $visitor_ip . "') 
                    BETWEEN decimal_ip_from AND decimal_ip_to LIMIT 1";                    
    $country_code = $wpdb->get_var($select_country_code);
	
	if ( $country_code ){		
		return $country_code;
	}	
	else return "";
}

function country_count( $country_code ){	
	global $wpdb;
	$code = $wpdb->get_var("SELECT country_code FROM whtp_visiting_countries WHERE country_code = '$country_code'");
	
	if ( $code ) {	
		$wpdb->update("whtp_visiting_countriess", array("count"=>"count+1"), array("country_code"=> $country_code), array("%d"), array("%s"));	
	}
	else {
		if ( $code != ""){
			$wpdb->insert( "whtp_visiting_countries", array("country_code"=>$country_code, "count"=>1));
		}
	}
}

/*
*	Get agent id
*/
function get_agent_id($browser){
	global $wpdb;
	$ua_id = $wpdb->get_var("SELECT agent_id FROM whtp_user_agents WHERE agent_name = '$browser' LIMIT 1");
	if ( $ua_id ){
		return $ua_id;
	}
	else{
		return "Unknown Browser";	
	}
}

function get_ip_id( $ip_address ){
	global $wpdb;
	$ip_id  = $wpdb->get_var("SELECT id FROM whtp_hitinfo WHERE ip_address='$ip_address' LIMIT 1");			
	if ($ip_id){
		return $ip_id;
	}
}

function browser_exists($browser){
	global $wpdb;
	$browser = $wpdb->get_var("SELECT agent_name FROM whtp_user_agents WHERE agent_name='$browser' LIMIT 1" );
	if ($browser){
		return true;
	}
	else return false;
}

function add_browser($browser, $details=""){
	global $wpdb;
	$$wpdb->insert("whtp_user_agents", array("agent_name"=>$browser, "agent_details"=>$details), array("%s", "%s") );	
}

function ip_hit($ip_id,$page_id,$date_ftime,$ua_id){
	global $wpdb;
	$wpdb->insert( "whtp_ip_hits", array("ip_id"=>$ip_id,"page_id"=>$page_id,"datetime_first_visit"=>$date_ftime,"browser_id"=>$ua_id), array("%d","%d","%s","%d") );	
}

function get_page_id ( $page ){
	global $wpdb;
	$page_id  = $wpdb->get_var("SELECT page_id FROM whtp_hits WHERE page='$page' LIMIT 1");
	return $page_id;
}
# reset page counts
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
	global $wpdb;
	$ip_address = $_POST['ip_address'];	
	if ($reset == ""){
		$reset_ip = stripslashes( $_POST['reset_ip'] );
	}else{
		$reset_ip = $reset;
	}
	if ($reset_ip != "all" ){
		# reset specific
		$reset=$wpdb->update("whtp_hitinfo", array("ip_total_visits"=>0),array("ip_address"=>$ip_address), array("%d"), array("%s"));
		if ( $reset ) {
			echo '<div class="success-msg">The count for ip address: ' . $ip_address . ' has been reset successfully.</div>';
		}
		else{
			echo '<div class="error-msg">Failed to reset count for IP: ' . $ip_address  .'</div>';
		}
	}
	elseif($reset_ip == "all" ){
		# reset all ip counters
		$reset_all = $wpdb->update( "whtp_hitinfo",array("ip_total_visits"=>0), array("%d"));
		if ( $reset_all ) {
			echo '<div class="success-msg">The count for "All" IPs has been reset successfully.</div>';
		}
		else{
			echo '<div class="error-msg">Failed to reset count for "All" IPs.</div>';
		}
	}
	$_POST['ip_address'] = "";
}

# delete ip address
function whtp_delete_ip (){
	global $wpdb;
	$ip_address = stripslashes($_POST['delete_this_ip']);	
	$delete_ip = stripslashes ( $_POST['delete_ip'] );
	
	if ( $delete_ip == "this_ip" ) {
		$del = $wpdb->query ("DELETE FROM whtp_hitinfo WHERE ip_address='$ip_address'");
		if ( $del ){
			echo '<div class="success-msg">The IP address ' . $ip_address . ' has been removed from the database</div>';
		}else{
			echo '<div class="error-msg">Failed to remove IP from database.</div>';
		}
	}
	elseif ( $delete_ip == "all" ){
		$del = $wpdb->query("DELETE FROM whtp_hitinfo");
		if ( $del ){
			echo '<div class="success-msg">All IP addresses have been removed from the database</div>';
		}else{
			echo '<div class="error-msg">Failed to remove IP addresses from database.</div>';
		}	
	}
	$_POST['delete_this_ip'] = "";
}

# delete page
function whtp_delete_page( $delete_page = ""){
	global $wpdb;
	if ($delete_page == ""){
		$delete_page = stripslashes( $_POST['delete_page'] );
	}
	
	if ( $delete_page != "all" ){
		$del = $wpdb->query ("DELETE FROM whtp_hits WHERE page='$delete_page'");
		if ( $del ){
			echo '<div class="success-msg">The page "' . $delete_page . '" has been deleted from your page counters records.</div>';
		}else{
			echo '<div class="error-msg">Failed to remove page "' . $delete_page . '"\'s counts.</div>';
		}
	}
	else{
		$del = $wpdb->query("DELETE FROM whtp_hits");
		if ( $del ){
			echo '<div class="success-msg">All page counts have been deleted from the hit counter table. New entries will be made when users visit your pages again. If you no longer wish to count visits on certain pages, go to the page editor and remove the [whohit]..[/whohit] shortcode. If you don\'t want to see this table, click "Delete All" to remove the pages.</div>';
		}else{
			echo '<div class="error-msg">Failed to remove page counts.</div>';
		}
	}
}



# check if an IP is denied
function ip_is_denied ( $ip_address ){
	global $wpdb;
	$denied_ip	= $wpdb->get_var("SELECT ip_address FROM whtp_hitinfo WHERE ip_status='denied' AND ip_address='$ip_address' LIMIT 1");
	
	if ( $denied_ip && $denied_ip != "" ){
		return true;
	}
	else{
		//echo mysql_error();
		return false;	
	}
}

# set an IP's status as denied
function whtp_deny_ip(){
	global $wpdb;
	$ip_address = stripslashes( $_POST['ip_address'] );
	
	$deny = $wpdb->update("whtp_hitinfo", array("ip_status"=>"denied"), array("ip_address"=>$ip_address), array("%s"), array("%s"));
	
	if ( $deny ) {
		echo '<div class="success-msg">Denied . The IP "' . $ip_address . '" has been denied and will not be counted the next time it visits your website.</div>';
	}else{
		echo '<div class="error-msg">Failed to Deny "' . $ip_address . '" </div>';
	}
}
/*
* Discount a page's counter by -1
*
*/
function whtp_discount_page(){
	global $wpdb;
	$page = stripslashes( $_POST['discount_page'] );
	$discountby = stripslashes( $_POST['discountby'] );
	$discount_page = $wpdb->update("whtp_hits", array("count"=>"count-$discountby"), array("page"=>$page));
	
	if ( $discount_page ) {
		echo '<div class="success-msg"><p>Discounted</b> . The Page "' . $page . '" has been discounted by (1). If you have visited the page more than once, then continue to Discount yourself on this page, otherwise Discount yourself on other pages you have visited</div>';
	}else{
		echo '<div class="error-msg">Failed to Discount on the page "' . $page . '."</div>';
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



function getBrowser($u_agent = "")
{
	if ( $u_agent == "" ){
    	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	}
	else {
		$u_agent = $u_agent;	
	}
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

/*
* Future functionality
* Collapse or expand all IP details
* Geolocate IP address
* User IP address and all browsers used
* Divide IP's Total Hits to the number of browsers associated to that IP	
*/

/*
* detect wordpress install host
* if host is the same as referrer, or host is the site url, we deny
* The host or developer's IP is not counted
*/

# this is function seems to work diferently on diferent hosts
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
?>