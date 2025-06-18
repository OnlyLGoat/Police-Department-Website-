<?php
require_once 'connect.php';
session_start();

if(!isset($_SESSION['CIVIL'])) {
    header('Location: Logins/loginc.php');
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            ':id_civilians' => $_SESSION['CIVIL']->id,
            ':report_type' => filter_input(INPUT_POST, 'report_type', FILTER_SANITIZE_SPECIAL_CHARS),
            ':title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS),
            ':rapport' => filter_input(INPUT_POST, 'rapport', FILTER_SANITIZE_SPECIAL_CHARS),
            ':location' => filter_input(INPUT_POST, 'location', FILTER_SANITIZE_SPECIAL_CHARS),
            ':incident_date' => filter_input(INPUT_POST, 'incident_date', FILTER_SANITIZE_SPECIAL_CHARS),
            ':related_persons' => filter_input(INPUT_POST, 'related_persons', FILTER_SANITIZE_SPECIAL_CHARS),
            ':related_vehicles' => filter_input(INPUT_POST, 'related_vehicles', FILTER_SANITIZE_SPECIAL_CHARS),
            ':priority' => filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_SPECIAL_CHARS)
        ];

        $stmt = $pdo->prepare("INSERT INTO civiliant_rapport 
                              (id_civilians, report_type, title, rapport, location, incident_date, 
                               related_persons, related_vehicles, priority) 
                              VALUES 
                              (:id_civilians, :report_type, :title, :rapport, :location, 
                               :incident_date, :related_persons, :related_vehicles, :priority)");
        
        if($stmt->execute($data)) {
            $success = "Report submitted successfully! Reference #: ".$pdo->lastInsertId();
        } else {
            $error = "Error submitting report";
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Report | Police Department</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .form-section {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header3.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="form-section">
                    <h3><i class="bi bi-file-earmark-plus"></i> Create New Report</h3>
                    <form method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required-field">Report Type</label>
                                <select name="report_type" class="form-select" required>
                                    <option value="">Select type</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="witness">Witness Statement</option>
                                    <option value="information">Information Report</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-field">Priority</label>
                                <select name="priority" class="form-select" required>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required-field">Report Title</label>
                            <input type="text" name="title" class="form-control" maxlength="100" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required-field">Incident Date & Time</label>
                            <input type="datetime-local" name="incident_date" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="Street, City">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required-field">Report Details</label>
                            <textarea name="rapport" class="form-control" rows="6" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Related Persons (if any)</label>
                            <textarea name="related_persons" class="form-control" rows="2" 
                                      placeholder="Names, descriptions, roles"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Related Vehicles (if any)</label>
                            <textarea name="related_vehicles" class="form-control" rows="2" 
                                      placeholder="License plates, colors, models"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-eraser"></i> Clear Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-check"></i> Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>