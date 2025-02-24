<?php
// Start session and include database connection
include '../connection/dbconn.php'; 
include '../barangay/notifications.php';
require_once( 'vendor/autoload.php' );

include '../includes/bypass.php';

if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$barangay_name = isset($_SESSION['barangay_name']) ? $_SESSION['barangay_name'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

// Define pagination variables
$results_per_page = 10; // Number of complaints per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Handle status update
// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complaint_id']) && isset($_POST['action'])) {
    try {
        $complaint_id = $_POST['complaint_id'];
        $action = $_POST['action'];
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';

        // Fetch complainant's phone number and name
        $stmt = $pdo->prepare("SELECT u.cp_number, u.first_name, u.last_name 
                               FROM tbl_complaints c 
                               JOIN tbl_users u ON c.user_id = u.user_id 
                               WHERE c.complaints_id = ?");
        $stmt->execute([$complaint_id]);
        $complainant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($complainant) {
            $cp_number = $complainant['cp_number'];
            $complainant_name = $complainant['first_name'] . ' ' . $complainant['last_name'];

            // Update complaint status
            $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ? WHERE complaints_id = ?");
            $stmt->execute([$status, $complaint_id]);

            // Send SMS notification
            if (!empty($cp_number)) {
                sendSMSNotification($cp_number, $complainant_name, $status);
            }

            // Set success message
            $_SESSION['success'] = "Complaint status updated successfully.";
            header("Location: manage-complaints.php");
            exit();
        } else {
            $_SESSION['error'] = "Complainant not found.";
            header("Location: manage-complaints.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating complaint status: " . $e->getMessage();
        header("Location: manage-complaints.php");
        exit();
    }
}

// Function to send SMS
function sendSMSNotification($phone, $name, $status) {
    $ch = curl_init();
    $message = "Hello $name, your complaint status has been updated to: $status.";
    $parameters = array(
        'apikey' => '0a1c1d98b58a36653a8b7c1486b78786', // Replace with your actual API key
        'number' => $phone,
        'message' => $message,
        'sendername' => 'Copwatch'
    );
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
}


// Fetch complaints with status 'Inprogress' from the user's barangay
try {
    // Get total complaints count for pagination
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tbl_complaints c 
                           LEFT JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id 
                           WHERE c.status = 'inprogress' AND ub.barangay_name = ? AND c.status != 'Rejected'");
    $stmt->execute([$barangay_name]);
    $total_complaints = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Calculate total number of pages
    $total_pages = ceil($total_complaints / $results_per_page);

    // Fetch complaints for the current page
    $stmt = $pdo->prepare("
        SELECT c.*, ub.barangay_name, 
               cc.complaints_category,

               u.cp_number,          
               u.gender,            
               u.place_of_birth,    
               u.age,               
               u.nationality,
               u.civil_status,
               u.purok,
               u.selfie_path,
            u.educational_background,
               u.pic_data,
               e.evidence_id, 
               e.evidence_path
        FROM tbl_complaints c
        LEFT JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
        JOIN tbl_users u ON c.user_id = u.user_id  
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        WHERE c.status = 'inprogress' AND c.barangay_saan = ? AND c.status != 'Rejected'
         ORDER BY c.date_filed DESC -- Sort by latest date first
        LIMIT ?, ?
    ");
    $stmt->bindValue(1, $barangay_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $start_from, PDO::PARAM_INT);
    $stmt->bindValue(3, $results_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching complaints: " . $e->getMessage();
    $complaints = []; // Default empty complaints array in case of error
    $total_pages = 1; // Default to one page if error occurs
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
.popover-content {
    background-color: whitesmoke; 
    padding: 10px; /* Add some padding */
    border: 1px solid #495057; /* Optional: border for better visibility */
    border-radius: 5px; /* Optional: rounded corners */
    max-height: 300px; /* Ensure it doesn't grow too large */
    overflow-y: auto; /* Add vertical scroll if needed */
}

/* Adjust the arrow for the popover to ensure it points correctly */
.popover .popover-arrow {
    border-top-color: #343a40; /* Match the background color */
}


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
<div class="content">
<div class="container mt-5">
    <h1>Resident Complaints</h1>

    <!-- Display success or error messages using SweetAlert -->
    <script>
        <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $_SESSION['success']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $_SESSION['error']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

<table class="table table-bordered table-hover">
    <thead>
        
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



<tr>
            <th style="text-align: center; vertical-align: middle;">#</th> <!-- Row number centered -->
            <th style="text-align: left; vertical-align: middle;">Complaint Name</th> <!-- Complaint name aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Date Filed</th> <!-- Date filed aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Address</th> <!-- Barangay aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Purok</th> <!-- Purok aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">What</th> <!-- Ano aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Where</th> <!-- Saan aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">When</th> <!-- Kailan aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">How</th> <!-- Paano aligned to the left -->
            <th style="text-align: left; vertical-align: middle;">Why</th> <!-- Bakit aligned to the left -->
            <th style="text-align: center; vertical-align: middle;">Action</th> <!-- Action button aligned to the center -->
        </tr>
    </thead>
    <tbody>
        <?php 
        $rowNumber = 1; // Initialize row number

        foreach ($complaints as $complaint): ?>
            <tr>
                <td style="text-align: center; vertical-align: middle;"><?php echo $rowNumber++; ?></td> <!-- Display row number centered -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['complaint_name']); ?></td> <!-- Left-align complaint name -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['date_filed']); ?></td> <!-- Left-align date filed -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['barangay_name']); ?></td> <!-- Left-align barangay name -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['purok']); ?></td> <!-- Left-align purok -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['ano']); ?></td> <!-- Left-align ano -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['barangay_saan']); ?></td> <!-- Left-align saan -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['kailan_date']) . ' ' . htmlspecialchars($complaint['kailan_time']); ?></td>

                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['paano']); ?></td> <!-- Left-align paano -->
                <td style="text-align: left; vertical-align: middle;"><?php echo htmlspecialchars($complaint['bakit']); ?></td> <!-- Left-align bakit -->
                <td style="text-align: center; vertical-align: middle;">
                    <button type="button" class="btn btn-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewComplaintModal" 
                            data-complaint='<?php echo htmlspecialchars(json_encode($complaint), ENT_QUOTES, 'UTF-8'); ?>'>
                        View
                    </button>
                </td> <!-- Center action button -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Pagination Links -->
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <!-- Previous Page Link -->
    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $_SERVER['PHP_SELF'] . '?page=' . ($page - 1) ?>" aria-label="Previous">
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
      <a class="page-link" href="<?= $_SERVER['PHP_SELF'] . '?page=' . ($page + 1) ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>

