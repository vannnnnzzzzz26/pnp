<?php
require_once '../connection/dbconn.php'; // Include database connection

// Check if complaint_name is passed via POST
if (isset($_POST['complaint_name']) && !empty($_POST['complaint_name'])) {
    $complaintName = trim($_POST['complaint_name']);  // Get complaint_name from POST parameter
} else {
    die("<script>alert('Complaint Name is missing.'); window.location.href = 'barangay-responder.php';</script>");
}

// Ensure complaint_name exists in tbl_complaints and has approved status, then fetch complaints_id
$stmt = $pdo->prepare("SELECT complaints_id FROM tbl_complaints WHERE complaint_name = :complaintName AND status = 'approved'");
$stmt->bindParam(':complaintName', $complaintName, PDO::PARAM_STR);
$stmt->execute();

// If complaint_name exists and status is 'approved', fetch complaints_id
if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $complaintsId = $row['complaints_id'];
} else {
    echo "<script>alert('Complaint Name does not exist or is not approved.'); window.location.href = 'barangay-responder.php';</script>";
    exit();
}

// Process file upload if complaint_name exists and is approved
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['cert_file']) && $_FILES['cert_file']['error'] == 0) {
    $fileTmpPath = $_FILES['cert_file']['tmp_name'];
    $fileName = $_FILES['cert_file']['name'];
    $fileSize = $_FILES['cert_file']['size'];
    $fileType = $_FILES['cert_file']['type'];

    // Allowed file types
    $allowedFileTypes = ['application/pdf', 'image/jpeg', 'image/png'];

    if (in_array($fileType, $allowedFileTypes)) {
        // Define upload directory
        $uploadDir = '../uploads/certificates/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Rename file to avoid duplicates
        $newFileName = time() . '_' . basename($fileName);
        $fileDest = $uploadDir . $newFileName;

        // Move uploaded file
        if (move_uploaded_file($fileTmpPath, $fileDest)) {
            try {
                // Save to database (tbl_complaints_certificates)
                $stmt = $pdo->prepare("INSERT INTO tbl_complaints_certificates (complaints_id, cert_path) 
                                       VALUES (:complaintsId, :certPath)");
                $stmt->bindParam(':complaintsId', $complaintsId, PDO::PARAM_INT);
                $stmt->bindParam(':certPath', $fileDest, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('File uploaded successfully!');
                            window.location.href = 'barangay-responder.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Database error while saving file.');
                            window.location.href = 'barangay-responder.php';
                          </script>";
                }
            } catch (PDOException $e) {
                die("Database error: " . $e->getMessage());
            }
        } else {
            echo "<script>
                    alert('Error moving uploaded file.');
                    window.location.href = 'barangay-responder.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Only PDF, JPG, and PNG files are allowed.');
                window.location.href = 'barangay-responder.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No file uploaded or an error occurred.');
            window.location.href = 'barangay-responder.php';
          </script>";
}
?>
