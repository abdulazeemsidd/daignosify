<?php
include __DIR__ . '/../auth/db-config.php';
include 'navbar.php';
$query = "SELECT * FROM doctors ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet Our Specialists - Diagnosify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body>


    <main class="container">
        <h2 class="section-title">Clinical Laboratory Specialists</h2>
        <p class="section-subtitle">Meet our certified professional panel managing multi-branch diagnostics across the network.</p>

        <div class="grid-layout">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($result)) { 
                    
                    $status = isset($row['status']) ? strtolower(trim($row['status'])) : 'active';
                ?>
                    <div class="custom-card">
                        
                        <div class="img-container">
                            <?php if ($status === 'active' || $status === '1') { ?>
                                <span class="status-badge-floating badge-active">
                                    <span class="status-dot"></span> Active
                                </span>
                            <?php } else { ?>
                                <span class="status-badge-floating badge-inactive">
                                    <span class="status-dot"></span> Inactive
                                </span>
                            <?php } ?>
                            
                            <img src="../images/<?php echo htmlspecialchars($row['image_url']); ?>" class="doc-img" alt="Doctor Pic">
                        </div>

                        <h3 class="doc-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="doc-dept"><?php echo htmlspecialchars($row['department']); ?></p>
                        <p class="doc-info"><i class="fa-solid fa-graduation-cap"></i> <?php echo htmlspecialchars($row['qualification']); ?></p>
                        <p class="doc-info"><i class="fa-solid fa-briefcase"></i> <?php echo htmlspecialchars($row['experience']); ?></p>
                        <span class="badge-time"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($row['timing']); ?></span>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-center text-muted w-100">No laboratory specialists registered yet.</p>
            <?php } ?>
        </div>
    </main>

<?php
include 'footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
