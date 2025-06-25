<?php
// Start the session
session_start();
require_once( 'vendor/autoload.php' );

// Include the database connection file
include '../connection/dbconn.php';

// Initialize PDO if not already done
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to send SMS notification
function sendSMS($message, $recipient) {
    $ch = curl_init();
    $parameters = array(
        'apikey' => '', // Your API KEY
        'number' => $recipient,
        'message' => $message,
        'sendername' => 'Copwatch'
    );
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// Handle POST requests to update or insert hearing details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'] ?? null;
    $hearing_date = $_POST['hearing_date'] ?? null;
    $hearing_time = $_POST['hearing_time'] ?? null; // 24-hour format input
    $hearing_type = $_POST['hearing_type'] ?? null;
    $hearing_status = $_POST['hearing_status'] ?? null;
    
    if (!$complaint_id || !$hearing_type) {
        echo "Complaint ID and Hearing Type are required.";
        exit;
    }

    // Fetch recipient details dynamically from tbl_users
    $stmt = $pdo->prepare("SELECT u.cp_number, u.first_name, u.last_name FROM tbl_complaints c JOIN tbl_users u ON c.user_id = u.user_id WHERE c.complaints_id = ?");
    $stmt->execute([$complaint_id]);
    $recipientData = $stmt->fetch(PDO::FETCH_ASSOC);
    $recipient_number = $recipientData['cp_number'] ?? null;
    $complaint_name = $recipientData['first_name'] . ' ' . $recipientData['last_name'] ?? "Complainant";

    if (!$recipient_number) {
        echo "Recipient contact number not found.";
        exit;
    }

    $formatted_hearing_time = $hearing_time ? date("h:i:s A", strtotime($hearing_time)) : null;

    try {
        // Check if the hearing record already exists
        $stmt = $pdo->prepare("SELECT id FROM tbl_hearing_history WHERE complaints_id = ? AND hearing_type = ?");
        $stmt->execute([$complaint_id, $hearing_type]);
        $existingHearing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingHearing) {
            // Update existing hearing record
            $stmt = $pdo->prepare("UPDATE tbl_hearing_history SET hearing_date = ?, hearing_time = ?, hearing_status = ? WHERE id = ?");
            $stmt->execute([$hearing_date, $formatted_hearing_time, $hearing_status, $existingHearing['id']]);
            echo "Hearing details updated successfully.";
            $smsMessage = "Hello $complaint_name, your hearing scheduled on $hearing_date at $formatted_hearing_time has been updated. Status: $hearing_status.";
        } else {
            // Insert new hearing record
            $stmt = $pdo->prepare("INSERT INTO tbl_hearing_history (complaints_id, hearing_date, hearing_time, hearing_type, hearing_status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$complaint_id, $hearing_date, $formatted_hearing_time, $hearing_type, $hearing_status]);
            echo "Hearing details recorded successfully.";
            $smsMessage = "Hello $complaint_name, a new hearing has been scheduled on $hearing_date at $formatted_hearing_time. Status: $hearing_status.";
        }

        // Send SMS notification
        sendSMS($smsMessage, $recipient_number);
    } catch (PDOException $e) {
        echo "Error recording hearing details: " . $e->getMessage();
    }
}

// Handle GET requests to fetch hearing history
if (isset($_GET['complaint_id'])) {
    $complaint_id = $_GET['complaint_id'];

    try {
        $stmt = $pdo->prepare("SELECT hearing_date, hearing_time, hearing_type, hearing_status FROM tbl_hearing_history WHERE complaints_id = ? ORDER BY hearing_date DESC, hearing_time DESC");
        $stmt->execute([$complaint_id]);
        $hearings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($hearings);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
