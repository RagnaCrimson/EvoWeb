<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['V_Name']) && isset($_POST['V_Province']) && isset($_POST['V_District']) && 
        isset($_POST['V_SubDistrict']) && isset($_POST['V_ExecName']) && isset($_POST['V_ExecPhone']) && 
        isset($_POST['V_ExecMail']) && isset($_POST['V_CoordName1']) && isset($_POST['V_CoordPhone1']) && 
        isset($_POST['V_CoordMail1']) && isset($_POST['V_CoordName2']) && isset($_POST['V_CoordPhone2']) && 
        isset($_POST['V_CoordMail2']) && isset($_POST['V_Sale']) && isset($_POST['V_Date']) && 
        isset($_POST['V_Electric_per_year']) && isset($_POST['V_Electric_per_month']) && isset($_POST['V_comment'])) {

        $name = $_POST['V_Name'];
        $province = $_POST['V_Province'];
        $district = $_POST['V_District'];
        $sub_district = $_POST['V_SubDistrict'];
        $exec_name = $_POST['V_ExecName'];
        $exec_phone = $_POST['V_ExecPhone'];
        $exec_mail = $_POST['V_ExecMail'];
        $coord_name1 = $_POST['V_CoordName1'];
        $coord_phone1 = $_POST['V_CoordPhone1'];
        $coord_mail1 = $_POST['V_CoordMail1'];
        $coord_name2 = $_POST['V_CoordName2'];
        $coord_phone2 = $_POST['V_CoordPhone2'];
        $coord_mail2 = $_POST['V_CoordMail2'];
        $sale = $_POST['V_Sale'];
        $date = $_POST['V_Date'];
        $electric_per_year = $_POST['V_Electric_per_year'];
        $electric_per_month = $_POST['V_Electric_per_month'];
        $comment = $_POST['V_comment'];

        $sql = "INSERT INTO datastore_db (V_Name, V_Province, V_District, V_SubDistrict, V_ExecName, V_ExecPhone, V_ExecMail, 
                V_CoordName1, V_CoordPhone1, V_CoordMail1, V_CoordName2, V_CoordPhone2, V_CoordMail2, V_Sale, V_Date, 
                V_Electric_per_year, V_Electric_per_month, V_comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $objConnect->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $objConnect->error);
        }

        $stmt->bind_param("ssssssssssssssssss", $name, $province, $district, $sub_district, $exec_name, $exec_phone, $exec_mail, 
                $coord_name1, $coord_phone1, $coord_mail1, $coord_name2, $coord_phone2, $coord_mail2, $sale, $date, 
                $electric_per_year, $electric_per_month, $comment);

        if ($stmt->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $stmt->error;
        }

        $stmt->close();
        $objConnect->close();
    } else {
        echo "Some POST variables are missing.";
    }
}
?>