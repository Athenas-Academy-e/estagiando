CREATE DATABASE IF NOT EXISTS jobboard DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
USE jobboard;

-- Tabela de vagas
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    type VARCHAR(50),
    salary VARCHAR(50),
    description TEXT,
    postedAt DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de candidaturas
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cv_path VARCHAR(255),
    applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS municipios (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL ,
    estado VARCHAR(2) NOT NULL
) ENGINE = InnoDB;

INSERT INTO municipios (`nome`, `estado`) VALUES 
('Petrópolis', 'RJ'),
('Juiz de Fora', 'MG');