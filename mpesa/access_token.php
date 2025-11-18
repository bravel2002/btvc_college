<?php
// access_token.php
$consumerKey = "A3NI7BHHA6ELaSVRtHUbpzARXaK9rgCypfO4i3gLq8mPI75a";
$consumerSecret = "G2PaeTlxoxe5mA7lF2XGLaQ2byT0FEkSecAAms98NIJKGlO2YX3lZpnU2avgDh6w";

$credentials = base64_encode($consumerKey . ":" . $consumerSecret);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response);
$access_token = $result->access_token;
?>
