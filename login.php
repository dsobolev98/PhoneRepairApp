<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <title>Phone Repair</title>
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
    <?php 
        include"includes/login.inc.php";
        if(isset($_COOKIE['username'])){
            header("location: profile.php");
        }
    ?> 
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="userNameInput">User Name</label>
            <input type="text" class="form-control" id="userNameInput" name="userName" maxlength="20">
        </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input type="password" class="form-control" id="inputPassword" name="password" minlength="6">
        </div>
        <!-- <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="remeberCheck">
            <label class="form-check-label" for="remeberCheck">Remeber Me</label>
        </div> -->
        <button type="submit" class="btn btn-primary" name="login">Sign In</button>
        <button type="submit" class="btn btn-primary" name="register">Register</button>
    </form>
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