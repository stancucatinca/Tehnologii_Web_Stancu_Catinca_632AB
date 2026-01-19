<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $client_id = $_SESSION['user_id'];
    $plecare = $_POST['plecare'];
    $destinatie = $_POST['destinatie'];
    $cost = rand(15, 60); 
    
    // --- SCHIMBAREA MAJORA AICI ---
    // Trebuie să fie exact una din valorile ENUM din baza ta de date
    $status = 'cauta_sofer'; 

    $sql = "INSERT INTO curse (client_id, plecare, destinatie, cost, status, data_cursa) 
            VALUES ('$client_id', '$plecare', '$destinatie', '$cost', '$status', NOW())";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        header("Location: client_comanda5.php?cursa_id=$last_id");
        exit();
    } else {
        echo "Eroare: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Comandă mașină</title>
  <style>
  :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; font-size: 16px; }
  body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
  .order-container { display: flex; align-items: stretch; justify-content: center; background: var(--white); border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 1100px; width: 100%; }
  .order-box { flex: 1; padding: 3rem 2rem; text-align: center; }
  .order-box h2 { margin-bottom: 1.5rem; color: var(--primary); font-size: 2rem; }
  .order-box .brand-link { text-decoration: none; color: var(--primary); font-weight: bold; }
  .order-box input { width: 100%; padding: 0.9rem; margin-bottom: 1rem; border: 1px solid var(--line); border-radius: 0.5rem; font-size: 1rem; }
  .order-box button { width: 100%; background: var(--primary); color: #fff; border: none; padding: 0.9rem; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
  .order-box button:hover { background: var(--primary-hover); }
  .back-link { margin-top: 1rem; font-size: 0.9rem; }
  .back-link a { color: var(--primary); text-decoration: none; }
  .back-link a:hover { text-decoration: underline; }
  .map-section { flex: 1.3; position: relative; }
  .map-section iframe { width: 100%; height: 100%; border: none; }
  .map-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top right, rgba(13,110,253,0.1), rgba(255,255,255,0.15)); pointer-events: none; }
  #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
  #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
  @media (max-width: 899px) { html { font-size: 15px; } .order-container { flex-direction: column; max-width: 400px; } .map-section { display: none; } .order-box { padding: 2rem 1.5rem; } .order-box h2 { font-size: 1.6rem; } }
  </style>
</head>
<body>
  <div class="order-container">
    <div class="order-box">
      <h2><a href="index.php" class="brand-link">RuSh</a></h2>
      <div style="margin-bottom: 20px;">Salut, <strong><?php echo $_SESSION['nume']; ?></strong>! 
        <a href="logout.php" style="background-color: #26cadc; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; margin-left: 10px;">Log Out</a>
      </div>
      <form action="" method="POST">
        <input type="text" name="plecare" placeholder="Punct de plecare" required>
        <input type="text" name="destinatie" placeholder="Adresa destinație" required>
        <button type="submit">Caută șofer</button>
      </form>
      <div class="back-link">
        <a href="contul4.php">← Înapoi la contul meu</a>
      </div>
    </div>
    <div class="map-section">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2848.108066017642!2d26.086547615733333!3d44.439663179102716!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b1ff3f0e9d5c1b%3A0x68573f2e7b5f1a55!2sPiața%20Unirii%2C%20București!5e0!3m2!1sro!2sro!4v1730677770000!5m2!1sro!2sro" loading="lazy" allowfullscreen></iframe>
      <div class="map-overlay"></div>
    </div>
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