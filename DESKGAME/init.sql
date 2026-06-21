CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'usuario'
);

CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  tag_uso VARCHAR(100) DEFAULT NULL,
  cpu_nome VARCHAR(155) DEFAULT NULL,
  gpu_nome VARCHAR(155) DEFAULT NULL,
  tipo VARCHAR(50) NOT NULL DEFAULT 'computador',
  imagem_url VARCHAR(255) DEFAULT 'default.png',
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE artigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    subtitulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    autor VARCHAR(100) DEFAULT 'Admin',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nome, email, senha, tipo) 
VALUES ('Admin - Gustavo', 'admin@deskgame.com', 'MINHA1802', 'admin')
ON DUPLICATE KEY UPDATE tipo='admin';