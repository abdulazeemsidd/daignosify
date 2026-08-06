<?php
session_start();

include __DIR__ . '/../auth/db-config.php'; 


if (!isset($_SESSION['patient_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];
$appointment_id = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : 0;

if ($appointment_id === 0) {
    die("Invalid Appointment ID.");
}


$query = "SELECT pr.*, p.name AS pat_name, p.gender, d.name AS doc_name, d.department 
          FROM prescriptions pr 
          JOIN patients p ON pr.patient_id = p.id
          JOIN doctors d ON pr.doctor_id = d.id
          WHERE pr.appointment_id = $appointment_id AND pr.patient_id = $patient_id";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);


if (!$data) {
    echo "   <div class='card border-0 shadow-sm mt-5 text-center p-4'>
                    <div class='card-body'>
                        <i class='fa-solid fa-circle-exclamation text-warning display-4 mb-3'></i>
                        <h4 class='fw-bold text-dark'>Prescription Not Found</h4>
                        <p class='text-secondary'>Either the prescription is not generated yet for this appointment or you don't have access to view it.</p>
                        <a href='dashboard.php' class='btn btn-primary btn-sm px-4 mt-2'>
                            <i class='fa-solid fa-arrow-left me-1'></i> Back to Dashboard
                        </a>
                    </div>
                </div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription #<?php echo $appointment_id; ?> - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .prescription-card { background: rgb(202, 200, 200)6f9; border-radius: 15px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); overflow: hidden; margin-top: 40px; margin-bottom: 40px; }
        .prescription-header { background: linear-gradient(135deg, #0f4c81, #1d72b8); color: white; padding: 30px; }
        .prescription-body { padding: 40px; }
        
        
    
        @media print {
            body { background-color: white; }
            .no-print { display: none !important; }
            .prescription-header { background: #0d265b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="col-md-9 col-lg-8">
        
        <div class="d-flex justify-content-between mt-4 px-2 no-print">
            <a href="dashboard.php" class="btn btn-secondary fw-bold px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            
        </div>

        <div class="prescription-card">
            <div class="prescription-header">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <h2 class="fw-bold m-0"><i class="fa-solid fa-heart-pulse me-2 text-danger"></i> Diagnosify</h2>
                        <p class="m-0 opacity-75 mt-1">Advanced Digital Health & Diagnostics Care</p>
                    </div>
                    <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
                        <h5 class="fw-bold m-0">Prescription Sheet</h5>
                        <small class="opacity-75">Ref: #<?php echo $appointment_id; ?></small>
                    </div>
                </div>
            </div>

            <div class="prescription-body">
                <div class="row pb-4 mb-4 border-bottom">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Patient Details</h6>
                        <h5 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($data['pat_name']); ?></h5>
                        <p class="text-secondary small m-0">Gender: <?php echo htmlspecialchars($data['gender']); ?></p>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Consultant Doctor</h6>
                        <h5 class="fw-bold text-primary m-0">Dr. <?php echo htmlspecialchars($data['doc_name']); ?></h5>
                        <p class="text-secondary small m-0">Dept: <?php echo htmlspecialchars($data['department']); ?></p>
                    </div>
                </div>

                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold text-dark d-flex align-items-center mb-3">
                        <i class="fa-solid fa-stethoscope text-danger me-2"></i> Diagnosis & Assessment
                    </h5>
                    <div class="p-3 bg-light rounded text-secondary">
                        <?php echo htmlspecialchars($data['diagnosis']); ?>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-baseline mb-3">
                      
                        <h5 class="fw-bold text-dark m-0">Medicines & Dose Instructions</h5>
                    </div>
                    <div class="p-3 bg-light-subtle border rounded text-dark" style="min-height: 120px; line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($data['medicines'])); ?>
                    </div>
                </div>

                <?php if (!empty($data['suggested_tests'])) { ?>
                    <div class="mt-4 pt-4 border-top">
                        <h5 class="fw-bold text-dark d-flex align-items-center mb-3">
                            <i class="fa-solid fa-flask-vial text-info me-2"></i> Recommended Clinical Tests
                        </h5>
                        <div class="p-3 bg-light rounded text-secondary">
                            <?php echo nl2br(htmlspecialchars($data['suggested_tests'])); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="bg-light p-3 text-center border-top text-muted small">
                Generated securely on Diagnosify Portal | Date: <?php echo date('d-M-Y', strtotime($data['created_at'])); ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>   