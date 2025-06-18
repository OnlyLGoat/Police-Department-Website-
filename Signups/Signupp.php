<?php 
    session_start();
    include '../connect.php';
    include '../header.php';
    
    if(isset($_SESSION['POLICIER'])){
        if(isset($_POST['submit']) && isset($_FILES["fichier"])){
            $errors = [];

            $folder = "../images/";
            $file = $_FILES["fichier"];
            $ext = strtolower(strrchr($file['name'], '.'));
            $allowed = ['.jpg', '.jpeg', '.png'];
            
            if (!in_array($ext, $allowed)) {
                $errors[] = "Only JPG, JPEG, and PNG files are allowed.";
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $errors[] = "File size must be less than 2MB.";
            } elseif ($file['error'] !== 0) {
                $errors[] = "File upload error.";
            }
            
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
            $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
            $acc = 'Verify';
            
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $nom)) {
                $errors[] = "First name can only contain letters and spaces.";
            }
            
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $prenom)) {
                $errors[] = "Last name can only contain letters and spaces.";
            }
            
            if ($age === false || $age < 22 || $age > 62) {
                $errors[] = "Age must be between 22 and 62.";
            }
            
            if (!preg_match('/@gmail\.com$/', $email)) {
                $errors[] = "Email must be a @gmail.com address.";
            }
            
            if (strlen($pass) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            }
            
            if (empty($errors)) {
                $path = $folder . time() . "-" . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $path)) {
                    $folder = "images/";
                    $statue = 'off_duty';
                    $path = $folder . time() . "-" . basename($file['name']);
                    
                    $pass = password_hash($pass, PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare('SELECT * FROM officres WHERE nom = ? and prenom = ?');
                    $stmt->execute([$nom, $prenom]);
                    
                    if($stmt->rowCount() > 0){
                        $errors[] = "This name combination already exists.";
                    } else {
                        $stmt = $pdo->prepare('UPDATE officres SET nom = ?, prenom = ?, age = ?, email = ?, password = ?, statue = ?, Acc_Verify = ?, image = ? WHERE CIN = ? and callsign = ?');
                        if($stmt->execute([$nom, $prenom, $age, $email, $pass, $statue, $acc, $path, $_SESSION['POLICIER']->CIN, $_SESSION['POLICIER']->callsign])){
                            header('Location: ../logins/loginp.php');
                            exit;
                        } else {
                            $errors[] = "Database error. Please try again.";
                        }
                    }
                } else {
                    $errors[] = "Failed to upload file.";
                }
            }
            
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
    } else {
        header('Location: ../Logins/LoginP.php');
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
            
            document.querySelector('input[name="nom"]').addEventListener('input', function() {
                const regex = /^[a-zA-ZÀ-ÿ\s\-]*$/;
                if (!regex.test(this.value)) {
                    this.setCustomValidity('Only letters and spaces allowed');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="prenom"]').addEventListener('input', function() {
                const regex = /^[a-zA-ZÀ-ÿ\s\-]*$/;
                if (!regex.test(this.value)) {
                    this.setCustomValidity('Only letters and spaces allowed');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="age"]').addEventListener('input', function() {
                const age = parseInt(this.value);
                if (isNaN(age) || age < 22 || age > 62) {
                    this.setCustomValidity('Age must be between 22 and 62');
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
                    this.setCustomValidity('Password must be at least 8 characters');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                const nom = document.querySelector('input[name="nom"]');
                if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(nom.value)) {
                    alert('First name can only contain letters and spaces');
                    nom.focus();
                    isValid = false;
                }
                
                const prenom = document.querySelector('input[name="prenom"]');
                if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(prenom.value)) {
                    alert('Last name can only contain letters and spaces');
                    prenom.focus();
                    isValid = false;
                }
                
                const age = document.querySelector('input[name="age"]');
                const ageValue = parseInt(age.value);
                if (isNaN(ageValue) || ageValue < 22 || ageValue > 62) {
                    alert('Age must be between 22 and 62');
                    age.focus();
                    isValid = false;
                }
                
                const email = document.querySelector('input[name="email"]');
                if (!email.value.endsWith('@gmail.com')) {
                    alert('Email must be a @gmail.com address');
                    email.focus();
                    isValid = false;
                }
                
                const pass = document.querySelector('input[name="pass"]');
                if (pass.value.length < 8) {
                    alert('Password must be at least 8 characters long');
                    pass.focus();
                    isValid = false;
                }
                
                const file = document.querySelector('input[name="fichier"]');
                if (file.files.length === 0) {
                    alert('Please select a profile photo');
                    file.focus();
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <title>Complete Your Profile</title>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <div class="container card d-grid mt-5" style="width:500px; background-color: rgb(205,169,65);">
        <form id="form" class="card-body m-1" action="SignupP.php" method="POST" enctype="multipart/form-data">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Complete Your Profile</h1>
            <br>
            <div>
                <input type="text" placeholder="Last Name (letters only)" class="form-control p-2" name="prenom" required 
                       pattern="[a-zA-ZÀ-ÿ\s\-]+" title="Only letters and spaces allowed">
            </div><br>
            <div>
                <input type="text" placeholder="First Name (letters only)" class="form-control p-2" name="nom" required 
                       pattern="[a-zA-ZÀ-ÿ\s\-]+" title="Only letters and spaces allowed">
            </div><br>
            <div>
                <input type="number" placeholder="Age (22-62)" class="form-control p-2" name="age" required 
                       min="22" max="62" title="Must be between 22 and 62">
            </div><br>
            <div>
                <input type="email" placeholder="Email (must be @gmail.com)" class="form-control p-2" name="email" required 
                       pattern=".+@gmail\.com$" title="Must be a @gmail.com address">
            </div><br>
            <div>
                <input type="password" placeholder="Password (min 8 characters)" class="form-control p-2" name="pass" required 
                       minlength="8" title="Must be at least 8 characters">
            </div><br>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="validatedCustomFile" name="fichier" required />
                <label class="custom-file-label" for="validatedCustomFile">Profil Photo ...</label>
            </div><br>
            <br><button type="submit" class="btn btn-block card-footer text-light" name="submit" style="background-color:rgb(24,55,39)">Complete Profile</button>
        </form>
    </div>
</body>
</html>