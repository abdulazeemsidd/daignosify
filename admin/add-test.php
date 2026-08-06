<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';
if(isset($_POST['add_test_btn'])){
    $t_name = mysqli_real_escape_string($conn, $_POST['test_name']);
    $t_desc = mysqli_real_escape_string($conn, $_POST['test_desc']);
    $t_category = mysqli_real_escape_string($conn, $_POST['test_category']);
    $t_price = mysqli_real_escape_string($conn, $_POST['test_price']);
    $estimated_h = mysqli_real_escape_string($conn, $_POST['estimated_hours']);

    $query = "INSERT INTO `lab_tests`(`test_name`, `test_desc`, `test_category`, `price`, `estimated_hours`) VALUES('$t_name', '$t_desc', '$t_category', '$t_price', '$estimated_h')";


    if (mysqli_query($conn, $query)) {
                echo "<script>alert('Test Added Successfully...!'); window.location.href='dashboard_admin.php';</script>";
            } else {
                echo "<script>alert('Database Error!');</script>";
            }
        
    

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Test - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body>
<div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="flex-grow-1 p-5">
        <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 600px; margin: auto;">
            <h3 class="fw-bold text-dark mb-4"><i class="fa-solid fa-user-doctor text-primary me-2"></i>Add New Tests</h3>
            
            <form action="add-test.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Test Name</label>
                    <input type="text" name="test_name" class="form-control" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description/Required_condition</label>
                    <input type="text" name="test_desc" class="form-control" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="test_category" class="form-control" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Price</label>
                    <input type="text" name="test_price" class="form-control" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Estimated Required Hours</label>
                    <input type="text" name="estimated_hours" class="form-control" placeholder="" required>
                </div>
                
                <button type="submit" name="add_test_btn" class="btn btn-primary w-100 fw-bold py-2">Add Test</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>