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
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>

<body>

    <!-- Navigation Bar -->
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
                    <li><a href="books.php">Buy</a></li>
                    <li class="active"><a href="">Sell</a></li>
                    <li><a href="../about/about.php">About Me</a></li>
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
                    <form action="books.php" class="navbar-form" role="search">
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
    
    <!-- Form to Create Patterns -->
    <div class="container">
          <div class="page-header">
            <h3>Create a Book Listing</h3> </div>
        <?php if(!isset($_SESSION['user'])) { 
            $email_needed = true;
    ?>
                <div class="alert alert-warning">
                 <p><span class="glyphicon glyphicon-exclamation-sign"></span>Please <a href="../login/login.php"> Login </a> to Submit a Book </p>
                </div>
                <?php    } ?>
    
    <div class="leftcol">
        
    <form action="" id="create_book" method="post">
        <div class="form-group row">
            <label for="title" class="col-sm-3 form-control-label">Title</label>
            <div class="col-sm-4">
                <input type="text" name="title" class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="author" class="col-sm-3 form-control-label">Author</label>
            <div class="col-sm-4">
                <input type="text" name="author" class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="publisher" class="col-sm-3 form-control-label">Course</label>
            <div class="col-sm-4">
                <input type="text" name="course" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="year" class="col-sm-3 form-control-label">Year</label>
            <div class="col-sm-4">
        <select name="year" class="form-control">
        <?php
for ($year=2016 ; $year>=1970 ; $year--) {
        echo '<option value="' . $year . '"'; 
    echo '>' . $year . '</option>' . PHP_EOL;
}
?>
                </select></div></div>
        <div class="form-group row">
            <label for="course_used" class="col-sm-3 form-control-label">ISBN</label>
            <div class="col-sm-4">
                <input type="text" name="bookISBN" class="form-control" required>
            </div>
        </div>
                <div class="form-group row">
            <label for="course_used" class="col-sm-3 form-control-label">Price</label>
            <div class="col-sm-4">
                <input type="number" name="price" class="form-control" step="any" required>
            </div>
        </div>
          <div class="form-group row">
            <label for="condition" class="col-sm-3 form-control-label">Condition</label>
            <div class="col-sm-4">
        <select name="condition" class="form-control">
        <option value="Excellent"> Excellent </option>
            <option value="Good"> Good </option>
            <option value="Fair"> Fair </option>
            <option value="Poor"> Poor </option>
                </select></div></div>

        <div class="form-group row">
            <div class="col-sm-3 col-sm-offset-4">
			<?php
			    if(isset($_SESSION['user']))
				{
				    echo"<button type='submit' class='btn btn-primary' value='Submit'>Submit</button>";
				}
				else
				{
				    echo"<button type='submit' class='btn btn-primary' value='Submit' disabled>Please Login</button>";
				}
			?>
            </div>
            
        </div>
        
    </form>
        
    </div>
    <?php
	if(isset($_SESSION['user'])) {
        include '../inc/db.inc'; 
        $title = trim(htmlspecialchars($_POST['title']));
        $author = trim(htmlspecialchars($_POST['author']));   
        $course = trim(htmlspecialchars($_POST['course'])); 
        $year = $_POST['year'];
        $bookISBN = trim(htmlspecialchars($_POST['bookISBN']));
        $price = trim(htmlspecialchars($_POST['price']));
        $condition = trim(htmlspecialchars($_POST['condition']));
		$user = $_SESSION['user'];
        if(isset($_POST['year']) && isset($_POST['title']) && isset($_POST['author']) && isset($_POST['course']) && isset($_POST['bookISBN']) && isset($_POST['condition'])) {
            $sql = "INSERT INTO book_listings (bookISBN, bookCost, bookName, author, course, userID, year, bookCondition) VALUES ('$bookISBN', '$price', '$title', '$author', '$course' , '$user' , '$year', '$condition' )";
            if (!mysqli_query($link1, $sql)) {
                $err = 'Unable to Insert into table: ' . mysqli_error($link1);
                echo $err;
                exit();
            }
            echo '<script type="text/javascript"> window.location.href = "../profile/mylistings.php"</script>';
        }
    }
    ?>
    </div>
</body>
</html>
