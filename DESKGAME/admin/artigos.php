<?php
// admin/artigo.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança
if (!isset($_SESSION['logado']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config/conexao.php';

$mensagem = "";
$modo_edicao = false;
$artigo_detalhe = null;

//  AÇÃO DE SALVAR OU ATUALIZAR 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $conteudo = $_POST['conteudo'];

    if ($id === 0) {
        // Cadastro Novo
        $stmt = $pdo->prepare("INSERT INTO artigos (titulo, subtitulo, conteudo) VALUES (:titulo, :subtitulo, :conteudo)");
        $stmt->execute(['titulo' => $titulo, 'subtitulo' => $subtitulo, 'conteudo' => $conteudo]);
        $mensagem = "⚡ Artigo publicado com sucesso!";
    } else {
        // Editar Existente
        $stmt = $pdo->prepare("UPDATE artigos SET titulo = :titulo, subtitulo = :subtitulo, conteudo = :conteudo WHERE id = :id");
        $stmt->execute(['titulo' => $titulo, 'subtitulo' => $subtitulo, 'conteudo' => $conteudo, 'id' => $id]);
        $mensagem = "⚙️ Artigo atualizado com sucesso!";
    }
}

//  AÇÃO DE DELETAR 
if (isset($_GET['deletar_id'])) {
    $id_del = intval($_GET['deletar_id']);
    $stmt = $pdo->prepare("DELETE FROM artigos WHERE id = :id");
    $stmt->execute(['id' => $id_del]);
    header("Location: artigos.php");
    exit();
}

//  AÇÃO DE SELECIONAR PARA EDITAR OU VER 
if (isset($_GET['editar'])) {
    $modo_edicao = true;
    $id_edit = intval($_GET['editar']);
    $stmt = $pdo->prepare("SELECT * FROM artigos WHERE id = :id");
    $stmt->execute(['id' => $id_edit]);
    $artigo_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_GET['id']))
 {

    $id_ver = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM artigos WHERE id = :id");
    $stmt->execute(['id' => $id_ver]);
    $artigo_detalhe = $stmt->fetch(PDO::FETCH_ASSOC);
}

//  BUSCAR TODOS OS ARTIGOS PARA LISTAGEM 
$stmt_todos = $pdo->prepare("SELECT * FROM artigos ORDER BY id DESC");
$stmt_todos->execute();
$artigos = $stmt_todos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Artigos do Blog</title>
    <link rel="stylesheet" href="notebooks.css"> </head>
<body>
    <div class="main-wrapper">
        <header class="gamer-header">
            <h1 class="logo-txt">🖥️ DESKGAME ADMIN</h1>
            <nav class="gamer-nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="computadores.php" class="nav-link">Gerenciar PCs</a>
                <a href="notebooks.php" class="nav-link">Gerenciar Notes</a>
                <a href="artigos.php" class="nav-link active ">Artigos</a>
            </nav>
        </header>

        <main style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
            <?php if (!empty($mensagem)): ?>
                <div class="alert-msg"><?= $mensagem; ?></div>
            <?php endif; ?>

            <div class="grid-admin">
                <div>
                    <h2 style="color: #fff; margin-top:0;">Feed de Artigos</h2>
                    <a href="artigos.php" style="color: #66fcf1; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 20px;">[+] Escrever Novo Artigo</a>
                    
                    <ul class="lista-painel">
                        <?php foreach($artigos as $art): ?>
                            <li>
                                <span style="color: #fff; font-weight: bold;"><?= htmlspecialchars($art['titulo']); ?></span>
                                <div>
                                    <a href="artigos.php?id=<?= $art['id']; ?>" class="action-link" style="color: #66fcf1;">🔎 Ver</a>
                                    <a href="artigos.php?editar=<?= $art['id']; ?>" class="action-link" style="color: #ffca28;">⚙️ Editar</a>
                                    <a href="artigos.php?deletar_id=<?= $art['id']; ?>" class="action-link" style="color: #ff3333;" onclick="return confirm('Apagar artigo?')">❌ Apagar</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div>
                    <?php if ($modo_edicao && $artigo_detalhe): ?>
                        <h2 style="color: #ffca28; margin-top:0;">Modificar Postagem</h2>
                        <form method="POST" class="form-adm">
                            <input type="hidden" name="id" value="<?= $artigo_detalhe['id']; ?>">
                            <div class="form-group">
                                <label>Título do Artigo</label>
                                <input type="text" name="titulo" value="<?= htmlspecialchars($artigo_detalhe['titulo']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Subtítulo / Resumo</label>
                                <input type="text" name="subtitulo" value="<?= htmlspecialchars($artigo_detalhe['subtitulo']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Conteúdo da Matéria</label>
                                <textarea name="conteudo" rows="8" style="width:100%; background:#0b0c10; color:#fff; border:1px solid #2f3b4c; border-radius:5px; padding:10px; font-family:sans-serif;" required><?= htmlspecialchars($artigo_detalhe['conteudo']); ?></textarea>
                            </div>
                            <button type="submit" class="btn-salvar" style="background: #ffca28; color: #0b0c10;">Atualizar Postagem</button>
                        </form>

                    <?php elseif (!$modo_edicao && $artigo_detalhe): ?>
                        <h2 style="color: #66fcf1; margin-top:0;">Visualização Prévia</h2>
                        <div class="card-inspecao">
                            <h3><?= htmlspecialchars($artigo_detalhe['titulo']); ?></h3>
                            <p style="color: #66fcf1; font-style: italic; margin-top:5px;"><?= htmlspecialchars($artigo_detalhe['subtitulo']); ?></p>
                            <hr>
                            <p style="color: #c5c6c7; white-space: pre-line; line-height:1.6;"><?= htmlspecialchars($artigo_detalhe['conteudo']); ?></p>
                        </div>
                    <?php else: ?>
                        <h2 style="color: #66fcf1; margin-top:0;">Publicar Novo Artigo</h2>
                        <form method="POST" class="form-adm">
                            <input type="hidden" name="id" value="0">
                            <div class="form-group">
                                <label>Título do Artigo</label>
                                <input type="text" name="titulo" placeholder="Ex: O Avanço das Novas Placas de Vídeo" required>
                            </div>
                            <div class="form-group">
                                <label>Subtítulo / Resumo</label>
                                <input type="text" name="subtitulo" placeholder="Ex: Entenda o impacto da IA nas GPUs atuais" required>
                            </div>
                            <div class="form-group">
                                <label>Conteúdo da Matéria</label>
                                <textarea name="conteudo" rows="8" style="width:100%; background:#0b0c10; color:#fff; border:1px solid #2f3b4c; border-radius:5px; padding:10px; font-family:sans-serif;" placeholder="Digite o corpo do texto aqui..." required></textarea>
                            </div>
                            <button type="submit" class="btn-salvar">Publicar Artigo 🚀</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php include '../includes/footer.php'; ?>
            </div>
        </main>
    </div>
</body>
</html>