CREATE DATABASE IF NOT EXISTS jobboard DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

USE jobboard;

-- Tabela de vagas
CREATE TABLE
    IF NOT EXISTS jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        company_id INT NOT NULL,
        location VARCHAR(255),
        type VARCHAR(50),
        salary VARCHAR(50),
        description TEXT,
        postedAt DATE NOT NULL,
        INDEX idx_jobs_company_id (company_id),
        CONSTRAINT fk_jobs_empresas FOREIGN KEY (company_id) REFERENCES empresas (id) ON DELETE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Tabela de candidaturas
CREATE TABLE
    IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        cv_path VARCHAR(255),
        applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (job_id) REFERENCES jobs (id) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE
    IF NOT EXISTS municipios (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        estado VARCHAR(2) NOT NULL
    ) ENGINE = InnoDB;

INSERT INTO
    municipios (`nome`, `estado`)
VALUES
    ('Petrópolis', 'RJ'),
    ('Juiz de Fora', 'MG');

CREATE TABLE
    `permissoes` (
        `id` int (11) NOT NULL,
        `Tipo` varchar(100) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO
    `permissoes` (`id`, `Tipo`)
VALUES
    (1, 'Administrador'),
    (2, 'Empresa'),
    (3, 'Profissional');

CREATE TABLE
    IF NOT EXISTS categorias (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        status ENUM ('ativo', 'inativo') DEFAULT 'ativo',
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB;

INSERT INTO
    categorias (nome, status)
VALUES
    ('Administração', 'ativo'),
    ('Recursos Humanos', 'ativo'),
    ('Marketing', 'ativo'),
    ('Finanças', 'ativo'),
    ('Contabilidade', 'ativo'),
    ('Engenharia Civil', 'ativo'),
    ('Engenharia Elétrica', 'ativo'),
    ('Engenharia de Produção', 'ativo'),
    ('Engenharia Mecânica', 'ativo'),
    ('Tecnologia da Informação', 'ativo'),
    ('Desenvolvimento de Software', 'ativo'),
    ('Design Gráfico', 'ativo'),
    ('Educação', 'ativo'),
    ('Direito', 'ativo'),
    ('Saúde', 'ativo'),
    ('Enfermagem', 'ativo'),
    ('Psicologia', 'ativo'),
    ('Arquitetura e Urbanismo', 'ativo'),
    ('Comércio Exterior', 'ativo'),
    ('Logística', 'ativo'),
    ('Comunicação Social', 'ativo'),
    ('Jornalismo', 'ativo'),
    ('Publicidade e Propaganda', 'ativo'),
    ('Turismo', 'ativo'),
    ('Gastronomia', 'ativo');

-- Tabela de métodos de emprego
CREATE TABLE
    IF NOT EXISTS jobs_method (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        status ENUM ('ativo', 'inativo') DEFAULT 'ativo',
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    jobs_method (nome, status)
VALUES
    ('Full-time', 'ativo'),
    ('Part-time', 'ativo'),
    ('Contract', 'ativo'),
    ('Internship', 'ativo'),
    ('Remote', 'ativo');

CREATE TABLE
    IF NOT EXISTS empresas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        cnpj VARCHAR(18) NOT NULL UNIQUE,
        telefone VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        status ENUM ('ativo', 'inativo') DEFAULT 'ativo',
        senha_hash VARCHAR(255) NOT NULL,
        nivel_de_acesso INT NOT NULL,
        data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE
    IF NOT EXISTS candidaturas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vaga_id INT NOT NULL,
        nome VARCHAR(120) NOT NULL,
        email VARCHAR(120) NOT NULL,
        telefone VARCHAR(50),
        mensagem TEXT,
        curriculo VARCHAR(255),
        data_envio DATETIME NOT NULL,
        FOREIGN KEY (vaga_id) REFERENCES jobs (id) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;