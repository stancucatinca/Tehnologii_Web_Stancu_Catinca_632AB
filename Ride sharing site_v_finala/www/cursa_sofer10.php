<?php
session_start();
include 'db.php';

// Verificăm dacă e logat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Luăm ID-ul cursei din link
$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

// Citim detaliile cursei din baza de date
$sql = "SELECT * FROM curse WHERE id = $cursa_id";
$result = $conn->query($sql);
$cursa = $result->fetch_assoc();

if (!$cursa) {
    die("Eroare: Cursa nu a fost găsită.");
}

// Opțional: Putem lua și numele clientului dacă facem un SELECT în tabelul utilizatori
// Dar pentru simplitate, afișăm datele cursei momentan.
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Cursă șofer</title>
  <style>
    /* CSS-ul tău original */
    :root { --bg: #f5f9fc; --white: #fff; --text: #1e293b; --muted: #64748b; --line: #cbd5e1; --primary: #0d6efd; --primary-hover: #0b5ed7; --ok: #16a34a; --ok-hover: #15803d; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
    .driver-trip-box { background-color: var(--white); padding: 2rem; border: 1px solid var(--line); border-radius: 1rem; width: 480px; max-width: 95%; box-shadow: 0 10px 25px rgba(0,0,0,.08); text-align: center; }
    .driver-trip-box h2 { color: var(--primary); font-size: 1.7rem; margin-bottom: 1.2rem; }
    .profile-img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border: 3px solid var(--primary); }
    .trip-image { width: 100%; height: auto; border-radius: 12px; margin-bottom: 1rem; object-fit: cover; box-shadow: 0 3px 8px rgba(0,0,0,0.08); }
    .info { font-size: 1rem; margin-bottom: 0.6rem; text-align: left; }
    .info strong { display: inline-block; width: 130px; color: var(--muted); }
    .button, .end-btn { width: 100%; padding: 0.9rem; margin-top: 0.8rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1.05rem; color: #fff; transition: background 0.2s ease, transform 0.2s ease; }
    .button { background-color: var(--primary); } .button:hover { background-color: var(--primary-hover); transform: scale(1.02); }
    .end-btn { background-color: var(--ok); } .end-btn:hover { background-color: var(--ok-hover); transform: scale(1.02); }
    #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 50px; padding: 12px 20px; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.2); cursor: pointer; text-decoration: none; z-index: 99; transition: all 0.3s ease; }
    #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
    @media (max-width: 600px) { html { font-size: 15px; } .driver-trip-box { padding: 1.5rem; } .driver-trip-box h2 { font-size: 1.4rem; } .trip-image { border-radius: 10px; } }
  </style>
</head>

<body>
  <div class="driver-trip-box">
    <h2>Cursa în desfășurare #<?php echo $cursa['id']; ?></h2>

    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Șofer" class="profile-img">

    <img src="Images/Car.webp" alt="Traseu drum" class="trip-image">

    <div class="info"><strong>Plecare:</strong> <?php echo $cursa['plecare']; ?></div>
    <div class="info"><strong>Destinație:</strong> <?php echo $cursa['destinatie']; ?></div>
    <div class="info"><strong>Cost:</strong> <?php echo $cursa['cost']; ?> RON</div>

    <button class="button" onclick="alert('Căutăm alți clienți...')">Caută clienți apropiați</button>

    <a href="fin_cursa_sofer_11.php?cursa_id=<?php echo $cursa_id; ?>">
      <button class="end-btn">Finalizează cursa</button>
    </a>
  </div>
</body>
</html>