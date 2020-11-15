<?php 
$repairID = trim(stripslashes(htmlspecialchars($_GET['id'])));
$username = trim(stripslashes(htmlspecialchars($_COOKIE["username"])));

$query1 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customers id for verification because we are using GET method
$stmt = $conn->prepare($query1);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach($result as $r){
    $cusID = $r["ID"];
}

//verify that the current customer id matches the associated customers id from repair id
$query1 = "SELECT COUNT(*) AS NUM FROM INQUIRY AS i INNER JOIN STATUS_ESTIMATE AS s ON i.REPAIR_ID = s.REPAIR_ID WHERE i.REPAIR_ID = ? AND i.CUS_ID = ?";
$stmt = $conn->prepare($query1);
$stmt->bind_param("ii", $repairID, $cusID);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach($result as $r1){
    $verify = $r1['NUM'];
}

if($verify > 0){    //We will be displaying the correct customers info
    //get estimate info
    $query2 = "SELECT STATUS_ID, REPAIRABLE, ESTIMATE, DATE_POSTED FROM STATUS_ESTIMATE WHERE REPAIR_ID = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("i", $repairID);
    $stmt2->execute();
    $result2 = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    //get invoice info
    $query3 = "SELECT STATUS_ID, INVOICE, DATE_POSTED FROM STATUS_INVOICE WHERE REPAIR_ID = ?";
    $stmt3 = $conn->prepare($query3);
    $stmt3->bind_param("i", $repairID);
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    $query5 = "SELECT AMOUNT_DUE, AMOUNT_PAID FROM CHECKOUT WHERE REPAIR_ID = ?";
    $stmt5 = $conn->prepare($query5);
    $stmt5->bind_param("i", $repairID);

    //get shipping info
    $query4 = "SELECT TRACKING_NUM, DATE_POSTED FROM STATUS_SHIPPING WHERE REPAIR_ID = ?";
    $stmt4 = $conn->prepare($query4);
    $stmt4->bind_param("i", $repairID);
    $stmt4->execute();
    $result4 = $stmt4->get_result();

    //display Esimate Status
    echo "<table class='table'>
        <thead>
            <tr>
                <th>Repairable</th>
                <th>Estimate</th>
                <th>Date Posted</th>
            </tr>
        </thead>
        <tbody>";
    foreach($result2 as $r2){
        echo "
        <tr>
            <td>";
            if($r2['REPAIRABLE'] == 1){
                echo "YES";
            }
            else{
                echo "NO";
            }
        echo"</td>
            <td><a href='files/".$r2['ESTIMATE']."'>View Estimate</a></td>
            <td>".$r2['DATE_POSTED']."</td>
        </tr>
        ";
    }
    echo "</tbody></table>";

    //display invoice information
    echo "<table class='table'>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Amount Due</th>
                <th>Date Posted</th>
                <th>Checkout</th>
            </tr>
        </thead>
        <tbody>";
    if($result3->num_rows === 0) {
        echo "<tbody>
            <tr><td>N/A</td><td>N/A</td></tr>
        </tbody></table>";
    }
    else{
        $result3->fetch_all(MYSQLI_ASSOC);
        foreach($result3 as $r3){
            $stmt5->execute();
            $result5 = $stmt5->get_result()->fetch_all(MYSQLI_ASSOC);
            foreach($result5 as $r5){
                $amountDue = $r5["AMOUNT_DUE"];
                $amountPaid = $r5["AMOUNT_PAID"];
            }
            $amountRem = $amountDue - $amountPaid;

            echo "
            <tr>
                <td><a href='files/".$r3['INVOICE']."'>View Invoice</a></td>
                <td>".$amountRem."</td>
                <td>".$r3['DATE_POSTED']."</td>";
                if($amountPaid < $amountDue){
                    echo "<td><a href='checkout.php?id=".$repairID."'>Checkout</td>";
                }
                else{
                    echo "<td>Thank You!</td>";
                }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

    //display shipping information
    echo "<table class='table'>
        <thead>
            <tr>
                <th>Tracking Number</th>
                <th>Date Posted</th>
            </tr>
        </thead>
        <tbody>";
    if($result4->num_rows === 0) {
        echo "<tbody>
            <tr><td>N/A</td><td>N/A</td></tr>
        </tbody></table>";
    }
    else{
        foreach($result4 as $r4){ 
            echo "
            <tr>
                <td>".$r4['TRACKING_NUM']."</td>
                <td>".$r4['DATE_POSTED']."</td>
            </tr>
            ";
        }
        echo "</tbody></table>";
    }
}
else{ //display nothing if they hacker changed value from GET to inject or if there is nothing no updates have been made to the inquiry
    echo "<ul><table class='table'>
        <thead class='thead-dark'>
            <tr>
                <th>Repairable</th>
                <th>Estimate</th>
                <th>Date Posted</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
            </tr>
        </tbody>
        </table>";
    echo "<table class='table'>
        <thead class='thead-dark'>
            <tr>
                <th>Invoice</th>
                <th>Date Posted</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>N/A</td>
                <td>N/A</td>
            </tr>
        </tbody>
        </table>";
    echo "<table class='table'>
        <thead class='thead-dark'>
            <tr>
                <th>Tracking Number</th>
                <th>Date Posted</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>N/A</td>
                <td>N/A</td>
            </tr>
        </tbody>
        </table></ul>";
}   
?>