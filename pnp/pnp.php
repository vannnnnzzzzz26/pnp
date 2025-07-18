
<?php 


session_start(); 
include '../includes/bypass.php';


$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$cp_number = $_SESSION['cp_number'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
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
        
        }

      
    </style>
<body>
 
<?php 

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
include '../includes/edit-profile.php';

?>
<center>




<div class="content">
    <div class="container">
        <h2 class="mt-3 mb-4">Complaints</h2>
        <form method="GET">
    <label class="form-label">Filter by Barangay:</label>
    <select id="barangayDropdown" name="barangay" onchange="this.form.submit()">
        <option value="">All</option>
        <?php
        // Fetch unique barangay names from tbl_complaints
        $stmtBarangay = $pdo->query("SELECT DISTINCT barangay_saan FROM tbl_complaints WHERE barangay_saan IS NOT NULL ORDER BY barangay_saan");
        while ($rowBarangay = $stmtBarangay->fetch(PDO::FETCH_ASSOC)) {
            $selected = isset($_GET['barangay']) && $_GET['barangay'] == $rowBarangay['barangay_saan'] ? 'selected' : '';
            echo "<option value='{$rowBarangay['barangay_saan']}' $selected>{$rowBarangay['barangay_saan']}</option>";
        }
        ?>
    </select>

    <br><br>

    <label class="form-label">Filter by Date Range:</label><br>
    <label for="from_date">From: </label>
    <input type="date" id="from_date" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>" onchange="this.form.submit()">
    <label for="to_date">To: </label>
    <input type="date" id="to_date" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>" onchange="this.form.submit()">
    
    <br><br>
    
    <label class="form-label">Filter by Category:</label>
    <select id="categoryDropdown" name="category" onchange="this.form.submit()">
        <option value="">All</option>
        <?php
        // Fetch categories from tbl_complaintcategories
        $stmtCategory = $pdo->query("SELECT category_id, complaints_category FROM tbl_complaintcategories ORDER BY complaints_category");
        while ($rowCategory = $stmtCategory->fetch(PDO::FETCH_ASSOC)) {
            $selected = isset($_GET['category']) && $_GET['category'] == $rowCategory['category_id'] ? 'selected' : '';
            echo "<option value='{$rowCategory['category_id']}' $selected>{$rowCategory['complaints_category']}</option>";
        }
        ?>
    </select>
</form>





<div style="width: 100%; text-align: center;">
    <button onclick="window.location.href='add_account.php';" class="btn btn-primary">Add Barangay Acccount</button>
