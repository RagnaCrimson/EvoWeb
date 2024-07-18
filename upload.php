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

                // Bind POST data for view table
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

                // Prepare SQL statement for view table
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
                    $sql_peak = "INSERT INTO peak (P_Month, P_1, P_2, P_3, P_4, P_5, P_6, P_7, P_8, P_9, P_10, P_11, P_12, PM_1, PM_2, PM_3, PM_4, PM_5, PM_6, PM_7, PM_8, PM_9, PM_10, PM_11, PM_12) 
                                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_peak = $objConnect->prepare($sql_peak);
                    if ($stmt_peak === false) {
                        die("Error preparing statement for peak table: " . $objConnect->error);
                    }

                    $p_month = htmlspecialchars($_POST['P_Month']);
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

                    $pm_1 = htmlspecialchars($_POST['PM_1']);
                    $pm_2 = htmlspecialchars($_POST['PM_2']);
                    $pm_3 = htmlspecialchars($_POST['PM_3']);
                    $pm_4 = htmlspecialchars($_POST['PM_4']);
                    $pm_5 = htmlspecialchars($_POST['PM_5']);
                    $pm_6 = htmlspecialchars($_POST['PM_6']);
                    $pm_7 = htmlspecialchars($_POST['PM_7']);
                    $pm_8 = htmlspecialchars($_POST['PM_8']);
                    $pm_9 = htmlspecialchars($_POST['PM_9']);
                    $pm_10 = htmlspecialchars($_POST['PM_10']);
                    $pm_11 = htmlspecialchars($_POST['PM_11']);
                    $pm_12 = htmlspecialchars($_POST['PM_12']);

                    $stmt_peak->bind_param("sssssssssssssssssssssss", $p_month, $p_1, $p_2, $p_3, $p_4, $p_5, $p_6, $p_7, $p_8, $p_9, $p_10, $p_11, $p_12, 
                                                                        $pm_1, $pm_2, $pm_3, $pm_4, $pm_5, $pm_6, $pm_7, $pm_8, $pm_9, $pm_10, $pm_11, $pm_12);
                    $stmt_peak->execute();
                    $stmt_peak->close();

                    // Insert data into bill table
                    $sql_bill = "INSERT INTO bill (B_Month, B_1, B_2, B_3, B_4, B_5, B_6, B_7, B_8, B_9, B_10, B_11, B_12, BM_Month, BM_1, BM_2, BM_3, BM_4, BM_5, BM_6, BM_7, BM_8, BM_9, BM_10, BM_11, BM_12) 
                                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_bill = $objConnect->prepare($sql_bill);
                    if ($stmt_bill === false) {
                        die("Error preparing statement for bill table: " . $objConnect->error);
                    }

                    $b_month = htmlspecialchars($_POST['B_Month']);
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

                    $bm_1 = htmlspecialchars($_POST['BM_1']);
                    $bm_2 = htmlspecialchars($_POST['BM_2']);
                    $bm_3 = htmlspecialchars($_POST['BM_3']);
                    $bm_4 = htmlspecialchars($_POST['BM_4']);
                    $bm_5 = htmlspecialchars($_POST['BM_5']);
                    $bm_6 = htmlspecialchars($_POST['BM_6']);
                    $bm_7 = htmlspecialchars($_POST['BM_7']);
                    $bm_8 = htmlspecialchars($_POST['BM_8']);
                    $bm_9 = htmlspecialchars($_POST['BM_9']);
                    $bm_10 = htmlspecialchars($_POST['BM_10']);
                    $bm_11 = htmlspecialchars($_POST['BM_11']);
                    $bm_12 = htmlspecialchars($_POST['BM_12']);

                    $stmt_bill->bind_param("sssssssssssssssssssssss", $b_month, $b_1, $b_2, $b_3, $b_4, $b_5, $b_6, $b_7, $b_8, $b_9, $b_10, $b_11, $b_12, 
                                                                       $bm_1, $bm_2, $bm_3, $bm_4, $bm_5, $bm_6, $bm_7, $bm_8, $bm_9, $bm_10, $bm_11, $bm_12);
                    $stmt_bill->execute();
                    $stmt_bill->close();

                    header("Location: index.php");
                    exit;
                } else {
                    echo "Error executing the statement for view table: " . $stmt_view->error;
                }

                $stmt_view->close();
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
