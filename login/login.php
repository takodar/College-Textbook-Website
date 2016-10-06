<?php
session_start();
include_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/db.inc' );

if(isset($_SESSION['user'])!="")
{
 header("Location: ../index.php");
}

if(isset($_POST['btn-login']))
{
    $email = trim(htmlspecialchars($_POST['email']));
    $upass = trim(htmlspecialchars($_POST['pass']));
 
    $authVerified = false;

 if(isset($_GET['auth']))
 {
     $auth = trim(htmlspecialchars($_GET['auth']));
	 $verEmail = trim(htmlspecialchars($_GET['email']));
	 
	 if($auth == md5($verEmail . "emVERh45h"))
	 {
	    $authVerified = true;
	 }
 }
 
 $sql = "SELECT * FROM secure_login.users WHERE email = '$email'";
 $res = mysqli_query($link, $sql);
    if (!$res) {
        $err = 'Unable to retrieve data from database: ' . mysqli_error($link);
        echo $err;
        exit();
    }    
 $row = mysqli_fetch_array($res);
 
 $canLogin = false; // if they are activated
 
 $showVerifyMessage = false;
 
 if($row['activated'] == 0)
 {
    if($authVerified)
	{
	    $sql_u = "UPDATE users SET activated = '1' WHERE email = '$email'";
        if ($link->query($sql_u) === FALSE) {
            echo "Error updating record: " . $link->error;
            exit();
        }
		
		$showVerifyMessage = true;
		$canLogin = true;
	}
 }
 else
 {
    $canLogin = true;
 }
 
 $notVerified = false; //for use with the alert message
 
 
 if(!$row['password'])
 {
    $invalid_user = true;
	$notVerified = false;
 }
 elseif($canLogin == false)
 {
    $notVerified = true;
 }
 elseif($row['password'] == md5($upass . "fg3tg83nDFGu8o2"))
 {
  $_SESSION['user'] = $row['id'];
  $_SESSION['email_id'] = $row['email'];  
  $_SESSION['registration'] = '';     
  header("Location: ../index.php");
 }
 
}
        
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Login</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/customstyle.css">
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>

    </head>

    <body>


        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../index.php">Takoda Register</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../books/books.php">Buy</a></li>
                        <li><a href="../books/createlisting.php">Sell</a></li>
                        <li><a href="../about/about.php">About Me</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/login/register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li class="active"><a href="/login/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    </ul>
                    <div class="col-sm-3 col-md-3 pull-right">
                        <form method="post" action="../books/books.php" class="navbar-form" role="search">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <select name="srch-type" class="form-control">
                                        <option value="ISBN" selected>ISBN</option>
                                        <option value="Course"> Course Used</option>
                                        <option value="Title">Title</option>
                                        <option value="Author">Author</option>
                                    </select>
                                </div>

                                <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">

                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="page-header">
                <h3>Login</h3> </div>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label"> Email </label>
                    <div class="col-sm-4">
                        <input type="email" name="email" placeholder="Email" id="inputEmail" class="form-control" value="<?php echo $_POST['email']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label"> Password </label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" name="pass" placeholder="Password" id="inputPassword" required>
                        <br />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="btn-login" class="btn btn-primary">Sign In</button>
                    </div>
                </div>
                
                <div class="col-sm-offset-4 col-sm-10"> 
                   <?php
            //Defining

require_once('../google/vendor/autoload.php');

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
 $redirect='https://takoda.online';
  header('Location:'.filter_var($redirect,FILTER_SANITIZE_URL));
}

if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
 $client->setAccessToken($_SESSION['access_token']);
 $me = $plus->people->get('me');
    
    $id = $me['id'];
    $_SESSION['user_name'] = $me['displayName'];
    $_SESSION['email_id'] = $me['emails'][0]['value'];
    $profile_image_url = $me['image']['url'];
    $cover_image_url = $me['cover']['coverPhoto']['url'];
    $profile_url = $me['url'];
}

else{
 $authUrl = $client->createAuthUrl();
}
?>
<div>
  
<?php
if(isset($authUrl)){
 echo "<a class='login' href='" . $authUrl . "'><img src='../google/signin_button.png' height='50px' /></a>";   
}

else {
    include_once( $_SERVER['DOCUMENT_ROOT'] . '../inc/db.inc' );

    
    $email = $_SESSION['email_id'];
    $upass = 'google';
     
    $upass = md5($upass . "fg3tg83nDFGu8o2");
    
    $sql = "SELECT * FROM secure_login.users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    if (!$res) {
        $err = 'Unable to retrieve data from database: ' . mysqli_error($link);
        echo $err;
        exit();
    }    
    $row = mysqli_fetch_array($res);
	 $uname = $_SESSION['user_name'];
    $_SESSION['user'] = $row['id'];
  $_SESSION['email_id'] = $row['email'];  
  $_SESSION['registration'] = ''; 
    
        if($row['email'] == $email)
    {
      $registered = true;
        // exit();
    }
    
    if($registered == false ){
    $sqli = "INSERT INTO users (username, email, password, activated) VALUES ('$uname', '$email', '$upass', '1')";
  
}
            if (!mysqli_query($link, $sqli)) {
                $err = 'Unable to Insert into table: ' . mysqli_error($link);
                // echo $err;
                // exit();
            }

    
    echo "<a class='login' href='logout.php'" . $authUrl . "'><img src='../google/signout_button.png' height='50px' /></a>";
    
    if($_SESSION['g_first'] != 'false' ){
    echo '<script type="text/javascript"> window.location.href = "../profile/mylistings.php"</script>'; 
    }
    else
    {
     echo '<script type="text/javascript"> window.location.href = $_SERVER["DOCUMENT_ROOT"]</script>';    
    }
    
}
?>
                    </div>
</div>
                
                
                
            </form>
            <?php if(isset($_SESSION['registration']) && $notVerified == false)
        {
            echo $_SESSION['registration'];
        } ?>
            <?php
            if($invalid_user == true) { ?>
                <div class="alert alert-danger">
                    <p><span class="glyphicon glyphicon-exclamation-sign"></span>Please Enter a Valid Email Address & Password </p>
                </div>
                <?php    } ?>

                    <?php
            if($notVerified) {  
            ?>
                        <div class="alert alert-danger">
                            <p><span class="glyphicon glyphicon-exclamation-sign"></span>Your account is not activated! Please verify using the email you were sent.</p>
                        </div>
                        <?php    } ?>
						
            <?php
            if($showVerifyMessage == true) { ?>
                <div class="alert alert-success">
                    <p><span class="glyphicon glyphicon-exclamation-sign"></span>Your account is now verified! You may now login.</p>
                </div>
                <?php    } ?>
            

                            <br />
                            <br />

        </div>

    </body>

    </html>
