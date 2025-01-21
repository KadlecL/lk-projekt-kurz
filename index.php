<?php
require "vendor/autoload.php";
require "stranky.php";

if (array_key_exists("stranka", $_GET))
{
    $stranka = $_GET["stranka"];    

    //kontrola existence stranky v $seznamStranek
    if(array_key_exists($stranka, $seznamStranek) == false)
    {
        //stranka neexistuje

        $stranka = "404";

        // že stránka neexistuje pro prohlížeč

        http_response_code(404);
    }
}
else
{
    //načtení první stranku z pole $seznamStranek
    $stranka = array_key_first($seznamStranek);
}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
    echo $seznamStranek[$stranka]->titulek;
    ?></title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/section.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="vendor\dist\photoswipe.css">
</head>
<body>
    <header>
        <menu>
            <div class="container">
                <a class="logo" href="./">
                    <img src="img/logo.png" alt="Logo PrimaKavárna" srcset="" width="142" height="80">
                 </a>
                <nav>
                    <ul>
                        <?php 
                        foreach ($seznamStranek as $idStranky => $instanceStranky)
                        {
                            if ($instanceStranky->menu != "")
                            {
                                echo "<li><a href='$instanceStranky->id'>$instanceStranky->menu</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </menu>

        <div class="nadpis">
            <h2>PrimaKavárna</h2>
            <h3>Jsme tu pro vás již od roku 2002</h3>
            <div class="social">
                <a class="fb" href="https://www.facebook.com"><i class="fa-brands fa-facebook"></i></a>
                <a class="ig" href="https://www.instagram.com"><i class="fa-brands fa-square-instagram"></i></i></a>
                <a class="yt" href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></i></a>
            </div>
        </div>
    </header>

    <section id = "content">
    <?php
                $obsah = $seznamStranek[$stranka]->getObsah();
                echo primakurzy\Shortcode\Processor::process('shortcodes', $obsah);
         ?>

    </section>


    <footer>
        <div class="container">
            <nav>
                <h3>Menu</h3>
                <ul>
                <?php 
                        foreach ($seznamStranek as $idStranky => $instanceStranky)
                        {
                            if ($instanceStranky->menu != "")
                            {
                                echo "<li><a href='$instanceStranky->id'>$instanceStranky->menu</a></li>";
                            }
                        }
                ?>
                </ul>
            </nav>

            <div class="kontakt">
                <h3>Kontakt</h3>
                <address>
                    <a href="https://mapy.cz/s/pobarojoka" target="_blank">
                        PrimaKavárna<br>
                        Jablonského 2<br>
                        Praha, Holešovice
                    </a>
                </address>
            </div>

            <div class="otevreno">
                <h3>Otevřeno</h3>
                <table>
                    <tr>
                        <th>Po - Pá:</th>
                        <td>8h - 20h</td>
                    </tr>
                    <tr>
                        <th>So:</th>
                        <td>10h - 22h</td>
                    </tr>
                    <tr>
                        <th>Ne:</th>
                        <td>12h - 20h</td>
                    </tr>
                </table>
            </div>
        </div>
    </footer>
    <div id="nahoru"><i class="fa-solid fa-angle-up"></i></div>
    <script src="js/index.js"></script>
</body>
</html>