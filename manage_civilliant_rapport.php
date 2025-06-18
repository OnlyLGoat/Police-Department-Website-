<?php
require_once 'connect.php';
session_start();

$allowedRanks = ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'];
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, $allowedRanks)) {
    header('Location: loginp.php');
    exit;
}

try {
    $stmt = $pdo->query("SELECT r.*, c.nom, c.prenom, c.CIN 
                         FROM civiliant_rapport r
                         JOIN civils c ON r.id_civilians = c.id
                         ORDER BY r.create_at DESC");
    $reports = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch(PDOException $e) {
    die("Error fetching reports: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civilian Reports | Police Department</title>
    <link rel="stylesheet" href="../Css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .report-container {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .report-header {
            border-bottom: 2px solid #1a237e;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .report-content {
            white-space: pre-line;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-top: 15px;
        }
        .civilian-info {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .badge-type {
            background-color: #1a237e;
            color: white;
        }
        .priority-high { color: #dc3545; }
        .priority-medium { color: #ffc107; }
        .priority-low { color: #28a745; }
    </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header2.php'; ?>
    
    <div class="container-fluid main-content">
        <div class="row justify-content-center">
            <main class="col-12 col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-light">
                        <i class="bi bi-file-earmark-text"></i> Civilian Reports
                    </h2>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <?php if(empty($reports)): ?>
                            <div class="alert alert-info">No reports found in the system.</div>
                        <?php else: ?>
                            <?php foreach($reports as $report): ?>
                            <div class="report-container">
                                <div class="civilian-info">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person-circle"></i> 
                                        <?= htmlspecialchars($report->prenom . ' ' . $report->nom) ?>
                                    </h5>
                                    <span class="badge bg-dark">
                                        CIN: <?= htmlspecialchars($report->CIN) ?>
                                    </span>
                                </div>

                                <div class="report-header">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h4><?= htmlspecialchars($report->title) ?></h4>
                                        <div>
                                            <span class="badge badge-type me-1">
                                                <?= ucfirst($report->report_type) ?>
                                            </span>
                                            <span class="badge bg-<?= 
                                                $report->status === 'resolved' ? 'success' : 
                                                ($report->status === 'rejected' ? 'danger' : 'primary') 
                                            ?>">
                                                <?= ucfirst(str_replace('_', ' ', $report->status)) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> 
                                            Submitted: <?= date('M d, Y H:i', strtotime($report->create_at)) ?>
                                        </small>
                                        <small class="priority-<?= $report->priority ?>">
                                            <i class="bi bi-exclamation-triangle-fill"></i> 
                                            Priority: <?= ucfirst($report->priority) ?>
                                        </small>
                                    </div>
                                    <?php if($report->location): ?>
                                    <div class="mt-2">
                                        <small>
                                            <i class="bi bi-geo-alt-fill"></i> 
                                            Location: <?= htmlspecialchars($report->location) ?>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="report-content">
                                    <?= htmlspecialchars($report->rapport) ?>
                                </div>
                                
                                <?php if($report->related_persons): ?>
                                <div class="mt-3">
                                    <h6><i class="bi bi-people-fill"></i> Related Persons:</h6>
                                    <div class="ps-3">
                                        <?= nl2br(htmlspecialchars($report->related_persons)) ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($report->related_vehicles): ?>
                                <div class="mt-3">
                                    <h6><i class="bi bi-car-front-fill"></i> Related Vehicles:</h6>
                                    <div class="ps-3">
                                        <?= nl2br(htmlspecialchars($report->related_vehicles)) ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../Js/bootstrap.bundle.min.js"></script>
</body>
</html>