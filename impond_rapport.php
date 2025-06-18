<?php
require_once 'connect.php';
session_start();
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, ['Officer', 'Sergeant', 'Lieutenant', 'Commander'])) {
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
    <title>Impound Reports | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .impound-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .status-normal {
            color: #28a745;
        }
        
        .status-impounded {
            color: #dc3545;
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
                        <h2 class="text-light"><i class="bi bi-car-front me-2"></i>Impound Reports</h2>
                        <a href="create_impound.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Impound
                        </a>
                    </div>
                    
                    <div class="card impound-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Plate Number</th>
                                            <th>Status</th>
                                            <th>Impound Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->prepare("SELECT * FROM vehicles ORDER BY impound_date DESC");
                                        $stmt->execute();
                                        $vehicles = $stmt->fetchAll();
                                        
                                        foreach($vehicles as $vehicle): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($vehicle['plate_number']) ?></td>
                                            <td>
                                                <span class="<?= 
                                                    $vehicle['status'] == 'impounded' ? 'status-impounded' : 'status-normal'
                                                ?>">
                                                    <?= ucfirst($vehicle['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $vehicle['impound_date'] ? date('M d, Y', strtotime($vehicle['impound_date'])) : 'N/A' ?></td>
                                            <td>
                                                <?php if(in_array($_SESSION['POLICIER']->rank, ['Sergeant', 'Lieutenant', 'Commander'])): ?>
                                                <a href="view_impound.php?id=<?= $vehicle['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($_SESSION['POLICIER']->id === $vehicle['officer_id']): ?>
                                                <a href="edit_impond.php?id=<?= $vehicle['id'] ?>" class="btn btn-sm btn-warning">
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