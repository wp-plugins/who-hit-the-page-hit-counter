
<div class="viewer">
	<h2>Who Hit The Page - Hit Counter.</h2>
    
    <p>
    	See who visited your website below. The hits per page are shown on the first table, and the visitor's IP addresses are shown on the last table.
    </p>
    
    <p>
    	Place the following shortcode snippet in any page or post you wish visitors counted on. 
    </p>
    
    <p>
    	<code>[whohit]-Page name or identifier-[/whohit]</code> - please remember to replace the `<code>-Page name or identifier-</code>` with the name of the page you placed the shortcode on, if you like you can put in anything you want to use as an identifier for the page.
    </p>
    
    <p>
    	For example: On our <a href="http://3pxwebstudios.co.nf/about-us">about us page</a> we placed <code>[whohit]About Us[/whohit]</code> and on our <a href="http://3pxwebstudios.co.nf/web-hosting">web hosting</a> page we placed <code>[whohit]Web Hosting[/whohit]</code>. Please note that what you put between [whohit] and [/whohit] doesn\'t need to be the same as the page name - that means; for our <a href="http://3pxwebstudios.co.nf/web-design-and-development">website design and development page</a> we can use <code>[whohit]Development[whohit]</code> instead of the whole <code>[whohit]website design and development page[whohit]</code> string, its completely up to you what you put as long as you will be able to see it on your admin what page has how many visits.
    </p>
    <p>
    	Please make sure you place the shortcode <code>[whohit]..[/whohit]</code> only once in a page or post, if you place it twice, that page will be counted twice and thats not what you want. If you don\'t put anything between the inner square brackets of the shortcode, like so:<code>[whohit][/whohit]</code>, then you will have an unknown page appering with a count on the hits table and you will not know what page that is on your website.
    </p>
</div>  
   
