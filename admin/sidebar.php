<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 260px; min-height: 100vh; box-shadow: 4px 0 10px rgba(0,0,0,0.1);">
    <a href="dashboard_admin.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none gap-2">
        <i class="fa-solid fa-screwdriver-wrench text-warning fs-4"></i>
        <span class="fs-4 fw-bold text-uppercase tracking-wider">Diagnosify</span>
    </a>
    <hr class="border-secondary">
    
    <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item">
            <a href="dashboard_admin.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-clipboard-list"></i> Test Bookings Panel
            </a>
        </li>
        <li>
            <a href="view_patients.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'view_patients.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-users"></i> Users Database
            </a>
        </li>

         <li>
            <a href="add-doctor.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'add-doctor.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-address-book"></i> Register Doctor
            </a>
        </li>

        <li>
            <a href="view_doctors.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'view_doctors.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-address-book"></i> Doctors Directory
            </a>
        </li>
       
        <li>
            <a href="add-test.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'add-test.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-square-plus"></i> Register New Test
            </a>
        </li>

           <li>
            <a href="view_test.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'view_test.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-square-plus"></i> Test Directory
            </a>
        </li>

        <li>
            <a href="view_query.php" class="nav-link text-white py-2 px-3 d-flex align-items-center gap-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'view_query.php') ? 'active bg-primary' : 'opacity-75'; ?>">
                <i class="fa-solid fa-square-plus"></i> All Contact Queries 
            </a>
        </li>

    </ul>
    
    <hr class="border-secondary">
    <div class="pb-2">
        <a href="../auth/login.php" class="btn btn-danger w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
            <i class="fa-solid fa-power-off"></i> Terminate Session
        </a>
    </div>
</div>