</div>


        <div class="table">
            <table class="table table-striped table-bordered table-center" id="complaintsTable">
                <thead>
                    <tr>
                    <th>#</th>
                        <th>Date Filed</th>
                        <th>Name</th>
                    
                        <th>Address</th>
                        <th>purok</th>
                        <th>ano</th>
                        <th>saan</th>
                        <th>kailan</th>
                        <th>paano</th>
                        <th>bakit</th>
                        <th>Category</th> <!-- Add Category Column -->
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                <?php
    // Function to display PNP complaints
    function displayPNPComplaints($pdo) {
        try {
            $conditions = ["c.responds = 'pnp'", "c.status != 'Filed in the court'"];
            $params = [];
    
            // Filter by Barangay
            if (!empty($_GET['barangay'])) {
                $conditions[] = "c.barangay_saan = ?";
                $params[] = $_GET['barangay'];
            }
    
            // Filter by Date Range
            if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
                $conditions[] = "c.date_filed BETWEEN ? AND ?";
                $params[] = $_GET['from_date'];
                $params[] = $_GET['to_date'];
            } elseif (!empty($_GET['from_date'])) {
                $conditions[] = "c.date_filed >= ?";
                $params[] = $_GET['from_date'];
            } elseif (!empty($_GET['to_date'])) {
                $conditions[] = "c.date_filed <= ?";
                $params[] = $_GET['to_date'];
            }
    
            // Filter by Category
            if (!empty($_GET['category'])) {
                $conditions[] = "c.category_id = ?";
                $params[] = $_GET['category'];
            }
    
            // Construct the query with conditions
            $query = "
                SELECT c.*, cat.complaints_category, u.purok
                FROM tbl_complaints c  
                JOIN tbl_users u ON u.user_id = c.user_id
                JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
                WHERE " . implode(" AND ", $conditions) . "
                ORDER BY c.date_filed DESC
            ";
    
            // Prepare and execute the query
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
    
            // Display results
            if ($stmt->rowCount() > 0) {
                $row_number = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ($row['kailan_date']) . ' ' . htmlspecialchars($row['kailan_time']);
                    echo "<tr>";
                    echo "<td style='text-align: center; vertical-align: middle;'>{$row_number}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['date_filed']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['complaint_name']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['barangay_saan']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['purok']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['ano']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['barangay_saan']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>" . htmlspecialchars($row['paano']) . ' ' . htmlspecialchars($row['kailan_time']) . "</td>";
                    
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['paano']}</td>"; 
                    echo "<td style='text-align: left; vertical-align: middle;'>{$row['bakit']}</td>"; 
                    echo "<td class='category'>{$row['complaints_category']}</td>"; 
                    echo "<td><button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#viewDetailsModal' data-id='{$row['complaints_id']}'>View Details</button></td>";
                    echo "</tr>";
                    $row_number++;
                }
            } else {
                echo "<tr><td colspan='12' class='text-center'>No record found</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='12' class='text-center'>Error fetching PNP complaints: " . $e->getMessage() . "</td></tr>";
        }
    }
    
    displayPNPComplaints($pdo);
?>

                </tbody>
            </table>

            </div>
        </div>
    </div>

    <!-- Modal for Viewing Details -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                        <!-- Content will be loaded here via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">

                <button type="button" class="btn btn-success" id="settleComplaintBtn">Settle Complaint</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

  </center>


   
</div>
<div id="notificationCard" class="card d-none" style="position: absolute; top: 50px; right: 10px; width: 300px; z-index: 1050;"></div>






    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../scripts/script.js"></script>
    <script>

document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
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
            method: 'GET',
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
                                <strong>Complaint:</strong> <a href="pnplogs.php?complaint=${encodeURIComponent(notification.complaint_name)}&barangay=${encodeURIComponent(notification.barangay_name)}&status=${encodeURIComponent(notification.status)}">${notification.complaint_name}</a><br>
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



