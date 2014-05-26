<?php
	if(!update_option("whtp_installed","false")){ 
		add_option("whtp_installed","false","","yes");
	}
	
	if ( get_option("whtp_data_action") == "delete-all"){
		whtp_un_install();
	}
	elseif( get_option("whtp_data_action") == "clear-tables"){
		whtp_empty_all();
	}
	/*
	else{ // if (get_option("whtp_data_action") == "do-nothing")
		#do nothing 
	}
	*/
		
	
	function whtp_empty_all(){
		mysql_query( "TRUNCATE `whtp_hits`" );	
		mysql_query( "TRUNCATE `whtp_hitinfo`" );
		mysql_query( "TRUNCATE `whtp_user_agents`" );
		mysql_query( "TRUNCATE `whtp_ip2location`" );
		mysql_query( "TRUNCATE `whtp_visiting_countries`" );
		mysql_query( "TRUNCATE `whtp_ip_hits`" );
	}
	
	
	function whtp_un_install(){
		mysql_query( "DROP TABLE whtp_hits" );
		mysql_query( "DROP TABLE whtp_hitinfo" );
		mysql_query( "DROP TABLE whtp_user_agents" );
		mysql_query( "DROP TABLE whtp_ip2location" );
		mysql_query( "DROP TABLE whtp_visiting_countries" );
		mysql_query( "DROP TABLE whtp_ip_hits" );
	}
?>