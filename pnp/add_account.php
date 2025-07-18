<?php
include '../connection/dbconn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to safely retrieve POST data
function getPostData($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

// Function to validate strong password
function isStrongPassword($password) {
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $first_name = getPostData('first_name');
    $middle_name = getPostData('middle_name');
    $last_name = getPostData('last_name');
    $extension_name = getPostData('extension_name'); // Optional field
    $cp_number = getPostData('cp_number'); // CP Number instead of email
    $password = getPostData('password');
    $confirm_password = getPostData('confirm_password');
    $accountType = getPostData('accountType');
    $barangay_name = getPostData('barangay');
    $security_question = getPostData('security_question');
    $security_answer = getPostData('security_answer');

    // Validate form data
    if ($first_name && $middle_name && $last_name && $cp_number && $password && $confirm_password && $accountType && $barangay_name && $security_question && $security_answer) {
        // Check if passwords match
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match.'); window.location.href = 'add_account.php';</script>";
            exit;
        }

        // Check if password is strong
        if (!isStrongPassword($password)) {
            echo "<script>alert('Password is too weak.'); window.location.href = 'add_account.php';</script>";
            exit;
        }

        // Check if the CP Number already exists in the database
        $stmt_check_cp_number = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE cp_number = ?");
        $stmt_check_cp_number->execute([$cp_number]);
        $count = $stmt_check_cp_number->fetchColumn();

        if ($count > 0) {
            echo "<script>alert('CP Number already exists.'); window.location.href = 'add_account.php';</script>";
            exit;
        }

        // Handle file upload for profile picture
        $pic_data = 'default-profile-picture.jpg'; // Default image if no file is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $temp_name = $_FILES['profile_picture']['tmp_name'];
            $file_name = $_FILES['profile_picture']['name'];
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $upload_directory = '../uploads/';
            $new_file_name = uniqid('profile_') . '.' . $file_extension;
            $destination = $upload_directory . $new_file_name;

            // Validate file type and move file
            if (in_array(strtolower($file_extension), $allowed_extensions)) {
                if (move_uploaded_file($temp_name, $destination)) {
                    $pic_data = $new_file_name; // Set uploaded file name
                } else {
                    echo "<script>alert('Error uploading file.'); window.location.href = 'add_account.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Invalid file type.'); window.location.href = 'add_account.php';</script>";
                exit;
            }
        }

        // Insert into tbl_users_barangay
        $stmt_barangay = $pdo->prepare("INSERT INTO tbl_users_barangay (barangay_name) VALUES (?)");
        $stmt_barangay->execute([$barangay_name]);
        $barangays_id = $pdo->lastInsertId(); // Retrieve the last inserted ID

        // Hash the password and security answer
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashed_answer = password_hash($security_answer, PASSWORD_DEFAULT);

        // Insert into tbl_users
        $stmt_users = $pdo->prepare("
            INSERT INTO tbl_users 
            (first_name, middle_name, last_name, extension_name, cp_number, password, accountType, barangays_id, pic_data, security_question, security_answer) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_users->execute([
            $first_name, $middle_name, $last_name, $extension_name, $cp_number, $hashedPassword, $accountType, 
            $barangays_id, $pic_data, $security_question, $hashed_answer
        ]);

        // Check if the user was successfully inserted
        if ($stmt_users->rowCount() > 0) {
            echo "<script>alert('Registration successful.'); window.location.href = 'pnp.php';</script>";
        } else {
            echo "<script>alert('Database error.'); window.location.href = 'add_account.php';</script>";
        }
        exit;
    } else {
        echo "<script>alert('All fields are required.'); window.location.href = 'add_account.php';</script>";
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Add Bootstrap CSS if not already added -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include SweetAlert2 CSS -->
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


        .progress-bar {
    transition: width 0.5s ease;
}

.progress-bar.bg-danger {
    background-color: red !important;
}

.progress-bar.bg-warning {
    background-color: orange !important;
}

.progress-bar.bg-info {
    background-color: blue !important;
}

.progress-bar.bg-success {
    background-color: green !important;
}



    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Register</h1>
        <form id="registerForm" method="POST" enctype="multipart/form-data">
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
                        <option value="Barangay Official">Barangay Official</option>
                        <option value="PNP Officer">PNP Officer</option>
                        <option value="Resident">Resident</option>
                    </select>
                </div>

                <!-- Barangay Select -->
                <div class="col-md-6 mb-3">
                    <label for="barangay" class="form-label">Barangay:</label>
                    <select id="barangay" name="barangay" class="form-select" required>
                        <?php
                        // Array of barangays of echague
                        $barangays = [
                            "Angoluan", "Annafunan", "Arabiat", "Aromin", "Babaran", "Bacradal", "Benguet", "Buneg", "Busilelao", "Cabugao (Poblacion)",
                            "Caniguing", "Carulay", "Castillo", "Dammang East", "Dammang West", "Diasan", "Dicaraoyan", "Dugayong", "Fugu", "Garit Norte",
                            "Garit Sur", "Gucab", "Gumbauan", "Ipil", "Libertad", "Mabbayad", "Mabuhay", "Madadamian", "Magleticia", "Malibago", "Maligaya",
                            "Malitao", "Narra", "Nilumisu", "Pag-asa", "Pangal Norte", "Pangal Sur", "Rumang-ay", "Salay", "Salvacion", "San Antonio Ugad",
                            "San Antonio Minit", "San Carlos", "San Fabian", "San Felipe", "San Juan", "San Manuel (formerly Atelan)", "San Miguel", "San Salvador",
                            "Santa Ana", "Santa Cruz", "Santa Maria", "Santa Monica", "Santo Domingo", "Silauan Sur (Poblacion)", "Silauan Norte (Poblacion)",
                            "Sinabbaran", "Soyung (Poblacion)", "Taggappan (Poblacion)", "Villa Agullana", "Villa Concepcion", "Villa Cruz", "Villa Fabia",
                            "Villa Gomez", "Villa Nuesa", "Villa Padian", "Villa Pereda", "Villa Quirino", "Villa Remedios", "Villa Serafica", "Villa Tanza",
                            "Villa Verde", "Villa Vicenta", "Villa Ysmael (formerly T. Belen)"
                        ];

                        // Display barangays as options
                        foreach ($barangays as $barangay) {
                            echo "<option value=\"$barangay\">$barangay</option>";
                        }
                        ?>
                    </select>
                </div>







          <!-- Place of Birth and Civil Status -->
          <div class="row">
            
           


            <div class="row">
          
            

                <!-- Profile Picture Upload -->
                <div class="col-md-6 mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                </div>
            </div>

            
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include SweetAlert2 JS -->
    <script>



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


    </script>
</body>
</html>