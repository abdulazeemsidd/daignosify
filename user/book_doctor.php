<?php
session_start();
include __DIR__ . '/../auth/db-config.php'; 
include 'navbar.php'; 

$selected_doctor_id = isset($_GET['select_doc']) ? intval($_GET['select_doc']) : 0; 

$doctor_info = null;
if ($selected_doctor_id > 0) {
    $doc_query = mysqli_query($conn, "SELECT * FROM doctors WHERE id = '$selected_doctor_id'"); 
    if ($doc_query && mysqli_num_rows($doc_query) > 0) { 
        $doctor_info = mysqli_fetch_assoc($doc_query); 
            }
}

if (isset($_POST['final_submit_booking_btn'])) { 
    if (isset($_SESSION['patient_id'])) { 
        $patient_id = $_SESSION['patient_id'];
    } else {
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
            echo "<script>alert('Failed to register patient profile.'); window.location.href='book_doctor.php';</script>"; 
            exit(); 
    }
    }
    $random_num = rand(10000, 99999); 
    $generated_booking_id = "DOC-" . $random_num; 

    $doctor_id = intval($_POST['target_doctor_id']); 
    $target_date = mysqli_real_escape_string($conn, $_POST['target_date']); 
    
    
    $booking_purpose = mysqli_real_escape_string($conn, $_POST['booking_purpose']);
    $test_id = ($booking_purpose === 'Test') ? intval($_POST['selected_test_id']) : 0; 
    
    $status = "Pending"; 

    $book_query = "INSERT INTO appointments (patient_id, booking_id, doctor_id, test_id, appointment_date, status) VALUES ('$patient_id', '$generated_booking_id', '$doctor_id', '$test_id', '$target_date', '$status')"; 
    
    if (mysqli_query($conn, $book_query)) { 
        echo "<script>alert('Awesome! Appointment Scheduled.\\n\\nTracking ID: " . $generated_booking_id . "'); window.location.href='dashboard.php';</script>"; 
    } else {
        echo "<script>alert('SQL Error: " . mysqli_error($conn) . "');</script>"; 
    }
};

