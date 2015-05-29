<div class="wrap">	
	<h2>Who Hit The Page Hit Counter</h2>
    <p>Here you will see the raw hit counter information. This page lists all your pages and their respective counts and also the visiting IP addresses with their respective counts and other information.</p>
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
                    <h3 class="hndle">Need More</h3>
                    <div class="inside welcome-panel-column welcome-panel-last">
                        <h4>Display a hit counter widget on any page or post. Its easy to change the colors and the font sizes for the numbers.</h4>
                        <a href="http://shop.whohit.co.za/" target="_blank">
                        	<img src="<?php echo WHTP_IMAGES_URL . 'widget_get.png'; ?>" alt="Get front end widget for who hit the page" />
                        </a>
                        <a href="http://shop.whohit.co.za/" class="button button-primary button-hero" style="width:100%; text-align:center;" target="_blank">Download Now</a>
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
                        <div class="welcome-panel-column">
                            <h3>Credits Where They Belong</h3>
                            <div>
                                This product includes GeoLite2 data created by MaxMind, available from
                                <a href="http://www.maxmind.com">http://www.maxmind.com</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="post-body">
            <div id="post-body-content">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox inside">
                    	<div class="handlediv" title="Click to toggle"><br /></div>
                    	<h3 class="hndle">Pages visited and number of visits per page.</h3>
                        <div class="inside">
