/*
NOTE: This is working ajax PHP contact form. This PHP form will help you to send email.
*/
<?php
if(isset($_POST["action"])) {
  $name = $_POST['name'];                 // Sender's name
  $email = $_POST['email'];     // Sender's email address
  $phone  = $_POST['phone'];     // Sender's email address
  $message = $_POST['message'];    // Sender's message
  $from = 'Demo Contact Form';    
  $to = 'demo@domain.com';     // Recipient's email address
  $subject = 'Message from Contact Demo ';

 $body ="From: $name \n E-Mail: $email \n Phone : $phone \n Message : $message"  ;
	
	// init error message 
	$errmsg='';
  // Check if name has been entered
  if (!$_POST['name']) {
   $errmsg = 'Please enter your name';
  }

  
  // Check if email has been entered and is valid
  if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
   $errmsg = 'Please enter a valid email address';
  }
  
  //Check if message has been entered
  if (!$_POST['message']) {
   $errmsg = 'Please enter your message';
  }

  if (!$_POST['g-recaptcha-response']) {
   $errmsg = 'Please check ReCaptcha.';
  }

  $success = _isGoogleCaptchaValid($_POST['g-recaptcha-response']);
  if (!$success) {
   $errmsg = 'Invalid ReCaptcha';
  }
 
	$result='';
  // If there are no errors, send the email
  if (!$errmsg) {
		if (mail ($to, $subject, $body, $from)) {
			$result='<div class="alert alert-success">Thank you for contacting us. Your message has been successfully sent. We will contact you very soon!</div>'; 
		} 
		else {
		  $result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later.</div>';
		}
	}
	else{
		$result='<div class="alert alert-danger">'.$errmsg.'</div>';
	}
	echo $result;
 }


  function _isGoogleCaptchaValid($fieldData)
  { 

      if (empty($fieldData)) {
        return false;  
      }

      $captchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
      $captchaSec = '6Ld4DykTAAAAAK3QcKF76DW1NPdL0DEyZaZBErrt';
      $verifyUrl  = $captchaUrl."?secret=".$captchaSec."&response=".$fieldData."&remoteip=".$_SERVER['REMOTE_ADDR'];
      $result     = captchaData($verifyUrl);
      $result     = json_decode($result,true);

      $bool = $result['success'] ? 'True' : 'False';

      return $result['success'];

  }

  function captchaData($url)
  {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_TIMEOUT, 10);
      curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
      $curlData = curl_exec($curl);
      curl_close($curl);
      return $curlData;
  }  
?>
