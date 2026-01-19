<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RuSh - Ride Sharing Inteligent</title>
  <style>
  /* CSS-ul tău original */
  :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; --nav-bg: #e8eefc; --nav-line: #cdd6f0; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; font-size: 16px; }
  body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; }
  body.index-layout { display: grid; grid-template-columns: 1fr 280px; grid-template-rows: auto auto 1fr auto; grid-template-areas: "header header" "nav nav" "main aside" "footer footer"; }
  header { grid-area: header; background: var(--white); border-bottom: 1px solid var(--line); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,.05); position: sticky; top: 0; z-index: 10; }
  .logo { font-size: 1.8rem; font-weight: bold; color: var(--primary); }
  .auth-buttons { display: flex; gap: 0.6rem; }
  header button { background: var(--primary); color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 0.4rem; cursor: pointer; transition: background .2s; font-size: 1rem; }
  header button:hover { background: var(--primary-hover); }
  nav { grid-area: nav; background: var(--nav-bg); display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 1rem; padding: 1rem; border-bottom: 1px solid var(--nav-line); }
  nav a { text-decoration: none; color: var(--text); font-weight: 500; font-size: 1rem; transition: color .2s; cursor: pointer; }
  nav a:hover, nav a.active { color: var(--primary); }
  main { grid-area: main; background: var(--white); padding: 2rem; margin: 1.5rem; border-radius: 0.8rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
  .hero { text-align: center; }
  .hero h1 { font-size: 2rem; margin-bottom: 1rem; color: var(--primary); }
  .hero p { color: var(--muted); max-width: 600px; margin: 0 auto 1.5rem; font-size: 1rem; line-height: 1.6; }
  .cta-buttons { display: flex; justify-content: center; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
  .cta-buttons button { background: var(--primary); color: #fff; border: none; padding: 0.7rem 1.2rem; border-radius: 0.5rem; cursor: pointer; transition: background .2s; }
  .cta-buttons button:hover { background: var(--primary-hover); }
  .hero img { width: 100%; max-width: 600px; border-radius: 0.7rem; margin-top: 1.5rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); height: auto; display: block; margin-left: auto; margin-right: auto; }
  aside { grid-area: aside; background: var(--white); border-left: 1px solid var(--line); padding: 1.5rem; margin: 1.5rem 1.5rem 1.5rem 0; border-radius: 0.8rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
  aside h3 { color: var(--primary); font-size: 1.1rem; margin-bottom: 0.8rem; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 0.3rem; }
  .news-list { list-style: none; padding: 0; }
  .news-list li { font-size: 0.9rem; color: var(--muted); line-height: 1.5; margin-bottom: 0.5rem; }
  footer { grid-area: footer; background: var(--white); text-align: center; padding: 1rem; font-size: 0.9rem; color: #64748b; border-top: 1px solid var(--line); }
  #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
  #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
  
  /* Stiluri speciale pentru tabelul de tarife */
  .rates-list { text-align: left; max-width: 600px; margin: 0 auto; list-style: none; padding: 0; }
  .rates-list li { background: #f8fafc; margin-bottom: 10px; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary); display: flex; justify-content: space-between; align-items: center; }
  .old-price { text-decoration: line-through; color: #94a3b8; font-size: 0.9rem; margin-right: 10px; }
  .new-price { color: var(--primary); font-weight: bold; font-size: 1.1rem; }
  
  @media (max-width: 899px) { body.index-layout { grid-template-columns: 1fr; grid-template-areas: "header" "nav" "main" "aside" "footer"; } header { flex-direction: column; gap: 0.5rem; padding: 1rem; } nav { flex-direction: column; gap: 0.6rem; } main, aside { margin: 0.8rem; padding: 1.2rem; } .hero h1 { font-size: 1.5rem; } .hero p { font-size: 0.95rem; line-height: 1.7; } footer { font-size: 0.8rem; } }
  </style>
</head>

<body class="index-layout">
  <a id="top"></a>
  <button id="top-btn" aria-label="Mergi sus">Up</button>

  <header>
    <div class="logo">RuSh</div>
    <div class="auth-buttons">
      <?php if(isset($_SESSION['user_id'])): ?>
          <a href="contul4.php"><button>Salut, <?php echo $_SESSION['nume']; ?></button></a>
          <a href="logout.php"><button style="background-color: #dc2626;">Log Out</button></a>
      <?php else: ?>
          <a href="login1.php"><button>Log In</button></a>
          <a href="signup2.php"><button>Sign Up</button></a>
      <?php endif; ?>
    </div>
  </header>

  <nav>
    <a href="#" id="btn-acasa" class="active">Acasă</a>
    <a href="#" id="btn-despre">Despre</a>
    <a href="#" id="btn-tarife">Tarife</a>
    <a href="PP3.php">Comandă</a>
  </nav>

  <main id="main-content">
    <section class="hero">
      <h1>Ride sharing inteligent, rapid și economic</h1>
      <p>RuSh este o aplicație economică ce reduce timpul de așteptare al clienților...</p>
      <div class="cta-buttons">
        <a href="signup2.php"><button>Începe acum</button></a>
        <a href="login1.php"><button>Autentifică-te</button></a>
      </div>
      <img src="Images/Poza_RuSh.png" alt="Ride sharing ilustratie">
    </section>
  </main>

  <aside>
    <h3>Ultimele noutăți</h3>
    <ul class="news-list">
      <li>Nou: posibilitatea de a partaja costul cursei.</li>
      <li>Aplicația RuSh disponibilă pe Android și iOS.</li>
      <li>Ratingurile șoferilor actualizate zilnic.</li>
    </ul>
  </aside>

  <footer>© 2025 RuSh • Toate drepturile rezervate</footer>
  
  <script>
    // Butonul de Scroll Sus
    const topBtn = document.getElementById("top-btn");
    window.addEventListener("scroll", () => { 
        topBtn.style.display = document.documentElement.scrollTop > 100 ? "block" : "none"; 
    });
    topBtn.addEventListener("click", () => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });

    // Gestionarea meniului (Schimbarea conținutului fără reîncărcare)
    const main = document.getElementById("main-content");
    const btnAcasa = document.getElementById("btn-acasa");
    const btnDespre = document.getElementById("btn-despre");
    const btnTarife = document.getElementById("btn-tarife");

    // Funcție pentru a schimba clasa 'active' pe butoane
    function setActive(btn) {
        document.querySelectorAll('nav a').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
    }

    // 1. Pagina ACASĂ
    btnAcasa.addEventListener("click", (e) => {
        e.preventDefault();
        setActive(btnAcasa);
        main.innerHTML = `
        <section class="hero">
          <h1>Ride sharing inteligent, rapid și economic</h1>
          <p>RuSh este o aplicație economică ce reduce timpul de așteptare al clienților,
          permițând șoferilor să preia până la 4 persoane cu destinații asemănătoare sau aflate în drum.</p>
          <div class="cta-buttons">
            <a href="signup2.php"><button>Începe acum</button></a>
            <a href="login1.php"><button>Autentifică-te</button></a>
          </div>
          <img src="Images/Poza_RuSh.png" alt="Ride sharing ilustratie">
        </section>`;
    });

    // 2. Pagina DESPRE
    btnDespre.addEventListener("click", (e) => {
        e.preventDefault();
        setActive(btnDespre);
        main.innerHTML = `
        <section class="hero">
          <h1>Despre aplicația RuSh</h1>
          <p><strong>RuSh</strong> este o platformă modernă de ride-sharing care combină
          inteligența artificială cu analiza rutelor în timp real pentru a conecta pasagerii
          și șoferii care au trasee similare.</p>
          <div style="text-align:left; max-width:600px; margin:0 auto; color:var(--muted);">
             <p> <strong>Eficiență:</strong> Reducem timpul de așteptare cu 30%.</p>
             <p> <strong>Ecologic:</strong> Scădem amprenta de carbon folosind mașinile la capacitate maximă.</p>
             <p> <strong>Siguranță:</strong> Toți șoferii sunt verificați și au rating public.</p>
          </div>
          <br>
          <p>Aplicația este disponibilă pe Android și iOS, iar prin interfața web poți vizualiza
          rutele, prețurile și ofertele active.</p>
        </section>`;
    });

    // 3. Pagina TARIFE (Cu lista cerută de tine)
    btnTarife.addEventListener("click", (e) => {
        e.preventDefault();
        setActive(btnTarife);
        main.innerHTML = `
        <section class="hero">
          <h1>Tarife și Curse Frecvente</h1>
          <p>Vezi mai jos cele mai populare rute și prețurile reduse pentru membrii RuSh:</p>
          
          <ul class="rates-list">
             <li>
                <span> Gara de Nord - Aeroport Otopeni</span>
                <div><span class="old-price">55 RON</span> <span class="new-price">40 RON</span></div>
             </li>
             <li>
                <span> Piața Victoriei - Pipera (Business)</span>
                <div><span class="old-price">30 RON</span> <span class="new-price">20 RON</span></div>
             </li>
             <li>
                <span> Unirii - Mall Băneasa</span>
                <div><span class="old-price">45 RON</span> <span class="new-price">32 RON</span></div>
             </li>
             <li>
                <span> Regie - Universitate</span>
                <div><span class="old-price">20 RON</span> <span class="new-price">12 RON</span></div>
             </li>
             <li>
                <span> Arena Națională - Centru Vechi</span>
                <div><span class="old-price">25 RON</span> <span class="new-price">18 RON</span></div>
             </li>
          </ul>

          <p style="margin-top:20px; font-size:0.9rem;">* Prețurile pot varia în funcție de trafic și ora de vârf.</p>
        </section>`;
    });
  </script>
</body>
</html>