<?php 
if(array_key_exists("estSubmit", $_POST)){  //upload estimate
    include"../includes/dbconnect.inc.php";
    $repairID = trim(stripslashes(htmlspecialchars($_GET['id'])));
    $date = date("Ymd");

    //find if it is repairable
    $repairable = trim(stripslashes(htmlspecialchars($_POST['repairable'])));
    if($repairable == "true"){
        $repairable = 1;
    }
    else {
        $repairable = 0;
    }

    //to get the status id
    $query = "SELECT COUNT(*) AS NUM FROM STATUS_ESTIMATE WHERE REPAIR_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $repairID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $statusID = ($r["NUM"] + 1).$repairID;
    }

    if(isset($_FILES["estimate"]["type"]) && $_FILES["estimate"]["error"] == UPLOAD_ERR_OK){  //upload estimate
        $target_dir = "../files/";
		$target_file = $target_dir.$statusID.basename($_FILES["estimate"]["name"]);
        $file_type = pathinfo($target_file,PATHINFO_EXTENSION);
        $accepted = array("jpg", "jpeg", "png", "pdf", "JPG", "JPEG", "PNG", "PDF");

        if(in_array($file_type, $accepted)){
            //change file name to assocaited estimate id and upload to "cloud" server
            $file = $statusID.$_FILES['estimate']['name'];

            if(move_uploaded_file($_FILES['estimate']['tmp_name'], $target_file)){
                $query1 = "INSERT INTO STATUS_ESTIMATE (STATUS_ID, REPAIR_ID, REPAIRABLE, ESTIMATE, DATE_POSTED) VALUES (?, ?, ?, ?,".$date.")";
                $stmt = $conn->prepare($query1);
                $stmt->bind_param("iiis", $statusID, $repairID, $repairable, $file);
                if(!$stmt->execute()){
                    echo "Error: Please Try Again Later!";
                }
                else{
                    header("location: ../inquiryEmp.php");
                }
            }
            else{
                echo "The file was not moved to the cloud folder... You will be redirected in 10 seconds";
                sleep(10);
                header("location: ../inquiryEmp.php");
            }
        }
        else{
            echo "The file is not the correct type Error(14)";
        }
    }
}

if(array_key_exists("invSubmit", $_POST)){  //upload invoice
    include"../includes/dbconnect.inc.php";
    $repairID = trim(stripslashes(htmlspecialchars($_GET['id'])));
    $date = date("Ymd");

    //to get the status id
    $query = "SELECT STATUS_ID FROM STATUS_ESTIMATE WHERE REPAIR_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $repairID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $statusID = $r["STATUS_ID"];
    }

    if(isset($_FILES["invoice"]["type"]) && $_FILES["invoice"]["error"] == UPLOAD_ERR_OK){ 
        $target_dir = "../files/";
		$target_file = $target_dir.$statusID.basename($_FILES["invoice"]["name"]);
        $file_type = pathinfo($target_file,PATHINFO_EXTENSION);
        $accepted = array("jpg", "jpeg", "png", "pdf", "JPG", "JPEG", "PNG", "PDF");

        if(in_array($file_type, $accepted)){
            $file = $statusID.$_FILES['invoice']['name'];
            
            if(move_uploaded_file($_FILES['invoice']['tmp_name'], $target_file)){
                //make a record for checkout, this will be updated later
                $query2 = "SELECT CUS_ID FROM INQUIRY WHERE REPAIR_ID = ?";
                $stmt = $conn->prepare($query2);
                $stmt->bind_param("i", $repairID);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                foreach($result as $r){
                    $cusID = $r["CUS_ID"];
                }

                $query2 = "SELECT COUNT(*) AS NUM FROM CHECKOUT WHERE REPAIR_ID = ?";
                $stmt = $conn->prepare($query2);
                $stmt->bind_param("i", $repairID);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                foreach($result as $r){
                    $transID = $statusID.($r["NUM"] + 1);
                }

                $amountDue = trim(stripslashes(htmlspecialchars($_POST["amountDue"])));
                $amountPaid = 0.00;
                $query2 = "INSERT INTO CHECKOUT (TRANS_ID, CUS_ID, REPAIR_ID, AMOUNT_DUE, AMOUNT_PAID) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query2);
                $stmt->bind_param("iiidd", $transID, $cusID, $repairID, $amountDue, $amountPaid);
                $stmt->execute();

                //inset into invoice table
                $query1 = "INSERT INTO STATUS_INVOICE (STATUS_ID, REPAIR_ID, INVOICE, DATE_POSTED) VALUES (?, ?, ?,".$date.")";
                $stmt = $conn->prepare($query1);
                $stmt->bind_param("iis", $statusID, $repairID, $file);
                if(!$stmt->execute()){
                    echo "Error: Please Try Again Later!";
                }
                else{
                    header("location: ../inquiryEmp.php");
                }
            }
            else{
                echo "The file was not moved to the cloud folder... You will be redirected in 10 seconds";
                sleep(10);
                header("location: ../inquiryEmp.php");
            }
        }
        else{
            echo "The file is not the correct type Error(14)";
        }
    }
}

