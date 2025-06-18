<?php
$currentPage = strtoupper(basename($_SERVER['SCRIPT_NAME']));
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$On = 'On Duty';
$Off = 'Off Duty';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Police Department Management System</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <!-- Custom CSS -->
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
    
    /* New styles for centered navigation */
    .nav-links-center {
      flex-grow: 1;
      display: flex;
      justify-content: center;
    }
    
    .user-profile-container {
      margin-left: auto;
    }
    
    /* Responsive adjustments */
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
      
      /* Mobile reordering */
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
  <!-- Navigation -->
  <nav class="navbar navbar-custom">
    <div class="container-fluid">
      <!-- Logo on the left -->
      <a class="navbar-brand" href="HomeP.php">
        <img src="Logo/LAASD.png" height="65" width="70" alt="">
      </a>

      <!-- Centered navigation links -->
      <div class="nav-links-center">
        <ul class="nav justify-content-center p-2 flex-wrap">
          <!-- Cadet -->
          <?php if($_SESSION['POLICIER']->rank === 'Cadet'): ?>
          <li class="nav-item">
            <!-- Home Cadet -->
            <?php if(($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="HOMEP.PHP">
                <i class="bi bi-activity"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-activity"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Activity Rapport Cadet -->
          <li class="nav-item">
             <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="ACTIVITY_RAPPORT.PHP">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Incident Rapport Cadet -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="INCIDENT_RAPPORT.PHP">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php endif; ?>
          </li>

          <!-- Officer -->
          <?php elseif($_SESSION['POLICIER']->rank === 'Officer'): ?>
            <!-- Home Officer -->
          <li class="nav-item">
            <?php if(($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="HOMEP.PHP">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Activity Rapport Officer -->
          <li class="nav-item">
             <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="ACTIVITY_RAPPORT.PHP">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Incident Rapport Officer -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="INCIDENT_RAPPORT.PHP">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Impound Rapport Officer -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="IMPOND_RAPPORT.PHP">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Civiliant Rapport Officer -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="MANAGE_CIVILLIANT_RAPPORT.PHP">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civiliant Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civilliant Rapport</span>
              </a>
            <?php endif; ?>
          </li>
          <?php elseif($_SESSION['POLICIER']->rank === 'Sergeant'): ?>
            <!-- Home Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="HOMEP.PHP">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Activity Rapport Sergeant -->
          <li class="nav-item">
             <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="ACTIVITY_RAPPORT.PHP">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Incident Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="INCIDENT_RAPPORT.PHP">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Impound Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="IMPOND_RAPPORT.PHP">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Civiliant Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="MANAGE_CIVILLIANT_RAPPORT.PHP">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civiliant Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civilliant Rapport</span>
              </a>
            <?php endif; ?>
            <!-- Open Cases Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')): ?>
              <a class="nav-btn active" href="OPEN_CASES.PHP">
                <i class="bi bi-folder"></i> <span class="nav-text">Open Cases</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-folder"></i> <span class="nav-text">Open Cases</span>
              </a>
            <?php endif; ?>
          </li>

          <?php elseif($_SESSION['POLICIER']->rank === 'Lieutenant'): ?>
            <!-- Home Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="HOMEP.PHP">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Activity Rapport Sergeant -->
          <li class="nav-item">
             <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="ACTIVITY_RAPPORT.PHP">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Incident Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="INCIDENT_RAPPORT.PHP">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Impound Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="IMPOND_RAPPORT.PHP">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Civiliant Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="MANAGE_CIVILLIANT_RAPPORT.PHP">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civiliant Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civilliant Rapport</span>
              </a>
            <?php endif; ?>
            <!-- Open Cases Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="OPEN_CASES.PHP">
                <i class="bi bi-folder"></i> <span class="nav-text">Open Cases</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-folder"></i> <span class="nav-text">Open Cases</span>
              </a>
            <?php endif; ?>
          </li>

          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')  || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="MANAGE_CASES.PHP">
                <i class="bi bi-folder"></i> <span class="nav-text">Manage Cases</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-folder"></i> <span class="nav-text">Manage Cases</span>
              </a>
            <?php endif; ?>
          </li>
          <?php elseif($_SESSION['POLICIER']->rank === 'Commander'): ?>
            <!-- Home Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="HOMEP.PHP">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Activity Rapport Sergeant -->
          <li class="nav-item">
             <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="ACTIVITY_RAPPORT.PHP">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-activity"></i> <span class="nav-text">Activity Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Incident Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="INCIDENT_RAPPORT.PHP">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-exclamation-triangle"></i> <span class="nav-text">Incident Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Impound Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="IMPOND_RAPPORT.PHP">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-car-front"></i> <span class="nav-text">Impound Rapport</span>
              </a>
            <?php endif; ?>
          </li>
            <!-- Civiliant Rapport Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'OPEN_CASES.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="MANAGE_CIVILLIANT_RAPPORT.PHP">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civiliant Rapport</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Civilliant Rapport</span>
              </a>
            <?php endif; ?>
            <!-- Open Cases Sergeant -->
          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')  || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="OPEN_CASES.PHP">
                <i class="bi bi-folder"></i> <span class="nav-text">Open Cases</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-folder"></i> <span class="nav-text">Open Cases</span>
              </a>
            <?php endif; ?>
          </li>

          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')  || ($currentPage === 'OPEN_CASES.PHP')): ?>
              <a class="nav-btn active" href="MANAGE_CASES.PHP">
                <i class="bi bi-folder"></i> <span class="nav-text">Manage Cases</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-folder"></i> <span class="nav-text">Manage Cases</span>
              </a>
            <?php endif; ?>
          </li>

          <li class="nav-item">
            <?php if(($currentPage === 'HOMEP.PHP') || ($currentPage === 'ACTIVITY_RAPPORT.PHP') || ($currentPage === 'INCIDENT_RAPPORT.PHP') || ($currentPage === 'IMPOND_RAPPORT.PHP') || ($currentPage === 'MANAGE_CIVILLIANT_RAPPORT.PHP')  || ($currentPage === 'OPEN_CASES.PHP') || ($currentPage === 'MANAGE_CASES.PHP')): ?>
              <a class="nav-btn active" href="SIGNUPCM.PHP">
                <i class="bi bi-folder"></i> <span class="nav-text">Add Officer</span>
              </a>
            <?php else : ?>
              <a class="nav-btn disabled" href="#">
                <i class="bi bi-folder"></i> <span class="nav-text">Add Officer</span>
              </a>
            <?php endif; ?>
          </li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- User profile on the right -->
      <div class="user-profile-container">
        <?php
        echo '<table class="user-profile-table" style="background-color: rgb(24, 55, 39); border-collapse: collapse; width: 100%; color: white;">';
        echo '<tr>';
        // First row: Picture in left column, Name/Rank in right column
        echo "<td style='width: 80px; padding: 10px;'><img src='{$_SESSION['POLICIER']->image}' class='user-avatar mr-3' style='width: 60px; height: 60px; border-radius: 50%; object-fit: cover;'></td>";
        
        if($_SESSION["POLICIER"]->statue === 'on_duty') {
            echo "<td class='user-info' style='vertical-align: middle;'>{$_SESSION["POLICIER"]->rank}, {$_SESSION["POLICIER"]->nom}<p>Statue : <span class='text-center text-success'>On Duty</span></p></td>";
        } else {
            echo "<td class='user-info' style='vertical-align: middle;'>{$_SESSION["POLICIER"]->rank}, {$_SESSION["POLICIER"]->nom}<p>Statue : <span class='text-center text-danger'>Off Duty</span></p></td>";
        }
        echo '</tr>';

        // Second row: Status in left column, Button in right column
        echo '<tr>';
        if($_SESSION["POLICIER"]->statue === 'on_duty') {
            echo "<td colspan='2' style='padding: 10px;'><a class='nav-btn active pt-1 pb-1' href='setduty.php?statue={$_SESSION["POLICIER"]->statue}' style='background-color: rgb(218, 17, 17); display: block; text-align: center; padding: 8px; border-radius: 4px;'><i class='bi bi-box-arrow-in-right'></i> <span class='nav-text'>OFF DUTY</span></a></td>";
        }else{
            echo "<td colspan='2' style='padding: 10px;'><a class='nav-btn active pt-1 pb-1' href='setduty.php?statue={$_SESSION["POLICIER"]->statue}' style='background-color: rgb(51, 218, 17); display: block; text-align: center; padding: 8px; border-radius: 4px;'><i class='bi bi-box-arrow-in-right'></i> <span class='nav-text'>ON DUTY</span></a></td>";
        }
        echo '</tr>';

        // Third row: Long login button spanning both columns
        echo '<tr>';
        echo '<td colspan="2" style="padding: 10px;"><a class="nav-btn active pt-1 pb-1" href="Logoutp.php" style="background-color: rgb(205, 169, 65); display: block; text-align: center; padding: 8px; border-radius: 4px;"><i class="bi bi-box-arrow-in-left"></i> <span class="nav-text">Logout</span></a></td>';
        echo '</tr>';

        echo '</table>';
        ?>
      </div>
    </div>
  </nav>

  <!-- Main Content Container -->
  <div class="container-fluid main-content">
    <div class="row">
      <main class="col-12">