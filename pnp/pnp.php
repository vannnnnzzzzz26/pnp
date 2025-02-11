
<?php 


session_start(); 
include '../includes/bypass.php';


$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';
$barangay_saan = $_SESSION['barangay_saan'] ?? '';

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
?>
<center>
<div class="content">
    <div class="container">
        <h2 class="mt-3 mb-4">Complaints</h2>

       <!-- Filter Row for Barangay and Category -->
<div class="row mb-3">
   <!-- Filter Row for Barangay, Category, and Date Range -->
<div class="row mb-3">
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
            $selectedBarangay = isset($_GET['barangay']) ? $_GET['barangay'] : '';
            $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
            $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
            $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
            
            $query = "
                SELECT c.*, cat.complaints_category, u.purok
                FROM tbl_complaints c
                JOIN tbl_users u ON u.user_id = c.user_id
                JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
                WHERE c.responds = 'pnp' AND c.status != 'Filed in the court'
            ";
            
            // Apply filter by barangay if selected
            if (!empty($selectedBarangay)) {
                $query .= " AND c.barangay_saan = :selectedBarangay";
            }
            
            // Apply filter by date range if provided
            if (!empty($fromDate)) {
                $query .= " AND c.date_filed >= :fromDate";
            }
            if (!empty($toDate)) {
                $query .= " AND c.date_filed <= :toDate";
            }
            
            // Apply filter by category if selected
            if (!empty($selectedCategory)) {
                $query .= " AND c.category_id = :selectedCategory";
            }
            
            $query .= " ORDER BY c.date_filed DESC";
            
            // Prepare the query
            $stmt = $pdo->prepare($query);
            
            // Bind the parameters if filters are applied
            if (!empty($selectedBarangay)) {
                $stmt->bindParam(':selectedBarangay', $selectedBarangay, PDO::PARAM_STR);
            }
            if (!empty($fromDate)) {
                $stmt->bindParam(':fromDate', $fromDate, PDO::PARAM_STR);
            }
            if (!empty($toDate)) {
                $stmt->bindParam(':toDate', $toDate, PDO::PARAM_STR);
            }
            if (!empty($selectedCategory)) {
                $stmt->bindParam(':selectedCategory', $selectedCategory, PDO::PARAM_INT);
            }
            
            // Execute the query
            $stmt->execute();
    
            // Check if there are any rows returned
            if ($stmt->rowCount() > 0) {
                $row_number = 1; // Initialize the row number
    
                // Loop through the results and display each complaint
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $complaint_id = $row['complaints_id'];
                    $date_filed = htmlspecialchars($row['date_filed']);

                    $complaint_name = htmlspecialchars($row['complaint_name']);
                    $complaint_purok = htmlspecialchars($row['purok']);

                    $complaint_barangay = htmlspecialchars($row['barangay_saan']);

                    $complaint_ano = htmlspecialchars($row['ano']);
                    $complaint_barangay_saan= htmlspecialchars($row['barangay_saan']);
                    $complaint_kailan = htmlspecialchars($row['kailan']);
                    $complaint_paano = htmlspecialchars($row['paano']);
                    $complaint_bakit= htmlspecialchars($row['bakit']);
                    $complaint_description = htmlspecialchars($row['complaints']);
                    $category = htmlspecialchars($row['complaints_category']);
    
                    // Fetch barangay name based on barangays_id
                    if (!empty($row['barangays_id'])) {
                        $stmtBar = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
                        $stmtBar->execute([$row['barangays_id']]);
                        $barangay_name = htmlspecialchars($stmtBar->fetchColumn());
                    } else {
                        $barangay_name = 'Unknown';
                    }
    
                    $address = $barangay_name;
    

                    
                    // Display the table row
                    echo "<td style='text-align: center; vertical-align: middle;'>{$row_number}</td>"; // Display row number centered
                    echo "<td style='text-align: left; vertical-align: middle;'>{$date_filed }</td>"; 

                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_name}</td>"; // Align name to the left
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_barangay }</td>";
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_purok }</td>";
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_ano }</td>"; 
                         
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_barangay_saan }</td>"; 
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_kailan }</td>"; 
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_paano }</td>"; 
                                    echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_bakit }</td>";
                    echo "<td class='category'>{$category}</td>"; // Display category
                    echo "<td><button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#viewDetailsModal' data-id='{$complaint_id}'>View Details</button></td>";
                    echo "</tr>";
    
                    $row_number++;
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='6' class='text-center'>Error fetching PNP complaints: " . $e->getMessage() . "</td></tr>";
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


    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" action="update_profile.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="middle_name" value="<?php echo htmlspecialchars($middleName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editExtensionName" class="form-label">Extension Name</label>
                        <input type="text" class="form-control" id="editExtensionName" name="extension_name" value="<?php echo htmlspecialchars($extensionName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProfilePic" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="editProfilePic" name="profile_pic">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>






    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../scripts/script.js"></script>
    <script>

document.getElementById("barangayFilter").addEventListener("change", function () {
    var selectedBarangay = this.value;

    // Reload the page with the selected barangay in the URL
    if (selectedBarangay === "") {
        window.location.href = window.location.pathname;
    } else {
        window.location.href = window.location.pathname + "?barangay=" + encodeURIComponent(selectedBarangay);
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
     













    document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const notificationCountBadge = document.getElementById("notificationCount");

    function fetchNotifications() {
        return fetch('notifications.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationCount = data.notifications.length;
                updateNotificationBadge(notificationCount);
                updatePopoverContent(data.notifications);
            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    function updateNotificationBadge(count) {
        notificationCountBadge.textContent = count > 0 ? count : "0";
        notificationCountBadge.classList.toggle("d-none", count === 0);
    }

    function updatePopoverContent(notifications) {
        let notificationListHtml = notifications.length > 0 ?
            notifications.map(notification => `
                <div class="dropdown-item" data-id="${notification.complaints_id}">
                    Complaint: ${notification.complaint_name}<br>
                    Barangay: ${notification.barangay_name}<br>
                    Status: ${notification.status}
                    <hr>
                </div>
            `).join('') :
            '<div class="dropdown-item text-center">No new notifications</div>';

        const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
        if (popoverInstance) {
            popoverInstance.setContent({ '.popover-body': notificationListHtml });
        } else {
            new bootstrap.Popover(notificationButton, {
                html: true,
                content: function () {
                    return `<div class="popover-content">${notificationListHtml}</div>`;
                },
                container: 'body'
            });
        }

        // Add click event listener to mark as read
        document.querySelectorAll('.popover-content .dropdown-item').forEach(item => {
            item.addEventListener('click', function () {
                const notificationId = this.getAttribute('data-id');
                markNotificationAsRead(notificationId);
            });
        });
    }

    function markNotificationAsRead(notificationId) {
  
        fetch('notifications.php?action=update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId, userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotifications(); // Refresh notifications
            } else {
                console.error("Failed to mark notification as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    // Fetch notifications when the page loads
    fetchNotifications();
});

    </script>
</body>
</html>
