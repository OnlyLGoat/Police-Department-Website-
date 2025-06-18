<?php 
    session_start();
    include 'connect.php';
    include 'mail.php';

    if(isset($_SESSION['POLICIER']) && $_SESSION['POLICIER']->rank === 'Commander'){
        $stmt = $pdo->prepare('SELECT * FROM ranks');
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        if(isset($_POST['submit'])){
            $CIN = filter_input(INPUT_POST, 'cin', FILTER_SANITIZE_SPECIAL_CHARS);
            $callsign = filter_input(INPUT_POST, 'callsign', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $passd = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
            $select = filter_input(INPUT_POST, 'select', FILTER_SANITIZE_SPECIAL_CHARS);

            $errors = [];

            if (!preg_match('/^EE\d{5}$/', $CIN)) {
                $errors[] = "CIN must be in the format EE followed by 5 digits (e.g., EE12345)";
            }

            if (!preg_match('/^(C|O|S|L|CM)\d{3}$/', $callsign)) {
                $errors[] = "Callsign must be C, O, S, L, or CM followed by 3 digits (e.g., C123, O456, CM789)";
            }

            if (!preg_match('/@gmail\.com$/', $email)) {
                $errors[] = "Email must be a @gmail.com address";
            }

            if (strlen($passd) < 8) {
                $errors[] = "Password must be at least 8 characters long";
            }
            
            if (empty($errors)) {
                $pass = password_hash($passd, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare('INSERT INTO officres(email, CIN, callsign, password, `rank`) VALUES(?, ?, ?, ?, ?)');
                if($stmt->execute([$email, $CIN, $callsign, $pass, $select])){
                    // $data = "Bonjour $select, Ce message contient votre information personel pour se authentifier a notre site : ||| |||";
                    // $data .= "CIN : $CIN|||";
                    // $data .= "CallSign : $callsign|||";
                    // $data .= "Password : $passd|||";
                    // $escaped = escapeshellarg($data);
                    // $escapedReceiver = escapeshellarg($email);

                    // $python = "C:\\Python313\\python.exe";
                    // $script = "C:\\wamp64\\www\\PHP\\APDProject\\script.py";
                    // $command = "C:\\Python313\\python.exe C:\\wamp64\\www\\PHP\\APDProject\\script.py $escaped $escapedReceiver";

                    // $output = shell_exec($command . "2>&1");
                    
                    $mail->setFrom('issimo181@gmail.com', 'Contact Form LASD Office');
                    $mail->addAddress($email);
                    $mail->Subject = 'Information for Login';
                    $mail->Body = "
                    <p>Bonjour Monsieur {$select},</p>
                    <p>Ce Message Contient Votre Information Personel Pour Se Authentifier A Notre Site :</p>
                    <h2 style='color:#2c3e50;'>CIN : $CIN</h2>
                    <h2 style='color:#2c3e50;'>CallSign : $callsign</h2>
                    <h2 style='color:#2c3e50;'>Password : $passd</h2>
                    <br>
                    <p>Best regards,<br><b>LASD</b></p>
                    <a href='https://postimg.cc/GT3kMCd2' target='_blank'><img src='https://i.postimg.cc/4yVBxf0z/general-principles-of-criminal-liability-2.jpg' border='0' alt='general-principles-of-criminal-liability-2'/></a>
                    ";
                    $mail->send();
                    header('Location: Logins/LoginP.php');
                }
            } else {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
        }
    }else{
        header('Location: Logins/LoginP.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/bootstrap.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;

                const cinInput = document.querySelector('input[name="cin"]');
                const cinRegex = /^EE\d{5}$/;
                if (!cinRegex.test(cinInput.value)) {
                    alert('CIN must be in the format EE followed by 5 digits (e.g., EE12345)');
                    cinInput.focus();
                    isValid = false;
                }
                
                const callsignInput = document.querySelector('input[name="callsign"]');
                const callsignRegex = /^(C|O|S|L|CM)\d{3}$/;
                if (!callsignRegex.test(callsignInput.value)) {
                    alert('Callsign must be C, O, S, L, or CM followed by 3 digits (e.g., C123, O456, CM789)');
                    callsignInput.focus();
                    isValid = false;
                }

                const emailInput = document.querySelector('input[name="email"]');
                const emailRegex = /.+@gmail\.com$/;
                if (!emailRegex.test(emailInput.value)) {
                    alert('Email must be a @gmail.com address');
                    emailInput.focus();
                    isValid = false;
                }

                const passwordInput = document.querySelector('input[name="pass"]');
                if (passwordInput.value.length < 8) {
                    alert('Password must be at least 8 characters long');
                    passwordInput.focus();
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });

            document.querySelector('input[name="cin"]').addEventListener('input', function() {
                const regex = /^EE\d{0,5}$/;
                if (!regex.test(this.value)) {
                    this.setCustomValidity('CIN must start with EE followed by up to 5 digits');
                } else if (this.value.length !== 7 && this.value.length > 2) {
                    this.setCustomValidity('CIN must be exactly EE followed by 5 digits (7 characters total)');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="callsign"]').addEventListener('input', function() {
                const regex = /^(C|O|S|L|CM)\d{0,3}$/;
                if (!regex.test(this.value)) {
                    this.setCustomValidity('Callsign must start with C, O, S, L, or CM followed by up to 3 digits');
                } else if ((this.value.startsWith('CM') && this.value.length !== 5) || 
                          (!this.value.startsWith('CM') && this.value.length !== 4) && 
                          this.value.length > 1) {
                    this.setCustomValidity('Callsign must be C/O/S/L followed by 3 digits (4 chars) or CM followed by 3 digits (5 chars)');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="email"]').addEventListener('input', function() {
                if (!this.value.endsWith('@gmail.com')) {
                    this.setCustomValidity('Email must end with @gmail.com');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="pass"]').addEventListener('input', function() {
                if (this.value.length < 8) {
                    this.setCustomValidity('Password must be at least 8 characters long');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
    <title>Document</title>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <?php include 'header2.php';?>
    <div class="container card d-grid mt-5" style="width:500px; background-color: rgb(205,169,65);">
        <form id="form" class="card-body m-1" action="SignupCM.php" method="POST" enctype="multipart/form-data">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Signup</h1>
            <br>
            <div>
                <input type="text" data-type="Nom" placeholder="CIN (format: EE12345)" class="form-control p-2" name="cin" required pattern="EE\d{5}" title="EE followed by 5 digits (e.g., EE12345)">
            </div><br>
            <div>
                <input type="text" data-type="Prenom" placeholder="CallSign (format: C123 or CM123)" class="form-control p-2" name="callsign" required pattern="(C|O|S|L|CM)\d{3}" title="C, O, S, L, or CM followed by 3 digits (e.g., C123, O456, CM789)">
            </div><br>
            <div>
                <input type="email" data-type="Email" placeholder="Email (must be @gmail.com)" class="form-control p-2" name="email" required pattern=".+@gmail\.com$" title="Must be a @gmail.com address">
            </div><br>
            <div>
                <input type="password" data-type="Password" placeholder="Password (min 8 characters)" class="form-control p-2" name="pass" required minlength="8" title="Must be at least 8 characters long">
            </div><br>
            <div>
                <select name="select" id="" class="form-control p-2" required>
                    <option value="">Select Rank</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat->rank ?>"><?= htmlspecialchars($cat->rank) ?></option>
                    <?php endforeach; ?>
                </select>
            </div><br>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Signup</button>
        </form>
    </div>
</body>
</html>