<?php
// Start the session at the beginning
session_start();

// Include your database connection file
include '../connection/dbconn.php'; 
include '../includes/bypass.php';

require_once( 'vendor/autoload.php' );

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';
$results_per_page = 10; 

// Determine current page
$page = !isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] <= 0 ? 1 : $_GET['page'];

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;

function displayComplaints($pdo, $start_from, $results_per_page) {
    try {
        $barangay_name = $_SESSION['barangay_name'] ?? '';

        $stmt = $pdo->prepare("
        SELECT c.*, 
               b.barangay_name, 
               cc.complaints_category,
               c_cert.cert_path,
               u.cp_number,          
               u.gender,            
               u.place_of_birth,    
               u.age,               
               u.nationality,
               u.educational_background,
               u.civil_status,
               u.purok,
               GROUP_CONCAT(DISTINCT e.evidence_path SEPARATOR ',') AS evidence_paths,
               GROUP_CONCAT(DISTINCT CONCAT(h.hearing_date, '|', h.hearing_time, '|', h.hearing_type, '|', h.hearing_status) SEPARATOR ',') AS hearing_history,
               c_cert.cert_path AS certificate_path -- Add certificate path from tbl_complaints_certificates
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        JOIN tbl_users u ON c.user_id = u.user_id  
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
        LEFT JOIN tbl_complaints_certificates c_cert ON c.complaints_id = c_cert.complaints_id -- Join with tbl_complaints_certificates
        WHERE c.status = 'Approved' AND c.barangay_saan = ?
        GROUP BY c.complaints_id
        ORDER BY c.date_filed DESC
        LIMIT ?, ?
    ");
     
    
    

        $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $start_from, PDO::PARAM_INT);
        $stmt->bindParam(3, $results_per_page, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='4'>No complaints found.</td></tr>";
        } else {
            $rowNumber = $start_from + 1; // Initialize row number

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $complaint_id = htmlspecialchars($row['complaints_id']);
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $complaint_ano = htmlspecialchars($row['ano']);
                $complaint_barangay_saan= htmlspecialchars($row['barangay_saan']);
// Combine kailan_date and kailan_time fields
$complaint_kailan = htmlspecialchars($row['kailan_date']) . ' ' . htmlspecialchars($row['kailan_time']);
                $complaint_paano = htmlspecialchars($row['paano']);
                $complaint_bakit= htmlspecialchars($row['bakit']);
                $complaint_description = htmlspecialchars($row['complaints']);
                $complaint_category = htmlspecialchars($row['complaints_category']);
                $complaint_barangay = htmlspecialchars($row['barangay_name']);
                $complaint_purok = htmlspecialchars($row['purok']);

                $complaint_contact = htmlspecialchars($row['cp_number']);
                $complaint_person = htmlspecialchars($row['complaints_person']);
                $complaint_gender = htmlspecialchars($row['gender']);
                $complaint_place_of_birth = htmlspecialchars($row['place_of_birth']);
                $complaint_age = htmlspecialchars($row['age']);
                $complaint_nationality = htmlspecialchars($row['nationality']);
                $complaint_education = htmlspecialchars($row['educational_background']);
                $complaint_civil_status = htmlspecialchars($row['civil_status']);
                $complaint_evidence = htmlspecialchars($row['evidence_paths']);
                $complaint_date_filed = htmlspecialchars($row['date_filed']);
                $complaint_status = htmlspecialchars($row['status']);
                $complaint_hearing_status = htmlspecialchars($row['hearing_history']);
                $complaint_cert_path = htmlspecialchars($row['cert_path']);


                echo "<tr>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$rowNumber}</td>"; // Display row number centered
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_name}</td>"; // Align name to the left
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_date_filed }</td>"; 
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_barangay }</td>";
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_purok }</td>";
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_ano }</td>"; 
                     
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_barangay_saan }</td>"; 
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_kailan }</td>"; 
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_paano }</td>"; 
                                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_bakit }</td>";
                               

                                // Align name to the left

                
                echo "<td style='text-align: center; vertical-align: middle;'>
                        <button type='button' class='btn btn-primary view-details-btn' 
                                data-id='{$complaint_id}' 
                                data-name='{$complaint_name}' 
                                 data-ano='{$complaint_ano}' 
                                      data-saan='{$complaint_barangay_saan}' 
                                        data-kailan='{$complaint_kailan}' 
                                        data-paano='{$complaint_paano}'
                                        data-bakit='{$complaint_bakit}' 


                                data-description='{$complaint_description}' 
                                data-category='{$complaint_category}' 
                                data-barangay='{$complaint_barangay}' 
                                data-contact='{$complaint_contact}' 
                                data-person='{$complaint_person}' 
                                data-gender='{$complaint_gender}' 
                                data-birth_place='{$complaint_place_of_birth}' 
                                data-age='{$complaint_age}' 
                                data-nationality ='{$complaint_nationality}'
                                data-education='{$complaint_education}' 
                                data-civil_status='{$complaint_civil_status}' 
                                data-evidence_paths='{$complaint_evidence}' 
                                data-date_filed='{$complaint_date_filed}' 
                                data-status='{$complaint_status}' 
                              data-cert_path='{$complaint_cert_path}' 

                                data-hearing_history='{$complaint_hearing_status}' 
                                data-bs-toggle='modal' data-bs-target='#complaintModal'>
                            View Details
                        </button>
                      </td>"; // Align button to center
            echo "</tr>";
            

                $rowNumber++; // Increment row number
            }
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='4'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
    }
}

