<?php 
include"../includes/dbconnect.inc.php";

if(array_key_exists("submit", $_POST)){
    $username = trim(stripslashes(htmlspecialchars($_POST["username"]))); 
    $password = trim(stripslashes(htmlspecialchars($_POST["password"]))); 
    $password = password_hash($password, PASSWORD_DEFAULT);
    $firstname = trim(stripslashes(htmlspecialchars($_POST["firstname"])));
    $lastname = trim(stripslashes(htmlspecialchars($_POST["lastname"]))); 
    $email = trim(stripslashes(htmlspecialchars($_POST["email"])));
    $phonenum = trim(stripslashes(htmlspecialchars($_POST["phoneNum"])));   

    $query1 = "SELECT USERNAME AS NUM FROM PROFILE WHERE USERNAME = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $query2 = "SELECT FIRSTNAME AS NUM FROM EMPLOYEE_PROFILE WHERE FIRSTNAME = ? AND LASTNAME = ? AND EMAIL = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("sss", $firstname, $lastname, $email);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if($result->num_rows === 0 && $result2->num_rows === 0){   //there are no user registered under this username and names
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
        $type = "e";
        $stmt->bind_param("isss", $count, $username, $password, $type);
        if($stmt->execute()){
            $query1 = "INSERT INTO EMPLOYEE_PROFILE (FIRSTNAME, LASTNAME, EMAIL, PHONE_NUM, PROFILE_ID) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query1);
            $stmt->bind_param("ssssi", $firstname, $lastname, $email, $phonenum, $count);
            if($stmt->execute()){
                header("Location: ../profile.php");
            }
            else{
                echo "Error: Please Try Again Later (21)";
            }
        }
        else{
            echo "Error: Please Try Again Later (20)";
        }
    }
    else{
        echo "<p class='text-warning'>Username Already Exists!</p>";
    }
}
?>