$all_doctors = mysqli_query($conn, "SELECT * FROM doctors ORDER BY id DESC"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find & Book Verified Specialists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
    <style>
        body { background-color: #f4f6f9; }
        .doc-card { border: none; transition: transform 0.2s, box-shadow 0.2s; border-radius: 15px; overflow: hidden; }
        .doc-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .doc-avatar { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #0d6efd; padding: 3px; }
    </style>
</head>
<body>

<div class="container my-5">
    
    <?php if (!$doctor_info) { ?>
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark"><i class="fa-solid fa-user-doctor text-primary me-2"></i>Meet Our Expert Specialists</h2>
            <p class="text-muted">Select any verified panel specialist below to launch instant reservation scheduling.</p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php 
            if ($all_doctors && mysqli_num_rows($all_doctors) > 0) {
                while($doc = mysqli_fetch_assoc($all_doctors)) { 
                    $doc_status = isset($doc['status']) ? $doc['status'] : 'Active'; 
                    if ($doc_status === 'Inactive') continue; 
            ?>
                <div class="col">
                    <div class="card h-100 doc-card shadow-sm bg-white text-center p-4">
                        <div class="mb-3">
                            <img src="../images/<?php echo htmlspecialchars($doc['image_url']); ?>" class="doc-avatar shadow-sm" alt="Dr. Photo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/387/387561.png';"> 
                        </div>
                        <div class="card-body p-0">
                            <h5 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($doc['name']); ?></h5> 
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 mb-3">
                                <?php echo htmlspecialchars(isset($doc['department']) ? $doc['department'] : $doc['specialization']); ?> 
                            </span>
                            
                            <p class="text-muted small mb-2"><i class="fa-solid fa-graduation-cap me-1"></i> <?php echo htmlspecialchars($doc['qualification']); ?></p> 
                            <p class="text-secondary small mb-3"><i class="fa-regular fa-clock me-1"></i> Timing: <?php echo htmlspecialchars($doc['timing']); ?></p> 
                            
                            <div class="border-top pt-3 mb-3 d-flex justify-content-around text-center small">
                                <div>
                                    <span class="text-muted d-block small">Consultation Fee</span>
                                    <strong class="text-success fs-6">PKR <?php echo htmlspecialchars($doc['fees']); ?>/=</strong> 
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-0 mt-2">
                            <a href="book_doctor.php?select_doc=<?php echo $doc['id']; ?>" class="btn btn-primary w-100 fw-bold py-2 rounded-3"> 
                                <i class="fa-solid fa-calendar-check me-1"></i> Book Appointment
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<div class='col-12 text-center text-muted py-5'><h5>No active doctors found on the server module.</h5></div>"; 
            }
            ?>
        </div>
    <?php } else { ?>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <a href="book_doctor.php" class="btn btn-sm btn-outline-secondary fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Back to Doctors List</a> 
                </div>
                
                <div class="card p-4 p-md-5 border-0 shadow rounded-3 bg-white">
                    <div class="text-center mb-4">
                        <img src="../images/<?php echo htmlspecialchars($doctor_info['image_url']); ?>" class="doc-avatar mb-3" alt="Dr. Photo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/387/387561.png';"> 
                        <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($doctor_info['name']); ?></h4> 
                        <p class="text-primary small fw-semibold mb-2"><?php echo htmlspecialchars(isset($doctor_info['department']) ? $doctor_info['department'] : $doctor_info['specialization']); ?></p> 
                        <p class="text-muted small mb-0"><i class="fa-regular fa-clock me-1"></i> <?php echo htmlspecialchars($doctor_info['timing']); ?> | Fee: <strong class="text-success">PKR <?php echo htmlspecialchars($doctor_info['fees']); ?>/=</strong></p> 
                    </div>

                    <form action="" method="POST">
                        <input type="hidden" name="target_doctor_id" value="<?php echo $doctor_info['id']; ?>"> 

                        <?php if (!isset($_SESSION['patient_id'])) { ?> 
                            <div class="bg-light p-3 rounded-3 mb-4 border">
                                <span class="badge bg-warning text-dark fw-bold mb-3">Patient Account Setup</span> 
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Full Name</label> 
                                        <input type="text" name="guest_name" class="form-control" required> 
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Email</label> 
                                        <input type="email" name="guest_email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Phone</label> 
                                        <input type="tel" name="guest_phone" class="form-control" required> 
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Gender</label> 
                                        <select name="guest_gender" class="form-select" required> 
                                            <option value="">-- Select --</option>
                                            <option value="Male">Male</option> 
                                            <option value="Female">Female</option> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-success small fw-semibold mb-4 text-center">
                                <i class="fa-solid fa-user me-1"></i> Booking appointment for: <strong><?php echo $_SESSION['patient_name']; ?></strong>
                            </div>
                        <?php } ?>

                        
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Appointment Purpose</label>
                            <select name="booking_purpose" id="bookingPurpose" class="form-select p-3 fw-bold" required>
                                <option value="Consultation">For General Consultation</option>
                                
                            </select>
                        </div>

                        
                        <div class="mb-4" id="testSelectionContainer" style="display: none;">
                            <label class="form-label small fw-semibold text-danger">Select Diagnostics Lab Test Catalog</label>
                            
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Choose Appointment Date</label>
                            <input type="date" name="target_date" class="form-control p-3" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <button type="submit" name="final_submit_booking_btn" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                            <i class="fa-solid fa-check-circle me-1"></i> Confirm Booking With <?php echo htmlspecialchars($doctor_info['name']); ?>
                            </button>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

</div>


<script>
document.getElementById('bookingPurpose').addEventListener('change', function() {
    var testContainer = document.getElementById('testSelectionContainer');
    if (this.value === 'Test') {
        testContainer.style.display = 'block';
        testContainer.querySelector('select').setAttribute('required', 'required');
    } else {
        testContainer.style.display = 'none';
        testContainer.querySelector('select').removeAttribute('required');
    }
});
</script>

</body>
</html>

<?php
include 'footer.php'; 
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>