</div>
</div>



<!-- Complaint Details Modal -->
<!-- Complaint Details Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Complainant:</strong></p>
                        <p id="complaintName" class="border p-2"></p>

                        <p><strong>Complaint:</strong></p>
                        <p id="Complaints" class="border p-2"></p>

                        <p><strong>Date Filed:</strong></p>
                        <p id="dateFiled" class="border p-2"></p>

                        <p><strong>Category:</strong></p>
                        <p id="category" class="border p-2"></p>

                        <p><strong>Barangay:</strong></p>
                        <p id="barangay" class="border p-2"></p>

                        <p><strong>Purok:</strong></p>
                        <p id="purok" class="border p-2"></p>

                        <p><strong>Contact Number:</strong></p>
                        <p id="contactNumber" class="border p-2"></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Complaints Person:</strong></p>
                        <p id="complaintsPerson" class="border p-2"></p>

                        <p><strong>Gender:</strong></p>
                        <p id="gender" class="border p-2"></p>

                        <p><strong>Place of Birth:</strong></p>
                        <p id="placeOfBirth" class="border p-2"></p>

                        <p><strong>Age:</strong></p>
                        <p id="age" class="border p-2"></p>

                        <p><strong>Educational Background:</strong></p>
                        <p id="educationalBackground" class="border p-2"></p>

                        <p><strong>Civil Status:</strong></p>
                        <p id="civilStatus" class="border p-2"></p>

                        <p><strong>Nationality:</strong></p>
                        <p id="nationality" class="border p-2"></p>

                        <p><strong>Evidence:</strong></p>
                        <p id="evidence" class="border p-2"></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 text-center">
                        <p><strong>Verification ID:</strong></p>
                        <img id="image" src="" alt="Complaint Image" style="max-width: 100px; cursor: pointer;">
                    </div>
                    <div class="col-md-6 text-center">
                        <p><strong>Selfie:</strong></p>
                        <img id="pic" src="" alt="Selfie" style="max-width: 100px; cursor: pointer;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="manage-complaints.php" method="post" class="d-inline-block">
                    <input type="hidden" name="complaint_id" id="complaintIdForForm">
                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                    <button type="submit" name="action" value="reject" class="btn btn-warning">Reject</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Image Viewing Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">View Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="View Image" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <video id="modalVideo" controls class="w-100">
                    <source id="videoSource" src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>
