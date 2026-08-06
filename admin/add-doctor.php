<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';

$depts_result = mysqli_query($conn, "SELECT * FROM departments ORDER BY dept_name ASC");
$quals_result = mysqli_query($conn, "SELECT * FROM qualifications ORDER BY qual_name ASC");

if (isset($_POST['add_doc_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['doc_name']);
    $email = mysqli_real_escape_string($conn, $_POST['doc_email']);
    $dept = mysqli_real_escape_string($conn, $_POST['doc_dept']); 
    $qual = mysqli_real_escape_string($conn, $_POST['doc_qual']); 
    $exp = mysqli_real_escape_string($conn, $_POST['doc_exp']);
    $time = mysqli_real_escape_string($conn, $_POST['doc_time']);
    $fees = mysqli_real_escape_string($conn, $_POST['doc_fees']);

    $img_name = $_FILES['doc_img']['name'];
    $img_tmp = $_FILES['doc_img']['tmp_name'];
    
    $target_dir = "../images/";
    $target_file = $target_dir . basename($img_name);

    if (!empty($name) && !empty($img_name) && !empty($fees)) {
        if (move_uploaded_file($img_tmp, $target_file)) {
            $insert_query = "INSERT INTO doctors (name, email, department, qualification, experience, timing, fees, image_url) 
                             VALUES ('$name', '$email', '$dept', '$qual', '$exp', '$time', '$fees', '$img_name')";
            
            if (mysqli_query($conn, $insert_query)) {
                echo "<script>alert('Doctor Registered & Image Uploaded Successfully!'); window.location.href='dashboard_admin.php';</script>";
            } else {
                echo "<script>alert('Database Error!');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload image file to folder.');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields and select an image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Doctor - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="bg-light">
<div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="flex-grow-1 p-5">
        <div class="card p-4 p-md-5 shadow-sm border-0 bg-white" style="max-width: 650px; margin: auto; border-radius: 12px;">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark"><i class="fa-solid fa-user-doctor text-primary me-2"></i>Add New Specialist</h3>
                <p class="text-muted small">Select options loaded dynamically from the central database server.</p>
            </div>
            
            <form action="add-doctor.php" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Doctor Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user text-muted"></i></span>
                        <input type="text" name="doc_name" class="form-control text-capitalize" placeholder="e.g., Dr. Asher Ahmed" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Doctor Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                        <input type="text" name="doc_email" class="form-control" placeholder="e.g., Dr.asherahmed@gmail.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Department Assignment</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-building-user text-muted"></i></span>
                        <select name="doc_dept" class="form-select" required>
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
                        <select name="doc_qual" class="form-select" required>
                            <option value="">-- Choose Credential Profile --</option>
                            <?php while($q_row = mysqli_fetch_assoc($quals_result)) { ?>
                                <option value="<?php echo htmlspecialchars($q_row['qual_name']); ?>">
                                    <?php echo htmlspecialchars($q_row['qual_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Experience Details</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-briefcase text-muted"></i></span>
                        <input type="text" name="doc_exp" class="form-control" placeholder="e.g., 14+ Years Experience" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Consultancy Fees (PKR)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-money-bill-wave text-muted"></i></span>
                        <input type="number" name="doc_fees" class="form-control" placeholder="e.g., 1500" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Available Timing Slot</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-clock text-muted"></i></span>
                        <input type="text" name="doc_time" class="form-control" placeholder="e.g., 09:00 AM - 04:00 PM" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary">Upload Specialist Photo</label>
                    <input type="file" name="doc_img" class="form-control" accept="image/*" required>
                    <div class="form-text text-muted small">Select any valid image matrix from your desktop node environment.</div>
                </div>

                <button type="submit" name="add_doc_btn" class="btn btn-primary w-100 fw-bold py-3 shadow-sm border-0">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Register Doctor Profile
                </button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>