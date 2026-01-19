<?php
session_start(); // Pornim sesiunea ca să știm pe cine deconectăm
session_unset(); // Ștergem toate variabilele (Nume, ID, Rol)
session_destroy(); // Distrugem sesiunea complet

// Te trimitem înapoi pe prima pagină
header("Location: index.php");
exit();
?>