if(array_key_exists("shipSubmit", $_POST)){ //upload tracking
    include"../includes/dbconnect.inc.php";
    $repairID = trim(stripslashes(htmlspecialchars($_GET['id'])));
    $tracking = trim(stripslashes(htmlspecialchars($_POST['tracking'])));
    $date = date("Ymd");

    //to get the status id
    $query = "SELECT STATUS_ID FROM STATUS_INVOICE WHERE REPAIR_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $repairID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $statusID = $r["STATUS_ID"];
    }
            
    $query1 = "INSERT INTO STATUS_SHIPPING (STATUS_ID, REPAIR_ID, TRACKING_NUM, DATE_POSTED) VALUES (?, ?, ?,".$date.")";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("iis", $statusID, $repairID, $tracking);
    if(!$stmt->execute()){
        echo "Error: Please Try Again Later!";
    }
    else{
        header("location: ../inquiryEmp.php");
    }
}
else{
    include"includes/dbconnect.inc.php";

    $repairID = trim(stripslashes(htmlspecialchars($_GET['id'])));
    $status = trim(stripslashes(htmlspecialchars($_GET['status'])));

    if($status == "ship"){
        $query = "SELECT * FROM STATUS_SHIPPING WHERE REPAIR_ID = ?";   //check if the status truly does neeed updating
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $repairID);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0){  //it does
            echo "<form action='includesEmp/statusFormEmp.inc.php?id=".$repairID."' method='POST'>
                <label for='tracking'>Tracking Number: </label>
                <input type='text' class='form-control id='tracking' name='tracking' maxlength='22'></input>
                <button type='submit' class='btn btn-primary' name='shipSubmit'>Submit Tracking</button>
            </form>";
        }
        else{   //doesnt need updating
            header("location: ../inquiryEmp.php");
        }
    }
    else{
        if($status == "inv"){
            $query = "SELECT * FROM STATUS_INVOICE WHERE REPAIR_ID = ?"; //check if the status truly does neeed updating
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $repairID);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0){  //it does
                echo "<form action='includesEmp/statusFormEmp.inc.php?id=".$repairID."' method='POST' enctype='multipart/form-data'>
                    <label for='amoundDue'>Amount Due: </label>
                    <input type='text' class='form-control' name='amountDue' id='amountDue'>
                    <label for='fileUpload'>Please select an the invoice to upload: </label>
                    <input type='file' class='form-control-file' name='invoice' id='fileUpload'><br>
                    <button type='submit' class='btn btn-primary' name='invSubmit'>Submit Invoice</button>
                </form>";
            }
            else{       //doesnt need updating
                header("location: ../inquiryEmp.php");
            }
        }
        else{
            if($status == "est"){
                $query = "SELECT * FROM STATUS_ESTIMATE WHERE REPAIR_ID = ?"; //check if the status truly does neeed updating
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $repairID);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows === 0){     //it does
                    echo "<form action='includesEmp/statusFormEmp.inc.php?id=".$repairID."' method='POST' enctype='multipart/form-data'>
                    <p>Repairable: </p>
                    <div class='form-check form-check-inline'>
                        <input class='form-check-input' type='radio' id='repairable' name='repairable' value='true'>
                        <label class='form-check-label' for='repairable'>Yes</label>
                    </div>
                    <div class='form-check form-check-inline'>
                        <input class='form-check-input' type='radio' id='repairable' name='repairable' value='false'>
                        <label class='form-check-label' for='repairable'>No</label>
                    </div><br><br>
                    <label for='fileUpload'>Please select an the estimate to upload: </label>
                    <input type='file' class='form-control-file' name='estimate' id='fileUpload'><br>
                    <button type='submit' class='btn btn-primary' name='estSubmit'>Submit Estimate</button>
                </form>";
                }
                else{   //doesnt need updating
                    header("location: ../inquiryEmp.php");
                }
            }
            else{   //get method doesnt bring back the right status to update
                header("Location: ../inquiryEmp.php");
            }
        }
    }
}
?>