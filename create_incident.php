<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $officer_id = $_SESSION['POLICIER']->id;

    try {
        $stmt = $pdo->prepare("INSERT INTO incidents (officer_id, type, location, description, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$officer_id, $type, $location, $description, $status]);
        
        $_SESSION['success'] = "Incident report created successfully!";
        header('Location: incident_rapport.php');
        exit;
    } catch(PDOException $e) {
        $error = "Error creating incident report: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Incident Report | Police Department</title>
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
                        <h2 class="text-light"><i class="bi bi-plus-circle me-2"></i>Create Incident Report</h2>
                        <a href="incident_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                    
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <div class="form-container">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="type" class="form-label">Incident Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select incident type</option>
                                    <option value="Theft">Theft</option>
                                    <option value="Assault">Assault</option>
                                    <option value="Burglary">Burglary</option>
                                    <option value="Traffic Accident">Traffic Accident</option>
                                    <option value="Vandalism">Vandalism</option>
                                    <option value="Domestic Disturbance">Domestic Disturbance</option>
                                    <option value="Suspicious Activity">Suspicious Activity</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
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