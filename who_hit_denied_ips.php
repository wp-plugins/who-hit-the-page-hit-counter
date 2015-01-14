<div class="wrap">	
	<h2>Who Hit The Page Hit Counter</h2>
    <p></p>
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
                    	<h3 class="hndle">Add Denied IP</h3>
    					<div class="inside">
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
                            <ul id="denyip" style="height:100px;">
                                <li><b>Enter IP address to add to deny list</b></li>
                                <li>
                                    <input style="width:50%;display:block;float:left;padding:14px;border:1px solid #06F;" type="text" name="ip_address" placeholder="e.g. 127.0.0.1" />
                                </li>
                                <li><input type="hidden" name="add_deny_ip" value="add_deny_ip" /></li>
                                <li><input type="submit" value="Add To Deny List" class="button button-hero button-primary" /></li>
                             </ul>
                          </form>
                      </div>
                      <br />
                      <br />
                 </div>
                 <div class="postbox inside">
					<div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle">Currently denied IP Addresses</h3>   
                    <div class="inside">
                    	<p>Here is a list of currently denied IP addresses</p>
                        <p>&nbsp;</p>
						<?php
                            global $wpdb;
                            
                            # Output format TABS
                            $t1 = "\t";
                            $t2 = "\t" ."\t";
                            $t3 = "\t" . "\t" ."\t";
                            $t4 = $t2 . $t2;
                            $t5 = $t3 . $t2;
                            $t6 = $t3 . $t3;
                            $total_ips	= $wpdb->get_var("SELECT COUNT(ip_address) FROM whtp_hitinfo WHERE ip_status='denied'");
                            
                            if ( $total_ips ){ // accumulate count $row[0];
                                if ( $total_ips > 0 ){
                                    # Display visitors
                                    $hit_info_result = $wpdb->get_results("SELECT * FROM whtp_hitinfo WHERE ip_status='denied' ORDER BY ip_total_visits DESC");  
                                    if( $hit_info_result ){
                                        echo $t1 .'<table class="denied-ip-table" cellspacing="0" cellpadding="5px" width="98%">' . "\n";
                                        
                                        # table headers
                                        echo $t2 .'<tr>';
                                        echo $t3 .'<td class="title-footer"><h4>Visitor\'s  IP Address</h4></td>' . "\n";
                                        echo $t3 .'<td class="title-footer"><h4>Allow Count</h4></td>' . "\n";
                                        /*echo $t3 .'<td class="title-footer ipv-title"><h4>Reset</h4></td>' . "\n";*/
                                        echo $t3 .'<td class="title-footer"><h4>Delete IP</h4></td>' . "\n";
                                        echo $t2 .'</tr>'. "\n";
                                        
                                        # print rows from table
                                        foreach( $hit_info_result as $row)
                                        {
                                            // Print out the contents of each row into a table
                                            echo $t2 .'<tr>'. "\n";
                                            echo $t3 .'<td>'. $row->ip_address . '</td>' . "\n";
                                            echo $t3 .'<td>' . "\n";					
                                                echo $t4 .'<form action="" method="post">' . "\n";
                                                echo $t5 .'<input type="hidden" name="allow_ip" value="this_ip" />' . "\n";
                                                echo $t5 .'<input type="hidden" name="ip_address" value="' . $row->ip_address . '" />' . "\n";
                                                echo $t5 .'<input type="submit" name="submit" value="Allow This IP" class="button-primary" />' . "\n";
                                                echo $t4 .'</form>' . "\n";
                                            echo $t3 .'</td>' . "\n";
                                            echo $t3 .'<td>' . "\n";			
                                                echo $t4 .'<form action="" method="post">' . "\n";
                                                echo $t5 .'<input type="hidden" name="delete_ip" value="this_ip" />' . "\n";
                                                echo $t5 .'<input type="hidden" name="delete_this_ip" value="' . $row->ip_address . '" />' . "\n";
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
                                    echo '<div class="wrap">' . "\n";
                                    echo '<h4 class="not-found">There are currently no registered IP addresses, please read above to get started</h4>' . "\n";
                                    echo '</div>' . "\n";
                                }
                            }
						?>
                    </div>
                 </div>                      
             </div>
    </div>
</div><!-- Wrap -->                  
                            
                            
<?php	
	# add an IP to the deny list
	function whtp_add_denied_ip(){
		global $wpdb;
		
		$ip_address = stripslashes( $_POST['ip_address'] );
		
		$add_ip = $wpdb->insert ("whtp_hitinfo", array("ip_status"=>"denied" ,"ip_address"=>$ip_address), array("%s", "%s", "%s") );
		if ( $add_ip ) {
			echo '<div class="wrap"><div class="updated"><p>The IP Address "' . $ip_address . '" has been added to your deny list. This IP address will not be counted the next time it visits your website</p></div></div>';
		}else{
			echo '<div class="wrap"><div class="warning error-msg error"><p>Failed to Add IP Address "' . $ip_address . '" to deny list. </p></div></div>';			
		}
	}
	
	# set an IP as active/allowed
	function whtp_allow_ip(){
		global $wpdb;
		$ip_address = stripslashes( $_POST['ip_address'] );
		
		$allow = $wpdb->update("whtp_hitinfo", array("ip_status"=>"active"), array("ip_address"=>$ip_address), array("%s"), array("%s") );
		
		if ( $allow ) {
			echo '<div class="wrap"><div class="updated"><p>The IP "' . $ip_address . '" has been allowed and will now be counted the next time it visits your website.</p></div></div>';
		}else{
			echo '<div class="error-msg warning error not-updated"><p>Failed to Allow "' . $ip_address . '"</p></div>';
		}
	}
?>
