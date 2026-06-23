<?php
// notebook.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/conexao.php';
$id_escolhido = isset($_GET['id']) ? intval($_GET['id']) : null;
$note_detalhe = null;
if ($id_escolhido) {
    $stmt = $pdo->prepare( "SELECT * FROM produtos WHERE id = :id AND tipo = 'notebook'");
    $stmt->execute(['id' => $id_escolhido]);
    $pc_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
}

//  Busca todos os computadores para os cards
$stmt = $pdo->query( "SELECT * FROM produtos WHERE tipo = 'notebook' ORDER BY id DESC");
$listaPCs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notebooks - DESKGAME</title>
    <link rel="stylesheet" href="notebooks.css">
</head>

<body >

    <div class="main-wrapper">
        
    <?php include 'includes/header.php'; ?>

        <main class="gamer-main" style="margin-bottom: 40px; padding: 0;">
            <h1 class="titulo-secao" style ="color: #66fcf1">Análise de Notebooks</h1>
            <p class="subtitulo-secao">Portabilidade sem abrir mão do desempenho. Conheça o nível do hardware móvel.</p>
        </main>

        <main class="container-admin">
            <h2 class="titulo-sessao cor-cadastro" style="margin-bottom: 30px;">Notebooks Disponíveis</h2>

            <?php if (empty($notebooks)): ?>
                <div class="alert-msg" style="background: #2d3745; border-left-color: #ffca28; color: #fff;">
                    Nenhum notebook cadastrado no momento. Volte mais tarde!
                </div>
            <?php else: ?>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                    
                    <?php foreach($notebooks as $note): ?>
                        <div class="card-inspecao" style="display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3><?= htmlspecialchars($note['nome']); ?></h3>
                                <p class="card-preco">R$ <?= number_format($note['preco'], 2, ',', '.'); ?></p>
                                <hr class="card-divisor">
                                
                                <p class="card-info"><strong>Processador:</strong> <?= htmlspecialchars($note['cpu_nome'] ?? 'Não informado'); ?></p>
                                <p class="card-info"><strong>Placa de Vídeo:</strong> <?= htmlspecialchars($note['gpu_nome'] ?? 'Não informado'); ?></p>
                                
                                <?php if(!empty($note['tag_uso'])): ?>
                                    <p class="card-info"><strong>Recomendado para:</strong> <?= htmlspecialchars($note['tag_uso']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="wrapper-btn" style="margin-top: 20px;">
                                <a href="detalhes_notebooks.php?id=<?= $note['id']; ?>" class="btn-manage" style="width: 100%; text-align: center; background: var(--neon-ciano);">
                                    Ver Detalhes 🔎
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

            <?php endif; ?>
        </main>

        <footer style="text-align: center; padding: 40px 20px; color: #c5c6c7; border-top: 1px solid #2d3745; margin-top: 60px;">
            <p>&copy; <?= date('Y'); ?> DESKGAME - Todos os direitos reservados.</p>
        </footer>
    </div>

</body>
</html>