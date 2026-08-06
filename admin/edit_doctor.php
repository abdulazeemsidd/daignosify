<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';

if (!isset($_GET['id'])) {
    header("Location: view_doctors.php");
    exit();
}

$doc_id = intval($_GET['id']);
$fetch_query = "SELECT * FROM doctors WHERE id = $doc_id";
$result = mysqli_query($conn, $fetch_query);
$doc = mysqli_fetch_assoc($result);

if (!$doc) {
    echo "<script>alert('Doctor profile not found!'); window.location.href='view_doctors.php';</script>";
    exit();
}


$depts_result = mysqli_query($conn, "SELECT * FROM departments ORDER BY dept_name ASC");
$quals_result = mysqli_query($conn, "SELECT * FROM qualifications ORDER BY qual_name ASC");


if (isset($_POST['update_doc_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['doc_name']);
    $email = mysqli_real_escape_string($conn, $_POST['doc_email']);
    $dept = mysqli_real_escape_string($conn, $_POST['doc_dept']);
    $qual = mysqli_real_escape_string($conn, $_POST['doc_qual']);
    $exp = mysqli_real_escape_string($conn, $_POST['doc_exp']);
    $time = mysqli_real_escape_string($conn, $_POST['doc_time']);
    
    $img_name = $_FILES['doc_img']['name'];
    $img_tmp = $_FILES['doc_img']['tmp_name'];
    
    if (!empty($img_name)) {
        
        $old_file = "../images/" . $doc['image_url'];
        if (file_exists($old_file) && !empty($doc['image_url'])) {
            unlink($old_file);
        }
        
        $target_dir = "../images/";
        $target_file = $target_dir . basename($img_name);
        move_uploaded_file($img_tmp, $target_file);
        
        
        $update_query = "UPDATE doctors SET name='$name', email='$email', department='$dept', qualification='$qual', experience='$exp', timing='$time', image_url='$img_name' WHERE id=$doc_id";
    } else {

        $update_query = "UPDATE doctors SET name='$name', email='$email', department='$dept', qualification='$qual', experience='$exp', timing='$time' WHERE id=$doc_id";
    }
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Specialist configuration updated successfully!'); window.location.href='view_doctors.php';</script>";
    } else {
        echo "<script>alert('Database Error during operation updates.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Specialist Profile</title>
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
                <h3 class="fw-bold text-dark"><i class="fa-solid fa-user-gear text-warning me-2"></i>Edit Profile Details</h3>
                <p class="text-muted small">Update master configurations for Dr. <?php echo htmlspecialchars($doc['name']); ?></p>
            </div>
            
            <form action="edit_doctor.php?id=<?php echo $doc_id; ?>" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Doctor Full Name</label>
                    <input type="text" name="doc_name" class="form-control" value="<?php echo htmlspecialchars($doc['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Doctor Email</label>
                    <input type="text" name="doc_email" class="form-control" value="<?php echo htmlspecialchars($doc['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Department Assignment</label>
                    <select name="doc_dept" class="form-select" required>
                        <?php while($d_row = mysqli_fetch_assoc($depts_result)) { ?>
                            <option value="<?php echo htmlspecialchars($d_row['dept_name']); ?>" <?php echo ($doc['department'] == $d_row['dept_name']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d_row['dept_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Medical Qualification</label>
                    <select name="doc_qual" class="form-select" required>
                        <?php while($q_row = mysqli_fetch_assoc($quals_result)) { ?>
                            <option value="<?php echo htmlspecialchars($q_row['qual_name']); ?>" <?php echo ($doc['qualification'] == $q_row['qual_name']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($q_row['qual_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Experience Details</label>
                    <input type="text" name="doc_exp" class="form-control" value="<?php echo htmlspecialchars($doc['experience']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Available Timing Slot</label>
                    <input type="text" name="doc_time" class="form-control" value="<?php echo htmlspecialchars($doc['timing']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary">Update Specialist Photo</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="../images/<?php echo htmlspecialchars($doc['image_url']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" alt="Current Pic">
                        <span class="text-muted small">Current layout frame graphic node.</span>
                    </div>
                    <input type="file" name="doc_img" class="form-control" accept="image/*">
                    <div class="form-text text-muted small">Leave empty if you don't want to replace current photo profile.</div>
                </div>

                <div class="d-flex gap-2">
                    <a href="view_doctors.php" class="btn btn-light w-50 fw-bold py-3">Cancel</a>
                    <button type="submit" name="update_doc_btn" class="btn btn-warning text-dark w-50 fw-bold py-3 shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>