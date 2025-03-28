<?php
include '../connection/dbconn.php'; 

header('Content-Type: application/json');

try {
    $sql = "SELECT DISTINCT barangay_name FROM tbl_users_barangay";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($barangays);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
