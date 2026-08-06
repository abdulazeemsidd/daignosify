<?php
session_start();
include __DIR__ . '/../auth/db-config.php';
include 'navbar.php';


$query = "SELECT * FROM lab_tests ORDER BY test_name ASC LIMIT 9";
$result = mysqli_query($conn, $query);


$doc_query = "SELECT * FROM doctors WHERE status = 'Active' ORDER BY id DESC LIMIT 3";
$doc_result = mysqli_query($conn, $doc_query);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosify - Intelligent Healthcare Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
   
</head>
<body>

    
    <header class="video-hero text-center text-md-start">
        <video autoplay loop muted playsinline>
            <source src="https://assets.mixkit.co/videos/preview/mixkit-corridor-of-a-modern-hospital-42220-large.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay"></div>
        <div class="container hero-content text-center py-5">
            <span class="badge bg-success mb-3 px-3 py-2 fs-6 rounded-pill text-uppercase tracking-wider">Welcome to Diagnosify Hub</span>
            <h1 class="display-3 fw-bold mb-3">Advanced Care, Right at Your Fingertips</h1>
            <p class="fs-5 opacity-90 mb-4">Experience a world-class health facility equipped with futuristic clinical smart labs, ultra-modern emergency response corridors, and renowned specialized medical panels.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="book_doctor.php" class="btn btn-primary btn-lg fw-bold px-4 py-3 shadow"><i class="fa-solid fa-user-md me-2"></i> Consult Our Doctors</a>
                <a href="#test-catalog" class="btn btn-light btn-lg fw-bold px-4 py-3 text-primary shadow"><i class="fa-solid fa-flask me-2"></i> Explore Lab Tests</a>
            </div>
        </div>
    </header>

    
    <section class="container my-5 py-3">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-box p-4 shadow-sm h-100">
                    <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-clock-history text-primary me-2"></i>24/7 Operations Room</h5>
                    <p class="text-muted small mb-0">Our automated diagnostics corridors, emergency ICU layers, and tracking servers work continuously without down-times.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box p-4 shadow-sm h-100">
                    <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-shield-virus text-primary me-2"></i>ISO Zero-Error Standard</h5>
                    <p class="text-muted small mb-0">Equipped with highly optimized molecular bio-analyzers for extreme precision analytics across medical reports.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box p-4 shadow-sm h-100">
                    <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-truck-medical text-primary me-2"></i>Rapid Doorstep Diagnostics</h5>
                    <p class="text-muted small mb-0">Schedule sampling matrices directly online. Certified clinical phlebotomists deploy instantly to your targeted grid locations.</p>
                </div>
            </div>
        </div>
    </section>

  
    <section class="bg-white py-5 border-top border-bottom">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=800" class="img-fluid rounded-4 shadow" alt="Hospital Interface">
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase tracking-widest small d-block mb-2">Hospital Infrastructure</span>
                    <h2 class="fw-bold text-dark mb-4">Revolutionizing Healthcare Delivery Channels</h2>
                    <p class="text-muted mb-3">Diagnosify integrates direct patient-care mechanics with high-fidelity analytics modules. From private premium clinical suites and ventilated inpatient wards to sterile diagnostic scanning environments, we guarantee continuous medical safety.</p>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <div class="d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2"></i> <span class="fw-semibold small text-secondary">Premium Executive Suites</span></div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2"></i> <span class="fw-semibold small text-secondary">Modular Smart Operation Theaters</span></div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2"></i> <span class="fw-semibold small text-secondary">Advanced Digital Imaging Systems</span></div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2"></i> <span class="fw-semibold small text-secondary">Centralized Biobank Repositories</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container my-5 py-4">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold text-dark m-0"><i class="fa-solid fa-user-md text-primary me-2"></i>Verified Medical Directors</h2>
                <p class="text-muted m-0 small">Consult leading elite clinicians available directly within our scheduling grids.</p>
            </div>
            <a href="book_doctor.php" class="btn btn-outline-primary fw-bold rounded-pill">View All Doctors <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php if ($doc_result && mysqli_num_rows($doc_result) > 0) { 
                while($doc_row = mysqli_fetch_assoc($doc_result)) { ?>
                <div class="col-md-4">
                    <div class="card h-100 doc-premium-card shadow-sm bg-white p-4 text-center">
                        <div class="mb-3">
                            <img src="../images/<?php echo htmlspecialchars($doc_row['image_url']); ?>" class="doc-avatar-img shadow-sm" alt="Doctor profile" onerror="this.src='https://cdn-icons-png.flaticon.com/512/387/387561.png';">
                        </div>
                        <h5 class="fw-bold text-dark mb-1 text-capitalize"> <?php echo htmlspecialchars($doc_row['name']); ?></h5>
                        <span class="badge bg-light text-primary border border-primary-subtle px-3 py-1 rounded-pill mb-3"><?php echo htmlspecialchars($doc_row['department']); ?></span>
                        <p class="text-secondary small mb-3"><i class="fa-solid fa-briefcase me-1 text-muted"></i> Experience: <strong><?php echo htmlspecialchars($doc_row['experience']); ?></strong></p>
                        <a href="book_doctor.php?select_doc=<?php echo $doc_row['id']; ?>" class="btn btn-sm btn-primary w-100 py-2 rounded-3 mt-auto fw-bold"><i class="fa-regular fa-calendar-check me-1"></i> Secure Slot</a>
                    </div>
                </div>
            <?php } } else { ?>
                <div class="col-12 text-center text-muted">No medical specialists mapped inside server stack yet.</div>
            <?php } ?>
        </div>
    </section>


    <div class="container my-5" id="appointment-form-section">
        <div id="bookingDesk" class="interactive-form-container p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark m-0"><i class="fa-solid fa-receipt text-primary me-2"></i>Instant Test Check-In Desk</h3>
                    <p class="text-muted small m-0">Selected Core Module: <span id="selectedTestNameDisplay" class="fw-bold text-primary">None</span></p>
                </div>
                <button type="button" class="btn-close shadow-none" onclick="closeBookingDesk()"></button>
            </div>

            <form action="index.php" method="POST">
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

    
    <main class="container my-5" id="test-catalog">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark"><i class="fa-solid fa-flask text-primary me-2"></i>Available Laboratory Tests (<?php $all_test = "SELECT * FROM lab_tests ORDER BY test_name ASC"; $test_count = mysqli_query($conn, $all_test); echo mysqli_num_rows($test_count); ?>)</h2>
            <p class="text-muted">Click directly on any laboratory card item below to load dynamic details into the scheduler form.</p>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
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
       <div class="text-center mt-4">
    <a href="all_tests.php" class="btn btn-outline-primary btn-md px-5 fw-bold rounded-pill shadow-sm">
        View All Tests <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
</div>
    </main>

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
</html>


<?php
include 'footer.php';
?>