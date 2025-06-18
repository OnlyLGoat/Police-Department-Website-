<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plate_number = $_POST['plate_number'];
    $reason = $_POST['reason'];
    $officer_id = $_SESSION['POLICIER']->id;

    try {
        $stmt = $pdo->prepare("INSERT INTO vehicles (plate_number, status, impound_date, officer_id, reason) VALUES (?, 'impounded', NOW(), ?, ?)");
        $stmt->execute([$plate_number, $officer_id, $reason]);
        
        $_SESSION['success'] = "Vehicle impound report created successfully!";
        header('Location: impond_rapport.php');
        exit;
    } catch(PDOException $e) {
        $error = "Error creating impound report: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Impound Report | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
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
                        <h2 class="text-light"><i class="bi bi-plus-circle me-2"></i>Create Impound Report</h2>
                        <a href="impond_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                    
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <div class="form-container">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="plate_number" class="form-label">Plate Number</label>
                                <input type="text" class="form-control" id="plate_number" name="plate_number" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Impound</label>
                                <select class="form-select" id="reason" name="reason" required>
                                    <option value="">Select reason</option>
                                    <option value="Stolen Vehicle">Stolen Vehicle</option>
                                    <option value="Evidence">Evidence</option>
                                    <option value="Abandoned">Abandoned</option>
                                    <option value="Parking Violation">Parking Violation</option>
                                    <option value="Unregistered">Unregistered</option>
                                    <option value="Suspected Involvement in Crime">Suspected Involvement in Crime</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Submit Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>