<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    
    $delete_query = "DELETE FROM contact_queries WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Query is permanently removed!'); window.location.href='view_query.php';</script>";
    } else {
        echo "<script>alert('Error updating database records.'); window.location.href='view_query.php';</script>";
    }
} else {
    header("Location: view_query.php");
    exit();
}
?>