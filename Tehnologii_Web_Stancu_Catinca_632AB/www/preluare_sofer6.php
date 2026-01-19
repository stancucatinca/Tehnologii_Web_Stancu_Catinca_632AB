<?php
session_start();
include 'db.php';

// Verificăm logarea
if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit();
}

$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

if ($cursa_id == 0) {
    header("Location: PP3.php");
    exit();
}

// 1. Obținem detaliile cursei actuale (ca să vedem DE UNDE pleacă clientul)
$sql_detalii = "SELECT plecare, sofer_id FROM curse WHERE id = $cursa_id";
$result_detalii = $conn->query($sql_detalii);
$cursa_curenta = $result_detalii->fetch_assoc();

if (!$cursa_curenta) {
    die("Eroare: Cursa nu există.");
}

$locatie_plecare = $cursa_curenta['plecare']; // Ex: "Piata Unirii"
$sofer_ales_id = 0;

// Verificăm dacă cursa are deja un șofer (în caz de refresh la pagină)
if ($cursa_curenta['sofer_id'] != NULL) {
    $sofer_ales_id = $cursa_curenta['sofer_id'];
} else {
    // --- AICI ÎNCEPE LOGICA DE RIDE SHARING ---

    // CAUTĂM UN ȘOFER CARE E DEJA LA ACEEAȘI LOCAȚIE ȘI ARE LOCURI LIBERE (< 4)
    // Numărăm câți clienți are fiecare șofer activ la acea locație
    $sql_share = "SELECT sofer_id, COUNT(*) as nr_pasageri 
                  FROM curse 
                  WHERE status = 'acceptata' 
                  AND plecare = '$locatie_plecare' 
                  AND sofer_id IS NOT NULL
                  GROUP BY sofer_id
                  HAVING nr_pasageri < 4 
                  LIMIT 1"; // Luăm primul șofer care respectă regula

    $result_share = $conn->query($sql_share);

    if ($result_share->num_rows > 0) {
        // SCENARIUL A: Am găsit un șofer care mai are loc!
        $row_share = $result_share->fetch_assoc();
        $sofer_ales_id = $row_share['sofer_id'];
    } else {
        // SCENARIUL B: Nu există șoferi la locație SAU sunt plini (4/4).
        // Căutăm un șofer NOU, care este COMPLET LIBER (nu are curse active)
        
        $sql_nou = "SELECT id FROM utilizatori 
                    WHERE rol = 'sofer' 
                    AND id NOT IN (SELECT DISTINCT sofer_id FROM curse WHERE status = 'acceptata' AND sofer_id IS NOT NULL)
                    ORDER BY RAND() 
                    LIMIT 1";
        
        $result_nou = $conn->query($sql_nou);

        if ($result_nou->num_rows > 0) {
            $sofer_nou = $result_nou->fetch_assoc();
            $sofer_ales_id = $sofer_nou['id'];
        } else {
            // Caz extrem: Toți șoferii sunt ocupați în alte părți
            die("<h1>Ne pare rău!</h1><p>Toți șoferii sunt momentan ocupați sau mașinile sunt pline. Încearcă din nou în câteva minute.</p><a href='PP3.php'>Înapoi</a>");
        }
    }

    // --- FINALIZARE: Atribuim șoferul găsit (Shared sau Nou) cursei curente ---
    if ($sofer_ales_id > 0) {
        $sql_update = "UPDATE curse SET sofer_id = $sofer_ales_id, status = 'acceptata' WHERE id = $cursa_id";
        $conn->query($sql_update);
    }
}

// --- AFISAREA DATELOR (Partea vizuală rămâne la fel) ---
// Acum luăm datele șoferului ales pentru a le afișa
$sql_sofer_info = "SELECT * FROM utilizatori WHERE id = $sofer_ales_id";
$res_info = $conn->query($sql_sofer_info);
$sofer_data = $res_info->fetch_assoc();

// Calculăm câți oameni sunt acum în mașină cu acest șofer
$sql_count = "SELECT COUNT(*) as total FROM curse WHERE sofer_id = $sofer_ales_id AND status='acceptata'";
$res_count = $conn->query($sql_count);
$nr_total_pasageri = $res_count->fetch_assoc()['total'];

