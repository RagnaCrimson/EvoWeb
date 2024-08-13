<?php
require_once '../connect.php'; // Use require_once to ensure the file is included

$conn = $objConnect; // Define the connection object

if (isset($_GET['T_ID'])) { // Check if T_ID is set
    $T_ID = $_GET['T_ID'];

    $query = "SELECT T_Status FROM task WHERE T_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $T_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $T_ID = $_POST['T_ID'];
    $new_status = $_POST['new_status'];

    $query = "SELECT T_Status FROM task WHERE T_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $T_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $old_status = $row['T_Status'];

    $query = "INSERT INTO task_status_history (T_ID, Old_Status, New_Status, Change_Date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $T_ID, $old_status, $new_status);
    $stmt->execute();

    $query = "UPDATE task SET T_Status = ?, T_Date = NOW() WHERE T_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $T_ID);
    $stmt->execute();

    header("Location: status_history.php?T_ID=$T_ID");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Task Status</title>
</head>
<body>
    <?php if (isset($T_ID)): ?>
        <h2>Update Status for Task ID: <?php echo htmlspecialchars($T_ID); ?></h2>

        <form method="post" action="update_status.php">
            <input type="hidden" name="T_ID" value="<?php echo htmlspecialchars($T_ID); ?>">

            <label for="current_status">Current Status:</label>
            <input type="text" id="current_status" value="<?php echo htmlspecialchars($task['T_Status']); ?>" disabled><br><br>

            <label for="new_status">New Status:</label>
            <select id="new_status" name="new_status" required>
                <option value="Sending">Sending</option>
                <option value="Sent">Sent</option>
                <option value="Delivered">Delivered</option>
                <option value="Completed">Completed</option>
            </select><br><br>

            <button type="submit">Update Status</button>
        </form>
    <?php else: ?>
        <h2>No task ID provided</h2>
    <?php endif; ?>
</body>
</html>