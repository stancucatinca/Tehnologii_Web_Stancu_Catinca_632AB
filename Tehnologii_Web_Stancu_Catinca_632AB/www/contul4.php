<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$mesaj = "";

// LOGICA DE UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $rol = $_POST['rol'];
    
    // Preluăm datele mașinii, chiar dacă sunt goale
    $nr_inmatriculare = isset($_POST['nr_inmatriculare']) ? $_POST['nr_inmatriculare'] : '';
    $culoare_masina = isset($_POST['culoare_masina']) ? $_POST['culoare_masina'] : '';
    $model_masina = isset($_POST['model_masina']) ? $_POST['model_masina'] : ''; 

    $sql_update = "UPDATE utilizatori SET 
                   nume='$nume', 
                   email='$email', 
                   telefon='$telefon', 
                   rol='$rol', 
                   nr_inmatriculare='$nr_inmatriculare', 
                   culoare_masina='$culoare_masina',
                   model_masina='$model_masina' 
                   WHERE id=$id_user";

    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['nume'] = $nume;
        $_SESSION['rol'] = $rol;
        $mesaj = "Datele au fost salvate cu succes!";
    } else {
        $mesaj = "Eroare la salvare: " . $conn->error;
    }
}

// CITIREA DIN DB
$sql = "SELECT * FROM utilizatori WHERE id = $id_user";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Contul meu</title>
  <style>
    /* CSS-ul  */
    :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
    .account-container { display: flex; align-items: stretch; justify-content: center; background: var(--white); border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 950px; width: 100%; }
    .account-image { width: 45%; background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=900&q=80'); background-size: cover; background-position: center; }
    .account-box { flex: 1; padding: 3rem 2rem; text-align: center; }
    /* Am modificat margin-bottom la h2 pentru aliniere */
    .account-box h2 { margin-bottom: 0; font-size: 2rem; color: var(--primary); }
    .account-box input, .account-box select { width: 100%; padding: 0.9rem; margin-bottom: 1rem; border: 1px solid var(--line); border-radius: 0.5rem; font-size: 1rem; }
    .account-box button { width: 100%; background: var(--primary); color: #fff; border: none; padding: 0.9rem; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
    .account-box button:hover { background: var(--primary-hover); }
    .back-link { margin-top: 1rem; font-size: 0.9rem; } .back-link a { color: var(--primary); text-decoration: none; } .back-link a:hover { text-decoration: underline; }
    .succes-msg { color: green; font-weight: bold; margin-bottom: 15px; } .error-msg { color: red; font-weight: bold; margin-bottom: 15px; }
    #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
    #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
    @media (max-width: 899px) { html { font-size: 15px; } .account-container { flex-direction: column; max-width: 400px; } .account-image { display: none; } .account-box { padding: 2rem 1.5rem; } .account-box h2 { font-size: 1.6rem; } }
  </style>
</head>

<body>
  <div class="account-container">
    <div class="account-image"></div>
    <div class="account-box">
      
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <h2>Contul meu</h2>
          <a href="logout.php" style="background-color: #26cadc; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; font-weight: bold;">Log Out</a>
      </div>
      
      <?php if(!empty($mesaj)): ?>
        <p class="<?php echo strpos($mesaj, 'Eroare') !== false ? 'error-msg' : 'succes-msg'; ?>">
            <?php echo $mesaj; ?>
        </p>
      <?php endif; ?>

      <form action="" method="POST" id="accountForm">
        <input type="text" name="nume" placeholder="Nume complet" value="<?php echo $user['nume']; ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" required>
        <input type="tel" name="telefon" placeholder="Număr de telefon" value="<?php echo $user['telefon']; ?>" required>

        <select id="statusSelect" name="rol" required>
          <option value="">Selectează statusul</option>
          <option value="client" <?php if($user['rol'] == 'client') echo 'selected'; ?>>Client</option>
          <option value="sofer" <?php if($user['rol'] == 'sofer') echo 'selected'; ?>>Șofer</option>
        </select>

        <div id="driverFields" style="display: <?php echo ($user['rol'] == 'sofer') ? 'block' : 'none'; ?>;">
          <input type="text" name="nr_inmatriculare" placeholder="Număr de înmatriculare" value="<?php echo $user['nr_inmatriculare']; ?>">
          <input type="text" name="culoare_masina" placeholder="Culoarea mașinii" value="<?php echo $user['culoare_masina']; ?>">
          
          <input type="text" name="model_masina" placeholder="Modelul mașinii" value="<?php echo isset($user['model_masina']) ? $user['model_masina'] : ''; ?>">
        </div>

        <button type="submit">Salvează modificările</button>
      </form>

      <div class="back-link">
        <?php if($user['rol'] == 'sofer'): ?>
             <a href="sofer_comanda9.php">Comenzi</a>
        <?php else: ?>
             <a href="PP3.php"> Comandă</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <button id="top-btn">Up</button>

  <script>
    const statusSelect = document.getElementById("statusSelect");
    const driverFields = document.getElementById("driverFields");
    statusSelect.addEventListener("change", () => {
      driverFields.style.display = statusSelect.value === "sofer" ? "block" : "none";
    });
    
    const topBtn = document.getElementById("top-btn");
    window.addEventListener("scroll", () => {
      topBtn.style.display = document.documentElement.scrollTop > 200 ? "block" : "none";
    });
    topBtn.addEventListener("click", () => window.scrollTo({top:0, behavior:'smooth'}));
  </script>
</body>
</html>