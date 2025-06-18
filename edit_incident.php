<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('Location: activity_rapport.php');
    exit;
}

$incident_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT i.*, o.nom, o.prenom 
    FROM incidents i
    JOIN officres o ON i.officer_id = o.id
    WHERE i.id = ?
");
$stmt->execute([$incident_id]);
$incident = $stmt->fetch();

if($incident['officer_id'] != $_SESSION['POLICIER']->id) {
    $error = "You do not have permission to edit this activity.";
    $activity = null; 

}

if(!$incident || ($_SESSION['POLICIER']->id !== $incident['officer_id'] && !in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander']))) {
    header('Location: activity_rapport.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    
    $update_stmt = $pdo->prepare("
        UPDATE incidents 
        SET type = ?, location = ?, description = ?, status = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $update_stmt->execute([$type, $location, $description, $status, $incident_id]);
    
    $_SESSION['success_message'] = "Incident updated successfully!";
    header("Location: view_incident.php?id=$incident_id");
    exit;
}

$currentPage = basename(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Incident | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .incident-form-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-in-progress {
            background-color: #0d6efd;
            color: white;
        }
        
        .badge-urgent {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header2.php'; ?>
    
    <div class="container-fluid main-content">
        <div class="row justify-content-center">
            <main class="col-12 col-lg-10">
                <div class="page-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-light"><i class="bi bi-pencil-square me-2"></i>Edit Incident Report</h2>
                        <a href="activity_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Incidents
                        </a>
                    </div>
                    
                    <div class="card incident-form-card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="type" class="form-label">Incident Type</label>
                                        <input type="text" class="form-control" id="type" name="type" 
                                               value="<?= htmlspecialchars($incident['type']) ?>" required maxlength="50">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="Pending" <?= $incident['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="In Progress" <?= $incident['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option value="Urgent" <?= $incident['status'] === 'Urgent' ? 'selected' : '' ?>>Urgent</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="<?= htmlspecialchars($incident['location']) ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="5"><?= htmlspecialchars($incident['description']) ?></textarea>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Save Changes
                                    </button>
                                    <a href="view_incident.php?id=<?= $incident_id ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>