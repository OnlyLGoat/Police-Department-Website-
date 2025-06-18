<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('Location: impond_rapport.php');
    exit;
}

$impond_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT i.*, o.nom AS officer_nom, o.prenom AS officer_prenom, o.rank AS officer_rank
    FROM vehicles i
    JOIN officres o ON i.officer_id = o.id
    WHERE i.id = ?
");
$stmt->execute([$impond_id]);
$impond = $stmt->fetch();

if(!$impond) {
    header('Location: impond_rapport.php');
    exit;
}

$currentPage = basename(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View impond | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .impond-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .badge-normal {
            background-color: #6c757d;
        }
        
        .badge-imponded {
            background-color: #dc3545;
        }
        
        .badge-released {
            background-color: #198754;
        }
        
        .impond-details {
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
                        <h2 class="text-light"><i class="bi bi-car-front me-2"></i>impond Details</h2>
                        <div>
                            <a href="impond_rapport.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to imponds
                            </a>
                            <?php if($_SESSION['POLICIER']->id === $impond['officer_id'] || in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander'])): ?>
                            <a href="edit_impond.php?id=<?= $impond['id'] ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card impond-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 impond-details">
                                    <h3 class="mb-4">impond Record #<?= $impond['id'] ?></h3>
                                    
                                    <div class="mb-4">
                                        <span class="badge <?= 
                                            $impond['status'] === 'normal' ? 'badge-normal' : 
                                            ($impond['status'] === 'imponded' ? 'badge-imponded' : 'badge-released')
                                        ?> fs-6">
                                            <?= ucfirst($impond['status']) ?>
                                        </span>
                                        <span class="text-muted ms-2">
                                            Created on <?= date('M d, Y \a\t H:i', strtotime($impond['created_at'])) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h5><i class="bi bi-car-front"></i> Vehicle Details</h5>
                                            <ul class="list-unstyled">
                                                <li><strong>Plate Number:</strong> <?= htmlspecialchars($impond['plate_number']) ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5><i class="bi bi-calendar-event"></i> Dates</h5>
                                            <ul class="list-unstyled">
                                                <?php if($impond['impound_date']): ?>
                                                <li><strong>imponded:</strong> <?= date('M d, Y \a\t H:i', strtotime($impond['impound_date'])) ?></li>
                                                <?php endif; ?>
                                                <?php if($impond['release_date']): ?>
                                                <li><strong>Released:</strong> <?= date('M d, Y \a\t H:i', strtotime($impond['release_date'])) ?></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5><i class="bi bi-exclamation-triangle"></i> Reason for impond</h5>
                                        <p><?= nl2br(htmlspecialchars($impond['reason'])) ?></p>
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
                                                    <h5><?= htmlspecialchars($impond['officer_prenom'] . ' ' . $impond['officer_nom']) ?></h5>
                                                    <span class="text-muted"><?= htmlspecialchars($impond['officer_rank']) ?></span>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <h5>Record Details</h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <strong>impond ID:</strong> <?= $impond['id'] ?>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Officer ID:</strong> <?= $impond['officer_id'] ?>
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