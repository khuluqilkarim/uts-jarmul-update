<?php

// Define some constants
define( "RECIPIENT_NAME", "John Doe" );
define( "RECIPIENT_EMAIL", "youremail@mail.com" );


// Read the form values
$success = false;
$firstName = isset( $_POST['username'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['username'] ) : "";
$senderEmail = isset( $_POST['email'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['email'] ) : "";
$senderPhone = isset( $_POST['phone'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['phone'] ) : "";
$Subject = isset( $_POST['subject'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['subject'] ) : "";
$message = isset( $_POST['message'] ) ? preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'] ) : "";

// If all values exist, send the email
if ( $firstName && $senderEmail && $senderPhone && $Subject && $message) {
  $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
  $headers = "From: " . $firstName . " <" . $senderEmail . ">";
  $msgBody = " Phone: ". $senderPhone . " Subject: " . $Subject . " Message: " . $message . "";
  $success = mail( $recipient, $headers, $msgBody );

  //Set Location After Successsfull Submission
  header('Location: contact.html?message=Successfull');
}

else{
	//Set Location After Unsuccesssfull Submission
  	header('Location: index.html?message=Failed');	
}

?>