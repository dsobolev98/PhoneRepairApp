<?php 
    include"includes/dbconnect.inc.php";
    $username = trim(stripslashes(htmlspecialchars($_COOKIE["username"])));
    if(isset($_COOKIE["username"])){
        //check if we have their information
        $query1 = "SELECT TYPE FROM PROFILE WHERE USERNAME = ?";  //get the customers id
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("s", $_COOKIE['username']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($result as $r){
            $cusType = $r["TYPE"];
        }

        echo"<p>Welcome, <a href='profile.php'>".$username."</a></p>";
        if($cusType == "c") {  //for customers      
            //check if we have their information like address and email
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
            if($numOfRows == 0){    //if we dont prompt the customer infomation form
                header("location: custInfoForm.php");
            }
            echo "
            <p><a href='inquiry.php'>New Inquiry</a></p>
            <p><a href='status.php'>Status</a></p>
            <p><a href='custInfo.php'>Information</a></p>
            <p><a href='transHis.php'>Transaction History</a></p>
            ";
        }
        
        if($cusType == "e"){   //for employes 
            //we do not need to check for their information beause it will be entered in the making process
            echo "
            <p><a href='inquiryEmp.php'>View Customer Inquiries</a></p>
            <p><a href='registerEmp.php'>Create New Worker</a></p>
            ";
        }
    }
    else{
        header('location: login.php');
    }
?>