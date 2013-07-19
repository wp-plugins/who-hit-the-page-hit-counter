<?php
		
	/*
	* subscribe to plugin development
	* subscription sent from current admin, forward to developer's email address
	* Developer's email address hard coded below
	*/
	if(isset($_POST['whtpsubscr']) && $_POST['whtpsubscr'] == "y"){
		whtp_admin_message_sender();
	}
	function whtp_admin_message_sender(){
		$s_email = stripslashes($_POST['asubscribe_email']);
		if($s_email != ""){
			$s_email = $s_email;
		}
		else{
			$s_email = get_option('admin_email');
		}
		
		# try to get email address from MPMF if it is installed
		if ( $s_email == "" ){
			$mpmf_installed = get_option('mpmf_installed');
			if ( $mpmf_installed ) {
				if( get_option('mpmf_email_to_us') != "" && get_option('mpmf_email_to_us') != "Your receiving email address" ){
					# && $send_to_us != "Your receiving email address" && $send_to_us != ""
					$s_email = get_option('mpmf_email_to_us');
				}
			}
			else{
				$err_msg = "You did not give us an email address. Please enter your email address to subscribe to updates, we will send update notices to this email address.";	
			}
		}
		$headers = "";
		$headers .= "From: ". $s_email . "\r\n";
		$headers .= "Reply-To: ". $s_email . "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		
		$message = "Subscribe me to `Who Hit The Page - Hit Counter` updates my email address is " . $s_email; 
		if ( $s_email == "" && $err_msg != "") {
			echo "<div class='error-msg'>Subscription message not sent $err_msg. Please enter email address and retry.</div>";
		}
		else{
			if(who_hit_send_email('3pxwebstudios@gmail.com','Who Hit The Page - Hit Counter',$message,$headers)){
				echo "<div class='success-msg'>Your subscription has been submitted.</div>";
			}
			else{
				echo "<div class='error-msg'>Subscription message not sent. Please retry.</div>";
			}
		}	
	}
	# functions
	
	/*
	* Email function to send an email
	*/
	function who_hit_send_email($user, $subject, $message, $headers){
		if(mail($user, $subject, $message, $headers)){
			return true;
		}
		else return false;
	}
?>