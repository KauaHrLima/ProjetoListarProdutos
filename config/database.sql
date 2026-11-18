CREATE DATABASE IF NOT EXISTS produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE produtos;

CREATE TABLE IF NOT EXISTS produtos (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150)   NOT NULL,
    descricao  TEXT,
    preco      DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    estoque    INT            NOT NULL DEFAULT 0,
    icone      VARCHAR(50)    NOT NULL DEFAULT 'bi-box-seam',
    criado_em  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
