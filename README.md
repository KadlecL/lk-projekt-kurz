1) Popis projektu:

Tento projekt je webová stránka vytvořená pomocí PHP, JavaScriptu, HTML a CSS. Stránka je určena pro běh na lokálním serveru, který je třeba nastavit pomocí Apache nebo MAMP (pro macOS).

2) Požadavky:

Pro správný běh projektu budete potřebovat nainstalovat Apache server na Windows nebo MAMP na macOS. Tyto nástroje vám umožní spustit PHP skripty a zobrazit webovou stránku na vašem počítači. 

Důležité: Je nutné stáhnout veškeré soubory a mít je ve stejné složce, konkrétně v rootu xampp a vložit je do podsložky htdocs (ta představuje doménu localhost). V případě původního nastavení bude cesta ke složce C:/xampp/htdocs.


3) Instalace (Windows a Apple Mac OS)

Windows - XAMPP Control Panel (Apache)
1) Stáhněte XAMPP Apache na Windows
2) Doporučení: Pro lepší chod XAMPP apache doporučuji v root složce smazar xampp-control.ini (obejdete tím tak většinu chybových hlášek)
3) Po spuštění XAMPP klikněte na "Start" v řádce Apache, tím se spustí.
4) Otevřete Visual Studio code, či jiný editor a otevřete admin.php jako doménu localhost

Apple Mac OS - MAMP
1) Stáhněte MAMP
2) Otevřete stažený soubor a následujte pokyny pro dokončení instalace
3) Soubory nahrajte do jedné složky a tu umístěte do htdocs v rootu MAMPu. Cestu naleznete např. /Aplikace/MAMP/htdocs
4) Klikněte na tlačítko "Start Servers" v MAMP aplikaci, aby se spustil Apache a MySQL server

4) Zobrazení projektu
 Pokud máte správně spuštěného Apache, tak po zadání localhost/index.php do prohlížece, se vám projekt zobrazí. Projekt lze také zobrazit po otevření ve Visual Studio code (nahrajete složku z htdocs se soubory -> otevřete index.php -> pravým kliknete do Visual studia -> zvolte "open in browser html/css/js". Pokud tam tuto možnost nemáte, lze si jí stáhnout v extensions.
