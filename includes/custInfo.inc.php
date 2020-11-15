<?php
include"includes/dbconnect.inc.php";

if(isset($_COOKIE['username'])){            
    //check if we have their information
    $query1 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customers id
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $_COOKIE['username']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $cusID = $r["ID"];
    }
            
    $query1 = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("i", $cusID);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        header("location: custInfoForm.php");
    }
    else{
        $quer1 = "SELECT FIRSTNAME, LASTNAME, EMAIL, PHONE_NUM, ADDRESS, CITY, STATE, ZIP FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = ?";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("i", $cusID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach($result as $r){
            echo "
            <p>First Name: ".$r["FIRSTNAME"]."</p>
            <p>Last Name: ".$r["LASTNAME"]."</p>
            <p>Email: ".$r["EMAIL"]."</p>
            <p>Phone Number: ".$r["PHONE_NUM"]."</p>
            <p>Address: ".$r["ADDRESS"]."</p>
            <p>City: ".$r["CITY"]."</p>
            <p>State: ".$r["STATE"]."</p>
            <p>Zip: ".$r["ZIP"]."</p>
            ";
        }
    }
}
else{
    header('location: login.php');
}
?>