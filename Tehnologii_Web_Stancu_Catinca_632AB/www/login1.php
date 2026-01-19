<?php
session_start();
include 'db.php'; // fișierul db.php 

$eroare = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $parola = $_POST['parola'];

    // Verificăm în baza de date
    $sql = "SELECT * FROM utilizatori WHERE email = '$email' AND parola = '$parola'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Salvăm datele în sesiune
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nume'] = $user['nume'];
        $_SESSION['rol'] = $user['rol'];

        // Redirecționăm în funcție de rol
        if($user['rol'] == 'client') {
             header("Location: PP3.php");
             exit();
        } else {
             header("Location: sofer_comanda9.php");
             exit();
        }
    } else {
        $eroare = "Email sau parolă greșită!";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RuSh - Log In</title>
  <style>
  /* CSS */
  :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #475569; --line: #d1d5db; --primary: #0d6efd; --primary-hover: #0b5ed7; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; font-size: 16px; }
  body { font-family: Arial, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 1rem; }
  .login-container { display: flex; align-items: center; justify-content: center; background: var(--white); border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 1000px; width: 100%; }
  .login-image { width: 40%; height: 500px; background-size: cover; background-position: center; }
  .left-image { background-image: url('https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=800&q=80'); }
  .right-image { background-image: url('https://images.unsplash.com/photo-1602407294553-6c941e6f7552?auto=format&fit=crop&w=800&q=80'); }
  .login-box { width: 60%; padding: 3rem 2rem; text-align: center; }
  .login-box h2 { color: var(--primary); margin-bottom: 1.5rem; font-size: 2rem; }
  .login-box input { width: 100%; padding: 0.9rem; margin-bottom: 1rem; border: 1px solid var(--line); border-radius: 0.5rem; font-size: 1rem; }
  .login-box button { width: 100%; background: var(--primary); color: #fff; border: none; padding: 0.9rem; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
  .login-box button:hover { background: var(--primary-hover); }
  .login-box p { margin-top: 1rem; font-size: 0.95rem; }
  .login-box a { color: var(--primary); text-decoration: none; }
  .login-box a:hover { text-decoration: underline; }
  #top-btn { position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: #fff; border: none; border-radius: 30px; padding: 10px 18px; cursor: pointer; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s ease; display: none; }
  #top-btn:hover { background: var(--primary-hover); transform: scale(1.05); }
  @media (max-width: 899px) { html { font-size: 15px; } .login-container { flex-direction: column; max-width: 400px; } .login-image { display: none; } .login-box { width: 100%; padding: 2rem 1.5rem; } .login-box h2 { font-size: 1.6rem; } }
  
  /* Stil pentru mesajul de eroare */
  .msg-eroare { color: red; margin-bottom: 15px; font-weight: bold; }
  </style>
</head>
<body>
  <a id="top"></a>
  <div class="login-container">
    <div class="login-image left-image"></div>
    <div class="login-box">
      <h2>Autentificare RuSh</h2>
      
      <?php if($eroare != ""): ?>
        <p class="msg-eroare"><?php echo $eroare; ?></p>
      <?php endif; ?>

      <form action="" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="parola" placeholder="Parolă" required>
        <button type="submit">Autentificare</button>
      </form>

      <p>Nu ai cont? <a href="signup2.php">Înregistrează-te</a></p>
      <p><a href="index.php">← Înapoi la pagina principală</a></p>
    </div>
    <div class="login-image right-image"></div>
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