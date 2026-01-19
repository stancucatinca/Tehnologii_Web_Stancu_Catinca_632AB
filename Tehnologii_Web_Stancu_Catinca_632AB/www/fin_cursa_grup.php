<?php
session_start();
include 'db.php';

// Verificăm dacă e logat
if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit();
}

$sofer_id = $_SESSION['user_id'];

// --- LOGICA DE SALVARE (Când se apasă butonul "Trimite tot") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $_POST['rating'] și $_POST['review'] sunt acum array-uri (liste)
    // Structura: [id_cursa => valoare]
    
    $ratings = $_POST['rating'];
    $reviews = $_POST['review'];

    // Trecem prin fiecare cursă trimisă și facem update
    foreach ($ratings as $id_cursa => $nota) {
        $text_recenzie = $conn->real_escape_string($reviews[$id_cursa]);
        $nota_sigura = intval($nota);

        $sql_update = "UPDATE curse SET 
                       rating_client = '$nota_sigura',
                       recenzie_client = '$text_recenzie',
                       status = 'finalizata'
                       WHERE id = $id_cursa AND sofer_id = $sofer_id";
        
        $conn->query($sql_update);
    }

    echo "<script>alert('Toate recenziile au fost salvate! Bravo!'); window.location.href='sofer_comanda9.php';</script>";
    exit();
}

// --- AFISARE: Căutăm toți pasagerii activi ai acestui șofer ---
$sql_grup = "SELECT c.id as cursa_id, c.cost, u.nume as nume_client 
             FROM curse c
             JOIN utilizatori u ON c.client_id = u.id
             WHERE c.sofer_id = $sofer_id AND c.status = 'acceptata'";

$result_grup = $conn->query($sql_grup);

// Dacă nu mai sunt pasageri (poate a dat refresh), îl trimitem acasă
if ($result_grup->num_rows == 0) {
    header("Location: sofer_comanda9.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>RuSh - Evaluare Grup</title>
  <style>
    :root { --bg: #eef3f8; --white: #fff; --text: #1e293b; --muted: #64748b; --primary: #0d6efd; --primary-hover: #0b5ed7; }
    body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 2rem; }
    .container { max-width: 800px; margin: 0 auto; }
    h2 { text-align: center; color: var(--primary); margin-bottom: 2rem; }
    
    .review-card {
        background: var(--white);
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border-left: 5px solid var(--primary);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .client-header { display: flex; justify-content: space-between; align-items: center; font-weight: bold; font-size: 1.1rem; }
    
    select, textarea { padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 100%; font-family: inherit; }
    textarea { height: 60px; resize: vertical; }
    
    .submit-all-btn {
        width: 100%; padding: 1rem; background: var(--primary); color: white; 
        border: none; border-radius: 8px; font-size: 1.2rem; cursor: pointer; font-weight: bold;
        position: sticky; bottom: 20px; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    }
    .submit-all-btn:hover { background: var(--primary-hover); }
  </style>
</head>
<body>

<div class="container">
    <h2>Evaluare Pasageri Multipli</h2>
    
    <form method="POST">
        <?php while($pasager = $result_grup->fetch_assoc()): ?>
            <div class="review-card">
                <div class="client-header">
                    <span>👤 <?php echo $pasager['nume_client']; ?></span>
                    <span style="color: green;"><?php echo $pasager['cost']; ?> RON</span>
                </div>

                <div>
                    <label>Rating:</label>
                    <select name="rating[<?php echo $pasager['cursa_id']; ?>]" required>
                        <option value="5">⭐⭐⭐⭐⭐ - Excelent</option>
                        <option value="4">⭐⭐⭐⭐ - Bun</option>
                        <option value="3">⭐⭐⭐ - Ok</option>
                        <option value="2">⭐⭐ - Slab</option>
                        <option value="1">⭐ - Groaznic</option>
                    </select>
                </div>

                <div>
                    <textarea name="review[<?php echo $pasager['cursa_id']; ?>]" placeholder="Scrie o notă despre <?php echo $pasager['nume_client']; ?>..."></textarea>
                </div>
            </div>
        <?php endwhile; ?>

        <button type="submit" class="submit-all-btn">✅ Finalizează și Notează pe Toți</button>
    </form>
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