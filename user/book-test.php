<?php
session_start();
include __DIR__ . '/../auth/db-config.php';

$pre_selected_id = isset($_GET['pre_select']) ? intval($_GET['pre_select']) : '';

if (isset($_POST['quick_process_booking_btn'])) {
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
            echo "<script>alert('Failed to build user node environment.'); window.location.href='index.php';</script>";
            exit();
        }
    }

    
    $random_num = rand(10000, 99999);
    $generated_booking_id = "DIAG-" . $random_num;

    $test_id = mysqli_real_escape_string($conn, $_POST['target_test_id']);
    $target_date = mysqli_real_escape_string($conn, $_POST['target_date']);
    $status = "Pending";

    $book_query = "INSERT INTO appointments (patient_id, booking_id, test_id, appointment_date, status) VALUES ('$patient_id', '$generated_booking_id', '$test_id', '$target_date', '$status')";
    if (mysqli_query($conn, $book_query)) {
        echo "<script>alert('Booking Successful! Your Track ID is: " . $generated_booking_id . "'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$catalog_result = mysqli_query($conn, "SELECT * FROM lab_tests ORDER BY test_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instant Test Scheduling System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 680px;">
    <div class="card p-4 p-md-5 border-0 shadow rounded-3 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark"><i class="fa-solid fa-address-card text-primary me-2"></i>Laboratory Check-In Desk</h3>
            <p class="text-muted small">Please fill out the details required below to submit request.</p>
        </div>
        <form action="" method="POST">
            <?php if (!isset($_SESSION['patient_id'])) { ?>
                <div class="bg-light p-3 rounded-3 mb-4 border border-dashed">
                    <span class="badge bg-warning text-dark fw-bold mb-3"><i class="fa-solid fa-user-plus me-1"></i>New Patient Info</span>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Patient Full Name</label>
                            <input type="text" name="guest_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="guest_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Phone</label>
                            <input type="tel" name="guest_phone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Gender</label>
                            <select name="guest_gender" class="form-select" required>
                                <option value="">-- Choose Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="alert alert-success small fw-semibold mb-4">
                    <i class="fa-solid fa-circle-user fs-5"></i> Session Logged: <?php echo isset($_SESSION['patient_name']) ? $_SESSION['patient_name'] : 'Active Patient'; ?>
                </div>
            <?php } ?>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Target Diagnostics Parameter</label>
                <select name="target_test_id" class="form-select p-3" required>
                    <option value="">-- Choose Test Profile --</option>
                    <?php while($test = mysqli_fetch_assoc($catalog_result)) { ?>
                        <option value="<?php echo $test['test_id']; ?>" <?php if($pre_selected_id == $test['test_id']) echo 'selected'; ?>>
                            <?php echo $test['test_name']; ?> — (PKR <?php echo $test['price']; ?>/=)
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Operational Booking Target Date</label>
                <input type="date" name="target_date" class="form-control p-3" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" name="quick_process_booking_btn" class="btn btn-primary w-100 py-3 fw-bold border-0 shadow-sm">Submit Booking Request</button>
        </form>
    </div>
</div>
</body>
</html>