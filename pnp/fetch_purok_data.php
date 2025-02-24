<?php
include '../connection/dbconn.php'; 

header('Content-Type: application/json');

try {
    $barangay = isset($_GET['barangay_name']) ? $_GET['barangay_name'] : '';

    if ($barangay) {
        $sql = "SELECT u.purok, COUNT(*) as count 
                FROM tbl_users u
                INNER JOIN tbl_users_barangay b ON u.barangays_id = b.id
                WHERE b.barangay_name = :barangay
                GROUP BY u.purok";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':barangay', $barangay, PDO::PARAM_STR);
    } else {
        $sql = "SELECT purok, COUNT(*) as count FROM tbl_users GROUP BY purok";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $purok_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Ensure JSON response is valid
    if (!$purok_data) {
        echo json_encode([]);
    } else {
        echo json_encode($purok_data);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
