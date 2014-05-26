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
	
	
?>
<div class="wrap">	
	<div id="welcome-panel" class="welcome-panel">
    	<h2>Who Hit The Page</h2>
    	<div class="welcome-panel-content">
            <h3>Visitor Statistics Summary!</h3>
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
                    <h4>Awesome Features Coming Soon</h4>
                    <p>I will soon release a Premium Version of Who Hit The Page, in the premium version you will get awesome features such as:</p>
                    <ul>
                        <li><div class="welcome-icon welcome-comments">Detailed GeaLocation</div></li>
                        <li><div class="welcome-icon welcome-comments">Results on Bars and Pie Charts</div></li>
                        <li><div class="welcome-icon welcome-comments">Export / Import all your data</div></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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
                <div class="welcome-panel-column welcome-panel-last">
                    <h4>Upgrade For More Details</h4>
                    <a class="button button-primary button-hero load-customize" href="#">
                    Upgrade To Premium</a>
                    <p>Upgrade to premium for more features and detailed information such as:</p>
                    <ul>
                        <li><div class="welcome-icon welcome-widgets-menus">Visitor's Cities</a></div></li>
                        <li><a href="#" class="welcome-icon welcome-comments">Plotted Charts</a></li>
                        <li><a href="#" class="welcome-icon welcome-learn-more">Advanced Data Exports</a></li>
                    </ul>
                </div>
            </div>
		</div><!-- welcome-panel-content -->
    </div><!-- welcome-panel -->
</div><!-- Wrap -->
