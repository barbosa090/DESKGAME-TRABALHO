<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['logado']) || $_SESSION['tipo'] !== 'admin') { // se não estiver logado ou n for admin, é mandado pra login.php
    header('Location: ../login.php');
    exit;
}
?>
<?php
// admin/index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TRAVA DE SEGURANÇA: Se não estiver logado OU não for admin, chuta de volta para o login
if (!isset($_SESSION['logado']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// CONEXÃO COM O BANCO DE DADOS (para pegar dados do relatório)
require_once '../config/conexao.php';

// Conta quantos computadores existem no banco
$sql_pcs = "SELECT COUNT(*) as total FROM produtos WHERE tipo = 'computador'";
$stmt_pcs = $pdo->query($sql_pcs);
$total_pcs = $stmt_pcs->fetch(PDO::FETCH_ASSOC)['total'];

// Conta quantos notebooks existem no banco
$sql_notes = "SELECT COUNT(*) as total FROM produtos WHERE tipo = 'notebook'";
$stmt_notes = $pdo->query($sql_notes);
$total_notes = $stmt_notes->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - DESKGAME</title>
    <link rel="stylesheet" href="index.css"> </head>
<body>

    <header class="admin-header">
        <div style="display: flex; align-items: center; gap: 40px;">
            <h1 class="brand-title">🖥️ DESKGAME <span class="brand-subtitle">| Painel Admin</span></h1>
            
            <nav class="admin-nav">
                <a href="index.php" class="nav-link active">Dashboard</a>
                <a href="computadores.php" class="nav-link">Gerenciar PCs</a>
                <a href="notebooks.php" class="nav-link">Gerenciar Notes</a>
                <a href="artigos.php" class="nav-link ">Artigos</a>
            </nav>
        </div>

        <div class="user-menu">
            <span>Olá, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Admin'); ?></strong></span>
            <a href="../logout.php" class="btn-logout">Sair</a>
        </div>
    </header>

    <main class="dashboard-container">
        
        <div class="welcome-section">
            <h2>Bem-vindo ao Controle do Sistema</h2>
            <p>Escolha qual setor do estoque você deseja gerenciar hoje.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card pcs">
                <h3>Computadores</h3>
                <p class="stat-number"><?= $total_pcs; ?></p>
            </div>
            
            <div class="stat-card notes">
                <h3>Notebooks</h3>
                <p class="stat-number"><?= $total_notes; ?></p>
            </div>
        </div>

        <div class="modules-grid">
            
            <div class="module-card">
                <h3>Gerenciar PCs</h3>
                <p>Cadastre novas máquinas de mesa, edite preços, mude tags e altere as especificações de hardware (CPU/GPU).</p>
                <a href="computadores.php" class="btn-manage">Abrir Estoque 🖥️</a>
            </div>

            <div class="module-card">
                <h3>Gerenciar Notebooks</h3>
                <p>Controle o estoque de laptops da loja, altere especificações técnicas, detalhes de portabilidade e valores.</p>
                <a href="notebooks.php" class="btn-manage">Abrir Estoque 💻</a>
            </div>

        </div>

    </main>

</body>
</html>