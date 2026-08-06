<?php
session_start();
include __DIR__ . '/../auth/db-config.php'; 

if (!isset($_SESSION['doctor_logged_in'])) { 
    header("Location: ../auth/login.php"); 
    exit(); 
}

$doctor_id = $_SESSION['doctor_id']; 


if (isset($_POST['update_consultation_status_btn'])) { 
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']); 
    $target_status = mysqli_real_escape_string($conn, $_POST['target_status']); 
    
    
    $update_query = "UPDATE appointments SET status='$target_status' WHERE appointment_id='$appointment_id'"; 
    
    if (mysqli_query($conn, $update_query)) { 
        echo "<script>alert('Status updated and Patient Pipeline synchronized successfully!'); window.location.href='dashboard.php';</script>"; 
    } else {
        echo "<script>alert('Database sync error operational block.');</script>"; 
    }
}


$consult_query = "SELECT a.*, p.name as patient_name, p.gender, p.phone, t.test_name 
                  FROM appointments a 
                  JOIN patients p ON a.patient_id = p.id 
                  LEFT JOIN lab_tests t ON a.test_id = t.test_id 
                  WHERE a.doctor_id = '$doctor_id' 
                  ORDER BY a.appointment_id DESC"; 
$consult_result = mysqli_query($conn, $consult_query); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Workspace Node - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .dashboard-container { max-width: 1300px; margin: 50px auto; }
        .table-card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-3 shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold mb-0 text-white"><i class="fa-solid fa-user-doctor me-2 text-primary"></i>  <?php echo htmlspecialchars($_SESSION['doctor_name']); ?> (<small class="text-info"><?php echo htmlspecialchars($_SESSION['doctor_dept']); ?></small>)</span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold px-3"><i class="fa-solid fa-power-off me-1"></i> LogOut Doctor Panel</a>
    </div>
</nav>

<div class="container dashboard-container px-3">
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Patient Checkup Routines</h3>
        <p class="text-muted small"> <?php echo htmlspecialchars($_SESSION['doctor_name']); ?> You have access to process and update operations for live tracking</p>
    </div>

    <div class="card p-4 table-card bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
             <thead class="table-light">
    <tr>
        <th>Tracking ID</th>
        <th>Patient Name</th>
        <th>Requested Test/OPD Checkup</th>
        <th>Gender</th>
        <th>Contact</th>
        <th>Scheduled Date</th>
        <th>Status</th>
        <th class="text-center">Action</th>
    </tr>
</thead>
<tbody>
    <?php 
    
    if (mysqli_num_rows($consult_result) > 0) {
        while ($row = mysqli_fetch_assoc($consult_result)) { 
            $status = $row['status'];
            $badge_class = ($status == 'Completed') ? 'bg-success' : 'bg-warning text-dark';
            
       
            $booking_id = $row['appointment_id']; 
            $patient_id = $row['patient_id']; 
        ?>
        <tr>
            <td><span class="fw-bold text-secondary">#<?php echo htmlspecialchars($booking_id); ?></span></td>
            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
            <td>
                <span class="badge bg-danger-subtle text-danger">
                    <i class="fa-solid fa-flask-vial me-1"></i> <?php echo htmlspecialchars($row['test_name'] ?? 'OPD Consultation'); ?>
                </span>
            </td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><i class="fa-regular fa-calendar me-1"></i> <?php echo htmlspecialchars($row['appointment_date'] ?? $row['scheduled_date']); ?></td>
            <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
            
            <td class="text-center">
                <?php if ($status === 'Completed') { ?>
                    <span class="text-success fw-bold small"><i class="fa-solid fa-circle-check me-1"></i> Completed & Prescribed</span>
                <?php } else { ?>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <form action="" method="POST" class="d-flex gap-1 m-0">
                            <input type="hidden" name="appointment_id" value="<?php echo $booking_id; ?>">
                            <select name="target_status" class="form-select form-select-sm" style="width: 110px;">
                                <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" <?php if($status == 'Processing') echo 'selected'; ?>>Processing</option>
                            </select>
                            <button type="submit" name="update_consultation_status_btn" class="btn btn-sm btn-primary py-1 px-2" title="Save Status">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </form>

                        <a href="write_prescription.php?patient_id=<?php echo $patient_id; ?>&appointment_id=<?php echo $booking_id; ?>" 
                           class="btn btn-sm btn-success fw-bold py-1 px-2">
                          Prescribe
                        </a>
                    </div>
                <?php } ?>
            </td>
        </tr>
        <?php 
        } 
    } else { ?>
        <tr>
            <td colspan="8" class="text-center py-4 text-muted">No appointments assigned to you yet.</td>
        </tr>
    <?php } ?>
</tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>