<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  SEGURANÇA: Centraliza a proteção usando o seu arquivo oficial
require_once '../includes/auth.php'; // Trava de segurança contra invasores
require_once '../config/conexao.php'; // Conexão segura com o banco

$mensagem = ""; // Para exibir alertas de sucesso ou erro na tela


//  AÇÃO DE SALVAR OU ATUALIZAR (Seguro contra SQL Injection)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']);
    $preco    = trim($_POST['preco']);
    $tag_uso  = trim($_POST['tag_uso']);
    $cpu_nome = trim($_POST['cpu_nome']);
    $gpu_nome = trim($_POST['gpu_nome']);
    $id       = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id === 0) {
        // CREATE - INSERIR NOVO
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, tag_uso, cpu_nome, gpu_nome, tipo) 
                VALUES (:nome, :preco, :tag_uso, :cpu_nome, :gpu_nome, :tipo)");
        $stmt->execute([
            'nome'     => $nome,
            'preco'    => $preco,
            'tag_uso'  => $tag_uso,
            'cpu_nome' => $cpu_nome,
            'gpu_nome' => $gpu_nome,
            'tipo'     => 'computador'
            ]);
        $mensagem = "✅ Computador cadastrado com sucesso!";
    } else {
        // UPDATE - ATUALIZAR EXISTENTE
        $stmt = $pdo->prepare( "UPDATE produtos SET nome = :nome, preco = :preco, tag_uso = :tag_uso, cpu_nome = :cpu_nome, gpu_nome = :gpu_nome WHERE id = :id");
        $stmt->execute([
            'nome'     => $nome,
            'preco'    => $preco,
            'tag_uso'  => $tag_uso,
            'cpu_nome' => $cpu_nome,
            'gpu_nome' => $gpu_nome,
            'id'       => $id
        ]);
        $mensagem = "🔄 Ficha técnica atualizada com sucesso!";
    }
}


//  AÇÃO DE DELETAR (Seguro usando prepare e intval)
if (isset($_GET['deletar_id'])) {
    // Força o ID a ser estritamente um número inteiro, bloqueando trapaças na URL
    $id_para_deletar = intval($_GET['deletar_id']);
    
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->execute(['id' => $id_para_deletar]);
    
    header("Location: computadores.php?sucesso=deletado");
    exit();
}

//  CORREÇÃO DE SEGURANÇA (XSS): Evita injeção de códigos pela URL
if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'deletado') {
    $mensagem = "❌ Computador removido do catálogo.";
}


//  READ - LISTAR TODOS (Seguro, pois não depende de dados digitados pelo usuário)
$stmt = $pdo->query( "SELECT * FROM produtos WHERE tipo = 'computador' ORDER BY id DESC");
$computadores = $stmt->fetchAll(PDO::FETCH_ASSOC);


// CAPTURAR O PC ESCOLHIDO (Seguro usando prepare)
$id_escolhido = isset($_GET['id']) ? intval($_GET['id']) : null;
$id_editar    = isset($_GET['editar']) ? intval($_GET['editar']) : null;
$pc_detalhe   = null;
$modo_edicao  = false;

if ($id_escolhido) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id AND tipo = 'computador'");
    $stmt->execute(['id' => $id_escolhido]);
    $pc_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($id_editar) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id AND tipo = 'computador'");
    $stmt->execute(['id' => $id_editar]);
    $pc_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
    $modo_edicao = true; 
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - Notebooks</title>
    <link rel="stylesheet" href="notebooks.css">
