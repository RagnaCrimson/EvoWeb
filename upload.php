<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = array("pdf");
        if (!in_array($file_type, $allowed_types)) {
            echo "Sorry, only PDF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $filename = $_FILES["file"]["name"];

                $name = htmlspecialchars($_POST['V_Name']);
                $province = htmlspecialchars($_POST['V_Province']);
                $district = htmlspecialchars($_POST['V_District']);
                $sub_district = htmlspecialchars($_POST['V_SubDistrict']);
                $exec_name = htmlspecialchars($_POST['V_ExecName']);
                $exec_phone = htmlspecialchars($_POST['V_ExecPhone']);
                $exec_mail = htmlspecialchars($_POST['V_ExecMail']);
                $coord_name1 = htmlspecialchars($_POST['V_CoordName1']);
                $coord_phone1 = htmlspecialchars($_POST['V_CoordPhone1']);
                $coord_mail1 = htmlspecialchars($_POST['V_CoordMail1']);
                $coord_name2 = htmlspecialchars($_POST['V_CoordName2']);
                $coord_phone2 = htmlspecialchars($_POST['V_CoordPhone2']);
                $coord_mail2 = htmlspecialchars($_POST['V_CoordMail2']);
                $sale = htmlspecialchars($_POST['V_Sale']);
                $date = htmlspecialchars($_POST['V_Date']);
                $electric_per_year = htmlspecialchars($_POST['V_Electric_per_year']);
                $electric_per_month = htmlspecialchars($_POST['V_Electric_per_month']);
                $comment = htmlspecialchars($_POST['V_comment']);
                $status = htmlspecialchars($_POST['T_Status']);

                $sql_datastore_db = "INSERT INTO view (V_Name, V_Province, V_District, V_SubDistrict, V_ExecName, V_ExecPhone, V_ExecMail, 
                                                        V_CoordName1, V_CoordPhone1, V_CoordMail1, V_CoordName2, V_CoordPhone2, V_CoordMail2, V_Sale, V_Date, 
                                                        V_Electric_per_year, V_Electric_per_month, V_comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt_datastore_db = $objConnect->prepare($sql_datastore_db);
                if ($stmt_datastore_db === false) {
                    die("Error preparing statement for datastore_db: " . $objConnect->error);
                }

                $stmt_datastore_db->bind_param("ssssssssssssssssss", $name, $province, $district, $sub_district, $exec_name, $exec_phone, $exec_mail, 
                                                $coord_name1, $coord_phone1, $coord_mail1, $coord_name2, $coord_phone2, $coord_mail2, $sale, $date, 
                                                $electric_per_year, $electric_per_month, $comment);

                if ($stmt_datastore_db->execute()) {
                    $last_id = $stmt_datastore_db->insert_id;

                    $sql_task = "INSERT INTO task (T_Status) VALUES (?)";
                    $stmt_task = $objConnect->prepare($sql_task);
                    if ($stmt_task === false) {
                        die("Error preparing statement for task: " . $objConnect->error);
                    }
                    $stmt_task->bind_param("s", $status);

                    $stmt_task->execute();
                    $stmt_task->close();

                    $sql_files = "INSERT INTO files (filename) VALUES (?)";
                    $stmt_files = $objConnect->prepare($sql_files);
                    if ($stmt_files === false) {
                        die("Error preparing the statement for files: " . $objConnect->error);
                    }
                    $stmt_files->bind_param("s", $filename);

                    $stmt_files->execute();
                    $stmt_files->close();

                    header("Location: index.php");
                    exit;
                } else {
                    echo "Error executing the statement for datastore_db: " . $stmt_datastore_db->error;
                }

                $stmt_datastore_db->close();
                $objConnect->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file was uploaded.";
    }
}
?>
