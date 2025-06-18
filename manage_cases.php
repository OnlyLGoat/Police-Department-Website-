<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander'])) {
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
    <title>Case Management | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .management-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .badge-closed {
            background-color: #6c757d;
        }
        
        .btn-close-case {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header2.php'; ?>
    
    <div class="container-fluid main-content">
        <div class="row justify-content-center">
            <main class="col-12 col-lg-10">
                <div class="page-content">
                    <h2 class="text-light mb-4"><i class="bi bi-folder2-open me-2"></i>Case Management</h2>
                    
                    <div class="card management-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Case ID</th>
                                            <th>Incident Type</th>
                                            <th>Lead Investigator</th>
                                            <th>Status</th>
                                            <th>Opened On</th>
                                            <th>Closed On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->prepare("
                                            SELECT c.*, i.type, o.nom, o.prenom 
                                            FROM cases c
                                            JOIN incidents i ON c.incident_id = i.id
                                            JOIN officres o ON i.officer_id = o.id
                                            ORDER BY 
                                                CASE WHEN c.status = 'open' THEN 1
                                                     WHEN c.status = 'under_investigation' THEN 2
                                                     ELSE 3 END,
                                                c.created_at DESC
                                        ");
                                        $stmt->execute();
                                        $cases = $stmt->fetchAll();
                                        
                                        foreach($cases as $case): ?>
                                        <tr>
                                            <td>PD-<?= $case['id'] ?></td>
                                            <td><?= htmlspecialchars($case['type']) ?></td>
                                            <td><?= htmlspecialchars($case['prenom'] . ' ' . $case['nom']) ?></td>
                                            <td>
                                                <span class="badge <?= 
                                                    $case['status'] == 'open' ? 'badge-open' : 
                                                    ($case['status'] == 'under_investigation' ? 'badge-investigation' : 'badge-closed')
                                                ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $case['status'])) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($case['created_at'])) ?></td>
                                            <td>
                                                <?= $case['status'] == 'closed' && $case['closed_at'] ? 
                                                    date('M d, Y', strtotime($case['closed_at'])) : 'N/A' ?>
                                            </td>
                                            <td>
                                                <a href="view_case_details.php?id=<?= $case['id'] ?>" class="btn btn-sm btn-info mb-2">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if($case['status'] != 'closed'): ?>
                                                <a href="close_case.php?id=<?= $case['id'] ?>" class="btn btn-sm btn-close-case">
                                                    <i class="bi bi-archive"></i>
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