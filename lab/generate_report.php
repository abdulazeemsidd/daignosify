<?php
session_start();
include __DIR__ . '/../auth/db-config.php';

$appointment_id = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : 0;
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

$info_query = "SELECT p.name AS pat_name, t.test_name 
               FROM patients p, lab_tests t 
               WHERE p.id = $patient_id AND t.test_id = $test_id";
$info_result = mysqli_query($conn, $info_query);
$info_data = mysqli_fetch_assoc($info_result);

if (!$info_data) {
    die("Error: Invalid Report Parameters.");
}


if (isset($_POST['submit_report_btn'])) {
    $min_range = mysqli_real_escape_string($conn, $_POST['min_range']);
    $max_range = mysqli_real_escape_string($conn, $_POST['max_range']);
    $patient_value = mysqli_real_escape_string($conn, $_POST['patient_value']);
    $test_unit = mysqli_real_escape_string($conn, $_POST['test_unit']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

   
    $insert_query = "INSERT INTO lab_reports (appointment_id, patient_id, test_id, min_range, max_range, patient_value, test_unit, remarks) 
                     VALUES ('$appointment_id', '$patient_id', '$test_id', '$min_range', '$max_range', '$patient_value', '$test_unit', '$remarks')";

    if (mysqli_query($conn, $insert_query)) {
       
        mysqli_query($conn, "UPDATE appointments SET status='Completed' WHERE appointment_id='$appointment_id'");
        
        echo "<script>alert('Report Generated and status updated successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Database insertion error.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Report - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .form-card { border: none; border-radius: 15px; box-shadow: 0 4px 25px rgba(0,0,0,0.06); }
        .card-header-gradient { background: linear-gradient(135deg, #005c97, #363795); color: white; border-radius: 15px 15px 0 0 !important; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard</a>
            
            <div class="card form-card">
                <div class="card-header card-header-gradient p-4">
                    <h4 class="fw-bold m-0"><i class="fa-solid fa-square-poll-horizontal me-2"></i> Create Dynamic Lab Report</h4>
                    <p class="m-0 small opacity-75 mt-1">Configure medical ranges and patient parameters</p>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-sm-6">
                            <span class="text-muted small text-uppercase d-block">Patient Name:</span>
                            <strong class="text-dark fs-5"><?php echo htmlspecialchars($info_data['pat_name']); ?></strong>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                            <span class="text-muted small text-uppercase d-block">Test Name:</span>
                            <strong class="text-primary fs-5"><?php echo htmlspecialchars($info_data['test_name']); ?></strong>
                        </div>
                    </div>

                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Minimum Safe Range</label>
                                <input type="number" step="0.01" name="min_range" class="form-control" placeholder="e.g. 12.00" required>
                                <div class="form-text">Minimum standard normal limit.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Maximum Safe Range</label>
                                <input type="number" step="0.01" name="max_range" class="form-control" placeholder="e.g. 17.00" required>
                                <div class="form-text">Maximum standard normal limit.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-danger">Patient Actual Result</label>
                                <input type="number" step="0.01" name="patient_value" class="form-control border-danger" placeholder="e.g. 14.5" required>
                                <div class="form-text text-danger">The actual test value observed.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Measurement Unit</label>
                                <input type="text" name="test_unit" class="form-control" placeholder="e.g. g/dL, mg/dL, mmol/L" required>
                                <div class="form-text">Standard medical metric unit.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Technician Remarks / Observations</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="e.g. Sample processed securely. Patient shows clear normal stats."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button type="submit" name="submit_report_btn" class="btn btn-success btn-lg w-100 fw-bold py-2 shadow-sm">
                            <i class="fa-solid fa-circle-check me-2"></i> Save Report & Sync Patient Pipeline
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>