// Handle status update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['new_status'];

    try {
        $responds = '';
        if ($new_status === 'settled_in_barangay') {
            $responds = 'barangay';
        } elseif ($new_status === 'pnp') {
            $responds = 'pnp';
        }

        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ?, responds = ? WHERE complaints_id = ?");
        $stmt->execute([$new_status, $responds, $complaint_id]);

        header("Location: {$_SERVER['PHP_SELF']}?page={$page}");
        exit();
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
}

// Handle hearing update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_hearing'])) {
    $complaint_id = $_POST['complaint_id'];
    $hearing_date = $_POST['hearing_date'];
    $hearing_time = $_POST['hearing_time'];
    $hearing_type = $_POST['hearing_type'];
    $hearing_status = $_POST['hearing_status'];

    try {
        // Delete existing hearing history for this complaint
        $stmt = $pdo->prepare("DELETE FROM tbl_hearing_history WHERE complaints_id = ?");
        $stmt->execute([$complaint_id]);

        // Insert new hearing details
        $stmt = $pdo->prepare("INSERT INTO tbl_hearing_history (complaints_id, hearing_date, hearing_time, hearing_type, hearing_status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_id, $hearing_date, $hearing_time, $hearing_type, $hearing_status]);

        header("Location: {$_SERVER['PHP_SELF']}?page={$page}");
        exit();
    } catch (PDOException $e) {
        echo "Error updating hearing details: " . $e->getMessage();
    }
}



$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$results_per_page = 10; // Set how many results per page
$start_from = ($page - 1) * $results_per_page;

// Count total complaints
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tbl_complaints WHERE status = 'Approved'");
$stmt->execute();
$total_results = $stmt->fetchColumn();
$total_pages = ceil($total_results / $results_per_page);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<style>


.sidebar-toggler {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: transparent; /* Changed from #082759 to transparent */
    border: none;
    cursor: pointer;
    color: white;
    text-align: left;
    width: auto; /* Adjust width automatically */
}
.sidebar{
  background-color: #082759;
}
.navbar{
  background-color: #082759;

}

.navbar-brand{
color: whitesmoke;
margin-left: 5rem;
}


 .table thead th {
            background-color: #082759;

            color: #ffffff;
            text-align: center;
        }

        label {
    font-weight: bold;
    margin-bottom: 5px;
}

span {
    display: block;
    margin-bottom: 10px;
}

body{
    background-color: #ffffff;
}
      
</style>
<body>

    
<?php 

