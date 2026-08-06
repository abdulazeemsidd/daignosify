<?php
session_start();
include __DIR__ . '/../auth/db-config.php';
include __DIR__ . '/navbar.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if (isset($_POST['submit_contact_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
   
        $query = "INSERT INTO contact_queries (name, email, subject, message) 
                  VALUES ('$name', '$email', '$subject', '$message')";
        
        if (mysqli_query($conn, $query)) {
            
            
            $mail = new PHPMailer(true);

            try {
                
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'admin.diagnosify@gmail.com';        
                $mail->Password   = 'twjtjxhogalugeco';         
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

               
                $mail->setFrom('admin.diagnosify@gmail.com', 'Diagnosify Portal');
                $mail->addAddress('admin.diagnosify@gmail.com');       
               

                $mail->addReplyTo($email, $name);

              
                $mail->isHTML(true);
                $mail->Subject = "Diagnosify New Query: " . $subject;
                
               
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 200px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 8px;'>
                        <h2 style='color: #0f4c81;'>New Patient Inquiry Received</h2>
                        <hr style='border: 0; border-top: 1px solid #eee;'>
                        <p><strong>Patient Name:</strong> {$name}</p>
                        <p><strong>Patient Email:</strong> {$email}</p>
                        <p><strong>Subject:</strong> {$subject}</p>
                        <p style='background: #f9f9f9; padding: 15px; border-radius: 5px;'><strong>Message:</strong><br>" . nl2br($message) . "</p>
                        <hr style='border: 0; border-top: 1px solid #eee;'>
                        <small style='color: #777;'>This inquiry was submitted from the Diagnosify contact form.</small>
                    </div>
                ";

                $mail->send();
                
               
                echo "<script>alert('Thank you! Your message has been sent successfully. Our team will contact you soon.'); window.location.href='index.php';</script>";
                
            } catch (Exception $e) {
                
                echo "<script>alert('Data saved in DB, but email could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href='index.php';</script>";
            }

        } else {
            echo "<script>alert('Unable to send your message.');</script>";
        }
    } else {
        echo "<script>alert('Please fill all the fields before submitting.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body>

<div class="container my-5">
    <div class="row g-4 max-width" style="max-width: 1100px; margin: auto;">
        
        <div class="col-md-5">
            <div class="bg-white p-4 rounded-3 shadow-sm border h-100">
                <h3 style="color: #0f4c81;" class="fw-bold mb-3">Get In Touch</h3>
                <p class="text-muted small mb-4">Have any queries regarding laboratory tests or reports? Reach out to our synchronization helpdesk.</p>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="contact-info-icon me-3"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <h6 class="fw-bold m-0">Location</h6>
                        <span class="text-secondary small">Aptech Korangi, Karachi </span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="contact-info-icon me-3"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <h6 class="fw-bold m-0">Phone Support</h6>
                        <span class="text-secondary small">+92 3131735685</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="contact-info-icon me-3"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h6 class="fw-bold m-0">Email</h6>
                        <span class="text-secondary small">admin.diagnosify@gmail.com</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm border">
                <h3 style="color: #0f4c81;" class="fw-bold mb-4">
                    <i class="fa-regular fa-envelope me-2"></i>Send Us A Message
                </h3>
                
                <form action="contact.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Your Name</label>
                            <input type="text" name="name" class="form-control p-3" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control p-3" placeholder="name@example.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Subject</label>
                            <input type="text" name="subject" class="form-control p-3" placeholder="Query topic" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Message / Core Inquiry</label>
                            <textarea name="message" class="form-control p-3" rows="5" placeholder="Type your dynamic message details here..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="submit_contact_btn" class="btn btn-primary w-100 py-3 fw-bold border-0 shadow-sm" style="background-color: #0f4c81;">
                                <i class="fa-solid fa-paper-plane me-2"></i>Dispatch Inquiry Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>