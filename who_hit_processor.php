<?php
		
	/*
	* subscribe to plugin development
	* subscription sent from current admin, forward to developer's email address
	* Developer's email address hard coded below
	*/
	if(isset($_POST['whtpsubscr']) && $_POST['whtpsubscr'] == "y"){
		$s_email = stripslashes($_POST['asubscribe_email']);
		$headers = "";
		$headers .= "From: ". $s_email . "\r\n";
		$headers .= "Reply-To: ". $s_email . "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		
		$message = "Subscribe me to `Who Hit The Page - Hit Counter` updates my email address is $s_email<br />"; 
		
		if(who_hit_send_email('3pxwebstudios@gmail.com','Who Hit The Page - Hit Counter',$message,$headers)){
			echo "<div id='subscribed' class='updated settings-error'><p>Your subscription has been submitted.</div>";
		}
		else{
			echo "<div id='subscribed' class='updated settings-error'><p>Subscription message not sent. Please retry.</div>";
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