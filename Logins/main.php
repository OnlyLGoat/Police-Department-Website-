<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Department Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Css/bootstrap.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .video-bg {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.6);
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .carousel-container {
            background-color: rgba(18, 62, 45, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
            margin-bottom: 2rem;
            max-width: 900px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
        .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 1.5rem;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
        }
        .access-portal {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 2rem;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-portal {
            padding: 1rem;
            font-size: 1.1rem;
            margin: 0.5rem 0;
            transition: all 0.3s;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .btn-civilian {
            background-color: #28a745;
            border: none;
        }
        .btn-officer {
            background-color: #0056b3;
            border: none;
        }
        .btn-civilian:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .btn-officer:hover {
            background-color: #004085;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 86, 179, 0.3);
        }
        .welcome-title {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            padding: 1.5rem 0;
            border-bottom: 2px solid rgba(255,255,255,0.1);
        }
        .emergency-notice {
            font-size: 0.9rem;
            background-color: rgba(220, 53, 69, 0.1);
            padding: 0.5rem;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    
    <video autoplay muted loop class="video-bg">
        <source src="PDPics/pdvideo.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <div class="main-content">
        <div class="carousel-container">
            <h1 class="text-center text-light welcome-title">Welcome To The Police Department</h1>
            <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="PDPics/pd4.jpg" class="d-block w-100" alt="Community Safety">
                        <div class="carousel-caption">
                            <h3>Community Safety</h3>
                            <p>Dedicated to protecting our neighborhoods</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="PDPics/pd1.jpg" class="d-block w-100" alt="Professional Team">
                        <div class="carousel-caption">
                            <h3>Professional Team</h3>
                            <p>Highly trained officers ready to serve</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="PDPics/pd2.jpg" class="d-block w-100" alt="Modern Equipment">
                        <div class="carousel-caption">
                            <h3>Modern Equipment</h3>
                            <p>State-of-the-art police vehicles</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="PDPics/pd3.jpg" class="d-block w-100" alt="Honor and Integrity">
                        <div class="carousel-caption">
                            <h3>Honor and Integrity</h3>
                            <p>Serving with pride and dedication</p>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <div class="access-portal text-center">
            <h4 class="mb-4">Please select your access type:</h4>
            <div class="d-grid gap-3">
                <a href="loginc.php" class="btn btn-civilian btn-portal text-white">
                    <i class="bi bi-person-fill me-2"></i> Enter as Civilian
                </a>
                <a href="loginp.php" class="btn btn-officer btn-portal text-white">
                    <i class="bi bi-shield-fill me-2"></i> Enter as Police Officer
                </a>
            </div>
            <div class="mt-4 emergency-notice">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Emergency? Call 911 immediately</strong>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="Js/script_regex.js"></script>
</body>
</html>