include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/edit-profile.php';
?>
    <!-- Page Content -->
    <div class="content">
    <div class="container">
        <h2 class="mt-3 mb-4">Complaints Status</h2>



        
    <!-- Dropdown for sorting -->



        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">


            
            <form method="POST">
    <label class="form-label">Sort by Status:</label>
    <select id="statusDropdown" name="status" onchange="handleStatusChange(this.value)">
        <option value="">select</option>
        <option value="Approved" 
            <?php echo (isset($_GET['status']) && $_GET['status'] == 'Approved') ? 'selected' : ''; ?>>
            Approved
        </option>
        <option value="In Progress" 
            <?php echo (isset($_GET['status']) && $_GET['status'] == 'In Progress') ? 'selected' : ''; ?>>
            In Progress
        </option>
    </select>
</form>

<script>
function handleStatusChange(status) {
    if (status === 'Approved') {
        window.location.href = 'barangay-responder.php?status=' + status;
    } else if (status === 'In Progress') {
        window.location.href = 'manage-complaints.php?status=' + status;
    }
}
</script>


<script>
function handleStatusChange(status) {
    if (status === 'Approved') {
        window.location.href = 'barangay-responder.php';
    } else if (status === 'In Progress') {
        window.location.href = 'manage-complaints.php';
    }
}
</script>

<!-- Add Walk-In Button -->



<div style="width: 100%; text-align: center;">
    <button onclick="window.location.href='add_walkin_page.php';" class="btn btn-primary">Add Walk-In</button>
    <button onclick="window.location.href='cert.php';" class="btn btn-success">Make  certificate</button>

</div >

<div>
</div >
       
                <tr>
                <th style="text-align: center; vertical-align: middle;">#</th> <!-- Row number centered -->
            <th style="text-align: left; vertical-align: middle;">Complaint Name</th> <!-- Complaint name aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Date Filed</th> <!-- Date filed aligned to the left -->
            <th style="text-align: left; vertical-align: middle;"><Address></Address></th> <!-- Barangay aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Purok</th> <!-- Purok aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">What</th> <!-- Ano aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Where</th> <!-- Saan aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">When</th> <!-- Kailan aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">How</th> <!-- Paano aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">why</th> <!-- Bakit aligned to the left -->
            <th style="text-align: center; vertical-align: middle;">Action</th> <!-- Action button aligned to the center -->
                </tr>
            </thead>
            <tbody>
                <?php displayComplaints($pdo, $start_from, $results_per_page); ?>
            </tbody>
        </table>


        

        <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <!-- Previous Page Link -->
    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= ($page > 1) ? $_SERVER['PHP_SELF'] . '?page=' . ($page - 1) : '#' ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
        <a class="page-link" href="<?= $_SERVER['PHP_SELF'] . '?page=' . $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next Page Link -->
    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= ($page < $total_pages) ? $_SERVER['PHP_SELF'] . '?page=' . ($page + 1) : '#' ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>



    </div>
</div>






    <div class="modal fade" id="viewsComplaintModal" tabindex="-1" aria-labelledby="viewsComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewsComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Existing fields -->

                <div id="hearingSection" style="display: none;">
    <h5 class="mt-4">Set Hearing Date and Time</h5>
    <form id="hearingForm">
        <div class="mb-3">
            <label for="hearing-date" class="form-label">Hearing Date</label>
            <input type="date" class="form-control" id="hearing-date" name="hearing_date" >
        </div>
        <div class="mb-3">
            <label for="hearing-time" class="form-label">Hearing Time</label>
            <input type="time" class="form-control" id="hearing-time" name="hearing_time" >
        </div>
        <div class="mb-3">
            <label for="hearing-type" class="form-label">Hearing Type</label>
            <select class="form-select" id="hearing-type" name="hearing_type" >
                <option value="" >Select Hearing Type</option>
                <option value="First Hearing">First Hearing</option>
                <option value="Second Hearing">Second Hearing</option>
                <option value="Third Hearing">Third Hearing</option>
            </select>
        </div>

        <div class="mb-3">
                            <label for="hearing-status" class="form-label">Hearing Status</label>
                            <select class="form-select" id="hearing-status" name="hearing_status" >
                                <option value="" >Select Hearing Status</option>
                                <option value="Attended">Attended</option>
                                <option value="Not Attended">Not Attended</option>
                                <option value="Not Resolved">Not Resolved</option>
                            </select>
                        </div>
        <button type="submit" class="btn btn-primary">Set Hearing</button>
    </form>
