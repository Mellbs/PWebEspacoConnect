-- Criar o banco de dados
CREATE DATABASE EspacoConnect;

-- Usar o banco de dados
USE EspacoConnect;

-- Tabela de Usuários
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    papel VARCHAR(100) NOT NULL
);

-- Inserir dados na tabela Usuarios
INSERT INTO Usuarios (email, senha, nome, papel) VALUES
('Usuario1@gmail.com', 'Senha1', 'Jossoares Almeida', 'usuario'),
('Usuario2@gmail.com', 'Senha2', 'Valdemir Carvalho', 'usuario'),
('admin@gerente.com', '123456', 'Marcus Rocha', 'gerente'),
('Admin2@gmail.com', 'Senha4', 'Jilmar Costa', 'gerente');

select * from Usuarios;

-- Tabela de Salas
CREATE TABLE Salas (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    capacidade INT NOT NULL,
    descricao_sala VARCHAR(250) NOT NULL,
    localizacao VARCHAR(250) NOT NULL
);

-- Inserir dados na tabela Salas
INSERT INTO Salas (nome, capacidade, descricao_sala, localizacao)
VALUES
('Sala A', 10, 'Sala para reuniões e apresentações com equipamentos de áudio e vídeo', 'Andar 1, Bloco A'),
('Sala B', 20, 'Sala para treinamentos e workshops com recursos de multimídia', 'Andar 2, Bloco B'),
('Sala C', 50, 'Sala para conferências e eventos com capacidade para até 50 pessoas', 'Andar 3, Bloco C'),
('Sala D', 15, 'Sala para desenvolvimento de projetos com recursos de tecnologia', 'Andar 4, Bloco D'),
('Sala E', 8, 'Sala para sessões de brainstorming e ideias com ambiente colaborativo', 'Andar 1, Bloco E');

-- Tabela de Agendamentos
CREATE TABLE Agendamentos (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT NOT NULL,
    id_usuario INT NOT NULL,
    periodo DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    status_agendamento ENUM('confirmado', 'cancelado', 'pendente') DEFAULT 'pendente',
    FOREIGN KEY (id_sala) REFERENCES Salas(id_sala),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Inserir dados na tabela Agendamentos
INSERT INTO Agendamentos (id_sala, id_usuario, periodo, hora_inicio, hora_fim, status_agendamento)
VALUES
(1, 1, '2025-08-10', '09:00:00', '11:00:00', 'confirmado'),
(2, 2, '2025-08-10', '14:00:00', '16:00:00', 'pendente'),
(3, 3, '2025-08-11', '10:00:00', '12:00:00', 'confirmado'),
(4, 4, '2025-08-12', '13:00:00', '15:00:00', 'cancelado'),
(5, 1, '2025-08-13', '09:30:00', '11:30:00', 'pendente');

-- Tabela de Recursos
CREATE TABLE Recursos (
    id_recurso INT AUTO_INCREMENT PRIMARY KEY,
    nome_recurso VARCHAR(50) NOT NULL,
    descricao_recurso VARCHAR(250)
);

-- Inserir dados na tabela Recursos
INSERT INTO Recursos (nome_recurso, descricao_recurso) VALUES
('Projetor', 'Projetor para apresentações'),
('Wi-Fi', 'Conexão Wi-Fi disponível na sala'),
('Ar condicionado', 'Sala equipada com ar condicionado'),
('Cadeiras extras', 'Disponibilidade de cadeiras adicionais para mais participantes'),
('Equipamento de som', 'Equipamento de som para apresentações e eventos');