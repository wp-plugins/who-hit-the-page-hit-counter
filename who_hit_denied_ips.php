<div class="viewer">
	<h2>Denied IPs | Who Hit The Page - Hit Counter.</h2>
    <p> Please add an IP address to your deny list. All IP addresses in this list will not be counted when visiting your website. To allow an IP to be counted again, click "Allow This IP" then it will be visible in your IP list on the counters' page and to remove it from the list click "Delete This IP.</p>
<?php
	if ( isset ( $_POST['add_deny_ip'] ) ){
		whtp_add_denied_ip();
	}
	if ( isset ( $_POST['allow_ip'] ) ) {
		whtp_allow_ip();	
	}
	if ( isset ( $_POST['delete_ip'] ) ){
		whtp_delete_ip ();
	}
?>
 <form action="" method="post" id="adddeny">
    <ul id="denyip">
        <li><b>Enter IP address to add to deny list</b></li>
        <li>
            <input type="text" name="ip_address" placeholder="e.g. 127.0.0.1" />
        </li>
        <li><input type="hidden" name="add_deny_ip" value="add_deny_ip" /></li>
        <li><input type="submit" value="Add To Deny List" class="button-primary" /></li>
     </ul>
  </form>
</div>
<?php
	$hit_result	= mysql_query("SELECT * FROM whtp_hitinfo WHERE ip_status='denied'");
	
	if ( $hit_result ){
		while ( $row = mysql_fetch_array( $hit_result ) )
		{
			$total_ips += 1 ; // accumulate count $row[0];
			#echo "Total IPs : " . $total_ips . '<br />'; 
		}
		if ( $total_ips > 0 ){
			# Display visitors
			$hit_info_result = mysql_query("SELECT * FROM whtp_hitinfo WHERE ip_status='denied' ORDER BY ip_total_visits DESC");  
			if( $hit_info_result ){
			echo '<div class="viewer">' . "\n";
			echo $t1 .'<h2>Denied IP List - Not Counted IP List</h2>' . "\n";
				echo $t1 .'<table class="denied-ip-table" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				
				# table headers
				echo $t2 .'<tr>';
				echo $t3 .'<td class="title-footer"><h4>Visitor\'s  IP Address</h4></td>' . "\n";
				echo $t3 .'<td class="title-footer"><h4>Allow Count</h4></td>' . "\n";
				/*echo $t3 .'<td class="title-footer ipv-title"><h4>Reset</h4></td>' . "\n";*/
				echo $t3 .'<td class="title-footer"><h4>Delete IP</h4></td>' . "\n";
				echo $t2 .'</tr>'. "\n";
				
				# print rows from table
				while($row = mysql_fetch_array( $hit_info_result ))
				{
					// Print out the contents of each row into a table
					echo $t2 .'<tr>'. "\n";
					echo $t3 .'<td>'. $row['ip_address'] . '</td>' . "\n";
					echo $t3 .'<td>' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="allow_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="ip_address" value="' . $row['ip_address'] . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Allow This IP" class="button-primary" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t3 .'<td>' . "\n";			
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="delete_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="delete_this_ip" value="' . $row['ip_address'] . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Delete This IP" class="button" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t2 .'</tr>' . "\n";
				}
				# footer with total count			 
				echo $t2 .'<tr>' . "\n";
				echo $t3 .'<td class="title-footer" colspan="2" align="right"><h4>Total Denied IP´s </h4></td>' . "\n";
				echo $t3 .'<td class="title-footer ipv-title"><h4>' .  $total_ips . '</h4></td>' . "\n";
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
	
	# add an IP to the deny list
	function whtp_add_denied_ip(){
		
		$ip_address = stripslashes( $_POST['ip_address'] );
		
		$add_ip = mysql_query ( "INSERT INTO `whtp_hitinfo` (`ip_status` ,`ip_address`) VALUES ('denied', '$ip_address')" );
		if ( $add_ip ) {
			echo '<div class="success-msg">The IP Address "' . $ip_address . '" has been added to your deny list. This IP address will not be counted the next time it visits your website</div>';
		}else{
			echo '<div class="error-msg">Failed to Add IP Address "' . $ip_address . '" to deny list. ' . mysql_error() . '</div>';			
		}
	}
	
	# set an IP as active/allowed
	function whtp_allow_ip(){
		$ip_address = stripslashes( $_POST['ip_address'] );
		
		$allow = mysql_query( "UPDATE `whtp_hitinfo` SET `ip_status` = 'active' WHERE ip_address='$ip_address'" );
		
		if ( $allow ) {
			echo '<div class="success-msg">The IP "' . $ip_address . '" has been allowed and will now be counted the next time it visits your website.</div>';
		}else{
			echo '<div class="error-msg">Failed to Allow "' . $ip_address . '" ' .mysql_error() . ' </div>';
		}
	}
?>
<div class="viewer">
	
    <div class="updated" style="padding: 10px;width:45%; display: inline; float: left;">
    	<h4>Please Rate Who Hit The Page on WordPress.org</h4>
        <b>Dear User</b>
        <br /><br />
        Please do not forget to 
        <a href="http://plugins.wordpress.org/who-hit-the-page-hit-counter">Rate this plugin now.</a> if you appreciate it.
        Rating this plugin will help other people like you to find this plugin because on wordpress plugins are sorted by rating, so rate it high and give it a fair review to help others find it.<br /><br />
        
        <a href="http://plugins.wordpress.org/who-hit-the-page-hit-counter">Rate this plugin now.</a><br /><br />
        Sincerely<br />
        <a href="http://profiles.wordpress.org/mahlamusa">Mahlamusa</a>
    </div>

	
    <div style="width:45%; display: inline; float: right;" class="updated">
        <h4>Please Signup For Updates</h4>
        <?php
            if(isset($_POST['whtpsubscr']) && $_POST['whtpsubscr'] == "y"){
                whtp_admin_message_sender();
            }
            whtp_signup_form();
        ?>
        <p>Thank you once again!</p>
    </div>
</div>