<?php
require_once __DIR__.'/../config.php';

// Lista vagas com filtros
function getJobs($query='', $location='', $type='', $sort='newest') {
    global $pdo;
    $sql = "SELECT * FROM jobs WHERE 1=1";
    $params = [];

    if ($query) {
        $sql .= " AND (title LIKE :q OR company LIKE :q OR description LIKE :q)";
        $params[':q'] = "%$query%";
    }
    if ($location) {
        $sql .= " AND location = :loc";
        $params[':loc'] = $location;
    }
    if ($type) {
        $sql .= " AND type = :type";
        $params[':type'] = $type;
    }

    $sql .= $sort==='oldest' ? " ORDER BY postedAt ASC" : " ORDER BY postedAt DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Obter vaga por ID
function getJobById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Criar ou atualizar vaga
function saveJob($data) {
    global $pdo;
    if (!empty($data['id'])) {
        $stmt = $pdo->prepare("UPDATE jobs SET title=?, company=?, location=?, type=?, salary=?, description=? WHERE id=?");
        $stmt->execute([$data['title'],$data['company'],$data['location'],$data['type'],$data['salary'],$data['description'],$data['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO jobs (title,company,location,type,salary,description,postedAt) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$data['title'],$data['company'],$data['location'],$data['type'],$data['salary'],$data['description'],date('Y-m-d')]);
    }
}

// Deletar vaga
function deleteJob($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM jobs WHERE id=?");
    $stmt->execute([$id]);
}

// Submeter candidatura
function applyJob($job_id, $name, $email, $cv_path=null) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO applications (job_id,name,email,cv_path) VALUES (?,?,?,?)");
    $stmt->execute([$job_id,$name,$email,$cv_path]);
}

// Lista todos os municípios (para popular o select)
function getMunicipios() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, estado FROM municipios ORDER BY estado ASC, nome ASC");
    return $stmt->fetchAll();
}

// Busca um município pelo id (para montar 'Nome, UF')
function getMunicipioById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, estado FROM municipios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Lista todas as categorias
function getCategorias() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, status FROM categorias ORDER BY nome ASC");
    return $stmt->fetchAll();
}

// Lista todos os metedos de trabalho
function getJobmethod() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, status FROM jobs_method ORDER BY nome ASC");
    return $stmt->fetchAll();
}