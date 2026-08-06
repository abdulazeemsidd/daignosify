<?php
session_start();
include __DIR__ . '/../auth/db-config.php'; 

include  'navbar.php'; 
$opId=$_GET['appointment_id'];
$query="SELECT patients.name as 'p.name',patients.phone,patients.gender,doctors.name as 'd.name',
doctors.email,doctors.fees,doctors.timing,prescriptions.diagnosis,prescriptions.medicines,prescriptions.suggested_tests
 FROM prescriptions INNER join doctors on prescriptions.doctor_id=doctors.id
inner join patients on prescriptions.patient_id=patients.id
inner join appointments on appointments.appointment_id=prescriptions.id";

$data=mysqli_query($conn,$query);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Diagnosify</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <link rel="stylesheet" href="../styling/style.css"> <style>
        body { background-color: #f4f6f9; }
        .dashboard-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); } 
        .nav-tabs .nav-link { border: none; font-weight: 600; color: #6c757d; padding: 12px 20px; } 
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 3px solid #0d6efd; background: transparent; }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-2 text-center">
            <h1>gnosify
</h1>

<hr><hr>

<?php

foreach($data as $pre){

}

?>

        </div>
    </div>
</div>



<?php 
include 'footer.php'; ?> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 </body>
</html>