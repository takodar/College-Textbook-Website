<?php
  session_start();
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Takoda Register</title>
        <meta charset="utf-8" />
        <meta name="keywords" content="Takoda, takoda, takoda register, takoda.online, https://takoda.online, takodaonline, http://takoda.online, college, textbook">
        <meta name="description" content="Takoda Registers Sample Website to Sell Your College Textbook to Other Students">
        <meta name="author" content="Takoda Register">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/customstyle.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>

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
                    <a class="navbar-brand" href="">Takoda Register</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="">Home</a></li>
                        <li><a href="/books/books.php">Buy</a></li>
                        <li><a href="/books/createlisting.php">Sell</a></li>
                        <li><a href="/about/about.php">About Me</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
					    if(isset($_SESSION['user'])!="")
                        {
						  echo "<li><a href='/profile/mylistings.php'><span class='glyphicon glyphicon-user'></span> " . ucfirst($_SESSION['email_id']) . "</a></li>";
                          echo "<li><a href='/login/logout.php'><span class='glyphicon glyphicon-log-out'></span>" . " Logout" . "</a></li>";
                        }
						else
						{
						  echo "<li><a href='/login/register.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
                          echo "<li><a href='/login/login.php'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>";
						}
					?>
                    </ul>
                    <div class="col-sm-3 col-md-3 pull-right">
                        <form method="post" action="/books/books.php" class="navbar-form" role="search">
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

<div class="carousel fade-carousel slide" data-ride="carousel" data-interval="4000" id="bs-carousel">
  <!-- Overlay -->


  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
    <li data-target="#bs-carousel" data-slide-to="1"></li>
    <li data-target="#bs-carousel" data-slide-to="2"></li>
  </ol>
  
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item slides active">
          <div class="overlay"></div>
      <div class="slide-1"> </div>  
      <div class="hero">
          
        <hgroup>
      
            <h1>Takoda's Sample Website</h1> 
            <h3>Sign in with your Google Account</h3>
        </hgroup>
             <?php
            //Defining

require_once('google/vendor/autoload.php');

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
    <center>
<?php
if(isset($authUrl)){
 echo "<a class='login' href='" . $authUrl . "'><img src='google/signin_button.png' height='50px' /></a>";   
}

else {
    include_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/db.inc' );

    
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

    
    echo "<a class='login' href='/login/logout.php'" . $authUrl . "'><img src='google/signout_button.png' height='50px' /></a>";
    
    if($_SESSION['g_first'] != 'false' ){
    echo '<script type="text/javascript"> window.location.href = "profile/mylistings.php"</script>'; 
    }
    else
    {
     echo '<script type="text/javascript"> window.location.href = $_SERVER["DOCUMENT_ROOT"]</script>';    
    }
    
}
?>
        </center>
</div>
      </div>
    </div>
    <div class="item slides">
      <div class="slide-2"></div>
      <div class="hero">        
        <hgroup>
            <h1>Tools Used to Create This Website</h1>
            <h3> Bootstrap, PHP, MySQL, HTML5, OAUTH2</h3>
        </hgroup>       
       
      </div>
    </div>
    <div class="item slides">
      <div class="slide-3"></div>
      <div class="hero">        
        <hgroup>
            <h1>Sell Your Textbook Today</h1>
        </hgroup>
          <a class='btn btn-lg btn-success' href='/books/createlisting.php' role='button'>Create a Listing</a>
      </div>
    </div>
  </div> 
</div>
           
        
        
        <div class="row">
                <div class="col-lg-4">
                    <h2>Listings</h2>
                    <p>Check for new listings. This includes all of the recent posting.</p>
                    <p><a class="btn btn-primary" href="/books/books.php" role="button">Current Listings »</a></p>
                </div>
                <div class="col-lg-4">
                    <h2>Create a Listing</h2>
                    <p>Login to your account and begin selling books today!</p>
                    <p><a class="btn btn-primary" href="/books/createlisting.php" role="button">Create a Listing »</a></p>
                </div>
                <div class="col-lg-4">
                    <h2>My Listings</h2>
                    <p>View your current book listings.</p>
                    <p><a class="btn btn-primary" href="/profile/mylistings.php" role="button">My Listings »</a></p>
                </div>
            </div>

       






    </body>

    </html>
