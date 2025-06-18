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

try {
    $stmt = $pdo->prepare("SELECT status FROM cases WHERE id = ?");
    $stmt->execute([$case_id]);
    $case = $stmt->fetch();
    
    if(!$case) {
        $_SESSION['error'] = "Case not found";
        header('Location: manage_cases.php');
        exit;
    }
    
    if($case['status'] == 'closed') {
        $_SESSION['error'] = "Case is already closed";
        header('Location: view_case_details.php?id='.$case_id);
        exit;
    }
    
    $stmt = $pdo->prepare("UPDATE cases SET status = 'closed', closed_at = NOW() WHERE id = ?");
    $stmt->execute([$case_id]);
    
    // Update related incident status
    $stmt = $pdo->prepare("UPDATE incidents i JOIN cases c ON i.id = c.incident_id SET i.status = 'completed' WHERE c.id = ?");
    $stmt->execute([$case_id]);
    
    $_SESSION['success'] = "Case #PD-$case_id has been closed successfully";
    header('Location: view_case_details.php?id='.$case_id);
    exit;
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['error'] = "Database error occurred while closing the case";
    header('Location: manage_cases.php');
    exit;
}