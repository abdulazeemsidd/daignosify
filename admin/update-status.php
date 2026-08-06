<?php
session_start();
include __DIR__ . '/../auth/db-config.php';

if (isset($_POST['update_status_btn'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);

    if (!empty($appointment_id) && !empty($new_status)) {
        $update_query = "UPDATE appointments SET status = '$new_status' WHERE appointment_id = '$appointment_id'";
        
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Pipeline state updated successfully!'); window.location.href='../user/dashboard.php';</script>";
        } else {
            echo "<script>alert('Database Error encountered.'); window.location.href='../user/dashboard.php';</script>";
        }
    } else {
        echo "<script>window.location.href='dashboard.php';</script>";
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>