<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER']) || $_SESSION['POLICIER']->Acc_Verify !== 'Verify') {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $activity_type = $_POST['activity_type'];
    $zone = $_POST['zone'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    
    $officer_id = $_SESSION['POLICIER']->id;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO activities 
            (officer_id, activity_type, zone, status, description, time) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $officer_id,
            $activity_type,
            $zone,
            $status,
            $description
        ]);
        
        $_SESSION['success'] = "Activity report created successfully!";
        header('Location: activity_rapport.php');
        exit;
    } catch(PDOException $e) {
        $error = "Error creating activity: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Activity Report | Police Department</title>
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
                        <h2 class="text-light"><i class="bi bi-plus-circle me-2"></i>Create Activity Report</h2>
                        <a href="activity_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                    
                    <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-container">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Reporting Officer</label>
                                <input type="text" class="form-control" 
                                       value="<?= htmlspecialchars($_SESSION['POLICIER']->prenom . ' ' . $_SESSION['POLICIER']->nom) ?>" 
                                       readonly>
                                <small class="text-muted">Activities are automatically assigned to your account</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="activity_type" class="form-label">Activity Type *</label>
                                <select class="form-select" id="activity_type" name="activity_type" required>
                                    <option value="">Select activity type</option>
                                    <option value="Patrol">Patrol</option>
                                    <option value="Investigation">Investigation</option>
                                    <option value="Traffic Control">Traffic Control</option>
                                    <option value="Community Outreach">Community Outreach</option>
                                    <option value="Training">Training</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="zone" class="form-label">Zone *</label>
                                <input type="text" class="form-control" id="zone" name="zone" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
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