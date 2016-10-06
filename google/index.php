<?php
//Defining

require_once('vendor/autoload.php');

const CLIENT_ID = '275500112782-ad2p28nanrpc62eacdr8mfqgh0pr7bk1.apps.googleusercontent.com';
const CLIENT_SECRET = 'R9GDbGXwqVpKx8tG5LnNy8OU';
const REDIRECT_URI = 'https://takoda.online';

session_start();

//Initialization

$client = new Google_Client();
$client ->setClientId(CLIENT_ID);
$client ->setClientSecret(CLIENT_SECRET);
$client ->setRedirectUri(REDIRECT_URI);
$client ->setScopes('email');

$plus = new Google_Service_Plus($client);

//actual process

if(isset($_REQUEST['logout'])){
 session_unset();   
}

if(isset($_GET['code'])){
 $client->authenticate($_GET['code']);
 $_SESSION['access_token'] = $client->getAccessToken();
 $redirect='https://takoda.online/books/books.php';
  header('Location:'.filter_var($redirect,FILTER_SANITIZE_URL));
}

if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
 $client->setAccessToken($_SESSION['access_token']);
 $me = $plus->people->get('me');
    
    $id = $me['id'];
    $name = $me['displayName'];
    $email = $me['emails'][0]['value'];
    $profile_image_url = $me['image']['url'];
    $cover_image_url = $me['cover']['coverPhoto']['url'];
    $profile_url = $me['url'];
}

else{
 $authUrl = $client->createAuthUrl();
    $_SESSION['displayName'] = $me['displayName'];
}
?>
<div>
<?php
if(isset($authUrl)){
 echo "<a class='login' href='" . $authUrl . "'><img src='signin_button.png' height='50px' /></a>";   
}

else {
 print "ID: {$id} <br>";
    print "Name: {$name} <br>";
    print "Email: {$email} <br>";
    print "Url: {$profile_url} <br><br>";
    echo "<a class='logout' href=?logout'><button>Logout</button></a>";
}
?>
</div>