<?php
include '../connection/dbconn.php'; 

// Check if complaint ID is provided via GET parameter
if (isset($_GET['id'])) {
    $complaintId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Prepare statement to fetch complaint details including additional information, evidence, certificates, and hearing history
        $stmt = $pdo->prepare(" 
            SELECT DISTINCT c.complaint_name, c.complaints AS description, c.date_filed, c.status, 
                   c.category_id, c.barangays_id, c.complaints_person, 
                   c.barangay_saan, cat.complaints_category,
                   u.gender, u.place_of_birth, u.age, u.educational_background, u.civil_status, u.nationality, u.cp_number,
                   e.evidence_path, cert.cert_path,
                   h.hearing_date, h.hearing_time, h.hearing_type, h.hearing_status
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            LEFT JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
            LEFT JOIN tbl_users u ON c.user_id = u.user_id
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            LEFT JOIN tbl_complaints_certificates cert ON c.complaints_id = cert.complaints_id
            LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
            WHERE c.complaints_id = :complaintId
        ");
        $stmt->bindParam(':complaintId', $complaintId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch complaint details
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            $evidencePaths = [];
            $certificatePaths = [];
            $hearings = [];

            foreach ($rows as $row) {
                // Collect evidence paths
                if (!empty($row['evidence_path'])) {
                    $evidencePaths[] = htmlspecialchars($row['evidence_path']);
                }

                // Collect certificate paths
                if (!empty($row['cert_path'])) {
                    $certificatePaths[] = htmlspecialchars($row['cert_path']);
                }

                // Collect hearing history
                if (!empty($row['hearing_date'])) {
                    $hearings[] = [
                        'date' => htmlspecialchars($row['hearing_date']),
                        'time' => htmlspecialchars($row['hearing_time']),
                        'type' => htmlspecialchars($row['hearing_type']),
                        'status' => htmlspecialchars($row['hearing_status'])
                    ];
                }

                // Display complaint details (only once)
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $description = htmlspecialchars($row['description']);
                $date_filed = htmlspecialchars($row['date_filed']);
                $status = htmlspecialchars($row['status']);
                $category_name = htmlspecialchars($row['complaints_category']);
                $barangay_name = htmlspecialchars($row['barangay_saan']);
                $cp_number = !empty($row['cp_number']) ? htmlspecialchars($row['cp_number']) : '-';
                $complaints_person = !empty($row['complaints_person']) ? htmlspecialchars($row['complaints_person']) : '-';
                $gender = htmlspecialchars($row['gender']);
                $place_of_birth = htmlspecialchars($row['place_of_birth']);
                $age = htmlspecialchars($row['age']);
                $educational_background = htmlspecialchars($row['educational_background']);
                $civil_status = htmlspecialchars($row['civil_status']);
                $nationality = htmlspecialchars($row['nationality']);

                echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 15px;'>";
                echo "<div><strong>Name:</strong><br><textarea class='form-control' rows='1' readonly>{$complaint_name}</textarea></div>";
                echo "<div><strong>Description:</strong><br><textarea class='form-control' rows='4' readonly>{$description}</textarea></div>";
                echo "<div><strong>Date Filed:</strong><br><textarea class='form-control' rows='1' readonly>{$date_filed}</textarea></div>";
                echo "<div><strong>Status:</strong><br><textarea class='form-control' rows='1' readonly>{$status}</textarea></div>";
                echo "<div><strong>Category:</strong><br><textarea class='form-control' rows='1' readonly>{$category_name}</textarea></div>";
                echo "<div><strong>Barangay:</strong><br><textarea class='form-control' rows='1' readonly>{$barangay_name}</textarea></div>";
                echo "<div><strong>Contact Number:</strong><br><textarea class='form-control' rows='1' readonly>{$cp_number}</textarea></div>";
                echo "<div><strong>Complaints Person:</strong><br><textarea class='form-control' rows='1' readonly>{$complaints_person}</textarea></div>";
                echo "<div><strong>Gender:</strong><br><textarea class='form-control' rows='1' readonly>{$gender}</textarea></div>";
                echo "<div><strong>Place of Birth:</strong><br><textarea class='form-control' rows='1' readonly>{$place_of_birth}</textarea></div>";
                echo "<div><strong>Age:</strong><br><textarea class='form-control' rows='1' readonly>{$age}</textarea></div>";
                echo "<div><strong>Educational Background:</strong><br><textarea class='form-control' rows='1' readonly>{$educational_background}</textarea></div>";
                echo "<div><strong>Civil Status:</strong><br><textarea class='form-control' rows='1' readonly>{$civil_status}</textarea></div>";
                echo "<div><strong>Nationality:</strong><br><textarea class='form-control' rows='1' readonly>{$nationality}</textarea></div>";
                echo "</div>";

                break;
            }

            // Display hearing history
            if (!empty($hearings)) {
                echo "<h5>Hearing History:</h5><ul>";
                foreach ($hearings as $hearing) {
                    echo "<li>{$hearing['type']} - Date: {$hearing['date']}, Time: {$hearing['time']}, Status: {$hearing['status']}</li>";
                }
                echo "</ul>";
            }

            // Display evidence
            if (!empty($evidencePaths)) {
                echo "<h5>Evidence:</h5><ul>";
                foreach (array_unique($evidencePaths) as $path) {
                    echo "<li><a href='../uploads/{$path}' target='_blank' onclick='window.open(this.href); return false;'>View Evidence</a></li>";
                }
                echo "</ul>";
            }

            // Display certificates
            if (!empty($certificatePaths)) {
                echo "<h5>Certificates:</h5><ul>";
                foreach (array_unique($certificatePaths) as $path) {
                    echo "<li><a href='../certificates/{$path}' target='_blank' onclick='window.open(this.href); return false;'>View Certificate</a></li>";
                }
                echo "</ul>";
            }
        } else {
            echo "<p>No details found for Complaint ID: {$complaintId}</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching complaint details: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Complaint ID not provided.</p>";
}
?>