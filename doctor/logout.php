<?php
session_start();
unset($_SESSION['doctor_logged_in']);
unset($_SESSION['doctor_id']);
unset($_SESSION['doctor_name']);
unset($_SESSION['doctor_dept']);
session_destroy();
header("Location: ../auth/login.php");
exit();
?>