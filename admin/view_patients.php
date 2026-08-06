<?php
session_start(); 


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { 
    echo "<script>alert('Access Denied! Admin authentication required.'); window.location.href='../auth/login.php';</script>"; 
    exit(); 
} 

include __DIR__ . '/../auth/db-config.php'; 

if (isset($_POST['update_role_btn'])) { 
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']); 
    $new_role = mysqli_real_escape_string($conn, $_POST['new_role']); 

    
    $update_query = "UPDATE patients SET role = '$new_role' WHERE id = '$patient_id'"; 

    if (mysqli_query($conn, $update_query)) { 

        echo "<script>alert('User role updated to $new_role successfully!'); window.location.href='view_patients.php';</script>"; 
    } else { 
        echo "<script>alert('Error updating role: " . mysqli_error($conn) . "');</script>"; 
    } 
} 

$patients_query = "SELECT id, name, email, phone, gender, created_at, role FROM patients ORDER BY id DESC"; 
$patients_result = mysqli_query($conn, $patients_query); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users List - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?> <div class="flex-grow-1 p-4 p-md-5" style="max-width: calc(100% - 260px);"> <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3 border-bottom pb-4"> <div>
                <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-users text-primary me-2"></i>Registered Users Registry</h2> <p class="text-muted mb-0">Viewing real-time server database logs for all registered Users profiles.</p> </div>
            <div class="bg-white px-3 py-2 rounded shadow-sm border small fw-semibold text-secondary"> Total User Counter: <span class="text-primary fw-bold"><?php echo mysqli_num_rows($patients_result); ?></span> </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white"> <div class="table-responsive"> <table class="table table-hover align-middle"> <thead class="table-light"> <tr>
                            <th>Patient ID</th> <th>Full Name</th> <th>Email Address</th> <th>Contact Number</th> <th>Gender</th> <th>Role</th> <th>Registration Date</th> </tr>
                    </thead>
                    <tbody>
                        <?php if($patients_result && mysqli_num_rows($patients_result) > 0) { ?> <?php while($patient = mysqli_fetch_assoc($patients_result)) { ?> <tr>
                                    <td class="fw-bold text-secondary"><?php echo $patient['id']; ?></td> <td class="fw-semibold text-dark"> <i class="fa-solid fa-circle-user text-secondary me-2"></i><?php echo $patient['name']; ?> </td>
                                    <td><a href="mailto:<?php echo $patient['email']; ?>" class="text-decoration-none text-muted"><?php echo $patient['email']; ?></a></td> <td><i class="fa-solid fa-phone small text-muted me-1"></i> <?php echo $patient['phone']; ?></td> <td>
                                        <span class="badge <?php echo ($patient['gender'] == 'Male') ? 'bg-blue text-primary-emphasis bg-primary-subtle' : 'bg-pink text-danger-emphasis bg-danger-subtle'; ?> px-2 py-1 rounded"> <?php echo $patient['gender']; ?> </span>
                                    </td>
                                    <td>
                                        <form action="" method="POST" class="d-flex gap-1 m-0 align-items-center"> <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>"> <select name="new_role" class="form-select form-select-sm" style="width: 140px;" required> <option value="patient" <?php if(($patient['role'] ?? 'patient') == 'patient') echo 'selected'; ?>>Patient</option> <option value="lab_doctor" <?php if(($patient['role'] ?? '') == 'lab_doctor') echo 'selected'; ?>>Lab Attendant</option> 
                                                <option value="doctor" <?php if(($patient['role'] ?? '') == 'doctor') echo 'selected'; ?>>Doctor</option> </select>
                                            
                                            <button type="submit" name="update_role_btn" class="btn btn-sm btn-success" title="Save Role"> <i class="fa-solid fa-check"></i> Save </button>
                                        </form>
                                    </td>
                                    <td class="text-muted small">
                                        <?php 
                                        echo isset($patient['created_at']) ? date('d-M-Y', strtotime($patient['created_at'])) : 'N/A'; 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?> <?php } else { ?> <tr>
                                <td colspan="7" class="text-center py-5 text-muted"> <i class="fa-solid fa-users-slash display-6 d-block mb-3 text-secondary"></i> No usrers registered on the server yet. </td>
                            </tr>
                        <?php } ?> </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>