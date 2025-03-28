<!-- Complaint Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div style="display: flex; flex-wrap: wrap;">
    <!-- First Column -->
    <div style="flex: 1; min-width: 300px; padding-right: 20px;">
        <label><strong>Name:</strong></label>
        <textarea id="modal-name" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Ano (What):</strong></label>
        <textarea id="modal-ano" class="form-control mb-2" rows="2"></textarea>

        <label><strong>Saan (Where):</strong></label>
        <textarea id="modal-saan" class="form-control mb-2" rows="2"></textarea>

        <label><strong>Kailan (When):</strong></label>
        <textarea id="modal-kailan" class="form-control mb-2" rows="2"></textarea>

        <label><strong>Paano (How):</strong></label>
        <textarea id="modal-paano" class="form-control mb-2" rows="2"></textarea>

        <label><strong>Bakit (Why):</strong></label>
        <textarea id="modal-bakit" class="form-control mb-2" rows="2"></textarea>

        <label><strong>Description:</strong></label>
        <textarea id="modal-description" class="form-control mb-2" rows="3"></textarea>

        <label><strong>Category:</strong></label>
        <textarea id="modal-category" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Barangay:</strong></label>
        <textarea id="modal-barangay" class="form-control mb-2" rows="1"></textarea>
    </div>

    <!-- Second Column -->
    <div style="flex: 1; min-width: 300px; padding-left: 20px;">
        <label><strong>Contact:</strong></label>
        <textarea id="modal-contact" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Person:</strong></label>
        <textarea id="modal-person" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Gender:</strong></label>
        <textarea id="modal-gender" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Birth Place:</strong></label>
        <textarea id="modal-birth_place" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Age:</strong></label>
        <textarea id="modal-age" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Education:</strong></label>
        <textarea id="modal-education" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Civil Status:</strong></label>
        <textarea id="modal-civil_status" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Date Filed:</strong></label>
        <textarea id="modal-date_filed" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Status:</strong></label>
        <textarea id="modal-status" class="form-control mb-2" rows="1"></textarea>

        <label><strong>Nationality:</strong></label>
        <textarea id="modal-nationality" class="form-control mb-2" rows="1"></textarea>
    </div>
</div>


                <!-- Certificate Section -->
               <!-- Certificate Section -->
<div class="text-center mt-3" id="modalCertificateSection">
    <p><strong>Certificate of File Action:</strong></p>
    <img id="modal-cert_path" src="" alt="No Certificate Uploaded"
        style="max-width: 100px; cursor: pointer; display: none;">
</div>


                <!-- Hearing History Section -->
                <div id="modalHearingHistorySection"></div>

                <!-- Evidence Section -->
                <div id="modalEvidenceSection" style="display: none;">
                    <p><strong>Evidence:</strong></p>
                    <ul id="modalEvidenceList"></ul>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="moveToPnpBtn">Move to PNP</button>
                <button type="button" class="btn btn-secondary" id="settleInBarangayBtn">Settle in Barangay</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewsComplaintModal" id="setHearingBtn">
                    Set Hearing
                </button>
            </div>
        </div>
    </div>
</div>

<!-- File Viewer Modal -->
<!-- File Viewer Modal for Image/PDF -->
<div id="fileViewerModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Viewer</h5>
                <button type="button" class="btn-close" onclick="closeFileViewer()" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="fileViewerContent" style="max-width: 100%; max-height: 80vh; overflow: auto;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Complaint Certificate Upload Form -->
<?php
require_once '../connection/dbconn.php';

// Fetch approved complaints for the dropdown
$stmt = $pdo->prepare("SELECT complaint_name FROM tbl_complaints WHERE status = 'approved'");
$stmt->execute();
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="upload.php" method="post" enctype="multipart/form-data" class="mt-3">
    <h5>  Add  certificate  of  file  action</h5>
    <label for="complaint_name">Select complainant:</label>
    <select name="complaint_name" id="complaint_name" class="form-select mb-2" required>
        <option value="">-- Select complainant --</option>
        <?php foreach ($complaints as $complaint): ?>
            <option value="<?= htmlspecialchars($complaint['complaint_name']); ?>">
                <?= htmlspecialchars($complaint['complaint_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="cert_file">Upload Certificate:</label>
    <input type="file" name="cert_file" id="cert_file" class="form-control mb-2" required accept=".pdf, .jpg, .jpeg, .png">

    <button type="submit" class="btn btn-success">Upload</button>
</form>
