<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 1. Luăm ID-ul cursei
$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

// 2. Citim detaliile reale din baza de date
$sql = "SELECT * FROM curse WHERE id = $cursa_id";
$result = $conn->query($sql);
$cursa = $result->fetch_assoc();

if (!$cursa) {
    die("Eroare: Cursa nu a fost găsită.");
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Cursă activă</title>
  <style>
    /* CSS-ul tău original */
    :root { --bg: #eef3f8; --white: rgba(255, 255, 255, 0.9); --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; --warn: #dc2626; --warn-hover: #b91c1c; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 1rem; }
    .trip-container { position: relative; width: 100%; max-width: 1000px; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
    .trip-map { width: 100%; height: auto; display: block; }
    .trip-info { position: absolute; bottom: 0; width: 100%; background: var(--white); backdrop-filter: blur(8px); padding: 1.5rem 2rem 2rem; display: flex; flex-direction: column; gap: 0.6rem; }
    .trip-info h2 { color: var(--primary); text-align: center; font-size: 1.8rem; margin-bottom: 0.8rem; }
    .info-row { display: flex; justify-content: space-between; align-items: center; font-size: 1rem; border-bottom: 1px solid var(--line); padding: 0.4rem 0; }
    .info-row strong { color: var(--muted); } .info-row span { font-weight: 500; color: var(--text); }
    .btn { margin-top: 0.8rem; width: 100%; padding: 0.9rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
    .end-btn { background-color: var(--primary); color: #fff; } .end-btn:hover { background-color: var(--primary-hover); }
    .cancel-btn { background-color: var(--warn); color: #fff; } .cancel-btn:hover { background-color: var(--warn-hover); }
    #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
    #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
    @media (max-width: 899px) { html { font-size: 15px; } .trip-container { max-width: 420px; } .trip-info { padding: 1rem 1.2rem 1.5rem; } .trip-info h2 { font-size: 1.5rem; } .info-row { font-size: 0.9rem; } }
  </style>
</head>

<body>
  <div class="trip-container">
    <img src="Images/harta-bucuresti.png" alt="Traseu cursă activă" class="trip-map">

    <div class="trip-info">
      <h2>Cursă activă #<?php echo $cursa['id']; ?></h2>
      
      <div class="info-row"><strong>Plecare:</strong> <span><?php echo $cursa['plecare']; ?></span></div>
      <div class="info-row"><strong>Destinație:</strong> <span><?php echo $cursa['destinatie']; ?></span></div>
      <div class="info-row"><strong>Timp rămas:</strong> <span>Calculating...</span></div>
      <div class="info-row"><strong>Cost total:</strong> <span><?php echo $cursa['cost']; ?> RON</span></div>

      <a href="fin_cursa_client8.php?cursa_id=<?php echo $cursa_id; ?>">
          <button class="btn end-btn">Termină cursa</button>
      </a>
      
      <a href="PP3.php"><button class="btn cancel-btn">Anulează</button></a>
    </div>
  </div>
</body>
</html>