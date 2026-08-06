<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied!'); window.location.href='../auth/login.php';</script>";
    exit();
}

include __DIR__ . '/../auth/db-config.php';

if (isset($_GET['id'])) {
    $doc_id = intval($_GET['id']);
    
    
    $img_query = "SELECT image_url FROM doctors WHERE id = $doc_id";
    $img_result = mysqli_query($conn, $img_query);
    if ($img_row = mysqli_fetch_assoc($img_result)) {
        $file_path = "../images/" . $img_row['image_url'];
        if (file_exists($file_path) && !empty($img_row['image_url'])) {
            unlink($file_path); 
        }
    }
    
    
    $delete_query = "DELETE FROM doctors WHERE id = $doc_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Specialist profile permanently removed!'); window.location.href='view_doctors.php';</script>";
    } else {
        echo "<script>alert('Error updating database records.'); window.location.href='view_doctors.php';</script>";
    }
} else {
    header("Location: view_doctors.php");
    exit();
}
?>