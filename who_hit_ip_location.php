<?php
    $ip = $_POST['ip_address'];
    
    $select_country = "SELECT country_name FROM whtp_ip2location WHERE INET_ATON('" . $ip . "') 
                    BETWEEN decimal_ip_from AND decimal_ip_to LIMIT 1";
                    
    $country = mysql_query($select_country);
    $country_name = mysql_fetch_array( $country );
    echo "Country Name: " . $country_name;
    
?>