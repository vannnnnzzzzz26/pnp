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
$pic_data = $_SESSION['pic_data'] ?? '';

// Get filters from GET request
$year = isset($_GET['year']) ? intval($_GET['year']) : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : ''; // Change to from_date
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : ''; // Change to to_date

// Function to fetch dashboard data
function fetchDashboardData($pdo, $year, $from_date, $to_date) {
    try {
        $dateConditions = [];
        $paramsTotal = [];
        $paramsFiledCourt = [];
        $paramsSettledBarangay = [];
        $paramsRejected = [];

        if ($year) {
            $dateConditions[] = "YEAR(c.date_filed) = ?";
            $paramsTotal[] = $year;
            $paramsFiledCourt[] = $year;
            $paramsSettledBarangay[] = $year;
            $paramsRejected[] = $year;
        }
        
        if ($from_date && $to_date) {
            $dateConditions[] = "c.date_filed BETWEEN ? AND ?";
            $paramsTotal[] = $from_date;
            $paramsTotal[] = $to_date;
            $paramsFiledCourt[] = $from_date;
            $paramsFiledCourt[] = $to_date;
            $paramsSettledBarangay[] = $from_date;
            $paramsSettledBarangay[] = $to_date;
            $paramsRejected[] = $from_date;
            $paramsRejected[] = $to_date;
        } elseif ($from_date) {
            $dateConditions[] = "c.date_filed >= ?";
            $paramsTotal[] = $from_date;
            $paramsFiledCourt[] = $from_date;
            $paramsSettledBarangay[] = $from_date;
            $paramsRejected[] = $from_date;
        } elseif ($to_date) {
            $dateConditions[] = "c.date_filed <= ?";
            $paramsTotal[] = $to_date;
            $paramsFiledCourt[] = $to_date;
            $paramsSettledBarangay[] = $to_date;
            $paramsRejected[] = $to_date;
        }

        $dateSql = $dateConditions ? implode(' AND ', $dateConditions) : '';

        // Fetch total complaints
        $whereSql = $dateSql ? 'WHERE ' . $dateSql : '';
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total_complaints FROM tbl_complaints c $whereSql");
        $stmtTotal->execute($paramsTotal);
        $totalComplaints = $stmtTotal->fetchColumn();

        // Fetch Filed in the Court
        $additionalWhere = $dateSql ? ' AND ' . $dateSql : '';
        $stmtFiledCourt = $pdo->prepare("SELECT COUNT(*) AS filed_in_court FROM tbl_complaints c WHERE c.status = 'Filed in the Court' AND c.responds = 'pnp' $additionalWhere");
        $stmtFiledCourt->execute($paramsFiledCourt);
        $filedInCourt = $stmtFiledCourt->fetchColumn();

        // Fetch settled in Barangay
        $stmtSettledBarangay = $pdo->prepare("SELECT COUNT(*) AS settled_in_barangay FROM tbl_complaints c WHERE c.status = 'settled_in_barangay' AND c.responds = 'barangay' $additionalWhere");
        $stmtSettledBarangay->execute($paramsSettledBarangay);
        $settledInBarangay = $stmtSettledBarangay->fetchColumn();

        $stmtRejected = $pdo->prepare("SELECT COUNT(*) AS rejected FROM tbl_complaints c WHERE c.status = 'rejected' $additionalWhere");
        $stmtRejected->execute($paramsRejected);
        $rejected = $stmtRejected->fetchColumn();

        return [
            'totalComplaints' => $totalComplaints,
            'filedInCourt' => $filedInCourt,
            'settledInBarangay' => $settledInBarangay,
            'rejected' => $rejected
        ];
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$data = fetchDashboardData($pdo, $year, $from_date, $to_date);

// Fetch complaints by barangay data
function fetchComplaintsByBarangay($pdo, $year, $from_date, $to_date) {
    try {
        $whereClauses = [];
        $params = [];

        // Build the WHERE clauses based on the provided parameters
        if ($year) {
            $whereClauses[] = "YEAR(c.date_filed) = ?";
            $params[] = $year;
        }

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

        // Create the WHERE SQL condition
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

$barangayData = fetchComplaintsByBarangay($pdo, $year, $from_date, $to_date);

// Fetch gender data
function fetchPurokData($pdo, $year, $from_date, $to_date) {
    try {
        $whereClauses = [];
        $params = [];

        if ($year) {
            $whereClauses[] = "YEAR(c.date_filed) = ?";
            $params[] = $year;
        }

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

        $whereSql = $whereClauses ? 'AND ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT u.purok, COUNT(u.user_id) AS purok_count
            FROM tbl_complaints c
            JOIN tbl_users u ON c.user_id = u.user_id
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
$purokData = fetchPurokData($pdo, $year, $from_date, $to_date);

// Fetch complaint categories data
function fetchComplaintCategoriesData($pdo, $year, $from_date, $to_date) {
    try {
        $whereClauses = [];
        $params = [];

        if ($year) {
            $whereClauses[] = "YEAR(c.date_filed) = ?";
            $params[] = $year;
        }

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
            SELECT cc.complaints_category, COUNT(c.complaints_id) AS category_count
            FROM tbl_complaints c
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            $whereSql
            GROUP BY cc.complaints_category
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$categoryData = fetchComplaintCategoriesData($pdo, $year, $from_date, $to_date);
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
            background-color: whitesmoke;
            
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

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
include '../includes/edit-profile.php';

?>


<center><div class="content">
     <center> <h1>Dashboard</h1></center>
     <div class="row">
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-file-alt" style="font-size:50px;color: green;"></i>
         <h2><?php echo htmlspecialchars($data['totalComplaints']); ?></h2>
         <p>Total Complaints</p>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-gavel" style="font-size:50px; color: cyan;"></i>
         
         <h2><?php echo htmlspecialchars($data['filedInCourt']); ?></h2>
         <p>Filed in the Court</p>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-check-circle" style="font-size:50px;color: blue;"></i>
         <h2><?php echo htmlspecialchars($data['settledInBarangay']); ?></h2>
         <p>Settled in Barangay</p>
      </div>
   </div>
   <div class="col-md-3">
   <div class="card">
    <i class="fas fa-times-circle" style="font-size:50px; color: red;"></i>
    <h2><?php echo htmlspecialchars($data['rejected']); ?></h2>
    <p>Rejected</p>
</div>

   </div>
</div>


     <div class="container mt-4">
    <!-- Filter Form -->


    <!-- Complaints by Barangay Chart -->
    <div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2>Complaints by Barangay</h2>
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
      
</div>


    
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 30rem;">
                        
                    <canvas id="barangayChartSmall"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender and Category Charts -->
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
            <h2>Top 10  Most Complaints</h2>

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
</center>

<div id="notificationCard" class="card d-none" style="position: absolute; top: 50px; right: 10px; width: 300px; z-index: 1050;"></div>

    <script>

document.addEventListener('DOMContentLoaded', function() {
    var ctxBarangay = document.getElementById('barangayChartSmall').getContext('2d');
    
    // Data from PHP
    var barangayNames = <?php echo json_encode(array_column($barangayData, 'barangay_saan')); ?>;
    var complaintCounts = <?php echo json_encode(array_column($barangayData, 'complaint_count')); ?>;

    // Find the maximum number of complaints
    var maxComplaints = Math.max(...complaintCounts);

    // Calculate the percentage for each barangay
    var percentages = complaintCounts.map(count => (count / maxComplaints * 100).toFixed(2));

    // Chart data
    var barangayChart = new Chart(ctxBarangay, {
        type: 'bar', // Line chart
        data: {
            labels: barangayNames,
            datasets: [{
                label: '', // Removed the dataset label
                data: complaintCounts,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 4,
                barBorderRadius: 10,// This adds rounded corners to the bars

                fill: false // Do not fill under the line
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true // Show the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            var index = tooltipItem.dataIndex;
                            return `Barangay: ${barangayNames[index]}, Complaints: ${complaintCounts[index]}, Percentage: ${percentages[index]}%`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: false // Hide the title
                    },
                    ticks: {
                        display: true // Hide the barangay names on the x-axis
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Complaints'
                    }
                }
            }
        }
    });




    // Gender Chart
    var ctxPurok = document.getElementById('purokChart').getContext('2d');
var purokDataValues = <?php echo json_encode(array_column($purokData, 'purok_count')); ?>;
var purokDataLabels = <?php echo json_encode(array_column($purokData, 'purok')); ?>;
var totalPurokCount = purokDataValues.reduce((a, b) => a + b, 0); // Total count of purok data

var purokChart = new Chart(ctxPurok, {
    type: 'bar',
    data: {
        labels: purokDataLabels.map((label, index) => `${label} (${((purokDataValues[index] / totalPurokCount) * 100).toFixed(1)}%)`), // Add percentages to labels
        datasets: [{
            data: purokDataValues,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            borderColor: '#fff',
            borderWidth: 1,
            barBorderRadius:10

        }]
    },
    options: {
        responsive: true,
        cutout: '50%',
        plugins: {
            legend: {
                display: false // Hide the legend if needed
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

// Combine labels and values into an array of objects for sorting
var combinedData = categoryDataLabels.map((label, index) => ({
    label: label,
    value: categoryDataValues[index],
}));

// Sort the combined data by value in descending order
combinedData.sort((a, b) => b.value - a.value);

// Take the top 5 categories
var top5Data = combinedData.slice(0, 5);
var top5Labels = top5Data.map(item => item.label);
var top5Values = top5Data.map(item => item.value);

// Create polar area chart with top 5 categories
var categoryChart = new Chart(ctxCategory, {
    type: 'polarArea', // Changed to polar area chart
    data: {
        labels: top5Labels.map((label, index) => `${label} (${((top5Values[index] / totalCategoryCount) * 100).toFixed(1)}%)`), // Add percentages to labels
        datasets: [{
            label: '', // Removed the dataset label
            data: top5Values,
            backgroundColor: [
                '#4e73df', // Blue
                '#1cc88a', // Green
                '#36b9cc', // Light Blue
                '#f6c23e', // Yellow
                '#e74a3b'  // Red
            ],
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false, // Display the legend
                position: 'top' // Position the legend at the top
            }
        },
        scales: {
            r: {
                beginAtZero: true
            }
        }
    }
});

// Find the highest value in top 5 category data
var maxCategoryValue = Math.max(...top5Values);
var maxCategoryIndex = top5Values.indexOf(maxCategoryValue);
document.getElementById('categoryMaxInfo').textContent = `${top5Labels[maxCategoryIndex]}: ${((maxCategoryValue / totalCategoryCount) * 100).toFixed(1)}%`;



var ctxTopCategories = document.getElementById('topCategoriesChart').getContext('2d');
    var sortedCategoryData = <?php echo json_encode($categoryData); ?> 
        .sort((a, b) => b.category_count - a.category_count)
        .slice(0, 10); // ito yung  limit niya 

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
                borderWidth: 3,
                barBorderRadius: 10

                

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@latest/dist/chartjs-plugin-datalabels.min.js"></script>

    <script src="../scripts/script.js"></script>
    

</body>
</html>
