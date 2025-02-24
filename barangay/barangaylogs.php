<?php
// Start the session
session_start();

// Include your database connection file
include '../connection/dbconn.php'; 
include '../includes/bypass.php';



// Initialize user information
$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$barangay = isset($_SESSION['barangays_id']) ? $_SESSION['barangays_id'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';
$birth_date = isset($_SESSION['birth_date']) ? $_SESSION['birth_date'] : '';
$age = isset($_SESSION['age']) ? $_SESSION['age'] : '';
$gender = isset($_SESSION['gender']) ? $_SESSION['gender'] : '';
$barangay_name = isset($_SESSION['barangay_name']) ? $_SESSION['barangay_name'] : '';
// Define pagination variables
$results_per_page = 10; // Number of results per page

// Determine current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Logs - Settled Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <style>
        table, th, td {
            border: none;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }



  
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
</head>
<body>
<?php 

include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/edit-profile.php';
?>
    <!-- Page Content -->
    <div class="content">
        <div class="container">

        
            <h2 class="mt-3 mb-4">Barangay Logs - Settled Complaints</h2>
        
            <table class="table table-bordered table-hover">
            <thead>


            <form method="GET">
    <label class="form-label">Filter by Purok:</label>
    <select id="purokDropdown" name="purok" onchange="this.form.submit()">
        <option value="">All</option>
        <?php
        $stmtPurok = $pdo->query("SELECT DISTINCT purok FROM tbl_users WHERE purok IS NOT NULL ORDER BY purok");
        while ($rowPurok = $stmtPurok->fetch(PDO::FETCH_ASSOC)) {
            $selected = isset($_GET['purok']) && $_GET['purok'] == $rowPurok['purok'] ? 'selected' : '';
            echo "<option value='{$rowPurok['purok']}' $selected>{$rowPurok['purok']}</option>";
        }
        ?>
    </select>
</form>



<form id="statusForm" method="POST">
    <label for="statusSelect" class="form-label"></label>
    <select id="statusSelect" name="status" class="form-select form-select-sm" style="width: 150px;" required>
        <option value="">-- Choose --</option>
        <option value="settled">Settled</option>
        <option value="rejected">Rejected</option>
    </select>
</form>

<script>
    document.getElementById("statusSelect").addEventListener("change", function() {
        var selectedValue = this.value;
        
        if (selectedValue === "settled") {
            window.location.href = "barangaylogs.php";
        } else if (selectedValue === "rejected") {
            window.location.href = "barangaylogs2.php";
        }
    });
</script>

                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date FIled</th>
                            <th> What</th>
                            <th>  Where</th>
                            <th> When</th>
                            <th> How</th>
                            <th> Why</th>

                          
                            <th>Barangay</th>
                            <th>Purok</th>
                            <th>Status</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                    <?php
try {
    $barangay_name = $_SESSION['barangay_name'] ?? '';
    $search_query = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

    // Fetch complaints data with pagination and search filter
    $purok_filter = isset($_GET['purok']) && !empty($_GET['purok']) ? $_GET['purok'] : '%';

    $stmt = $pdo->prepare("
        SELECT c.*, b.barangay_name, 
                   cc.complaints_category,
                   u.cp_number,          
                   u.gender,            
                   u.place_of_birth,    
                   u.age,               
                   u.nationality,
                   u.educational_background,
                   u.civil_status, e.evidence_path,
                   u.purok
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        JOIN tbl_users u ON c.user_id = u.user_id  
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        WHERE (c.status IN ('settled_in_barangay')) 
        AND c.barangay_saan = ?
        AND u.purok LIKE ?
        AND (c.complaint_name LIKE ? OR c.complaints LIKE ? OR cc.complaints_category LIKE ? OR u.gender LIKE ? OR u.place_of_birth LIKE ? OR u.educational_background LIKE ? OR u.civil_status LIKE ?)
        ORDER BY c.date_filed DESC
        LIMIT ?, ?
    ");
    
    $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
    $stmt->bindParam(2, $purok_filter, PDO::PARAM_STR);
    $stmt->bindParam(3, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(4, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(5, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(6, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(7, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(8, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(9, $search_query, PDO::PARAM_STR);
    $stmt->bindParam(10, $start_from, PDO::PARAM_INT);
    $stmt->bindParam(11, $results_per_page, PDO::PARAM_INT);
    $stmt->execute();
    

    if ($stmt->rowCount() == 0) {
        echo "<tr><td colspan='4'>No complaints found.</td></tr>";
    } else {
        $rowNumber = $start_from + 1; // Initialize row number

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $complaint_name = htmlspecialchars($row['complaint_name']);
            $date_filed = htmlspecialchars($row['date_filed']);

            $ano = htmlspecialchars($row['ano']);
            $barangay_saan = htmlspecialchars($row['barangay_saan']);
            $kailan = htmlspecialchars($row['kailan_date']) . ' ' . htmlspecialchars($row['kailan_time']);
            $paano = htmlspecialchars($row['paano']);
            $bakit = htmlspecialchars($row['bakit']);


            $barangay_name = htmlspecialchars($row['barangay_name']);
            $purok = htmlspecialchars($row['purok']);
            
            // ... other variables you might need

            echo "<tr>
            <td style='text-align: center; vertical-align: middle;'>{$rowNumber}</td>
            <td style='text-align: left; vertical-align: middle;'>{$complaint_name}</td>
               <td style='text-align: left; vertical-align: middle;'>{$date_filed}</td>
                <td style='text-align: left; vertical-align: middle;'>{$ano}</td>
                 <td style='text-align: left; vertical-align: middle;'>{$barangay_saan}</td>
                  <td style='text-align: left; vertical-align: middle;'>{$kailan}</td>
                   <td style='text-align: left; vertical-align: middle;'>{$paano}</td>
                    <td style='text-align: left; vertical-align: middle;'>{$bakit}</td>

            <td style='text-align: left; vertical-align: middle;'>{$barangay_name}</td>
               <td style='text-align: left; vertical-align: middle;'>{$purok}</td>
            <td style='text-align: center; vertical-align: middle;'>
                <button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$row['complaints_id']})'>View Details</button>
            </td>
        </tr>";
        

            $rowNumber++; // Increment row number
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}




$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tbl_complaints c JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id WHERE c.status = 'settled_in_barangay' AND b.barangay_name = ?");
$stmt->execute([$barangay_name]);
$total_results = $stmt->fetchColumn();
$total_pages = ceil($total_results / $results_per_page);
?>

                    </tbody>
                </table>
            </div>




            <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1&search=<?= htmlspecialchars($search_query); ?>">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= htmlspecialchars($search_query); ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>&search=<?= htmlspecialchars($search_query); ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= htmlspecialchars($search_query); ?>">Next</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $total_pages; ?>&search=<?= htmlspecialchars($search_query); ?>">Last</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

        </div>
    </div>

    <!-- Modal for Viewing Complaint Details -->
    <div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="complaintDetails">
                    <!-- Complaint details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  
    <div id="notificationCard" class="card d-none" style="position: absolute; top: 50px; right: 10px; width: 300px; z-index: 1050;"></div>


    <script src="../scripts/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script>


document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    });

      function loadComplaintDetails(complaintId) {
            fetch(`barangaydetails.php?id=${complaintId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('complaintDetails').innerHTML = data;
                    $('#viewComplaintModal').modal('show');
                })
                .catch(error => console.error('Error fetching complaint details:', error));
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

    document.addEventListener('DOMContentLoaded', function () {
    var hearingHistoryModal = document.getElementById('hearingHistoryModal');
    
    hearingHistoryModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var complaintId = button.getAttribute('data-complaint-id');

        // Fetch hearing history
        fetch(`hearing.php?complaint_id=${complaintId}`)
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

    </script>
</body>
</html>
