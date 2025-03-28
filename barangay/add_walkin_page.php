<?php
include '../connection/dbconn.php'; 
include '../resident/notifications.php';




$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$barangay = isset($_SESSION['barangays_id']) ? $_SESSION['barangays_id'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Collect complaint form data
    
             
        $complaint_name = isset($_POST['complaints']) ? htmlspecialchars($_POST['complaint_name']) : '';
        $complaints = isset($_POST['complaints']) ? htmlspecialchars($_POST['complaints']) : '';
        $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '';
        $complaints_person = isset($_POST['complaints_person']) ? htmlspecialchars($_POST['complaints_person']) : '';
        $date_filed = date('Y-m-d H:i:s');
        $barangay_name = isset($_POST['barangay_name']) ? htmlspecialchars($_POST['barangay_name']) : '';

        // Collect data for "ano, saan, kailan, paano, bakit"
        $ano = isset($_POST['ano']) ? htmlspecialchars($_POST['ano']) : '';
        $barangay_saan = isset($_POST['barangay_saan']) ? htmlspecialchars($_POST['barangay_saan']) : '';
        
        // Get 'kailan' input and convert it to database-friendly datetime format
        $kailan_date = isset($_POST['kailan_date']) ? htmlspecialchars($_POST['kailan_date']) : '';
        $kailan_time = isset($_POST['kailan_time']) ? htmlspecialchars($_POST['kailan_time']) : '';
        $kailan_time_12hr = date("h:i:s A", strtotime($kailan_time)); // Convert to 12-hour format with AM/PM

        $paano = isset($_POST['paano']) ? htmlspecialchars($_POST['paano']) : '';
        $bakit = isset($_POST['bakit']) ? htmlspecialchars($_POST['bakit']) : '';
        $cp_number = isset($_POST['cp_number']) ? htmlspecialchars($_POST['cp_number']) : '';
        $purok = isset($_POST['purok']) ? htmlspecialchars($_POST['purok']) : '';

        $civil_status = isset($_POST['civil_status']) ? htmlspecialchars($_POST['civil_status']) : '';
        $age = isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '';
        $birth_date = isset($_POST['birth_date']) ? htmlspecialchars($_POST['birth_date']) : '';
        $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';
        $place_of_birth = isset($_POST['place_of_birth']) ? htmlspecialchars($_POST['place_of_birth']) : '';
        $purok = isset($_POST['purok']) ? htmlspecialchars($_POST['purok']) : '';
        $nationality  = isset($_POST['nationality']) ? htmlspecialchars($_POST['nationality ']) : '';

        $educational_background = isset($_POST['educational_background']) ? htmlspecialchars($_POST['educational_background']) : '';
        $pdo->beginTransaction();

  // Insert into tbl_info to store additional user information
  $stmt = $pdo->prepare("INSERT INTO tbl_users (civil_status, age, birth_date, gender, place_of_birth, educational_background,purok,nationality) VALUES (?,?, ?, ?, ?, ?, ?,?,?)");
  $stmt->execute([$cp_number, $civil_status, $age, $birth_date, $gender, $place_of_birth, $educational_background,$purok,$nationality ]);
  $user_id = $pdo->lastInsertId();
        // Check category and insert new category if necessary
        $stmt = $pdo->prepare("SELECT category_id FROM tbl_complaintcategories WHERE complaints_category = ?");
        $stmt->execute([$category]);
        $category_id = $stmt->fetchColumn();

        if (!$category_id) {
            $stmt = $pdo->prepare("INSERT INTO tbl_complaintcategories (complaints_category) VALUES (?)");
            $stmt->execute([$category]);
            $category_id = $pdo->lastInsertId();
        }

        // Validate barangay
        $barangay = $_POST['barangay_name']; // Get the Barangay name from the form submission

        $stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangay_name = ?");
    $stmt->execute([$barangay]);
    $barangays_id = $stmt->fetchColumn(); // Fetch the Barangay ID

    // If Barangay doesn't exist, insert it into the database
    if (!$barangays_id) {
        $stmt = $pdo->prepare("INSERT INTO tbl_users_barangay (barangay_name) VALUES (?)");
        $stmt->execute([$barangay]);
        $barangays_id = $pdo->lastInsertId(); // Get the last inserted Barangay ID
    }

    // Continue with further processing using $barangays_id...


        // Handle category
        $other_category = isset($_POST['other-category']) ? htmlspecialchars($_POST['other-category']) : '';
        if ($category === 'Other' && !empty($other_category)) {
            $category = $other_category; 
        }

        // Set status and response values based on category
        $status = ($category === 'Other') ? 'barangay' : 'Approved';
        $responds = ($category === 'Other') ? 'pnp' : '';

        // Insert into tbl_complaints
       $user_id = $_SESSION['user_id']; // Retrieve user_id from session

        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints, date_filed, category_id, barangays_id, complaints_person, status, responds, ano, barangay_saan, kailan_date, kailan_time, paano, bakit, user_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$complaint_name, $complaints, $date_filed, $category_id, $barangays_id, $complaints_person, $status, $responds, $ano, $barangay_saan, $kailan_date, $kailan_time_12hr, $paano, $bakit, $user_id]);

        $complaint_id = $pdo->lastInsertId();

        // Handle evidence upload
        if (isset($_FILES['evidence']) && $_FILES['evidence']['error'][0] == UPLOAD_ERR_OK) {
            foreach ($_FILES['evidence']['tmp_name'] as $key => $tmp_name) {
                $evidence_filename = basename($_FILES['evidence']['name'][$key]);
                $evidence_path = '../uploads/' . $evidence_filename;
                $date_uploaded = date('Y-m-d H:i:s');

                if (move_uploaded_file($tmp_name, $evidence_path)) {
                    $stmt = $pdo->prepare("INSERT INTO tbl_evidence (complaints_id, evidence_path, date_uploaded) VALUES (?, ?, ?)");
                    $stmt->execute([$complaint_id, $evidence_path, $date_uploaded]);
                } else {
                    throw new Exception("Failed to upload evidence.");
                }
            }
        }

        $pdo->commit();

        $_SESSION['success'] = true;
        header("Location: barangay-responder.php ");
        exit();


    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    }
}

