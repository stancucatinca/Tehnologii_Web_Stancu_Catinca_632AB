// Așteptăm încărcarea completă a paginii (deși scriptul este la final, este o bună practică)
window.addEventListener('DOMContentLoaded', () => {
    
    // 1. Selectarea elementelor
    const containerDetalii = document.getElementById('detalii');
    const btnDetalii = document.getElementById('btnDetalii');
    const spanData = document.getElementById('dataProdus');

    // 2. Acțiuni la încărcarea paginii
    
    // a) Ascundem secțiunea de detalii inițial
    containerDetalii.classList.add('ascuns');

    // b) Gestionarea datei curente
    const dataCurenta = new Date();
    
    // Tabloul de luni (pentru a afișa numele lunii în loc de număr)
    const luni = [
        "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", 
        "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
    ];

    const zi = dataCurenta.getDate();
    const lunaText = luni[dataCurenta.getMonth()]; // getMonth() returnează 0-11
    const an = dataCurenta.getFullYear();

    // Injectarea datei formatate în span
    spanData.textContent = `${zi} ${lunaText} ${an}`;

    // 3. Evenimentul de Click pe buton
    btnDetalii.addEventListener('click', () => {
        // Comutăm clasa 'ascuns' (dacă există o scoate, dacă nu există o adaugă)
        containerDetalii.classList.toggle('ascuns');

        // Verificăm dacă elementul are clasa 'ascuns' pentru a schimba textul butonului
        if (containerDetalii.classList.contains('ascuns')) {
            // Dacă este ascuns -> Textul devine "Afișează detalii"
            btnDetalii.textContent = "Afișează detalii";
        } else {
            // Dacă este vizibil -> Textul devine "Ascunde detalii"
            btnDetalii.textContent = "Ascunde detalii";
        }
    });

});