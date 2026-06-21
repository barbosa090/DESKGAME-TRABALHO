<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SEGURANÇA: Trava de segurança e Conexão com o banco
require_once '../includes/auth.php'; 
require_once '../config/conexao.php'; 

$mensagem = ""; // Para exibir alertas na tela


//  AÇÃO DE SALVAR OU ATUALIZAR (Disparada pelo formulário POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']);
    $preco    = trim($_POST['preco']);
    $tag_uso  = trim($_POST['tag_uso']);
    $cpu_nome = trim($_POST['cpu_nome']);
    $gpu_nome = trim($_POST['gpu_nome']);
    
    // Descobre se é um Notebook novo (0) ou uma Edição (> 0)
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id === 0) {
        // CREATE - INSERIR NOVO NOTEBOOK (Alinhado com as 5 variáveis do execute)
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, tag_uso, cpu_nome, gpu_nome, tipo) VALUES (:nome, :preco, :tag_uso, :cpu_nome, :gpu_nome, 'notebook')");
        $stmt->execute([
            'nome'     => $nome,
            'preco'    => $preco,
            'tag_uso'  => $tag_uso,
            'cpu_nome' => $cpu_nome,
            'gpu_nome' => $gpu_nome
        ]);
        $mensagem = "✅ Notebook cadastrado com sucesso!";
    } else {
        // UPDATE - ATUALIZAR EXISTENTE (Corrigido para 'notebook')
        $stmt = $pdo->prepare("UPDATE produtos SET nome = :nome, preco = :preco, tag_uso = :tag_uso, cpu_nome = :cpu_nome, gpu_nome = :gpu_nome WHERE id = :id AND tipo = 'notebook'");
        $stmt->execute([
            'nome'     => $nome,
            'preco'    => $preco,
            'tag_uso'  => $tag_uso,
            'cpu_nome' => $cpu_nome,
            'gpu_nome' => $gpu_nome,
            'id'       => $id
        ]);
        $mensagem = "🔄 Ficha técnica do notebook atualizada!";
    }
}


//  AÇÃO DE DELETAR (Disparada pelo link ?deletar_id=X)
if (isset($_GET['deletar_id'])) {
    $id_para_deletar = intval($_GET['deletar_id']);
    
    // Deleta apenas se for do tipo notebook por segurança (Corrigido para 'notebook')
    $stmt = $pdo->prepare( "DELETE FROM produtos WHERE id = :id AND tipo = 'notebook'");
    $stmt->execute(['id' => $id_para_deletar]);
    
    header("Location: notebooks.php?sucesso=deletado");
    exit();
}

if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'deletado') {
    $mensagem = "❌ Notebook removido do catálogo.";
}


//  READ - BUSCAR APENAS NOTEBOOKS PARA A LISTA DA ESQUERDA (Corrigido para 'notebook')
$stmt = $pdo->query("SELECT * FROM produtos WHERE tipo = 'notebook' ORDER BY id DESC");
$notebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
//  CAPTURAR O NOTEBOOK ESCOLHIDO PARA EDIÇÃO/INSPEÇÃO (Corrigido para 'notebook')
$id_escolhido = isset($_GET['id']) ? intval($_GET['id']) : null;
$id_editar    = isset($_GET['editar']) ? intval($_GET['editar']) : null;
$note_detalhe = null;
$modo_edicao  = false;

