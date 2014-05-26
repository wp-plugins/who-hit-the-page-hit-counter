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
		 $ips = all_ips();
		 for ( $count = 0; $count < count ( $ips ); $count ++ ){
			$country = "SELECT country_code FROM whtp_ip2location WHERE INET_ATON('" . $ips[$count] . "') 
                    BETWEEN decimal_ip_from AND decimal_ip_to LIMIT 1"; 
				
			$country_code = mysql_fetch_array( $country );
			$code  = $country_code['country_code'];
			
			$exists = mysql_query ( "SELECT country_code FROM whtp_visiting_countries WHERE country_code='$code'" );
			
			if ( $exists ) {
				if ( mysql_num_rows ( $exists ) == 0 ) {
					mysql_query ( "INSERT INTO whtp_visiting_countries (country_code, count) VALUES ('$code', 1)" );
				}
				else{
					mysql_query ( "UPDATE whtp_visiting_countries SET count=count+1 WHERE country_code='$code'" );
				}
			}
		 }
	}	
	
	/*
	* Return all ip addresse
	*
	*/
	function all_ips(){
		$result = mysql_query ( "SELECT ip_address FROM whtp_hitinfo");
		
		$all_ips = array();
		if ( $result ) {
			while ( $row = mysql_fetch_row ( $result ) ){
				$all_ips = $row['ip_address'];	
			}
		}
		return $all_ips;
	}
?>