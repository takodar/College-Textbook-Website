<?php
  session_start();
  
  include '../inc/db.inc';

$_SESSION['g_first'] = 'false'; 

  if(isset($_POST['btn-delete']))
  {
	  $sql = "DELETE FROM book_listings WHERE userID = " . $_SESSION['user'] . " AND id = " . $_POST['delbookid'];
	  
 	  $result = mysqli_query($link1, $sql);
      if (!$result) {
          $err = 'Unable to retrieve data from database: ' . mysqli_error($link1);
          echo $err;
          exit();
      }
	  unset($_POST['delbookid']);
  }
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
                    <a class="navbar-brand" href="">Takoda Register</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../books/books.php">Buy</a></li>
                        <li><a href="../books/createlisting.php">Sell</a></li>
                        <li><a href="../about/about.php">About Me</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
					    if(isset($_SESSION['user'])!="")
                        {
						  echo "<li class='active'><a href=''><span class='glyphicon glyphicon-user'></span> " . ucfirst($_SESSION['email_id']) . "</a></li>";
                          echo "<li><a href='../login/logout.php'><span class='glyphicon glyphicon-log-out'></span>" . " Logout" . "</a></li>";
                        }
						else
						{
						  echo "<li><a href='../login/register.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
                          echo "<li><a href='../login/login.php'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>";
						}
					?>
                    </ul>
                    <div class="col-sm-3 col-md-3 pull-right">
                    <form method="post" action="../books/books.php" class="navbar-form" role="search">
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

        <div class="container">
            <div class="page-header">
                <h3> <?php echo ucfirst($_SESSION['email_id']) ?> </h3> </div>
            <?php if(!isset($_SESSION['user'])) {?>
                <div class="alert alert-warning">
                    <p><span class="glyphicon glyphicon-exclamation-sign"></span>Please <a href="../login/login.php"> Login </a> to view your listings.</p>
                </div>
                <?php    } ?>
                    <p> View, or Delete your listings below:</p> 
                        <!-- Table Displaying All Patterns -->
                        <table class="table table-hover">
                            <?php 
if(isset($_SESSION['user'])) {
    $table = '<thead><tr><th>Title</th><th>Author</th><th>Course</th><th>Year</th><th>ISBN</th><th>Price</th><th>Condition</th><th>Date Posted</th><th>Delete</th></tr></thead><tbody><tr>';
    $sql = 'SELECT * FROM book_listings WHERE userID = ' . $_SESSION["user"] . ' ORDER BY listDate DESC';
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
        array_push ($books, substr($row['listDate'], 5,6 ));
        array_push ($books, "<form method='post' action=''><button type='submit' class='btn btn-danger' name='btn-delete'><span class='glyphicon glyphicon-trash'></span></button><input type='hidden' name='delbookid' value='" . $row["id"] . "'></form>");
        
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
        if($count == 0){ echo "You currently have no textbooks for sale.<br/> Create a listing for your textbook by clicking&nbsp;" . "<a href='../books/createlisting.php'>here</a>";}
}
?>
                        </table>
        </div>
    </body>

    </html>
