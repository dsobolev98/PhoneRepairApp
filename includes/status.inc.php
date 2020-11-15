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

    $query1 = "SELECT COUNT(*) AS NUM FROM INQUIRY WHERE CUS_ID = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("i", $cusID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($result as $r1){
        $numOfRows = $r1['NUM'];
    }

    echo 
        "<table class='table'>
            <thead>
                <tr>
                    <th>Repair_ID</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Date Posted</th>
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
        $query1 = "SELECT REPAIR_ID, MAKE, MODEL, SERIAL_NUM, DATE_POSTED FROM INQUIRY WHERE CUS_ID = ?";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("i", $cusID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($result as $r2){
            echo 
            "<tr>
                <td><a href='statusInquiry.php?id=".$r2["REPAIR_ID"]."'>".$r2["REPAIR_ID"]."</a></td>
                <td>".$r2["MAKE"]."</td>
                <td>".$r2["MODEL"]."</td>
                <td>".$r2["SERIAL_NUM"]."</td>
                <td>".$r2["DATE_POSTED"]."</td>
            </tr>";
        }
        echo "</tbody></table>";
    }

?>