<?php
session_start();
include '../connection/dbconn.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $firstName = htmlspecialchars($_POST['first_name']);
    $middleName = htmlspecialchars($_POST['middle_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $extensionName = htmlspecialchars($_POST['extension_name']);
    $cp_number = htmlspecialchars($_POST['cp_number']);
    $redirectTo = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'pnp'; // Default to 'pnp'

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    try {
        // Start the transaction
        $pdo->beginTransaction();

        // Update user details
        $stmt = $pdo->prepare("UPDATE tbl_users SET first_name = ?, middle_name = ?, last_name = ?, extension_name = ?, cp_number = ? WHERE user_id = ?");
        $stmt->execute([$firstName, $middleName, $lastName, $extensionName, $cp_number, $userId]);

        // Handle profile picture update
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['profile_pic']['tmp_name']);
            $fileSize = $_FILES['profile_pic']['size'];

            if (in_array($fileType, $allowedTypes) && $fileSize <= 10000000) { // 10MB limit
                $profilePicFilename = basename($_FILES['profile_pic']['name']);
                $profilePicPath = '../uploads/' . $profilePicFilename;

                // Create 'uploads' directory if it doesn't exist
                if (!file_exists('../uploads')) {
                    mkdir('../uploads', 0777, true); // Create directory with full permissions
                }

                // Move uploaded file to 'uploads' directory
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePicPath)) {
                    $stmt = $pdo->prepare("UPDATE tbl_users SET pic_data = ? WHERE user_id = ?");
                    $stmt->execute([$profilePicPath, $userId]);

                    // Update session variable
                    $_SESSION['pic_data'] = $profilePicPath;
                } else {
                    throw new Exception("Failed to upload profile picture.");
                }
            } else {
                throw new Exception("Invalid file type or size. Please upload a valid image (JPEG, PNG, GIF) no larger than 10MB.");
            }
        }

        // Password update logic
        if (!empty($newPassword) && !empty($confirmPassword)) {
            if ($newPassword !== $confirmPassword) {
                throw new Exception("New passwords do not match.");
            }

            // Fetch current password from database
            $stmt = $pdo->prepare("SELECT password FROM tbl_users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                throw new Exception("Current password is incorrect.");
            }

            // Hash new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE tbl_users SET password = ? WHERE user_id = ?");
            $stmt->execute([$hashedPassword, $userId]);
        }

        // Commit the transaction
        $pdo->commit();

        // Update session variables
        $_SESSION['first_name'] = $firstName;
        $_SESSION['middle_name'] = $middleName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['extension_name'] = $extensionName;
        $_SESSION['cp_number'] = $cp_number;

        // Redirect based on hidden field value
        switch ($redirectTo) {
            case 'pnplogs':
                header("Location: barangay-official.php");
                break;
            case 'pnp-announcement':
                header("Location: barangaylogs.php");
                break;
            case 'dashboard':
                header("Location: manage-complaints.php");
                break;
            case 'pnp':
            default:
                header("Location: barangay-responder.php");
                break;
        }
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
