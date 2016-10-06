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
                    <li class="active"><a href="">Buy</a></li>
                    <li><a href="/books/createlisting.php">Sell</a></li>
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
                    <form method="post" action="" class="navbar-form" role="search">
                        <div class="input-group">
                            <div class="input-group-btn">
					        <select name="srch-type" class="form-control">
                            <option value="ISBN" <?php if ($_POST[ 'srch-type']=='ISBN' ) echo 'selected="selected"'; ?>>ISBN</option>
                            <option value="Course"<?php if ($_POST[ 'srch-type']=='Course' ) echo 'selected="selected"'; ?>> Course Used</option>
                            <option value="Title" <?php if ($_POST[ 'srch-type']=='Title' ) echo 'selected="selected"'; ?>>Title</option>
                            <option value="Author"<?php if ($_POST[ 'srch-type']=='Author' ) echo 'selected="selected"'; ?>>Author</option>
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

    <!-- Table of all Books -->
    <div class="container">
         <div class="page-header">
            <h3>Book Listings</h3> </div>
        <br />
        <div class="row-fluid">

            <form action="" id="book_form" method="post">Course: &nbsp;
                <select name="srch-term">
					<?php
                        include '../inc/db.inc';
				    	$sql2 = "SELECT course FROM book_listings";
                        $result2 = mysqli_query($link1, $sql2);
                        if (!$result2) {
                            $err = 'Unable to retrieve data from database: ' . mysqli_error($link);
                            echo $err;
                            exit();
                        }
		
		                while ($row2 = mysqli_fetch_assoc($result2)) {
						    $data = $row2['course'];
						    echo "<option value=\"" . $data . "\">" . $data . "</option>";
                        }
					?>
                </select>
				<input type='hidden' name='srch-type' value='Course'>
                <input type="submit"  value="Submit">
            </form>
        </div>
        <br />

        <table class="table table-hover">
        <?php 
    $table = '<thead><tr><th>Title</th><th>Author</th><th>Course</th><th>Year</th><th>ISBN</th><th>Price</th><th>Condition</th><th>Date Posted</th><th>Email User</th></tr></thead><tbody><tr>';       
    
	$args = "";
	
	if(isset($_POST['srch-term']) && isset($_POST['srch-type']))
	{
	    $item = trim(htmlspecialchars($_POST['srch-term']));
	    $type = trim(htmlspecialchars($_POST['srch-type']));
		
		$args = "";
		
		switch($type)
		{
          case "ISBN":
            $type = "bookISBN";
			$args = " WHERE " . $type . " = '" . $item . "'";
            break;
        case "Course":
            $type = "course";
			$args = " WHERE " . $type . " LIKE '%" . $item . "%'";
            break;
        case "Title":
            $type = "bookName";
			$args = " WHERE " . $type . " LIKE '%" . $item . "%'";
            break;
		case "Author":
            $type = "author";
			$args = " WHERE " . $type . " LIKE '%" . $item . "%'";
		    break;
        default:
            $item = "";
			$type = "";
        }
	}
	
	$sql = "SELECT * FROM book_listings" . $args . " ORDER BY listDate DESC";
    $result = mysqli_query($link1, $sql);
    if (!$result) {
        $err = 'Unable to retrieve data from database: ' . mysqli_error($link1);
        echo $err;
        exit();
    }
    
    $books = array();
    while ($row = mysqli_fetch_assoc($result)) {
  
        array_push ($books, $row['bookName']); 
        array_push ($books, $row['author']); 
        array_push ($books, $row['course']);
        array_push ($books, $row['year']);
        array_push ($books, $row['bookISBN']);
        array_push ($books, "$" . $row['bookCost']);
        array_push ($books, $row['bookCondition']);
        array_push ($books, substr($row['listDate'], 5,5 ));
        $sql1 = "SELECT email FROM users WHERE id = " . $row['userID'];
        $result1 = mysqli_query($link, $sql1);
        if (!$result1) {
            $err = 'Unable to retrieve data from database: ' . mysqli_error($link);
            echo $err;
            exit();
        }
		
		while ($row1 = mysqli_fetch_assoc($result1)) {
		    if(isset($_SESSION['user']))
	        {
		        $mail_link = "<a href='mailto:" . $row1['email'] . "' class='btn btn-success'>Email User</a>";
	        }
			else
			{
			    $mail_link = "<a href='' class='btn btn-success' disabled>Login to Email User</a>";
			}
        }
		
        array_push ($books, $mail_link);
        
    }
    $count = count($books);
    $nr_col = 9;
   
for($x=0; $x<$count; $x++){
    $table .= "<td>" . $books[$x] . "</td>";
            
    $col_to_add = ($x+1) % $nr_col;
    if($col_to_add == 0) { 
     $table .= "</tr><tr>";   
    }

}
$table .= "</tr></tbody></table>";
$table = str_replace('<tr></tr>', '', $table);
echo $table;

?>
         
    </div>

</body>
    <div class="foot">
        <div class="book_bottom">
<img class="bookPic" src="/inc/Footer.png" alt="footer logo">
        </div>
        </div>
</html>
