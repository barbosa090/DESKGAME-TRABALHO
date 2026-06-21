<?php
// artigo.php (Raiz)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/conexao.php';

$id_artigo = isset($_GET['id']) ? intval($_GET['id']) : null;
$artigo_completo = null;

// Se clicou, busca o artigo específico
if ($id_artigo) {
    $stmt = $pdo->prepare("SELECT * FROM artigos WHERE id = :id");
    $stmt->execute(['id' => $id_artigo]);
    $artigo_completo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Busca a lista de todas as postagens para o menu lateral/superior
$sql_lista = "SELECT id, titulo, subtitulo, data_criacao FROM artigos ORDER BY id DESC";
$lista_artigos = $pdo->query($sql_lista)->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & Notícias - DESKGAME</title>
    <link rel="stylesheet" href="artigos.css"> 
    
    <style>
        /* Configurações globais e imagem de fundo */
        body {
            background-color: #0d1117; /* Fundo escuro estilo gamer como fallback */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }

        /* Container principal */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Espaçamento do Header */
        .header-wrapper {
            margin-bottom: 40px;
        }

        /* Títulos */
        .portal-title {
            text-align: center;
            color: #17c5e4;
            font-size: 2.2rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(23, 197, 228, 0.3);
        }

        .section-title {
            text-align: center;
            font-size: 1.5rem;
            margin-top: 30px;
            margin-bottom: 25px;
            color: #ffffff;
        }

        /* Grid de Artigos */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        /* Cartão de Artigo Individual */
        .article-card {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .article-card:hover {
            transform: translateY(-5px);
            border-color: #17c5e4;
        }

        .card-date {
            font-size: 0.85rem;
            color: #8b949e;
            display: block;
            margin-bottom: 8px;
        }

        .card-title {
            margin: 0 0 10px 0;
            font-size: 1.3rem;
            color: #ffffff;
        }

        .card-subtitle {
            margin: 0 0 20px 0;
            font-size: 0.95rem;
            color: #c9d1d9;
            line-height: 1.4;
        }

        /* Botão do Cartão */
        .card-button {
            display: inline-block;
            text-align: center;
            background-color: transparent;
            color: #17c5e4;
            border: 1px solid #17c5e4;
            padding: 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .card-button:hover {
            background-color: #17c5e4;
            color: #0d1117;
        }

        /* Seção do Leitor de Artigo Completo */
        .reader-section {
            margin-top: 20px;
        }

        .full-article {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 40px;
        }

        .article-main-title {
            font-size: 2.2rem;
            color: #17c5e4;
            margin: 0 0 10px 0;
        }

        .article-main-subtitle {
            font-size: 1.3rem;
            color: #c9d1d9;
            margin: 0 0 15px 0;
        }

        .article-meta {
            color: #8b949e;
            font-size: 0.9rem;
        }

        .article-divider {
            border: 0;
            height: 1px;
            background-color: #30363d;
            margin: 25px 0;
        }

        .article-content {
            font-size: 1.1rem;
            color: #e6edf3;
            line-height: 1.6;
        }

        /* Mensagens Vazias e Caixas de Aviso */
        .empty-message, .placeholder-box {
            text-align: center;
            padding: 40px 20px;
            background-color: rgba(22, 27, 34, 0.7);
            border-radius: 8px;
            border: 1px dashed rgba(23, 197, 228, 0.3);
        }

        .placeholder-text {
            color: #8b949e;
            font-size: 1.1rem;
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="main-container">
        
        <div class="header-wrapper">
            <?php include 'includes/header.php'; ?>
        </div>

        <h1 class="portal-title">Portal de Conteúdo DESKGAME</h1>
        <h2 class="section-title">Manchetes Recentes</h2>

        <div class="articles-grid">
            <?php if(empty($lista_artigos)): ?>
                <p class="empty-message">Nenhum artigo publicado no momento.</p>
            <?php else: ?>
                <?php foreach($lista_artigos as $art): ?>
                    <div class="article-card">
                        <div>
                            <span class="card-date">📅 <?= date('d/m/Y', strtotime($art['data_criacao'])); ?></span>
                            <h3 class="card-title"><?= htmlspecialchars($art['titulo']); ?></h3>
                            <p class="card-subtitle"><?= htmlspecialchars($art['subtitulo']); ?></p>
                        </div>
                        <a href="artigos.php?id=<?= $art['id']; ?>" class="card-button">Ler Artigo Completo 📖</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="reader-section">
            <?php if ($artigo_completo): ?>
                <article class="full-article">
                    <h2 class="article-main-title"><?= htmlspecialchars($artigo_completo['titulo']); ?></h2>
                    <p class="article-main-subtitle"><?= htmlspecialchars($artigo_completo['subtitulo']); ?></p>
                    <small class="article-meta">Por: <strong><?= htmlspecialchars($artigo_completo['autor']); ?></strong> | Postado em: <?= date('d/m/Y H:i', strtotime($artigo_completo['data_criacao'])); ?></small>
                    
                    <hr class="article-divider">
                    
                    <div class="article-content">
                        <?= htmlspecialchars($artigo_completo['conteudo']); ?>
                    </div>
                </article>
            <?php else: ?>
                <div class="placeholder-box">
                    <p class="placeholder-text">💡 Escolha um dos artigos na lista acima para abrir o leitor de notícias.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>