<?php
session_start();
include '../connection/dbconn.php';
include '../includes/bypass.php';

// Fetch user information from session
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$cp_number = $_SESSION['cp_number'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$barangay_saan = $_SESSION['barangay_saan'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Get filters from GET request
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Function to fetch dashboard data
function fetchDashboardData($pdo, $from_date, $to_date, $barangay_name) {
    try {
        $whereClauses = ["c.barangay_saan = ?"];
        $params = [$barangay_name];

        // Add date range filter
        if ($from_date && $to_date) {
            $whereClauses[] = "c.date_filed BETWEEN ? AND ?";
            $params[] = $from_date;
            $params[] = $to_date;
        } elseif ($from_date) {
            $whereClauses[] = "c.date_filed >= ?";
            $params[] = $from_date;
        } elseif ($to_date) {
            $whereClauses[] = "c.date_filed <= ?";
            $params[] = $to_date;
        }

        $whereSql = $whereClauses ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT 
                SUM(CASE WHEN c.status = 'Rejected' THEN 1 ELSE 0 END) AS rejected,
                SUM(CASE WHEN c.status = 'settled_in_barangay' THEN 1 ELSE 0 END) AS settled_in_barangay,
                SUM(CASE WHEN c.status = 'Approved' THEN 1 ELSE 0 END) AS Approved,
                SUM(CASE WHEN c.status = 'inprogress' THEN 1 ELSE 0 END) AS inprogress,
                SUM(CASE WHEN c.status = 'pnp' THEN 1 ELSE 0 END) AS pnp
            FROM tbl_complaints c
            $whereSql
        ");
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Call the function with the correct parameter order
$data = fetchDashboardData($pdo, $from_date, $to_date, $barangay_name);

// Fetch complaints by barangay data
function fetchComplaintsByBarangay($pdo, $from_date, $to_date) {
    try {
        $whereClauses = [];
        $params = [];

        // Add date range filter
        if ($from_date && $to_date) {
            $whereClauses[] = "c.date_filed BETWEEN ? AND ?";
            $params[] = $from_date;
            $params[] = $to_date;
        } elseif ($from_date) {
            $whereClauses[] = "c.date_filed >= ?";
            $params[] = $from_date;
        } elseif ($to_date) {
            $whereClauses[] = "c.date_filed <= ?";
            $params[] = $to_date;
        }

        $whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT c.barangay_saan, COUNT(c.complaints_id) AS complaint_count
            FROM tbl_complaints c
            $whereSql
            GROUP BY c.barangay_saan
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Example usage
$barangayData = fetchComplaintsByBarangay($pdo, $from_date, $to_date);

// Fetch other data (gender, categories, etc.) following the same pattern

// Function to fetch purok data
function fetchPurokData($pdo, $from_date, $to_date, $barangay_name) {
    try {
        $whereClauses = ["ub.barangay_name = ?"];
        $params = [$barangay_name];

        // Add date range filter
        if ($from_date && $to_date) {
            $whereClauses[] = "c.date_filed BETWEEN ? AND ?";
            $params[] = $from_date;
            $params[] = $to_date;
        } elseif ($from_date) {
            $whereClauses[] = "c.date_filed >= ?";
            $params[] = $from_date;
        } elseif ($to_date) {
            $whereClauses[] = "c.date_filed <= ?";
            $params[] = $to_date;
        }

        $whereSql = $whereClauses ? ' AND ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT u.purok, COUNT(u.user_id) AS purok_count
            FROM tbl_complaints c
            JOIN tbl_users u ON c.user_id = u.user_id
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE 1=1 $whereSql
            GROUP BY u.purok
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Usage
$purokData = fetchPurokData($pdo, $from_date, $to_date, $barangay_name);

// Function to fetch complaint categories data
function fetchComplaintCategoriesData($pdo, $from_date, $to_date, $barangay_name) {
    try {
        $whereClauses = ["c.barangay_saan = ?"];
        $params = [$barangay_name];

        // Add date range filter
        if ($from_date && $to_date) {
            $whereClauses[] = "c.date_filed BETWEEN ? AND ?";
            $params[] = $from_date;
            $params[] = $to_date;
        } elseif ($from_date) {
            $whereClauses[] = "c.date_filed >= ?";
            $params[] = $from_date;
        } elseif ($to_date) {
            $whereClauses[] = "c.date_filed <= ?";
            $params[] = $to_date;
        }

        $whereSql = $whereClauses ? ' AND ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT cc.complaints_category, COUNT(c.complaints_id) AS category_count
            FROM tbl_complaints c
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            WHERE 1=1 $whereSql
            GROUP BY cc.complaints_category
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$categoryData = fetchComplaintCategoriesData($pdo, $from_date, $to_date, $barangay_name);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="../styles/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .card h2 {
            margin: 0;
            font-size: 2em;
            color: #333;
        }
        .card p {
            margin: 10px 0 0;
            font-size: 1.2em;
            color: #666;
        }
        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .small-card {
            width: 300px;
            height: 400px;
            margin: 20px;
        }

        .card.smalls-card {
            width: 600px; /* Increase width */
            height: 400px; /* Increase height */
            margin: 20px auto; /* Center the card */
        }
        .chart-container {
            width: 100%;
            height: 300px;
            margin: 0 auto;
        }
        .pie-chart-container {
            display: flex;
            justify-content: space-around; /* Adjust space between cards */
            flex-wrap: wrap;
            margin: 20px 0; /* Add margin at the top and bottom */
        }

        .charts-container {
            width: 100%;
            height: 500px;
            margin: 0 auto;
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
   

    </style>
</head>
<body>

<?php 

include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/edit-profile.php';
?>

<div class="content">
    <div class="container">
        <h1>Dashboard</h1>
        <div class="card-container">
          
            <div class="card">
            <i class="bi bi-skip-forward-circle-fill" style="font-size:40px; color: cyan;"></i>
                <h2><?php echo htmlspecialchars($data['pnp'] ?? 0); ?></h2>
              
                <p>Forwarded Cases</p>
                </a>
            </div>
            <div class="card">

            <i class="bi bi-x-octagon" style="font-size:40px; color: red;"></i>
                <h2><?php echo htmlspecialchars($data['rejected'] ?? 0); ?></h2>
                <a href="barangaylogs2.php" style="text-decoration: none; color: inherit;">
                <p>Rejected Complaints</p>
                </a>
            </div>
            <div class="card">
            <i class="bi bi-person-check" style="font-size:40px;color: blue;"></i>
                <h2><?php echo htmlspecialchars($data['settled_in_barangay'] ?? 0); ?></h2>
                <a href="barangaylogs.php" style="text-decoration: none; color: inherit;">
                <p>Settled in Barangay</p>
                </a>
            </div>
   
        <div class="card">
            <i class="bi bi-calendar-check" style="font-size:40px;color: yellow;"></i>
                <h2><?php echo htmlspecialchars($data['Approved'] ?? 0); ?></h2>
                <a href="barangay-responder.php" style="text-decoration: none; color: inherit;">
                <p>Approve Complaints </p>  </a>
            </div>
            
        <div class="card">
            <i class="bi bi-clock-history" style="font-size:40px;color: orange;"></i>
                <h2><?php echo htmlspecialchars($data['inprogress'] ?? 0); ?></h2>
                <a href="manage-complaints.php" style="text-decoration: none; color: inherit;">
                <p>Pending to Approve</p></a>
            </div>
        </div>
       
<div class="container mt-4">


<div class="row mb-4">
  
       
                <div class="container mt-4">
                    <form method="get" action="">
                        <div class="row justify-content-center">
                            <!-- Month From Filter -->
                            <div class="col-md-3 mb-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>" onchange="this.form.submit()">
                            </div>

                            <!-- Month To Filter -->
                            <div class="col-md-3 mb-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>" onchange="this.form.submit()">
                            </div>
                        </div>
                    </form>
                </div>
           

    
        <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Purok</h2>
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                        
                        <canvas id="purokChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Highest Purok Count:</h4>
                        <p class="" id="purokMaxInfo"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Complaint Categories</h2>
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Highest Complaints  Count:</h4>
                        <p id="categoryMaxInfo"></p>
                    </div>
                </div>
            </div>
        </div>

     
    <div class="card">
        <div class="card-body">
            <h2>Top 5 Complaint Categories</h2>

            <div class="chart-container d-flex justify-content-center align-items-center" style="height: 20rem;">                <canvas id="topCategoriesChart"></canvas>
            </div>
            <div class="analytics-info mt-3">
       
            </div>
        </div>
    </div>
</div>
    </div>
</div>






       
    </div>
</div>

<div id="notificationCard" class="card d-none" style="position: absolute; top: 50px; right: 10px; width: 300px; z-index: 1050;"></div>


<script>

document.addEventListener('DOMContentLoaded', function () {
    var profilePic = document.querySelector('.profile');
    var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

    profilePic.addEventListener('click', function () {
        editProfileModal.show();
    });
});

document.addEventListener('DOMContentLoaded', function() {
 
   // Purok Chart
var ctxPurok = document.getElementById('purokChart').getContext('2d');
var purokDataValues = <?php echo json_encode(array_column($purokData, 'purok_count')); ?>;
var purokDataLabels = <?php echo json_encode(array_column($purokData, 'purok')); ?>;
var totalPurokCount = purokDataValues.reduce((a, b) => a + b, 0); // Total count of puroks

var purokChart = new Chart(ctxPurok, {
    type: 'doughnut',
    data: {
        labels: purokDataLabels.map((label, index) => `${label} (${((purokDataValues[index] / totalPurokCount) * 100).toFixed(1)}%)`), // Add percentages to labels
        datasets: [{
            data: purokDataValues,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            borderColor: '#fff',
            borderWidth: 10
        }]
    },
    options: {
        responsive: true,
        cutout: '50%',
        plugins: {
            legend: {
                display: true // Hide the legend if needed
            }
        }
    }
});

// Find the highest value in purok data
var maxPurokValue = Math.max(...purokDataValues);
var maxPurokIndex = purokDataValues.indexOf(maxPurokValue);
document.getElementById('purokMaxInfo').textContent = `${purokDataLabels[maxPurokIndex]}: ${((maxPurokValue / totalPurokCount) * 100).toFixed(1)}%`;

    // Most Complaints Report (Category Chart)
    var ctxCategory = document.getElementById('categoryChart').getContext('2d');
    var categoryDataValues = <?php echo json_encode(array_column($categoryData, 'category_count')); ?>;
    var categoryDataLabels = <?php echo json_encode(array_column($categoryData, 'complaints_category')); ?>;
    var totalCategoryCount = categoryDataValues.reduce((a, b) => a + b, 0); // Total count of complaints in categories

    var categoryChart = new Chart(ctxCategory, {
        type: 'polarArea', // Changed to pie chart
        data: {
            labels: categoryDataLabels.map((label, index) => `${label} (${((categoryDataValues[index] / totalCategoryCount) * 100).toFixed(1)}%)`), // Add percentages to labels
            datasets: [{
                label: '', // Removed the dataset label
                data: categoryDataValues,
                backgroundColor: [
                    '#4e73df', // Blue
                    '#1cc88a', // Green
                    '#36b9cc', // Light Blue
                    '#f6c23e', // Yellow
                    '#e74a3b', // Red
                    '#5a5c69'  // Gray
                ],
                borderColor: '#fff',
                borderWidth: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Display the legend
                    position: 'top' // Position the legend at the top
                }
            }
        }
    });

    // Find the highest value in category data
    var maxCategoryValue = Math.max(...categoryDataValues);
    var maxCategoryIndex = categoryDataValues.indexOf(maxCategoryValue);
   
    document.getElementById('categoryMaxInfo').textContent = `${categoryDataLabels[maxCategoryIndex]}: ${((maxCategoryValue / totalCategoryCount) * 100).toFixed(1)}%`;



    var ctxTopCategories = document.getElementById('topCategoriesChart').getContext('2d');
    var sortedCategoryData = <?php echo json_encode($categoryData); ?> 
        .sort((a, b) => b.category_count - a.category_count)
        .slice(0, 5); // ito yung  limit niya 

    var topCategoryLabels = sortedCategoryData.map(item => item.complaints_category);
    var topCategoryCounts = sortedCategoryData.map(item => item.category_count);
    var totalTopCategoryCount = topCategoryCounts.reduce((a, b) => a + b, 0);

    // Horizontal Bar Chart  dito  na didisplay yung top 5
    var topCategoriesChart = new Chart(ctxTopCategories, {
        type: 'bar',
        data: {
            labels: topCategoryLabels.map((label, index) => 
                `${label} (${((topCategoryCounts[index] / totalTopCategoryCount) * 100).toFixed(1)}%)`),
            datasets: [{
                data: topCategoryCounts,
                backgroundColor: [
                    '#4e73df', // Blue
                    '#1cc88a', // Green
                    '#36b9cc', // Light Blue
                    '#f6c23e', // Yellow
                    '#e74a3b'  // Red
                ],
                borderColor: '#fff',
                borderWidth: 10,
                barBorderRadius: 15

                

            }]
        },
        options: {
            indexAxis: 'y', 
            responsive: true,
            plugins: {
                legend: {
                    display: false 
                }
            },
            scales: {
                x: {
                    title: {
                        display: false,
                        text: 'Number of Complaints'
                    },
                    beginAtZero: true
                },
                y: {
                    title: {
                        display: false, 
                    }
                }
            }
        }
    });

  
    var maxTopCategoryValue = Math.max(...topCategoryCounts);
    var maxTopCategoryIndex = topCategoryCounts.indexOf(maxTopCategoryValue);
    document.getElementById('topCategoryInfo').textContent = `${topCategoryLabels[maxTopCategoryIndex]}: ${((maxTopCategoryValue / totalTopCategoryCount) * 100).toFixed(1)}%`;
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
            window.location.href = " ../reg/login.php?logout=<?php echo $_SESSION['user_id']; ?>";
        }
    });
}

</script>
<script src="../scripts/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@latest/dist/chartjs-plugin-datalabels.min.js"></script>




</body>
</html>