// Example retrieval of the 'kailan' field for displaying with AM/PM format
if (isset($complaint_id)) {
    $stmt = $pdo->prepare("SELECT kailan FROM tbl_complaints WHERE complaints_id = ?");
    $stmt->execute([$complaint_id]);
    $kailan_from_db = $stmt->fetchColumn();
    
    // Convert stored 'kailan' to AM/PM format for display

    $kailan = isset($_POST['kailan']) ? htmlspecialchars($_POST['kailan']) : '';

    // Convert the datetime-local format to MySQL datetime format
    $kailan_db_format = date('Y-m-d H:i:s', strtotime($kailan));

    // If you want to display AM/PM later, you can format it
    $kailan_am_pm = date('F j, Y, g:i A', strtotime($kailan));

    // Validate the conversion
    if (!$kailan_db_format) {
        throw new Exception("Invalid date format for 'kailan'.");
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <!-- Bootstrap CSS -->
     <!-- Bootstrap Icons CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">

</head>
<body >

<style>

body{
    background-color: #082759;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
}


h1{
    color: whitesmoke;
    text-align: center;
}


.button-container {
    width: 100%;
    text-align: center;
    margin-top: 20px;
    padding: 20px; /* Add padding here */
}

    </style>


    <!-- Page Content -->
  

   
   <div class="content">
    

   <h1>Walk in Complaints</h1>
  <div class="card">
    <div class="card-body">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
        <div class="form-box">
          <!-- User Information -->
          <div class="container">
  <!-- First row with basic complaint details and barangay -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="complaint_name">Complaint name:</label>
      <textarea id="complaint_name" name="complaint_name" class="form-control" required></textarea>
    </div>
    <div class="col-lg-6 col-md-12 form-group">
      <label for="barangay_name">Address:</label>
      <textarea id="barangay_name" name="barangay_name" class="form-control" required></textarea>
    </div>
  </div>

  <!-- Second row with complaint information and category selection -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="complaints">Complaint:</label>
      <textarea id="complaints" name="complaints" class="form-control" required></textarea>
    </div>
    <div class="col-lg-6 col-md-12 form-group">
      <label for="category">Category:</label>
      <?php include 'category.php'; ?>
      <button id="openModalButton" class="btn btn-primary">View Category</button><br>
      <select id="category" name="category" class="form-control" required>
        <option value="">Select</option>
        <option value="Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)">Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)</option>
    <option value="Alarms and Scandals (Art. 155)">Alarms and Scandals (Art. 155)</option>
    <option value="Using False Certificates (Art. 175)">Using False Certificates (Art. 175)</option>
    <option value="Using Fictitious Names and Concealing True Names (Art. 178)">Using Fictitious Names and Concealing True Names (Art. 178)</option>
    <option value="Illegal Use of Uniforms and Insignias (Art. 179)">Illegal Use of Uniforms and Insignias (Art. 179)</option>
    <option value="Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)">Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)</option>
    <option value="Giving Assistance to Consummated Suicide (Art. 253)">Giving Assistance to Consummated Suicide (Art. 253)</option>
    <option value="Responsibility of Participants in a Duel if only Physical Injuries are Inflicted or No Physical Injuries have been Inflicted (Art. 260)">Responsibility of Participants in a Duel if only Physical Injuries are Inflicted or No Physical Injuries have been Inflicted (Art. 260)</option>
    <option value="Less serious physical injuries (Art. 265)">Less serious physical injuries (Art. 265)</option>
    <option value="Slight physical injuries and maltreatment (Art. 266)">Slight physical injuries and maltreatment (Art. 266)</option>
    <option value="Unlawful arrest (Art. 269)">Unlawful arrest (Art. 269)</option>
    <option value="Inducing a minor to abandon his/her home (Art. 271)">Inducing a minor to abandon his/her home (Art. 271)</option>
    <option value="Abandonment of a person in danger and abandonment of one’s own victim (Art. 275)">Abandonment of a person in danger and abandonment of one’s own victim (Art. 275)</option>
    <option value="Abandoning a minor (a child under seven (7) years old) (Art. 276)">Abandoning a minor (a child under seven (7) years old) (Art. 276)</option>
    <option value="Abandonment of a minor by persons entrusted with his/her custody; indifference of parents (Art. 277)">Abandonment of a minor by persons entrusted with his/her custody; indifference of parents (Art. 277)</option>
    <option value="Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)">Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)</option>
    <option value="Other forms of trespass (Art. 281)">Other forms of trespass (Art. 281)</option>
    <option value="Light threats (Art. 283)">Light threats (Art. 283)</option>
    <option value="Other light threats (Art. 285)">Other light threats (Art. 285)</option>
    <option value="Grave coercion (Art. 286)">Grave coercion (Art. 286)</option>
    <option value="Light coercion (Art. 287)">Light coercion (Art. 287)</option>
    <option value="Other similar coercions (compulsory purchase of merchandise and payment of wages by means of tokens) (Art. 288)">Other similar coercions (compulsory purchase of merchandise and payment of wages by means of tokens) (Art. 288)</option>
    <option value="Formation, maintenance and prohibition of combination of capital or labor through violence or threats (Art. 289)">Formation, maintenance and prohibition of combination of capital or labor through violence or threats (Art. 289)</option>
    <option value="Discovering secrets through seizure and correspondence (Art. 290)">Discovering secrets through seizure and correspondence (Art. 290)</option>
    <option value="Revealing secrets with abuse of authority (Art. 291)">Revealing secrets with abuse of authority (Art. 291)</option>
    <option value="Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)">Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)</option>
    <option value="Qualified theft (if the amount does not exceed Php500) (Art. 310)">Qualified theft (if the amount does not exceed Php500) (Art. 310)</option>
    <option value="Occupation of real property or usurpation of real rights in property (Art. 312)">Occupation of real property or usurpation of real rights in property (Art. 312)</option>
    <option value="Altering boundaries or landmarks (Art. 313)">Altering boundaries or landmarks (Art. 313)</option>
    <option value="Swindling or estafa (if the amount does not exceed Php200.00) (Art. 315)">Swindling or estafa (if the amount does not exceed Php200.00) (Art. 315)</option>
    <option value="Other forms of swindling (Art. 316)">Other forms of swindling (Art. 316)</option>
    <option value="Swindling a minor (Art. 317)">Swindling a minor (Art. 317)</option>
    <option value="Other deceits (Art. 318)">Other deceits (Art. 318)</option>
    <option value="Removal, sale or pledge of mortgaged property (Art. 319)">Removal, sale or pledge of mortgaged property (Art. 319)</option>
    <option value="Special cases of malicious mischief (if the value of the damaged property does not exceed Php1,000.00 Art. 328)">Special cases of malicious mischief (if the value of the damaged property does not exceed Php1,000.00 Art. 328)</option>
    <option value="Other mischiefs (if the value of the damaged property does not exceed Php1,000.00) (Art. 329)">Other mischiefs (if the value of the damaged property does not exceed Php1,000.00) (Art. 329)</option>
    <option value="Simple seduction (Art. 338)">Simple seduction (Art. 338)</option>
    <option value="Acts of lasciviousness with the consent of the offended party (Art. 339)">Acts of lasciviousness with the consent of the offended party (Art. 339)</option>
    <option value="Threatening to publish and offer to prevent such publication for compensation (Art. 356)">Threatening to publish and offer to prevent such publication for compensation (Art. 356)</option>
    <option value="Prohibiting publication of acts referred to in the course of official proceedings (Art. 357)">Prohibiting publication of acts referred to in the course of official proceedings (Art. 357)</option>
    <option value="Incriminating innocent persons (Art. 363)">Incriminating innocent persons (Art. 363)</option>
    <option value="Intriguing against honor (Art. 364)">Intriguing against honor (Art. 364)</option>
    <option value="Issuing checks without sufficient funds (B.P. 22)">Issuing checks without sufficient funds (B.P. 22)</option>
    <option value="Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)">Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)</option>


        <option value="Other">Other</option>
      </select>
      <div id="other-category-group" style="display: none;">
        <label for="other-category">Please specify:</label>
        <input type="text" id="other-category" name="other-category" class="form-control" placeholder="Specify your complaint" />
      </div>
    </div>
  </div>

  <!-- Third row with date and time information, "Ano" and "Barangay" fields -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="ano">Ano (What):</label>
      <input type="text" name="ano" id="ano" class="form-control" required>
    </div>
    
    <div class="col-lg-6 col-md-12 form-group">
      <label for="barangay_saan">Barangay:</label>
      <textarea id="barangay_saan" name="barangay_saan" class="form-control" required></textarea>

    </div>
  </div>

  <!-- Fourth row with date and time details -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="kailan_date">Kailan (When - date):</label>
      <input type="date" name="kailan_date" id="kailan_date" class="form-control" required>
      <label for="kailan_time">Anong oras (When time):</label>
      <input type="time" name="kailan_time" id="kailan_time" class="form-control" required>
    </div>
  </div>

  <!-- Fifth row with "Paano" (How) and "Bakit" (Why) -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="paano">Paano (How):</label>
      <textarea name="paano" id="paano" class="form-control" required></textarea>
    </div>
    <div class="col-lg-6 col-md-12 form-group">
      <label for="bakit">Bakit (Why):</label>
      <textarea name="bakit" id="bakit" class="form-control" required></textarea>
    </div>
  </div>

  <!-- Sixth row with evidence upload and person involved -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="evidence">Upload Evidence:</label>
      <input type="file" id="evidence" name="evidence[]" class="form-control" multiple required>
    </div>
    <div class="col-lg-6 col-md-12 form-group">
      <label for="complaints_person">Person Involved:</label>
      <input type="text" id="complaints_person" name="complaints_person" class="form-control" required>
    </div>
  </div>

  <!-- Seventh row with Purok selection, contact, and nationality -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
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
    </div>
    <div class="col-lg-6 col-md-12 form-group">
      <label for="cp_number">CP Number:</label>
      <input type="text" id="cp_number" name="cp_number" class="form-control" placeholder="Enter your number" required>
    </div>
  </div>

  <!-- Eighth row with nationality and birth date -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="nationality">Nationality/Citizenship:</label>
      <input type="text" id="nationality" name="nationality" class="form-control" required>
    </div>
    <div class="col-lg-6 col-md-12 form-group">
      <label for="birth_date">Birth Date:</label>
      <input type="date" id="birth_date" name="birth_date" class="form-control" required>
    </div>
  </div>

  <!-- Ninth row with gender and age -->
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

  <!-- Tenth row with place of birth and civil status -->
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
  </div>

  <!-- Eleventh row with educational background -->
  <div class="row">
    <div class="col-lg-6 col-md-12 form-group">
      <label for="educational_background">Educational Attainment:</label>
      <select id="educational_background" name="educational_background" class="form-control" required>
        <option value="No Formal Education">No Formal Education</option>
        <option value="Elementary">Elementary</option>
        <option value="Highschool">Highschool</option>
        <option value="College">College</option>
        <option value="Post Graduate">Post Graduate</option>
      </select>
    </div>
  </div>
