<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Sergeant', 'Lieutenant', 'Commander'])) {
    header('Location: login.php');
    exit;
}

$currentPage = basename(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Cases | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .case-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .badge-open {
            background-color: #fd7e14;
        }
        
        .badge-investigation {
            background-color: #17a2b8;
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
                        <h2 class="text-light"><i class="bi bi-folder me-2"></i>Open Cases</h2>
                        <a href="create_case.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Case
                        </a>
                    </div>
                    
                    <div class="card case-card">
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
                                            WHERE c.status != 'closed'
                                            ORDER BY c.created_at DESC
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
                                                    $case['status'] == 'open' ? 'badge-open' : 'badge-investigation'
                                                ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $case['status'])) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($case['created_at'])) ?></td>
                                            <td>
                                                <?php if(in_array($_SESSION['POLICIER']->rank, ['Lieutenant', 'Commander'])): ?>
                                                <a href="view_case_details.php?id=<?= $case['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
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