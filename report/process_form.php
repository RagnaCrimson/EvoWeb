<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $sales_team = $_POST['sales_team'];
    $task_status = $_POST['task_status'];
    $date = $_POST['date'];

    // Build the query
    $query = "SELECT view.*, task.T_Status 
              FROM view 
              LEFT JOIN task ON view.V_ID = task.T_ID 
              WHERE 1=1";
    
    if (!empty($sales_team)) {
        $query .= " AND view.V_Sale = '" . mysqli_real_escape_string($conn, $sales_team) . "'";
    }
    if (!empty($task_status)) {
        $query .= " AND task.T_Status = '" . mysqli_real_escape_string($conn, $task_status) . "'";
    }
    if (!empty($date)) {
        $query .= " AND view.V_Date = '" . mysqli_real_escape_string($conn, $date) . "'";
    }

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Display the results
    echo "<h2>ผลการค้นหา</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>ID</th><th>Name</th><th>Province</th><th>District</th><th>SubDistrict</th><th>Exec Name</th><th>Exec Phone</th><th>Exec Mail</th><th>Coord Name1</th><th>Coord Phone1</th><th>Coord Mail1</th><th>Coord Name2</th><th>Coord Phone2</th><th>Coord Mail2</th><th>Sale</th><th>Date</th><th>Electric Per Year</th><th>Electric Per Month</th><th>Peak Year</th><th>Peak Month</th><th>Comment</th><th>Status</th></tr></thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['V_ID']}</td>";
        echo "<td>{$row['V_Name']}</td>";
        echo "<td>{$row['V_Province']}</td>";
        echo "<td>{$row['V_District']}</td>";
        echo "<td>{$row['V_SubDistrict']}</td>";
        echo "<td>{$row['V_ExecName']}</td>";
        echo "<td>{$row['V_ExecPhone']}</td>";
        echo "<td>{$row['V_ExecMail']}</td>";
        echo "<td>{$row['V_CoordName1']}</td>";
        echo "<td>{$row['V_CoordPhone1']}</td>";
        echo "<td>{$row['V_CoordMail1']}</td>";
        echo "<td>{$row['V_CoordName2']}</td>";
        echo "<td>{$row['V_CoordPhone2']}</td>";
        echo "<td>{$row['V_CoordMail2']}</td>";
        echo "<td>{$row['V_Sale']}</td>";
        echo "<td>{$row['V_Date']}</td>";
        echo "<td>{$row['V_Electric_per_year']}</td>";
        echo "<td>{$row['V_Electric_per_month']}</td>";
        echo "<td>{$row['V_Peak_year']}</td>";
        echo "<td>{$row['V_Peak_month']}</td>";
        echo "<td>{$row['V_comment']}</td>";
        echo "<td>{$row['T_Status']}</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    // Free the result set
    mysqli_free_result($result);
}

// Close the database connection
mysqli_close($conn);
?>