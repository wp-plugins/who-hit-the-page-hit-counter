<style type="text/css">
<?php include("style_who_hit.css"); ?>
</style>
<?php
include('who_hit_processor.php');
?>
<?php
	


	echo '<h2>Who Hit The Page - Hit Counter.</h2>';
	echo 'See who visited your website below. The hits per page are shown on the first table, and the visitor\'s IP addresses are shown on the last table.';
	echo 'Place the following shortcode snippet in any page or post you wish visitors counted on. <br /><br />';
	echo '<code>[whohit]-Page name or identifier-[/whohit]</code> - please remember to replace the `<code>-Page name or identifier-</code>` with the name of the page you placed the shortcode on, if you like you can put in anything you want to use as an identifier for the page.<br /><br /> <br />';
	echo 'For example: On our <a href="http://3pxwebstudios.co.nf/about-us">about us page</a> we placed <code>[whohit]About Us[/whohit]</code>';
	echo 'and on our <a href="http://3pxwebstudios.co.nf/web-hosting">web hosting</a> page we placed <code>[whohit]Web Hosting[/whohit]</code>. Please note that what you put between [whohit] and [/whohit] doesn\'t need to be the same as the page name - that means;'; 
	echo 'for our <a href="http://3pxwebstudios.co.nf/web-design-and-development">website design and development page</a> we can use';
	echo ' <code>[whohit]Development[whohit]</code> instead of the whole <code>[whohit]website design and development page[whohit]</code> string, its completely up to you what you put as long as you will be able to see it on your admin what page has how many visits.';

	
	
	
	/*
	* Get the total hits
	*/
	$query = "SELECT SUM(count)  AS totalhits FROM hits";
	 
	$result = mysql_query($query);

	if ( $result ){
		while($row = mysql_fetch_array($result))
		{
			$total_hits = $row['totalhits'];			
		}
		if( $total_hits > 0 ){
			/*
			* Display page hit count
			*/	
			echo '<h2>Pages visited and number of visits per page.</h2>';		
			$result = mysql_query("SELECT * FROM hits ORDER BY count DESC");	
			if ( $result ){
				echo '<div class="view">';
				echo '<ul class="hits">';
				echo '<li class="title-footer"><h4>Page Visited</h4></li>
					<li class="title-footer"><h4>Number of Hits</h4></li>';
					
				if ( $result ){
					while($row = mysql_fetch_array( $result ))
					{
						echo '<li class="even">' . $row['page'] . '</li>';
						echo '<li class="odd">'.$row['count'] . '</li>'; 
					}
				}
				echo '<li class="title-footer"><h4> Total Hits </h4></li><li class="title-footer"> <h4>' . $total_hits . '</h4> </li>';
				echo '</ul>';
				echo '</div>';
			}
		}else{
			echo '<h4 class="not-found">No Page visits yet. Read documentation to learn how to get started.</h4>';
		}
	}
	/*
	* get total unique IP addresses
	* and print on table
	*/
	
	$result	=	mysql_query("SELECT MAX(id) FROM hitinfo");
	
	if ( $result ){
		while ($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			$total_ips = $row[0];  
		}
		if ($total_ips > 0){
							
			# Display visitors 					
			echo '<h2> Visitors </h2>'; 
				
			$result = mysql_query("SELECT * FROM hitinfo ORDER BY id DESC");  
			if($result){	
				echo '<div class="viewer">';
				echo '<table class="ip-table" cellspacing="0" cellpadding="5px" width="100%">';
				
				# table headers
				echo '<tr>';
				echo '<td class="title-footer ip-title"><h4>Visitor\'s  IP Address</h4></td>';
				echo '<td class="title-footer agent-title"><h4>User Agent</h4></td>';
				echo '<td class="title-footer time-title"><h4>Date &amp; Time</h4></td>';
				echo '</tr>';
			 
				# print rows from table
				while($row = mysql_fetch_array( $result ))
				{
					// Print out the contents of each row into a table
					echo '<tr>';
					echo '<td class="ip">' . $row['ip_address'] . '</td>';		
					echo '<td class="agent">'. $row['user_agent'] . '</td>';
					echo '<td class="time">'. $row['datetime']. '</td>';
					echo '</tr>';
				}
				# footer with total count			 
				echo '<tr>';
				echo '<td class="title-footer ip-title"><h4>Total unique IP´s </h4></td>';
				echo '<td class="title-footer agent-title"><h4>' .  $total_ips . '</h4></td>';
				echo '<td class="title-footer time-title">&nbsp;</td>';
				echo '</tr>';
				echo '</table>';
				echo '</div>';
			}
		}else{
			# there are currently no visitors 
			echo '<h4 class="not-found">There are currently no registered visits on your site, please read above to get started</h4>';
		}
	}
	/*
	* Help and support links
	* and subscription form
	*/
	
	
	# get started
	echo '<div class="viewer">';
	echo '<h2> Help and support</h2>';
	echo '<ul class="hits">';
	echo '<li><strong>Optional: </strong>';
	echo 'Please link to our website if you like our plugin, we really appreciate your kind gesture. Visi our website ' . whtp_link_bank();
	echo '<br /><br />';
	echo 'Please copy and paste this: <code>[whohit]Page name or identifier[/whohit]</code> on any page or post in visual view to display the link shown above.<br /><br />';
	echo 'Or you can copy and paste the following link on your pages, posts or theme files to display a link to our website.';
	echo '<textarea readonly="readonly">' . whtp_link_bank() . '</textarea>';
	echo '</li>';
	
	# author contact details
	echo '<li>';	
	echo '<ul class="author">
				<li>Author\'s website</li>
				<li>
					<a href="http://3pxwebstudios.co.nf" title="Worpress plugins author" target="_blank">
						3pxwebstudios.co.nf
					</a>
				</li>
				
				<li>Plugin\'s documentation</li>
				<li>
					<a href="http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter" title="Multi Purpose Mail Form wordpress plugin" target="_blank">
						http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter
					</a>
				</li>
				<li>Report errors/bugs to</li>
				<li>
					<a href="mailto:3pxwebstudios@gmail.com" title="email address to report plugin\'s bugs or errors" target="_blank">
						3pxwebstudios@gmail.com
					</a>
				</li>
				<li>Contact phone number</li>
				<li>
					+27 76 706 4015 (ZA)
				</li>
			</ul>';
			
	echo '</li>';
	
	# subscription form
	echo '<li>';
	echo '<form action="" method="post">
			<input type="hidden" name="whtpsubscr" value="y" />
			<label for="asubscribe_email">Enter your email address to subscribe to updates</label>
			<input type="email" placeholder="e.g. you@your-domain.com" name="asubscribe_email" /><br />
			<input type="submit" value="Subscribe to updates" />
		</form>';
	echo '</li>';
	echo '</ul>';
	echo '</div>';
?> 