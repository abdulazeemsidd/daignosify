<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';
if (!isset($_GET['id'])){
    header("location:view_test.php");
    exit();
}

$id = intval($_GET['id']);

$data = mysqli_query($conn,"SELECT * FROM `lab_tests` WHERE test_id= $id " );
$result = mysqli_fetch_assoc($data);

if(isset($_POST['edit_test_btn'])){
    

    $t_name = mysqli_real_escape_string($conn, $_POST['test_name']);
    $t_desc = mysqli_real_escape_string($conn, $_POST['test_desc']);
    $t_category = mysqli_real_escape_string($conn, $_POST['test_category']);
    $t_price = mysqli_real_escape_string($conn, $_POST['test_price']);
    $estimated_h = mysqli_real_escape_string($conn, $_POST['estimated_hours']);

    $query = "UPDATE `lab_tests` SET `test_name`='$t_name',`test_desc`='$t_desc',`test_category`='$t_category',`price`='$t_price',`estimated_hours`='$estimated_h' WHERE test_id = '$id' ";


    if (mysqli_query($conn, $query)) {
                echo "<script>alert('Test Edited Successfully...!'); window.location.href='dashboard_admin.php';</script>";
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
        
            <form action="edit_test.php?id=<?php echo $id;?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Test Name</label>
                    <input type="text" name="test_name" class="form-control" value="<?php echo $result['test_name'] ?>" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description/Required_condition</label>
                    <input type="text" name="test_desc" class="form-control" placeholder=""  value="<?php echo $result['test_desc'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="test_category" class="form-control" value="<?php echo $result['test_category'] ?>" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Price</label>
                    <input type="text" name="test_price" class="form-control" placeholder=""  value="<?php echo $result['price'] ?>"required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Estimated Required Hours</label>
                    <input type="text" name="estimated_hours" class="form-control" placeholder="" value="<?php echo $result['estimated_hours'] ?>" required>
                </div>
                
                <button type="submit" name="edit_test_btn" class="btn btn-primary w-100 fw-bold py-2">Add Test</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>