</div>


                <!-- Additional fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





           

    <?php

include 'complaints_viewmodal.php';

?>

    <!-- Complaint Modal -->



    <div id="notificationCard" class="card d-none" style="position: absolute; top: 50px; right: 10px; width: 300px; z-index: 1050;"></div>



    <!-- Bootstrap JS and dependencies -->
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/script.js"></script>



<!-- Bootstrap JavaScript link -->
 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    
  <script>


function getCurrentDate() { 
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Set the min attribute of the date input
    document.getElementById('hearing-date').setAttribute('min', getCurrentDate());
             document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    });


    // Handle Move to PNP button click
    document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details-btn');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Populate modal fields with dataset values
            document.getElementById('modal-name').value = this.dataset.name;
            document.getElementById('modal-ano').value = this.dataset.ano;
            document.getElementById('modal-saan').value = this.dataset.saan;
            document.getElementById('modal-kailan').value = this.dataset.kailan;
            document.getElementById('modal-paano').value = this.dataset.paano;
            document.getElementById('modal-bakit').value = this.dataset.bakit;
            document.getElementById('modal-description').value = this.dataset.description;
            document.getElementById('modal-category').value = this.dataset.category;
            document.getElementById('modal-barangay').value = this.dataset.barangay;
            document.getElementById('modal-contact').value = this.dataset.contact;
            document.getElementById('modal-person').value = this.dataset.person;
            document.getElementById('modal-gender').value = this.dataset.gender;
            document.getElementById('modal-birth_place').value = this.dataset.birth_place;
            document.getElementById('modal-age').value = this.dataset.age;
            document.getElementById('modal-education').value = this.dataset.education;
            document.getElementById('modal-civil_status').value = this.dataset.civil_status;
            document.getElementById('modal-date_filed').value = this.dataset.date_filed;
            document.getElementById('modal-status').value = this.dataset.status;
            document.getElementById('modal-nationality').value = this.dataset.nationality;

            // Handle Hearing History
            let hearingHistoryHtml = '<h5>Hearing History:</h5>';
            if (this.dataset.hearing_history) {
                let hearings = this.dataset.hearing_history.split(',');
                hearingHistoryHtml += '<table class="table"><thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead><tbody>';
                
                hearings.forEach(function (hearing) {
                    let details = hearing.split('|');
                    hearingHistoryHtml += `<tr>
                        <td>${details[0]}</td>
                        <td>${details[1]}</td>
                        <td>${details[2]}</td>
                        <td>${details[3]}</td>
                    </tr>`;
                });

                hearingHistoryHtml += '</tbody></table>';
            } else {
                hearingHistoryHtml += '<p>No hearing history available.</p>';
            }
            document.getElementById('modalHearingHistorySection').innerHTML = hearingHistoryHtml;

            // Handle Evidence Display
            let evidenceHtml = '<h5>Evidence:</h5>';
            if (this.dataset.evidence_paths) {
                let evidencePaths = this.dataset.evidence_paths.split(',').map(path => path.trim());
                if (evidencePaths.length > 0) {
                    evidenceHtml += '<ul>';
                    evidencePaths.forEach(function (path) {
                        let fileExtension = path.split('.').pop().toLowerCase();
                        if (fileExtension === 'pdf') {
                            evidenceHtml += `<li><a href="../uploads/${path}" target="_blank">View Evidence (PDF)</a></li>`;
                        } else {
                            evidenceHtml += `<li><a href="../uploads/${path}" target="_blank">View Evidence</a></li>`;
                        }
                    });
                    evidenceHtml += '</ul>';
                } else {
                    evidenceHtml += '<p>No evidence available.</p>';
                }
            } else {
                evidenceHtml += '<p>No evidence available.</p>';
            }
            document.getElementById('modalEvidenceSection').innerHTML = evidenceHtml;

            // Handle Certificate Display
            let certPath = this.dataset.cert_path;
            let certContainer = document.getElementById('modalCertificateSection');
            let certImg = document.getElementById('modal-cert_path');

            if (certPath) {
                let fileExtension = certPath.split('.').pop().toLowerCase();
                let imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (imageExtensions.includes(fileExtension)) {
                    certContainer.innerHTML = `<img src="../uploads/${certPath}" 
                        alt="Certificate" 
                        style="max-width: 40%; height: auto; cursor: pointer;" 
                        onclick="viewFile('../uploads/${certPath}', 'image')">`;
                    certImg.style.display = 'block';
                } else if (fileExtension === 'pdf') {
                    certContainer.innerHTML = `<a href="#" onclick="viewFile('../uploads/${certPath}', 'pdf')">View Certificate (PDF)</a>`;
                    certImg.style.display = 'none';
                } else {
                    certContainer.innerHTML = `<a href="../uploads/${certPath}" download>Download Certificate</a>`;
                    certImg.style.display = 'none';
                }
            } else {
                certContainer.innerHTML = "<p>No certificate uploaded.</p>";
            }

            // Store the complaint ID in the modal
            document.getElementById('complaintModal').setAttribute('data-complaint-id', this.dataset.id);
        });
    });
});

