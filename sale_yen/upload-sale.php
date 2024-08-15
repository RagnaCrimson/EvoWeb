<?php
include '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $target_dir = "../uploads/";

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

                // Prepare and bind POST data for view table
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
                $vPeakYear = htmlspecialchars($_POST['V_Peak_year']);
                $vPeakMonth = htmlspecialchars($_POST['V_Peak_month']);
                $comment = htmlspecialchars($_POST['V_comment']);
                $status = htmlspecialchars($_POST['T_Status']);

                $sql_view = "INSERT INTO view (V_Name, V_Province, V_District, V_SubDistrict, V_ExecName, V_ExecPhone, V_ExecMail, 
                                                    V_CoordName1, V_CoordPhone1, V_CoordMail1, V_CoordName2, V_CoordPhone2, V_CoordMail2, V_Sale, V_Date, 
                                                    V_Electric_per_year, V_Electric_per_month, V_Peak_year, V_Peak_month, V_comment) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_view = $objConnect->prepare($sql_view);
                if ($stmt_view === false) {
                    die("Error preparing statement for view table: " . $objConnect->error);
                }

                $stmt_view->bind_param("ssssssssssssssssssss", $name, $province, $district, $sub_district, $exec_name, $exec_phone, $exec_mail, 
                                            $coord_name1, $coord_phone1, $coord_mail1, $coord_name2, $coord_phone2, $coord_mail2, $sale, $date, 
                                            $electric_per_year, $electric_per_month, $vPeakYear, $vPeakMonth, $comment);

                if ($stmt_view->execute()) {
                    $last_id = $stmt_view->insert_id;

                    // Insert data into task table
                    $sql_task = "INSERT INTO task (T_Status) VALUES (?)";
                    $stmt_task = $objConnect->prepare($sql_task);
                    if ($stmt_task === false) {
                        die("Error preparing statement for task table: " . $objConnect->error);
                    }
                    $stmt_task->bind_param("s", $status);
                    $stmt_task->execute();
                    $stmt_task->close();
                    

                    // Insert data into files table
                    $sql_files = "INSERT INTO files (filename) VALUES (?)";
                    $stmt_files = $objConnect->prepare($sql_files);
                    if ($stmt_files === false) {
                        die("Error preparing statement for files table: " . $objConnect->error);
                    }
                    $stmt_files->bind_param("s", $filename);
                    $stmt_files->execute();
                    $stmt_files->close();

                    // Insert data into peak table
                    $sql_peak = "INSERT INTO peak (V_ID, serial_number, CA_code, P_12, P_M12) 
                    VALUES (?, ?, ?, ?, ?)";
                    $stmt_peak = $objConnect->prepare($sql_peak);
                    if ($stmt_peak === false) {
                        die("Error preparing statement for peak table: " . $objConnect->error);
                    }

                    $numb = htmlspecialchars($_POST['serial_number']);
                    $ca_code = htmlspecialchars($_POST['CA_code']);
                    $p_12 = htmlspecialchars($_POST['P_12']);
                    $pm_12 = htmlspecialchars($_POST['B_M12']);

                    $stmt_peak->bind_param("issss", $last_id, $numb, $ca_code, $p_12, $pm_12);
                    $stmt_peak->execute();
                    $stmt_peak->close();

                    // Insert data into bill table
                    $sql_bill = "INSERT INTO bill (V_ID, B_12, B_M12) 
                    VALUES (?, ?, ?)";
                    $stmt_bill = $objConnect->prepare($sql_bill);
                    if ($stmt_bill === false) {
                        die("Error preparing statement for bill table: " . $objConnect->error);
                    }

                    $b_12 = htmlspecialchars($_POST['B_12']);
                    $bm_12 = htmlspecialchars($_POST['B_M12']);

                    $stmt_bill->bind_param("iss", $last_id, $b_12, $bm_12);
                    $stmt_bill->execute();
                    $stmt_bill->close();

                    echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded and data has been inserted.";
                    header("Location: insert_data-yen.php");
                    exit();

                } else {
                    echo "Error inserting data into view table: " . $stmt_view->error;
                }
                $stmt_view->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file was uploaded or there was an error uploading the file.";
    }
}
?>
