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
    $sales_team = $_POST['sales_team'];
    $task_status = $_POST['task_status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

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
    if (!empty($start_date) && !empty($end_date)) {
        // Assuming your date column is named V_Date
        $query .= " AND view.V_Date BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
    }
    
    // Add ORDER BY clause to sort dates in ascending order
    $query .= " ORDER BY view.V_Date ASC";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if there are rows in the result set
    if (mysqli_num_rows($result) > 0) {
        // Display the table if there are rows
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Report Results</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                 .container-fluid {
                    padding: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 1rem;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table th,
                .table td {
                    padding: 0.75rem;
                    vertical-align: top;
                    border-top: 1px solid #dee2e6;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .table thead th {
                    vertical-align: bottom;
                    border-bottom: 2px solid #dee2e6;
                }
                .table tbody + tbody {
                    border-top: 2px solid #dee2e6;
                }
                .table-responsive {
                    overflow-x: auto;
                }
                .no-data-message {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 18px;
                    color: #555;
                }
                .no-data-message span {
                    font-weight: bold;
                    color: #f00;
                }
            </style>
        </head>
        <body>

        <div class="container-fluid">
            <h2 class="text-center my-4">Report Results</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sale</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Province</th>
                            <th>Exec Name</th>
                            <th>Exec Phone</th>
                            <th>Electric Per Year (บาท)</th>
                            <th>Electric Per Month (บาท)</th>
                            <th>Peak Year</th>
                            <th>Peak Month</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['V_Sale']}</td>";
                            echo "<td>" . date('d-m-Y', strtotime($row['V_Date'])) . "</td>";
                            echo "<td>{$row['V_Name']}</td>";
                            echo "<td>{$row['V_SubDistrict']}</td>";
                            echo "<td>{$row['V_ExecName']}</td>";
                            echo "<td>{$row['V_ExecPhone']}</td>";
                            echo "<td>" . (($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2)) . "</td>";
                            echo "<td>" . (($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2)) . "</td>";
                            echo "<td>" . (($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2)) . "</td>";
                            echo "<td>" . (($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2)) . "</td>";
                            echo "<td>{$row['T_Status']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        </body>
        </html>

        <?php
    } else {
        
        echo "<p>No data available.</p>";
        
    }
}
?>
