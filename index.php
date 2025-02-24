<?php  
include './connection/dbconn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Design Carousel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
    
    <style>
        /* Fixed Navbar */
        .navbar {
            background-color: #082759;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand, .nav-link {
            color: whitesmoke !important;
        }

        /* Add padding to body to prevent content from being hidden under navbar */
        body {
            padding-top: 60px;
        }

        /* Responsive Carousel */
        .carousel-item img {
        height: 500px;
        object-fit: cover;
        border-radius: 20px; /* Adjust this value as needed */
    }

    /* Adjust for mobile screens */
    @media (max-width: 768px) {
        .carousel-item img {
            height: 300px;
            border-radius: 15px; /* Smaller border-radius for mobile */
        }
    }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">PNP Police Station, Echague, Isabela</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="reg/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Carousel -->

<br>
<div class="container mt-4">

    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="pic.jpg" class="d-block w-100" alt="Modern Home">
            </div>
            <div class="carousel-item">
                <img src="pic1.jpg" class="d-block w-100" alt="Villa Home">
            </div>
            <div class="carousel-item">
                <img src="pic2.jpg" class="d-block w-100" alt="Interior Design">
            </div>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
