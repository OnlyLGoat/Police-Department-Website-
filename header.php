<?php
     $currentpage = strtoupper(basename($_SERVER['SCRIPT_NAME']));
     
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/bootstrap.css">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
      <style>
    .navbar-custom {
      background-color: rgb(205, 169, 65);
      border-radius: 0 0 15px 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 0.5rem 1rem;
    }
    
    .nav-btn {
      background-color: rgb(24, 55, 39);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1.25rem;
      margin: 0.25rem;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .nav-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      color: white;
    }
    
    .nav-btn:active {
      transform: translateY(0);
    }
    
    .nav-btn.disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }
    
    .nav-btn.active {
      position: relative;
    }
    
    .nav-btn.active::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 60%;
      height: 3px;
      background-color: white;
      border-radius: 3px;
    }
    
    .navbar-brand img {
      transition: transform 0.3s ease;
    }
    
    .navbar-brand:hover img {
      transform: scale(1.05);
    }
    
    .main-content {
      padding-top: 2rem;
    }
  </style>
</head>
<body>
    <nav class="navbar navbar" style="border-radius:0 0 15px 15px; background-color:rgb(205,169,65)">
    <a class="navbar-brand" href="main.php">
        <img src="../Logo/LAASD.png" height="65" width="70" alt="">
    </a>
      <ul class="nav justify-content-end p-2">
        <li class="nav-item">
          <?php if (($currentpage === 'SIGNUPC.PHP') || ($currentpage === 'LOGINC.PHP')): ?>
            <a class="nav-btn active" href="../civiliant_rapport.php">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          <?php elseif (($currentpage === 'SIGNUPP.PHP') || ($currentpage === 'LOGINP.PHP')): ?>
            <a class="nav-btn active" href="../Homep.php">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          <?php else: ?>
            <a class="nav-btn disabled" href="#">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if ($currentpage === 'AboutUs.php'): ?>
            <a class="nav-btn disabled" href="#">
              <i class="bi bi-info-circle"></i> About
            </a>
          <?php else : ?>
            <a class="nav-btn active" href="#">
              <i class="bi bi-info-circle"></i> About
            </a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (($currentpage === 'SIGNUPC.PHP')): ?>
            <a class="nav-btn active" href="../Logins/LOGINC.PHP">
              <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
          <?php elseif (($currentpage === 'SIGNUPP.PHP')): ?>
            <a class="nav-btn active" href="../Logins/LOGINP.PHP">
              <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
          <?php else: ?>
            <a class="nav-btn disabled" href="#">
              <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (($currentpage === 'LOGINC.PHP')): ?>
            <a class="nav-btn active" href="../Signups/SIGNUPC.PHP">
              <i class="bi bi-person-plus"></i> Register
            </a>
          <?php elseif (($currentpage === 'LOGINP.PHP')): ?>
            <a class="nav-btn active" href="../Signups/SIGNUPP.PHP">
              <i class="bi bi-person-plus"></i> Register
            </a>
          <?php else: ?>
            <a class="nav-btn disabled" href="#">
              <i class="bi bi-person-plus"></i> Register
            </a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
</body>
</html>