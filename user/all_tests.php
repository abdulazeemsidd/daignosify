<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../auth/db-config.php';
if (isset($_POST['instant_book_btn'])) {
    $test_id = mysqli_real_escape_string($conn, $_POST['test_id']);
    $target_date = mysqli_real_escape_string($conn, $_POST['target_date']);
    $status = "Pending";

    if (!isset($_SESSION['patient_id'])) {
        $p_name = mysqli_real_escape_string($conn, $_POST['guest_name']);
        $p_email = mysqli_real_escape_string($conn, $_POST['guest_email']);
        $p_phone = mysqli_real_escape_string($conn, $_POST['guest_phone']);
        $p_gender = mysqli_real_escape_string($conn, $_POST['guest_gender']);
        $p_pass = "patient123"; 

        $create_user = "INSERT INTO patients (name, email, phone, password, gender) VALUES ('$p_name', '$p_email', '$p_phone', '$p_pass', '$p_gender')";
        if (mysqli_query($conn, $create_user)) {
            $patient_id = mysqli_insert_id($conn);
            $_SESSION['patient_id'] = $patient_id;
            $_SESSION['patient_name'] = $p_name;
        } else {
            echo "<script>alert('Failed to register patient profile.');</script>";
        }
    } else {
        $patient_id = $_SESSION['patient_id'];
    }

    if (isset($patient_id)) {
        $book_query = "INSERT INTO appointments (patient_id, test_id, appointment_date, status) VALUES ('$patient_id', '$test_id', '$target_date', '$status')";
        if (mysqli_query($conn, $book_query)) {
            echo "<script>alert('Awesome! Your Test has been scheduled successfully.'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Booking database error.');</script>";
        }
    }
}


$query = "SELECT * FROM lab_tests ORDER BY test_name ASC ";
$all_tests = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosify - All Laboratory Tests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
   
</head>
<body>


<?php include 'navbar.php'; ?>

<div class="container " id="appointment-form-section">
    <div id="bookingDesk" class="interactive-form-container p-4 p-md-5" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark m-0"><i class="fa-solid fa-receipt text-primary me-2"></i>Instant Test Check-In Desk</h3>
                <p class="text-muted small m-0">Selected Core Module: <span id="selectedTestNameDisplay" class="fw-bold text-primary">None</span></p>
            </div>
            <button type="button" class="btn-close shadow-none" onclick="closeBookingDesk()"></button>
        </div>

        <form action="all_tests.php" method="POST">
            <input type="hidden" name="test_id" id="hiddenTestId">

            <?php if(!isset($_SESSION['patient_id'])) { ?>
                <div class="p-3 bg-light rounded-3 mb-4 border border-dashed">
                    <p class="fw-bold text-secondary small mb-3"><i class="fa-solid fa-id-card me-1"></i> Quick Patient Profile Synchronization</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Patient Full Name</label>
                            <input type="text" name="guest_name" class="form-control" placeholder="e.g. Alaina Ahmed" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email Node</label>
                            <input type="email" name="guest_email" class="form-control" placeholder="name@domain.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Mobile</label>
                            <input type="tel" name="guest_phone" class="form-control" placeholder="03XXXXXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Gender</label>
                            <select name="guest_gender" class="form-select" required>
                                <option value="">Choose Axis</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row align-items-end g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold text-secondary">Target Appointment Schedule Date</label>
                    <input type="date" name="target_date" class="form-control p-3" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="instant_book_btn" class="btn btn-success w-100 p-3 fw-bold shadow-sm">
                        <i class="fa-solid fa-check-circle me-1"></i> Finalize Test Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark"><i class="fa-solid fa-vials text-danger me-2"></i>Complete Laboratory Catalog</h2>
        <p class="text-muted">Browse through our complete list of clinical profile arrays and diagnostics.</p>
    </div>

   <div class="row g-4">
            <?php if (mysqli_num_rows($all_tests) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($all_tests)) { ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 test-card shadow-sm p-4 bg-white" 
                           onclick="openBookingDesk('<?php echo $row['test_id']; ?>', '<?php echo mysqli_real_escape_string($conn, $row['test_name']); ?>')">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">Active Server Token</span>
                                <div class="text-primary opacity-50 fs-4"><i class="fa-solid fa-microscope"></i></div>
                            </div>
                            <h5 class="fw-bold text-dark mb-2"><?php echo $row['test_name']; ?></h5>
                            <div class="fs-4 fw-bold text-success mb-3">PKR <?php echo $row['price']; ?>/=</div>
                            <div class="text-secondary small border-top pt-2 mt-auto d-flex align-items-center justify-content-between">
                                <span class="fw-medium text-primary fw-bold">Click to Book Test <i class="fa-solid fa-bolt ms-1 text-warning"></i></span>
                                <i class="fa-solid fa-arrow-right-long text-primary"></i>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fa-solid fa-circle-exclamation display-5 d-block mb-3"></i> No testing profiles initialized inside database engine yet.
                </div>
            <?php } ?>
        </div>
</div>


 <script>
    function openBookingDesk(testId, testName) {
        document.getElementById('hiddenTestId').value = testId;
        document.getElementById('selectedTestNameDisplay').innerText = testName;
        
        var desk = document.getElementById('bookingDesk');
        desk.style.display = 'block';
        
        
        document.getElementById('appointment-form-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function closeBookingDesk() {
        document.getElementById('bookingDesk').style.display = 'none';
    }
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

<?php
include 'footer.php';
?>
</html>