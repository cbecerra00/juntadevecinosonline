<?php
session_start();
require('lib/oauth_client.php');
require('lib/http.php');
require('lib/lib.inc');
 
$client = new oauth_client_class;
$client->debug = false;
$client->debug_http = true;
$client->redirect_uri = REDIRECT_URL;
 
$client->client_id = API_KEY;
$application_line = __LINE__;
$client->client_secret = SECRET_KEY;
 
if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
  die('Please go to LinkedIn Apps page');
 
$client->scope = "r_basicprofile r_emailaddress";
if (($success = $client->Initialize())) {
  if (($success = $client->Process())) {
    if (strlen($client->authorization_error)) {
      $client->error = $client->authorization_error;
      $success = false;
    } elseif (strlen($client->access_token)) {
      $success = $client->CallAPI(
					'http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,picture-url,public-profile-url,formatted-name,positions)', 
					'GET', array(
						'format'=>'json'
					), array('FailOnAccessError'=>true), $user);
    }
  }
  $success = $client->Finalize($success);
}
if ($client->exit) exit;

if ($success) {
   print_r($user);
   $result = '<h1>LinkedIn Profile Details </h1>';
   $result .= '<img src="'.$user->pictureUrl.'">';
   $result .= '<br/>LinkedIn ID : ' . $user->id;
   $result .= '<br/>Email  : ' . $user->emailAddress;
   $result .= '<br/>Name : ' . $user->firstName.' '.$user->lastName;
   $result .= '<br/>Location : ' . $user->positions->values[0]->location->name;
   $result .= '<br/>Positions : ' . $user->positions->values[0]->company->name;
   $result .= '<br/>Logout from <a href="logout.php">LinkedIn</a>';
   echo '<div>'.$result.'</div>';
}
?>
