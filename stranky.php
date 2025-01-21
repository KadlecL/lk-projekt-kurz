<?php

// vytvoření připojení na databázi
$db = new PDO(
    "mysql:host=localhost;dbname=primakavarna;charset=utf8", // spojení
    "root", // jmeno
    "", // heslo
    // pole, které v případě chyby vyhazuje výjimky
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    ),
);

class Stranka
{
    public $id;
    public $titulek;
    public $menu;
    // vytvoření konstruktoru jako základních parametru pro $seznamStranek
    function __construct($id, $titulek, $menu)
    {
        $this->id = $id;
        $this->titulek = $titulek;
        $this->menu = $menu;
    }
    // funkce, která vrací obsah stránky.
    function getObsah()
    {
        //return file_get_contents("$this->id.html"); -> Původní načítání podle souboru

        //nacteni obsahu stranky z databaze
        global $db;
        $dotaz = $db->prepare("SELECT obsah FROM stranka WHERE id = ?");
        $dotaz->execute([$this->id]);

        $vysledek = $dotaz->fetch();
        // pokud by databáze nic nevrátila, tak vrátíme prázdný obsah
        if ($vysledek == false)
        {
            return "";
        }
        else
        {
            return $vysledek["obsah"];
        }
    
    }

    // do $obsah se ukládá to, co admin napsal do textarea
    function setObsah($obsah)
    {

        //file_put_contents("$this->id.html", $obsah); -> původní souborové ukládání
        global $db;

        $dotaz = $db->prepare("UPDATE stranka SET obsah = ? WHERE id = ?");
        $dotaz->execute([$obsah, $this->id]);
    }

    function ulozit($puvodniId)
    {
        global $db;

        if ($puvodniId != "")
        {
            // jde o aktualizaci existujici stranky
            $dotaz = $db->prepare("UPDATE stranka SET id = ?, titulek = ?, menu = ? WHERE id = ?");
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $puvodniId]);
        }
        else
        {
            // jde o pridavani nove stranky
            // zjisteni maximalniho poradi stramky
            $dotaz = $db->prepare("SELECT MAX(poradi) AS poradi FROM stranka");
            $dotaz->execute();
            $vysledek = $dotaz->fetch();

            // vezmeme nejvyssi poradi ktere je v tabulce a navysime o 1
            $poradi = $vysledek["poradi"] + 1;

            $dotaz = $db->prepare("INSERT INTO stranka SET id = ?, titulek = ?, menu = ?, poradi = ?");
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $poradi]);

        }
    }

    function smazat()
    {
        global $db;
        $dotaz = $db->prepare("DELETE FROM stranka WHERE id = ?");
        $dotaz->execute([$this->id]);
    }

    static function nastavitPoradi($poradi)
    {
        global $db;
        // projdeme pole s poradim (pole je cislovane)
        foreach ($poradi as $cislo => $idStranky)
        {
            $dotaz = $db->prepare("UPDATE stranka SET poradi = ? WHERE id = ?");
            $dotaz->execute([$cislo, $idStranky]);
        }
        
    }
}

// prázdné pole, které se doplňuje dynamicky z databáze
$seznamStranek = [];
// Načtení z databáze - obsah vynechán, ten je doplněn před admin sekci (TinyMCE)
$dotaz = $db->prepare("SELECT id, titulek, menu FROM stranka ORDER BY poradi");
$dotaz->execute();
//FetchAll - protože je to více stránek
$stranky = $dotaz->fetchAll();

//Vyplnění pole $seznamStranek jednotlivymi instancemi tridy Stranka, která vrátila databáze

//projdeme stránku po stránce cyklem
foreach($stranky as $stranka)
{
    $idStranky = $stranka["id"];
    // pridame do pole novou instanci tridy Stranka
    $seznamStranek[$idStranky] = new Stranka($idStranky, $stranka["titulek"], $stranka["menu"]);
}

/*
Původní uložení v souboru, před vytvořením databáze.
$seznamStranek = [
    "uvod" => new Stranka("uvod", "PrimaPenzion", "Domů"),
    "nabidka" => new Stranka("nabidka", "PrimaPenzion - Nabídka", "Nabídka"),
    "galerie" => new Stranka("galerie", "PrimaPenzion - Galerie", "Galerie"),
    "rezervace" => new Stranka("rezervace", "PrimaPenzion - Rezervace", "Rezervace"),
    "kontakt" => new Stranka("kontakt", "PrimaPenzion - Kontakt", "Kontakt"),
    "404" => new Stranka("404", "Stránka neexistuje", ""),
];
*/