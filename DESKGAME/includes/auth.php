<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['tipo'] !== 'admin') {
    
    // Joga o usuário de volta para a tela de login (Saindo da pasta admin se necessário)
    header("Location: ../login.php"); 
    exit();
}