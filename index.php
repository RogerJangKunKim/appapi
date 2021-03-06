<?php
//Configuration for our PHP server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//Make constants using define.
define('clientID', '9ae4c7b4dbaa40a980b5cfa58e733a28');
define('clientSecret', '42f5be596d8a4151897df655b413a9f0');
define('redirectURI', 'http://localhost/appapi/index.php');
define('ImageDirectory', 'pics/');

//function that is going to connect to Instagram.
function connectToInstagram($url){
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

//function to get userID cause the userName doesn't allow us to get pictures
function getUserID($userName){
	$url = 'https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	return $results['data']['0']['id'];
}

//function to print out images onto screen
function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=5';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	//parse through the information one by one
	foreach ($results['data'] as $items) {
		$image_url = $items['images']['low_resolution']['url']; //going through all of my results and give myself back the URL of those pictures because we want to save it in the PHP Server
		echo '<img src=" '.$image_url.' "/><br/>';
		//calling a function to save that $image_url
		savePictures($image_url);
	}
}

//function to save image to server
function savePictures($image_url){
	//echo $image_url.'<br>'; //filename is what we are storing. basename is the PGP built in method that we are using to store $image_url
	$filename = basename($image_url);
	//echo $filename . '<br>';

	$destination = ImageDirectory . $filename; //making sure that the image doesn't exist in the storage
	file_put_contents($destination, file_get_contents($image_url)); //goes and grabs an imagefile and stores it into out server

}

if (isset($_GET['code'])){
	?>
	<div>
		Instagram
	</div>
	<?php
	$code = ($_GET['code']);
	$url = "https://api.instagram.com/oauth/access_token";
	$access_token_settings = array('client_id' => clientID,
		'client_secret' =>clientSecret,
		'grant_type' => 'authorization_code',
		'redirect_uri' => redirectURI,
		'code' => $code
		);
//library used to make calls to other api's
$curl = curl_init($url); //getting data from url
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings); //setting to the array that we created
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //setting to 1 because we are getting string back
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //in live work-production, we want to set to true

$result = curl_exec($curl);
curl_close($curl);

$results = json_decode($result, true);

$userName = $results['user']['username'];

$userID = getUserID($userName);

printImages($userID);

?>

<!DOCTYPE html>
<html id='instapage'>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" id="font-awesome-css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" type="text/css" media="screen">
</head>
<body>
	<!-- <div class="scroll-top-wrapper ">
		<span class="scroll-top-inner">
			<i class="fa fa-2x fa-arrow-circle-up"></i>
		</span>
	</div> -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src='main.js'></script>
	<script type="text/javascript" src="http://arrow.scrolltotop.com/arrow59.js"></script>
</body>
</html>

<?php
}
else{
?>
<!-- 
CLIENT ID	    9ae4c7b4dbaa40a980b5cfa58e733a28
CLIENT SECRET	42f5be596d8a4151897df655b413a9f0
WEBSITE URL	    http://localhost/appapi/index.php
REDIRECT URI	http://localhost/appapi/index.php
 -->
 <!DOCTYPE html>
 <html id='loginpage'>
 <head>
 	<title></title>
 	<link rel="stylesheet" type="text/css" href="main.css">
 	<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" id="font-awesome-css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" type="text/css" media="screen">
 </head>
 <body>

 	<div>
 		<h1>APP API</h1>
 	</div>
 	<!-- <div class="scroll-top-wrapper ">
		<span class="scroll-top-inner">
			<i class="fa fa-2x fa-arrow-circle-up"></i>
		</span>
	</div> -->
 	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code"><button>LOGIN</button></a>
 	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 	<script type="text/javascript" src='main.js'></script>
	<script type="text/javascript" src="http://arrow.scrolltotop.com/arrow59.js"></script>


 </body>
 </html>
 <?php
}
?>