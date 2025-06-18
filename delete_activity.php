<?php
require_once 'connect.php';
session_start();

// Only allow Sergeants and above to delete activities
$allowed_ranks = ['Sergeant', 'Lieutenant', 'Commander'];
if(!isset($_SESSION['POLICIER']) || !in_array($_SESSION['POLICIER']->rank, $allowed_ranks)) {
    header('Location: loginp.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activity_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
        $stmt->execute($_POST['activity_id']);
        
        $_SESSION['success'] = "Activity deleted successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting activity: " . $e->getMessage();
    }
}

header('Location: activity_rapport.php');
exit;
?>