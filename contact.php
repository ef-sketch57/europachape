<?php  
/* 
Credits: Bit Repository 
URL: http://www.bitrepository.com/web-programming/ajax/tableless-form-using-jquery.html 
*/  
  
include 'config.php';  
  
error_reporting (E_ALL ^ E_NOTICE);  

function ValidateEmail($email)
{
	/*
	(Name) Letters, Numbers, Dots, Hyphens and Underscores
	(@ sign)
	(Domain) (with possible subdomain(s) ).
	Contains only letters, numbers, dots and hyphens (up to 255 characters)
	(. sign)
	(Extension) Letters only (up to 10 (can be increased in the future) characters)
	*/
	$regex = "([a-z0-9_.-]+)". # name
	"@". # at
	"([a-z0-9.-]+){2,255}". # domain & possibly subdomains
	".". # period
	"([a-z]+){2,10}"; # domain extension 
	$eregi = eregi_replace($regex, '', $email);
	return empty($eregi) ? true : false;
}
 
$post = (!empty($_POST)) ? true : false;  
  
if($post)  
{  
  
	$name = stripslashes($_POST['name']);  
	$email = $_POST['email'];  
	$subject = stripslashes($_POST['subject']);  
	$message = stripslashes(htmlspecialchars($_POST['message']));  
	  
	$error = '';  
	  
	// Check name  
	if(!$name){  
		$error .= 'Please enter your name.<br />';  
	}
	
	// Check email   
	if(!$email){  
		$error .= 'Please enter an e-mail address.<br />';  
	}  
	  
	if($email && !ValidateEmail($email)){  
		$error .= 'Please enter a valid e-mail address.<br />';  
	}  
	  
	// Check message (length)  
	  
	if(!$message || strlen($message) < 15)  {  
		$error .= "Please enter your message. It should have at least 15 characters.";  
	}  
	  
	if(!$error)  {  
		$mail = mail(WEBMASTER_EMAIL, $subject, $message,  
	     "From: ".$name." <".$email.">");  
	  
		if($mail){  
			echo '<div class="notification_ok">Thank you for your message!</div>';  
		}  
	}else{  
		echo '<div class="notification_error">'.$error.'</div>';  
	}   
}  
?>  