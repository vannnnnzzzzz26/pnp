<?php
include 'connection/dbconn.php';

// Start the session
session_start();

// Initialize variables
$cp_number = $new_password = $confirm_password = $security_answer = "";
$cp_number_err = $new_password_err = $confirm_password_err = $security_answer_err = "";
$security_question = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Step 1: Handle phone number input and fetch security question
    if (isset($_POST['submit_cp_number'])) {
        if (empty(trim($_POST['cp_number']))) {
            $cp_number_err = "Please enter your phone number.";
        } else {
            $cp_number = trim($_POST['cp_number']);

            // Fetch security question based on phone number
            $stmt = $pdo->prepare("SELECT security_question FROM tbl_users WHERE cp_number = ?");
            $stmt->execute([$cp_number]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Set security question from the fetched data
                $security_question = $user['security_question'];

                // Store phone number in session for use in step 2
                $_SESSION['cp_number'] = $cp_number;
            } else {
                $cp_number_err = "Phone number not found!";
            }
        }
    }

    // Step 2: Handle security answer and password reset
// Step 2: Handle security answer and password reset
if (isset($_POST['submit_answers'])) {
    // Retrieve phone number from session
    $cp_number = $_SESSION['cp_number'] ?? "";

    $security_answer = trim($_POST['security_answer']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($security_answer)) {
        $security_answer_err = "Please answer the security question.";
    }
    if (empty($new_password)) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen($new_password) < 8) {
        $new_password_err = "Password must have at least 8 characters.";
    }
    if ($new_password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match.";
    }

    if (empty($security_answer_err) && empty($new_password_err) && empty($confirm_password_err)) {
        try {
            // Fetch user to verify security answer
            $stmt_check_cp = $pdo->prepare("SELECT user_id, security_answer FROM tbl_users WHERE cp_number = ?");
            $stmt_check_cp->execute([$cp_number]);
            $user = $stmt_check_cp->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Ensure security answer matches
                if ($security_answer === $user['security_answer']) { // Change this if security_answer is hashed
                    // Security answer is correct, proceed with password update
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt_update_password = $pdo->prepare("UPDATE tbl_users SET password = ? WHERE user_id = ?");
                    if ($stmt_update_password->execute([$hashedPassword, $user['user_id']])) {
                        echo "<div class='alert alert-success'>Password reset successful! <a href='login.php'>Login here</a></div>";
                        unset($_SESSION['cp_number']);
                    } else {
                        echo "<div class='alert alert-danger'>Password reset failed!</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Incorrect security answer!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Phone number not found!</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
        }
    }
}

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
        <h1 class="text-center mb-4">Reset Password</h1>
        <form method="post">
        <?php if (empty($security_question)) : ?>
            <!-- Step 1: CP Number input -->
                <div class="mb-3">
                    <label for="cp_number" class="form-label">CP Number:</label>
                    <input type="text" id="cp_number" name="cp_number" class="form-control <?php echo (!empty($cp_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cp_number; ?>" required>
                    <span class="invalid-feedback"><?php echo $cp_number_err; ?></span>
                </div>
                <button type="submit" name="submit_cp_number" class="btn btn-primary w-100">Next</button>
            <?php else : ?>
                <!-- Step 2: Security questions and new password input -->
                <div class="row mb-3">
    <div class="col-12 mb-3">
        <label for="security_question" class="form-label">Security Question:</label>
        <select id="security_question" name="security_question" class="form-select" required>
    <option value="">Select a question...</option>
    <option value="What was your childhood nickname?" <?php echo ($security_question == 'What was your childhood nickname?') ? 'selected' : ''; ?>>What was your childhood nickname?</option>
    <option value="What is the name of your first pet?" <?php echo ($security_question == 'What is the name of your first pet?') ? 'selected' : ''; ?>>What is the name of your first pet?</option>
    <option value="What was the make and model of your first car?" <?php echo ($security_question == 'What was the make and model of your first car?') ? 'selected' : ''; ?>>What was the make and model of your first car?</option>
</select>

        <input type="text" id="security_answer" name="security_answer" class="form-control mt-2" placeholder="Your answer" value="<?php echo $security_answer; ?>" required>
    </div>
</div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password:</label>
                    <input type="password" id="new_password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>" required>
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" required>
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <button type="submit" name="submit_answers" class="btn btn-primary w-100">Reset Password</button>
            <?php endif; ?>
        </form>
        <p class="mt-3 text-center"><a href="./reg/login.php">Back to Login</a></p>
    </div>
</body>
</html>
