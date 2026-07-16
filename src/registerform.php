<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>User Register</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            .navbar {
                background-color: rgb(197, 74, 139); /* Maro */
            }
            .navbar-nav {
                margin-left: auto; /* Mută elementele la dreapta */
            }
            .form-register {
                background-color: #f8f9fa; /* Fundal alb */
                border-radius: 10px; /* Colțuri rotunjite */
                padding: 40px; /* Padding mărit */
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Umbră */
                width: 100%; /* Lățime */
                max-width: 500px; /* Lățime maximă */
            }
            .form-control {
                border-radius: 5px; /* Colțuri rotunjite */
                font-size: 1.2rem; /* Dimensiunea fontului mărită */
                padding: 10px; /* Padding mărit */
            }
            .btn-custom {
                background-color: rgb(197, 74, 139); /* Maro */
                color: white;
                font-size: 1.2rem; /* Dimensiunea fontului mărită */
                padding: 10px 20px; /* Padding mărit */
            }
            .btn-custom:hover {
                background-color:rgb(188, 33, 116); /* Maro mai închis */
            }
            .container-form {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .form-header {
                margin-bottom: 20px; /* Spațiu sub header */
                font-size: 2rem; /* Dimensiunea fontului mărită */
            }
        </style>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" style="color: white;" href="index.php">Grădina cu Flori</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" style="color: white;" aria-current="page" href="index.php">Acasa</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: white;" href="about.php">Despre noi</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">Produse</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="flori.php">Flori</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="listaflori.php">Lista fori</a></li>
                                <li><a class="dropdown-item" href="buchetespeciale.php">Buchete speciale</a></li>
                                <li><a class="dropdown-item" href="decoratiuni.php">Decoratiuni evenimente</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Register Form -->
        <div class="container container-form">
            <form method="post" action="register.php" name="form1" onsubmit="return checkCaptcha();" class="form-register">
                <h2 class="form-header text-center">Register</h2>
                <input type="text" name="nume" placeholder="Username" class="form-control mb-3" required>
                <input type="password" name="pass" placeholder="Password" class="form-control mb-3" required>
                <input class="form-control mb-3" type="text" id="cta" name="ct" value="####" readonly>
                <input class="form-control mb-3" type="text" id="ci" placeholder="Captcha" required>
                <button class="btn btn-custom mt-3" name="Register">Register</button>
                <input class="btn btn-custom mt-3" name="refresh" type="button" value="Refresh" id="refreshbtn" onclick="getNewCaptcha();">
                <div class="text-center mt-3">
                    <p>Ai deja cont? Apasa <a href="login_form.php">aici</a>.</p>
                </div>
            </form>
        </div>
        <!-- Footer -->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS -->
        <script src="js/scripts.js"></script>
        <!-- Captcha Script -->
        <script>
            var captcha, chars;

            function getNewCaptcha(){
                chars="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                captcha = chars[Math.floor(Math.random()*chars.length)];
                for(var i=0; i<5; i++){
                    captcha = captcha + chars[Math.floor(Math.random()*chars.length)];
                }
                form1.ct.value=captcha;
            }

            function checkCaptcha(){
                var check=document.getElementById("ci").value;
                if(captcha==check){
                    return true;
                }
                else{
                    alert("Invalid captcha");
                    return false;
                }
            }
            getNewCaptcha();
        </script>
    </body>
</html>