if ($id_escolhido) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id AND tipo = 'notebook'");
    $stmt->execute(['id' => $id_escolhido]);
    $note_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($id_editar) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id AND tipo = 'notebook'");
    $stmt->execute(['id' => $id_editar]);
    $note_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
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
                <a href="computadores.php" class="nav-link">Gerenciar PCs</a>
                <a href="notebooks.php" class="nav-link active">Gerenciar Notebooks</a>
                <a href="artigos.php" class="nav-link">Artigos</a>
            </nav>
        </header>

        <main style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
            
            <?php if (!empty($mensagem)): ?>
                <div class="alert-msg"><?= $mensagem; ?></div>
            <?php endif; ?>

            <div class="grid-admin">
                
                <div>
                    <h2 style="color: #fff; margin-top: 0; margin-bottom: 15px;">Banco de Notebooks</h2>
                    <a href="notebooks.php" style="color: #66fcf1; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 15px;">[+] Resetar para Cadastrar Novo Notebook</a>
                    
                    <ul class="lista-painel">
                        <?php foreach($notebooks as $note): ?>
                            <li>
                                <span style="color: #fff; font-weight: bold"><?= htmlspecialchars($note['nome']); ?></span>
                                <div>
                                    <a href="notebooks.php?id=<?= $note['id']; ?>" class="action-link" style="color: #66fcf1;">🔎 Ver</a>
                                    <a href="notebooks.php?editar=<?= $note['id']; ?>" class="action-link" style="color: #ffca28;">⚙️ Editar</a>
                                    <a href="notebooks.php?deletar_id=<?= $note['id']; ?>" class="action-link" style="color: #ff3333;" onclick="return confirm('Deletar esse notebook?')">❌ Apagar</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div>
                    <?php if ($modo_edicao && $note_detalhe): ?>
                        <h2 style="color: #ffca28; margin-top: 0; margin-bottom: 15px;">Editar: <?= htmlspecialchars($note_detalhe['nome']); ?></h2>
                        <form method="POST" class="form-adm">
                            <input type="hidden" name="id" value="<?= $note_detalhe['id']; ?>">
                            
                            <div class="form-group">
                                <label>Nome do Notebook</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($note_detalhe['nome']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Preço (R$)</label>
                                <input type="number" step="0.01" name="preco" value="<?= $note_detalhe['preco']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Tag (Ex: GAMER PORTÁTIL)</label>
                                <input type="text" name="tag_uso" value="<?= htmlspecialchars($note_detalhe['tag_uso']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Modelo Processador</label>
                                <input type="text" name="cpu_nome" value="<?= htmlspecialchars($note_detalhe['cpu_nome']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Modelo Placa de Vídeo</label>
                                <input type="text" name="gpu_nome" value="<?= htmlspecialchars($note_detalhe['gpu_nome']); ?>">
                            </div>
                            <button type="submit" class="btn-salvar" style="background: #ffca28; color: #0b0c10;">Atualizar Notebook</button>
                        </form>

                    <?php elseif (!$modo_edicao && $note_detalhe): ?>
                        <h2 style="color: #66fcf1; margin-top: 0; margin-bottom: 15px;">Inspeção do Notebook</h2>
                        <div class="card-inspecao">
                            <h3><?= htmlspecialchars($note_detalhe['nome']); ?></h3>
                            <p style="color:#66fcf1; font-weight:bold; margin-top:5px; font-size: 18px;">R$ <?= number_format($note_detalhe['preco'], 2, ',', '.'); ?></p>
                            <hr>
                            <p><strong>Tag:</strong> <?= htmlspecialchars($note_detalhe['tag_uso']); ?></p>
                            <p><strong>Processador:</strong> <?= htmlspecialchars($note_detalhe['cpu_nome']); ?></p>
                            <p><strong>Placa de Vídeo:</strong> <?= htmlspecialchars($note_detalhe['gpu_nome']); ?></p>
                            
                            <div style="margin-top: 25px;">
                                <a href="notebooks.php?editar=<?= $note_detalhe['id']; ?>" class="btn-manage" style="display: inline-block; padding: 10px 20px;">Ir para Edição ⚙️</a>
                            </div>
                        </div>

                    <?php else: ?>
                        <h2 style="color: #66fcf1; margin-top: 0; margin-bottom: 15px;">Cadastrar Novo Notebook</h2>
                        <form method="POST" class="form-adm">
                            <input type="hidden" name="id" value="0">
                            
                            <div class="form-group">
                                <label>Nome do Notebook</label>
                                <input type="text" name="nome" placeholder="Ex: Notebook Apex Nitro v15" required>
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