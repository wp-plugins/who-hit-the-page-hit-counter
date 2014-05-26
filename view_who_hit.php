<div class="wrap">
	<h1>Who Hit The Page - Hit Counter</h1>
	<p>Here you will see the raw hit counter information. This page lists all your pages and their respective counts and also the visiting IP addresses with their respective counts and other information.</p>
</div>
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
		echo '<div class="viewer">';
		whtp_reset_page_count();
		echo '</div>';
	}
	if ( isset( $_POST['delete_page'] ) ) {
		echo '<div class="viewer">';
		whtp_delete_page();
		echo '</div>';
	}
	if ( isset( $_POST['discount_page'] ) ) {
		echo '<div class="viewer">';
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
			echo '<div class="viewer wrap">' . "\n";
			echo '<h2>Pages visited and number of visits per page.</h2>' . "\n";	
				echo '<table class="hits" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				echo $t1 .'<tr>' . "\n";
				echo $t2 .'<td  class="title-footer first-col-title"><h4>Page Visited</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer second-col-title"><h4>Number of Hits</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer action-col-title"><h4>Discount By (-1)</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer action-col-title"><h4>Reset</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer action-col-title"><h4>Delete</h4></td>' . "\n";
				echo $t1 .'</tr>' . "\n";
					 
				foreach($hits as  $row  )
				{
					echo $t1 .'<tr>' . "\n";
					echo $t2 .'<td class="first-col">' . $row->page . '</td>' . "\n";
					echo $t2 .'<td class="second-col">' .$row->count. '</td>' . "\n";	
					echo $t2 .'<td class="action-col">'. "\n";						
						echo $t3 .'<form action="" method="post">'. "\n";
						echo $t4 .'<input type="hidden" name="discount_page" value="'. $row->page . '" />' . "\n";
						echo $t4 .'<input type="number" name="discountby" value="1" />' . "\n";
						echo $t4 .'<input type="submit" name="submit" value="Discount" class="button-primary" />' . "\n";
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
				echo '</div>' . "\n";
			}
			else{
				echo '<div class="viewer">' . "\n";
				echo '<h4 class="not-found">No Page visits yet or the page counters have been reset. Read documentation to learn how to get started.</h4>' . "\n";
				echo '</div>' . "\n";
			}
		}
	}
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
			echo '<div class="viewer wrap">' . "\n";			
			echo $t1 .'<h2> Visitors\' IP addresses and Information</h2>' . "\n";
				echo $t1 .'<table class="ip-table table" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				
				# table headers
				echo $t2 .'<tr>';
				echo $t3 .'<td class="title-footer ip-title"><h4>Visitor\'s  IP Address</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title"><h4>Visits</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer agent-title"><h4>User Agent</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ftime-title"><h4>1st Visit</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ltime-title"><h4>Last Visit</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title"><h4>Deny / MisCount</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title"><h4>Reset</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title"><h4>Delete</h4></td>' . "\n";
				echo $t2 .'</tr>'. "\n";
			 
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
				echo $t1 .'</div>' . "\n";
			}
		}
		else{
			# there are currently no visitors 
			echo '<div class="viewer">' . "\n";
			echo '<h4 class="not-found">There are currently no registered IP addresses, please read above to get started</h4>' . "\n";	
			echo '</div>' . "\n";
		}
	}
	
	
	/*
	* Help and support links
	* and subscription form
	*/
	
	
	# get started
	
?>
<?php
//end if is admin
}
?>
