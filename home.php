<?php  
session_start();  
include("connect.php");  
?>  
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>VolunTree</title>  
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>  
    <style>  
        body {  
            font-family: Arial, sans-serif;  
            margin: 0;  
            padding: 0;  
            background-color: #f4f4f4;  
            text-align: center;  
        }  

        .navbar {  
            display: flex;  
            justify-content: space-between;  
            align-items: center;  
            background-color: #007BFF;  
            color: white;  
            padding: 15px 20px;  
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);  
        }  

        .logo {  
            font-size: 24px;  
            font-weight: bold;  
        }  

        .btn, .btn-outline {  
            padding: 10px 20px;  
            border: none;  
            border-radius: 5px;  
            cursor: pointer;  
            font-size: 16px;  
        }  

        .btn {  
            background-color: white;  
            color: #007BFF;  
        }  

        .btn-outline {  
            background-color: transparent;  
            color: white;  
            border: 2px solid white;  
        }  

        .hero {  
            position: relative;  
            height: 500px;  
            overflow: hidden;  
            color: white;  
            display: flex;  
            flex-direction: column;  
            justify-content: center;  
            align-items: center;  
        }  

        .hero::before {  
            content: '';  
            position: absolute;  
            top: 0;  
            left: 0;  
            width: 100%;  
            height: 100%;  
            background: rgba(0, 0, 0, 0.5);  
            z-index: 1;  
        }  

        .hero h2, .hero p, .hero .buttons {  
            z-index: 2;  
            position: relative;  
        }  

        .slider {  
            position: absolute;  
            top: 0;  
            left: 0;  
            width: 100%;  
            height: 100%;  
        }  

        .slider img {  
            position: absolute;  
            top: 0;  
            left: 0;  
            width: 100%;  
            height: 100%;  
            object-fit: cover;  
            opacity: 0;  
            transition: opacity 1s ease-in-out;  
        }  

        .slider img.active {  
            opacity: 1;  
        }  

        .slider-nav {  
            position: absolute;  
            top: 50%;  
            width: 100%;  
            display: flex;  
            justify-content: space-between;  
            transform: translateY(-50%);  
            z-index: 3;  
        }  

        .slider-nav button {  
            background: rgba(255, 255, 255, 0.8);  
            border: none;  
            color: #007BFF;  
            font-size: 24px;  
            padding: 10px;  
            cursor: pointer;  
            border-radius: 50%;  
            width: 40px;  
            height: 40px;  
            display: flex;  
            align-items: center;  
            justify-content: center;  
        }  

        .slider-nav button:hover {  
            background: rgba(255, 255, 255, 1);  
        }  

        .buttons {  
            margin-top: 20px;  
        }  

        .buttons .btn {  
            font-size: 18px;  
            padding: 12px 24px;  
            font-weight: bold;  
        }  
    </style>  
</head>  
<body>  
    <nav class="navbar">  
        <h1 class="logo">VolunTree</h1>  
        <div>  
            <button class="btn-outline" onclick="window.location.href='login.php'">Login</button>  
            <button class="btn-outline" onclick="window.location.href='register.php'">Register</button>  
        </div>  
    </nav>  

    <header class="hero">  
        <div class="slider">  
            <img src="https://i.pinimg.com/736x/6d/0b/76/6d0b7670918099732fd087ff78d4c2f1.jpg" alt="Group of volunteers" class="active">  
            <img src="https://i.pinimg.com/736x/3e/93/fe/3e93feb0777eb984125202906e922840.jpg" alt="Volunteers distributing food">  
            <img src="https://i.pinimg.com/736x/17/e5/ea/17e5ea4493c00640fe49e1f49fdfb0fd.jpg" alt="Trash Pick Up"> 
            <img src="https://i.pinimg.com/736x/86/65/5c/86655c9c63885905cc81ed7881c089de.jpg" alt="Volunteering">  
            <img src="https://i.pinimg.com/736x/65/de/67/65de6738127bbb9dac0675661086b8b5.jpg" alt="Tree Planting">  
            <img src="https://i.pinimg.com/736x/51/57/9a/51579ab90620cb36fb8592b19cd4280f.jpg" alt="Helping hands">  
            <img src="https://i.pinimg.com/736x/e4/da/da/e4dada85dfdbfec12910d4d79cadbd12.jpg" alt="Volunteering">  
        </div>  
        <div class="slider-nav">  
            <button onclick="prevSlide()">&#10094;</button>  
            <button onclick="nextSlide()">&#10095;</button>  
        </div>  
        <h2>Join Hands, Make a Difference!</h2>  
        <p>Connecting volunteers with opportunities to create impact.</p>  
        <div class="buttons">  
            <button class="btn" onclick="window.location.href='register.php'">Get Started</button>  
        </div>  
    </header>  

    <footer class="footer">  
        <p>2025 Volunteer Connect. All rights reserved.</p>  
    </footer>  

    <script>  
        let slides = document.querySelectorAll('.slider img');  
        let currentSlide = 0;  

        function showSlide(index) {  
            slides.forEach((slide) => slide.classList.remove('active'));  
            slides[index].classList.add('active');  
        }  

        function nextSlide() {  
            currentSlide = (currentSlide + 1) % slides.length;  
            showSlide(currentSlide);  
        }  

        function prevSlide() {  
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;  
            showSlide(currentSlide);  
        }  

        setInterval(nextSlide, 5000);  
    </script>  
</body>  
</html>
