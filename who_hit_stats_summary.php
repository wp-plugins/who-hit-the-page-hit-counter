<?php
	global $wpdb;
	
	$user_stats = array();
	$browsers = array();
	$top_countries = array();
	
	$query = "SELECT * FROM whtp_hits";	 //where ip address = 127.0.0.1
	$result = mysql_query( $query );
	if ( $result ){
		if ( $result ){			
			$hit_result = mysql_query("SELECT * FROM whtp_hits ORDER BY count DESC");		
		}
	}
	
	/*
	/*$country_ips = "SELECT ip_id, COUNT(ip_id) AS total_visitors FROM whtp_ip_hits 
					WHERE (SELECT COUNT(ip_id) AS total_2 FROM whtp_ip_hits) < (SELECT COUNT(ip_id) FROM whtp_ip_hits) ";
										
	$country_ips = "SELECT ip_id, MAX(ip_id) FROM (SELECT COUNT(ip_id) AS ip_id FROM whtp_ip_hits) AS top_ips";
	
	$country_ips = "SELECT ip_id AS id, MAX(COUNT(ip_id)) AS count FROM whtp_ip_hits ORDER BY count DESC";
	
	$ips = mysql_query($country_ips);
	
	if ( $ips ) {
		$ips_array = mysql_fetch_array( $ips );
		var_dump ( $ips_array );
		while ( $row = mysql_fetch_row( $ips_array ) ){
			echo "ID : ". $row['id'] . " <<:::>> COUNT : " .$row['count'] . "<br />";	
		}
	}else echo mysql_query();*/
	
	$browsers = $wpdb->get_results ( "SELECT agent_name FROM whtp_user_agents" );
	// Total hits
	function total_hits (){
		global $wpdb;
		$total_hits = $wpdb->get_var( "SELECT SUM(count) AS totalhits FROM whtp_hits" );
		if ( is_bool( $total_hits )  ){		
			return 0;	
		}
		else{
			return $total_hits;
		}
	}
	
	function total_unique_ips($date_in_past=""){
		if ($date_in_past==""){
			$where = "";
		}else{
			$date_today = date("Y/m/d");
			if ($date_in_past=="today"){
				$date = date ("Y/m/d"); //, strtotime("today") );
				$date = strtotime( $date . " " . "0:0:0");
				$where= "DATE_SUB(CURDATE(), INTERVAL 1 DAY) >= $date";
			}
			elseif ($date_in_past=="yesterday"){
				$date = strtotime("yesterday");
				$where= "DATE_SUB(CURDATE(), INTERVAL 1 DAY) >= $date";
			}			
			elseif ($date_in_past=="7 days"){
				$date = strtotime("-7 days");
				$where= "DATE_SUB(CURDATE(), INTERVAL 7 DAY) >= $date";
			}
			elseif($date_in_past=="lastmonth"){
				$date = strtotime("-30 days");
				$where= "datetime_last_visit BETWEEN CURDATE() AND $date";
			}
		}
		if ($where == ""){
			$query = "SELECT * FROM whtp_hitinfo WHERE ip_status='active' ORDER BY ip_total_visits DESC";			
		}else{
			$query ="SELECT * FROM whtp_hitinfo WHERE ip_status='active' AND $where ORDER BY ip_total_visits DESC";
		}
		$hit_info_result = mysql_query($query);
		if( $hit_info_result ){		 //where ip address = 127.0.0.1
			$total_unique = mysql_num_rows( $hit_info_result );
		}
		return $total_unique;
	}/**/
	
	function past_present( $date ){
		$seven_day = date ("Y/m/d", strtotime("-7 days") );
	}/**/
	//IP Stats
	
	//total unique visitors
	$total_unique = $wpdb->get_var("SELECT COUNT(ip_address) AS totalunique FROM whtp_hitinfo WHERE ip_status='active'");
	
	$top_visitors = $wpdb->get_results( "SELECT ip_address, ip_total_visits FROM whtp_hitinfo WHERE ip_status='active' ORDER BY ip_total_visits DESC" );
	
	function top_visiting_countries(){
		global $wpdb;
		$select_countries = $wpdb->get_results("SELECT * FROM whtp_visiting_countries ORDER BY count");
		if ( $select_countries ){
			$countries = array();
			$num_rows = count($select_countries);
			if ( $num_rows > 5 ) $max_count = 5;
			else $max_count = $num_rows;
			
			for ( $count = 0; $count < $max_count; $count ++ ) {
				$row = $select_countries[$count];
				$countries[] = array('country_code'=>$row->country_code, 'country_name'=>get_country_name($row->country_code),'count'=> $row->count );
			}
			return $countries;
		}		
	}
	
	function get_country_name($country_code){
		global $wpdb;
		$country_name = $wpdb->get_var("SELECT country_name FROM whtp_ip2location WHERE country_code='$country_code' LIMIT 0,1");
		if ( $country_name == "") return "Unknown Country";
		else return $country_name;
	}
	
	function whtp_print_error(){
		global $wpdb;
		$wpdb->show_errors();
		$wpdb->print_error();
	}

