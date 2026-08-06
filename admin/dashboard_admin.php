
<?php
session_start();


if (file_exists(__DIR__ . '/../auth/db-config.php')) {
    include __DIR__ . '/../auth/db-config.php';
} elseif (file_exists(__DIR__ . '/../db-config.php')) {
    include __DIR__ . '/../db-config.php';
} else {
    die("Database Configuration file missing!");
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}


$count_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM patients"))['total'];
$count_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments"))['total'];
$count_tests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM lab_tests"))['total'];


$query = "SELECT a.appointment_id, a.appointment_date, a.status, t.test_name, p.name AS patient_name, p.phone, d.name AS doc_name 
          FROM appointments a 
          LEFT JOIN lab_tests t ON a.test_id = t.test_id 
          LEFT JOIN patients p ON a.patient_id = p.id 
          LEFT JOIN doctors d ON a.doctor_id = d.id
          ORDER BY a.appointment_id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosify - Master Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; }
        .stat-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="flex-grow-1 p-4 p-md-5" style="max-width: calc(100% - 260px);">
        
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3 border-bottom pb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Administrative Control Desk</h2>
                <p class="text-muted mb-0">Overviewing system health parameters, system tracking arrays, and data configurations.</p>
            </div>
            <div class="bg-white px-3 py-2 rounded shadow-sm border small fw-semibold text-secondary">
                <i class="fa-regular fa-calendar-check text-primary me-2"></i>System Live: <?php echo date('d-M-Y'); ?>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white p-4 border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Registered Users</h6>
                            <h2 class="fw-bold text-dark mb-0"><?php echo $count_patients; ?></h2>
                        </div>
                        <div class="fs-1 text-primary opacity-50"><i class="fa-solid fa-users"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white p-4 border-start border-success border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Total Pipeline Bookings</h6>
                            <h2 class="fw-bold text-dark mb-0"><?php echo $count_appointments; ?></h2>
                        </div>
                        <div class="fs-1 text-success opacity-50"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white p-4 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Configured Catalog Tests</h6>
                            <h2 class="fw-bold text-dark mb-0"><?php echo $count_tests; ?></h2>
                        </div>
                        <div class="fs-1 text-warning opacity-50"><i class="fa-solid fa-vials"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Global Log Submissions</h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">Real-Time Sync</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Contact </th>
                            <th>Tests/Doctors Appointments</th>
                            <th>Shediulded Date</th>
                            <th>Status</th>
                            <!-- <th class="text-center">Action Executions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0) { ?>
                            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="fw-bold text-secondary"><?php echo $row['appointment_id']; ?></td>
                                    <td class="fw-semibold text-dark"><?php echo $row['patient_name']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td>
                                        <?php if(!empty($row['test_name'])) { ?>
                                            <span class="badge bg-light text-danger border border-danger-subtle px-2 py-1 mb-1 d-block text-start">
                                                <i class="fa-solid fa-flask me-1"></i> <?php echo $row['test_name']; ?>
                                            </span>
                                        <?php } ?>
                                        <?php if(!empty($row['doc_name'])) { ?>
                                            <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 d-block text-start">
                                                <i class="fa-solid fa-user-doctor me-1"></i> Dr. <?php echo $row['doc_name']; ?>
                                            </span>
                                        <?php } elseif(empty($row['test_name'])) { ?>
                                            <span class="text-muted small">General Service</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo date('d-M-Y', strtotime($row['appointment_date'])); ?></td>
                                    <td>
                                        <?php 
                                        $status = $row['status'];
                                        if($status == 'Pending') {
                                            echo '<span class="status-badge-pending">Pending</span>';
                                        } elseif($status == 'Approved') {
                                            echo '<span class="status-badge-approved">Approved</span>';
                                        } else {
                                            echo '<span class="status-badge-completed">Completed</span>';
                                        }
                                        ?>
                                    </td>

                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox display-6 d-block mb-3 text-secondary"></i>
                                    No records logged into core environment schemas yet.
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