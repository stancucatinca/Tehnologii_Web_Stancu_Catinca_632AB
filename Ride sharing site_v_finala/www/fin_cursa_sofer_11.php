<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

// --- PASUL 1: CITIM NUMELE CLIENTULUI DIN BAZA DE DATE ---
// Facem un JOIN între tabelul 'curse' și 'utilizatori'
$sql_info = "SELECT u.nume 
             FROM curse c
             JOIN utilizatori u ON c.client_id = u.id
             WHERE c.id = $cursa_id";

$result_info = $conn->query($sql_info);
$nume_client_db = "";

if ($result_info->num_rows > 0) {
    $row = $result_info->fetch_assoc();
    $nume_client_db = $row['nume'];
} else {
    $nume_client_db = "Client Necunoscut";
}

// --- PASUL 2: LOGICA DE TRIMITERE RECENZIE ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating_client = $_POST['rating'];
    $recenzie_client = $_POST['review'];

    $sql = "UPDATE curse SET 
            rating_client = '$rating_client', 
            recenzie_client = '$recenzie_client',
            status = 'finalizata' 
            WHERE id = $cursa_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Recenzia a fost trimisă. Mulțumim!'); window.location.href='index.php';</script>";
    } else {
        echo "Eroare: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Evaluare client</title>
  <style>
    /* CSS-ul tău original */
    :root { --bg: #eef3f8; --white: rgba(255, 255, 255, 0.95); --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; --accent: #f8fafc; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
    .finish-container { display: flex; align-items: stretch; justify-content: center; background: var(--white); border-radius: 1rem; box-shadow: 0 6px 20px rgba(0,0,0,0.15); overflow: hidden; max-width: 950px; width: 100%; }
    .finish-image { width: 45%; background-image: url(Images/Cat.jpg); background-size: cover; background-position: center; filter: brightness(0.95); }
    .finish-box { flex: 1; padding: 3rem 2rem; text-align: center; background: var(--accent); }
    .finish-box h2 { color: var(--primary); font-size: 2rem; margin-bottom: 1.5rem; }
    .finish-box .section { margin-bottom: 1.2rem; text-align: left; }
    .finish-box label { display: block; font-size: 1rem; font-weight: 500; margin-bottom: 0.4rem; color: var(--muted); }
    .finish-box input, .finish-box select, .finish-box textarea { width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 0.5rem; font-size: 1rem; font-family: inherit; transition: box-shadow 0.2s; background: #fff; }
    .finish-box input:focus, .finish-box select:focus, .finish-box textarea:focus { outline: none; box-shadow: 0 0 6px rgba(13,110,253,0.4); }
    .finish-box textarea { resize: vertical; height: 90px; }
    .finish-box .finish-btn { width: 100%; background-color: var(--primary); color: #fff; padding: 0.9rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1.1rem; margin-top: 1rem; transition: background 0.2s, transform 0.15s; }
    .finish-box .finish-btn:hover { background-color: var(--primary-hover); transform: scale(1.03); }
    .back-link { margin-top: 1.2rem; font-size: 0.9rem; } .back-link a { color: var(--primary); text-decoration: none; } .back-link a:hover { text-decoration: underline; }
    #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
    #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
    @media (max-width: 899px) { html { font-size: 15px; } .finish-container { flex-direction: column; max-width: 420px; } .finish-image { display: none; } .finish-box { padding: 2rem 1.5rem; } .finish-box h2 { font-size: 1.6rem; } }
  </style>
</head>

<body>
  <div class="finish-container">
    <div class="finish-image"></div>
    <div class="finish-box">
      <h2>Evaluare client</h2>

      <form action="" method="POST">
        <div class="section">
          <label for="client-name">Client:</label>
          <input type="text" id="client-name" name="nume_client" value="<?php echo $nume_client_db; ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
        </div>

        <div class="section">
          <label for="rating">Rating client:</label>
          <select id="rating" name="rating" required>
            <option value="">Selectează...</option>
            <option value="5">⭐⭐⭐⭐⭐ - Excelent</option>
            <option value="4">⭐⭐⭐⭐ - Foarte bun</option>
            <option value="3">⭐⭐⭐ - Bun</option>
            <option value="2">⭐⭐ - Slab</option>
            <option value="1">⭐ - Foarte slab</option>
          </select>
        </div>

        <div class="section">
          <label for="review">Recenzie:</label>
          <textarea id="review" name="review" placeholder="Cum a fost clientul?"></textarea>
        </div>

        <button type="submit" class="finish-btn">Trimite recenzia</button>
      </form>

      <div class="back-link">
        <a href="cursa_sofer10.php?cursa_id=<?php echo $cursa_id; ?>">← Înapoi la cursă</a>
      </div>
    </div>
  </div>
</body>
</html>