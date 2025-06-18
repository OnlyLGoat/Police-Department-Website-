<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Cadet', 'Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: loginp.php');
    exit;
}

$currentPage = basename(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .incident-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .badge-urgent {
            background-color: #dc3545;
        }
        
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
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
                        <h2 class="text-light"><i class="bi bi-exclamation-triangle me-2"></i>Incident Reports</h2>
                        <a href="create_incident.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Incident
                        </a>
                    </div>
                    
                    <div class="card incident-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Officer</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->prepare("
                                            SELECT i.*, o.nom, o.prenom 
                                            FROM incidents i
                                            JOIN officres o ON i.officer_id = o.id
                                            ORDER BY i.created_at DESC
                                        ");
                                        $stmt->execute();
                                        $incidents = $stmt->fetchAll();
                                        
                                        foreach($incidents as $incident): ?>
                                        <tr>
                                            <td><?= $incident['id'] ?></td>
                                            <td><?= htmlspecialchars($incident['type']) ?></td>
                                            <td><?= htmlspecialchars($incident['location']) ?></td>
                                            <td><?= htmlspecialchars($incident['prenom'] . ' ' . $incident['nom']) ?></td>
                                            <td>
                                                <span class="badge <?= 
                                                    $incident['status'] == 'urgent' ? 'badge-urgent' : 
                                                    ($incident['status'] == 'pending' ? 'badge-pending' : 'badge-success')
                                                ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $incident['status'])) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($incident['created_at'])) ?></td>
                                            <td>
                                                <?php if(in_array($_SESSION['POLICIER']->rank, ['Sergeant', 'Lieutenant', 'Commander'])): ?>
                                                <a href="view_incident.php?id=<?= $incident['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($_SESSION['POLICIER']->nom === $incident['nom']): ?>
                                                <a href="edit_incident.php?id=<?= $incident['id'] ?>" class="btn btn-sm btn-warning">
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