document.addEventListener('DOMContentLoaded', function () {
    var viewDetailsButtons = document.querySelectorAll('button[data-bs-target="#viewDetailsModal"]');

    viewDetailsButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var complaintId = this.getAttribute('data-id');

            fetch('get_complaint_details.php?id=' + complaintId)
                .then(response => response.json())
                .then(data => {
                    var modalContent = document.getElementById('modalContent');
                    if (data.error) {
                        modalContent.innerHTML = `<p>Error: ${data.error}</p>`;
                    } else {
                        var evidenceHtml = '';

                        if (data.evidence && data.evidence.length > 0) {
                            evidenceHtml = '<h5>Evidence:</h5><ul>';
                            data.evidence.forEach(function(evidencePath) {
                                evidenceHtml += `<li><a href="../uploads/${evidencePath}" target="_blank">View Evidence</a></li>`;
                            });
                            evidenceHtml += '</ul>';
                        } else {
                            evidenceHtml = '<p>No evidence available.</p>';
                        }

                        var hearingHistoryHtml = '';

                        if (data.hearing_history && data.hearing_history.length > 0) {
                            hearingHistoryHtml = '<h5>Hearing History:</h5><table class="table"><thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead><tbody>';
                            data.hearing_history.forEach(function(hearing) {
                                hearingHistoryHtml += `
                                    <tr>
                                        <td>${hearing.hearing_date}</td>
                                        <td>${hearing.hearing_time}</td>
                                        <td>${hearing.hearing_type}</td>
                                        <td>${hearing.hearing_status}</td>
                                    </tr>
                                `;
                            });
                            hearingHistoryHtml += '</tbody></table>';
                        } else {
                            hearingHistoryHtml = '<p>No hearing history available.</p>';
                        }



                        var certificateHtml = '';

if (data.cert_path) {
    var fileExtension = data.cert_path.split('.').pop().toLowerCase();
    var fileUrl = '../uploads/' + data.cert_path; // Update with your upload folder path

    certificateHtml = `
        <h5>Certificate:</h5>
        <a href="${fileUrl}" target="_blank" onclick="window.open('${fileUrl}', '_blank', 'width=800,height=600'); return false;">
            ${['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension) ? 
                `<img src="${fileUrl}" alt="Certificate Image" style="max-width: 50%; height: auto; cursor: pointer;"/>` : 
                `Click here to view the certificate`}
        </a>
    `;
} else {
    certificateHtml = '<p>No certificate available.</p>';
}


                        
                        modalContent.innerHTML = `
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div><strong>Complainant:</strong>
            <textarea class="form-control" rows="1" readonly>${data.complaint_name}</textarea>
        </div>
        <div><strong>Complaints Person:</strong>
            <textarea class="form-control" rows="1" readonly>${data.complaints_person}</textarea>
        </div>

        <div><strong>Complaint:</strong>
            <textarea id="description" class="form-control" rows="4" readonly>${data.complaints}</textarea>
        </div>
        <div><strong>Gender:</strong>
            <textarea class="form-control" rows="1" readonly>${data.gender}</textarea>
        </div>

        <div><strong>Date Filed:</strong>
            <textarea class="form-control" rows="1" readonly>${data.date_filed}</textarea>
        </div>
        <div><strong>Place of Birth:</strong>
            <textarea class="form-control" rows="1" readonly>${data.place_of_birth}</textarea>
        </div>

        <div><strong>Category:</strong>
            <textarea class="form-control" rows="1" readonly>${data.category}</textarea>
        </div>
        <div><strong>Age:</strong>
            <textarea class="form-control" rows="1" readonly>${data.age}</textarea>
        </div>

        <div><strong>Barangay:</strong>
            <textarea class="form-control" rows="1" readonly>${data.barangay_name}</textarea>
        </div>
        <div><strong>Educational Background:</strong>
            <textarea class="form-control" rows="1" readonly>${data.educational_background}</textarea>
        </div>

        <div><strong>Purok:</strong>
            <textarea class="form-control" rows="1" readonly>${data.purok}</textarea>
        </div>
        <div><strong>Civil Status:</strong>
            <textarea class="form-control" rows="1" readonly>${data.civil_status}</textarea>
        </div>

        <div><strong>Contact Number:</strong>
            <textarea class="form-control" rows="1" readonly>${data.cp_number}</textarea>
        </div>
        <div><strong>Nationality:</strong>
            <textarea class="form-control" rows="1" readonly>${data.nationality}</textarea>
        </div>
    </div>
    ${certificateHtml} <!-- Certificate display -->

    ${evidenceHtml}
    ${hearingHistoryHtml}
`;



                        // Add complaint ID to the settle button
                        var settleButton = document.getElementById('settleComplaintBtn');
                        settleButton.setAttribute('data-id', complaintId);
                    }
                })
                .catch(error => {
                    var modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `<p>Error fetching details: ${error}</p>`;
                });
        });
    });
});

    // Handle "Settle Complaint" button click with SweetAlert
    document.getElementById('settleComplaintBtn').addEventListener('click', function () {
        var complaintId = this.getAttribute('data-id');

        if (complaintId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to settle this complaint?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, settle it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('settle_complaint.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: complaintId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Settled!',
                                'The complaint has been settled.',
                                'success'
                            ).then(() => {
                                location.reload(); // Refresh the page to update the complaints table
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to settle the complaint: ' + data.error,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An error occurred: ' + error.message,
                            'error'
                        );
                    });
                }
            });
        }
    });


   



document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
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
     
    </script>
</body>
</html>