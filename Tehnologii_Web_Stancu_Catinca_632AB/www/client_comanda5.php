<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login1.php"); exit(); }

// Luăm ID-ul cursei din URL
$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

// Căutăm cursa în bază
$sql = "SELECT * FROM curse WHERE id = $cursa_id";
$result = $conn->query($sql);
$cursa = $result->fetch_assoc();

if (!$cursa) { 
    // Dacă nu găsim cursa, afișăm un link înapoi în loc de doar "die"
    echo "<h2>Cursa nu a fost găsită!</h2>";
    echo "<a href='PP3.php'>Înapoi la comandă</a>";
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Comanda actuală</title>
  <style>
  /* CSS original */
  :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; --warn: #dc2626; --warn-hover: #b91c1c; --amber: #f59e0b; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; font-size: 16px; }
  body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
  .order-container { display: flex; justify-content: center; align-items: stretch; background: var(--white); border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 1100px; width: 100%; }
  .side-image { width: 35%; background-size: cover; background-position: center; }
  .left-image { background-image: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80'); }
  .right-image { background-image: url('https://images.unsplash.com/photo-1526938095499-b0d99eeb13f8?auto=format&fit=crop&w=900&q=80'); }
  .order-box { flex: 1; padding: 3rem 2rem; text-align: center; }
  .order-box h2 { margin-bottom: 1.5rem; font-size: 2rem; color: var(--primary); }
  .info { margin-bottom: 12px; font-size: 1rem; }
  .info strong { display: inline-block; width: 140px; color: var(--muted); }
  .rating { margin: 15px 0; font-size: 1rem; color: var(--amber); }
  .reviews { background-color: #f1f5f9; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.95rem; color: var(--muted); }
  .search-btn, .cancel-btn { width: 100%; padding: 0.9rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; margin-bottom: 10px; transition: background 0.2s; }
  .search-btn { background-color: var(--primary); color: #fff; }
  .search-btn:hover { background-color: var(--primary-hover); }
  .cancel-btn { background-color: var(--warn); color: #fff; }
  .cancel-btn:hover { background-color: var(--warn-hover); }
  .back-link { margin-top: 1rem; font-size: 0.9rem; } .back-link a { color: var(--primary); text-decoration: none; }
  @media (max-width: 899px) { .order-container { flex-direction: column; max-width: 400px; } .side-image { display: none; } .order-box { padding: 2rem 1.5rem; } }
  </style>
</head>

<body>
  <div class="order-container">
    <div class="side-image left-image"></div>
    <div class="order-box">
      <h2>Comanda ta #<?php echo $cursa['id']; ?></h2>

      <div class="info"><strong>Plecare:</strong> <?php echo $cursa['plecare']; ?></div>
      <div class="info"><strong>Destinație:</strong> <?php echo $cursa['destinatie']; ?></div>
      <div class="info"><strong>Cost estimat:</strong> <?php echo $cursa['cost']; ?> RON</div>

      <div class="rating">Status: <strong><?php echo $cursa['status']; ?></strong></div>
      <div class="reviews">
        <p>Se caută cel mai apropiat șofer...</p>
      </div>

      <a href="preluare_sofer6.php?cursa_id=<?php echo $cursa['id']; ?>">
          <button class="search-btn">Confirmă și Caută șofer</button>
      </a>
      
      <a href="PP3.php"><button class="cancel-btn">Anulează</button></a>

      <div class="back-link">
        <a href="contul4.php">← Înapoi la contul meu</a>
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