</head>
<body>

    <div class="main-wrapper">
        <header class="gamer-header">
            <h1 class="logo-txt">🖥️ DESKGAME ADMIN</h1>
            <nav class="gamer-nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="computadores.php" class="nav-link active">Gerenciar PCs</a>
                <a href="notebooks.php" class="nav-link ">Gerenciar Notebooks</a>
                <a href="artigos.php" class="nav-link">Artigos</a>
            </nav>
        </header>

        <main style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
            
            <?php if (!empty($mensagem)): ?>
                <div class="alert-msg"><?= $mensagem; ?></div>
            <?php endif; ?>

            <div class="grid-admin">
                
                <div>
                    <h2 style="color: #fff; margin-top: 0; margin-bottom: 15px;">Banco de computadores</h2>
                    <a href="computadores.php" style="color: #66fcf1; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 15px;">[+] Resetar para Cadastrar Novo Notebook</a>
                    
                    <ul class="lista-painel">
                        <?php foreach($computadores as $comp): ?>
                            <li>
                                <span style="color: #fff; font-weight: bold"><?= htmlspecialchars($comp['nome']); ?></span>
                                <div>
                                    <a href="computadores.php?id=<?= $comp['id']; ?>" class="action-link" style="color: #66fcf1;">🔎 Ver</a>
                                    <a href="computadores.php?editar=<?= $comp['id']; ?>" class="action-link" style="color: #ffca28;">⚙️ Editar</a>
                                    <a href="computadores.php?deletar_id=<?= $comp['id']; ?>" class="action-link" style="color: #ff3333;" onclick="return confirm('Deletar esse notebook?')">❌ Apagar</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div>
                    <?php if ($modo_edicao && $pc_detalhe): ?>
                        <h2 style="color: #ffca28; margin-top: 0; margin-bottom: 15px;">Editar: <?= htmlspecialchars($pc_detalhe['nome']); ?></h2>
                        <form method="POST" class="form-adm">
                            <input type="hidden" name="id" value="<?= $pc_detalhe['id']; ?>">
                            
                            <div class="form-group">
                                <label>Nome do computador</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($pc_detalhe['nome']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Preço (R$)</label>
                                <input type="number" step="0.01" name="preco" value="<?= $pc_detalhe['preco']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Tag (Ex: GAMER PORTÁTIL)</label>
                                <input type="text" name="tag_uso" value="<?= htmlspecialchars($pc_detalhe['tag_uso']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Modelo Processador</label>
                                <input type="text" name="cpu_nome" value="<?= htmlspecialchars($pc_detalhe['cpu_nome']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Modelo Placa de Vídeo</label>
                                <input type="text" name="gpu_nome" value="<?= htmlspecialchars($pc_detalhe['gpu_nome']); ?>">
                            </div>
                            <button type="submit" class="btn-salvar" style="background: #ffca28; color: #0b0c10;">Atualizar Notebook</button>
                        </form>

                    <?php elseif (!$modo_edicao && $pc_detalhe): ?>
                        <h2 style="color: #66fcf1; margin-top: 0; margin-bottom: 15px;">Inspeção do computadores</h2>
                        <div class="card-inspecao">
                            <h3><?= htmlspecialchars($pc_detalhe['nome']); ?></h3>
                            <p style="color:#66fcf1; font-weight:bold; margin-top:5px; font-size: 18px;">R$ <?= number_format($pc_detalhe['preco'], 2, ',', '.'); ?></p>
                            <hr>
                            <p><strong>Tag:</strong> <?= htmlspecialchars($pc_detalhe['tag_uso']); ?></p>
                            <p><strong>Processador:</strong> <?= htmlspecialchars($pc_detalhe['cpu_nome']); ?></p>
                            <p><strong>Placa de Vídeo:</strong> <?= htmlspecialchars($pc_detalhe['gpu_nome']); ?></p>
                            
                            <div style="margin-top: 25px;">
                                <a href="computadores.php?editar=<?= $pc_detalhe['id']; ?>" class="btn-manage" style="display: inline-block; padding: 10px 20px;">Ir para Edição ⚙️</a>
                            </div>
                        </div>

                    <?php else: ?>
                        <h2 style="color: #66fcf1; margin-top: 0; margin-bottom: 15px;">Cadastrar Novo computador</h2>
                        <form method="POST" class="form-adm">
                            <input type="hidden" name="id" value="0">
                            
                            <div class="form-group">
                                <label>Nome do computadores</label>
                                <input type="text" name="nome" placeholder="Ex: computadores Apex Nitro v15" required>
                            </div>
                            <div class="form-group">
                                <label>Preço (R$)</label>
                                <input type="number" step="0.01" name="preco" placeholder="Ex: 5899.00" required>
                            </div>
                            <div class="form-group">
                                <label>Tag de Uso</label>
                                <input type="text" name="tag_uso" placeholder="Ex: ULTRABOOK PREMIUM">
                            </div>
                            <div class="form-group">
                                <label>Modelo CPU</label>
                                <input type="text" name="cpu_nome" placeholder="Ex: Intel Core i5-13420H">
                            </div>
                            <div class="form-group">
                                <label>Modelo GPU</label>
                                <input type="text" name="gpu_nome" placeholder="Ex: RTX 4050 Laptop">
                            </div>
                            <button type="submit" class="btn-salvar">Salvar Notebook 💻</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </main>

        <?php include '../includes/footer.php'; ?>
    </div>

</body>
</html>