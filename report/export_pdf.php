<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$objConnect = new mysqli($servername, $username, $password, $dbname);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

$objConnect->set_charset("utf8");

$branch = $_POST['branch'];
$date = $_POST['date'];
$status = $_POST['status'];

// Build your query based on form inputs
$sql = "SELECT * FROM your_table WHERE branch='$branch' AND date='$date'";

if ($status != 'all') {
    $sql .= " AND status='$status'";
}

$result = $objConnect->query($sql);

$html = '<h2>Generated Report</h2>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Column 3</th>
                </tr>
            </thead>
            <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['column1']}</td>
                    <td>{$row['column2']}</td>
                    <td>{$row['column3']}</td>
                  </tr>";
    }
} else {
    $html .= "<tr><td colspan='3'>No records found</td></tr>";
}

$html .= '</tbody></table>';

$objConnect->close();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("report.pdf", array("Attachment" => 1));
?>
