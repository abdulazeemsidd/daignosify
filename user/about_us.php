<?php
session_start();
include __DIR__ . '/../auth/db-config.php';
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us & Clinical FAQs - Diagnosify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="bg-light">

    <header class="lnh-premium-about-hero text-center">
        <div class="container py-2">
            <div class="mb-3">
                <span class="lnh-tag-badge text-uppercase"><i class="fa-solid fa-building-shield me-1"></i> Established 2001 | 25 Years of Absolute Excellence</span>
            </div>
            <h1 class="display-5 fw-bold text-dark mb-3">About Diagnosify Medical Networks</h1>
            <p class="lead opacity-90 max-width-700 mx-auto">Driving high-tier automated pathology diagnostics, secure lab reporting matrices, and elite healthcare control systems across the region.</p>
        </div>
    </header>

    <main class="container my-5" style="max-width: 950px;">

        <section class="corporate-profile-card mb-5">
            <h3 class="fw-bold text-dark border-bottom pb-3 mb-4"><i class="fa-solid fa-hospital text-primary me-2"></i>Institutional Governance & Vision</h3>
            <p class="text-secondary">Diagnosify operates as a centralized healthcare conglomerate. Over the past 25 years, our medical installations have evolved from a core diagnostic facility into an integrated multi-department healthcare framework managing thousands of active patient matrices daily. We assert absolute oversight on all diagnostic loops, ensuring that advanced automated laboratory systems translate results into seamless clinical data pipelines without any manual evaluation failures.</p>
            <p class="text-secondary mb-4">Every biological assay distribution, processing queue, and consulting protocol inside our network complies strictly with international healthcare mandates. By maintaining encrypted database records, we ensure that digital report payloads remain globally verifiable and legally immutable for supreme medical validation boards.</p>
            
            <div class="row g-3 mt-3 text-center">
                <div class="col-6 col-sm-3">
                    <div class="metric-data-container">
                        <h4 class="fw-bold text-primary m-0">25</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Years Active Legacy</small>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="metric-data-container">
                        <h4 class="fw-bold text-primary m-0">99.9%</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Precision Metric</small>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="metric-data-container">
                        <h4 class="fw-bold text-primary m-0">100%</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Automated Encryption</small>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="metric-data-container">
                        <h4 class="fw-bold text-primary m-0">250K+</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Reports Issued</small>
                    </div>
                </div>
            </div>
        </section>

        <section class="corporate-faq-wrapper mb-5">
            <div class="text-center mb-5">
                <h3 class="fw-bold text-dark m-0"><i class="fa-solid fa-circle-question text-primary me-2"></i>Institutional Operations FAQ Matrix</h3>
                <p class="text-muted small mt-1">Review verified technical protocols governing the Diagnosify clinical database mainframes.</p>
            </div>

            <div class="accordion custom-faq-accordion" id="diagnosifyExtendedFaq">
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c1">
                            1. How can patients verify the live status of their diagnostic laboratory bookings?
                        </button>
                    </h2>
                    <div id="faq-c1" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Patients must utilize the unique numeric Booking ID generated during test allocation. Inputting this tracking number inside the "Track Status" interface fetches current processing parameters from the database.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c2">
                            2. Are the dynamic digital PDF medical reports legally authenticated for global use?
                        </button>
                    </h2>
                    <div id="faq-c2" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Yes. All clinical report payloads uploaded by verified administrators undergo precise system verification and carry valid digital parameters, making them acceptable for review boards worldwide.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c3">
                            3. What operational standard triggers an update from 'Pending' to 'Completed'?
                        </button>
                    </h2>
                    <div id="faq-c3" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            When an admin uploading professional uploads the validated PDF file via the backend dashboard, the system modifies the booking query status to 'Completed' and registers the exact timestamp automatically.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c4">
                            4. Can an appointment or laboratory booking target date be modified online?
                        </button>
                    </h2>
                    <div id="faq-c4" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            To ensure diagnostic queue integrity, active processing target dates cannot be manually altered post-submission. Patients must communicate directly with our helpline desks found on the contact node.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c5">
                            5. How does the system secure user authentication logs and patient profile privacy?
                        </button>
                    </h2>
                    <div id="faq-c5" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Our database enforces strict session validation architectures (`session_start()`). Patient identifiers and tracking profiles remain restricted behind encryption layers to block unauthorized data manipulation.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c6">
                            6. What should a patient do if they misplace their unique numeric Tracking Token?
                        </button>
                    </h2>
                    <div id="faq-c6" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Registered profiles can view their complete laboratory matrix archives by accessing the user dashboard. Alternatively, verification teams can retrieve the record via the patient's registered contact number.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c7">
                            7. Are laboratory tests managed by automated machinery or manual lab assistants?
                        </button>
                    </h2>
                    <div id="faq-c7" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Diagnosify minimizes manual evaluation risks by executing sample processing via high-end automated machinery. Senior pathology directors then audit and digitally approve the final result parameters.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h8">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c8">
                            8. Can multiple medical diagnostic tests be registered under a single tracking invoice?
                        </button>
                    </h2>
                    <div id="faq-c8" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Each laboratory procedure generates a standalone transactional tracking token inside the database layout to maintain specific department workflow tracking and avoid cross-contamination reporting.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h9">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c9">
                            9. What standard framework protocols protect uploaded PDF medical records?
                        </button>
                    </h2>
                    <div id="faq-c9" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Reports are assigned isolated filenames paired with random numeric strings before storage in our secure directories. This strategy prevents direct path discovery and secures medical records.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h10">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c10">
                            10. Is the Diagnosify interface fully optimized for mobile devices?
                        </button>
                    </h2>
                    <div id="faq-c10" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Yes. The user interface leverages Bootstrap grid systems coupled with optimized custom stylesheet engines to guarantee flawless rendering across all smartphone, tablet, and desktop viewports.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h11">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c11">
                            11. How are clinical specialist rosters verified before onboarding?
                        </button>
                    </h2>
                    <div id="faq-c11" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Every medical professional undergoes intensive background credential validation. Academic certifications, licenses, and clinical experiences are meticulously vetted before their profile goes live on our portal.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-h12">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c12">
                            12. What happens to clinical records and tracking logs if a query database disconnect occurs?
                        </button>
                    </h2>
                    <div id="faq-c12" class="accordion-collapse collapse" data-bs-parent="#diagnosifyExtendedFaq">
                        <div class="accordion-body">
                            Our database uses transactional safety mechanisms. In the event of a network disruption, current requests automatically roll back to their last stable state, ensuring zero data loss or database corruption.
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>