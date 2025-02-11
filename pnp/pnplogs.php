<?php
// Start session and retrieve user details
session_start();
$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_saan'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

include '../connection/dbconn.php'; 
include '../includes/bypass.php';

$results_per_page = 10; // Number of complaints per page

// Get the current page number from GET, if available, otherwise set to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

// Calculate the start row number for the SQL LIMIT clause
$start_from = ($page - 1) * $results_per_page;

// Get the search query from the GET request if available
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$barangay_filter = isset($_GET['barangay']) ? $_GET['barangay'] : ''; // Filter by barangay
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : ''; // Filter by from date
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : ''; // Filter by to date
$category_filter = isset($_GET['category']) ? $_GET['category'] : ''; // Filter by category

// Function to display complaints with pagination
function displayComplaintDetails($pdo, $search_query, $start_from, $results_per_page, $barangay_filter, $from_date, $to_date, $category_filter) {
    try {
        // Prepare the search query for LIKE
        $search_query = '%' . $search_query . '%';

        // Modify the SQL query to filter by barangay, date, and category if selected
        $sql = "
        SELECT c.complaints_id, c.complaint_name, c.barangay_saan, c.ano, c.date_filed, c.kailan, c.paano, c.bakit, c.complaints, cat.complaints_category,
        u.purok
        FROM tbl_complaints c
        JOIN tbl_users u ON u.user_id = c.user_id
        JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
        WHERE c.responds = 'pnp'
        AND (c.complaint_name LIKE ? OR c.barangay_saan LIKE ?)
        ";

        // Apply filters if provided
        if (!empty($barangay_filter)) {
            $sql .= " AND c.barangay_saan = ? ";
        }
        if (!empty($from_date)) {
            $sql .= " AND c.date_filed >= ? ";
        }
        if (!empty($to_date)) {
            $sql .= " AND c.date_filed <= ? ";
        }
        if (!empty($category_filter)) {
            $sql .= " AND c.category_id = ? ";
        }

        $sql .= " ORDER BY c.date_filed ASC LIMIT ?, ?";

        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(1, $search_query, PDO::PARAM_STR);
        $stmt->bindParam(2, $search_query, PDO::PARAM_STR);

        $bindIndex = 3; // Starting bind parameter index after search query

        if (!empty($barangay_filter)) {
            $stmt->bindParam($bindIndex++, $barangay_filter, PDO::PARAM_STR);
        }
        if (!empty($from_date)) {
            $stmt->bindParam($bindIndex++, $from_date, PDO::PARAM_STR);
        }
        if (!empty($to_date)) {
            $stmt->bindParam($bindIndex++, $to_date, PDO::PARAM_STR);
        }
        if (!empty($category_filter)) {
            $stmt->bindParam($bindIndex++, $category_filter, PDO::PARAM_INT);
        }

        // Bind the pagination parameters
        $stmt->bindParam($bindIndex++, $start_from, PDO::PARAM_INT);
        $stmt->bindParam($bindIndex++, $results_per_page, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='4'>No complaints found.</td></tr>";
        } else {
            $row_number = $start_from + 1;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $complaint_id = $row['complaints_id'];
                $date_filed = htmlspecialchars($row['date_filed']);
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $complaint_purok = htmlspecialchars($row['purok']);
                $complaint_barangay = htmlspecialchars($row['barangay_saan']);
                $complaint_ano = htmlspecialchars($row['ano']);
                $complaint_barangay_saan = htmlspecialchars($row['barangay_saan']);
                $complaint_kailan = htmlspecialchars($row['kailan']);
                $complaint_paano = htmlspecialchars($row['paano']);
                $complaint_bakit = htmlspecialchars($row['bakit']);
                $complaint_description = htmlspecialchars($row['complaints']);
                $complaint_category = htmlspecialchars($row['complaints_category']);
                $category = htmlspecialchars($row['complaints_category']);

                echo "<tr>";
                echo "<td class='align-middle'>{$row_number}</td>";
                echo "<td style='text-align: left; vertical-align: middle;'>{$date_filed }</td>"; 
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_name}</td>";
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_barangay }</td>";
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_purok }</td>";
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_ano }</td>"; 
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_barangay_saan }</td>"; 
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_kailan }</td>"; 
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_paano }</td>"; 
                echo "<td style='text-align: left; vertical-align: middle;'>{$complaint_bakit }</td>";
                echo "<td class='category'>{$category}</td>";

                echo "<td '>
                        <button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$complaint_id})'>View Details</button>
                      </td>";
                echo "</tr>";

                $row_number++;
            }
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='4'>Error fetching PNP complaints logs: " . $e->getMessage() . "</td></tr>";
    }
}

