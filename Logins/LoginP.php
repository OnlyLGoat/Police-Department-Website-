<?php 
    session_start();
    include '../connect.php';
    include '../header.php';
    
    if(isset($_POST['submit'])){
        $CIN = filter_input(INPUT_POST, 'cin', FILTER_SANITIZE_SPECIAL_CHARS);
        $callsign = filter_input(INPUT_POST, 'callsign', FILTER_SANITIZE_SPECIAL_CHARS);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Server-side validation
        $errors = [];
        
        // Validate CIN format
        if (!preg_match('/^EE\d{5}$/', $CIN)) {
            $errors[] = "Invalid CIN format. Must be EE followed by 5 digits.";
        }
        
        // Validate callsign format
        if (!preg_match('/^(C|O|S|L|CM)\d{3}$/', $callsign)) {
            $errors[] = "Invalid callsign format. Must be C, O, S, L, or CM followed by 3 digits.";
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare('SELECT * FROM officres WHERE callsign = ? and CIN = ?');
            $stmt->execute([$callsign, $CIN]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            if($user && password_verify($pass, $user->password)){
                $_SESSION['POLICIER'] = $user;
                if($user->Acc_Verify === ''){
                    header('Location: ../Signups/SignupP.php');
                    exit;
                }elseif($user->Acc_Verify === 'Verify'){
                    header('Location: ../HomeP.php');
                    exit;
                }
            } else {
                $errors[] = "Invalid credentials. Please try again.";
            }
        }
        
        // Display errors if any
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
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
                
                // Validate CIN (EE followed by 5 digits)
                const cinInput = document.querySelector('input[name="cin"]');
                const cinRegex = /^EE\d{5}$/;
                if (!cinRegex.test(cinInput.value)) {
                    alert('CIN must be in the format EE followed by 5 digits (e.g., EE12345)');
                    cinInput.focus();
                    isValid = false;
                }
                
                // Validate callsign (C, O, S, L, or CM followed by 3 digits)
                const callsignInput = document.querySelector('input[name="callsign"]');
                const callsignRegex = /^(C|O|S|L|CM)\d{3}$/;
                if (!callsignRegex.test(callsignInput.value)) {
                    alert('Callsign must be C, O, S, L, or CM followed by 3 digits (e.g., C123, O456, CM789)');
                    callsignInput.focus();
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // Add real-time validation feedback
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
        });
    </script>
    <title>Login</title>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <div class="container card d-grid mt-5" style="width:500px; background-color: rgb(205,169,65);">
        <form id="form" class="card-body m-1" action="LoginP.php" method="POST">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Login</h1>
            <br>
            <div>
                <input type="text" data-type="cin" placeholder="CIN (format: EE12345)" class="form-control p-2" name="cin" required pattern="EE\d{5}" title="EE followed by 5 digits (e.g., EE12345)">
            </div><br>
            <div>
                <input type="text" data-type="callsign" placeholder="Callsign (format: C123 or CM123)" class="form-control p-2" name="callsign" required pattern="(C|O|S|L|CM)\d{3}" title="C, O, S, L, or CM followed by 3 digits (e.g., C123, O456, CM789)">
            </div><br>
            <div>
                <input type="password" data-type="Password" placeholder="Password" class="form-control p-2" name="pass" required>
            </div><br>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Login</button>
        </form>
    </div>
</body>
</html>