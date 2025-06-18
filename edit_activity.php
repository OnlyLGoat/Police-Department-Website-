<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER'])) {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);
$error = '';
$success = '';
$activity = null;
$officers = [];

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT a.*, o.nom, o.prenom 
            FROM activities a
            JOIN officres o ON a.officer_id = o.id
            WHERE a.id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $activity = $stmt->fetch();

        if($activity['officer_id'] != $_SESSION['POLICIER']->id) {
            $error = "You do not have permission to edit this activity.";
            $activity = null;

        }
        
        if(!$activity) {
            $error = "Activity not found";
        }
    } catch(PDOException $e) {
        $error = "Error fetching activity: " . $e->getMessage();
    }
} else {
    $error = "No activity ID specified";
}

try {
    $stmt = $pdo->prepare("SELECT id, nom, prenom FROM officres WHERE ACC_Verify = 'Verify' ORDER BY nom");
    $stmt->execute();
    $officers = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching officers: " . $e->getMessage();
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump(isset($_POST['delete']));
    if(isset($_POST['update_activity'])) {
        // Handle update
        $activity_id = $_POST['activity_id'];
        $officer_id = $_POST['officer_id'];
        $activity_type = $_POST['activity_type'];
        $zone = $_POST['zone'];
        $status = $_POST['status'];
        $description = $_POST['description'];
        
        try {
            $stmt = $pdo->prepare("
                UPDATE activities 
                SET officer_id = ?, activity_type = ?, zone = ?, status = ?, description = ?
                WHERE id = ?
            ");
            $stmt->execute([$officer_id, $activity_type, $zone, $status, $description, $activity_id]);
            
            $_SESSION['success'] = "Activity updated successfully!";
            header('Location: activity_rapport.php');
            exit;
        } catch(PDOException $e) {
            $error = "Error updating activity: " . $e->getMessage();
        }
    }
    elseif(isset($_POST['delete'])) {
        $activity_id = $_POST['activity_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
            $stmt->execute([$activity_id]);
            
            $_SESSION['success'] = "Activity deleted successfully!";
            header('Location: activity_rapport.php');
            exit;
        } catch(PDOException $e) {
            $error = "Error deleting activity: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Activity | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .edit-form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .status-badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }
        .badge-pending { background-color: #ffc107; color: #212529; }
        .badge-in-progress { background-color: #17a2b8; color: white; }
        .badge-completed { background-color: #28a745; color: white; }
        .badge-urgent { background-color: #dc3545; color: white; }
    </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header2.php'; ?>
    
    <div class="container-fluid main-content">
        <div class="row justify-content-center">
            <main class="col-12 col-lg-8">
                <div class="page-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-light"><i class="bi bi-pencil-square me-2"></i>Edit Activity</h2>
                        <a href="activity_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Activities
                        </a>
                    </div>
                    
                    <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($activity): ?>
                    <div class="edit-form-container">
                        <form method="POST">
                            <input type="hidden" name="activity_id" value="<?= $activity['id'] ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="officer_id" class="form-label">Officer</label>
                                    <select class="form-select" id="officer_id" name="officer_id" required>
                                        <?php foreach($officers as $officer): ?>
                                        <option value="<?= $officer['id'] ?>" <?= $officer['id'] == $activity['officer_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($officer['prenom'] . ' ' . $officer['nom']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="activity_type" class="form-label">Activity Type</label>
                                    <select class="form-select" id="activity_type" name="activity_type" required>
                                        <option value="Patrol" <?= $activity['activity_type'] == 'Patrol' ? 'selected' : '' ?>>Patrol</option>
                                        <option value="Investigation" <?= $activity['activity_type'] == 'Investigation' ? 'selected' : '' ?>>Investigation</option>
                                        <option value="Traffic Control" <?= $activity['activity_type'] == 'Traffic Control' ? 'selected' : '' ?>>Traffic Control</option>
                                        <option value="Community Outreach" <?= $activity['activity_type'] == 'Community Outreach' ? 'selected' : '' ?>>Community Outreach</option>
                                        <option value="Training" <?= $activity['activity_type'] == 'Training' ? 'selected' : '' ?>>Training</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="zone" class="form-label">Zone</label>
                                    <input type="text" class="form-control" id="zone" name="zone" 
                                           value="<?= htmlspecialchars($activity['zone']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="pending" <?= $activity['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="in_progress" <?= $activity['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="completed" <?= $activity['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="urgent" <?= $activity['status'] == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="5" required><?= htmlspecialchars($activity['description']) ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-3 d-md-flex justify-content-md-end">
                                <button type="submit" name="delete" class="btn btn-danger">
                                    <i class="bi bi-save"></i> Delete
                                </button>
                                <button type="submit" name="update_activity" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Changes
                                </button>
                            </div>

                        </form>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        No activity selected or activity not found. Please return to the <a href="activity_rapport.php">activities list</a>.
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('status').addEventListener('change', function() {
            const badge = document.getElementById('statusBadge');
            if(badge) {
                badge.className = 'badge-status badge-' + this.value.replace('_', '-');
                badge.textContent = this.options[this.selectedIndex].text;
            }
        });
    </script>
</body>
</html>