<?php
session_start();
include 'db.php';

// 1. Protecție: Verificăm dacă e logat și dacă e șofer
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'sofer') {
    header("Location: login1.php");
    exit();
}

$sofer_id = $_SESSION['user_id'];
$comanda_disponibila = null;
$mesaj = "";

// 2. VERIFICARE CONSISTENȚĂ: Are deja acest șofer o cursă activă?
// Căutăm o cursă unde sofer_id este el, și statusul NU este finalizat/anulat/cauta_sofer
$sql_check_active = "SELECT id FROM curse WHERE sofer_id = $sofer_id AND status IN ('acceptata', 'in_desfasurare')";
$result_active = $conn->query($sql_check_active);

if ($result_active->num_rows > 0) {
    // Șoferul are deja o cursă! Îl trimitem direct la ea.
    $cursa_activa = $result_active->fetch_assoc();
    $id_activ = $cursa_activa['id'];
    header("Location: cursa_sofer10.php?cursa_id=$id_activ");
    exit();
}

// 3. LOGICA DE ACCEPTARE (Când apasă butonul)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accepta_id'])) {
    $id_de_acceptat = $_POST['accepta_id'];
    
    // Facem update: punem ID-ul șoferului și schimbăm statusul
    $sql_update = "UPDATE curse SET sofer_id = $sofer_id, status = 'acceptata' WHERE id = $id_de_acceptat AND sofer_id IS NULL";
    
    if ($conn->query($sql_update) === TRUE) {
        // Dacă update-ul a reușit (nimeni altcineva nu a luat-o între timp)
        header("Location: cursa_sofer10.php?cursa_id=$id_de_acceptat");
        exit();
    } else {
        $mesaj = "Eroare: Cursa a fost deja luată de altcineva!";
    }
}

// 4. LOGICA DE CĂUTARE CLIENT RANDOM (Doar dacă nu are cursă activă)
// Selectăm o cursă care nu are șofer și are statusul 'cauta_sofer'
// Facem JOIN cu tabelul utilizatori ca să aflăm numele clientului
$sql_search = "SELECT c.*, u.nume as nume_client 
               FROM curse c
               JOIN utilizatori u ON c.client_id = u.id
               WHERE c.status = 'cauta_sofer' AND c.sofer_id IS NULL
               ORDER BY RAND() LIMIT 1";

$result_search = $conn->query($sql_search);

if ($result_search->num_rows > 0) {
    $comanda_disponibila = $result_search->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Comandă client</title>
  <style>
  /* CSS */
  :root { --bg: #f5f9fc; --white: #fff; --text: #1e293b; --muted: #64748b; --line: #cbd5e1; --primary: #0d6efd; --ok: #16a34a; --ok-hover: #15803d; --warn: #dc2626; --warn-hover: #b91c1c; --amber: #f59e0b; --accent: #f1f5f9; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html { font-size: 16px; scroll-behavior: smooth; }
  body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
  .driver-box { background: var(--white); padding: 2rem; border: 1px solid var(--line); border-radius: 1rem; width: 460px; max-width: 95%; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; }
  .driver-box h2 { color: var(--primary); text-align: center; font-size: 1.6rem; margin-bottom: 1.2rem; }
  .driver-box .info { margin-bottom: 0.75rem; font-size: 1rem; }
  .driver-box .info strong { display: inline-block; width: 140px; color: var(--muted); }
  .driver-box .rating { font-size: 0.95rem; color: var(--amber); font-weight: bold; margin: 1rem 0; }
  .driver-box .reviews { background: var(--accent); padding: 0.9rem; border-radius: 8px; margin-bottom: 1.2rem; font-size: 0.9rem; color: var(--text); line-height: 1.5; }
  .driver-box .reviews p { margin-bottom: 0.5rem; }
  .accept-btn, .cancel-btn { width: 100%; padding: 0.9rem; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; color: #fff; margin-bottom: 0.8rem; transition: background 0.2s ease; }
  .accept-btn { background-color: var(--ok); } .accept-btn:hover { background-color: var(--ok-hover); }
  .cancel-btn { background-color: var(--warn); } .cancel-btn:hover { background-color: var(--warn-hover); }
  .back-link { text-align: center; font-size: 0.9rem; margin-top: 1rem; } .back-link a { color: var(--primary); text-decoration: none; } .back-link a:hover { text-decoration: underline; }
  .no-orders { text-align: center; color: var(--muted); padding: 20px; font-size: 1.1rem; }
  @media (max-width: 600px) { html { font-size: 15px; } .driver-box { padding: 1.5rem; } .driver-box h2 { font-size: 1.4rem; } }
  </style>
</head>
<body>
  <div class="driver-box">
    <h2>Comandă client</h2>
    <p style="text-align:center; margin-bottom:15px;">
    Salut, șofer <strong><?php echo $_SESSION['nume']; ?></strong>
    <br><br> <a href="logout.php" style="background-color: #26cadc; color: white; padding: 5px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">Log Out</a>
    </p>

    <?php if ($mesaj): ?>
        <p style="color: red; text-align: center;"><?php echo $mesaj; ?></p>
    <?php endif; ?>

    <?php if ($comanda_disponibila): ?>
        <div class="info"><strong>Nume client:</strong> <?php echo $comanda_disponibila['nume_client']; ?></div>
        <div class="info"><strong>Plecare:</strong> <?php echo $comanda_disponibila['plecare']; ?></div>
        <div class="info"><strong>Destinație:</strong> <?php echo $comanda_disponibila['destinatie']; ?></div>
        <div class="info"><strong>Cost:</strong> <?php echo $comanda_disponibila['cost']; ?> RON</div>

        <div class="rating">⭐ 4.9 / 5 (Simulat)</div>

        <div class="reviews">
          <p>„Clientul așteaptă confirmarea.”</p>
        </div>

        <form action="" method="POST">
            <input type="hidden" name="accepta_id" value="<?php echo $comanda_disponibila['id']; ?>">
            <button type="submit" class="accept-btn">Acceptă comanda</button>
        </form>

        <button class="cancel-btn" onclick="window.location.reload();">Refuză (Caută alta)</button>

    <?php else: ?>
        <div class="no-orders">
            <p>Nu există comenzi disponibile momentan.</p>
            <p>Relaxează-te puțin </p>
            <button class="accept-btn" onclick="window.location.reload();" style="background-color: var(--primary);">Verifică din nou</button>
        </div>
    <?php endif; ?>

    <div class="back-link">
      <a href="contul4.php">Mergi la Contul Meu</a>
    </div>
  </div>
</body>
</html>