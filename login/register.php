<?php

session_start();

include_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/db.inc' );

if(isset($_POST['btn-signup']))
{
    
    $uname = trim(htmlspecialchars($_POST['uname']));
    $email = trim(htmlspecialchars($_POST['email']));
    $upass = trim(htmlspecialchars($_POST['pass']));
    $c_upass = trim(htmlspecialchars($_POST['c_pass']));

    if ($_POST['pass'] != $_POST['c_pass'])
    {
            $password_unconfirmed = true;  
    }
 
    $upass = md5($upass . "fg3tg83nDFGu8o2");
    
    $sql = "SELECT * FROM secure_login.users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    if (!$res) {
        $err = 'Unable to retrieve data from database: ' . mysqli_error($link);
        echo $err;
        exit();
    }    
    $row = mysqli_fetch_array($res);
	 

    if($row['email'] == $email)
    {
      $duplicate_user = true;
    }
    
    $sqli = "INSERT INTO users (username, email, password, activated) VALUES ('$uname', '$email', '$upass', '0')";
  
    if ($duplicate_user == false && $password_unconfirmed == false) {
            if (!mysqli_query($link, $sqli)) {
                $err = 'Unable to Insert into table: ' . mysqli_error($link);
                echo $err;
                exit();
            }
      
        else { 
		
		$login = true; 
		$to      = $email;
        $email_h = md5($email . 'emVERh45h');
        $subject = 'Account Activation - Takoda.Online';
        $message = 'Please click this link to activate the account you recently created (Do not click this if you did not create an account): http://takoda.online/login/login.php?auth=' . $email_h . "&email=" . $email;
        $headers = 'From: no-reply@takoda.online';
		system("php executeEmail.php $to '$subject' '$message' '$headers' 1>/dev/null 2>&1 &"); //This prevents mail from delaying page load.
		}
    }
	
}
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Sign Up</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
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
                        <li class="active"><a href="/login/register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li><a href="/login/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
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
                <h3>Create an Account</h3> </div>
            <form method="post" class="form-horizontal" action="">
                <div class="form-group">
                    <label for="inputUser" class="col-sm-2 control-label"> Username </label>
                    <div class="col-sm-4">
                        <input type="text" name="uname" class="form-control" placeholder="User Name" value="<?php echo $_POST['uname']; ?>" required> </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label"> Email </label>
                    <div class="col-sm-4">
                        <input type="email" class="form-control" name="email" placeholder="Email" id="email" value="<?php echo $_POST['email']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label"> Password </label>
                    <div class="col-sm-4">
                        <input type="password" name="pass" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword2" class="col-sm-2 control-label"> Confirm Password </label>
                    <div class="col-sm-4">
                        <input type="password" name="c_pass" class="form-control" placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-large btn-primary" name="btn-signup">Submit</button>
                    </div>
                </div>
            </form>


            <?php
            if($login === true){
                $_SESSION['registration'] = "Check your email: " . $email . " to complete account activation";
                echo '<script type="text/javascript"> window.location.href = "login.php"</script>'; 
            }
            if($duplicate_user) {
                // Delete User account if not set to active
                if($row['activated'] == 0)
	            {
	            $sql_d = "DELETE FROM users WHERE email = '$email'";
                if ($link->query($sql_d) === TRUE) { ?>
                <div class="alert alert-warning">
                    <p><span class="glyphicon glyphicon-exclamation-sign"></span>This email address was already entered but not activated, therefore we have deleted the old record so you can register </p>
                </div>
                <?php exit();
                } else {
                    echo "Error deleting record: " . $link->error;
                    exit();
                }
	           }?>
                    <div class="alert alert-danger">
                        <p><span class="glyphicon glyphicon-exclamation-sign"></span>This email account is already in use. Please enter a different one. </p>
                    </div>
                    <?php
                }
            if($password_unconfirmed == true) { ?>
                        <div class="alert alert-danger">
                            <p><span class="glyphicon glyphicon-exclamation-sign"></span>Please enter the same password in both fields </p>
                        </div>

                        <?php 
            }
            ?>


        </div>

    </body>

    </html>
