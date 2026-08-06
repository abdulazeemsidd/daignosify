<?php
session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied! Admin authentication required.'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';


$query = "SELECT * from lab_tests";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered  List - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="flex-grow-1 p-4 p-md-5" style="max-width: calc(100% - 260px);">
        
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3 border-bottom pb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-users text-primary me-2"></i>Total Available Test</h2>
            </div>
            <div class="bg-white px-3 py-2 rounded shadow-sm border small fw-semibold text-secondary">
                Total Test Counter: <span class="text-primary fw-bold"><?php echo mysqli_num_rows($result); ?></span>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Test Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Estimated Hours</th>
                                <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0) { ?>
                            <?php while($test = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="fw-bold text-secondary"><?php echo $test['test_id']; ?></td>
                                    <td class="fw-semibold text-dark">
                                        <?php echo $test['test_name']; ?>
                                    </td>
                                   
                                    <td><?php echo $test['test_desc']; ?></td>
                                    <td> <?php echo $test['test_category']; ?></td>
                                    <td> <?php echo $test['price']; ?></td>
                                    <td> <?php echo $test['estimated_hours']; ?></td>
                                   
                                    <td class="text-center">
    <a href="edit_test.php?id=<?php echo $test['test_id']; ?>" class="btn btn-sm btn-info fw-bold text-white my-2">
        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
    </a>
    <a href="delete_test.php?id=<?php echo $test['test_id']; ?>" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Are you sure you want to delete this test?');">
        <i class="fa-solid fa-trash me-1"></i> Delete
    </a>
</td>
                                    
                                
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash display-6 d-block mb-3 text-secondary"></i>
                                    No Test registered on the Database yet.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>