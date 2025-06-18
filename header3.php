<?php
$currentPage = strtoupper(basename($_SERVER['SCRIPT_NAME']));
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Police Department Management System</title>
  <link rel="stylesheet" href="css/bootstrap.css">
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
      padding: 0.5rem 1rem;
      margin: 0.25rem;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
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
      height: auto;
      max-width: 100%;
    }
    
    .navbar-brand:hover img {
      transform: scale(1.05);
    }
    
    .main-content {
      padding-top: 1.5rem;
    }
    
    .user-profile-table {
      border-collapse: separate;
      border-spacing: 5px;
      border-radius: 10px;
      width: 100%;
      max-width: 300px;
    }
    
    .user-profile-table td {
      padding: 5px;
    }
    
    .user-avatar {
      height: 65px;
      width: 70px;
      object-fit: cover;
    }
    
    .user-info {
      font-weight: 500;
      color: white;
    }
    
    .nav-links-center {
      flex-grow: 1;
      display: flex;
      justify-content: center;
    }
    
    .user-profile-container {
      margin-left: auto;
    }
    
    @media (max-width: 992px) {
      .navbar-custom {
        padding: 0.5rem;
      }
      
      .nav-btn {
        padding: 0.5rem;
        font-size: 0.8rem;
      }
      
      .user-profile-table {
        max-width: 250px;
      }
      
      .user-avatar {
        height: 50px;
        width: 55px;
      }
      
      .nav-links-center {
        order: 3;
        width: 100%;
      }
      
      .navbar-brand {
        order: 1;
      }
      
      .user-profile-container {
        order: 2;
        margin-left: 0;
      }
    }
    
    @media (max-width: 768px) {
      .nav {
        flex-wrap: wrap;
      }
    }
    
    @media (max-width: 576px) {
      .nav-btn {
        font-size: 0.75rem;
        padding: 0.4rem 0.6rem;
      }
      
      .nav-btn i {
        font-size: 0.9rem;
      }
      
      .user-avatar {
        height: 45px;
        width: 50px;
      }
      
      .user-info {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="HomeP.php">
        <img src="Logo/LAASD.png" height="65" width="70" alt="">
      </a>


      <div class="user-profile-container">
        <?php
          echo '<table class="user-profile-table" style="background-color: rgb(24, 55, 39);">';
          echo '<tr>';
          echo "<td rowspan='2'><img src='{$_SESSION['CIVIL']->image}' class='user-avatar mr-3'></td>";
          echo "<td class='user-info'>{$_SESSION["CIVIL"]->prenom} {$_SESSION["CIVIL"]->nom}</td>";
          echo '</tr>';
          echo '<tr>';
          echo '<td><a class="nav-btn active pt-1 pb-1" href="Logoutc.php" style="background-color: rgb(205, 169, 65);"><i class="bi bi-box-arrow-in-right"></i> <span class="nav-text">Logout</span></a></td>';
          echo '</tr>';
          echo '</table>';
        ?>
      </div>
    </div>
  </nav>

  <div class="container-fluid main-content">
    <div class="row">
      <main class="col-12">