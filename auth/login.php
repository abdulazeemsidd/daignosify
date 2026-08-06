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

if (isset($_POST['login_btn'])) {
    session_unset();
    $email = mysqli_real_escape_string($conn, trim($_POST['user_email']));
    $password = trim($_POST['user_password']); 

    
    $clean_email = preg_replace('/^dr\.?/i', '', $email);

    $doc_query = "SELECT doctors.*, departments.dept_name AS dept_name 
                  FROM doctors 
                  LEFT JOIN departments ON doctors.department = departments.dept_id 
                  WHERE doctors.email = '$email' 
                  OR doctors.email = '$clean_email' 
                  OR doctors.email = 'dr.$clean_email' 
                  OR doctors.email = 'dr$clean_email'";
                  
    $doc_res = mysqli_query($conn, $doc_query);

    if ($doc_res && mysqli_num_rows($doc_res) > 0) {
        $doc = mysqli_fetch_assoc($doc_res);  

        if (password_verify($password, $doc['password']) || $password === $doc['password']) {
            if (isset($doc['status']) && strtolower($doc['status']) !== 'active') {
                $alert_message = "Account Approval Pending! Admin has not approved your profile yet.";
                $alert_type = "warning";
            } else {
                $_SESSION['user_id'] = $doc['id'];
                $_SESSION['role'] = 'doctor';
                $_SESSION['doctor_logged_in'] = true;
                $_SESSION['doctor_id'] = $doc['id'];
                $_SESSION['doctor_name'] = $doc['name'];
                $_SESSION['doctor_email'] = $doc['email'];
                $_SESSION['doctor_dept'] = !empty($doc['dept_name']) ? $doc['dept_name'] : $doc['department'];
                
                header("Location: ../doctor/dashboard.php");
                exit();
            }
        } else {
            $alert_message = "Incorrect password!";
            $alert_type = "danger";
        }
    } else {
      
        $check_user_query = "SELECT * FROM patients WHERE LOWER(TRIM(email)) = LOWER('$email')";
        $result = mysqli_query($conn, $check_user_query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                
                $role = strtolower(trim($user['role']));

                if ($role === 'admin') {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['name'];
                    $_SESSION['admin_email'] = $user['email'];
                    
                    header("Location: ../admin/dashboard_admin.php");
                    exit();

                } elseif ($role === 'lab_doctor') {
                    $_SESSION['lab_logged_in'] = true;
                    $_SESSION['lab_id'] = $user['id'];
                    $_SESSION['lab_name'] = $user['name'];
                    $_SESSION['lab_email'] = $user['email'];
                    
                    header("Location: ../lab/dashboard.php");
                    exit();

                } else {
                    $_SESSION['patient_id'] = $user['id'];
                    $_SESSION['patient_name'] = $user['name'];
                    $_SESSION['patient_email'] = $user['email'];
                    
                    header("Location: ../user/dashboard.php");
                    exit();
                }

            } else {
                $alert_message = "Incorrect password! Please try again.";
                $alert_type = "danger";
            }
        } else {
            $alert_message = "No account found matching this email!";
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
    <title>Diagnosify - Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; background-color: #f4f6f9; }
        .login-container { background: white; padding: 35px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 420px; border: 1px solid #eef2f5; }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="text-center fw-bold mb-1" style="color: #0f4c81;">
        <i class="fa-solid fa-heart-pulse text-danger me-2"></i>Diagnosify
    </h2>
    <p class="text-center text-muted small mb-3">Single Portal Access System</p>
    
    <?php if(!empty($alert_message)): ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show small fw-bold mb-3" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $alert_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold small">Email Address</label>
            <input type="email" name="user_email" class="form-control" placeholder="name@example.com" required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold small">Password</label>
            <input type="password" name="user_password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" name="login_btn" class="btn btn-primary w-100 fw-bold border-0 py-2" style="background-color: #0f4c81;">
            <i class="fa-solid fa-right-to-bracket me-1"></i> Proceed Login
        </button>
    </form>
    
    <div class="text-center mt-4 small">
        <p class="mb-1">Don't have a patient account? <a href="register.php" class="text-success fw-bold text-decoration-none">Register Patient Profile</a></p>
        <p class="mb-0">Are you a Doctor? <a href="register_doctor.php" class="text-primary fw-bold text-decoration-none">Apply as Doctor</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>