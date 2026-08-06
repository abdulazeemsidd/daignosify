<?php
session_start();
include __DIR__ . '/../auth/db-config.php';

if (!isset($_SESSION['doctor_logged_in'])) {
    header("Location: ../auth/login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
$appointment_id = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : 0;


$patient_query = "SELECT name FROM patients WHERE id = $patient_id";
$patient_res = mysqli_query($conn, $patient_query);
$patient = mysqli_fetch_assoc($patient_res);

if (isset($_POST['submit_prescription_btn'])) {
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $medicines = mysqli_real_escape_string($conn, $_POST['medicines']);
    $suggested_tests = mysqli_real_escape_string($conn, $_POST['suggested_tests']);

    $insert_query = "INSERT INTO prescriptions (appointment_id, patient_id, doctor_id, diagnosis, medicines, suggested_tests) 
                     VALUES ('$appointment_id', '$patient_id', '$doctor_id', '$diagnosis', '$medicines', '$suggested_tests')";

    if (mysqli_query($conn, $insert_query)) {
        
        mysqli_query($conn, "UPDATE appointments SET status='Completed' WHERE appointment_id='$appointment_id'");
        
        echo "<script>alert('Prescription submitted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to submit prescription.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Prescription - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5" style="max-width: 700px;">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-primary text-white p-4">
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-file-prescription me-2"></i>Write Clinical Prescription</h4>
            <p class="mb-0 small text-white-50">Patient Name: <?php echo htmlspecialchars($patient['name'] ?? 'N/A'); ?> (Appt ID: #<?php echo $appointment_id; ?>)</p>
        </div>
        <div class="card-body p-4 bg-white">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Diagnosis</label>
                    <textarea name="diagnosis" class="form-control" rows="3" placeholder="Enter patient diagnosis..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Medicines</label>
                    <textarea name="medicines" class="form-control" rows="5" placeholder="e.g. Tab Panadol (500mg) - 1+0+1 (After meal)" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Suggested Lab Tests & Lab(Optional)</label>
                    <input type="text" name="suggested_tests" class="form-control" placeholder="e.g. CBC, Serum Creatinine, Liver Function Test FROM Dow Lab">
                </div>
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="dashboard.php" class="btn btn-light fw-bold">Cancel</a>
                    <button type="submit" name="submit_prescription_btn" class="btn btn-success fw-bold">Submit & Complete</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>