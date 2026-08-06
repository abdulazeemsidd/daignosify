<?php
session_start();
include __DIR__ . '/../auth/db-config.php'; 
include  'navbar.php'; 

if (!isset($_SESSION['patient_id'])) { 
    echo "<script>alert('Please login first!'); window.location.href='../auth/login.php';</script>"; 
    exit(); 
} 

$patient_id = $_SESSION['patient_id']; 


$test_query = "SELECT a.*, t.test_name, t.price, d.name AS assigned_doc 
               FROM appointments a 
               LEFT JOIN lab_tests t ON a.test_id = t.test_id 
               LEFT JOIN doctors d ON a.doctor_id = d.id
               WHERE a.patient_id = '$patient_id' AND a.test_id > 0 
               ORDER BY a.appointment_id DESC"; 
$test_result = mysqli_query($conn, $test_query); 


$doc_query = "SELECT a.*, d.name AS doc_name, d.department, d.experience FROM appointments a 
              LEFT JOIN doctors d ON a.doctor_id = d.id 
              WHERE a.patient_id = '$patient_id' AND a.doctor_id > 0 AND (a.test_id = 0 OR a.test_id IS NULL)
              ORDER BY a.appointment_id DESC";
$doc_result = mysqli_query($conn, $doc_query); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Diagnosify</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <link rel="stylesheet" href="../styling/style.css"> <style>
        body { background-color: #f4f6f9; }
        .dashboard-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); } 
        .nav-tabs .nav-link { border: none; font-weight: 600; color: #6c757d; padding: 12px 20px; } 
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 3px solid #0d6efd; background: transparent; }
    </style>
</head>
<body>
<div class="container my-5"> <div class="card p-4 dashboard-card bg-white mb-4"> <div class="d-flex justify-content-between align-items-center flex-wrap gap-3"> <div>
                <h3 class="fw-bold text-dark mb-1">🎉Welcome, <?php echo htmlspecialchars($_SESSION['patient_name']); ?>!</h3> <p class="text-muted mb-0">Manage and live-track all your diagnostic medical requests here.</p> </div>
            <div class="d-flex gap-2"> <a href="book_doctor.php" class="btn btn-primary fw-bold"><i class="fa-solid fa-user-md me-1"></i> Doctor/Tests Appointments</a> </div>
        </div>
    </div>

    <div class="card dashboard-card bg-white p-4"> <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist"> <li class="nav-item" role="presentation"> <button class="nav-link active" id="tests-tab" data-bs-toggle="tab" data-bs-target="#tests-pane" type="button" role="tab"><i class="fa-solid fa-vial me-2"></i>Lab Test Bookings</button> </li>
            <li class="nav-item" role="presentation"> <button class="nav-link" id="doctors-tab" data-bs-toggle="tab" data-bs-target="#doctors-pane" type="button" role="tab"><i class="fa-solid fa-user-doctor me-2"></i>Doctor Consultations</button> </li>
        </ul>

        <div class="tab-content" id="dashboardTabsContent"> <div class="tab-pane fade show active" id="tests-pane" role="tabpanel" aria-labelledby="tests-tab"> <div class="table-responsive"> <table class="table table-hover align-middle"> <thead class="table-light"> <tr>
                                <th>Tracking ID</th> <th>Test Name</th> <th>Assigned Doctor</th> <th>Appointment Date</th> <th>Price</th> <th>Current Status</th> <th class="text-center">Action</th> </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($test_result) > 0) { ?> <?php while ($row = mysqli_fetch_assoc($test_result)) { 
                                    $status = $row['status']; 
                                    $badge_class = ($status == 'Completed' || $status == 'Approved') ? 'bg-success' : (($status == 'Processing') ? 'bg-primary' : 'bg-warning text-dark'); 
                                    
                                    if (!empty($row['booking_id'])) { 
                                        $display_tracking_id = $row['booking_id']; 
                                        $download_param = $row['booking_id']; 
                                    } else {
                                        $display_tracking_id = "APT-00" . $row['appointment_id']; 
                                        $download_param = $row['appointment_id']; 
                                    }
                                ?>
                                    <tr>
                                        <td><span class="fw-bold text-secondary"><?php echo htmlspecialchars($display_tracking_id); ?></span></td> <td><span class="fw-semibold text-dark"><?php echo htmlspecialchars($row['test_name']); ?></span></td> <td>
                                            <?php if(!empty($row['assigned_doc'])) { ?> <span class="text-primary fw-semibold">Dr. <?php echo htmlspecialchars($row['assigned_doc']); ?></span> <?php } else { ?> <span class="text-muted small">Direct Lab Booking</span> <?php } ?>
                                        </td>
                                        <td><i class="fa-regular fa-calendar-days text-muted me-1"></i> <?php echo htmlspecialchars($row['appointment_date']); ?></td> <td class="text-success fw-bold">PKR <?php echo htmlspecialchars($row['price']); ?>/=</td> <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span></td> <td class="text-center"> <?php if ($status == 'Completed') { ?> <a href="download_report.php?booking_id=<?php echo urlencode($download_param); ?>"  class="btn btn-sm btn-success fw-bold px-3 rounded-pill"> <i class="fa-solid fa-file-arrow-down me-1"></i> Download Report </a>
                                            <?php } else { ?>
                                                <span class="text-muted small"><i class="fa-solid fa-clock me-1"></i> Under Process</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?> <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">You haven't booked any laboratory tests yet.</td> </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="doctors-pane" role="tabpanel" aria-labelledby="doctors-tab"> <div class="table-responsive"> <table class="table table-hover align-middle"> <thead class="table-light"> <tr>
                                <th>Tracking ID</th> <th>Doctor Name</th> <th>Department</th> <th>Experience</th> <th>Appointment Date</th> <th>Current Status</th> <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($doc_result) > 0) { ?> <?php while ($row = mysqli_fetch_assoc($doc_result)) { 
                                    $status = $row['status']; 
                                    $badge_class = ($status == 'Completed' || $status == 'Approved') ? 'bg-success' : (($status == 'Processing') ? 'bg-primary' : 'bg-warning text-dark'); 
                                    $appt_id = $row['appointment_id'];

                                    $presc_check = mysqli_query($conn, "SELECT id FROM prescriptions WHERE appointment_id = '$appt_id'"); 
                                    $has_prescription = mysqli_num_rows($presc_check) > 0; 
                                ?>
                                    <tr>
                                        <td><span class="fw-bold text-secondary"><?php echo htmlspecialchars($row['appointment_id']); ?></span></td> <td><span class="fw-bold text-primary">Dr. <?php echo htmlspecialchars($row['doc_name']); ?></span></td> <td><span class="badge bg-light text-secondary border"><?php echo htmlspecialchars($row['department']); ?></span></td> <td><span class="text-muted small"><i class="fa-solid fa-briefcase me-1"></i> <?php echo htmlspecialchars($row['experience']); ?></span></td> <td><i class="fa-regular fa-calendar-days text-muted me-1"></i> <?php echo htmlspecialchars($row['appointment_date']); ?></td> <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span></td> <td class="text-center"> <?php if ($has_prescription) { ?>
                                        
                                        <a href="view_prescription.php?appointment_id=<?php echo $appt_id; ?>"  class="btn btn-sm btn-outline-success fw-bold px-3 rounded-pill"> <i class="fa-solid fa-file-pdf me-1"></i> View Prescription </a>


                                            <?php } else { ?> <span class="text-muted small">Awaiting Checkup</span> <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?> <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">You haven't scheduled any doctor consultations yet.</td> </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
include 'footer.php'; ?> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 </body>
</html>