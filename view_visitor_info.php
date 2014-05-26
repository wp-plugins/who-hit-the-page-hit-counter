<?php
	global $wpdb;
	if ( isset ( $_GET['ip'] ) && $_GET['ip'] != "" ){
		$visitor_ip =	$_GET['ip'];//"192.10.10.253";//;france = "80.248.208.145", za = 41.61.255.255;, za = 41.77.63.255;
		$ip_id = get_ip_id($visitor_ip);
		echo display_select_visitor_ip();
		wp_reset_postdata();
	}
	elseif( isset ( $_POST['ip_address'] ) ){
		$visitor_ip = stripslashes( $_POST['ip_address'] );
		$ip_id = get_ip_id($visitor_ip);
		echo display_select_visitor_ip();
		wp_reset_postdata();
	}else{
		echo display_select_visitor_ip();
		echo '<div class="wrap">
				<div id="message" class="updated">
					<p>Please select an IP to see more details about that IP Address.</p> 
				</div>
			  </div>';				 
	}
	
		
	
	/*
	*	 Results are stored in arrays
	*/
		
	$user_stats = array();
	$info_result = array();
	$user_agents = array();
	$page_ids = array();
	$hit_result = array();
	$pages_visited = array();
	$countries = array();
	$agent_ids = array();
	$browsers = array();
	/*
	* Given the page ids, get the page names
	* save to array of pages and counts
	*/
	$page_ids = page_ids_from_ip_id($ip_id);
	
	for ( $count = 0; $count < count($page_ids); $count ++ ){
		$page_id = $page_ids[$count];		
		$page = $wpdb->get_row ( "SELECT page, count FROM whtp_hits WHERE page_id='$page_id' LIMIT 0,1" ); 
		$pages_visited[] = $page; // $row = ({"page","count"})
	}
	/*
	* Get hitinfo
	*
	*/
	$hit_info_result = $wpdb->get_row("SELECT * FROM whtp_hitinfo WHERE ip_address='$visitor_ip'");  
	$info_result = $hit_info_result;	
	/*
	* Select the country associated with the selected $ip_id
	* and store the countries as an array, then display the first country
	*/
    $select_country = "SELECT country_name FROM whtp_ip2location WHERE INET_ATON('" . $visitor_ip . "') 
                    BETWEEN decimal_ip_from AND decimal_ip_to";                    
    $countries = $wpdb->get_col($select_country);
	
	
	/*
	* Select the browsers used by the selected $ip_id
	*
	*/
	$agent_ids = agent_ids_from_ip_id( $ip_id );
	
	/*
	*	Get browsers used
	*	returns an array of browsers
	*	pass an array of browser ids,you will get browsers with those ids
	*   pass an empty string, you get an array of all browsers ever used
	*/
	for ( $count = 0; $count < count($agent_ids); $count ++ ){
		$agent_id = $agent_ids[$count];
		$browser = $wpdb->get_var ( "SELECT agent_name FROM whtp_user_agents WHERE agent_id='$agent_id' LIMIT 0,1" );
		if ( $browser ){
			$browsers[] = $browser;
		}
	}
	
	/*
	* Get all the ids of the browsers used by the user
	* Return as an array of agent_ids
	*/
	function agent_ids_from_ip_id($ip_id){
		global $wpdb;
		$ids = $wpdb->get_col( "SELECT browser_id FROM whtp_ip_hits WHERE ip_id = '$ip_id'" );
		if ( count ( $ids ) ){
			$agent_ids = array();
			for( $count=0; $count < count ($results); $count ++){
				if  ( !in_array( $ids[$count], $agent_ids )	){
					$agent_ids[] = $ids[$count];
				}
			}
		}
		return $agent_ids;
	}
	/*
	* Get all the ids of the pages visted by the user
	* Return as an array of page_ids
	*/
	function page_ids_from_ip_id($ip_id){
		global $wpdb;
		$results = $wpdb->get_col( "SELECT page_id FROM whtp_ip_hits WHERE ip_id = '$ip_id'" );
		if ($results){
			$page_ids = array();
			for( $count = 0; $count < count($results); $count ++ ){
				if  ( !in_array( $results[$count], $page_ids )	){
					$page_ids[] = $results[$count];
				}
			}
			return $page_ids;
		}
		return false;
	}/*
	*	Display a select for the user to select an IP
	*/
	function display_select_visitor_ip(){
		global $wpdb;
		$ip_results = $wpdb->get_results( "SELECT ip_address, ip_total_visits FROM whtp_hitinfo ORDER BY ip_total_visits DESC" );
		if ( $ip_results ){
			$form = '<div class="wrap">'. "\n";
			$form = '<form name="select_ip" method="post" action="" >' . "\n";
			//$form .= "\t" . '<p>Please select an IP to see more details about that IP Address.</p>' . "\n";
			$form .= "\t" . '<select name="ip_address" style="padding:5px;">' . "\n";;
			foreach ( $ip_results as $ip ){
				$form .= "\t" . "\t" . '<option style="padding:10px;" value="' 
				. $ip->ip_address . '">' 
				. $ip->ip_address . ' (' 
				. $ip->ip_total_visits 
				. ')</option>' . "\n";	
			}	
			$form .= "\t" . '</select>' . "\n";
			$form .= "\t" . '<input type="submit" value="View Details" class="button button-primary" />'. "\n";
			$form .= '</form>' . "\n";
			$form .= '</div>' . "\n";		
		}else{
		 	$form .= $wpdb->print_error();	
		}
		return $form;
	}
