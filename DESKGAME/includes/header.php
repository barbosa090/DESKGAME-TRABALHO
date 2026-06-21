<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>DESKGAME</title>
</head>
<body> 
<header class="gamer-header">
    <h1 class="logo-txt">🖥️ DESKGAME</h1>
    <nav class="gamer-nav">
        <a href="index.php" class="nav-link">Início</a>
        <a href="computadores.php" class="nav-link">Computadores</a>
        <a href="notebooks.php" class="nav-link">Notebooks</a>
        <a href="artigos.php" class="nav-link">Artigos</a>
    </nav>
    <div class="header-right">
        <?php if (isset($_SESSION['usuario_nome'])): ?>
            <div class="user-info">
                <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?></span>
            </div>
            <a href="logout.php" class="btn-logout">Sair</a>
        <?php else: ?>
            <a href="cadastro.php" class="nav-link">Entrar</a>
        <?php endif; ?>
    </div>
</header>
</html>