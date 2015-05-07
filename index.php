<?php
//Configuration for our PHP server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//Make constants using define.
define('clientID', '9ae4c7b4dbaa40a980b5cfa58e733a28');
define('client_Secret', '42f5be596d8a4151897df655b413a9f0');
define('redirectURI', 'http://localhost/appapi/index.php');
define('ImageDirectory', 'pics/');

if (isset($_GET['code'])){
	$code = ($_GET['code']);
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_setting = array('client_id' => clientID,
		'client_secret' =>clientSecret,
		'grant_type' => 'authorizeation_code',
		'redirect_uri' => redirectURI,
		'code' => $code
		);
}
?>
<!-- 
CLIENT ID	    9ae4c7b4dbaa40a980b5cfa58e733a28
CLIENT SECRET	42f5be596d8a4151897df655b413a9f0
WEBSITE URL	    http://localhost/appapi/index.php
REDIRECT URI	http://localhost/appapi/index.php
 -->
 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>
 	<a href="https:api.instagram.com/oauth.authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code/">LOGIN</a>
 	<script src="js/main.js"></script>
 </body>
 </html>