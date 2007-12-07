<?php

/**
 * Created by Nathan Kinkade on Tue Nov  6 16:27:27 PST 2007 at the
 * request of Mike who apparently passed it on from Larry.  This script
 * should take a single text input form element, which should contain an email
 * address * and then forward that address on to melissa@creativecommons.org.  The
 * purpose is to give people a way of opting out of email correspondence.
 */

$address = trim($_POST['address']);

# USER SETTINGS
$rcpt_to = "melissa@creativecommons.org";
$mail_from = "unsubscribe-from-cc-emails@creativecommons.org";
$subject = "SCRUB email address from CC mailings";
$body = <<<BODY
A request was submitted to remove the following address from any future
emails from Creative Commons:

{$_POST['address']}

BODY;


#Only proceed if an email address was submitted.
if ( empty($address) ) {
	header("Location: http://creativecommons.org/about/no-email/");
	exit;
}

# Only try to send the mail if the address meets some vary basic
# validation and actually seems like an email address.
if ( preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $address) ) {
	mail($rcpt_to, $subject, $body, "From: $mail_from");
} else {
	header("Location: http://creativecommons.org/about/no-email/");
	exit;
}

header("Location: http://creativecommons.org/about/no-email-thanks/");

?>