<?php
//important
if (is_admin() ) {
	?>  
<?php
	
	global $wpdb, $table_prefix;
	$wpdb->show_errors();
	# Output format TABS
	$t1 = "\t";
	$t2 = "\t" ."\t";
	$t3 = "\t" . "\t" ."\t";
	$t4 = $t2 . $t2;
	$t5 = $t3 . $t2;
	$t6 = $t3 . $t3;
	# check if there is an action to reset counters
	if ( isset ( $_POST['reset_page'] ) ){
		echo '<div class="viewer wrap">';
		whtp_reset_page_count();
		echo '</div>';
	}
	if ( isset( $_POST['delete_page'] ) ) {
		echo '<div class="viewer wrap">';
		whtp_delete_page();
		echo '</div>';
	}
	if ( isset( $_POST['discount_page'] ) ) {
		echo '<div class="viewer wrap">';
		whtp_discount_page();
		echo '</div>';
	}
	
	/*
	* Get the total hits
	*/
	$query = "SELECT SUM(count) AS totalhits FROM whtp_hits";	 
	$total_hits = $wpdb->get_var( $query );
	if ( $total_hits > 0 ){
		# echo "Total hits : " . $total_hits . '<br />';
		/*
		* Display page hit count
		*/				
		$hits = $wpdb->get_results("SELECT * FROM whtp_hits ORDER BY count DESC");
		$count = count( $hits );
		if ( $count  > 0 ){
			if( $total_hits > 0 ||$count > 0 ){		
			echo '<div class="wrap">
					<div id="welcome-panel" class="welcome-panel">' . "\n";
			echo '' . "\n";	
			//table class=hits
				echo '<table class="hits widefat fixed" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				echo $t1 .'<thead>' . "\n";
				echo $t2 .'<th  class="title-footer first-col-title">Page Visited</th>' . "\n";
				echo $t2 .'<th  class="title-footer second-col-title">Number of Hits</th>' . "\n";
				echo $t2 .'<th  class="title-footer action-col-title">Discount By</th>' . "\n";
				echo $t2 .'<th  class="title-footer action-col-title">Reset</th>' . "\n";
				echo $t2 .'<th  class="title-footer action-col-title">Delete</th>' . "\n";
				echo $t1 .'</thead>' . "\n";
					 
				foreach($hits as  $row  )
				{
					echo $t1 .'<tr>' . "\n";
					echo $t2 .'<td class="first-col">' . $row->page . '</td>' . "\n";
					echo $t2 .'<td class="second-col">' .$row->count. '</td>' . "\n";	
					echo $t2 .'<td class="action-col discount">'. "\n";						
						echo $t3 .'<form action="" method="post">'. "\n";
						echo $t4 .'<input type="hidden" name="discount_page" value="'. $row->page . '" />' . "\n";
						echo $t4 .'<input type="number" name="discountby" value="1" />' . "\n";
						echo $t4 .'<input type="submit" name="submit" value="--" class="button-primary" />' . "\n";
						echo $t3 .'</form>'. "\n";
					echo $t2 .'</td>'. "\n";					
					echo $t2 .'<td class="action-col">'. "\n";						
						echo $t3 .'<form action="" method="post">'. "\n";
						echo $t4 .'<input type="hidden" name="reset_page" value="'. $row->page . '" />' . "\n";
						echo $t4 .'<input type="submit" name="submit" value="Reset" class="button-primary" />' . "\n";
						echo $t3 .'</form>'. "\n";
					echo $t2 .'</td>'. "\n";
					echo $t2 .'<td class="action-col">'. "\n";						
						echo $t3 .'<form action="" method="post">'. "\n";
						echo $t4 .'<input type="hidden" name="delete_page" value="'. $row->page . '" />' . "\n";
						echo $t4 .'<input type="submit" name="submit" value="Delete" class="button" />' . "\n";
						echo $t3 .'</form>'. "\n";
					echo $t2 .'</td>'. "\n";
					echo $t1 .'</tr>'. "\n";
				}
				echo $t1 .'<tr>' . "\n";
				echo $t2 .'<td class="title-footer first-col-title"><h4>Total Hits</h4></td>' . "\n";
				echo $t2 .'<td class="title-footer second-col-title"><h4>' . $total_hits . '</h4></td>' . "\n";
				echo $t2 .'<td class="action-col-title title-footer" bgcolor="#FF0000" colspan="3">&nbsp;' . "\n";
				echo $t2 .'</td>' . "\n";
				echo $t1 .'</tr>' . "\n";
				echo $t1 .'<tr>' . "\n";
				echo $t2 .'<td class="title-footer" colspan="3"><h4>&nbsp;</h4></td>';
				echo $t2 .'<td class="action-col-title title-footer">'. "\n";
					echo $t3 .'<form action="" method="post">' . "\n";
					echo $t4 .'<input type="hidden" name="reset_page" value="all" />' . "\n";
					echo $t4 .'<input type="submit" name="submit" value="Reset All" class="button-primary" />' . "\n";
					echo $t3 .'</form>' . "\n";	
				echo $t2 .'</td>' . "\n";
				echo $t2 .'<td class="action-col-title title-footer">' . "\n";
					echo $t3 .'<form action="" method="post">' . "\n";
					echo $t4 .'<input type="hidden" name="delete_page" value="all" />' . "\n";
					echo $t4 .'<input type="submit" name="submit" value="Delete All" class="button" />' . "\n";
					echo $t3 .'</form>' . "\n";
				echo $t2 .'</td>' . "\n";
				echo $t1 .'</tr>' . "\n";
				echo '</table>' . "\n";
				echo '</div></div>' . "\n";
			}
			else{
				?>
				<div id="welcome-panel" class="welcome-panel">
                    <h4 class="handle">No Data Found</h4>
                    <p>No Page visits yet or the page counters have been reset. Read documentation to learn how to get started</p>
                </div>          
                <?php
			}
		}
	}
	?>
    
    					</div>
                    </div>
					<div class="postbox inside">
                    	<h3 class="handle"><h2>Visitors' IP addresses and Information</h2></h3>
                        <div class="inside">
                        
    <?php
	/*
	* get total unique IP addresses
	* and print on table
	*/
	
	//$result	= mysql_query("SELECT MAX(id) FROM hitinfo");
	
	if ( isset( $_POST['reset_ip'] ) ) {
		echo '<div class="viewer wrap">' . "\n";
		whtp_reset_ip_info();
		echo '</div>' . "\n";
	}
	if ( isset( $_POST['delete_ip'] ) ) {
		echo '<div class="viewer wrap">' . "\n";
		whtp_delete_ip ();
		echo '</div>' . "\n";
	}
	if ( isset ( $_POST['deny_ip'] ) ) {
		echo '<div class="viewer wrap">' . "\n";
		whtp_deny_ip();	
		echo '</div>' . "\n";	
	}
	if (isset ($_POST['view_visitor'] ) ){
		//view visitor details	
		echo '<div class="viewer wrap">' . "\n";
		echo "<h1>Visitor Data Goes Here for $view_visitor</h1>";
		echo '</div>' . "\n";
	}
	
	
	$hit_result	= $wpdb->get_results("SELECT * FROM whtp_hitinfo WHERE ip_status='active'");
	$ips = $wpdb->get_var("SELECT COUNT(ip_address) FROM whtp_hitinfo WHERE ip_status='active'");
	if ( $ips > 0 ){
		$total_ips = $ips;
		
		if ( $total_ips > 0 ){
			# Display visitors
			$hit_info = $wpdb->get_results("SELECT * FROM whtp_hitinfo WHERE ip_status='active' ORDER BY ip_total_visits DESC");  
			$count_info = count ( $hit_info );
			if( $count_info > 0 ){	
			echo '<div class="wrap">
						<div id="welcome-panel" class="welcome-panel">' . "\n";		
				//table class="ip-table
				echo $t1 .'<table class="ip-table widefat fixed table" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				
				# table headers
				# td class="title-footer +
				echo $t2 .'<thead>';
				echo $t3 .'<th class="title-footer ip-title">Visitor\'s  IP</th>' . "\n";
				echo $t3 .'<th class="title-footer ipv-title">Visits</th>' . "\n";
				echo $t3 .'<th class="title-footer agent-title">User Agent</th>' . "\n";
				echo $t3 .'<th class="title-footer ftime-title">1st Visit</th>' . "\n";
				echo $t3 .'<th class="title-footer ltime-title">Last Visit</th>' . "\n";
				echo $t3 .'<th class="title-footer ipv-title">Don\'t Count</th>' . "\n";
				echo $t3 .'<th class="title-footer ipv-title">Reset</th>' . "\n";
				echo $t3 .'<th class="title-footer ipv-title">Delete</th>' . "\n";
				echo $t2 .'</thead>'. "\n";
			 
				# print rows from table
				
				foreach($hit_info as $row )
				{
					// Print out the contents of each row into a table
					echo $t2 .'<tr>'. "\n";
					echo $t3 .'<td class="ip"><a href="admin.php?page=whtp-visitor-stats&ip='
					. $row->ip_address . '">'
					. $row->ip_address . '</a>
					</td>' . "\n";
					echo $t3 .'<td class="ipv">' . $row->ip_total_visits . '</td>' . "\n";		
					echo $t3 .'<td class="agent">'. $row->user_agent . '</td>' . "\n";
					echo $t3 .'<td class="ftime">'. $row->datetime_first_visit . '</td>' . "\n";
					echo $t3 .'<td class="ltime">'. $row->datetime_last_visit . '</td>' . "\n";
					echo $t3 .'<td class="ipv">' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="deny_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="ip_address" value="' . $row->ip_address . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Deny" class="button-primary" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t3 .'<td class="ipv">' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="reset_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="ip_address" value="' . $row->ip_address . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Reset" class="button-primary" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t3 .'<td class="ipv">' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="delete_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="delete_this_ip" value="' . $row->ip_address . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Delete" class="button" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t2 .'</tr>' . "\n";
				}
				# footer with total count			 
				echo $t2 .'<tr>' . "\n";
				echo $t3 .'<td class="title-footer" colspan="7" align="right"><h4>Total unique IP´s </h4></td>' . "\n";
				/*echo $t3 .'<td class="title-footer ftime-title">&nbsp;</td>' . "\n";
				echo $t3 .'<td class="title-footer ltime-title"></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title"><h4>&nbsp;</h4></td>' . "\n";*/
				echo $t3 .'<td class="title-footer ipv-title"><h4>' .  $total_ips . '</h4></td>' . "\n";
				echo $t2 .'</tr>' . "\n";
				echo $t2 .'<tr>' . "\n";
				echo $t3 .'<td class="title-footer" colspan="4"></td>' . "\n";
				
				echo $t3 .'<td class="title-footer ltime-title"></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title">' . "\n";
					echo $t4 .'<form action="" method="post">' . "\n";
					echo $t5 .'<input type="hidden" name="reset_ip" value="all" />' . "\n";
					echo $t5 .'<input type="submit" name="submit" value="Reset All" class="button-primary" />' . "\n";
					echo $t4 .'</form>' . "\n";
				echo $t3 .'</td>' . "\n";
				echo $t2 .'<td class="title-footer ftime-title">' . "\n";
					echo $t4 .'<form action="" method="post">' . "\n";
					echo $t5 .'<input type="hidden" name="delete_ip" value="all" />' . "\n";
					echo $t5 .'<input type="submit" name="submit" value="Delete All" class="button" />' . "\n";
					echo $t4 .'</form>' . "\n";
				echo $t3 .'</td>' . "\n";				
				echo $t3 .'<td class="title-footer ipv-title"><h4>&nbsp;</h4></td>' . "\n";
				echo $t2 .'</tr>' . "\n";
				echo $t1 .'</table>' . "\n";
				echo $t1 .'</div></div>' . "\n";
			}
		}
		else{
			# there are currently no visitors 
			?>
            <div id="welcome-panel" class="welcome-panel">
                <h4 class="not-found">There are currently no registered IP addresses, please read above to get started</h4>
            </div>
            <?php
		}
	}	
?>
<?php
//end if is admin
}
?>
			</div>
        </div>
    </div>
</div><!-- Wrap -->