?>
<div class="wrap">
	<h2>View Visitor's Behaviour (IP: <?php echo $visitor_ip; ?>)</h2>
	<div id="welcome-panel" class="welcome-panel">
    	<div class="welcome-panel-content">
            <h3>Visitor Statistics!</h3>
            <p class="about-description">
            	This are the statistics for a single user/visitor with IP Address :<?php echo $visitor_ip; ?>
            </p>
            <!-- Top -->
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h4>Visitor's IP: <?php if (!$visitor_ip) echo "IP Address Not Set"; else echo $visitor_ip; ?></h4>
                </div>
                <div class="welcome-panel-column">
                    <h4>Total Visits: <?php if (!$info_result) echo "Not Set"; 
					else echo $info_result->ip_total_visits; ?></h4>
                </div>
                <div class="welcome-panel-column welcome-panel-last">
                    <h4>Location: <?php if ( !$countries ) echo "Unknown"; else echo $countries[0]; ?></h4>
                </div>
            </div>
         </div>
     </div>
     <div id="welcome-panel" class="welcome-panel">
    	<div class="welcome-panel-content">    
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                   <?php if ( $info_result ){ ?>
                    <h3>Date and Time of First and Last Visit</h3>
                    <ul>
                        <li>
                        	First Visit : 
                            	<span class="entry-date">
									<?php echo $info_result->datetime_first_visit; ?>
                                </span>
                        </li>
                        <li>
                        	Last Visit : 
                            <span class="entry-date">
								<?php echo $info_result->datetime_last_visit; ?>
                            </span>
                        </li>
                    </ul>
                     <?php } //end if ?>
                 </div>
            </div>
        </div>
    </div>
    <div id="welcome-panel" class="welcome-panel">
    	<div class="welcome-panel-content">     
            <!-- Main -->
            <div class="welcome-panel-column-container">
                <!--<div class="welcome-panel-column">
                    <h4></h4>
                    <a class="button button-primary button-hero load-customize hide-if-no-customize" href="#">
                    Customize Your Site</a>
                    <a class="button button-primary button-hero hide-if-customize" href="#">
                    Customize Your Site</a>
                    <p class="hide-if-no-customize">or, <a href="#">change your theme completely</a></p>
                </div>-->
                <div class="welcome-panel-column">
                    <h4>Pages Visited by this user</h4> 
                    <ul>
                    <?php
						if ( !$page_ids || count($page_ids) == 0 ){
							echo '<a href="#" class="welcome-icon welcome-view-site">Un-identified Page</a>';
						}
						else{
							foreach ( $pages_visited as $page){
								echo '<a href="#" class="welcome-icon welcome-view-site">'  . $page->page . '('.$page->count . ')</a>';	
							}
						}
					?>
                    </ul>
                </div>
                <div class="welcome-panel-column welcome-panel-last">
                    <h4>The User Have The following Browsers</h4>
                    <ul>
					<?php
						if ( !$browsers || 0 == count( $browsers )){
							echo '<li><div class="welcome-icon welcome-widgets-menus">Unknown Browser(s)</div></li>';
						}else{
							for ( $count=0; $count < count($browsers); $count ++){
								echo '<li><div class="welcome-icon welcome-widgets-menus">';
								echo $browsers[$count];
								echo '</div></li>';       
							}
						}
                    ?>
                    </ul>
                </div>
            </div>
		</div><!-- welcome-panel-content -->
    </div><!-- welcome-panel -->
</div><!-- Wrap -->
