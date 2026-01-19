<?php
session_start();
include 'db.php';

$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

// --- PASUL A: AFLĂM CINE A FOST ȘOFERUL ---
// Vrem să afișăm "Cum a fost cursa cu Ion?" în loc de generic.
$nume_sofer = "Șofer";
$sql_sofer = "SELECT u.nume 
              FROM curse c 
              JOIN utilizatori u ON c.sofer_id = u.id 
              WHERE c.id = $cursa_id";
$result_sofer = $conn->query($sql_sofer);
if($result_sofer->num_rows > 0){
    $row_s = $result_sofer->fetch_assoc();
    $nume_sofer = $row_s['nume'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $recenzie = $_POST['review'];

    // Actualizăm statusul la 'finalizata' și punem recenzia
    // Aceasta afectează DOAR rândul acestui client specific
    $sql = "UPDATE curse SET 
            rating = '$rating', 
            recenzie = '$recenzie', 
            status = 'finalizata' 
            WHERE id = $cursa_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Mulțumim! Cursa a fost notată.'); window.location.href='index.php';</script>";
    } else {
        echo "Eroare: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Final cursă</title>
  <style>
    /* CSS */
    :root { --bg: linear-gradient(180deg, #f5f9fc 0%, #e9eff8 100%); --white: #ffffff; --text: #1e293b; --muted: #64748b; --line: #cbd5e1; --primary: #0d6efd; --primary-hover: #0b5ed7; --accent: #f1f5f9; --success: #16a34a; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; scroll-behavior: smooth; }
    body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 2rem; }
    .finish-box { background-color: var(--white); padding: 2.2rem; border: 1px solid var(--line); border-radius: 1rem; width: 440px; max-width: 95%; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); }
    .finish-box h2 { color: var(--primary); text-align: center; font-size: 1.7rem; margin-bottom: 0.5rem; }
    .driver-name { text-align: center; color: var(--muted); margin-bottom: 1.5rem; font-weight: bold; }
    .section { margin-bottom: 1.3rem; }
    label { font-weight: 500; font-size: 0.95rem; display: block; margin-bottom: 0.5rem; color: var(--text); }
    select, textarea { width: 100%; padding: 0.7rem 0.8rem; border: 1px solid var(--line); border-radius: 8px; font-size: 1rem; background: var(--accent); font-family: inherit; color: var(--text); }
    .finish-btn { width: 100%; background-color: var(--primary); color: #fff; padding: 0.8rem; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; margin-top: 1rem; transition: background 0.2s ease; }
    .finish-btn:hover { background-color: var(--primary-hover); }
    .payment-options { display: flex; gap: 1rem; margin-top: 0.4rem; }
    @media (max-width: 600px) { .finish-box { padding: 1.5rem; } }
  </style>
</head>

<body>
  <div class="finish-box">
    <h2>Final cursă</h2>
    <div class="driver-name">Șofer: <?php echo $nume_sofer; ?></div>

    <form action="" method="POST">
        <div class="section">
          <label for="rating">Rating cursă:</label>
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
          <label for="review">Scrie un review:</label>
          <textarea id="review" name="review" placeholder="Cum a fost cursa?"></textarea>
        </div>

        <div class="section">
          <label>Metodă de plată:</label>
          <div class="payment-options">
            <label><input type="radio" name="plata" value="cash" checked> Cash</label>
            <label><input type="radio" name="plata" value="card"> Card</label>
          </div>
        </div>

        <button type="submit" class="finish-btn">Finalizează și Trimite</button>
    </form>

    <div style="text-align: center; margin-top: 1rem;">
      <a href="index.php" style="color: #0d6efd; text-decoration: none;">← Înapoi la acasă</a>
    </div>
  </div>
</body>
</html>