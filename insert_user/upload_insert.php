<?php
include '../connect.php';

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
                    $sql_peak = "INSERT INTO peak (V_ID, P_1, P_2, P_3, P_4, P_5, P_6, P_7, P_8, P_9, P_10, P_11, P_12, P_M1, P_M2, P_M3, P_M4, P_M5, P_M6, P_M7, P_M8, P_M9, P_M10, P_M11, P_M12) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_peak = $objConnect->prepare($sql_peak);
                    if ($stmt_peak === false) {
                    die("Error preparing statement for peak table: " . $objConnect->error);
                    }

                    $p_1 = htmlspecialchars($_POST['P_1']);
                    $p_2 = htmlspecialchars($_POST['P_2']);
                    $p_3 = htmlspecialchars($_POST['P_3']);
                    $p_4 = htmlspecialchars($_POST['P_4']);
                    $p_5 = htmlspecialchars($_POST['P_5']);
                    $p_6 = htmlspecialchars($_POST['P_6']);
                    $p_7 = htmlspecialchars($_POST['P_7']);
                    $p_8 = htmlspecialchars($_POST['P_8']);
                    $p_9 = htmlspecialchars($_POST['P_9']);
                    $p_10 = htmlspecialchars($_POST['P_10']);
                    $p_11 = htmlspecialchars($_POST['P_11']);
                    $p_12 = htmlspecialchars($_POST['P_12']);

                    $pm_1 = htmlspecialchars($_POST['P_M1']);
                    $pm_2 = htmlspecialchars($_POST['P_M2']);
                    $pm_3 = htmlspecialchars($_POST['P_M3']);
                    $pm_4 = htmlspecialchars($_POST['P_M4']);
                    $pm_5 = htmlspecialchars($_POST['P_M5']);
                    $pm_6 = htmlspecialchars($_POST['P_M6']);
                    $pm_7 = htmlspecialchars($_POST['P_M7']);
                    $pm_8 = htmlspecialchars($_POST['P_M8']);
                    $pm_9 = htmlspecialchars($_POST['P_M9']);
                    $pm_10 = htmlspecialchars($_POST['P_M10']);
                    $pm_11 = htmlspecialchars($_POST['P_M11']);
                    $pm_12 = htmlspecialchars($_POST['P_M12']);

                    $stmt_peak->bind_param("issssssssssssssssssssssss", $last_id, $p_1, $p_2, $p_3, $p_4, $p_5, $p_6, $p_7, $p_8, $p_9, $p_10, $p_11, $p_12, 
                                                    $pm_1, $pm_2, $pm_3, $pm_4, $pm_5, $pm_6, $pm_7, $pm_8, $pm_9, $pm_10, $pm_11, $pm_12);
                    $stmt_peak->execute();
                    $stmt_peak->close();

                    // Insert data into bill table
                    $sql_bill = "INSERT INTO bill (V_ID, B_1, B_2, B_3, B_4, B_5, B_6, B_7, B_8, B_9, B_10, B_11, B_12, B_M1, B_M2, B_M3, B_M4, B_M5, B_M6, B_M7, B_M8, B_M9, B_M10, B_M11, B_M12) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_bill = $objConnect->prepare($sql_bill);
                    if ($stmt_bill === false) {
                    die("Error preparing statement for bill table: " . $objConnect->error);
                    }

                    $b_1 = htmlspecialchars($_POST['B_1']);
                    $b_2 = htmlspecialchars($_POST['B_2']);
                    $b_3 = htmlspecialchars($_POST['B_3']);
                    $b_4 = htmlspecialchars($_POST['B_4']);
                    $b_5 = htmlspecialchars($_POST['B_5']);
                    $b_6 = htmlspecialchars($_POST['B_6']);
                    $b_7 = htmlspecialchars($_POST['B_7']);
                    $b_8 = htmlspecialchars($_POST['B_8']);
                    $b_9 = htmlspecialchars($_POST['B_9']);
                    $b_10 = htmlspecialchars($_POST['B_10']);
                    $b_11 = htmlspecialchars($_POST['B_11']);
                    $b_12 = htmlspecialchars($_POST['B_12']);

                    $bm_1 = htmlspecialchars($_POST['B_M1']);
                    $bm_2 = htmlspecialchars($_POST['B_M2']);
                    $bm_3 = htmlspecialchars($_POST['B_M3']);
                    $bm_4 = htmlspecialchars($_POST['B_M4']);
                    $bm_5 = htmlspecialchars($_POST['B_M5']);
                    $bm_6 = htmlspecialchars($_POST['B_M6']);
                    $bm_7 = htmlspecialchars($_POST['B_M7']);
                    $bm_8 = htmlspecialchars($_POST['B_M8']);
                    $bm_9 = htmlspecialchars($_POST['B_M9']);
                    $bm_10 = htmlspecialchars($_POST['B_M10']);
                    $bm_11 = htmlspecialchars($_POST['B_M11']);
                    $bm_12 = htmlspecialchars($_POST['B_M12']);

                    $stmt_bill->bind_param("issssssssssssssssssssssss", $last_id, $b_1, $b_2, $b_3, $b_4, $b_5, $b_6, $b_7, $b_8, $b_9, $b_10, $b_11, $b_12, 
                                                    $bm_1, $bm_2, $bm_3, $bm_4, $bm_5, $bm_6, $bm_7, $bm_8, $bm_9, $bm_10, $bm_11, $bm_12);
                    $stmt_bill->execute();
                    $stmt_bill->close();

                } else {
                    echo "Error: " . $stmt_view->error;
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
