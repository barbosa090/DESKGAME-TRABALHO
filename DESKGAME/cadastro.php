<?php
require_once __DIR__ . '/config/conexao.php';
$msg = "";
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $nome = trim($_POST['nome']);
 $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
 $senha = $_POST['senha'];
 if(!empty($nome) && !empty($email) && !empty($senha)) {
    $msg = "Por favor, preencha todos os campos!";
    $senha_segura = password_hash($senha, PASSWORD_DEFAULT);

    try{
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_segura]);
       $msg = "faça login para acessar sua conta!";
       $sucesso = true;
    } catch (PDOException $e) {
        $msg = "esse e-mail já está cadastrado!";

    }
 } else {
        $msg = "Por favor, preencha todos os campos!";
    }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - DESKGAME</title>
    <link rel="stylesheet" href="cadastro.css">
    
</head>
<body>
    <div class="login-container">
        <h2 style="color: #17c5e4;">Criar Conta</h2>

        <?php if (!empty($msg)): ?>
            <div class="erro-alerta">
                <?= htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="nome">Nome</label>
            <p><input type="text" name="nome" id="nome" placeholder="Digite seu nome completo" required></p>

            <label for="email">E-mail</label>
            <p><input type="email" name="email" id="email" placeholder="Digite seu e-mail" required></p>
            
            <label for="senha">Senha</label>
            <p><input type="password" name="senha" id="senha" placeholder="Crie uma senha forte" required></p>
            
            <input type="submit" value="Cadastrar e Entrar">
        </form>

     <div class="login-link-container" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(23, 197, 228, 0.2); display: flex; flex-direction: column; align-items: center; gap: 12px;">
            <span class="texto-divisor" style="color: #a0a0a0; font-size: 13px; letter-spacing: 0.5px; font-family: sans-serif;">Já possui um cadastro?</span>
            
            <a href="login.php" class="login-link" style="display: inline-block; width: 100%; box-sizing: border-box; text-align: center; padding: 12px; color: #17c5e4; text-decoration: none; font-weight: bold; font-size: 14px; border: 2px solid #17c5e4; border-radius: 4px; background: transparent; font-family: sans-serif; transition: all 0.25s ease-in-out;" onmouseover="this.style.backgroundColor='#17c5e4'; this.style.color='#121212'; this.style.boxShadow='0 0 12px rgba(23, 197, 228, 0.5)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#17c5e4'; this.style.boxShadow='none';">
                Já tenho uma conta. Fazer Login
            </a>
        </div>

</body>
</html>