<div id="notificationCard" class="card d-none" style="position: absolute; top: 50px; right: 10px; width: 300px; z-index: 1050;"></div>

<!-- Include JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
<script src="../scripts/script.js"></script>

<script>
// Initialize Bootstrap modals
const viewComplaintModal = new bootstrap.Modal(document.getElementById('viewComplaintModal'), { keyboard: false });
const imageModal = new bootstrap.Modal(document.getElementById('imageModal'), { keyboard: false });
const videoModal = new bootstrap.Modal(document.getElementById('videoModal'), { keyboard: false });

document.addEventListener('DOMContentLoaded', function () {
    const modalButtons = document.querySelectorAll('[data-bs-target="#viewComplaintModal"]');
    modalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const complaint = JSON.parse(this.getAttribute('data-complaint'));

            // Populate modal fields with complaint details
            document.getElementById('complaintName').textContent = complaint.complaint_name;
            document.getElementById('Complaints').textContent = complaint.complaints;
            document.getElementById('dateFiled').textContent = complaint.date_filed;
            document.getElementById('category').textContent = complaint.complaints_category;
            document.getElementById('barangay').textContent = complaint.barangay_name;
            document.getElementById('contactNumber').textContent = complaint.cp_number;
            document.getElementById('complaintsPerson').textContent = complaint.complaints_person;
            document.getElementById('gender').textContent = complaint.gender;
            document.getElementById('placeOfBirth').textContent = complaint.place_of_birth;
            document.getElementById('age').textContent = complaint.age;
            document.getElementById('educationalBackground').textContent = complaint.educational_background;
            document.getElementById('purok').textContent = complaint.purok;

            document.getElementById('civilStatus').textContent = complaint.civil_status;
            document.getElementById('nationality').textContent = complaint.nationality;
            document.getElementById('image').setAttribute('src', complaint.selfie_path || '');
            document.getElementById('pic').setAttribute('src', complaint.pic_data || '');

            
            document.getElementById('complaintIdForForm').value = complaint.complaints_id;

            // Handle Evidence Display
            let evidenceHtml = '';
            if (complaint.evidence_path) {
                const evidenceArray = complaint.evidence_path.split(',');
                evidenceArray.forEach(evidencePath => {
                    const fileExtension = evidencePath.split('.').pop().toLowerCase();
                    if (['mp4', 'mov', 'avi', 'wmv'].includes(fileExtension)) {
                        evidenceHtml += `<a href="#" data-video="${evidencePath}" class="view-media" data-type="video">View Video</a><br>`;
                    } else {
                        evidenceHtml += `<a href="#" data-image="${evidencePath}" class="view-media" data-type="image">View Image</a><br>`;
                    }
                });
            } else {
                evidenceHtml = 'No Evidence Available';
            }
            document.getElementById('evidence').innerHTML = evidenceHtml;

            // Add event listeners for viewing media in modals
            document.querySelectorAll('.view-media').forEach(item => {
                item.addEventListener('click', function (event) {
                    event.preventDefault();
                    const type = this.getAttribute('data-type');
                    if (type === 'image') {
                        const src = this.getAttribute('data-image');
                        document.getElementById('modalImage').setAttribute('src', src);
                        imageModal.show();
                    } else if (type === 'video') {
                        const src = this.getAttribute('data-video');
                        document.getElementById('videoSource').setAttribute('src', src);
                        document.getElementById('modalVideo').load(); // Reload the video element
                        videoModal.show();
                    }
                });
            });
        });
    });

    // Event listener for image click (to open in modal)
    const complaintImage = document.getElementById('image');
    complaintImage.addEventListener('click', function () {
        const src = this.getAttribute('src');
        if (src) {
            document.getElementById('modalImage').setAttribute('src', src);
            imageModal.show();
        }
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

    document.addEventListener('DOMContentLoaded', function () {
    var profilePic = document.querySelector('.profile');
    var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

    profilePic.addEventListener('click', function () {
        editProfileModal.show();
    });
});
</script>


</body>
</html>