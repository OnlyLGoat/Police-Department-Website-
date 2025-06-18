<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="Js/script_regex.js"></script>
    <!-- Removed duplicate Bootstrap includes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        .video-bg {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.6);
        }
        .content-wrapper {
            padding-top: 100px;
            padding-bottom: 100px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        /* New styles for the carousel container */
        .carousel-container {
            width: 70%;
            margin: 0 auto;
        }
        .carousel-img {
            height: 600px; /* Reduced from 1000px for better proportions */
            object-fit: cover;
        }
    </style>
    <title>Document</title>
</head>
<body class="bg-success">

    <video autoplay muted loop class="video-bg">
        <source src="PDPics/pdvideo.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <div class="card">
        <div class="carousel-container"> <!-- Added container div -->
            <div id="demo" class="carousel slide m-5 bg-dark text-light rounded-2" data-bs-ride="carousel">
                <h1 class="text-center m-5 card-header">Welcome To The Home Page</h1>
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="3"></button>
                </div>

                <div class="carousel-inner card-body">
                    <div class="carousel-item active">
                        <img src="PDPics/pd4.jpg" alt="Los Angeles" class="d-block rounded-5 w-100 carousel-img">
                        <div class="carousel-caption">
                            <h3>Los Angeles</h3>
                            <p>We had such a great time in LA!</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="PDPics/pd1.jpg" alt="Chicago" class="d-block rounded-5 w-100 carousel-img">
                        <div class="carousel-caption">
                            <h3>Chicago</h3>
                            <p>Thank you, Chicago!</p>
                        </div> 
                    </div>
                    <div class="carousel-item">
                        <img src="PDPics/pd2.jpg" alt="New York" class="d-block rounded-5 w-100 carousel-img">
                        <div class="carousel-caption">
                            <h3>New York</h3>
                            <p>We love the Big Apple!</p>
                        </div>  
                    </div>
                    <div class="carousel-item">
                        <img src="PDPics/pd3.jpg" alt="New York" class="d-block rounded-5 w-100 carousel-img">
                        <div class="carousel-caption">
                            <h3>New York</h3>
                            <p>We love the Big Apple!</p>
                        </div>  
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        
        <p class="text-center ml-4 mr-4 card-footer bg-dark text-light rounded-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam ducimus pariatur accusamus, voluptatum quisquam doloremque. Perferendis dolorum ut aperiam, labore eos assumenda consequatur alias inventore blanditiis libero eveniet, fugiat hic possimus, aliquid commodi necessitatibus id vel at velit saepe molestiae. Aliquam ut ducimus dolor quidem rem illum at eaque necessitatibus laborum animi laudantium minima porro quaerat, maxime temporibus pariatur nemo aperiam? Facere similique eligendi esse ratione cum quae doloribus natus error recusandae blanditiis. Dignissimos recusandae placeat illo temporibus exercitationem facere! Consequatur at culpa dignissimos ipsam numquam officiis modi alias impedit minima, adipisci corrupti repellat nemo delectus, commodi rem optio aperiam vel aut quisquam nobis suscipit labore neque! Fugit eaque quidem hic quod ducimus consequatur autem fuga, culpa in nulla doloremque, iusto molestias placeat deleniti et reprehenderit neque nostrum aut quae officia dolore provident. Sapiente, est ipsum fugiat mollitia qui quaerat ullam? Laudantium vitae velit nisi repellendus at, in assumenda ipsa.</p>
        
        <div class="footer bg-dark text-light container-fluid card" style="width:80%;">
            <form id="form" class="card-body">
                <h1 class="card-title card-header">Contact Us</h1>
                <br>
                <div>
                    <input type="text" data-type="Nom" placeholder="Nom" class="form-control">
                    <p class="input-group-text text-danger"></p><br>
                </div>
                <div>
                    <input type="text" data-type="Email" placeholder="Email" class="form-control">
                    <p class="input-group-text text-danger"></p><br>
                </div>
                <div>
                    <input type="email" data-type="Mdp" placeholder="Confirm Email" class="form-control">
                    <p class="input-group-text text-danger"></p><br>
                </div>
                <div>
                    <textarea type="password" data-type="Mdp Confirmation" placeholder="Description" class="form-control"></textarea>
                    <p class="input-group-text text-danger p-2"></p><br>
                </div>
                <button type="submit" class="btn btn-outline-info btn-block card-footer" data-bs-toggle="modal" data-bs-target="#myModal"><b>Send</b></button>
            </form>
        </div>
    </div>
</body>
</html>