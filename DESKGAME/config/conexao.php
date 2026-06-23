<?php
// se precisar no host aponta exatamente para o nome do serviço do banco de dados que está no seu docker-compose.yml
// e coloco "db" no $host
// para manter funcionando localmente http://localhost/DESKGAME-TRABALHO/DESKGAME/
// Use variáveis de ambiente quando disponíveis (útil no Docker Compose).
// No container web, o host do MySQL é o nome do serviço: "db".
$host = getenv('DB_HOST') ?: 'db';
$db      = getenv('DB_NAME') ?: 'deskgame';
$usuario = getenv('DB_USER') ?: 'root';
$senha   = getenv('DB_PASS') ?: 'root';

try {
    // Tenta conectar incluindo a porta padrão do MySQL
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Se o banco estiver desligado, ele para aqui de forma limpa
    die("Erro ao conectar: " . $e->getMessage());
}
?>
