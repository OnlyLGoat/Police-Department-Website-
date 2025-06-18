<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['POLICIER'])) {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);
$activity = null;

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT a.*, o.nom, o.prenom, o.rank, o.callsign 
            FROM activities a
            JOIN officres o ON a.officer_id = o.id
            WHERE a.id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $activity = $stmt->fetch();
        
        if(!$activity) {
            $_SESSION['error'] = "Activity not found";
            header('Location: activity_rapport.php');
            exit;
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error fetching activity: " . $e->getMessage();
        header('Location: activity_rapport.php');
        exit;
    }
} else {
    $_SESSION['error'] = "No activity ID specified";
    header('Location: activity_rapport.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Activity | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .detail-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .detail-item {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
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
                        <h2 class="text-light"><i class="bi bi-activity me-2"></i>Activity Details</h2>
                        <a href="activity_rapport.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Activities
                        </a>
                    </div>
                    
                    <div class="detail-card">
                        <div class="detail-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="detail-label">Activity ID:</span>
                                    <span>ACT-<?= $activity['id'] ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Status:</span>
                                    <span class="status-badge badge-<?= str_replace('_', '-', $activity['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $activity['status'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="detail-label">Officer:</span>
                                    <span><?= htmlspecialchars($activity['prenom'] . ' ' . $activity['nom']) ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Rank:</span>
                                    <span><?= htmlspecialchars($activity['rank']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="detail-label">Callsign:</span>
                                    <span><?= $activity['callsign'] ? htmlspecialchars($activity['callsign']) : 'N/A' ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Activity Type:</span>
                                    <span><?= htmlspecialchars($activity['activity_type']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="detail-label">Zone:</span>
                                    <span><?= htmlspecialchars($activity['zone']) ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Date/Time:</span>
                                    <span><?= date('M j, Y H:i', strtotime($activity['time'])) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Description:</span>
                            <div class="mt-2 p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($activity['description'])) ?>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <?php if(in_array($_SESSION['POLICIER']->rank, ['Sergeant', 'Lieutenant', 'Commander'])): ?>
                            <a href="edit_activity.php?id=<?= $activity['id'] ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Edit Activity
                            </a>
                            <?php endif; ?>
                            <a href="activity_rapport.php" class="btn btn-secondary">
                                <i class="bi bi-list-ul"></i> Return to List
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>