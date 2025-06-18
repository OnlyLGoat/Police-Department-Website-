<?php 
session_start();
include '../connect.php';
include '../header.php';

$errors = [];
$formData = [
    'nom' => '',
    'prenom' => '',
    'age' => '',
    'email' => '',
    'CIN' => ''
];

if(isset($_POST['submit'])) {
    $formData = [
        'nom' => trim(filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)),
        'prenom' => trim(filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)),
        'age' => filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT),
        'email' => trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)),
        'CIN' => trim(filter_input(INPUT_POST, 'CIN', FILTER_SANITIZE_SPECIAL_CHARS)),
        'pass' => $_POST['pass'] // Will be sanitized during validation
    ];

    if (empty($formData['nom'])) {
        $errors[] = 'First name is required';
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $formData['nom'])) {
        $errors[] = 'First name can only contain letters and spaces';
    }

    if (empty($formData['prenom'])) {
        $errors[] = 'Last name is required';
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $formData['prenom'])) {
        $errors[] = 'Last name can only contain letters and spaces';
    }

    if ($formData['age'] === false) {
        $errors[] = 'Age must be a number';
    } elseif ($formData['age'] < 18 || $formData['age'] > 120) {
        $errors[] = 'Age must be between 18 and 120';
    }

    if (empty($formData['email'])) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    } elseif (!preg_match('/@gmail\.com$/', $formData['email'])) {
        $errors[] = 'Only Gmail addresses are allowed';
    }

    if (empty($formData['CIN'])) {
        $errors[] = 'CIN is required';
    } elseif (!preg_match('/^[A-Za-z]{2}\d{5}$/', $formData['CIN'])) {
        $errors[] = 'CIN must be 2 letters followed by 5 digits (e.g., AB12345)';
    }

    if (empty($_POST['pass'])) {
        $errors[] = 'Password is required';
    } elseif (strlen($_POST['pass']) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    } elseif (!preg_match('/[A-Z]/', $_POST['pass']) || !preg_match('/[a-z]/', $_POST['pass']) || !preg_match('/[0-9]/', $_POST['pass'])) {
        $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
    }

    $path = null;
    if(isset($_FILES["fichier"]) && $_FILES["fichier"]['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["fichier"];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, JPEG, PNG files are allowed";
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "File size must be less than 2MB";
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error";
        } else {
            $uploadDir = "../images/";
            $filename = uniqid() . '.' . $ext;
            $path = "images/" . $filename;
            
            if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $errors[] = "Failed to upload file";
            }
        }
    } else {
        $errors[] = "Profile photo is required";
    }

    if(empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM civils WHERE email = ? OR CIN = ?');
        $stmt->execute([$formData['email'], $formData['CIN']]);
        
        if($stmt->rowCount() > 0) {
            $errors[] = "User with this email or CIN already exists";
        } else {
            $hashedPass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $statue = 'Civils';
            
            $st = $pdo->prepare('INSERT INTO civils(nom, prenom, age, email, CIN, Statue, password, image) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            if($st->execute([$formData['nom'], $formData['prenom'], $formData['age'], $formData['email'], 
                           $formData['CIN'], $statue, $hashedPass, $path])) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header('Location: ../Logins/loginC.php');
                exit;
            } else {
                $errors[] = "Database error occurred";
            }
        }
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
                if (isNaN(age) || age < 18 || age > 120) {
                    this.setCustomValidity('Age must be between 18 and 120');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="email"]').addEventListener('input', function() {
                if (!this.value.endsWith('@gmail.com')) {
                    this.setCustomValidity('Only Gmail addresses are allowed');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="CIN"]').addEventListener('input', function() {
                const regex = /^[A-Za-z]{0,2}\d{0,5}$/;
                if (!regex.test(this.value)) {
                    this.setCustomValidity('Must be 2 letters followed by 5 digits');
                } else if (this.value.length > 2 && this.value.length !== 7) {
                    this.setCustomValidity('Must be exactly 2 letters and 5 digits');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('input[name="pass"]').addEventListener('input', function() {
                if (this.value.length < 8) {
                    this.setCustomValidity('Password must be at least 8 characters');
                } else if (!/[A-Z]/.test(this.value) || !/[a-z]/.test(this.value) || !/[0-9]/.test(this.value)) {
                    this.setCustomValidity('Password must contain uppercase, lowercase, and number');
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
                if (isNaN(ageValue)) {
                    alert('Age must be a number');
                    age.focus();
                    isValid = false;
                } else if (ageValue < 18 || ageValue > 120) {
                    alert('Age must be between 18 and 120');
                    age.focus();
                    isValid = false;
                }
                
                const email = document.querySelector('input[name="email"]');
                if (!email.value.endsWith('@gmail.com')) {
                    alert('Only Gmail addresses are allowed');
                    email.focus();
                    isValid = false;
                }
                
                const cin = document.querySelector('input[name="CIN"]');
                if (!/^[A-Za-z]{2}\d{5}$/.test(cin.value)) {
                    alert('CIN must be 2 letters followed by 5 digits (e.g., AB12345)');
                    cin.focus();
                    isValid = false;
                }
                
                const pass = document.querySelector('input[name="pass"]');
                if (pass.value.length < 8) {
                    alert('Password must be at least 8 characters long');
                    pass.focus();
                    isValid = false;
                } else if (!/[A-Z]/.test(pass.value) || !/[a-z]/.test(pass.value) || !/[0-9]/.test(pass.value)) {
                    alert('Password must contain at least one uppercase letter, one lowercase letter, and one number');
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
    <title>Signup</title>
</head>
<body style="background-color: rgba(18,62,45,255);">
    <div class="container card d-grid mt-5" style="width:500px; background-color: rgb(205,169,65);">
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                    <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form id="form" class="card-body m-1" action="SignupC.php" method="POST" enctype="multipart/form-data">
            <h1 class="card-title card-header" style="color: rgb(24,55,39);">Signup</h1>
            <br>
            <div class="mb-3">
                <input type="text" placeholder="Last Name" class="form-control p-2" name="prenom" 
                       value="<?= htmlspecialchars($formData['nom']) ?>" required
                       pattern="[a-zA-ZÀ-ÿ\s\-]+" title="Only letters and spaces allowed">
            </div>
            <div class="mb-3">
                <input type="text" placeholder="First Name" class="form-control p-2" name="nom" 
                       value="<?= htmlspecialchars($formData['prenom']) ?>" required
                       pattern="[a-zA-ZÀ-ÿ\s\-]+" title="Only letters and spaces allowed">
            </div>
            <div class="mb-3">
                <input type="number" placeholder="Age (18-120)" class="form-control p-2" name="age" 
                       value="<?= htmlspecialchars($formData['age']) ?>" required
                       min="18" max="120" title="Must be between 18 and 120">
            </div>
            <div class="mb-3">
                <input type="email" placeholder="Email (must be @gmail.com)" class="form-control p-2" name="email" 
                       value="<?= htmlspecialchars($formData['email']) ?>" required
                       pattern=".+@gmail\.com$" title="Must be a @gmail.com address">
            </div>
            <div class="mb-3">
                <input type="text" placeholder="CIN (format: AB12345)" class="form-control p-2" name="CIN" 
                       value="<?= htmlspecialchars($formData['CIN']) ?>" required
                       pattern="[A-Za-z]{2}\d{5}" title="Must be 2 letters followed by 5 digits">
            </div>
            <div class="mb-3">
                <input type="password" placeholder="Password (min 8 chars, with uppercase, lowercase, number)" 
                       class="form-control p-2" name="pass" required
                       minlength="8" title="Must be at least 8 characters with uppercase, lowercase, and number">
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="validatedCustomFile" name="fichier" required />
                <label class="custom-file-label" for="validatedCustomFile">Profil Photo ...</label>
            </div><br>
            <button type="submit" class="btn btn-block card-footer text-light mt-3" name="submit" 
                    style="background-color:rgb(24,55,39)">Signup</button>
        </form>
    </div>
</body>
</html>