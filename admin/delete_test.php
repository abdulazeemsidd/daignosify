<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    
    $delete_query = "DELETE FROM lab_tests WHERE test_id = $id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Specialist profile permanently removed!'); window.location.href='view_test.php';</script>";
    } else {
        echo "<script>alert('Error updating database records.'); window.location.href='view_test.php';</script>";
    }
} else {
    header("Location: view_test.php");
    exit();
}
?>