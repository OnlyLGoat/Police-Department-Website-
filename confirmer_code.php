<?php 
Session_start();
include 'mail.php';
include 'connect.php';
    if(isset($_POST['verify']) && $_SESSION['SEND']){
        $code_verify = $_POST['code'];
        $stmt = $pdo->prepare('SELECT * FROM civils WHERE Verify_Code = ?');
        $stmt->execute([$code_verify]);
        // var_dump($stmt->fetch());
        if($stmt->fetch()){
            echo 'Code Verified';
            header('Location: change_pass.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Css/bootstrap.css">
</head>
<body style="background-color: rgba(18,62,45,255);">
        <div class="container card d-grid mt-5" style="width:500px; background-color: rgb(205,169,65);">
        <form id="form" class="card-body m-1" action="email_checker.php" method="POST">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Enter Your Email</h1>
            <br>
            <?php if($_SESSION['SEND']): ?>
            <div>
                <input type="number" placeholder="Verification Code" class="form-control p-2" name="code" required >
            </div>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="verify" style="background-color:rgb(24,55,39)">Confirm</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>