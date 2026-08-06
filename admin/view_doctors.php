<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied! Admin authentication required.'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';

if (isset($_POST['action_update_status'])) {
    $doc_id = intval($_POST['doctor_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['updated_status']);
    
    $update_query = "UPDATE doctors SET status = '$new_status' WHERE id = $doc_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Specialist status updated to " . $new_status . " successfully!'); window.location.href='view_doctors.php';</script>";
        exit();
    }
}

$query = "SELECT * FROM doctors ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Specialists Panel - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; }
        .table-doc-thumb { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid #eef2f5; }
        .status-select-active { color: #198754 !important; font-weight: 700; background-color: #e8f5e9; border-color: #a5d6a7; }
        .status-select-inactive { color: #dc3545 !important; font-weight: 700; background-color: #ffebee; border-color: #ef9a9a; }
        .status-select-pending { color: #ffc107 !important; font-weight: 700; background-color: #fff8e1; border-color: #ffe082; }
        .action-btn-edit { color: #ffc107; background: none; border: none; padding: 5px 10px; font-size: 1.1rem; transition: 0.2s; }
        .action-btn-edit:hover { color: #dba200; transform: scale(1.15); }
        .action-btn-delete { color: #dc3545; background: none; border: none; padding: 5px 10px; font-size: 1.1rem; transition: 0.2s; }
        .action-btn-delete:hover { color: #bd2130; transform: scale(1.15); }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="flex-grow-1 p-4 p-md-5" style="max-width: calc(100% - 260px);">
        
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3 border-bottom pb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-user-doctor text-success me-2"></i>Medical Specialists Registry</h2>
                <p class="text-muted mb-0">Reviewing system tracking rosters and data configurations for all active clinical consultants.</p>
            </div>
            <div class="bg-white px-3 py-2 rounded shadow-sm border small fw-semibold text-secondary">
                Total Active Specialists: <span class="text-success fw-bold"><?php echo mysqli_num_rows($result); ?></span>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Qualifications</th>
                            <th>Fees</th>
                            <th>Time Schedule</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0) { ?>
                            <?php while($row = mysqli_fetch_assoc($result)) { 
                                $current_status = isset($row['status']) ? $row['status'] : 'Pending';
                                
                                if ($current_status === 'Active') {
                                    $select_class = 'status-select-active';
                                } elseif ($current_status === 'Inactive') {
                                    $select_class = 'status-select-inactive';
                                } else {
                                    $select_class = 'status-select-pending';
                                }
                            ?>
                                <tr>
                                    <td>
                                        <img src="../images/<?php echo htmlspecialchars($row['image_url']); ?>" class="table-doc-thumb" alt="Doctor Pic">
                                    </td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1"><?php echo htmlspecialchars($row['department']); ?></span>
                                    </td>
                                    <td class="text-secondary small fw-medium"><?php echo htmlspecialchars($row['qualification']); ?></td>
                                    <td class="fw-bold text-success">PKR <?php echo htmlspecialchars($row['fees']); ?>/-</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded">
                                            <i class="fa-regular fa-clock me-1"></i><?php echo htmlspecialchars($row['timing']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="view_doctors.php" class="m-0">
                                            <input type="hidden" name="doctor_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="action_update_status" value="1">
                                            
                                            <select name="updated_status" class="form-select form-select-sm <?php echo $select_class; ?>" onchange="this.form.submit()" style="width: 125px; border-radius: 6px;">
                                                <option value="Active" <?php echo (strtolower($current_status) === 'active') ? 'selected' : ''; ?>>● Active</option>
                                                <option value="Pending" <?php echo (strtolower($current_status) === 'pending') ? 'selected' : ''; ?>>● Pending</option>
                                                <option value="Inactive" <?php echo (strtolower($current_status) === 'inactive') ? 'selected' : ''; ?>>● Inactive</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="edit_doctor.php?id=<?php echo $row['id']; ?>" class="action-btn-edit" title="Edit Roster Parameters">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </a>
                                        <a href="delete_doctor.php?id=<?php echo $row['id']; ?>" class="action-btn-delete" title="Delete Permanent Record" onclick="return confirm('Are you sure you want to completely erase this doctor profile? This action is irreversible.');">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-user-slash display-6 d-block mb-3 text-secondary"></i>
                                    No doctors registered inside the central environment schemas yet.
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