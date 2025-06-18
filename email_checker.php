<?php 
    session_start();
    include 'mail.php';
    include 'connect.php';
    if(isset($_SESSION['SEND']) && isset($_SESSION['EMAIL_VERIFY'])){
        $_SESSION['SEND'] = null;
        $_SESSION['EMAIL_VERIFY'] = null;
    }
    $error = '';

    if(isset($_POST['submit'])){
        $email = $_POST['email'];

        $stmt = $pdo->prepare('SELECT * FROM civils WHERE email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        if($result){
            $code = random_int(100000, 999999);
            $stmt = $pdo->prepare('UPDATE civils SET Verify_Code = ?');
            if($stmt->execute([$code])){
                $mail->setFrom('YOU_EMAIL', 'Contact Form LASD Verification');
                $mail->addAddress($email);
                $mail->Subject = 'Code Verification';
                $mail->Body = "
                <p>Dear {$result['nom']},</p>
                <p>We received a request to resend your verification code. Please find your code below:</p>
                <h2 style='color:#2c3e50;'>$code</h2>
                <p>Use this code to complete your verification process.</p>
                <p>If you did not request this email, please ignore it or contact our support team.</p>
                <br>
                <p>Best regards,<br>Your Company Team</p>
                ";
                if ($mail->send()) {
                    ob_end_clean();
                    $_SESSION['SEND'] = 'Done';
                    $_SESSION['EMAIL_VERIFY'] = $email;
                    $dn = 'Done';
                    header("Location: confirmer_code.php");
                exit;
                } else {
                    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                }

            }
        }else{
            $error = 'Invalid Email';
        }
    }
    // var_dump($_SESSION['SEND']);
    // var_dump($_SESSION['EMAIL_VERIFY']);






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
            <?php if($error): echo "<div class='alert alert-danger'>$error</div>"; endif; ?>

            <?php if(!$_SESSION['SEND']): ?>
            <div>
                <input type="email" placeholder="Email" class="form-control p-2" name="email" required 
                       pattern="[^\s@]+@[^\s@]+\.[^\s@]+" title="Please enter a valid email address">
            </div>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Send Verification Code</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
