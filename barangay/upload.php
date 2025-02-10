<?php
session_start();
include '../connection/dbconn.php';

if (isset($_POST['submit'])) {
    $uploadDir = '../uploads/';
    
    // Ensure complaint_id is set in form submission
    if (isset($_POST['complaint_id'])) {
        $complaint_id = $_POST['complaint_id'];
        $_SESSION['complaint_id'] = $complaint_id;  // Optionally store in session for future use
    } else {
        echo "Complaint ID is not set. Please select a complaint.";
        exit;
    }

    if (!empty($_FILES['cert_file']['name'])) {
        $fileName = basename($_FILES['cert_file']['name']);
        $targetFilePath = $uploadDir . time() . "_" . $fileName; // Unique filename
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];

        // Check if the file type is allowed
        if (in_array(strtolower($fileType), $allowedTypes)) {

            // Check if the file already exists for the complaint_id
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_evidence WHERE complaints_id = :complaints_id AND cert_path = :cert_path");
            $stmt->bindParam(':complaints_id', $complaint_id);
            $stmt->bindParam(':cert_path', $targetFilePath);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo "This file has already been uploaded for this complaint.";
                exit;
            }

            // Proceed with uploading if file is not already uploaded
            if (move_uploaded_file($_FILES['cert_file']['tmp_name'], $targetFilePath)) {
                // Ensure $pdo is defined before using it
                if (!isset($pdo)) {
                    throw new Exception("Database connection is not available.");
                }

                // Check if complaint_id exists in tbl_complaints
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_complaints WHERE complaints_id = :complaints_id");
                $stmt->bindParam(':complaints_id', $complaint_id);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    // Insert into tbl_evidence
                    $stmt = $pdo->prepare("INSERT INTO tbl_evidence (complaints_id, cert_path, date_uploaded) VALUES (:complaints_id, :cert_path, NOW())");
                    $stmt->bindParam(':complaints_id', $complaint_id);
                    $stmt->bindParam(':cert_path', $targetFilePath);
                    $stmt->execute();

                    echo "File uploaded successfully.";
                } else {
                    echo "Invalid complaint ID. It does not exist in the database.";
                }
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, PDF, and DOCX are allowed.";
        }
    } else {
        echo "Please select a file.";
    }
}
?>
