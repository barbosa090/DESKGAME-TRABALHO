<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  Conexão com o banco
require_once 'config/conexao.php';

//  Captura o ID do PC que foi clicado na URL
$id_escolhido = isset($_GET['id']) ? intval($_GET['id']) : null;
$pc_detalhe = null;

//  Busca detalhes do PC clicado
if ($id_escolhido) {
    $stmt =$pdo->prepare( "SELECT * FROM produtos WHERE id = :id AND tipo = 'computador'");
    $stmt->execute(['id' => $id_escolhido]);
    $pc_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
}

//  Busca todos os computadores para os cards
$stmt = $pdo->query( "SELECT * FROM produtos WHERE tipo = 'computador' ORDER BY id DESC");
$listaPCs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computadores - DESKGAME</title>
    <link rel="stylesheet" href="computadores.css">
</head>
<body >
    <div class="main-wrapper">

    <?php include 'includes/header.php'; ?>
        
        
        <main class="gamer-main" style="margin-top: 40px; padding: 0 20px; text-align: left;">
            <h1 class="titulo-secao">Laboratório de Setups</h1>
            <p class="subtitulo-secao">Selecione uma das máquinas abaixo para carregar a análise de hardware.</p>
            
            <div class="analise-grid-horizontal" style="margin-top: 30px; margin-bottom: 40px;">
                <?php if (empty($listaPCs)): ?>
                    <p style="color: #c5c6c7;">Nenhum computador disponível no catálogo no momento.</p>
                <?php else: ?>
                    <?php
                    ?>
                    <div style="max-width: 1100px; margin: 40px auto; padding: 20px; font-family: sans-serif;">
        <h1 style="color: #66fcf1; text-align: center;">Laboratório de Setups</h1>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; margin-top: 30px;">
            <?php if (empty($listaPCs)): ?>
                <p style="color: #ff3333;">Nenhum computador cadastrado no banco de dados.</p>
            <?php else: ?>
                <?php foreach($listaPCs as $pc): ?>
                    
                    <div style="background: #1f2833; border: 1px solid #2f3b4c; padding: 15px; border-radius: 8px; width: 260px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="width: 100%; height: 160px; background: #0b0c10; border-radius: 6px; overflow: hidden; margin-bottom: 12px; display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($pc['imagem']) && file_exists('img/' . $pc['imagem'])): ?>
                                    <img src="img/<?= $pc['imagem']; ?>" alt="<?= htmlspecialchars($pc['nome']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="color: #555; font-size: 12px;">📷 Sem Foto</span>
                                <?php endif; ?>
                            </div>

                            <span style="background: #463077; color: #66fcf1; padding: 2px 6px; font-size: 11px; border-radius: 4px; font-weight: bold; text-transform: uppercase;"><?= htmlspecialchars($pc['tag_uso'] ?? 'Gamer'); ?></span>
                            <h3 style="color: #fff; margin: 10px 0 5px 0; font-size: 1.1rem;"><?= htmlspecialchars($pc['nome']); ?></h3>
                            <p style="color: #66fcf1; font-weight: bold; margin: 0 0 15px 0;">R$ <?= number_format($pc['preco'], 2, ',', '.'); ?></p>
                        </div>
                        
                        <a href="computadores.php?id=<?= $pc['id']; ?>" style="display: block; text-align: center; background: #463077; color: white; padding: 10px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 13px; transition: 0.2s;">Ver Setup 🔎</a>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div style="margin-top: 40px;">
            <?php if ($pc_detalhe): ?>
                <div style="background: #1f2833; border: 2px solid #66fcf1; padding: 30px; border-radius: 8px; color: #fff; display: flex; gap: 30px; flex-wrap: wrap; align-items: center;">
                    
                    <div style="width: 300px; height: 250px; background: #0b0c10; border-radius: 8px; overflow: hidden; border: 1px solid #2f3b4c;">
                        <?php if (!empty($pc_detalhe['imagem']) && file_exists('img/' . $pc_detalhe['imagem'])): ?>
                            <img src="img/<?= $pc_detalhe['imagem']; ?>" alt="<?= htmlspecialchars($pc_detalhe['nome']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #555;">Sem Foto</div>
                        <?php endif; ?>
                    </div>

                    <div style="flex: 1; min-width: 280px;">
                        <h2 style="margin: 0; font-size: 1.8rem;"><?= htmlspecialchars($pc_detalhe['nome']); ?></h2>
                        <p style="color: #66fcf1; font-size: 1.6rem; font-weight: bold; margin: 5px 0 20px 0;">R$ <?= number_format($pc_detalhe['preco'], 2, ',', '.'); ?></p>
                        
                        <div style="background: #0b0c10; padding: 15px; border-radius: 6px; border: 1px solid #2f3b4c;">
                            <h3 style="color: #66fcf1; margin-top: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #2f3b4c; padding-bottom: 8px;">📋 Especificações</h3>
                            <p style="margin: 10px 0 5px 0;"><strong>⚡ Processador:</strong> <?= htmlspecialchars($pc_detalhe['cpu_nome'] ?? 'Não informado'); ?></p>
                            <p style="margin: 5px 0 0 0;"><strong>🎮 Placa de Vídeo:</strong> <?= htmlspecialchars($pc_detalhe['gpu_nome'] ?? 'Não informado'); ?></p>
                        </div>
                    </div>

                </div>
            <?php else: ?>
                <div style="background: #1f2833; padding: 20px; border-radius: 8px; text-align: center; border: 1px dashed #2f3b4c; color: #c5c6c7;">
                    <p>💡 Clique em "Ver Setup" em qualquer um dos cards acima para carregar a imagem e as peças deste computador.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

            <?php endif; ?>

        </main>

        <?php include 'includes/footer.php'; ?>

    </div>

</body>

</html>