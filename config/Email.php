<?php



$email_headers  = 'MIME-Version: 1.0' . "\r\n";

$email_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";



$email_headers .= 'From: Organization Name<info@organization.com>' . "\r\n" .

    'Reply-To: webmaster@example.com' . "\r\n" .

    'X-Mailer: PHP/' . phpversion();





$config_email = [

    'headers' => $email_headers,

    'additional_headers' => '' 

];
