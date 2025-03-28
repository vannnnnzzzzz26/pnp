<?php
session_start();
include '../connection/dbconn.php';
require_once('vendor/autoload.php');

function sendOTP($number, $otp) {
    $ch = curl_init();
    $parameters = array(
    'apikey' => '', // Replace with actual API key
        'number' => $number, 
        'message' => "Your One Time Password  code is: $otp",
        'sendername' => 'Copwatch'
    );

    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['resend_otp'])) {
        // Check if user_data exists before using it
        if (!isset($_SESSION['user_data'])) {
            echo "<script>alert('Session expired! Please restart the registration process.'); window.location.href='register.php';</script>";
            exit();
        }

        // Generate a new OTP
        $new_otp = rand(100000, 999999);
        $_SESSION['otp'] = $new_otp;

        // Get user's phone number from session data
        $phone_number = $_SESSION['user_data']['cp_number'];

        // Send OTP
        sendOTP($phone_number, $new_otp);

        echo "<script>alert('A new OTP has been sent to your phone.');</script>";
    }

    if (isset($_POST['verify_otp'])) {
        if (!isset($_SESSION['user_data']) || !isset($_SESSION['otp'])) {
            echo "<script>alert('Session expired! Please restart the registration process.'); window.location.href='register.php';</script>";
            exit();
        }

        $entered_otp = trim($_POST['otp']);

        if ($entered_otp == $_SESSION['otp']) {
            // Retrieve user data from session
            $user_data = $_SESSION['user_data'];
            $hashedPassword = password_hash($user_data['password'], PASSWORD_DEFAULT);
            $hashed_answer = password_hash($user_data['security_answer'], PASSWORD_DEFAULT);

            try {
                // Begin a transaction
                $pdo->beginTransaction();

                // Check if barangay exists
                $stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangay_name = ?");
                $stmt->execute([$user_data['barangay_name']]);
                $barangay = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$barangay) {
                    // Insert new barangay
                    $stmt = $pdo->prepare("INSERT INTO tbl_users_barangay (barangay_name) VALUES (?)");
                    $stmt->execute([$user_data['barangay_name']]);
                    $barangay_id = $pdo->lastInsertId();
                } else {
                    $barangay_id = $barangay['barangays_id'];
                }

                // Insert user data
                $stmt = $pdo->prepare("INSERT INTO tbl_users (first_name, middle_name, last_name, extension_name, cp_number, password, accountType, barangays_id, security_question, security_answer, civil_status, nationality, age, birth_date, gender, place_of_birth, purok, educational_background) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->execute([
                    $user_data['first_name'], $user_data['middle_name'], $user_data['last_name'], 
                    $user_data['extension_name'], $user_data['cp_number'], $hashedPassword, 
                    $user_data['accountType'], $barangay_id, 
                    $user_data['security_question'], $hashed_answer, 
                    $user_data['civil_status'], $user_data['nationality'], 
                    $user_data['age'], $user_data['birth_date'], $user_data['gender'], 
                    $user_data['place_of_birth'], $user_data['purok'], $user_data['educational_background']
                ]);

                // Commit transaction
                $pdo->commit();

                // Clear session data
                unset($_SESSION['otp']);
                unset($_SESSION['user_data']);

                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Invalid OTP!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-4">OTP Verification</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" name="otp" required placeholder="Enter OTP">
                        </div>
                        <button type="submit" name="verify_otp" class="btn btn-primary w-100">Verify OTP</button>
                    </form>
                    <form method="POST" class="mt-3">
                        <button type="submit" name="resend_otp" class="btn btn-secondary w-100">Resend OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
