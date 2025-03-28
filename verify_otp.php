<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_otp = trim($_POST['otp']);
    
    if ($user_otp == $_SESSION['otp'] && time() < $_SESSION['otp_expiry']) {
        $_SESSION['otp_verified'] = true;
        header("Location: forgot-password.php"); // Redirect to reset password page
        exit();
    } else {
        $error = "Invalid or expired OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="text-center">Enter OTP</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">OTP Code:</label>
            <input type="text" name="otp" class="form-control" required>
        </div>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <button type="submit" class="btn btn-primary w-100">Verify</button>
    </form>
</div>
</body>
</html>
