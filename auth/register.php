<?php
include __DIR__ . '/db-config.php';

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $email = mysqli_real_escape_string($conn, $_POST['patient_email']);
    
    
    $password = $_POST['patient_password'];
    // $password_hash=sha1($password,true);
     $password_hash = password_hash($password, PASSWORD_DEFAULT);

    
    $phone = mysqli_real_escape_string($conn, $_POST['patient_phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['patient_gender']);

    $check_email = "SELECT * FROM patients WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This Email is already registered!');</script>";
    } else {
        
        $insert_query = "INSERT INTO patients (name, email, password, phone, gender, role) 
                         VALUES ('$name', '$email', '$password_hash', '$phone', '$gender', 'patient')";
        
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error creating account. Try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosify - Patient Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; background-color: #f4f6f9; }
        .register-container { background: white; padding: 35px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 450px; border: 1px solid #eef2f5; }
    </style>
</head>
<body>

<div class="register-container">
    <h2 class="text-center fw-bold mb-1" style="color: #0f4c81;">
        <i class="fa-solid fa-heart-pulse text-danger me-2"></i>Diagnosify
    </h2>
    <p class="text-center text-muted small mb-4">Create your patient health portal account</p>
    
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold small">Full Name</label>
            <input type="text" name="patient_name" class="form-control" placeholder="Enter your full name" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Email Address</label>
            <input type="email" name="patient_email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Password</label>
            <input type="password" name="patient_password" class="form-control" placeholder="Create strong password" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Phone Number</label>
            <input type="text" name="patient_phone" class="form-control" placeholder="e.g. 03XXXXXXXXX" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold small">Gender</label>
            <select name="patient_gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <button type="submit" name="register_btn" class="btn btn-primary w-100 fw-bold border-0 py-2" style="background-color: #0f4c81;">Register</button>
    </form>
    
    <div class="text-center mt-4 small">
        Already have an account? <a href="login.php" class="text-decoration-none fw-bold" style="color: #0f4c81;">Login here</a>
    </div>
</div>

</body>
</html>