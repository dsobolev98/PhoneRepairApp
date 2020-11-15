<?php 
include"dbconnect.inc.php";

if(array_key_exists("submit", $_POST)){
    //get info from post
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $email = $_POST['email'];
    $phone_num = $_POST['phoneNum'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    //check if we have their information
    $query1 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customers id
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $_COOKIE['username']);
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

    //if there isnt a record then we insert
    if($numOfRows == 0){
        echo "we are inserting";
        $query1 = "INSERT INTO CUSTOMER_PROFILE (FIRSTNAME, LASTNAME, EMAIL, PHONE_NUM, ADDRESS, CITY, STATE, ZIP, CUSTOMER_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("ssssssssi", $firstname, $lastname, $email, $phone_num, $address, $city, $state, $zip, $cusID);
        if(!$stmt->execute()){
            echo "Error: Please Try Again Later";
        }
        else{
            header("location: profile.php");
        }
    }
    else{   //else then we update
        $query1 = "UPDATE CUSTOMER_PROFILE SET FIRSTNAME = ?, LASTNAME = ?, EMAIL = ?, PHONE_NUM = ?, ADDRESS = ?, CITY = ?, STATE = ?, ZIP = ? WHERE CUSTOMER_ID = ?";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("ssssssssi", $firstname, $lastname, $email, $phone_num, $address, $city, $state, $zip, $cusID);
        if(!$stmt->execute()){
            echo "Error: Please Try Again Later";
        }
        else{
            header("location: custInfo.php");
        }
    }
}
?>