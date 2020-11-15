<?php
include"../includes/dbconnect.inc.php";

if(array_key_exists('submit', $_POST)){
    $make = trim(stripslashes(htmlspecialchars($_POST["make"]))); 
    $model = trim(stripslashes(htmlspecialchars($_POST["model"]))); 
    $serialNum = trim(stripslashes(htmlspecialchars($_POST["serialNum"]))); 
    $issue = trim(stripslashes(htmlspecialchars($_POST["issue"]))); 
    $date = date("Ymd");

    $query1 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customers id, for foreign key on inquiry table and part of repair id
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $_COOKIE['username']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $cusID = $r["ID"];
    }

    $query1 = "SELECT COUNT(*) AS NUM FROM INQUIRY";    //get the number of inquirys we have for repair_id
    $stmt = $conn->prepare($query1);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $repair = $r["NUM"]+1;
    }
    $repairID = $repair.$cusID;
    
    //check if file was uploaded
    if(isset($_FILES["photo"]["type"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK){  //upload inquiry with picture
        $target_dir = "../files/";
        $target_file = $target_dir.$cusID.basename($_FILES["photo"]["name"]);
        $file_type = pathinfo($target_file,PATHINFO_EXTENSION);
        $accepted = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");

        if(in_array($file_type, $accepted)){
            $image = $cusID.$_FILES['photo']['name'];
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)){
                $query1 = "INSERT INTO INQUIRY (REPAIR_ID, CUS_ID, MAKE, MODEL, SERIAL_NUM, PROBLEM, IMAGE, DATE_POSTED) VALUES (?, ?, ?, ?, ?, ?, ?,".$date.")";
                $stmt = $conn->prepare($query1);
                $stmt->bind_param("iisssss", $repairID, $cusID, $make, $model, $serialNum, $issue, $image);
                if(!$stmt->execute()){
                    echo "Error: Please Try Again Later!";
                }
                else{
                    header("location: ../status.php");
                }
            }
            else{
                echo "The image was not moved to the cloud folder... You will be redirected in 10 seconds";
                sleep(10);
                header("location: ../inquiry.php");
            }
        }
        else{
            echo "The File is not the correct type Error(14)";
        }
    }
    else{   //upload without picture
        $query1 = "INSERT INTO INQUIRY (REPAIR_ID, CUS_ID, MAKE, MODEL, SERIAL_NUM, PROBLEM, DATE_POSTED) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("iisssss", $repairID, $cusID, $make, $model, $serialNum, $issue, $date);
        if(!$stmt->execute()){
            echo "Error: Please Try Again Later!";
        }
        else{
            header("location: ../status.php?");
        }
    }
}
?>