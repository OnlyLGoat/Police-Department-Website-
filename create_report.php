<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['CIVIL'])) {
    header('Location: loginc.php');
    exit;
}

$currentPage = basename(__FILE__);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report = $_POST['report'];
    $civilian_id = $_SESSION['CIVIL']->id;

    try {
        $stmt = $pdo->prepare("INSERT INTO civiliant_rapport (rapport, id_civilians) VALUES (?, ?)");
        $stmt->execute([$report, $civilian_id]);
        
        $_SESSION['success'] = "Report submitted successfully!";
        header('Location: civiliant_rapport.php');
        exit;
    } catch(PDOException $e) {
        $error = "Error submitting report: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        
        .report-types {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .report-type-btn {
            flex: 1 0 45%;
        }
    </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header2.php'; ?>
    
    <div class="container-fluid main-content">
        <div class="row justify-content-center">
            <main class="col-12 col-lg-8">
                <div class="page-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-light"><i class="bi bi-plus-circle me-2"></i>Submit Report</h2>
                        <a href="civiliant_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                    
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <div class="form-container">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Report Type</label>
                                <div class="report-types">
                                    <button type="button" class="btn btn-outline-primary report-type-btn" onclick="setReportType('Theft')">
                                        <i class="bi bi-bag-x"></i> Theft
                                    </button>
                                    <button type="button" class="btn btn-outline-primary report-type-btn" onclick="setReportType('Vandalism')">
                                        <i class="bi bi-paint-bucket"></i> Vandalism
                                    </button>
                                    <button type="button" class="btn btn-outline-primary report-type-btn" onclick="setReportType('Suspicious Activity')">
                                        <i class="bi bi-eye"></i> Suspicious Activity
                                    </button>
                                    <button type="button" class="btn btn-outline-primary report-type-btn" onclick="setReportType('Noise Complaint')">
                                        <i class="bi bi-volume-up"></i> Noise Complaint
                                    </button>
                                    <button type="button" class="btn btn-outline-primary report-type-btn" onclick="setReportType('Parking Issue')">
                                        <i class="bi bi-car-front"></i> Parking Issue
                                    </button>
                                    <button type="button" class="btn btn-outline-primary report-type-btn" onclick="setReportType('Other')">
                                        <i class="bi bi-question-circle"></i> Other
                                    </button>
                                </div>
                                <input type="hidden" id="report_type" name="report_type">
                            </div>
                            
                            <div class="mb-3">
                                <label for="report" class="form-label">Report Details</label>
                                <textarea class="form-control" id="report" name="report" rows="6" required placeholder="Please provide detailed information about the incident..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required placeholder="Where did this occur?">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Submit Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function setReportType(type) {
            document.getElementById('report_type').value = type;
            document.querySelectorAll('.report-type-btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            event.target.classList.remove('btn-outline-primary');
            event.target.classList.add('active', 'btn-primary');
        }
    </script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>