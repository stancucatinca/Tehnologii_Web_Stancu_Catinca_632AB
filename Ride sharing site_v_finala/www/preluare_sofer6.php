<?php
session_start();
include 'db.php'; // Acum avem nevoie de baza de date!

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

if ($cursa_id == 0) {
    header("Location: PP3.php");
    exit();
}

// 1. Verificăm dacă cursa are DEJA un șofer alocat (în caz de refresh)
$sql_check = "SELECT sofer_id FROM curse WHERE id = $cursa_id";
$result_check = $conn->query($sql_check);
$cursa_existenta = $result_check->fetch_assoc();

$sofer = null;

if ($cursa_existenta['sofer_id'] != NULL) {
    // CAZ A: Avem deja șofer, îl preluăm pe el
    $sofer_id = $cursa_existenta['sofer_id'];
    $sql_sofer = "SELECT * FROM utilizatori WHERE id = $sofer_id";
    $result_sofer = $conn->query($sql_sofer);
    $sofer = $result_sofer->fetch_assoc();
} else {
    // CAZ B: Nu avem șofer, alegem unul RANDOM
    // Selectăm un utilizator care are rolul 'sofer'
    $sql_random = "SELECT * FROM utilizatori WHERE rol = 'sofer' ORDER BY RAND() LIMIT 1";
    $result_random = $conn->query($sql_random);

    if ($result_random->num_rows > 0) {
        $sofer = $result_random->fetch_assoc();
        $sofer_id = $sofer['id'];

        // Actualizăm cursa: punem șoferul și schimbăm statusul în 'acceptata'
        $sql_update = "UPDATE curse SET sofer_id = $sofer_id, status = 'acceptata' WHERE id = $cursa_id";
        $conn->query($sql_update);
    } else {
        // Nu există niciun șofer în baza de date
        die("Ne pare rău, nu există niciun șofer înregistrat în sistem momentan.");
    }
}

// Generăm un rating fictiv pentru afișare (între 4.5 și 5.0)
$rating_display = number_format(rand(45, 50) / 10, 1);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RuSh - Preluare șofer</title>
  <style>
    /* CSS-ul tău original */
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
    #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
    #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
    @media (max-width: 899px) { html { font-size: 15px; } .pickup-container { flex-direction: column; max-width: 400px; } .side-image { display: none; } .pickup-box { padding: 2rem 1.5rem; } .pickup-box h2 { font-size: 1.6rem; } .pickup-box .info { font-size: 0.9rem; } }
  </style>
</head>

<body>
  <div class="pickup-container">
    <div class="side-image left-image"></div>
    <div class="pickup-box">
      <h2>Șoferul este pe drum</h2>

      <div class="info"><strong>Nume șofer:</strong> <span><?php echo $sofer['nume']; ?></span></div>
      <div class="info"><strong>Mașină:</strong> <span><?php echo $sofer['model_masina']; ?> (<?php echo $sofer['culoare_masina']; ?>)</span></div>
      <div class="info"><strong>Număr înmatriculare:</strong> <span><?php echo $sofer['nr_inmatriculare']; ?></span></div>
      
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
</body>
</html>