<?php
	
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
	$result = mysql_query($query);
	if ( $result ){
		
		while( $row = mysql_fetch_array( $result ) )
		{
			$total_hits = $row['totalhits'];	
			# echo "Total hits : " . $total_hits . '<br />';		
		}		
		/*
		* Display page hit count
		*/				
		$result = mysql_query("SELECT * FROM whtp_hits ORDER BY count DESC");
		if ( $result ){
			if( $total_hits > 0 || mysql_num_rows( $result ) > 0 ){		
			echo '<div class="viewer">' . "\n";
			echo '<h2>Pages visited and number of visits per page.</h2>' . "\n";	
				echo '<table class="hits" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				echo $t1 .'<tr>' . "\n";
				echo $t2 .'<td  class="title-footer first-col-title"><h4>Page Visited</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer second-col-title"><h4>Number of Hits</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer action-col-title"><h4>Discount By (-1)</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer action-col-title"><h4>Reset</h4></td>' . "\n";
				echo $t2 .'<td  class="title-footer action-col-title"><h4>Delete</h4></td>' . "\n";
				echo $t1 .'</tr>' . "\n";
					 
				if ( $result ){
					while( $row = mysql_fetch_array( $result ) )
					{
						echo $t1 .'<tr>' . "\n";
						echo $t2 .'<td class="first-col">' . $row['page'] . '</td>' . "\n";
						echo $t2 .'<td class="second-col">' .$row['count'] . '</td>' . "\n";	
						echo $t2 .'<td class="action-col">'. "\n";						
							echo $t3 .'<form action="" method="post">'. "\n";
							echo $t4 .'<input type="hidden" name="discount_page" value="'. $row['page'] . '" />' . "\n";
							echo $t4 .'<input type="submit" name="submit" value="-1" class="button-primary" />' . "\n";
							echo $t3 .'</form>'. "\n";
						echo $t2 .'</td>'. "\n";					
						echo $t2 .'<td class="action-col">'. "\n";						
							echo $t3 .'<form action="" method="post">'. "\n";
							echo $t4 .'<input type="hidden" name="reset_page" value="'. $row['page'] . '" />' . "\n";
							echo $t4 .'<input type="submit" name="submit" value="Reset" class="button-primary" />' . "\n";
							echo $t3 .'</form>'. "\n";
						echo $t2 .'</td>'. "\n";
						echo $t2 .'<td class="action-col">'. "\n";						
							echo $t3 .'<form action="" method="post">'. "\n";
							echo $t4 .'<input type="hidden" name="delete_page" value="'. $row['page'] . '" />' . "\n";
							echo $t4 .'<input type="submit" name="submit" value="Delete" class="button" />' . "\n";
							echo $t3 .'</form>'. "\n";
						echo $t2 .'</td>'. "\n";
						echo $t1 .'</tr>'. "\n";
					}
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
		echo '<div class="viewer">' . "\n";
		whtp_reset_ip_info();
		echo '</div>' . "\n";
	}
	if ( isset( $_POST['delete_ip'] ) ) {
		echo '<div class="viewer">' . "\n";
		whtp_delete_ip ();
		echo '</div>' . "\n";
	}
	if ( isset ( $_POST['deny_ip'] ) ) {
		echo '<div class="viewer">' . "\n";
		whtp_deny_ip();	
		echo '</div>' . "\n";	
	}
	$hit_result	= mysql_query("SELECT * FROM whtp_hitinfo WHERE ip_status='active'");
	
	if ( $hit_result ){
		while ( $row = mysql_fetch_array( $hit_result ) )
		{
			$total_ips += 1 ; // accumulate count $row[0];
			#echo "Total IPs : " . $total_ips . '<br />'; 
		}
		if ( $total_ips > 0 ){
			# Display visitors
			$hit_info_result = mysql_query("SELECT * FROM whtp_hitinfo WHERE ip_status='active' ORDER BY ip_total_visits DESC");  
			if( $hit_info_result ){	
			echo '<div class="viewer">' . "\n";			
			echo $t1 .'<h2> Visitors\' IP addresses and Information</h2>' . "\n";
				echo $t1 .'<table class="ip-table" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
				
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
				while($row = mysql_fetch_array( $hit_info_result ))
				{
					// Print out the contents of each row into a table
					echo $t2 .'<tr>'. "\n";
					echo $t3 .'<td class="ip">'. $row['ip_address'] . '</td>' . "\n";
					echo $t3 .'<td class="ipv">' . $row['ip_total_visits'] . '</td>' . "\n";		
					echo $t3 .'<td class="agent">'. $row['user_agent'] . '</td>' . "\n";
					echo $t3 .'<td class="ftime">'. $row['datetime_first_visit'] . '</td>' . "\n";
					echo $t3 .'<td class="ltime">'. $row['datetime_last_visit'] . '</td>' . "\n";
					echo $t3 .'<td class="ipv">' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="deny_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="ip_address" value="' . $row['ip_address'] . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Deny" class="button-primary" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t3 .'<td class="ipv">' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="reset_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="ip_address" value="' . $row['ip_address'] . '" />' . "\n";
						echo $t5 .'<input type="submit" name="submit" value="Reset" class="button-primary" />' . "\n";
						echo $t4 .'</form>' . "\n";
					echo $t3 .'</td>' . "\n";
					echo $t3 .'<td class="ipv">' . "\n";					
						echo $t4 .'<form action="" method="post">' . "\n";
						echo $t5 .'<input type="hidden" name="delete_ip" value="this_ip" />' . "\n";
						echo $t5 .'<input type="hidden" name="delete_this_ip" value="' . $row['ip_address'] . '" />' . "\n";
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
<div class="viewer">
    <h2> Help and support</h2>
    <ul class="help">
        <li> 
            <h5>Please Rate Who Hit The Page on WordPress</h5>
            <p>
            Please do not forget to rate this plugin if you love it. 
            <a href="http://plugins.wordpress.org/who-hit-the-page-hit-counter">Rate this plugin now.</a> 
            Rating this plugin will help other people like you to find this plugin because on wordpress plugins are sorted by rating, so rate it high and give it a fair review to help others find it.
            </p>
            <p><a href="http://plugins.wordpress.org/who-hit-the-page-hit-counter">Rate this plugin now.</a></p>
            <strong>Optional: </strong>	Please link to our website if you like our plugin, we really appreciate your kind gesture. Visit our website at <?php echo whtp_link_bank(); ?>
            <br />
            <br />
            Please copy and paste this: <code>[whohit]Page name or identifier[/whohit]</code> on any page or post in visual view to display the link shown above.<br /><br />
            Or you can copy and paste the following link on your pages, posts or theme files to display a link to our website
            <textarea readonly="readonly"><?php echo whtp_link_bank(); ?></textarea>
            <br />
            
        </li>
        
        <!--  author contact details -->
        <li>	
            <ul class="author">
                <li><b>Author's website</b></li>
                <li>
                    <a href="http://3pxwebstudios.co.nf" title="Worpress plugins author" target="_blank">
                        3pxwebstudios.co.nf
                    </a>
                </li>
                
                <li><b>Plugin's documentation</b></li>
                <li>
                    <a href="http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter" title="Multi Purpose Mail Form wordpress plugin" target="_blank">
                        http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter
                    </a>
                </li>
                
                <li><b>Report bugs/ request features</b></li>
                <li>
                    <a href="mailto:3pxwebstudios@gmail.com" title="email address to report plugin's bugs or errors" target="_blank">
                        3pxwebstudios@gmail.com
                    </a>
                </li>
                
                <li><b>Contact phone number</b><br /><small>(Only from Monday to Friday )</small></li>
                <li>
                    27 76 706 4015 (ZA)
                </li>
            </ul>
        </li>
        <!-- subscription form -->
        <li>
            <?php
                if(isset($_POST['whtpsubscr']) && $_POST['whtpsubscr'] == "y"){
                    whtp_admin_message_sender();
                }
            ?>
            <?php				
           		whtp_signup_form();
            ?>               
        </li>
    </ul>
</div>