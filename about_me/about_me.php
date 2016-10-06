<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Takoda Register</title>
    <meta charset="utf-8" />
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
                <a class="navbar-brand" href="../index.php">Takoda Register</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../books/books.php">Buy</a></li>
                    <li><a href="../books/createlisting.php">Sell</a></li>
                    <li class="active"><a href="">About Me</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
					<?php
					    if(isset($_SESSION['user'])!="")
                        {
						  echo "<li><a href='../profile/mylistings.php'><span class='glyphicon glyphicon-user'></span> " . ucfirst($_SESSION['email_id']) . "</a></li>";
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
        <!-- Introduction Row -->
        <div class="row2">
            <div class="col-lg-12">
                <h2 class="page-header">Resume</h2>
            </div>
        </div>

    </div>
            <div class="wrapper">
            <center>
                 <button><a href="/inc/Resume (Takoda Register).pdf"><img src="/inc/resume.png" class="patent"></a></button>
              </div>
                </center>

</body>

</html>
