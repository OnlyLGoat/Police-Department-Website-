<?php
    include 'connect.php';
    session_start();
    $On = 'on_duty';
    $Off = 'off_duty';


    if($_SESSION['POLICIER'] && $_GET['statue']){
        $statue = $_GET['statue'];
        $id = $_SESSION['POLICIER']->id;
        if($statue == 'on_duty'){
            $stmt = $pdo->prepare("UPDATE officres SET statue = ? WHERE id = ?");
            $stmt->execute([$Off, $id]);
            $_SESSION['POLICIER']->statue = $Off; // Update session variable
            header("Location: Homep.php");
        }else if($statue == 'off_duty'){
            $stmt = $pdo->prepare("UPDATE officres SET statue = ? WHERE id = ?");
            $stmt->execute([$On, $id]);
            $_SESSION['POLICIER']->statue = $On; // Update session variable
            header("Location: Homep.php");
        } else {
            echo "Invalid status parameter.";
            exit;
        }
    } else {
        header("Location: Homep.php");
    }
    

?>