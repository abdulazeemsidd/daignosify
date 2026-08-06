<?php
session_start();

if (file_exists(__DIR__ . '/db-config.php')) {
    include __DIR__ . '/db-config.php';
} elseif (file_exists(__DIR__ . '/../db-config.php')) {
    include __DIR__ . '/../db-config.php';
} else {
    die("Database Configuration file missing! Check db-config.php location.");
}

$alert_message = '';
$alert_type = '';

if (isset($_POST['register_lab_btn'])) {
    $name     = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['user_email']));
    $phone    = mysqli_real_escape_string($conn, trim($_POST['user_phone']));
    $gender   = mysqli_real_escape_string($conn, trim($_POST['user_gender']));
    $password = trim($_POST['user_password']);
    
    
    $role = 'lab_doctor';

    
    $check_email_query = "SELECT * FROM patients WHERE LOWER(TRIM(email)) = LOWER('$email')";
    $check_res = mysqli_query($conn, $check_email_query);

    if ($check_res && mysqli_num_rows($check_res) > 0) {
        $alert_message = "This Email Address is already registered! Try Logging in.";
        $alert_type = "danger";
    } else {
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        
        $insert_query = "INSERT INTO patients (name, email, phone, gender, password, role) 
                        VALUES ('$name', '$email', '$phone', '$gender', '$hashed_password', '$role')";

        if (mysqli_query($conn, $insert_query)) {
            $alert_message = "Lab Attendant Profile Created Successfully! You can now log in.";
            $alert_type = "success";
        } else {
            $alert_message = "Registration Failed: " . mysqli_error($conn);
            $alert_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosify - Lab Attendant Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            padding: 20px; 
            background-color: #f4f6f9; 
        }
        .register-container { 
            background: white; 
            padding: 35px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 480px; 
            border: 1px solid #eef2f5; 
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2 class="text-center fw-bold mb-1" style="color: #0f4c81;">
        <i class="fa-solid fa-flask text-info me-2"></i>Lab Attendant
    </h2>
    <p class="text-center text-muted small mb-4">Register Clinical Staff Profile</p>
    
    <?php if(!empty($alert_message)): ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show small fw-bold mb-3" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $alert_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="register_lab.php" method="POST">
        
        <div class="mb-3">
            <label class="form-label fw-semibold small">Full Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user text-secondary"></i></span>
                <input type="text" name="full_name" class="form-control" placeholder="e.g. Huda Malik" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope text-secondary"></i></span>
                <input type="email" name="user_email" class="form-control" placeholder="hudamalik@gmail.com" required>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Contact No.</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-phone text-secondary"></i></span>
                    <input type="text" name="user_phone" class="form-control" placeholder="03XXXXXXXXX" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Gender</label>
                <select name="user_gender" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold small">Access Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock text-secondary"></i></span>
                <input type="password" name="user_password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" name="register_lab_btn" class="btn btn-primary w-100 fw-bold border-0 py-2 shadow-sm" style="background-color: #0f4c81;">
            <i class="fa-solid fa-user-plus me-1"></i> Register Lab Staff
        </button>
    </form>
    
    <div class="text-center mt-4 small border-top pt-3">
        <p class="mb-0">Already registered? <a href="login.php" class="text-primary fw-bold text-decoration-none">Go to Login Portal</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>