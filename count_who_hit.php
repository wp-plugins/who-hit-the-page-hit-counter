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
	
	//$ua = getBrowser(); //Get browser info
	$ua = whtp_browser_info();
	$browser = $ua['name'];
		
	$page_check = $wpdb->get_var("SELECT page FROM whtp_hits WHERE page = '$page' LIMIT 1");
	if ( $page_check != "" ){
		$count = $wpdb->get_var("SELECT count FROM whtp_hits WHERE page = '$page' LIMIT 1");
		$ucount = $count + 1;
		$update = $wpdb->update("whtp_hits",array("count"=> $ucount), array("page"=>$page),array("%d"),array("%s"));
	}
	else {
		$insert = $wpdb->insert("whtp_hits", array("page"=>$page,"count"=>1), array("%s", "%d"));
		$page_id = $wpdb->insert_id;
		$_SESSION['insert_page'] = $page_id;
	}
	if ( !browser_exists($browser) ){
		add_browser($browser);
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
	
	//$ua = getBrowser(); //Get browser info
	$ua = whtp_browser_info();
	$browser = $ua['name'];
	
	$page_id = get_page_id( $page );
	/*
	* first check if the IP is in database
	* if the ip is not in the database, add it in
	* otherwise update
	*/
	
	$ip_check  =  $wpdb->get_var( "SELECT ip_address FROM whtp_hitinfo WHERE ip_address = '$ip_address'" );
	if ( $ip_check == ""){
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
		$ip_total_visits = $wpdb->get_var("SELECT ip_total_visits FROM whtp_hitinfo WHERE ip_address = '$ip_address'");
		$ip_total_visits += 1;
		$update_ip_visit = $wpdb->update( "whtp_hitinfo", array("ip_total_visits"=>$ip_total_visits, "user_agent"=>$browser, "datetime_last_visit"=>$date_ltime), array("ip_address"=>$ip_address), array("%d","%s","%s"));
		
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
/*
* Count a visiting country
* update country's count
*/
function country_count( $country_code ){	
	global $wpdb;
	$res = false;
	$code = $wpdb->get_var("SELECT country_code FROM whtp_visiting_countries WHERE country_code = '$country_code'");
	
	if ( $code ) {	
		if ( $wpdb->update("whtp_visiting_countriess", array("count"=>"count+1"), array("country_code"=> $country_code), array("%d"), array("%s")) )
			$res = true;
		else $res = false;
	}
	else {
		if ( $code != ""){
			if ( $wpdb->insert( "whtp_visiting_countries", array("country_code"=>$country_code, "count"=>1)) )
				$res = true;
			else $res = false;
		}
	}
	return $res;
}

/*
* Get entries fro old hitinfo table
* count the existing ips into the country count
*/
function update_count_visiting_countries(){
	global $wpdb;
	$ips  = $wpdb->get_results("SELECT ip_address FROM whtp_hitinfo WHERE ip_status = 'active'");
	if ( $ips ){
		foreach ($ips as $ip ){
			$country_code = get_country_code( $ip->ip_address );
			if ( country_count( $country_code ) )
				$res = true;
			else $res = false;				
		}
	}
	return $res;
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
	$wpdb->insert("whtp_user_agents", array("agent_name"=>$browser, "agent_details"=>$details), array("%s", "%s") );	
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
			echo '<div class="success-msg" id="message">The page "' . $delete_page . '" has been deleted from your page counters records.</div>';
		}else{
			echo '<div class="error-msg">Failed to remove page "' . $delete_page . '"\'s counts.</div>';
		}
	}
	else{
		$del = $wpdb->query("DELETE FROM whtp_hits");
		if ( $del ){
			echo '<div class="updated fade" id="message">All page counts have been deleted from the hit counter table. New entries will be made when users visit your pages again. If you no longer wish to count visits on certain pages, go to the page editor and remove the <code>[whohit]..[/whohit]<code> shortcode. If you don\'t want to see this table, click "Delete All" to remove the pages.</div>';
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
		echo '<div class="updated fade"  id="message">Denied . The IP "' . $ip_address . '" has been denied and will not be counted the next time it visits your website.</div>';
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
	$old_count = $wpdb->get_var( "SELECT count FROM whtp_hits WHERE page='$page'" );
	$discount_page = $wpdb->update("whtp_hits", array("count"=>$old_count-$discountby), array("page"=>$page));
	
	if ( $discount_page ) {
		echo '<div class="updated fade" id="message"><p>Discounted</b>. The Page "' . $page . '" has been discounted by ' . $discountby .'</div>';
	}else{
		echo '<div class="error-msg">Failed to Discount on the page "' . $page . '."</div>';
	}
}


# updates signup form
function whtp_signup_form(){?>
<form action="" method="post" id="signup">
    <input type="hidden" name="whtpsubscr" value="y" />
    <label for="asubscribe_email">Enter your email address to subscribe to updates</label>
    <input type="email" placeholder="e.g. <?php echo get_option('admin_email'); ?>" name="asubscribe_email" value="" class="90" /><br />
    <input type="submit" value="Subscribe to updates" class="button button-primary button-hero" />
</form>
<?php }

/*
* These functions reliy on the BroserDetection class
* Resturns an array ( $name, $version )
*
*/
function whtp_browser_info(){
	require_once('BrowserDetection.php');
	$browser_info = array();
	$browser = new BrowserDetection();
	if ($browser->getBrowser() == BrowserDetection::BROWSER_AMAYA ) {
		$browser_info['name'] = 'Amaya';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_ANDROID ) {
		$browser_info['name'] = 'Android';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_BINGBOT) {
		$browser_info['name'] = 'Bingbot';
		$broswer_info['version'] = $browser->getVersion();
	}
	
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_BLACKBERRY) {
		$browser_info['name'] = 'BlackBerry';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_CHROME) {
		$browser_info['name'] = 'Chrome';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_FIREBIRD) {
		$browser_info['name'] = 'Firebird';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_FIREFOX) {
		$browser_info['name'] = 'Firefox';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_GALEON) {
		$browser_info['name'] = 'Galeon';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_GOOGLEBOT) {
		$browser_info['name'] = 'Googlebot';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_ICAB) {
		$browser_info['name'] = 'iCab';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_ICECAT) {
		$browser_info['name'] = 'GNU IceCat';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_ICEWEASEL) {
		$browser_info['name'] = 'GNU IceWeasel';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_IE) {
		$browser_info['name'] = 'Internet Explorer';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_IE_MOBILE) {
		$browser_info['name'] = 'Internet Explorer Mobile';
		$broswer_info['version'] = $browser->getVersion();
	}elseif ($browser->getBrowser() == BrowserDetection::BROWSER_KONQUEROR) {
		$browser_info['name'] = 'Konqueror';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_LYNX) {
		$browser_info['name'] = 'Lynx';
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_MOZILLA) {
		$browser_info['name'] = 'Mozilla';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_MSNBOT) {
		$browser_info['name'] = 'MSNBot';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_MSNTV) {
		$browser_info['name'] = 'MSN TV';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_NETPOSITIVE) {
		$browser_info['name'] = 'NetPositive';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_NETSCAPE) {
		$browser_info['name'] = 'Netscape';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_NOKIA) {
		$browser_info['name'] = 'Nokia Browser';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_OMNIWEB) {
		$browser_info['name'] = 'OmniWeb';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_OPERA) {
		$browser_info['name'] = 'Opera';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_OPERA_MINI) {
		$browser_info['name'] = 'Opera Mini';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_OPERA_MOBILE) {
		$browser_info['name'] = 'Opera Mobile';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_PHOENIX) {
		$browser_info['name'] = 'Phoenix';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_SAFARI) {
		$browser_info['name'] = 'Safari';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_SLURP) {
		$browser_info['name'] = 'Yahoo! Slurp';
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_TABLET_OS) {
		$browser_info['name'] = 'BlackBerry Tablet OS';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_W3CVALIDATOR) {
		$browser_info['name'] = 'W3C Validator';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ($browser->getBrowser() == BrowserDetection::BROWSER_YAHOO_MM) {
		$browser_info['name'] = 'Yahoo! Multimedia';
		$broswer_info['version'] = $browser->getVersion();
	}
	elseif ( $browser->getBrowser() == BrowserDetection::BROWSER_UNKNOWN ) {
		$browser_info['name'] = 'Unknown';
		$broswer_info['version'] = 'Unknown';
	}
	/*
	*else($browser->getBrowser() == BrowserDetection:: ) {
	*	
	*}
	*/
	
	return $browser_info;
}
/*
* This is the old get browser method
* Now replaced with the browserInfo();
* browserInfor() is updated, detects more browsers than getBrowser()


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
	//"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36 OPR/20.0.1387.91"
	elseif(preg_match('Chrome/i',$u_agent) && preg_match('Safari/i',$u_agent)){
		$bname = 'Opera 20';
        $ub = "Opera";
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
	elseif(preg_match('/Flock/i',$u_agent)){
		$bname = "Flock";
		$ub = "Flock";
	}
	elseif(preg_match('/Lynx/i',$u_agent)){
		$bname = "Lynx";
		$ub = "Lynx";
	}
	/*
	* Convert all to lowercase
	* detect other browsers that don't use standard naming conversions
	*
	*/
	/*
	if ( $bname == "Unknown" ){
		$u_agent = strtolower( $u_agent );
		if(preg_match('/msie/i',$u_agent) && !preg_match('/opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		elseif(preg_match('/flock/i',$u_agent)){
			$bname = "Flock";
			$ub = "Flock";
		}
		elseif(preg_match('/lynx/i',$u_agent)){
			$bname = "Lynx";
			$ub = "Lynx";
		}
	}
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
	 // Next get the name of the useragent yes seperately and for good reason
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
*
*
*
*/



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