<?php 
include"includes/dbconnect.inc.php";

$shipped = array();
$invoiced = array();
$estimated = array();
//get all the repairs ids where they have been shipped and put into array
$query = "SELECT REPAIR_ID FROM STATUS_SHIPPING";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach($result as $r){
    array_push($shipped, $r["REPAIR_ID"]);
}

//get all repair ids where they have been invoiced
$query = "SELECT REPAIR_ID FROM STATUS_INVOICE";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach($result as $r){
    array_push($invoiced, $r["REPAIR_ID"]);
}

//get all repair ids where they have been estimated
$query = "SELECT REPAIR_ID FROM STATUS_ESTIMATE";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach($result as $r){
    array_push($estimated, $r["REPAIR_ID"]);
}

//show all not completed repairs with their last status
echo "<table class='table'>
    <thead>
        <tr>
            <th>Repair ID</th>
            <th>Make</th>
            <th>Model</th>
            <th>Issue</th>
            <th>Last Status</th>
            <th>Next Status</th>
            <th>Image</th>
            <th>Amount Remaining</th>
        </tr>
    </thead>
    <tbody>";
$query1 = "SELECT REPAIR_ID, MAKE, MODEL, PROBLEM, IMAGE FROM INQUIRY";
$stmt1 = $conn->prepare($query1);
$stmt1->execute();
$result1 = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);
foreach($result1 as $r){
    if(!in_array($r["REPAIR_ID"], $estimated)){ //display the items that have not been estimated, invoiced or shipped
        echo"
            <tr>
                <td>".$r["REPAIR_ID"]."</td>
                <td>".$r["MAKE"]."</td>
                <td>".$r["MODEL"]."</td>
                <td>".$r["PROBLEM"]."</td>
                <td>N/A</td>
                <td><a href='statusFormEmp.php?id=".$r["REPAIR_ID"]."&status=est'>ESTIMATE</a></td>";
                if ($r['IMAGE'] !== NULL){
                    echo "<td><a href='files/".$r['IMAGE']."'>VIEW</a></td>";
                }
                else{
                    echo "<td>N/A</td>";
                }
        echo "<td>N/A</td></tr>";
    }
    else{
        if(!in_array($r["REPAIR_ID"], $invoiced)){  //display the ites that have not been invoiced or shippied
            echo"
                <tr>
                    <td>".$r["REPAIR_ID"]."</td>
                    <td>".$r["MAKE"]."</td>
                    <td>".$r["MODEL"]."</td>
                    <td>".$r["PROBLEM"]."</td>
                    <td>Estimate</td>
                    <td><a href='statusFormEmp.php?id=".$r["REPAIR_ID"]."&status=inv'>INVOICE</a></td>";
                    if ($r['IMAGE'] !== NULL){
                        echo "<td><a href='files/".$r['IMAGE']."'>VIEW</a></td>";
                    }
                    else{
                        echo "<td>N/A</td>";
                    }
            echo "<td>N/A</td></tr>";
        }
        else{
            if(!in_array($r["REPAIR_ID"], $shipped)){   //display the items that have not been shipped
                //get amount due where there is no balance due
                $query = "SELECT AMOUNT_DUE, AMOUNT_PAID FROM CHECKOUT WHERE REPAIR_ID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $r["REPAIR_ID"]);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                foreach($result as $mon){
                    $amountDue = $mon["AMOUNT_DUE"];
                    $amountPaid = $mon["AMOUNT_PAID"];
                }
                $amountRem = $amountDue - $amountPaid;

                echo"
                    <tr>
                        <td>".$r["REPAIR_ID"]."</td>
                        <td>".$r["MAKE"]."</td>
                        <td>".$r["MODEL"]."</td>
                        <td>".$r["PROBLEM"]."</td>
                        <td>Invoice</td>
                        <td><a href='statusFormEmp.php?id=".$r["REPAIR_ID"]."&status=ship'>SHIP</a></td>";
                        if ($r['IMAGE'] !== NULL){
                            echo "<td><a href='files/".$r['IMAGE']."'>VIEW</a></td>";
                        }
                        else{
                            echo "<td>N/A</td>";
                        }
                echo "<td>$".$amountRem."</td></tr>";
            }
            //else dont display that item
        }
    }
}
echo"</tbody></table>";
?>