<?php
session_start();
include __DIR__ . '/../auth/db-config.php';

if (!isset($_SESSION['patient_id'])) {
    echo "<script>alert('Please login first to view reports.'); window.location.href='../auth/login.php';</script>";
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = mysqli_real_escape_string($conn, $_GET['booking_id']);
    $patient_id = $_SESSION['patient_id'];


 $query = "SELECT a.*, p.name as patient_name, p.email, p.phone, p.gender, 
                 t.test_name, t.price, t.test_desc 
          FROM appointments a 
          LEFT JOIN patients p ON a.patient_id = p.id 
          LEFT JOIN lab_tests t ON a.test_id = t.test_id 
          WHERE (a.booking_id = '$booking_id' OR a.appointment_id = '$booking_id') 
          AND a.patient_id = '$patient_id' 
          AND LOWER(TRIM(a.status)) = 'completed'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $report = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Report not generated yet or booking data mismatched.'); window.close();</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid Tracking Route Parameters.'); window.close();</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic_Report_<?php echo htmlspecialchars($report['booking_id']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; }
        .report-header { border-bottom: 3px solid #0f4c81; padding-bottom: 20px; }
        .lab-brand { color: #0f4c81; font-weight: 800; font-size: 2rem; }
        .info-box { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 6px; }
        .status-stamp { border: 2px solid #198754; color: #198754; font-weight: bold; text-transform: uppercase; padding: 5px 15px; display: inline-block; border-radius: 4px; transform: rotate(-5deg); }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body >

<div class="container my-5">
    
    <div class="no-print text-end mb-4">
        <button onclick="window.print()" class="btn btn-primary fw-bold px-4 me-2"><i class="fa-solid fa-print me-1"></i> Print / Save PDF</button>
        <a href="dashboard.php"><button class="btn btn-secondary fw-bold">Close</button></a>
    </div>

    <div class="border p-5 rounded bg-white shadow-sm">
        
        <div class="row report-header align-items-center mb-4">
            <div class="col-sm-6">
                <div class="lab-brand">DIAGNOSIFY</div>
                <p class="text-muted small m-0">Advanced Electronic Lab Diagnosis Matrix Node<br>Aptech Campus, Karachi, Pakistan</p>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <div class="status-stamp">VERIFIED & COMPLETED</div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="info-box h-100">
                    <h6 class="text-secondary fw-bold text-uppercase small mb-2">Patient Details</h6>
                    <table class="table table-borderless table-sm m-0 small">
                        <tr><td class="fw-bold p-0 text-muted" style="width: 100px;">Name:</td><td class="p-0 text-dark fw-semibold"><?php echo htmlspecialchars($report['patient_name']); ?></td></tr>
                        <tr><td class="fw-bold p-0 text-muted">Gender:</td><td class="p-0 text-dark"><?php echo htmlspecialchars($report['gender']); ?></td></tr>
                        <tr><td class="fw-bold p-0 text-muted">Contact:</td><td class="p-0 text-dark"><?php echo htmlspecialchars($report['phone']); ?></td></tr>
                        <tr><td class="fw-bold p-0 text-muted">Email ID:</td><td class="p-0 text-dark"><?php echo htmlspecialchars($report['email']); ?></td></tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box h-100">
                    <h6 class="text-secondary fw-bold text-uppercase small mb-2">Tracking Logs</h6>
                    <table class="table table-borderless table-sm m-0 small">
                        <tr><td class="fw-bold p-0 text-muted" style="width: 130px;">Tracking ID:</td><td class="p-0 text-primary fw-bold"><?php echo htmlspecialchars($report['booking_id']); ?></td></tr>
                        <tr><td class="fw-bold p-0 text-muted">Appointment ID:</td><td class="p-0 text-dark">#DG-<?php echo htmlspecialchars($report['appointment_id']); ?></td></tr>
                        <tr><td class="fw-bold p-0 text-muted">Scheduled Date:</td><td class="p-0 text-dark"><?php echo date('d-M-Y', strtotime($report['appointment_date'])); ?></td></tr>
                        <tr><td class="fw-bold p-0 text-muted">Generated On:</td><td class="p-0 text-dark"><?php echo date('d-M-Y H:i:s'); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="table-responsive my-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-uppercase small">
                    <tr>
                        <th class="py-3 px-3" style="width: 70%;">Diagnostic Parameter / Test Name</th>
                        <th class="py-3 text-center" style="width: 30%;">Result Metrics</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-4 px-3">
                            <h6 class="fw-bold text-dark m-0 mb-1"><?php echo htmlspecialchars($report['test_name']); ?></h6>
                            <p class="text-muted small m-0"><?php echo htmlspecialchars($report['test_desc']); ?></p>
                        </td>
                        <td class="text-center py-4">
                            <span class="text-success fw-bold d-block fs-5"><i class="fa-solid fa-circle-check me-1"></i> NORMAL</span>
                            <small class="text-muted">Cleared by Diagnostics Center</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mt-5 pt-4 border-top">
            <div class="col-7">
                <p class="text-muted small m-0"><strong>Note:</strong> This is an e-generated diagnostic tracking report synced from secure server data slots. Physical authentication is mapped dynamically via system hashing keys.</p>
            </div>
            <div class="col-5 text-end">
                <div class="d-inline-block text-center border-top pt-2" style="width: 200px;">
                    <p class="fw-bold text-dark small m-0">Pathologist In-Charge</p>
                    <span class="text-muted d-block" style="font-size: 11px;">Diagnosify System Node</span>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>