$rating_display = number_format(rand(45, 50) / 10, 1);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RuSh - Preluare șofer</title>
  <style>
    /* CSS Original */
    :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; --warn: #dc2626; --warn-hover: #b91c1c; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
    .pickup-container { display: flex; justify-content: center; align-items: stretch; background: var(--white); border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 1100px; width: 100%; }
    .side-image { width: 35%; background-size: cover; background-position: center; }
    .left-image { background-image: url('https://images.unsplash.com/photo-1518893494013-481c1d8ed3fd?auto=format&fit=crop&w=900&q=80'); }
    .right-image { background-image: url('https://images.unsplash.com/photo-1525104885119-8806dd94ad58?auto=format&fit=crop&w=900&q=80'); }
    .pickup-box { flex: 1; padding: 3rem 2rem; text-align: left; }
    .pickup-box h2 { color: var(--primary); text-align: center; margin-bottom: 1.5rem; font-size: 2rem; }
    .pickup-box .info { display: flex; justify-content: space-between; align-items: center; font-size: 1rem; margin-bottom: 0.6rem; gap: 0.5rem; border-bottom: 1px solid var(--line); padding-bottom: 0.3rem; }
    .pickup-box .info strong { color: var(--muted); width: auto; flex-shrink: 0; }
    .pickup-box .info span { color: var(--text); font-weight: 500; }
    .btn { width: 100%; padding: 0.9rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; margin-top: 1.2rem; transition: background 0.2s; }
    .start-btn { background-color: var(--primary); color: #fff; } .start-btn:hover { background-color: var(--primary-hover); }
    .cancel-btn { background-color: var(--warn); color: #fff; } .cancel-btn:hover { background-color: var(--warn-hover); }
    .back-link { margin-top: 1.2rem; text-align: center; font-size: 0.9rem; } .back-link a { color: var(--primary); text-decoration: none; } .back-link a:hover { text-decoration: underline; }
    .passengers-badge { background: #dbeafe; color: #1e40af; padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; text-align: center; margin-bottom: 15px; display: block; }
    @media (max-width: 899px) { html { font-size: 15px; } .pickup-container { flex-direction: column; max-width: 400px; } .side-image { display: none; } .pickup-box { padding: 2rem 1.5rem; } .pickup-box h2 { font-size: 1.6rem; } .pickup-box .info { font-size: 0.9rem; } }
  </style>
</head>

<body>
  <div class="pickup-container">
    <div class="side-image left-image"></div>
    <div class="pickup-box">
      <h2>Șoferul este pe drum</h2>
      
      <span class="passengers-badge">
           Pasageri în mașină: <?php echo $nr_total_pasageri; ?> / 4
      </span>

      <div class="info"><strong>Nume șofer:</strong> <span><?php echo $sofer_data['nume']; ?></span></div>
      <div class="info"><strong>Mașină:</strong> <span><?php echo $sofer_data['model_masina']; ?> (<?php echo $sofer_data['culoare_masina']; ?>)</span></div>
      <div class="info"><strong>Număr înmatriculare:</strong> <span><?php echo $sofer_data['nr_inmatriculare']; ?></span></div>
      
      <div class="info"><strong>Rating:</strong> <span>⭐ <?php echo $rating_display; ?> / 5</span></div>
      <div class="info"><strong>Timp estimat:</strong> <span><?php echo rand(2, 8); ?> minute</span></div>

      <a href="cursa_activa7.php?cursa_id=<?php echo $cursa_id; ?>">
          <button class="btn start-btn">Începe cursa</button>
      </a>
      
      <a href="PP3.php"><button class="btn cancel-btn">Anulează comanda</button></a>

      <div class="back-link">
        <a href="client_comanda5.php?cursa_id=<?php echo $cursa_id; ?>">← Înapoi la comandă</a>
      </div>
    </div>
    <div class="side-image right-image"></div>
  </div>

   <style>
  #top-btn-universal {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #0d6efd; /* Albastru principal */
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    z-index: 9999;
    transition: transform 0.3s ease;
    display: none; /* Ascuns la început */
    justify-content: center;
    align-items: center;
  }
  #top-btn-universal:hover { background-color: #0b5ed7; transform: scale(1.1); }
</style>

<button id="top-btn-universal" title="Mergi sus">Up</button>

<script>
  // Logica pentru buton
  const topButton = document.getElementById("top-btn-universal");
  
  window.onscroll = function() {
    // Apare doar dacă ai dat scroll în jos 200px
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
      topButton.style.display = "flex";
    } else {
      topButton.style.display = "none";
    }
  };

  topButton.addEventListener("click", function() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
</script>

</body>
</html>