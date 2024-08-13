<?php
include 'connect.php';

// Validate and sanitize the T_ID variable
$T_ID = filter_var(1, FILTER_VALIDATE_INT);

if ($T_ID === false) {
    throw new Exception('Invalid T_ID value');
}

$query = "SELECT Old_Status, New_Status, Change_Date FROM task_status_history WHERE T_ID = ? ORDER BY Change_Date DESC";
$stmt = $objConnect->prepare($query);
$stmt->bind_param("i", $T_ID);


try {
    $stmt->execute();
    $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    throw new Exception('Database error: ' . $e->getMessage());
}

$status_history = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Status History</title>
</head>
<body>
    <h2>Status History for Task ID: <?php echo htmlspecialchars($T_ID); ?></h2>

    <?php if (!empty($status_history)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Old Status</th>
                    <th>New Status</th>
                    <th>Change Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($status_history as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Old_Status']); ?></td>
                        <td><?php echo htmlspecialchars($row['New_Status']); ?></td>
                        <td><?php echo htmlspecialchars($row['Change_Date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No status history available for this task.</p>
    <?php endif; ?>
</body>
</html>