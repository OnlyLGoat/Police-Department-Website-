<?php 
    session_start();
    include 'connect.php';
    if($_SESSION['SEND'] && $_SESSION['EMAIL_VERIFY']){
        if(isset($_POST['submit'])){
            $email = $_SESSION['EMAIL_VERIFY'];
            $pass = trim($_POST['pass']);
            $pass = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('UPDATE civils SET password = ? WHERE email = ?');
            if($stmt->execute([$pass, $email])){
                $_SESSION['SEND'] = null;
                $_SESSION['EMAIL_VERIFY'] = null;
                header('Location: Logins/loginc.php');
            }
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
        <form id="form" class="card-body m-1" action="change_pass.php" method="POST">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Enter Your New Password</h1>
            <br>
            <div>
                <input type="password" placeholder="New Password" class="form-control p-2" name="pass" required >
            </div>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Confirm</button>
        </form>
    </div>
</body>
</html>