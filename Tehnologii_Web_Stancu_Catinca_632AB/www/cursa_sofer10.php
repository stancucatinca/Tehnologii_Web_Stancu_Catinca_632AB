<?php
session_start();
include 'db.php';

// Verificăm dacă e logat
if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit();
}

$sofer_id = $_SESSION['user_id'];
// Luăm un ID de cursă din URL (doar ca punct de plecare, nu e critic dacă avem lista)
$cursa_id = isset($_GET['cursa_id']) ? $_GET['cursa_id'] : 0;

// --- LOGICA INTELIGENTĂ: FINALIZARE COLECTIVĂ SAU INDIVIDUALĂ ---
if (isset($_POST['finalizeaza_tot'])) {
    
    // 1. Numărăm câți pasageri activi are șoferul
    $sql_check = "SELECT id FROM curse WHERE sofer_id = $sofer_id AND status = 'acceptata'";
    $result_check = $conn->query($sql_check);
    $total_activi = $result_check->num_rows;
    
    if ($total_activi == 1) {
        // CAZUL A: Are un singur pasager. La pagina simplă.
        $row = $result_check->fetch_assoc();
        $unicul_pasager_id = $row['id'];
        header("Location: fin_cursa_sofer_11.php?cursa_id=$unicul_pasager_id");
        exit();
        
    } elseif ($total_activi > 1) {
        // CAZUL B: Are mai mulți pasageri. La pagina de grup.
        header("Location: fin_cursa_grup.php");
        exit();
        
    } else {
        // Caz de siguranță (0 pasageri - poate a dat dublu click)
        header("Location: sofer_comanda9.php");
        exit();
    }
}

// 2. Căutăm TOȚI pasagerii din mașina acestui șofer pentru a-i afișa în listă
$sql_grup = "SELECT c.*, u.nume as nume_real, u.telefon 
             FROM curse c
             JOIN utilizatori u ON c.client_id = u.id
             WHERE c.sofer_id = $sofer_id AND c.status = 'acceptata'";

$result_grup = $conn->query($sql_grup);
$numar_pasageri = $result_grup->num_rows;

// Dacă nu mai are pasageri și a ajuns aici din greșeală, îl trimitem la meniu
if ($numar_pasageri == 0 && !isset($_GET['debug'])) {
     
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Cursă șofer</title>
  <style>
    /* Stilurile tale */
    :root { --bg: #f5f9fc; --white: #fff; --text: #1e293b; --muted: #64748b; --line: #cbd5e1; --primary: #0d6efd; --primary-hover: #0b5ed7; --ok: #16a34a; --ok-hover: #15803d; --warn: #dc2626; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
    .driver-trip-box { background-color: var(--white); padding: 2rem; border: 1px solid var(--line); border-radius: 1rem; width: 500px; max-width: 95%; box-shadow: 0 10px 25px rgba(0,0,0,.08); text-align: center; }
    .driver-trip-box h2 { color: var(--primary); font-size: 1.7rem; margin-bottom: 1.2rem; }
    .profile-img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border: 3px solid var(--primary); }
    
    .button { width: 100%; padding: 0.9rem; margin-top: 0.8rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1.05rem; color: #fff; background-color: var(--primary); transition: transform 0.2s; }
    .button:hover { background-color: var(--primary-hover); transform: scale(1.02); }
    
    .end-all-btn { width: 100%; padding: 1rem; margin-top: 2rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; font-weight: bold; color: #fff; background-color: var(--warn); box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3); }
    .end-all-btn:hover { background-color: #60ebc3; transform: scale(1.02); }

    /* Stiluri lista pasageri */
    #lista-pasageri { display: block; margin-top: 20px; text-align: left; border-top: 1px solid var(--line); padding-top: 15px; }
    .passenger-item { background: #f1f5f9; padding: 12px; border-radius: 8px; margin-bottom: 10px; border-left: 5px solid var(--primary); position: relative; }
    .passenger-item h4 { margin: 0 0 5px 0; color: var(--text); font-size: 1.1rem; }
    .passenger-item p { margin: 0; font-size: 0.9rem; color: var(--muted); }
    .badge { float: right; background: #dbeafe; color: #1e40af; padding: 3px 10px; border-radius: 15px; font-weight: bold; font-size: 0.9rem; }
    .single-finish { display: inline-block; margin-top: 8px; font-size: 0.85rem; color: var(--ok); text-decoration: none; font-weight: bold; }
    .single-finish:hover { text-decoration: underline; }

    @media (max-width: 600px) { html { font-size: 15px; } .driver-trip-box { padding: 1.5rem; } }
  </style>
</head>

<body>
  <div class="driver-trip-box">
    <h2>Cursa în desfășurare</h2>
    <div style="background: #e0f2fe; color: #0369a1; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
         Pasageri la bord: <strong><?php echo $numar_pasageri; ?> / 4</strong>
    </div>

    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Șofer" class="profile-img">
    
    <div id="lista-pasageri">
        <h3 style="color:var(--text);">Listă Pasageri:</h3>
        
        <?php 
        if ($numar_pasageri > 0) {
            $result_grup->data_seek(0);
            while($pasager = $result_grup->fetch_assoc()): 
        ?>
            <div class="passenger-item">
                <span class="badge"><?php echo $pasager['cost']; ?> RON</span>
                <h4><?php echo $pasager['nume_real']; ?></h4>
                
                <p> Destinație: <strong><?php echo $pasager['destinatie']; ?></strong></p>
                <p> Telefon: <?php echo $pasager['telefon']; ?></p>
                
                <a href="fin_cursa_sofer_11.php?cursa_id=<?php echo $pasager['id']; ?>" class="single-finish">
                     Finalizează doar pentru <?php echo $pasager['nume_real']; ?>
                </a>
            </div>
        <?php 
            endwhile; 
        } else {
            echo "<p>Nu ai pasageri momentan. Așteaptă comenzi...</p>";
        }
        ?>
    </div>

    <?php if ($numar_pasageri > 0): ?>
        <form method="POST">
            <button type="submit" name="finalizeaza_tot" class="end-all-btn">
                 FINALIZEAZĂ CURSA (<?php echo $numar_pasageri; ?>)
            </button>
        </form>
    <?php else: ?>
        <a href="sofer_comanda9.php"><button class="button">Înapoi la meniu</button></a>
    <?php endif; ?>

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
    // Apare doar dacă s-a dat scroll în jos 200px
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