<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('Location: incident_rapport.php');
    exit;
}

$incident_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT i.*, o.nom, o.prenom, o.rank as officer_rank
    FROM incidents i
    JOIN officres o ON i.officer_id = o.id
    WHERE i.id = ?
");
$stmt->execute([$incident_id]);
$incident = $stmt->fetch();

if(!$incident) {
    header('Location: incident_rapport.php');
    exit;
}

$currentPage = basename(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Incident | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .incident-card {
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
        
        .incident-details {
            border-left: 4px solid #0d6efd;
            padding-left: 15px;
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
                        <h2 class="text-light"><i class="bi bi-eye me-2"></i>Incident Details</h2>
                        <div>
                            <a href="incident_rapport.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Incidents
                            </a>
                            <?php if($_SESSION['POLICIER']->id === $incident['officer_id'] || in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander'])): ?>
                            <a href="edit_incident.php?id=<?= $incident['id'] ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card incident-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 incident-details">
                                    <h3 class="mb-4"><?= htmlspecialchars($incident['type']) ?></h3>
                                    
                                    <div class="mb-4">
                                        <span class="badge <?= 
                                            $incident['status'] == 'Urgent' ? 'badge-urgent' : 
                                            ($incident['status'] == 'In Progress' ? 'badge-in-progress' : 'badge-pending')
                                        ?> fs-6">
                                            <?= $incident['status'] ?>
                                        </span>
                                        <span class="text-muted ms-2">
                                            Reported on <?= date('M d, Y \a\t H:i', strtotime($incident['created_at'])) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5><i class="bi bi-geo-alt"></i> Location</h5>
                                        <p><?= htmlspecialchars($incident['location']) ?></p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5><i class="bi bi-text-paragraph"></i> Description</h5>
                                        <p><?= nl2br(htmlspecialchars($incident['description'])) ?></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Officer Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-person-badge fs-1"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5><?= htmlspecialchars($incident['prenom'] . ' ' . $incident['nom']) ?></h5>
                                                    <span class="text-muted"><?= htmlspecialchars($incident['officer_rank']) ?></span>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <h5>Incident Details</h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <strong>ID:</strong> <?= $incident['id'] ?>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Last Updated:</strong> 
                                                    <?= $incident['updated_at'] ? date('M d, Y \a\t H:i', strtotime($incident['updated_at'])) : 'Not updated yet' ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>