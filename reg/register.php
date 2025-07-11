<?php
session_start();
include '../connection/dbconn.php';
require 'otp_sender.php'; // Include OTP function
require_once( 'vendor/autoload.php' );

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getPostData($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

function isStrongPassword($password) {
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_directory = '../uploads/'; // Directory for uploaded images
    $pic_data = '';
    $selfie_path = '';

    // Handle Profile Picture Upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('profile_') . '.' . $file_extension;
        $destination = $upload_directory . $new_file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
            $pic_data = $destination;
        } else {
            echo "<script>alert('Error uploading profile picture!');</script>";
            exit();
        }
    }

    // Handle Selfie Upload
    if (isset($_FILES['selfie']) && $_FILES['selfie']['error'] === UPLOAD_ERR_OK) {
        $selfie_extension = pathinfo($_FILES['selfie']['name'], PATHINFO_EXTENSION);
        $new_selfie_name = uniqid('selfie_') . '.' . $selfie_extension;
        $selfie_path = $upload_directory . $new_selfie_name;

        if (!move_uploaded_file($_FILES['selfie']['tmp_name'], $selfie_path)) {
            echo "<script>alert('Error uploading selfie!');</script>";
            header("Location: register.php");

            exit();
        }
    }

    $user_data = [
        'first_name' => getPostData('first_name'),
        'middle_name' => getPostData('middle_name'),
        'last_name' => getPostData('last_name'),
        'extension_name' => getPostData('extension_name'),
        'cp_number' => getPostData('cp_number'),
        'password' => getPostData('password'),
        'confirm_password' => getPostData('confirm_password'),
        'accountType' => getPostData('accountType'),
        'barangay_name' => getPostData('barangay'),
        'security_question' => getPostData('security_question'),
        'security_answer' => getPostData('security_answer'),
        'civil_status' => getPostData('civil_status'),
        'nationality' => getPostData('nationality'),
        'age' => getPostData('age'),
        'birth_date' => getPostData('birth_date'),
        'gender' => getPostData('gender'),
        'place_of_birth' => getPostData('place_of_birth'),
        'purok' => getPostData('purok'),
        'educational_background' => getPostData('educational_background'),
        'pic_data' => $pic_data, // Store uploaded profile picture path
        'selfie_path' => $selfie_path // Store uploaded selfie path
    ];

    if (in_array(null, $user_data, true)) {
        echo "<script>alert('Please fill out all required fields!');</script>";
        exit();
    }
    
    if ($user_data['password'] !== $user_data['confirm_password']) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }
    
    if (!isStrongPassword($user_data['password'])) {
        echo "<script>alert('Weak password! It must have uppercase, lowercase, number, and special character.');</script>";
        exit();
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE cp_number = ?");
    $stmt->execute([$user_data['cp_number']]);
    if ($stmt->fetchColumn() > 0) {
        echo "<script>alert('CP Number already exists!');</script>";
        exit();
    }
    
    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['user_data'] = $user_data;
    
    // Send OTP via Semaphore
    sendOTP($user_data['cp_number'], $otp);

    // Redirect to OTP verification page
    header("Location: otp_verification.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.4/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../reg/poles.jpg');
            background-size: cover;
            background-position: center top;
            background-position: center;
            background-repeat: no-repeat;
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
        .form-control, .form-select {
            border-radius: 25px; /* Make inputs and selects round */
        }
        .btn {
            border-radius: 25px; /* Make button round */
        }
        .btn-primary {
            border-radius: 50px;
            background-color: #5bc0de;
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
        }


        .progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.4s;
}

.weak {
    background-color: red;
}

.medium {
    background-color: orange;
}

.strong {
    background-color: green;
}


    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Register</h1>
        <form id="registerForm" method="post">
            <div class="row">
                <!-- First Name and Middle Name -->
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter your first name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="middle_name" class="form-label">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="Enter your middle name" required>
                </div>

                <!-- Last Name and Extension Name -->
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter your last name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="extension_name" class="form-label">Extension Name:</label>
                    <input type="text" id="extension_name" name="extension_name" class="form-control" placeholder="Enter your extension name">
                </div>

                <!-- Email -->
                <div class="col-12 mb-3">
                    <label for="cp_number" class="form-label">CP Number:</label>
                    <input type="cp_number" id="cp_number" name="cp_number" class="form-control" placeholder="Enter your number" required>
                </div>

                <!-- Password and Confirm Password -->
                <div class="col-md-6 mb-3">
    <label for="password" class="form-label">Password:</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
    <div id="password-strength" class="progress mt-2" style="height: 10px;">
        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <small id="strength-text" class="form-text"></small> 

</div>
<div class="col-md-6 mb-3">
    <label for="confirm_password" class="form-label">Re-enter Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter your password" required>
    <div id="confirm-password-strength" class="progress mt-2" style="height: 10px;">
        <div id="confirm-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <small id="confirm-strength-text" class="form-text"></small>

</div>


                <!-- Account Type -->
                <div class="col-md-6 mb-3">
                    <label for="accountType" class="form-label">Account Type:</label>
                    <select id="accountType" name="accountType" class="form-select" required>
                        <option value="Resident">Resident</option>
                    </select>
                </div>

                <!-- Barangay Select -->
                <div class="col-md-6 mb-3">
                    <label for="barangay" class="form-label">Address: tang ina mo princesss</label>
                    <input type="text" id="barangay" name="barangay" class="form-control" required>

                </div>


                <label for="purok">Purok:</label>
<select name="purok" required>
    <option value="">Select Purok</option>
    <option value="Purok 1">Purok 1</option>
    <option value="Purok 2">Purok 2</option>
    <option value="Purok 3">Purok 3</option>
    <option value="Purok 4">Purok 4</option>
    <option value="Purok 5">Purok 5</option>
    <option value="Purok 6">Purok 6</option>
    <option value="Purok 7">Purok 7</option>
</select>


                <div class="form-group">
    <label for="nationality">Nationality/Citizenship:</label>
    <input type="text" id="nationality" name="nationality" class="form-control" required>
</div>




<div class="col-lg-6 col-md-12 form-group">
              <label for="birth_date">Birth Date:</label>
              <input type="date" id="birth_date" name="birth_date" class="form-control" required>
            </div>
          </div>

          <!-- Gender and Age Information -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="gender">Gender:</label>
              <select id="gender" name="gender" class="form-control" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="age">Age:</label>
              <input type="number" id="age" name="age" class="form-control" readonly>
            </div>
          </div>

          <!-- Place of Birth and Civil Status -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="place_of_birth">Place of Birth:</label>
              <input type="text" id="place_of_birth" name="place_of_birth" class="form-control" required>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="civil_status">Civil Status:</label>
              <select id="civil_status" name="civil_status" class="form-control" required>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Live-in">Live-in</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
              </select>
            </div>


            <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="educational_background">Educational Attainment:</label>
              <select id="educational_background" name="educational_background" class="form-control" required>
                <option value="No Formal Education">No Formal Education</option>
                <option value="Elementary">Elementary</option>
                <option value="Highschool">Highschool</option>
                <option value="College">College</option>
                <option value="Post Graduate<">Post Graduate</option>
              </select>
            </div>


                <!-- Profile Picture Upload -->
                <div class="col-md-6 mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                </div>
            </div>

            
          </div>
          <div class="form-group">
        <label for="selfie">Upload Selfie:</label>
        <input type="file" name="selfie" accept="image/*" class="form-control" required>
    </div>
            <div class="col-12 mb-3">
                <label for="security_question" class="form-label">Security Question 1:</label>
                <select id="security_question" name="security_question" class="form-select" required>
                    <option value="">Select a question...</option>
                    <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                    <option value="What is the name of your first pet?">What is the name of your first pet?</option>
                    <option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
                </select>
                <input type="text" id="security_answer" name="security_answer" class="form-control mt-2" placeholder="Your answer" required>
            </div>


            <!-- Submit Button -->
            <div class="text-center">
            <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.4/dist/sweetalert2.min.js"></script>
    <script>



document.getElementById('cp_number').addEventListener('input', function (e) {
    // Remove non-numeric characters
    this.value = this.value.replace(/\D/g, '');
    // Limit to 11 digits
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }
});

            document.getElementById('birth_date').addEventListener('change', function() {
    var birthDate = new Date(this.value);
    var today = new Date();
    var age = today.getFullYear() - birthDate.getFullYear();
    var monthDifference = today.getMonth() - birthDate.getMonth();
    
    // Adjust the age if the birthday hasn't occurred yet this year
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    if (age < 18) {
        alert("Age must be 18 or above.");
        this.value = ''; // Clear the birth date field
        document.getElementById('age').value = ''; // Clear the age field
    } else {
        document.getElementById('age').value = age; // Set the calculated age
    }
});






     // Password Strength Indicator
