<?php 
    $username = trim(stripslashes(htmlspecialchars($_COOKIE["username"])));
    $query1 = "SELECT ID FROM PROFILE WHERE USERNAME = ?";  //get the customers id
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r){
        $cusID = $r["ID"];
    }

    $query1 = "SELECT COUNT(*) AS NUM FROM CHECKOUT WHERE CUS_ID = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("i", $cusID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r1){
        $numOfRows = $r1["NUM"];
    }

    echo 
        "<table class='table'>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Repair ID</th>
                    <th>Amount Due</th>
                    <th>Amount Paid</th>
                    <th>Checkout</th>
                </tr>
            </thead>
            <tbody>";    

    if($numOfRows == 0){
        echo 
        "<tr>
            <td>N/A</td>
            <td>N/A</td>
            <td>N/A</td>
            <td>N/A</td>
            <td>N/A</td>
        </tr></tbody></table>";
    }
    else{
        $query1 = "SELECT TRANS_ID, REPAIR_ID, AMOUNT_DUE, AMOUNT_PAID FROM CHECKOUT WHERE CUS_ID = ? AND AMOUNT_DUE = AMOUNT_PAID";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("i", $cusID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($result as $r2){
            //there is no trans id assigned, assign in when invoice gets uploaded, if the amount paid is less than due than prompt checkout
            echo 
            "<tr>
                <td>".$r2["TRANS_ID"]."</td>
                <td>".$r2["REPAIR_ID"]."</td>
                <td>".$r2["AMOUNT_DUE"]."</td>
                <td>".$r2["AMOUNT_PAID"]."</td>";
            if($r2['AMOUNT_PAID'] < $r2['AMOUNT_DUE']){ //prompt checkout, when pay in checkout then update amount due
                echo "
                    <td><a href='checkout.php?transId=".$r2["TRANS_ID"]."'>CHECKOUT</a></td>
                </tr>";
            }
            else{
                echo "
                    <td>THANK YOU!</a></td>
                </tr>";
            }
        }
        echo "</tbody></table>";
    }

?>