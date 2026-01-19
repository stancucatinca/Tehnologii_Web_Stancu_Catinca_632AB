<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $parola = $_POST['parola'];
    $confirm_parola = $_POST['confirm_parola'];
    $rol = $_POST['rol'];
    
    $nr_inmatriculare = isset($_POST['nr_inmatriculare']) ? $_POST['nr_inmatriculare'] : '';
    $culoare_masina = isset($_POST['culoare_masina']) ? $_POST['culoare_masina'] : '';
    $model_masina = isset($_POST['model_masina']) ? $_POST['model_masina'] : '';

    if ($parola !== $confirm_parola) {
        echo "<script>alert('Parolele nu coincid!'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO utilizatori (nume, email, telefon, parola, rol, nr_inmatriculare, culoare_masina, model_masina) 
                VALUES ('$nume', '$email', '$telefon', '$parola', '$rol', '$nr_inmatriculare', '$culoare_masina' , '$model_masina')";

        try {
            if ($conn->query($sql) === TRUE) {
                // --- AUTENTIFICAREA AUTOMATĂ ---
                
                // 1. Luăm ID-ul noului utilizator creat
                $last_id = $conn->insert_id;

                // 2. Setăm variabilele de sesiune (exact ca la login.php)
                $_SESSION['user_id'] = $last_id;
                $_SESSION['nume'] = $nume;
                $_SESSION['rol'] = $rol;

                // 3. Acum contul4.php merge, pentru că exista 'user_id' în sesiune
                echo "<script>alert('Cont creat cu succes!'); window.location.href='contul4.php';</script>";
            }
        } catch (mysqli_sql_exception $e) {
            // Verific dacă emailul e deja folosit (cod eroare 1062)
            if ($e->getCode() == 1062) {
                echo "<script>alert('Acest email este deja folosit! Te rugăm să te loghezi.'); window.location.href='login1.php';</script>";
            } else {
                echo "Eroare: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Înregistrare</title>
  <style>
  /* CSS-ul */
  :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; font-size: 16px; }
  body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
  .signup-container { display: flex; align-items: stretch; justify-content: center; background: var(--white); border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 1000px; width: 100%; }
  .signup-image { width: 40%; background-size: cover; background-position: center; }
  .left-image { background-image: url('https://images.unsplash.com/photo-1549921296-3b4a4f9a12db?auto=format&fit=crop&w=800&q=80'); }
  .right-image { background-image: url('https://images.unsplash.com/photo-1605902711622-cfb43c4437b5?auto=format&fit=crop&w=800&q=80'); }
  .signup-box { width: 60%; padding: 3rem 2rem; text-align: center; }
  .signup-box h2 { color: var(--primary); margin-bottom: 1.5rem; font-size: 2rem; }
  .signup-box input, .signup-box select { width: 100%; padding: 0.9rem; margin-bottom: 1rem; border: 1px solid var(--line); border-radius: 0.5rem; font-size: 1rem; }
  .signup-box button { width: 100%; background: var(--primary); color: #fff; border: none; padding: 0.9rem; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
  .signup-box button:hover { background: var(--primary-hover); }
  .signup-box p { margin-top: 1rem; font-size: 0.95rem; }
  .signup-box a { color: var(--primary); text-decoration: none; }
  .signup-box a:hover { text-decoration: underline; }
  #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
  #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
  @media (max-width: 899px) { html { font-size: 15px; } .signup-container { flex-direction: column; max-width: 400px; } .signup-image { display: none; } .signup-box { width: 100%; padding: 2rem 1.5rem; } .signup-box h2 { font-size: 1.6rem; } }
  </style>
</head>
<body>
  <div class="signup-container">
    <div class="signup-image left-image"></div>
    <div class="signup-box">
      <h2>Înregistrare RuSh</h2>

      <form action="" method="POST">
        <input type="text" name="nume" placeholder="Nume complet" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="telefon" placeholder="Număr de telefon" required>
        <input type="password" name="parola" placeholder="Parolă" required>
        
        <input type="password" name="confirm_parola" placeholder="Confirmă parola" required>

        <select id="statusSelect" name="rol" required>
          <option value="">Selectează statusul</option>
          <option value="client">Client</option>
          <option value="sofer">Șofer</option>
        </select>

        <div id="driverFields" style="display:none;">
          <input type="text" name="nr_inmatriculare" placeholder="Număr de înmatriculare">
          <input type="text" name="culoare_masina" placeholder="Culoarea mașinii">
          <input type="text" name="model_masina" placeholder="Modelul mașinii">
        </div>

        <button type="submit">Creează cont</button>
      </form>

      <p>Ai deja un cont? <a href="login1.php">Conectează-te</a></p>
      <p><a href="index.php">← Înapoi la pagina principală</a></p>
    </div>
    <div class="signup-image right-image"></div>
  </div>

  <script>
    const statusSelect = document.getElementById("statusSelect");
    const driverFields = document.getElementById("driverFields");
    statusSelect.addEventListener("change", () => {
      driverFields.style.display = statusSelect.value === "sofer" ? "block" : "none";
    });
  </script>

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