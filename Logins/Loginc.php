<?php 
    session_start();
    include '../connect.php';
    include '../header.php';
    $_SESSION['PAGE'] = 'LoginC';

    if(!isset($_SESSION['USER']) && isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        
        $stmt = $pdo->prepare('SELECT c.* FROM civils c 
                              JOIN remember_tokens rt ON c.id = rt.user_id 
                              WHERE rt.token = ? AND rt.expires > NOW()');
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        
        if($user) {
            $_SESSION['CIVIL'] = $user;
            setcookie('remember_token', $token, time() + 30*24*60*60, '/');
            header('Location: ../civiliant_rapport.php');
            exit();
        }
    }

    if(isset($_POST['submit'])){
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        $remember = isset($_POST['remember']) ? true : false;
        
        $errors = [];
        
        if (!$email) {
            $errors[] = "Invalid email format";
        }
        
        if (empty($pass)) {
            $errors[] = "Password is required";
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare('SELECT * FROM civils WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            if($user && password_verify($pass, $user->password)){
                $_SESSION['CIVIL'] = $user;
                
                if($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + 30*24*60*60; // 30 days from now
                    
                    $stmt = $pdo->prepare('INSERT INTO remember_tokens (user_id, token, expires) 
                                           VALUES (?, ?, FROM_UNIXTIME(?)) 
                                           ON DUPLICATE KEY UPDATE token = VALUES(token), expires = VALUES(expires)');
                    $stmt->execute([$user->id, $token, $expires]);
                    
                    setcookie('remember_token', $token, $expires, '/');
                }
                
                header('Location: ../civiliant_rapport.php');
                exit();
            } else {
                $errors[] = "Invalid email or password";
            }
        }
        
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
            const emailInput = document.querySelector('input[name="email"]');
            const passInput = document.querySelector('input[name="pass"]');
            
            emailInput.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    this.setCustomValidity('Please enter a valid email address');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            passInput.addEventListener('input', function() {
                if (this.value.length < 8) {
                    this.setCustomValidity('Password must be at least 8 characters');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    alert('Please enter a valid email address');
                    emailInput.focus();
                    isValid = false;
                }
                
                if (passInput.value.length < 8) {
                    alert('Password must be at least 8 characters');
                    passInput.focus();
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <title>Login</title>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <div class="container card d-grid mt-5" style="width:500px; background-color: rgb(205,169,65);">
        <form id="form" class="card-body m-1" action="LoginC.php" method="POST">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Login</h1>
            <br>
            <div>
                <input type="email" placeholder="Email" class="form-control p-2" name="email" required 
                       pattern="[^\s@]+@[^\s@]+\.[^\s@]+" title="Please enter a valid email address">
            </div><br>
            <div>
                <input type="password" placeholder="Password (min 8 characters)" class="form-control p-2 mb-2" name="pass" required
                       minlength="8" title="Password must be at least 8 characters">
            </div>
            <div>
            </div><br>
            <div class="form-check">
                <input class="form-check-input p-2" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div><br>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Login</button>
        </form>
    </div>
</body>
</html>