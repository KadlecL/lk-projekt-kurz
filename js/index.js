const nahoru = document.querySelector("#nahoru");
nahoru.addEventListener("click", (udalost) => {
    window.scrollTo({
        left: 0,
        top: 0,
        behavior: 'smooth'
    });
});
const header = document.querySelector("header");
// tlačítko se zobrazuje, když jsme nascrollovaný jinde, odchytává událost scroll
window.addEventListener("scroll", (udalost) => {
    //zalogovaná pozice scrollování
    console.log(window.scrollY);

    // vrací nám pozici headeru
    const poziceHeaderu = header.getBoundingClientRect();

    // pokud se scrollujeme pod header funkce bottom.
    if (window.scrollY > poziceHeaderu.bottom)
    {
        nahoru.classList.add("zobrazit");
    }
    else
    {
        nahoru.classList.remove("zobrazit");
    }
});