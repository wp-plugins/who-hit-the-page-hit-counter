<?php

	if ( !defined ( 'WHTP_BACKUP_DIR' ) ){
		whtp_make_backup_dir ();
	}
	
	$recent_backups = array();
	/*
	*
	* Functions to import and export csv files
	*/
	function whtp_write_csv($file_name, array $list, $delimeter = ",", $enclosure = '"'){
		$handle = fopen( $file_name, "w" );		
		foreach ( $list as $fields ){
			if ( fputcsv ( $handle, $fields ) ) return true;
			else return false;
		}
		/*$out = fopen('php://output','w'); // send output to the browser_id
		fputcsv( $out, $list );
		fclose( $handle );*/
	}
	
	
	/*
	*
	*
	*/
	function whtp_export_hits( $backup_date ){
		$filename_url = WHTP_BACKUP_DIR;		
		$filename = $filename_url . "/" . $backup_date . "/whtp-hits.csv";
		
		/*echo "DIR NAME : " . $filename_url . "<br />";
		echo "DATE : " . $filename_date . "<br />";
		echo "FILE NAME : " . $filename . "<br />";*/
		
		$result = mysql_query ( "SELECT * FROM whtp_hits" );
		
		$fields = array(); // csv rows / whole document
		if ( $result ){			
			while ( $row = mysql_fetch_array ( $result )  ){
				
				$csv_row  = array();	 // new row	
				$csv_row[] = $row['page'];
				$csv_row[] = $row['count'];
				
				$fields[] = $csv_row; // append row to others
			}
			//whtp_write_csv( $filename, $fields);
			if ( whtp_write_csv( $filename, $fields) ){
				$export_url = WP_CONTENT_URL . '/uploads/whtp_backups/' . $backup_date . "/whtp-hits.csv";
				echo '<p>Page "Hits" backup successful.</p>';
			}
		}
		
		return $recent_backup = array( 'link'=>$export_url, 'filename'=>'whtp-hits' );
		
	}
	
	/*
	*
	*
	*/
	function whtp_export_hitinfo( $backup_date ){
		$filename_url = WHTP_BACKUP_DIR;		
		$filename = $filename_url . "/" . $backup_date . "/whtp-hitinfo.csv";
		
		$result = mysql_query ( "SELECT * FROM whtp_hitinfo" );
		
		$fields = array(); // csv rows / whole document
		if ( $result ){
			
			while ( $row = mysql_fetch_array ( $result )  ){
				$csv_row  = array();	 // new row
				
				$csv_row[] = $row['ip_address'];
				$csv_row[] = $row['ip_total_visits'];
				$csv_row[] = $row['user_agent'];
				$csv_row[] = $row['datetime_first_visit'];
				$csv_row[] = $row['datetime_last_visit'];
				
				$fields[] = $csv_row;	 // new row;
				
			}
			//whtp_write_csv( $filename, $fields);
			if ( whtp_write_csv( $filename, $fields) ){
				$export_url = WP_CONTENT_URL . '/uploads/whtp_backups/' . $backup_date . "/whtp-hitinfo.csv";
				echo '<p>Hitinfo backup successful.</p>';
			}	
		}
		return $recent_backup = array( 'link'=>$export_url, 'filename'=>'whtp-hitinfo' );
	}
	
	/*
	*
	*
	*/
	function whtp_export_user_agents( $backup_date ){
		$filename_url = WHTP_BACKUP_DIR;		
		$filename = $filename_url . "/" . $backup_date . "/whtp-user-agents.csv";
		
		$query = mysql_query ( "SELECT * FROM whtp_user_agents" );
		$fields = array();
		
		if ( $query ){			
			while ( $row = mysql_fetch_array ( $query )  ){
				$csv_row  = array();
				$csv_row [] = $row['agent_id'];
				$csv_row [] = $row['agent_name'];
				$csv_row [] = $row['agent_details'];
				
				$fields[] = $csv_row;
			}	
			// write to csv
			//whtp_write_csv( $filename, $fields);
			if ( whtp_write_csv( $filename, $fields) ){
				$export_url = WP_CONTENT_URL . '/uploads/whtp_backups/' . $backup_date . "/whtp-user-agents.csv";
				echo '<p>User Agents backup successful.</p>';
			}	
		}
		return $recent_backup = array( 'link'=>$export_url, 'filename'=>'whtp-user-agents' );	
	}
	
	/*
	*
	*
	*/
	function whtp_export_ip_hits( $backup_date ){
		$filename_url = WHTP_BACKUP_DIR;		
		$filename = $filename_url . "/" . $backup_date . "/whtp-ip-hits.csv";
		
		$query = mysql_query ( "SELECT * FROM whtp_ip_hits" );
		
		$fields = array();
		if ( $query ){			
			while ( $row = mysql_fetch_array ( $query )  ){
				$csv_row  = array();
				
				$csv_row[] = $row['ip_id'];
				$csv_row[] = $row['page_id'];
				$csv_row[] = $row['datetime_first_visit'];
				$csv_row[] = $row['datetime_last_visit'];
				$csv_row[] = $row['browser_id'];
				
				$fields[] = $csv_row;
			}	
			if ( whtp_write_csv( $filename, $fields) ){
				$export_url = WP_CONTENT_URL . '/uploads/whtp_backups/' . $backup_date . "/whtp-ip-hits.csv";
				echo '<p>IP Hits Table backup successful.</p>';
			}	
		}
		return $recent_backup = array( 'link'=>$export_url, 'filename'=>'whtp-ip-hits' );
	}
