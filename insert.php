<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['V_Name'];
    $province = $_POST['V_Province'];
    $district = $_POST['V_District'];
    $sub_district = $_POST['V_SubDistrict'];
    $exec_name = $_POST['V_Exec_name'];
    $exec_phone = $_POST['V_Exec_phone'];
    $exec_mail = $_POST['V_Exec_mail'];
    $coord_name1 = $_POST['V_Coord_name1'];
    $coord_phone1 = $_POST['V_Coord_phone1'];
    $coord_mail1 = $_POST['V_Coord_mail1'];
    $coord_name2 = $_POST['V_Coord_name2'];
    $coord_phone2 = $_POST['V_Coord_phone2'];
    $coord_mail2 = $_POST['V_Coord_mail2'];
    $sale = $_POST['V_Sale'];
    $date = $_POST['V_Date'];
    $electric_per_year = $_POST['V_Electric_per_year'];
    $electric_per_month = $_POST['V_Electric_per_month'];
    $comment = $_POST['V_comment'];

    $sql = "INSERT INTO datastore_db (V_Name, V_Province, V_Distric, V_SubDistic, V_ExecName, V_ExecPhone, V_ExecMail, 
            V_CoordName1, V_CoordPhone1, V_CoordMail1, V_CoordName2, V_CoordPhone2, V_CoordMail2, V_Sale, V_Date, 
            V_Eletric_per_year, V_Electric_per_month, V_comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $objConnect->prepare($sql);
    $stmt->bind_param("sssssssssssssssss", $name, $province, $district, $sub_district, $exec_name, $exec_phone, $exec_mail, 
            $coord_name1, $coord_phone1, $coord_mail1, $coord_name2, $coord_phone2, $coord_mail2, $sale, $date, 
            $electric_per_year, $electric_per_month, $comment);

    if ($stmt->execute()) {
        echo "Data inserted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
    $objConnect->close();
}
?>
