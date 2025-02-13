<?php
require_once '../connection/dbconn.php'; // Include database connection

// Check if the form is submitted and file is uploaded
if (isset($_POST['submit'])) {
    // Retrieve category_id dynamically (either from the form or session)
    $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;

    // Validate that category_id is provided
    if ($categoryId === null) {
        echo "<script>
                alert('Category ID is missing.');
                window.location.href = 'barangay-responder.php';
              </script>";
        exit();
    }

    // Check if a file was uploaded
    if (isset($_FILES['cert_file']) && $_FILES['cert_file']['error'] == 0) {
        $fileTmpPath = $_FILES['cert_file']['tmp_name'];
        $fileName = $_FILES['cert_file']['name'];
        $fileSize = $_FILES['cert_file']['size'];
        $fileType = $_FILES['cert_file']['type'];

        // Define allowed file types (PDF, JPG, PNG)
        $allowedFileTypes = ['application/pdf', 'image/jpeg', 'image/png'];

        // Check file type
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = '../uploads/certificates/'; // Ensure correct path

            // Create upload directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate a unique file name to prevent conflicts
            $newFileName = time() . '_' . basename($fileName);
            $fileDest = $uploadDir . $newFileName;

            // Move uploaded file to destination
            if (move_uploaded_file($fileTmpPath, $fileDest)) {
                try {
                    // Insert file path and category_id into database (foreign key relation)
                    $query = "INSERT INTO tbl_complaintcategories (category_id, cert_path) VALUES (:categoryId, :certPath)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT); // Use category_id from the form
                    $stmt->bindParam(':certPath', $fileDest, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        echo "<script>
                                alert('File uploaded and saved successfully!');
                                window.location.href = 'barangay-responder.php';
                              </script>";
                    } else {
                        echo "<script>
                                alert('Error saving file in database.');
                                window.location.href = 'barangay-responder.php';
                              </script>";
                    }
                } catch (PDOException $e) {
                    die("Database error: " . $e->getMessage());
                }
            } else {
                echo "<script>
                        alert('Error uploading file.');
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
}
?>
