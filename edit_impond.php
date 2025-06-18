<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('Location: impounds.php');
    exit;
}

$impound_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT i.*, o.nom AS officer_nom, o.prenom AS officer_prenom
    FROM vehicles i
    JOIN officres o ON i.officer_id = o.id
    WHERE i.id = ?
");
$stmt->execute([$impound_id]);
$impound = $stmt->fetch();

if($impound['officer_id'] != $_SESSION['POLICIER']->id) {
    $error = "You do not have permission to edit this activity.";
    $activity = null;

}

if(!$impound || ($_SESSION['POLICIER']->id !== $impound['officer_id'] && !in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander']))) {
    header('Location: impounds.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate_number = $_POST['plate_number'];
    $status = $_POST['status'];
    $reason = $_POST['reason'];
    $release_date = $_POST['release_date'];

    
    $update_stmt = $pdo->prepare("
        UPDATE vehicles
        SET plate_number = ?, status = ?, reason = ?, release_date = ?
        WHERE id = ?
    ");
    $update_stmt->execute([
        $plate_number, 
        $status, 
        $reason,
        $impound_id,
        $release_date,
    ]);
    
    $_SESSION['success_message'] = "Impound record updated successfully!";
    header("Location: view_impound.php?id=$impound_id");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Impound | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .impound-form-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-section {
            border-left: 4px solid #0d6efd;
            padding-left: 15px;
            margin-bottom: 20px;
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
                        <h2 class="text-light"><i class="bi bi-pencil-square me-2"></i>Edit Impound Record</h2>
                        <a href="view_impound.php?id=<?= $impound_id ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to View
                        </a>
                    </div>
                    
                    <div class="card impound-form-card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-section">
                                    <h4><i class="bi bi-car-front"></i> Vehicle Information</h4>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="plate_number" class="form-label">Plate Number</label>
                                            <input type="text" class="form-control" id="plate_number" name="plate_number" 
                                                   value="<?= htmlspecialchars($impound['plate_number']) ?>" required maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-section">
                                    <h4><i class="bi bi-clipboard-check"></i> Impound Status</h4>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="normal" <?= $impound['status'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                                                <option value="impounded" <?= $impound['status'] === 'impounded' ? 'selected' : '' ?>>Impounded</option>
                                                <option value="released" <?= $impound['status'] === 'released' ? 'selected' : '' ?>>Released</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="impound_date" class="form-label">Impound Date</label>
                                            <input type="datetime-local" class="form-control" id="impound_date" name="impound_date" 
                                                   value="<?= $impound['impound_date'] ? date('Y-m-d\TH:i', strtotime($impound['impound_date'])) : '' ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="release_date" class="form-label">Release Date</label>
                                            <input type="datetime-local" class="form-control" id="release_date" name="release_date" 
                                                   value="<?= $impound['release_date'] ? date('Y-m-d\TH:i', strtotime($impound['release_date'])) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-section">
                                    <h4><i class="bi bi-exclamation-triangle"></i> Impound Details</h4>
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for Impound</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="4" required><?= htmlspecialchars($impound['reason']) ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Save Changes
                                    </button>
                                    <a href="view_impound.php?id=<?= $impound_id ?>" class="btn btn-outline-secondary">
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
    <script>
        document.getElementById('status').addEventListener('change', function() {
            const status = this.value;
            const impoundDate = document.getElementById('impound_date');
            const releaseDate = document.getElementById('release_date');
            
            if(status === 'impounded') {
                impoundDate.disabled = false;
                releaseDate.disabled = true;
                releaseDate.value = '';
            } else if(status === 'released') {
                impoundDate.disabled = false;
                releaseDate.disabled = false;
            } else {
                impoundDate.disabled = true;
                releaseDate.disabled = true;
                impoundDate.value = '';
                releaseDate.value = '';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('status').dispatchEvent(new Event('change'));
        });
    </script>
</body>
</html>