<?php
include"dbconnect.inc.php";

if(array_key_exists('register', $_POST)){
    $username = trim(stripslashes(htmlspecialchars($_POST["userName"]))); 
    $pwd = trim(stripslashes(htmlspecialchars($_POST["password"]))); 
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);

    $query1 = "SELECT USERNAME FROM PROFILE WHERE USERNAME = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){            //there are no user registered under this username
        $queryCount = "SELECT COUNT(*) AS NUM FROM PROFILE";
        $stmt = $conn->prepare($queryCount);
        if(!$stmt->execute()){
            echo "<p class='text-danger'>Error! Please Try Again!</p>";
        }
        
        $result = $stmt->get_result();
        $result->fetch_all(MYSQLI_ASSOC);
        foreach($result as $countArray){
            $count = $countArray["NUM"] + 1;
        }

        $query1 = "INSERT INTO PROFILE (ID, USERNAME, PASSWORD, TYPE) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query1);
        $type = "c";
        $stmt->bind_param("isss", $count, $username, $pwd, $type);
        if($stmt->execute()){
            echo "<p class='text-success'>Account Created!</p>";
        }
    }
    else{
        echo "<p class='text-warning'>Username Already Exists!</p>";
    }
}

if(array_key_exists('login', $_POST)){
    $username = trim(stripslashes(htmlspecialchars($_POST["userName"])));
    $pwd = trim(stripslashes(htmlspecialchars($_POST["password"]))); 

    $query1 = "SELECT USERNAME, PASSWORD FROM PROFILE WHERE USERNAME = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $username);
    if(!$stmt->execute()){
        echo "<p class='text-danger'>Error! Please Try Again!</p>";
        sleep(5);
        header("Location: ../login.php");
    }
    else{
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo "<p class='text-warning'>User Does Not Exist!</p>";
        }
        else {
            $result->fetch_all(MYSQLI_ASSOC);
            foreach($result as $profile){
                
                if(password_verify($pwd, $profile["PASSWORD"])){
                    echo "<p class='text-success'>Its a match!</p>";
                    setcookie('username', $username, time()+3600, "/");
                    header('Location: profile.php');
                }
                else{
                    echo "<p class='text-warning'>Password Incorrect!</p>";
                }
            }
        }  
    }
}
?>