</div>

     
          <!-- Submit Button -->
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="button-container">
    <button onclick="window.history.back();" class="btn btn-danger">
        <i class="fa fa-arrow-left"></i> Back
    </button>
</div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="../scripts/script.js"></script>
  
    <!-- Include jQuery and Bootstrap JavaScript -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

$(document).ready(function() {
  // Open the modal when a button is clicked
  $('#openModalButton').click(function() {
    $('#categoryModal').modal('show');
  });

  // Close the modal  
  $('#categoryModal').on('hidden.bs.modal', function () {
    // You can reset any content here if necessary
    console.log('Modal closed');
  });

  // Optional: Any additional logic when the modal is shown
  $('#categoryModal').on('shown.bs.modal', function () { 
    console.log('Modal is open');
  });
});





        // Check if the session variable is set and show SweetAlert
       

        function onSubmitForm() {
            // Check if image field is empty
            var imageField = document.getElementById('image');
            if (imageField.value.trim() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please upload an image!',
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }


        function confirmLogout() {
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#212529",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, logout"
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout URL
                window.location.href = " ../reg/login.php?logout=<?php echo $_SESSION['user_id']; ?>";
            }
        });

    }

    </script>



<script>
            document.getElementById('category').addEventListener('change', function() {
                var otherCategoryGroup = document.getElementById('other-category-group');
                if (this.value === 'Other') {
                    otherCategoryGroup.style.display = 'block';
                } else {
                    otherCategoryGroup.style.display = 'none';
                    document.getElementById('other-category').value = ''; // Clear the input field
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


    

    function markNotificationsAsRead() {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ markAsRead: true })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector(".badge.bg-danger");
                if (badge) {
                    badge.classList.add("d-none");
                }
            } else {
                console.error("Failed to mark notifications as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }






<?php if (isset($_SESSION['alert_message'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['alert_type'] ?>',
            title: '<?= $_SESSION['alert_message'] ?>'
        });
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); endif; ?>

            
        </script>
    
</body>
</html>