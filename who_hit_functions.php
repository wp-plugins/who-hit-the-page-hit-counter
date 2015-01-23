<?php
	function whtp_make_backup_dir (){
		$whtp_backup_dir =  WP_CONTENT_DIR . "/uploads/whtp_backups"; //always use wp_content_dir instead of _url
		if ( wp_mkdir_p ( $whtp_backup_dir ) ) {
			define( 'WHTP_BACKUP_DIR', $whtp_backup_dir );
			return true;
		}
		else{
			if ( mkdir ( $whtp_backup_dir ) ) {
				define( 'WHTP_BACKUP_DIR', $whtp_backup_dir );	
				return true;
			}
		}
		return false;
	}
	
	
	/*
	* get all IP addresses previously registered
	* get country_code for each ip and
	* add the country to the list of visiting countries
	*/ 
	function update_visiting_countries(){
		global $wpdb;
		 $ips = all_ips();
		 for ( $count = 0; $count < count ( $ips ); $count ++ ){				
			$country_code = $wpdb->get_var( "SELECT country_code FROM whtp_ip2location WHERE INET_ATON('" . $ips[$count] . "') 
                    BETWEEN decimal_ip_from AND decimal_ip_to LIMIT 1" );
			
			$exists = $wpdb->get_var( "SELECT country_code FROM whtp_visiting_countries WHERE country_code='$country_code'" );
			
			if ( $exists ) {
				if ( $exists == "" ) {
					$wpdb->query ( "INSERT INTO whtp_visiting_countries (country_code, count) VALUES ('$country_code', 1)" );
				}
				else{
					$wpdb->query ( "UPDATE whtp_visiting_countries SET count=count+1 WHERE country_code='$country_code'" );
				}
			}
		 }
	}	
	
	/*
	* Return all ip addresse
	*
	*/
	function all_ips(){
		global $wpdb;
		$all_ips = array();
		$all_ips = $wpdb->get_col ( "SELECT ip_address FROM whtp_hitinfo" );		
		
		return $all_ips;
	}
?>