document.getElementById('password').addEventListener('input', function() {
    var password = this.value;
    var strengthBar = document.getElementById('strength-bar');
    var strengthText = document.getElementById('strength-text');
    var strength = 0;

    // Check password strength
    if (password.length >= 8) {
        strength += 25; // Length check
    }
    if (/[A-Z]/.test(password)) {
        strength += 25; // Uppercase check
    }
    if (/[a-z]/.test(password)) {
        strength += 25; // Lowercase check
    }
    if (/\d/.test(password)) {
        strength += 25; // Number check
    }

    // Update the password strength bar and text
    strengthBar.style.width = strength + '%';
    strengthBar.setAttribute('aria-valuenow', strength);

    if (strength < 25) {
        strengthText.textContent = 'Poor';
        strengthBar.className = 'progress-bar bg-danger';
    } else if (strength < 50) {
        strengthText.textContent = 'Weak';
        strengthBar.className = 'progress-bar bg-warning';
    } else if (strength < 75) {
        strengthText.textContent = 'Fair';
        strengthBar.className = 'progress-bar bg-info';
    } else {
        strengthText.textContent = 'Strong';
        strengthBar.className = 'progress-bar bg-success';
    }

    // Match the password and confirm password
    checkPasswordMatch();
});

// Confirm Password Strength Indicator
document.getElementById('confirm_password').addEventListener('input', function() {
    checkPasswordMatch();
});

// Function to check if password and confirm password match
function checkPasswordMatch() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var confirmStrengthBar = document.getElementById('confirm-strength-bar');
    var confirmStrengthText = document.getElementById('confirm-strength-text');

    if (confirmPassword.length > 0) {
        if (password === confirmPassword) {
            confirmStrengthBar.style.width = '100%';
            confirmStrengthBar.setAttribute('aria-valuenow', 100);
            confirmStrengthText.textContent = 'Passwords Match';
            confirmStrengthBar.className = 'progress-bar bg-success';
        } else {
            confirmStrengthBar.style.width = '50%';
            confirmStrengthBar.setAttribute('aria-valuenow', 50);
            confirmStrengthText.textContent = 'Passwords Do Not Match';
            confirmStrengthBar.className = 'progress-bar bg-danger';
        }
    } else {
        confirmStrengthBar.style.width = '0%';
        confirmStrengthBar.setAttribute('aria-valuenow', 0);
        confirmStrengthText.textContent = '';
        confirmStrengthBar.className = 'progress-bar bg-light';
    }
}



<?php if (!empty($alertMessage)) { echo $alertMessage; } ?>

    </script>
</body>
</html>