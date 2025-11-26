CREATE DATABASE IF NOT EXISTS EspacoConnect;

USE EspacoConnect;

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    papel ENUM('gerente', 'usuario') DEFAULT 'usuario'
);

INSERT INTO Usuarios (nome, email, senha, papel) VALUES
('Gerente', 'gerente@gerente.com', '123', 'gerente'),
('Usuario', 'usuario@usuario.com', '123', 'usuario');