?>
<div class="wrap">	
	<h2>Who Hit The Page Hit Counter</h2>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
            	<div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle">Support</h3>
                    <div class="inside welcome-panel-column welcome-panel-last">
                        <h4>Donate via PayPal</h4>
                        <p>Any Amount is highly Appreciated</p>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="3CL75HTEMYZW4">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
                    </div>  
                </div>
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle">Subscribe to updates</h3>
                    <div class="inside welcome-panel-column welcome-panel-last">
					   <?php
                            if(isset($_POST['whtpsubscr']) && $_POST['whtpsubscr'] == "y"){
                                whtp_admin_message_sender();
                            }
                            whtp_signup_form();
                        ?>
                        <p>Thank you once again!</p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle">Please Rate this plugin</h3>
                    <div class="inside welcome-panel-column welcome-panel-last">
                        <p><b>Dear User</b></p>
                        <p>Please 
                        <a href="http://wordpress.org/support/view/plugin-reviews/who-hit-the-page-hit-counter">Rate this plugin now.</a> if you appreciate it.
                        Rating this plugin will help other people like you to find this plugin because on wordpress plugins are sorted by rating, so rate it high and give it a fair review to help others find it.<br />
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="post-body">
            <div id="post-body-content">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox inside">
                    	<div class="handlediv" title="Click to toggle"><br /></div>
                    	<h3 class="hndle">Summary</h3>
                        <div class="inside">
                            <div id="welcome-panel" class="welcome-panel">                                
                                <div class="welcome-panel-content">
                                    <p class="about-description">This is a summary of your page hit statistics.</p>
                                    <!-- Top -->
                                    <div class="welcome-panel-column-container">
                                        <div class="welcome-panel-column">
                                            <h4>Total Page Hits: <?php echo total_hits(); ?></h4>
                                            <h4>Total Unique Visitors :<?php echo $total_unique; ?></h4>
                                            <!--<p>Today Only: <strong></strong></p>
                                            <p>Yesterday<strong></strong></p>
                                            <p>Last 7 Days<strong><?php //past_present( $date ); ?></strong></p>-->
                                        </div>
                                        <div class="welcome-panel-column">
                                            <h4>Top 5 Visitors</h4>                                                
                                            <?php 
                                            if ( is_admin() ){                                                    
                                                $limit = count ( $top_visitors );                                                    
                                                if ($limit > 5) {
                                                    $limit = 5;
                                                }
                                                if ($limit > 0){
                                                    echo '<table cellpadding="5" cellspacing="2">' . "\n";
                                                    echo "\t<thead><th>IP Address</th><th>Total Hits</th></thead>\n";
                                                    echo "\t<tbody>\n";
                                                    for ($count = 0; $count < $limit; $count ++){
                                                        $top = $top_visitors[$count] ;	
                                                        echo '<tr><td><a href="admin.php?page=whtp-visitor-stats&ip=' 
                                                        . $top->ip_address 
                                                        . '">' . $top->ip_address 
                                                        . '</a></td><td>' . $top->ip_total_visits
                                                        . '</td></tr>' . "\n";
                                                    }
                                                    echo "\t</tbody>\n";
                                                    echo "</table>";
                                                }
                                            }
                                            ?>
                                            <!--<p>Today Only: <strong><?php echo total_unique_ips("today"); ?></strong></p>
                                            <p>Yesterday: <strong><?php echo total_unique_ips("yesterday"); ?></strong></p>
                                            <p>Last 7 Days: <strong><?php echo total_unique_ips("7 days"); ?></strong></p>-->
                                        </div>
                                        <div class="welcome-panel-column welcome-panel-last">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="postbox inside">
                    	<div class="handlediv" title="Click to toggle"><br /></div>
                    	<h3 class="hndle">More information</h3>
                        <div class="inside">
                            <div id="welcome-panel" class="welcome-panel">
                                <div class="welcome-panel-content">     
                                    <!-- Main -->
                                    <div class="welcome-panel-column-container">
                                        <div class="welcome-panel-column">
                                            <h4>Used Browsers</h4>                    
                                            <?php if ( count ( $browsers ) > 0 ){ ?>
                                            <ul>
                                                <?php
                                                foreach ( $browsers as $browser ){
                                                    echo '<li><div class="welcome-icon welcome-widgets-menus">'
                                                    . $browser->agent_name .'</div></li>';                        
                                                }//end for ?>
                                            </ul>
                                            <?php
                                                }else{
                                                    echo '<p>Unknown Browsers</p>';
                                                }
                                            ?>
                                        </div>
                                        <div class="welcome-panel-column">
                                            <h4>Top Visiting Countries</h4> 
                                            <?php
                                            
                                            $top_countries = array();
                                            $top_countries = top_visiting_countries();
                                            
                                            if ( count ( $top_countries ) ){
                                            ?>
                                            <ul>
                                                <?php
                                                
                                                for ( $count = 0; $count < count ( $top_countries ); $count ++ ){
                                                    $top_country = $top_countries[$count];
                                                    echo '<li><div class="welcome-icon welcome-widgets-menus">' 
                                                    . $top_country['country_name'] . "\t ,"
                                                    . $top_country['country_code'] . "\t ("
                                                    . $top_country['count']
                                                    . ') </div></li>';
                                                }
                                                
                                                ?>
                                            </ul>
                                            
                                            <?php }//end if 
                                            ?>
                                        </div>                                    
                                    </div>
                                    <div class="welcome-panel-column-container">
                                        <h4>Top Visiting Countries</h4>
                                        
                                    </div>
                                </div><!-- welcome-panel-content -->
                            </div><!-- welcome-panel -->
                        </div>
                    </div>
                    <div class="postbox inside">
                    	<div class="handlediv" title="Click to toggle"><br /></div>
                    	<h3 class="hndle">Disclaimer</h3>
                        <div class="inside">
                            <p>This product includes GeoLite2 data created by MaxMind, available from <a href="http://www.maxmind.com">http://www.maxmind.com</a></p>
                            <p>I, Lindeni Mahlalela, referred to as "mahlamusa" don't guarantee the accuracy of the Geolocation data used in this plugin. I do not claim that I have gathered this data myself, but this product uses GeoLite2 data created by MaxMind, available from <a href="http://www.maxmind.com">http://www.maxmind.com</a>. If the data is inaccurate, please be advisable tha providing accurate data is beyond my personal capacity. When this version of the plugin was released, the data was 80% accurate.</p>
                             <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Wrap -->
