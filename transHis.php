<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <title>Profile</title>
  </head>
  <body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1537f4;">
        <div class="container">
            <a class="navbar-brand w-50" href="home.php"><img src="images/logo21.png" height="68px" width="173.5px"></a>
            <!-- <a class="navbar-brand w-50" href="#"><img src="images/logo2.png" height="50px" width="50px"></a> -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex flex-row-reverse">
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link active" href="home.php">Home</a>
                        <?php 
                            if(!isset($_COOKIE['username'])){
                                echo"<a class='nav-item nav-link active' href='login.php'>Sign In</a>";
                            }
                            else{
                                echo"<a class='nav-item nav-link active' href='profile.php'>Profile</a>";
                                echo"<a class='nav-item nav-link active' href='includes/logout.inc.php'>Log Out</a>";
                            }
                        ?>
                    </div>
                </div>
            </div>    
        </div>   
    </nav>
</header>
<main class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-2 profile-list">
                <?php 
                    include"includes/dbconnect.inc.php";
                    if(isset($_COOKIE['username'])){
                        $username = trim(stripslashes(htmlspecialchars($_COOKIE["username"])));
                        echo"<p>Welcome, <a href='profile.php'>".$username."</a></p>";
                        
                        //check if we have their information
                        $query1 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customers id
                        $stmt = $conn->prepare($query1);
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                        foreach($result as $r){
                            $cusID = $r["ID"];
                        }
                        
                        $query2 = "SELECT COUNT(*) AS NUM FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = ?"; //get the number of records of this customer
                        $stmt2 = $conn->prepare($query2);
                        $stmt2->bind_param("i", $cusID);
                        $stmt2->execute();
                        $result1 = $stmt2->get_result();
                        $result1->fetch_all(MYSQLI_ASSOC);
                        foreach($result1 as $r1){
                            $numOfRows = $r1["NUM"];
                        }
                        if($numOfRows == 0){    //we need their information
                            header("location: custInfoForm.php");
                        }
                    }
                    else{   //log out if do not have their information
                        header('location: login.php');
                    }
                ?>
                <p><a href="inquiry.php">New Inquiry</a></p>
                <p><a href="status.php">Status</a></p>
                <p><a href="custInfo.php">Information</a></p>
                <p><a href="transHis.php">Transaction History</a></p>
            </div>
            <div class="col d-flex justify-content-center">
                <?php 
                    include"includes/transHistory.inc.php";
                ?>
                </table>
            </div>
        </div>
    </div>
</div>
</main>
<footer class="footer">
    <div class="text-dark" style="background-color: #e6e6e6;">
        <div class="container footer-padding">
            <a class="footer-anchor" href="home.php">Home</a>
            <?php 
                if(!isset($_COOKIE['username'])){
                    echo"<a class='footer-anchor' href='login.php'>Sign In</a>";
                }
                else{
                    echo"<a class='footer-anchor' href='profile.php'>Profile</a>";
                    echo"<a class='footer-anchor' href='includes/logout.inc.php'>Log Out</a>";
                }
            ?>
            <hr>
            <p>Copyright 2020</p>
            <img src="images/acceptedPayments.png" width="175" height="35" alt="accepted payment types">
            <hr>
            <div class="">
                <a class="footer-anchor" href="#"><img src="images/facebook.png" width="35" height="35" alt="facebook"></a>
                <a class="footer-anchor" href="#"><img src="images/twitter.png" width="35" height="35" alt="twitter"></a>
           </div>
        </div>       
    </div>
</footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>