?>
<div class="wrap">
	<h2>Import / Export - Who Hit The Page</h2>
	<div id="welcome-panel" class="welcome-panel">
    	<div class="welcome-panel-content">
			<div class="welcome-panel-column-container">
				<!-- Import to CSV -->
				<div class="welcome-panel-column">
                	<h3>Export All Now</h3>
					<?php
						if ( !defined ('WHTP_BACKUP_DIR'  ) ){
							if ( whtp_make_backup_dir () ){
								echo '<div class="success"><p>Backup directory setup complete. Now you can backup your data and CSV files will be saved on : ' . WHTP_BACKUP_DIR . '</p></div>';	
							}
						}
						else {
							
							//make folder for this new backup
							$backup_date = date("Y-m-d") . '-' . date('H-i-s');
							wp_mkdir_p ( WHTP_BACKUP_DIR . '/' . $backup_date );
						
						
							if ( isset( $_POST["export-all"] ) ) {
								echo '<div class="success"><p>';
								// export hits table (page, count)
								$recent_backups[] = whtp_export_hits ( $backup_date );
								
								// export hitinfo table (ip_address, ip_total_count, user_agent, datetime_first_visit, atetime_last_visit)
								$recent_backups[] = whtp_export_hitinfo( $backup_date );
								
								// export user agents (agent_name, agent_details)
								$recent_backups[] = whtp_export_user_agents( $backup_date );
								
								// export ip hits
								$recent_backups[] = whtp_export_ip_hits( $backup_date );
								
								
								
								update_option('whtp_recent_backups', $recent_backups);
								
								echo '</p></div>';
							}
							
						}
                    ?>
                    <form method="post" action="" enctype="multipart/form-data" name="form1" id="form1">
                        <p>Clicking "Export All Data" will generate CSV files and save them in a folder: <br />
                        <code>&lt;site-or-blog-url&gt;wp-content/uploads/whtp_backups/&lt;YEAR-MONTH-DAY-HOUR-MINUTE-SECOND&gt;</code>
                         which you may access using an FTP client. The download links are shown on this page to download the most recent CSVs.</p>
                       
						<input name="export-all" value="export-all" type="hidden" />
                        <p><input class="button button-primary button-hero" type="submit" name="submit" value="Export All Data" /></p>
                    </form>
				</div>    
				<div class="welcome-panel-column">
                	<h3>Recent Backups</h3>
                    <?php
						$recent_backups = get_option('whtp_recent_backups');
					if ( count ( $recent_backups ) > 0 ) {
						echo "<div><p>The most recent backups are shown here, ealier backups may be found in the folder/directory <br /><code>&lt;sitename&gt;wp-content/uploads/whtp_backups</code></p>";
					?>	
                    <ul>
                    	<?php
						$num_backups = count( $recent_backups );
						if (  $num_backups > 0 ){
							for ($count = 0; $count < $num_backups ; $count ++){
								echo '<li>
											<div class="welcome-icon icon-download">
												<a href="' 
													. $recent_backups[$count]['link'] . '">
														[download] ' 
													. $recent_backups[$count]['filename'] 
												. '</a>
											</div>
									</li>';	
								
							}	
						}else{
							echo "<div><p>There are no backups recently created</p></div>";
						}
						echo "</div>";
						?>
                    </ul>
                    <?php 
					
					} else{
						echo '<p>There are no backups created. To create a backup, click "Export All Data" to start the export</p>';
					}?>
				</div>    
			</div>
        </div>
    </div>
</div>

<div class="wrap">
	<h2>Import Geo Location Data</h2>
	<div id="welcome-panel" class="welcome-panel">
    	<div class="welcome-panel-content">
			<div class="welcome-panel-column-container">
				<!-- Import to CSV -->
				<div class="welcome-panel-column">
                	<h3>Import Location Data</h3>
					<?php
						if ( isset ( $_POST['import-geo'] ) ){				
							if ( $_FILES['csv']['size'] > 0 ){
								// get the file to import
								$file = $_FILES['csv']['tmp_name'];
								echo "<p>Importing from Temp CSV : <br /><code>" . $file . "</code></p>";
								echo "<p><strong>Please Wait</strong></p>";
								//open the file
								$handle = fopen($file, "r");								
								//loop
								do {
									$row_number = 0;
									if ( $data[0] ){										
										$insert = mysql_query ( "INSERT INTO whtp_ip2location ( ip_from, ip_to, decimal_ip_from, decimal_ip_to, country_code, country_name ) VALUES
										(
											'" . addslashes ( $data[0] ) . "',
											'" . addslashes ( $data[1] ) . "',
											'" . addslashes ( $data[2] ) . "',
											'" . addslashes ( $data[3] ) . "',
											'" . addslashes ( $data[4] ) . "',
											'" . addslashes ( $data[5] ) . "'
										)" );
									}
								}
								while ( $data = fgetcsv ( $handle, 1000, ",", '"') );							
								
							}
							echo '<div class="success"><p>Import Completed Successfully</p></div>';
						}
                    ?>
                    <form method="post" action="" enctype="multipart/form-data" name="form1" id="form1">
                        <p>Choose your file to import</p>                        
                        <p><input type="file" name="csv" id="csv"  /></p>
                        <p>
                        	<input type="hidden" name="import-geo" value="import-geo" />
                        	<input class="button button-primary button-hero" type="submit" name="submit" value="Import CSV" />
                         </p>
                    </form>
               	</div>
          	</div>
        </div>
     </div>
 </div>       