<?php
// notifications.php
include '../connection/dbconn.php';

header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Fetch notifications
try {
    $stmt = $pdo->prepare("SELECT c.complaints_id, c.complaint_name, c.status, b.barangay_name,c.date_filed
                            FROM tbl_complaints c
                            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
                            WHERE c.status = 'pnp'
                            ORDER BY c.date_filed DESC");
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'notifications' => $notifications
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error fetching notifications: ' . $e->getMessage()
    ]);
}

exit;
?>