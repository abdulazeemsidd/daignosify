
<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}


if ($_SESSION['role'] !== 'lab_doctor') {
    
    echo "<script>
            alert('Access Denied! Only Lab Attendants can access this portal.'); 
            window.location.href='../auth/login.php';
          </script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';





$query = "SELECT a.*, p.name AS patient_name, p.gender, t.test_name 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.id 
          JOIN lab_tests t ON a.test_id = t.test_id 
          ORDER BY a.appointment_id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Attendant Dashboard - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
    <style>
        body { background-color: #f4f6f9; }
        .dashboard-container { max-width: 1300px; margin: 50px auto; }
        .table-card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-3 shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold mb-0 text-white"><i class="fa-solid fa-flask me-2 text-info"></i> Lab Technician Portal</span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold px-3"><i class="fa-solid fa-power-off me-1"></i> LogOut</a>
    </div>
</nav>

<div class="container dashboard-container px-3">
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Lab Testing Queue</h3>
        <p class="text-muted small">Manage incoming clinical tests and generate dynamic biochemical reports.</p>
    </div>

    <div class="card p-4 table-card bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Patient Name</th>
                        <th>Test Requested</th>
                        <th>Gender</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                            $status = $row['status'];
                            $badge_class = ($status == 'Completed') ? 'bg-success' : 'bg-warning text-dark';
                        ?>
                        <tr>
                            <td><span class="fw-bold text-secondary">#<?php echo htmlspecialchars($row['appointment_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info">
                                    <i class="fa-solid fa-vial me-1"></i> <?php echo htmlspecialchars($row['test_name']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><i class="fa-regular fa-calendar me-1"></i> <?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                            <td class="text-center">
                                <?php if ($status === 'Completed') { ?>
                                    <span class="text-success fw-bold small"><i class="fa-solid fa-circle-check me-1"></i> Report Generated</span>
                                <?php } else { ?>
                                    <a href="generate_report.php?appointment_id=<?php echo $row['appointment_id']; ?>&patient_id=<?php echo $row['patient_id']; ?>&test_id=<?php echo $row['test_id']; ?>" 
                                       class="btn btn-sm btn-primary fw-bold px-3">
                                        <i class="fa-solid fa-file-medical me-1"></i> Generate Report
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php 
                        } 
                    } else { ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No lab test bookings found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>