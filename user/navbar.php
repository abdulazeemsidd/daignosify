<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark custom-nav-theme sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-uppercase tracking-wider d-flex align-items-center" href="index.php">
            <i class="fa-solid fa-heart-pulse text-danger me-2"></i>Diagnosify
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#diagnosifyNav" aria-controls="diagnosifyNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="diagnosifyNav">
            <ul class="navbar-nav ms-auto align-items-center gap-3 pt-2 pt-lg-0">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="doctors.php" class="nav-link">Doctors</a></li>
                <li class="nav-item"><a href="book_doctor.php" class="nav-link text-warning fw-bold"><i class="fa-solid fa-calendar-check me-1"></i>Doctor Appointment</a></li>
                <li class="nav-item"><a href="../user/dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="index.php#test-catalog" class="nav-link">Book Test</a></li>
                <li class="nav-item"><a href="about_us.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact Us</a></li>
                
                <li class="nav-item dropdown ms-lg-2">
                    <?php if(isset($_SESSION['patient_id'])) { ?>
                        <a class="btn btn-warning btn-sm px-4 fw-bold rounded-pill shadow-sm text-dark dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['patient_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item py-2" href="../user/dashboard.php"><i class="fa-solid fa-gauge me-2 text-primary"></i>My Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger py-2" href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                        </ul>

                    <?php } elseif(isset($_SESSION['doctor_id'])) { ?>
                        <a class="btn btn-success btn-sm px-4 fw-bold rounded-pill shadow-sm text-white dropdown-toggle" href="#" id="doctorDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-md me-1"></i> <?php echo htmlspecialchars($_SESSION['doctor_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="doctorDropdown">
                            <li><a class="dropdown-item py-2" href="../doctor/dashboard.php"><i class="fa-solid fa-clipboard-list me-2 text-success"></i>Doctor Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger py-2" href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                        </ul>

                    <?php } else { ?>
                        <a class="btn btn-warning btn-sm px-4 fw-bold rounded-pill shadow-sm text-dark dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account Portal
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item py-2 fw-semibold" href="../auth/login.php"><i class="fa-solid fa-right-to-bracket me-2 text-primary"></i>Login Portal</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li><a class="dropdown-item py-2" href="../auth/register.php"><i class="fa-solid fa-user-plus me-2 text-info"></i>Register Patient</a></li>
                            <li><a class="dropdown-item py-2" href="../auth/register_doctor.php"><i class="fa-solid fa-user-doctor me-2 text-success"></i>Register Doctor</a></li>
                            <li><a class="dropdown-item py-2" href="../auth/register_lab.php"><i class="fa-solid fa-flask me-2 text-warning"></i>Register Lab Attendant</a></li>
                        </ul>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>