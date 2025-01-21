<?php 
$chyba = null;
// načtení souboru stranky.php, kde je vytvořená třída Stranka a pole $seznamStranek
require "stranky.php";

//session - ukládá přihlášení uživatele
session_start();

$chyba = "";
// zpracování přihlašovacího formuláře

if (array_key_exists("prihlasit", $_POST))
{
    $jmeno = $_POST["jmeno"];
    $heslo = $_POST["heslo"];

    if ($jmeno == "admin" && $heslo == "1234")
    {
        // uživatel zadal platné přihlašovací údaje
        $_SESSION["prihlasenyUzivatel"] = $jmeno;
    }
    else
    {
        // špatné přihlašovací údaje
        $chyba = "Nesprávné přihlašovací údaje";
    }
}

//zpracování odhlašovacího formuláře
if (array_key_exists("odhlasit", $_POST))
{
    unset($_SESSION["prihlasenyUzivatel"]);
    header("Location: ?");
}

// zpracovani akci v administraci je pouze pro prihlasene uzivatele
if (array_key_exists("prihlasenyUzivatel", $_SESSION))
{
    // Pomocná proměnná představující stránku pro admin na editování

    $instanceAktualniStranky = null;

    // zpracování výběrů AktuálníStránky
    if (array_key_exists("stranka", $_GET))
    {
        $idStranky = $_GET["stranka"];

        // vytažení klíče z pole $seznamStranek a podle proměnné $idStranky (pomocná proměnná)
        // hodnota $idStranky se bude odvíjet díky tomu, na co jsme klikli
        // to zajišťuje řádek no. 45.
        $instanceAktualniStranky = $seznamStranek[$idStranky];
    }

    //zpracování zlačítka Přidat
    if (array_key_exists("pridat", $_GET))
    {
        $instanceAktualniStranky = new Stranka("", "", "");
    }    

    // tlačítko pro mazání stránky
    if (array_key_exists("smazat", $_GET))
    {
        $instanceAktualniStranky->smazat();

        //přesměrování po smazání stránky, aby nezůstala v prohlížeči původní adresa
        header("Location: ?");
    }    
    //zpracování formuláře pro uložení
    if (array_key_exists("ulozit", $_POST))
    {
        //poznamenáme si původní id, než si ho přepíšeme
        $puvodniId = $instanceAktualniStranky->id;

        //změna
        $instanceAktualniStranky->id = $_POST["id"];
        $instanceAktualniStranky->titulek = $_POST["titulek"];
        $instanceAktualniStranky->menu = $_POST["menu"];
        // zavolame funkci pro uložení změněných hodnot do databáze
        $instanceAktualniStranky->ulozit($puvodniId);

        //ukládání obsahu stránky
        $obsah = $_POST["obsah"];
        $instanceAktualniStranky->setObsah($obsah);

        //přesměrujeme se na url s editací stránky s novým id
        header("Location:?stranka=".urlencode($instanceAktualniStranky->id));

    }

    //zpracování požadavku změny pořadí stránek z javascriptu (ajaxem)
    if (array_key_exists("poradi", $_GET))
    {
        $poradi = $_GET["poradi"];
        // zavolani funkce pro nastaveni poradi a ulozeni do db
        Stranka::nastavitPoradi($poradi);
        // odpovime javascriptu ze je to ok
        echo "OK";
        // skript ukoncime aby do javascriptu se negeneroval zbytek
        // html stranky
        exit;
    }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
</head>
<body>
    <div class="admin-body">
    <?php
    // podmínka - Formulář se objeví pouze, pokud pole $_SESSION je prázdné
    if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false)
    {
        ?>
         <main class="form-signin">
            <form method="post">
                <h1 class="h3 mb-3 fw-normal">Přihlašte se prosím</h1>

                <?php 
                // alert chyby
                if ($chyba != "") { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $chyba; ?>
                        </div>
                    <?php } ?>

                <div class="form-floating">
                    <input name="jmeno" type="text" class="form-control" id="floatingInput" placeholder="login">
                    <label for="floatingInput">Přihlašovací jméno</label>
                </div>
                <div class="form-floating">
                    <input name="heslo" type="password" class="form-control" id="floatingPassword" placeholder="heslo">
                    <label for="floatingPassword">Heslo</label>
                </div>

                <button name="prihlasit" class="w-100 btn btn-lg btn-primary" type="submit">Přihlásit</button>
            </form>
        </main>       
    
        <?php 
    }
    else
    {
    echo "<main class='admin'>";
        // sekce po úspěšném přihlášení
        ?>
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                <div>Přihlášený uživatel: <?php echo $_SESSION["prihlasenyUzivatel"]; ?></div>

                <div class="col-md-3 text-end">
                    <form method='post'>
                        <button name='odhlasit' class="btn btn-outline-primary me-2">Odhlásit</button>
                    </form>
                </div>
            </header>
        </div>

        <?php
        //vypíšeme seznam stránek, které můžeme editovat
        echo "<ul id='stranky' class='list-group'>";
        foreach ($seznamStranek as $idStranky => $instanceStranky)
        {
            $active = '';
            $buttonClass ='btn btn-primary';
            if ($instanceStranky == $instanceAktualniStranky)
            {
                $active = 'active';
                $buttonClass = 'btn-secondary';
            }
            //volání tlačítek k: editování - zobrazení stránky - názvu stránky
            echo "<li class='list-group-item $active' id='$instanceStranky->id'>
            
            <a class='btn $buttonClass' href='?stranka=$instanceStranky->id'><i class='fa-solid fa-pen-to-square'></i></a>

            <a class='smazat btn $buttonClass' href='?stranka=$instanceStranky->id&smazat'><i class='fa-solid fa-trash-can'></i></a>

            <a class='btn $buttonClass' href='$instanceStranky->id' target='_blank'><i class='fa-solid fa-eye'></i></a>
            
            <span>$instanceStranky->id</span>
            </li>";
        }
        echo "</ul>";

        //formulář s tlačítkem pro přidání stránky 
       
        ?>

        <div class="container">
                <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                    <div class="col-md-3 text-start">
                        <form>
                            <button name='pridat' class="btn btn-outline-primary me-2">Přidat</button>
                        </form>
                    </div>
                </header>
            </div>

        <?php

        // editační formulář
        // Pokud je nějaká stránka zobrazená v proměnné (buďto je null nebo není)
        if ($instanceAktualniStranky != null)
        {
            echo "<div class='alert alert-secondary' role='alert'><h1>";
            if ($instanceAktualniStranky->id == "")
            {
                echo "Přidávání stránky";
            }
            else
            {
                echo "Editace stránky: $instanceAktualniStranky->id";
            }
            echo "</h1></div>";         

            ?>
            <form method="post">

        <!-- 3x div pro přidání do administrace možnost editace ID, titulku, menu uživatelem u každé stránky-->
                <div class="form-floating">
                    <input 
                    class="form-control"
                    type="text" 
                    name="id" 
                    id="id"
                    value="<?php echo htmlspecialchars($instanceAktualniStranky->id)?>"
                    placeholder="Menu"
                    >
                    <label for="id">Id</label>
                </div>

                <div class="form-floating">
                        <input
                            class="form-control"
                            type="text"
                            name="titulek"
                            id="titulek"
                            value="<?php echo htmlspecialchars($instanceAktualniStranky->titulek) ?>"
                            placeholder="Titulek"
                            >
                        <label for="titulek">Titulek</label>
                    </div>

                    <div class="form-floating">
                        <input
                            class="form-control"
                            type="text"
                            name="menu"
                            id="menu"
                            value="<?php echo htmlspecialchars($instanceAktualniStranky->menu) ?>"
                            placeholder="Menu"
                            >
                        <label for="menu">Menu</label>
                    </div>

               <textarea id="obsah" name="obsah" cols="80" rows="15"><?php 
               //html specialchars -> vytváří speciální entity, aby se html kod zobrazil
               //getObsah() -> vlastní funkce, která vrací obsah stranky napr. galerie, nabidka a přidá ".html" pro prohlížeč
               //Obsah stranky vrací podle toho, na co jsme kliknuli díky $instanceAktualniStranky
               echo htmlspecialchars($instanceAktualniStranky->getObsah());
               ?></textarea>
               <br>
               <button name="ulozit" class="btn btn-primary">Uložit</button> 
            </form>
            <!-- Vložení knihovny tinymce (nahrazení html za editor pro uživatele) -->
            <script src="vendor\tinymce\tinymce\tinymce.min.js"></script>
            <!-- inicializace tinymce a pomocí id="obsah" v textarea je selector: #obsah -->
            <script type="text/javascript">
                tinymce.init({
                    sandbox_iframes: false,
                    selector: '#obsah',
                    language: 'cs',
                    // dynamické načítání, kde je umístěná složka
                    language_url: '<?php echo dirname($_SERVER["PHP_SELF"]); ?>/vendor/tweeb/tinymce-i18n/langs/cs.js',
                    // nastavení velikosti editovacího okna
                    height: '50vh',
                    // aby se kod nepřepisoval na entity (kvůli diakritice, speciální znakum atp.)
                    entity_encoding: "raw",
                    //aby nemizely např. ikonky, kde je prázdné místo
                    verify_html: false,
                    // nastavení ikonek pro obsah editoru a google fontů
                    content_css: [
                        'css/reset.css',
                        'css/section.css',
                        'css/style.css',
                        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
                        'https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap',
                    ],
                    //dostání stylu
                    body_id: "content",
                    // pluginy na nastavení editoru
                    plugins: 'advlist anchor autolink charmap code colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists nonbreaking noneditable pagebreak paste preview print save searchreplace tabfocus table textcolor textpattern visualchars',
                    // tlačítka na nastavení v toolbarech
                    toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor",
                    toolbar2: "link unlink anchor | fontawesome | image media | responsivefilemanager | preview code",
                    external_plugins: {
			        'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
			        'filemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/filemanager/plugin.min.js',
		            },
		            external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/",
		            filemanager_title: "File manager",
            });
            </script>
            <?php
        }

        echo "</main>";
    }    
        ?>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>