
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!---icon link-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
    <!--icon link-->

    <link rel="stylesheet" href="kafe.css">

</head>

<body>
    <!--- Header Başlangıç-->
    <header>
        <div class="header">
            <div class="container">
                <div class="header-navbar">
                    <div class="header-logo">
                        <h2>TARÇIN</h2>

                    </div>
                    <div class="header-menu">
                        <ul id="menuItems">
                            <li><a href="#">Menü</a></li>
                            <li><a href="#">Hakkımızda</a></li>
                            <li><a href="#">Sosyal Medya</a></li>
                            <li><a href="#">İletişim</a></li>
                        </ul>
                        <span class="material-symbols-outlined" id="menu-icon" onclick="menuToggle();">
                            menu
                        </span>
                    </div>
                </div>
                <div class="header-text">
                    <h1><span class="first-letter"> W</span>elcome</h1>
                    <h3>Fill Me up With Coffe</h3>
                    <a href="#" class="btn-yemek">Get Started</a>
                </div>
            </div>
        </div>
    </header>
    <!--- Header Bitiş-->

    <!--- About Başlangıç-->
    <section id="about">
        <div class="about">
            <div class="container">
                <div class="about-tittle">
                    <h2>ABOUT</h2>
                </div>
                <div class="about-content">
                    <div class="about-img">
                        <img class="" src="resim/about-2.jpg" alt="">
                    </div>
                    <div class="about-text">
                        <h4>Our Story</h4>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Molestias est quod sequi, quam
                            recusandae reiciendis?<br>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Et omnis
                            repellat excepturi, recusandae similique quasi corrupti, asperiores facilis nemo totam
                            libero ea doloribus saepe eius animi ad odit sunt inventore!<br></p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!--- About Bitiş-->

    <!---portfolio başlangıç--->

    <section id="portfolio">
        <div class="portfolio">
            <div class="portfolio-item">
                <img src="resim/americano.jpg" alt="kahve">
                <div class="overlay">
                    <span class="material-symbols-outlined">
                        local_cafe
                    </span>

                </div>
            </div>
            <div class="portfolio-item">
                <img src="resim/brownie.jpg" alt="tatlı">
                <div class="overlay">
                    <span class="material-symbols-outlined">
                        cookie
                        </span>
                </div>
            </div>
            <div class="portfolio-item">
                <img src="resim/burger.jpg" alt="burger">
                <div class="overlay">
                    <span class="material-symbols-outlined">
                        lunch_dining
                        </span>
                </div>
            </div>
            <div class="portfolio-item">
                <img src="resim/espresso.jpg" alt="kahve">
                <div class="overlay">
                    <span class="material-symbols-outlined">
                        local_cafe
                    </span>
                </div>
            </div>
            <div class="portfolio-item">
                <img src="resim/pizza.jpg" alt="pizza">
                <div class="overlay">
                    <span class="material-symbols-outlined">
                        local_pizza
                    </span>
                </div>
            </div>
            <div class="portfolio-item">
                <img src="resim/tost.jpg" alt="tost">
                <div class="overlay">
                    <span class="material-symbols-outlined">
                        breakfast_dining
                        </span>
                </div>
            </div>
        </div>

    </section>


    <!---potrfolio bitiş--->
    <!--Discover başlangıç-->
    <section id="about">
        <div class="about">
            <div class="container">
                <div class="about-tittle">
                    <h2>DİSCOVER</h2>
                </div>
                <div class="about-content">
                    <div class="about-img">
                        <img class="" src="resim/menu.jpg" alt="">
                    </div>
                    <div class="about-text">
                        <h4>Our Story</h4>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Molestias est quod sequi, quam
                            recusandae reiciendis?<br>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Et omnis
                            repellat excepturi, recusandae similique quasi corrupti, asperiores facilis nemo totam
                            libero ea doloribus saepe eius animi ad odit sunt inventore!<br></p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!--Discover bitiş-->
    <!--Contact başlangıç-->
    <section id="contact">
        <div class="contact">
            <div class="container">
                <div class="page-title">
                    <h2>CONTACT</h2>
                </div>
                <div class="contact-content">
                    <div class="contact-item">
                        <span class="material-symbols-outlined">
                            call
                        </span>


                        <p>0534 587 4578</p><br>
                        <p>0212 456 9875</p><br>

                    </div>

                    <div class="contact-item">
                        <span class="material-symbols-outlined">
                            location_on
                        </span>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>


                    </div>

                    <div class="contact-item">
                        <span class="material-symbols-outlined">
                            mail
                        </span>
                        <p>tarcıncoffe@gmail.com</p>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!--Contact bitiş-->
    <!--footer başlangıç-->
    <footer id="footer">
        <div class="footer">
            <div class="footer-content">
                <div class="page-tittle">
                    <h2>ABOUT US</h2>

                </div>
                <div class="footer-copyright">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, veniam?</p>
                    <br>
                </div>
                <nav class="footer-social">
                    <li>
                        <a href="#"><img src="icons/google.svg" alt=""></a>
                        <a href="#"><img src="icons/instagram.svg" alt=""></a>
                        <a href="#"><img src="icons/x-twitter2.svg" alt=""></a>
                    </li>
                </nav>
            </div>
        </div>
    </footer>
    <!--footer bitiş-->


    <script>
        var menuItems
    </script>




</body>

</html>
