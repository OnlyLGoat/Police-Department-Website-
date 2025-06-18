<?php
include 'connect.php';
session_start();

$allowed_ranks = ['Sergeant', 'Lieutenant', 'Commander'];
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, $allowed_ranks)) {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);

$error = '';
$success = '';
$incidents = [];
$officers = [];

try {
    $stmt = $pdo->prepare("SELECT i.id, i.type, i.location, i.created_at 
        FROM incidents i
        LEFT JOIN cases c ON i.id = c.incident_id
        WHERE c.id IS NULL OR c.status IN ('Pending', 'In Progress')
        ORDER BY i.created_at DESC");
    $stmt->execute();
    $incidents = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT * FROM officres');
    $stmt->execute();
    $officers = $stmt->fetchAll();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $incident_id = $_POST['incident_id'] ?? '';
        $assigned_officer = $_POST['assigned_officer'] ?? '';
        $priority = $_POST['priority'] ?? 'medium';
        $notes = $_POST['notes'] ?? '';
        
        if(empty($incident_id) || empty($assigned_officer)) {
            throw new Exception("Please fill all required fields");
        }
        
        $stmt = $pdo->prepare("SELECT id FROM cases WHERE incident_id = ? AND status IN ('open', 'under_investigation')");
        $stmt->execute([$incident_id]);
        if($stmt->rowCount() > 0) {
            throw new Exception("This incident already has an active case");
        }

        $stmt = $pdo->prepare('INSERT INTO cases(incident_id, assigned_officer, status, notes, created_at) VALUES(?, ?, "open", ?, CURDATE())');
        $stmt->execute([
            $incident_id,
            $assigned_officer,
            $notes,
        ]);
        
        $stmt = $pdo->prepare("UPDATE incidents SET status = 'in_progress' WHERE id = ?");
        $stmt->execute([$incident_id]);
        
        $success = "Case opened successfully!";
        $_SESSION['success'] = $success;
        header('Location: open_cases.php');
        exit;
    }
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "A database error occurred. Please try again.";
} catch(Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open New Case | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .priority-low { color: #28a745; }
        .priority-medium { color: #ffc107; }
        .priority-high { color: #dc3545; }
        .incident-option {
            display: flex;
            justify-content: space-between;
        }
        .incident-details {
            font-size: 0.9rem;
            color: #6c757d;
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
                        <h2 class="text-light"><i class="bi bi-plus-circle me-2"></i>Open New Case</h2>
                        <a href="open_cases.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Cases
                        </a>
                    </div>
                    
                    <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-container">
                        <form method="POST" id="caseForm">
                            <div class="mb-3">
                                <label for="incident_id" class="form-label">Related Incident *</label>
                                <select class="form-select" id="incident_id" name="incident_id" required>
                                    <option value="">Select incident</option>
                                    <?php foreach($incidents as $incident): 
                                        $incidentDate = date('M d, Y', strtotime($incident['created_at']));
                                    ?>
                                    <option value="<?= $incident['id'] ?>">
                                        <?= htmlspecialchars($incident['type']) ?> - 
                                        <?= htmlspecialchars($incident['location']) ?> (<?= $incidentDate ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Only incidents without cases or with active cases are shown</div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="assigned_officer" class="form-label">Assign To Officer *</label>
                                    <select class="form-select" id="assigned_officer" name="assigned_officer" required>
                                        <option value="">Select officer</option>
                                        <?php foreach($officers as $officer): ?>
                                        <option value="<?= $officer['id'] ?>">
                                            <?= htmlspecialchars($officer['rank'] . ' ' . $officer['prenom'] . ' ' . $officer['nom']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="priority" class="form-label">Priority *</label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low" class="priority-low">Low Priority</option>
                                        <option value="medium" class="priority-medium" selected>Medium Priority</option>
                                        <option value="high" class="priority-high">High Priority</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Initial Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" 
                                          placeholder="Enter case details, initial findings, or special instructions..."></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-outline-secondary me-md-2">
                                    <i class="bi bi-eraser"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-folder-plus"></i> Open Case
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prioritySelect = document.getElementById('priority');
            prioritySelect.addEventListener('change', function() {
                this.className = 'form-select priority-' + this.value;
            });
            
            prioritySelect.className = 'form-select priority-' + prioritySelect.value;
        });
    </script>
</body>
</html>