// Function to view files (images and PDFs)
function viewFile(filePath, type) {
    let viewerModal = document.getElementById('fileViewerModal');
    let viewerContent = document.getElementById('fileViewerContent');

    if (type === 'image') {
        viewerContent.innerHTML = `<img src="${filePath}" style="max-width: 100%; height: auto;">`;
    } else if (type === 'pdf') {
        viewerContent.innerHTML = `<iframe src="${filePath}" style="width: 100%; height: 600px;"></iframe>`;
    }

    // Manually show the modal using Bootstrap's modal method
    $(viewerModal).modal('show');
}


function closeFileViewer() {
    let viewerModal = document.getElementById('fileViewerModal');
    let viewerContent = document.getElementById('fileViewerContent');

    // Clear the content of the modal to avoid lingering data
    viewerContent.innerHTML = '';

    // Close the modal using Bootstrap's modal method
    $(viewerModal).modal('hide');
}



document.addEventListener('DOMContentLoaded', function() {
    const moveToPnpBtn = document.getElementById('moveToPnpBtn');
    const settleInBarangayBtn = document.getElementById('settleInBarangayBtn');

    moveToPnpBtn.addEventListener('click', function() {
        updateComplaintStatus('pnp');
    });

    settleInBarangayBtn.addEventListener('click', function() {
        updateComplaintStatus('settled_in_barangay');
    });

    function updateComplaintStatus(newStatus) {
        const complaintId = document.getElementById('complaintModal').getAttribute('data-complaint-id');
        
        fetch('update_complaint_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `complaint_id=${complaintId}&new_status=${newStatus}`
        })
        .then(response => response.text())
        .then(result => {
            Swal.fire({
                title: 'Success!',
                text: result,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Reload the page to reflect changes
            });
        })
        .catch(error => {
            console.error('Error:', error); 
            Swal.fire({
                title: 'Error!',
                text: 'There was an error updating the status.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all view-details buttons
    const viewButtons = document.querySelectorAll('.view-details-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const complaintId = this.dataset.id;
            const status = this.dataset.status;

            // Set the complaint ID as a data attribute on the modal
            document.getElementById('viewsComplaintModal').setAttribute('data-complaint-id', complaintId);

            // Show the hearing section if the complaint status is 'Approved'
            document.getElementById('hearingSection').style.display = status === 'Approved' ? 'block' : 'none';

            // Fetch existing hearing details
            fetch(`set_hearing.php?complaint_id=${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        const hearing = data[0];
                        document.getElementById('hearing-date').value = hearing.hearing_date || '';
                        document.getElementById('hearing-time').value = hearing.hearing_time || '';
                        document.getElementById('hearing-type').value = hearing.hearing_type || '';
                    } else {
                        document.getElementById('hearing-date').value = '';
                        document.getElementById('hearing-time').value = '';
                        document.getElementById('hearing-type').value = '';
                    }
                })
                .catch(error => console.error('Error fetching hearing details:', error));

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewComplaintModal'));
            modal.show();
        });
    });

    // Handle form submission for setting hearing details
    document.getElementById('hearingForm').addEventListener('submit', function(event) {
        event.preventDefault();
        setHearingDetails();
    });

    function setHearingDetails() {
        const complaintId = document.getElementById('viewsComplaintModal').getAttribute('data-complaint-id');
        const hearingDate = document.getElementById('hearing-date').value;
        const hearingTime = document.getElementById('hearing-time').value;
        const hearingType = document.getElementById('hearing-type').value;
        const hearingStatus = document.getElementById('hearing-status').value;

        // Send the form data to the server
        fetch('set_hearing.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `complaint_id=${complaintId}&hearing_date=${hearingDate}&hearing_time=${hearingTime}&hearing_type=${hearingType}&hearing_status=${hearingStatus}`
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // Display success or error message
            window.location.reload(); // Reload the page to reflect changes
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});




document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const complaintId = this.dataset.id;
            
            // Fetch hearing history
            fetch(`set_hearing.php?complaint_id=${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('hearingHistoryTableBody');
                    tableBody.innerHTML = ''; // Clear existing rows

                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    data.forEach(hearing => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${hearing.hearing_date}</td>
                            <td>${hearing.hearing_time}</td>
                            <td>${hearing.hearing_type}</td>
                            <td>${hearing.hearing_status}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Fetch error:', error));
        });
    });
});










document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const notificationCountBadge = document.getElementById('notificationCount');
    const notificationCard = document.getElementById('notificationCard');

    // Toggle the notification card
    notificationButton.addEventListener('click', function () {
        notificationCard.classList.toggle('d-none');
    });

    // Fetch notifications
    function fetchNotifications() {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Filter out notifications with 'Settled in Barangay' and 'Settled in PNP'
                const filteredNotifications = data.notifications.filter(notification => 
                    notification.status !== 'Settled in Barangay' && notification.status !== 'Settled in PNP'
                );

                const notificationCount = filteredNotifications.length;

                if (notificationCount > 0) {
                    notificationCountBadge.textContent = notificationCount;
                    notificationCountBadge.classList.remove('d-none');
                } else {
                    notificationCountBadge.textContent = "0";
                    notificationCountBadge.classList.add('d-none');
                }

                let notificationListHtml = '<div class="card-header">Notifications</div><div class="card-body" style="max-height: 300px; overflow-y: auto;">';

                if (notificationCount > 0) {
                    filteredNotifications.slice(0, 5).forEach(notification => {
                        notificationListHtml += `
                            <div class="card-text border-bottom p-2">
                                <strong>Complaint:</strong> <a href="barangaylogs.php?complaint=${encodeURIComponent(notification.complaint_name)}&barangay=${encodeURIComponent(notification.barangay_name)}&status=${encodeURIComponent(notification.status)}">${notification.complaint_name}</a><br>
                                <strong>Barangay:</strong> ${notification.barangay_name}<br>
                                <strong>Status:</strong> ${notification.status}
                            </div>`;
                    });
                } else {
                    notificationListHtml += '<div class="text-center">No new notifications</div>';
                }

                notificationListHtml += '</div>';
                notificationCard.innerHTML = notificationListHtml;

            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    // Initial fetch
    fetchNotifications();

    // Refresh notifications every 30 seconds
    setInterval(fetchNotifications, 30000);

    // Mark notifications as read when the button is clicked
    notificationButton.addEventListener('click', function () {
        markNotificationsAsRead();
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
                notificationCountBadge.classList.add('d-none');
            } else {
                console.error("Failed to mark notifications as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
});
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



     // Check if the session variable is set and show SweetAlert
     <?php 
        
        if (isset($_SESSION['success'])): ?>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your complaint has been submitted',
                showConfirmButton: false,
                timer: 1500
            });
          
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>



    </script>
</body>
</html>