<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('Location: manage_cases.php');
    exit;
}

$case_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT c.*, 
           i.type as incident_type, 
           i.location, 
           i.description as incident_desc,
           o.nom as officer_nom, 
           o.prenom as officer_prenom,
           o.rank as officer_rank,
           o.callsign
    FROM cases c
    JOIN incidents i ON c.incident_id = i.id
    JOIN officres o ON c.assigned_officer = o.id
    WHERE c.id = ?
");
$stmt->execute([$case_id]);
$case = $stmt->fetch();

if(!$case) {
    header('Location: manage_cases.php');
    exit;
}

$currentPage = 'view_case_details.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Details | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .case-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .badge-open {
            background-color: #28a745;
        }
        
        .badge-investigation {
            background-color: #17a2b8;
        }
        
        .badge-closed {
            background-color: #6c757d;
        }
        
        .case-details {
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
                        <h2 class="text-light">
                            <i class="bi bi-folder me-2"></i>
                            Case Details: PD-<?= $case['id'] ?>
                        </h2>
                        <a href="manage_cases.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Cases
                        </a>
                    </div>
                    
                    <div class="card case-card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 case-details">
                                    <h3 class="mb-4"><?= htmlspecialchars($case['incident_type']) ?></h3>
                                    
                                    <div class="mb-4">
                                        <span class="badge <?= 
                                            $case['status'] == 'open' ? 'badge-open' : 
                                            ($case['status'] == 'under_investigation' ? 'badge-investigation' : 'badge-closed')
                                        ?> fs-5">
                                            <?= ucfirst(str_replace('_', ' ', $case['status'])) ?>
                                        </span>
                                        <span class="text-muted ms-2">
                                            <?= $case['status'] == 'closed' ? 
                                                'Closed on ' . date('M d, Y', strtotime($case['closed_at'])) : 
                                                'Opened on ' . date('M d, Y', strtotime($case['created_at'])) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5><i class="bi bi-geo-alt"></i> Incident Location</h5>
                                        <p><?= htmlspecialchars($case['location']) ?></p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5><i class="bi bi-journal-text"></i> Incident Description</h5>
                                        <p><?= nl2br(htmlspecialchars($case['incident_desc'])) ?></p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5><i class="bi bi-card-text"></i> Case Notes</h5>
                                        <p><?= nl2br(htmlspecialchars($case['notes'] ?? 'No additional notes')) ?></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Assigned Investigator</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-person-badge fs-1"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h4><?= htmlspecialchars($case['officer_prenom'] . ' ' . $case['officer_nom']) ?></h4>
                                                    <div class="text-muted"><?= htmlspecialchars($case['officer_rank']) ?></div>
                                                    <div class="text-muted">Callsign: <?= htmlspecialchars($case['callsign']) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Case Actions</h5>
                                        </div>
                                        <div class="card-body">
                                            <?php if($case['status'] != 'closed'): ?>
                                            <a href="close_case.php?id=<?= $case['id'] ?>" class="btn btn-danger w-100">
                                                <i class="bi bi-archive"></i> Close Case
                                            </a>
                                            <?php else: ?>
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="bi bi-lock"></i> Case Closed
                                            </button>
                                            <?php endif; ?>
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