// Count the total number of complaints for pagination
$stmt = $pdo->prepare("
    SELECT COUNT(*) AS total 
    FROM tbl_complaints c
    LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
    WHERE c.responds = 'pnp'
    AND (c.complaint_name LIKE ? OR c.barangay_saan LIKE ?)
");
$search_query_like = '%' . $search_query . '%';
$stmt->execute([$search_query_like, $search_query_like]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_complaints = $row['total'];

// Calculate the total number of pages
$total_pages = ceil($total_complaints / $results_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Complaints Logs</title>
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

    <!-- Page Content -->
    <center><div class="content">
  
        <h2 class="mt-3 mb-4">Barangay Complaints History</h2>

        <form method="GET">
    <label class="form-label">Filter by Barangay:</label>
    <select id="barangayDropdown" name="barangay" onchange="this.form.submit()">
        <option value="">All</option>
        <?php
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

    


        <div class="table">
            <table class="table table-striped table-bordered">
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
                        displayComplaintDetails($pdo, $search_query, $start_from, $results_per_page, $barangay_filter, $from_date, $to_date, $category_filter);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
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

    

    <div class="modal fade" id="hearingHistoryModal" tabindex="-1" aria-labelledby="hearingHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hearingHistoryModalLabel">Hearing History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="hearingHistoryTableBody">
                        <!-- Hearing history rows will be populated here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
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

    <!-- Modal for Viewing Complaint Details -->
    <div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="complaintDetails">Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="../scripts/script.js"></script>

    <!-- JavaScript to handle modal content dynamically -->
    <script>




document.getElementById('barangayFilter').addEventListener('change', filterTable);
document.getElementById('categoryFilter').addEventListener('change', filterTable);
document.getElementById('dateFrom').addEventListener('change', filterTable);
document.getElementById('dateTo').addEventListener('change', filterTable);

function filterTable() {
    const barangayFilter = document.getElementById('barangayFilter').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    const rows = document.querySelectorAll('#complaintsTable tbody tr');
    let visibleRowCount = 0; // Track the number of visible rows

    // Remove "No record found" row if it exists before filtering
    let noRecordRow = document.querySelector('#noRecordRow');
    if (noRecordRow) {
        noRecordRow.remove();
    }

    rows.forEach(row => {
        const barangay = row.querySelector('.barangay').textContent.toLowerCase();
        const dateFiled = row.querySelector('td:nth-child(3)').textContent;

        // Check if the row matches the date range, barangay, and category filters
        let dateMatch = true;
        if (dateFrom && dateTo) {
            const filedDate = new Date(dateFiled);
            const fromDate = new Date(dateFrom);
            const toDate = new Date(dateTo);
            dateMatch = filedDate >= fromDate && filedDate <= toDate;
        }

        // Apply filters
        if ((barangayFilter === "" || barangay.includes(barangayFilter)) &&
           
            dateMatch) {
            row.style.display = '';
            visibleRowCount++; // Increment the visible row count
        } else {
            row.style.display = 'none';
        }
    });

    // Check if any row is visible, otherwise display the "No record found" message
    const tableBody = document.querySelector('#complaintsTable tbody');
    if (visibleRowCount === 0) {
        noRecordRow = document.createElement('tr');
        noRecordRow.id = 'noRecordRow';
        noRecordRow.innerHTML = '<td colspan="6" class="text-center">No record found</td>';
        tableBody.appendChild(noRecordRow);
    }
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


        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    
     
        function loadComplaintDetails(complaintId) {
            let url = `pnpdetails.php?id=${complaintId}`;

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('complaintDetails').innerHTML = data;
                    // Show the modal
                    var complaintModal = new bootstrap.Modal(document.getElementById('viewComplaintModal'));
                    complaintModal.show();
                })
                .catch(error => {
                    console.error('Error fetching complaint details:', error);
                    document.getElementById('complaintDetails').innerHTML = "Error loading details.";
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
