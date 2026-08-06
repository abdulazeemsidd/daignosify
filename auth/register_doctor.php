<?php
session_start();
include __DIR__ . '/db-config.php';

$depts_result = mysqli_query($conn, "SELECT * FROM departments ORDER BY dept_name ASC");
$quals_result = mysqli_query($conn, "SELECT * FROM qualifications ORDER BY qual_name ASC");

$alert_message = '';
$alert_type = '';

if (isset($_POST['register_doc_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['doc_name']);
    $email = mysqli_real_escape_string($conn, $_POST['doc_email']);
    $password = $_POST['doc_password'];
    $phone = mysqli_real_escape_string($conn, $_POST['doc_phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['doc_gender']);
    $specialization = mysqli_real_escape_string($conn, $_POST['doc_specialization']);
    $qualification = mysqli_real_escape_string($conn, $_POST['doc_qualification']);
    $timing = mysqli_real_escape_string($conn, $_POST['doc_timing']);
    $fees = mysqli_real_escape_string($conn, $_POST['doc_fees']);

    $image_name = 'default_doc.png';
    if (isset($_FILES['doc_image']['name']) && $_FILES['doc_image']['name'] != '') {
        $image_name = time() . '_' . $_FILES['doc_image']['name'];
        move_uploaded_file($_FILES['doc_image']['tmp_name'], '../images/' . $image_name);
    }

    $check_email = mysqli_query($conn, "SELECT * FROM patients WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $alert_message = "Email address is already registered!";
        $alert_type = "danger";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $insert_patient = "INSERT INTO patients (name, email, password, phone, gender, role) VALUES ('$name', '$email', '$hashed_password', '$phone', '$gender', 'doctor')";
        
        if (mysqli_query($conn, $insert_patient)) {
            $insert_doctor = "INSERT INTO doctors (name, email, password, department, qualification, timing, fees, image_url, status) VALUES ('$name', '$email', '$hashed_password', '$specialization', '$qualification', '$timing', '$fees', '$image_name', 'Pending')";
            
            mysqli_query($conn, $insert_doctor);

            $alert_message = "Registration submitted successfully! Profile pending Admin Approval.";
            $alert_type = "success";
        } else {
            $alert_message = "Database error! Could not register profile.";
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
    <title>Doctor Registration - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 30px 15px; }
        .reg-container { background: white; padding: 35px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
    </style>
</head>
<body>

<div class="reg-container">
    <h3 class="text-center fw-bold mb-1" style="color: #0f4c81;">
        <i class="fa-solid fa-user-md me-2"></i>Doctor Registration
    </h3>
    <p class="text-center text-muted small mb-3">Register your doctor profile for panel approval</p>

    <?php if(!empty($alert_message)): ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show small fw-bold mb-3" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i> <?php echo $alert_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="register_doctor.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Full Name</label>
                <input type="text" name="doc_name" class="form-control" placeholder="Dr. Asim Azhar" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Email Address</label>
                <input type="email" name="doc_email" class="form-control" placeholder="dr.example@gmail.com" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="doc_password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Phone Number</label>
                <input type="text" name="doc_phone" class="form-control" placeholder="03001234567" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Gender</label>
                <select name="doc_gender" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

             <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Department Assignment</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-building-user text-muted"></i></span>
                        <select name="doc_specialization" class="form-select" required>
                            <option value="">-- Choose Target Department --</option>
                            <?php while($d_row = mysqli_fetch_assoc($depts_result)) { ?>
                                <option value="<?php echo htmlspecialchars($d_row['dept_name']); ?>">
                                    <?php echo htmlspecialchars($d_row['dept_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Medical Qualification</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-graduation-cap text-muted"></i></span>
                        <select name="doc_qualification" class="form-select" required>
                            <option value="">-- Choose Credential Profile --</option>
                            <?php while($q_row = mysqli_fetch_assoc($quals_result)) { ?>
                                <option value="<?php echo htmlspecialchars($q_row['qual_name']); ?>">
                                    <?php echo htmlspecialchars($q_row['qual_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            <!-- <div class="col-md-6">
                <label class="form-label small fw-semibold">Specialization / Department</label>
                <input type="text" name="doc_specialization" class="form-control" placeholder="Cardiology, Neurology, etc." required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Qualification</label>
                <input type="text" name="doc_qualification" class="form-control" placeholder="MBBS, FCPS" required>
            </div> -->
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Timing</label>
                <input type="text" name="doc_timing" class="form-control" placeholder="09:00 AM - 02:00 PM" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Consultation Fees (PKR)</label>
                <input type="number" name="doc_fees" class="form-control" placeholder="2000" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Profile Photo</label>
                <input type="file" name="doc_image" class="form-control" accept="image/*">
            </div>
            <div class="col-12 mt-4">
                <button type="submit" name="register_doc_btn" class="btn btn-primary w-100 fw-bold py-2" style="background-color: #0f4c81;">
                    Submit Application
                </button>
            </div>
        </div>
    </form>
    
    <div class="text-center mt-3 small">
        Already registered? <a href="login.php" class="text-decoration-none fw-bold">Login here</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>