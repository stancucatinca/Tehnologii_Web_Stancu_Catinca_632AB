// Selectăm elementele din DOM
const btnAdauga = document.getElementById("btnAdauga");
const inputActivitate = document.getElementById("inputActivitate");
const listaActivitati = document.getElementById("listaActivitati");

// Tabloul cu lunile anului în limba română
const luni = [
    "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
    "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
];

// Adăugăm evenimentul de click pe buton
btnAdauga.addEventListener("click", function() {
    // 1. Citim textul introdus
    const textActivitate = inputActivitate.value.trim(); // Folosim trim() pentru a elimina spațiile inutile

    // 2. Verificăm dacă textul nu este gol
    if (textActivitate !== "") {
        
        // 3. Creăm un nou element <li>
        const elementNou = document.createElement("li");

        // 4. Obținem data curentă
        const dataCurenta = new Date();
        const ziua = dataCurenta.getDate();
        const lunaText = luni[dataCurenta.getMonth()]; // getMonth() returnează 0-11
        const anul = dataCurenta.getFullYear();

        // 5. Creăm elementele <span> pentru textul activității și data
        const spanActivitate = document.createElement("span");
        spanActivitate.className = "activity-text";
        spanActivitate.textContent = textActivitate;

        const spanData = document.createElement("span");
        spanData.className = "activity-date";
        spanData.textContent = `adăugată la: ${ziua} ${lunaText} ${anul}`;

        // Adăugăm span-urile în elementul <li>
        elementNou.appendChild(spanActivitate);
        elementNou.appendChild(spanData);

        // 6. Adăugăm elementul în listă
        listaActivitati.appendChild(elementNou);

        // 7. Golim câmpul de input
        inputActivitate.value = "";
    } else {
        alert("Te rog introdu o activitate!");
    }
});