<?php 
include"dbconnect.inc.php";

$repairID = trim(stripslashes(htmlspecialchars($_GET['id'])));

//verify that the customer is associated for with this repair
$query = "SELECT CUS_ID FROM INQUIRY WHERE REPAIR_ID = ?";  //first get the customer id associated with the repair
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $repairID);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows !== 0){
    $result->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $cusID = $r['CUS_ID'];
    }

    $query2 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customer id associated with the current user
    $stmt2 = $conn->prepare($query2);
    $username = trim(stripslashes(htmlspecialchars($_COOKIE['username'])));
    $stmt2->bind_param("s", $username);
    $stmt2->execute();
    $result2 = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result2 as $r2){
        $currentCusID =$r2["ID"];
    }

    if($currentCusID == $cusID){    //verify the current customer matches the repairs associated customer id
        $query1 = "SELECT TRANS_ID, AMOUNT_DUE FROM CHECKOUT WHERE REPAIR_ID = ? AND CUS_ID = ? AND AMOUNT_PAID < AMOUNT_DUE";  //verify that they need to pay
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("ii", $repairID, $cusID);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        if ($result1->num_rows === 0){  //they have already paid
            header("Location: status.php");
        }
        else{   //they need to pay
            $result1->fetch_all(MYSQLI_ASSOC);
            foreach($result1 as $r1){
                $amountDue = $r1['AMOUNT_DUE'];
                $trans_ID = $r1['TRANS_ID'];
            }

            if(array_key_exists("next", $_POST)){
                if(isset($_POST["billing"])){   //if user wants their existing info entered for billing
                    if(isset($_POST["shipping"])){  //if user want their existing info entered for shipping and billing
                        echo "<form action='checkout.php?id=".$repairID."' method='POST'>
                            <p>YOU OWE: $".$amountDue."</p>
                            <p>BILLING:</p>
                            <label for='creditcard'>Credit Card: </label>
                            <input type='text' class='form-control' name='cardNum' id='creditcard'>
                            <label for='ccv'>CCV: </label>
                            <input type='text' class='form-control' name='ccv' id='ccv'>
                            <label for='exp'>Expiration Date:</label>
                            <input type='text' class='form-control' name='exp' id='exp'>
                            <button type='sumbit' class='btn btn-primary' name='submitInfo'>Submit Info</button>
                        </form>";
                    }
                    else{   //if user want their exisitng info entered only for billing and not for shipping
                        echo "
                        <form action='checkout.php?id=".$repairID."' method='POST'>
                            <p>YOU OWE: $".$amountDue."</p>
                            <p>BILLING:</p>
                            <label for='creditcard'>Credit Card: </label>
                            <input type='text' class='form-control' name='cardNum' id='creditcard'>
                            <label for='ccv'>CCV: </label>
                            <input type='text' class='form-control' name='ccv' id='ccv'>
                            <label for='exp'>Expiration Date:</label>
                            <input type='text' class='form-control' name='exp' id='exp'>
                            <br><p>SHIPPING: </p>
                            <label for='shipFirstName'>First Name: </label>
                            <input type='text' class='form-control' name='shipFirstName' id='shipFirstName'>
                            <label for='shipLastName'>Last Name: </label>
                            <input type='text' class='form-control' name='shipLastName' id='shipLastName'>
                            <label for='billaddress'>Address: </label>
                            <input type='text' class='form-control' name='shipAddress' id='billaddress'>
                            <label for='billcity'>City: </label>
                            <input type='text' class='form-control' name='shipCity' id='billcity'>
                            <label for='billstate'>State: </label>
                            <input type='text' class='form-control' name='shipState' id='billstate' maxlength='2'>
                            <label for='billzip'>Zip Code: </label>
                            <input type='text' class='form-control' name='shipZip' id='billzip' maxlength='5'>
                            <button type='sumbit' class='btn btn-primary' name='submitInfoNoB'>Submit Info</button>
                        </form>";
                    }
                }
                else{   //if user does NOT want their existing info entered for billing
                    if(isset($_POST["shipping"])){  //if user wants their existing info entered only for shipping and not billing
                        echo "
                        <form action='checkout.php?id=".$repairID."' method='POST'>
                            <p>YOU OWE: $".$amountDue."</p>
                            <p>BILLING:</p>
                            <label for='creditcard'>Credit Card: </label>
                            <input type='text' class='form-control' name='cardNum' id='creditcard'>
                            <label for='ccv'>CCV: </label>
                            <input type='text' class='form-control' name='ccv' id='ccv'>
                            <label for='exp'>Expiration Date:</label>
                            <input type='text' class='form-control' name='exp' id='exp'>
                            <label for='billFirstName'>First Name: </label>
                            <input type='text' class='form-control' name='billFirstName' id='billFirstName'>
                            <label for='billLastName'>Last Name: </label>
                            <input type='text' class='form-control' name='billLastName' id='billLastName'>
                            <label for='billAddress'>Address: </label>
                            <input type='text' class='form-control' name='billAddress' id='billAddress'>
                            <label for='billCity'>City: </label>
                            <input type='text' class='form-control' name='billCity' id='billCity'>
                            <label for='billState'>State: </label>
                            <input type='text' class='form-control' name='billState' id='billState' maxlength='2'>
                            <label for='billZip'>Zip Code: </label>
                            <input type='text' class='form-control' name='billZip' id='billZip' maxlength='5'>
                            <button type='sumbit' class='btn btn-primary' name='submitInfoNoS'>Submit Info</button>
                        </form>";
                    }
                    else{   //if user does not want their existing info entered for shipping and billing
                        echo "
                        <form action='checkout.php?id=".$repairID."' method='POST'>
                            <p>YOU OWE: $".$amountDue."</p>
                            <p>BILLING:</p>
                            <label for='creditcard'>Credit Card: </label>
                            <input type='text' class='form-control' name='cardNum' id='creditcard'>
                            <label for='ccv'>CCV: </label>
                            <input type='text' class='form-control' name='ccv' id='ccv'>
                            <label for='exp'>Expiration Date:</label>
                            <input type='text' class='form-control' name='exp' id='exp'>
                            <label for='billFirstName'>First Name: </label>
                            <input type='text' class='form-control' name='billFirstName' id='billFirstName'>
                            <label for='billLastName'>Last Name: </label>
                            <input type='text' class='form-control' name='billLastName' id='billLastName'>
                            <label for='billAddress'>Address: </label>
                            <input type='text' class='form-control' name='billAddress' id='billAddress'>
                            <label for='billCity'>City: </label>
                            <input type='text' class='form-control' name='billCity' id='billCity'>
                            <label for='billState'>State: </label>
                            <input type='text' class='form-control' name='billState' id='billState' maxlength='2'>
                            <label for='billZip'>Zip Code: </label>
                            <input type='text' class='form-control' name='billZip' id='billZip' maxlength='5'>
                            <br><p>SHIPPING: </p>
                            <label for='shipFirstName'>First Name: </label>
                            <input type='text' class='form-control' name='shipFirstName' id='shipFirstName'>
                            <label for='shipLastName'>Last Name: </label>
                            <input type='text' class='form-control' name='shipLastName' id='shipLastName'>
                            <label for='billaddress'>Address: </label>
                            <input type='text' class='form-control' name='shipAddress' id='billaddress'>
                            <label for='billcity'>City: </label>
                            <input type='text' class='form-control' name='shipCity' id='billcity'>
                            <label for='billstate'>State: </label>
                            <input type='text' class='form-control' name='shipState' id='billstate' maxlength='2'>
                            <label for='billzip'>Zip Code: </label>
                            <input type='text' class='form-control' name='shipZip' id='billzip' maxlength='5'>
                            <button type='sumbit' class='btn btn-primary' name='submitInfoNoBS'>Submit Info</button>
                        </form>";
                    }
                }
            }
            else{
                if(array_key_exists("submitInfoNoBS", $_POST)){
                    //get entered info for billing
                    $CC = trim(stripslashes(htmlspecialchars($_POST['cardNum'])));
                    $CCV = trim(stripslashes(htmlspecialchars($_POST['ccv'])));
                    $exp = trim(stripslashes(htmlspecialchars($_POST['exp'])));
                    $billFirstname = trim(stripslashes(htmlspecialchars($_POST['billFirstName'])));
                    $billLastname = trim(stripslashes(htmlspecialchars($_POST['billLastName'])));
                    $billAddress = trim(stripslashes(htmlspecialchars($_POST['billAddress'])));
                    $billCity = trim(stripslashes(htmlspecialchars($_POST['billCity'])));
                    $billState = trim(stripslashes(htmlspecialchars($_POST['billState'])));
                    $billZip = trim(stripslashes(htmlspecialchars($_POST['billZip'])));

                    //get entered info for shipping
                    $shipFirstname = trim(stripslashes(htmlspecialchars($_POST['shipFirstName'])));
                    $shipLastname = trim(stripslashes(htmlspecialchars($_POST['shipLastName'])));
                    $shipAddress = trim(stripslashes(htmlspecialchars($_POST['shipAddress'])));
                    $shipCity = trim(stripslashes(htmlspecialchars($_POST['shipCity'])));
                    $shipState = trim(stripslashes(htmlspecialchars($_POST['shipState'])));
                    $shipZip = trim(stripslashes(htmlspecialchars($_POST['shipZip'])));

                    //prepare for billing table
                    $query4 = "INSERT INTO CHECKOUT_BILLING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP, CARD_NUM, EXP_DATE, CCV) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt4 = $conn->prepare($query4);
                    $stmt4->bind_param("isssssssss", $trans_ID, $billFirstname, $billLastname, $billAddress, $billCity, $billState, $billZip, $CC, $exp, $CCV);

                    //prepare for shipping table
                    $query5 = "INSERT INTO CHECKOUT_SHIPPING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt5 = $conn->prepare($query5);
                    $stmt5->bind_param("issssss", $trans_ID, $shipFirstname, $shipLastname, $shipAddress, $shipCity, $shipState, $shipZip);

                    //prepare to update the amount paid
                    $query6 = "UPDATE CHECKOUT SET AMOUNT_PAID = AMOUNT_DUE, DATE_POSTED = ? WHERE TRANS_ID = ?";
                    $stmt6 = $conn->prepare($query6);
                    $date = date("Ymd");
                    $stmt6->bind_param("si", $date, $trans_ID);
                    
                    //execute
                    if($stmt4->execute()){
                        if($stmt5->execute()){
                            if($stmt6->execute()){
                                header("location: profile.php");
                            }
                            else{
                                echo"There was an error (53), redirecting in 10 secs..";
                                sleep(10);
                                header("Location: checkout.php?id=".$repairID."");
                            }
                        }
                        else{
                            echo"There was an error (52), redirecting in 10 secs..";
                            sleep(10);
                            header("Location: checkout.php?id=".$repairID."");
                        }
                    }
                    else{
                        echo"There was an error (51), redirecting in 10 secs..";
                        sleep(10);
                        header("Location: checkout.php?id=".$repairID."");
                    }
                }
                else{
                    if(array_key_exists("submitInfoNoB", $_POST)){
                        //get their exisiting info for billing
                        $query3 = "SELECT FIRSTNAME, LASTNAME, ADDRESS, CITY, STATE, ZIP FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = ?";
                        $stmt3 = $conn->prepare($query3);
                        $stmt3->bind_param("i", $cusID);
                        $stmt3->execute();
                        $result3 = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
                        foreach($result3 as $r3){
                            $firstname = $r3['FIRSTNAME'];
                            $lastname = $r3['LASTNAME'];
                            $address = $r3['ADDRESS'];
                            $city = $r3['CITY'];
                            $state = $r3['STATE'];
                            $zip = $r3['ZIP'];
                        }
                        $CC = trim(stripslashes(htmlspecialchars($_POST['cardNum'])));
                        $CCV = trim(stripslashes(htmlspecialchars($_POST['ccv'])));
                        $exp = trim(stripslashes(htmlspecialchars($_POST['exp'])));

                        //get entered info for shipping
                        $shipFirstname = trim(stripslashes(htmlspecialchars($_POST['shipFirstName'])));
                        $shipLastname = trim(stripslashes(htmlspecialchars($_POST['shipLastName'])));
                        $shipAddress = trim(stripslashes(htmlspecialchars($_POST['shipAddress'])));
                        $shipCity = trim(stripslashes(htmlspecialchars($_POST['shipCity'])));
                        $shipState = trim(stripslashes(htmlspecialchars($_POST['shipState'])));
                        $shipZip = trim(stripslashes(htmlspecialchars($_POST['shipZip'])));

                        //prepare for billing table
                        $query4 = "INSERT INTO CHECKOUT_BILLING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP, CARD_NUM, EXP_DATE, CCV) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt4 = $conn->prepare($query4);
                        $stmt4->bind_param("isssssssss", $trans_ID, $firstname, $lastname, $address, $city, $state, $zip, $CC, $exp, $CCV);

                        //prepare for shipping table
                        $query5 = "INSERT INTO CHECKOUT_SHIPPING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt5 = $conn->prepare($query5);
                        $stmt5->bind_param("issssss", $trans_ID, $shipFirstname, $shipLastname, $shipAddress, $shipCity, $shipState, $shipZip);

                        //prepare to update the amount paid
                        $query6 = "UPDATE CHECKOUT SET AMOUNT_PAID = AMOUNT_DUE, DATE_POSTED = ? WHERE TRANS_ID = ?";
                        $stmt6 = $conn->prepare($query6);
                        $date = date("Ymd");
                        $stmt6->bind_param("si", $date, $trans_ID);
                        
                        //execute
                        if($stmt4->execute()){
                            if($stmt5->execute()){
                                if($stmt6->execute()){
                                    header("location: profile.php");
                                }
                                else{
                                    echo"There was an error (53), redirecting in 10 secs..";
                                    sleep(10);
                                    header("Location: checkout.php?id=".$repairID."");
                                }
                            }
                            else{
                                echo"There was an error (52), redirecting in 10 secs..";
                                sleep(10);
                                header("Location: checkout.php?id=".$repairID."");
                            }
                        }
                        else{
                            echo"There was an error (51), redirecting in 10 secs..";
                            sleep(10);
                            header("Location: checkout.php?id=".$repairID."");
                        }
                    }
                    else{
                        if(array_key_exists("submitInfoNoS", $_POST)){
                            //get their exisiting info for shipping
                            $query3 = "SELECT FIRSTNAME, LASTNAME, ADDRESS, CITY, STATE, ZIP FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = ?";
                            $stmt3 = $conn->prepare($query3);
                            $stmt3->bind_param("i", $cusID);
                            $stmt3->execute();
                            $result3 = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
                            foreach($result3 as $r3){
                                $firstname = $r3['FIRSTNAME'];
                                $lastname = $r3['LASTNAME'];
                                $address = $r3['ADDRESS'];
                                $city = $r3['CITY'];
                                $state = $r3['STATE'];
                                $zip = $r3['ZIP'];
                            }

                            //get entered info for billing
                            $CC = trim(stripslashes(htmlspecialchars($_POST['cardNum'])));
                            $CCV = trim(stripslashes(htmlspecialchars($_POST['ccv'])));
                            $exp = trim(stripslashes(htmlspecialchars($_POST['exp'])));
                            $billFirstname = trim(stripslashes(htmlspecialchars($_POST['billFirstName'])));
                            $billLastname = trim(stripslashes(htmlspecialchars($_POST['billLastName'])));
                            $billAddress = trim(stripslashes(htmlspecialchars($_POST['billAddress'])));
                            $billCity = trim(stripslashes(htmlspecialchars($_POST['billCity'])));
                            $billState = trim(stripslashes(htmlspecialchars($_POST['billState'])));
                            $billZip = trim(stripslashes(htmlspecialchars($_POST['billZip'])));

                            //prepare for billing table
                            $query4 = "INSERT INTO CHECKOUT_BILLING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP, CARD_NUM, EXP_DATE, CCV) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt4 = $conn->prepare($query4);
                            $stmt4->bind_param("isssssssss", $trans_ID, $billFirstname, $billLastname, $billAddress, $billCity, $billState, $billZip, $CC, $exp, $CCV);

                            //prepare for shipping table
                            $query5 = "INSERT INTO CHECKOUT_SHIPPING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP) VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $stmt5 = $conn->prepare($query5);
                            $stmt5->bind_param("issssss", $trans_ID, $firstname, $lastname, $address, $city, $state, $zip);

                            //prepare to update the amount paid
                            $query6 = "UPDATE CHECKOUT SET AMOUNT_PAID = AMOUNT_DUE WHERE TRANS_ID = ?";
                            $stmt6 = $conn->prepare($query6);
                            $stmt6->bind_param("i", $trans_ID);
                            
                            //execute
                            if($stmt4->execute()){
                                if($stmt5->execute()){
                                    if($stmt6->execute()){
                                        header("location: profile.php");
                                    }
                                    else{
                                        echo"There was an error (53), redirecting in 10 secs..";
                                        sleep(10);
                                        header("Location: checkout.php?id=".$repairID."");
                                    }
                                }
                                else{
                                    echo"There was an error (52), redirecting in 10 secs..";
                                    sleep(10);
                                    header("Location: checkout.php?id=".$repairID."");
                                }
                            }
                            else{
                                echo"There was an error (51), redirecting in 10 secs..";
                                sleep(10);
                                header("Location: checkout.php?id=".$repairID."");
                            }
                        }
                        else{
                            if(array_key_exists("submitInfo", $_POST)){
                                $query3 = "SELECT FIRSTNAME, LASTNAME, ADDRESS, CITY, STATE, ZIP FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = ?";
                                $stmt3 = $conn->prepare($query3);
                                $stmt3->bind_param("i", $cusID);
                                $stmt3->execute();
                                $result3 = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
                                foreach($result3 as $r3){
                                    $firstname = $r3['FIRSTNAME'];
                                    $lastname = $r3['LASTNAME'];
                                    $address = $r3['ADDRESS'];
                                    $city = $r3['CITY'];
                                    $state = $r3['STATE'];
                                    $zip = $r3['ZIP'];
                                }

                                //get card info
                                $CC = trim(stripslashes(htmlspecialchars($_POST['cardNum'])));
                                $CCV = trim(stripslashes(htmlspecialchars($_POST['ccv'])));
                                $exp = trim(stripslashes(htmlspecialchars($_POST['exp'])));
                                
                                //prepare for billing table
                                $query4 = "INSERT INTO CHECKOUT_BILLING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP, CARD_NUM, EXP_DATE, CCV) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt4 = $conn->prepare($query4);
                                $stmt4->bind_param("isssssssss", $trans_ID, $firstname, $lastname, $address, $city, $state, $zip, $CC, $exp, $CCV);

                                //prepare for shipping table
                                $query5 = "INSERT INTO CHECKOUT_SHIPPING_INFO (TRANS_ID, FIRST_NAME, LAST_NAME, ADDRESS, CITY, STATE, ZIP) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                $stmt5 = $conn->prepare($query5);
                                $stmt5->bind_param("issssss", $trans_ID, $firstname, $lastname, $address, $city, $state, $zip);

                                //prepare to update the amount paid
                                $query6 = "UPDATE CHECKOUT SET AMOUNT_PAID = AMOUNT_DUE, DATE_POSTED = ? WHERE TRANS_ID = ?";
                                $stmt6 = $conn->prepare($query6);
                                $date = date("Ymd");
                                $stmt6->bind_param("si", $date, $trans_ID);
                                
                                //execute
                                if($stmt4->execute()){
                                    if($stmt5->execute()){
                                        if($stmt6->execute()){
                                            header("location: profile.php");
                                        }
                                        else{
                                            echo"There was an error (53), redirecting in 10 secs..";
                                            sleep(10);
                                            header("Location: checkout.php?id=".$repairID."");
                                        }
                                    }
                                    else{
                                        echo"There was an error (52), redirecting in 10 secs..";
                                        sleep(10);
                                        header("Location: checkout.php?id=".$repairID."");
                                    }
                                }
                                else{
                                    echo"There was an error (51), redirecting in 10 secs..";
                                    sleep(10);
                                    header("Location: checkout.php?id=".$repairID."");
                                }
                            }
                            else{
                                echo "<form action='checkout.php?id=".$repairID."' method='POST'><br><br>
                                        <div class='form-check form-check-inline'>
                                            <input class='form-check-input' type='checkbox' id='billing' name='billing' value='true'>
                                            <label class='form-check-label' for='billing'>Billing: Use your info</label>
                                        </div>
                                        <div class='form-check form-check-inline'>
                                            <input class='form-check-input' type='checkbox' id='shipping' name='shipping' value='true'>
                                            <label class='form-check-label' for='shipping'>Shipping: Use your info</label>
                                        </div><br><br>
                                        <button type='sumbit' class='btn btn-primary' name='next'>Next</button>
                                    </form>";
                            }
                        }
                    }
                }
            }
        }
    }
}
else{
    header("Location: status.php");
}
?>