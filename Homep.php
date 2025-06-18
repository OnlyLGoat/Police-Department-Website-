
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Police Department Management System</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    :root {
      --pd-blue: #1a237e;
      --pd-blue-light: #303f9f;
      --pd-red: #c62828;
      --pd-green: #2e7d32;
      --pd-amber: #ff8f00;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    
    .navbar-pd {
      background-color: var(--pd-blue) !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    .nav-link {
      font-weight: 500;
      padding: 0.5rem 1rem;
      margin: 0 0.1rem;
      border-radius: 4px;
      transition: all 0.2s;
    }
    
    .nav-link:hover, .nav-link.active {
      background-color: rgba(255,255,255,0.15);
    }
    
    .stat-card {
      border: none;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      transition: transform 0.2s;
      overflow: hidden;
      position: relative;
    }
    
    .stat-card:hover {
      transform: translateY(-3px);
    }
    
    .stat-card .card-icon {
      position: absolute;
      right: 20px;
      top: 20px;
      opacity: 0.2;
      font-size: 2.5rem;
    }
    
    .stat-card.officers {
      background-color: var(--pd-blue-light);
    }
    
    .stat-card.incidents {
      background-color: var(--pd-red);
    }
    
    .stat-card.cases {
      background-color: var(--pd-amber);
    }
    
    .stat-card.vehicles {
      background-color: var(--pd-green);
    }
    
    .recent-activity {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .activity-table {
      margin-bottom: 0;
      border-radius: 10px;
    }
    
    .activity-table th {
      border-top: none;
      font-weight: 600;
      color: #495057;

    }
    
    .badge-status {
      padding: 0.35em 0.65em;
      font-weight: 500;
      letter-spacing: 0.5px;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
    }
    
    .user-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
    }
    
    .main-content {
      padding-top: 20px;
      padding-bottom: 40px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    
    .section-title {
      font-weight: 600;
      color: #343a40;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #e9ecef;
    }

    .page-content {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 0 15px;
    }

    @media (max-width: 768px) {
      .stat-card .card-text.display-4 {
        font-size: 2rem;
      }
      .stat-card .card-icon {
        font-size: 2rem;
      }
    }

    @media (max-width: 576px) {
      .col-md-3 {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }
  </style>
</head>
<body style="background-color: rgba(18,62,45,255);">
  <?php
    require_once 'connect.php';
    session_start();
    if(isset($_SESSION['POLICIER'])){
      if($_SESSION['POLICIER']->Acc_Verify === 'Verify'){
        try {
          $stmt = $pdo->prepare("SELECT * FROM officres WHERE statue = ?");
          $stmt->execute(['on_duty']);
          $officersOnDuty = $stmt->rowCount();

          $stmt = $pdo->prepare("SELECT * FROM incidents WHERE DATE(created_at) = CURDATE()");
          $stmt->execute();
          $incidentsToday = $stmt->rowCount();
          
          $stmt = $pdo->prepare("SELECT * FROM cases WHERE status = ?");
          $stmt->execute(['open']);
          $openCases = $stmt->rowCount();

          $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE status = ?");
          $stmt->execute(['impounded']);
          $impoundedVehicles = $stmt->rowCount();

          $stmt = $pdo->prepare("
            SELECT 
              a.time,
              o.nom as officer_name,
              a.activity_type,
              a.zone,
              a.status
            FROM activities a
            JOIN officres o ON a.officer_id = o.id
            ORDER BY a.time DESC
            LIMIT 5
          ");
          $stmt->execute();
          $recentActivity = $stmt->fetchAll();
        } catch(PDOException $e) {
          error_log("Query failed: " . $e->getMessage());
          $error = "Failed to fetch data. Please try again later.";
        }
      }
    }else{
      header('Location: Logins/loginp.php');
    }

  ?>

  <?php  include 'header2.php'; ?>

  <div class="container-fluid main-content">
    <div class="row justify-content-center">
      <main class="col-12 col-lg-10">
        <div class="page-content">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
              <?php echo htmlspecialchars($error); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <div id="dashboard-page" class="active-page">
            <div class="row justify-content-center mt-4">
              <div class="col-6 col-md-3 mb-4">
                <div class="card stat-card officers text-white h-100">
                  <div class="card-body">
                    <i class="bi bi-shield-check card-icon"></i>
                    <h5 class="card-title">Officers On Duty</h5>
                    <p class="card-text display-4 fw-bold"><?php echo $officersOnDuty; ?></p>
                    <p class="card-text"><small>Currently active</small></p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-3 mb-4">
                <div class="card stat-card incidents text-white h-100">
                  <div class="card-body">
                    <i class="bi bi-exclamation-triangle card-icon"></i>
                    <h5 class="card-title">Incidents Today</h5>
                    <p class="card-text display-4 fw-bold"><?php echo $incidentsToday; ?></p>
                    <p class="card-text"><small>Reported today</small></p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-3 mb-4">
                <div class="card stat-card cases text-white h-100">
                  <div class="card-body">
                    <i class="bi bi-folder card-icon"></i>
                    <h5 class="card-title">Open Cases</h5>
                    <p class="card-text display-4 fw-bold"><?php echo $openCases; ?></p>
                    <p class="card-text"><small>Under investigation</small></p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-3 mb-4">
                <div class="card stat-card vehicles text-white h-100">
                  <div class="card-body">
                    <i class="bi bi-car-front card-icon"></i>
                    <h5 class="card-title">Impounded Vehicles</h5>
                    <p class="card-text display-4 fw-bold"><?php echo $impoundedVehicles; ?></p>
                    <p class="card-text"><small>In custody</small></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="recent-activity p-4 mt-4" style="background-color: rgb(255, 255, 255);">
              <h2 class="section-title">
                <i class="bi bi-activity me-2"></i>Recent Activity
              </h2>
              <div class="table-responsive">
                <table class="table activity-table" style="border-radius: 100px;">
                  <thead style="background-color: rgba(18,62,45,255);">
                    <tr>
                      <th class="text-light"><i class="bi bi-clock me-1 "></i> Time</th>
                      <th class="text-light"><i class="bi bi-person-badge me-1"></i> Officer</th>
                      <th class="text-light"><i class="bi bi-activity me-1"></i> Activity</th>
                      <th class="text-light"><i class="bi bi-pin-map me-1"></i> Zone</th>
                      <th class="text-light"><i class="bi bi-circle-fill me-1"></i> Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($recentActivity as $activity): ?>
                      <tr>
                        <td class="text-dark"><b><?php echo date('H:i', strtotime($activity['time'])); ?></b></td>
                        <td class="text-dark"><?php echo htmlspecialchars($activity['officer_name']); ?></td>
                        <td class="text-dark"><?php echo htmlspecialchars($activity['activity_type']); ?></td>
                        <td class="text-dark"><?php echo htmlspecialchars($activity['zone']); ?></td>
                        <td class="text-dark">
                          <span class="badge badge-status p-2 bg-<?php echo getStatusClass($activity['status']); ?>">
                            <?php echo htmlspecialchars($activity['status']); ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script>
    function logout() {
      window.location.href = 'logoutp.php';
    }
    
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function() {
        document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>
</body>
  <?php
    function getStatusClass($status) {
      switch(strtolower($status)) {
        case 'completed': return 'success';
        case 'in_progress': return 'primary';
        case 'pending': return 'warning';
        case 'urgent': return 'danger';
        default: return 'secondary';
      }
    }

    $pdo = null;
  ?>
</body>
</html>