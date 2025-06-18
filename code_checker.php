<?php 
    session_start();
    include 'connect.php';
    if(isset($_POST['submit'])){
        $email = $_POST['email'];

        $stmt = $pdo->prepare('SELECT * FROM civils WHERE email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        if($result){
            $code = random_int(100000, 999999);
            $stmt = $pdo->prepare('UPDATE civils SET Verify_Code = ?');
            if($stmt->execute([$code])){
                $data = "Bonjour Monsieur, Votre Code De Confirmation Est : ||| |||";
                $data .= "Verification Code : $code|||";
                $escaped = escapeshellarg($data);
                $escapedReceiver = escapeshellarg($email);

                $python = "C:\\Python313\\python.exe";
                $script = "C:\\wamp64\\www\\PHP\\APDProject\\script.py";
                $command = "C:\\Python313\\python.exe C:\\wamp64\\www\\PHP\\APDProject\\script.py $escaped $escapedReceiver";

                $output = shell_exec($command . "2>&1");
                header('Location: Logins/LoginP.php');
            }
        }else{
            echo 'aaa';
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
            <div>
                <input type="number" placeholder="Verification Code" class="form-control p-2" name="email" required >
            </div>

            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Send Verification Code</button>
        </form>
    </div>
</body>
</html>