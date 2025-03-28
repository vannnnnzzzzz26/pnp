<?php
ob_start(); // Prevent output errors

require_once '../connection/dbconn.php';
require_once 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Retrieve and sanitize form data
        $barangay = htmlspecialchars($_POST['barangay']);
        $municipality = htmlspecialchars($_POST['municipality']);
        $province = htmlspecialchars($_POST['province']);
        $complaint_person = htmlspecialchars($_POST['complaint_person']);
        $complainant = htmlspecialchars($_POST['complainant']);
        $respondent = htmlspecialchars($_POST['respondent']);
        $secretary = htmlspecialchars($_POST['secretary']);
        $captain = htmlspecialchars($_POST['captain']);
        $certificate_text = htmlspecialchars($_POST['certificate_text']);
        $date = $_POST['date'];
     

        // Handle logo uploads
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $leftLogo = null;
        $rightLogo = null;

        if (!empty($_FILES['left_logo']['name'])) {
            $leftLogoPath = $uploadDir . basename($_FILES['left_logo']['name']);
            move_uploaded_file($_FILES['left_logo']['tmp_name'], $leftLogoPath);
            $leftLogo = $leftLogoPath;
        }

        if (!empty($_FILES['right_logo']['name'])) {
            $rightLogoPath = $uploadDir . basename($_FILES['right_logo']['name']);
            move_uploaded_file($_FILES['right_logo']['tmp_name'], $rightLogoPath);
            $rightLogo = $rightLogoPath;
        }

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO certificates 
            (barangay, municipality, province, complaint_person, complainant, respondent, date_created, certificate_text, secretary, captain, left_logo, right_logo) 
            VALUES (:barangay, :municipality, :province, :complaint_person, :complainant, :respondent, :date_created, :certificate_text, :secretary, :captain, :left_logo, :right_logo)");
        
        $stmt->execute([
            ':barangay' => $barangay,
            ':municipality' => $municipality,
            ':province' => $province,
            ':complaint_person' => $complaint_person,
            ':complainant' => $complainant,
            ':respondent' => $respondent,
            ':date_created' => $date,
            ':certificate_text' => $certificate_text,
            ':secretary' => $secretary,
            ':captain' => $captain,
            ':left_logo' => $leftLogo ?? null,
            ':right_logo' => $rightLogo ?? null
        ]);

        // Generate PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Barangay Office');
        $pdf->SetTitle('Certificate to File Action');
        $pdf->SetMargins(20, 20, 20);
        $pdf->AddPage();
        
        // Logo Positions (Ensure proper spacing)
        $logoWidth = 30;
        $logoHeight = 30;
        
        if ($leftLogo) {
            $pdf->Image($leftLogo, 20, 15, $logoWidth, $logoHeight); // Move left logo down
        }
        if ($rightLogo) {
            $pdf->Image($rightLogo, 160, 15, $logoWidth, $logoHeight); // Move right logo down
        }
        
        // Set font for header text
        $pdf->SetFont('times', 'B', 12);
        
        // Adjust text block width and positioning
        $pdf->SetXY(50, 12);
        $pdf->MultiCell(110, 5, 
            "Republic of the Philippines\nProvince of $province\nMunicipality of $municipality\nBarangay $barangay", 
            0, 'C'
        );
        
      
        $pdf->Ln(10);
        
        $pdf->SetFont('times', 'B', 14);
        $pdf->Cell(0, 8, 'OFFICE OF THE PUNONG TAGAPAMAYAPA', 0, 1, 'C');
        
        // **Draw a horizontal line below the header (keep this)**
        $pdf->Line(20, $pdf->GetY() + 2, 190, $pdf->GetY() + 2);
        $pdf->Ln(5); // Add space after line
        
        
        // Certificate Body
        $pdf->SetFont('times', '', 12);
// Align Complaint Person (Right) and Complainant (Left)
$pdf->Cell(95, 8, $complainant, 0, 0, 'L'); // Left Aligned
$pdf->Cell(80, 8, $complaint_person, 0, 1, 'R'); // Right Aligned

$pdf->Cell(95, 8, 'Complainant', 0, 0, 'L'); // Label Left
$pdf->Cell(75, 8, 'Complaint Person', 0, 1, 'R'); // Label Right

$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 8, '- against -', 0, 1, 'C'); // Centered

$pdf->Cell(0, 8, $respondent, 0, 1, 'L'); // Respondent Left Aligned
$pdf->Cell(0, 8, 'respondent', 0, 1, 'L'); // Respondent Left Aligned

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 8, 'CERTIFICATE TO FILE ACTION', 0, 1, 'C'); // Centered Title

$pdf->SetFont('times', '', 12);
$pdf->MultiCell(0, 6, $certificate_text, 0, 'J'); // Justified Certificate Text

$pdf->Ln(5);
$pdf->Cell(0, 8, 'Date: ' . date('F d, Y', strtotime($date)), 0, 1, 'L'); // Left Aligned Date

$pdf->Ln(10);

// Signatures
$pdf->Cell(95, 8, $secretary, 0, 0, 'L'); // Left Aligned Secretary
$pdf->Cell(80, 8, $captain, 0, 1, 'R'); // Right Aligned Captain

$pdf->Cell(95, 8, 'Barangay Secretary', 0, 0, 'L'); // Label Left
$pdf->Cell(75, 8, 'Barangay Captain', 0, 1, 'R'); // Label Right

        $pdf->writeHTML($html, true, false, true, false, '');
        ob_end_clean();
        $pdf->Output('Certificate_to_File_Action.pdf', 'D'); // Forces download

        // Redirect to barangay_responder.php after PDF is generated
        header("Location: barangay_responder.php");
        exit();
        
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Certificate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>body{
    background-color: #082759;
}
 </style>
<body>
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Generate Certificate</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Barangay</label>
                        <input type="text" name="barangay" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Municipality</label>
                        <input type="text" name="municipality" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Province</label>
                        <input type="text" name="province" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Complaint Person</label>
                        <input type="text" name="complaint_person" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Complainant</label>
                        <input type="text" name="complainant" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Respondent</label>
                        <input type="text" name="respondent" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Barangay Secretary</label>
                        <input type="text" name="secretary" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Barangay Captain</label>
                        <input type="text" name="captain" class="form-control" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Certificate Text</label>
                        <textarea name="certificate_text" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Upload Left Logo</label>
                        <input type="file" name="left_logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Upload Right Logo</label>
                        <input type="file" name="right_logo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Generate PDF & Save</button>
                </div>
            </form>
            <br>
            <div class="button-container">
            <center>  <button onclick="window.history.back();" class="btn btn-danger">
        <i class="fa fa-arrow-left"></i> Back
    </button></center>
        </div>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
