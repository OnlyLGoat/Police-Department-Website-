<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: logins/loginp.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Reports | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --pd-blue: #1a237e;
            --pd-blue-light: #303f9f;
            --pd-red: #c62828;
            --pd-green: #2e7d32;
            --pd-amber: #ff8f00;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .report-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .btn-action {
            border-radius: 5px;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        @media (max-width: 768px) {
            .btn-action {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
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
                        <h2 class="text-light"><i class="bi bi-activity me-2"></i>Activity Reports</h2>
                        <a href="create_activity.php" class="btn btn-primary btn-action">
                            <i class="bi bi-plus-circle"></i> New Report
                        </a>
                    </div>
                    
                    <div class="card report-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Date</th>
                                            <th>Officer</th>
                                            <th>Activity Type</th>
                                            <th>Zone</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->prepare("
                                            SELECT a.*, o.nom, o.prenom 
                                            FROM activities a
                                            JOIN officres o ON a.officer_id = o.id
                                            ORDER BY a.time DESC
                                        ");
                                        $stmt->execute();
                                        $activities = $stmt->fetchAll();
                                        
                                        foreach($activities as $activity): ?>
                                        <tr>
                                            <td><?= date('M d, Y H:i', strtotime($activity['time'])) ?></td>
                                            <td><?= htmlspecialchars($activity['prenom'] . ' ' . $activity['nom']) ?></td>
                                            <td><?= htmlspecialchars($activity['activity_type']) ?></td>
                                            <td><?= htmlspecialchars($activity['zone']) ?></td>
                                            <td>
                                                <span class="badge p-2 bg-<?= 
                                                    $activity['status'] == 'completed' ? 'success' : 
                                                    ($activity['status'] == 'in_progress' ? 'primary' : 
                                                    ($activity['status'] == 'urgent' ? 'danger' : 'warning')) 
                                                ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $activity['status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if(in_array($_SESSION['POLICIER']->rank, ['Sergeant', 'Lieutenant', 'Commander'])): ?>
                                                <a href="view_activity.php?id=<?= $activity['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($_SESSION['POLICIER']->nom === $activity['nom']): ?>
                                                <a href="edit_activity.php?id=<?= $activity['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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