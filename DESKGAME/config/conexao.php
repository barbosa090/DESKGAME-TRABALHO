<?php
// se precisar no host aponta exatamente para o nome do serviço do banco de dados que está no seu docker-compose.yml
// e coloco "db"no $host

try {
  $pdo = new PDO("mysql:host=localhost;dbname=deskgame;charset=utf8mb4", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro ao conectar: " . $e->getMessage());
}

?>
