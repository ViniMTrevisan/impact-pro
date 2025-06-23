<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $termo = $_POST['termo'] ?? '';
    $tipo = $_POST['tipo'] ?? 'todos';
    $localizacao = $_POST['localizacao'] ?? '';
    $area_atuacao = $_POST['area_atuacao'] ?? '';

    $sql = "SELECT id, nome, tipo, localizacao, area_atuacao FROM usuarios WHERE 1=1";
    $params = [];
    $types = '';

    if ($tipo != 'todos') {
        $sql .= " AND tipo = ?";
        $params[] = $tipo;
        $types .= 's';
    }
    if ($termo) {
        $sql .= " AND nome LIKE ?";
        $params[] = "%$termo%";
        $types .= 's';
    }
    if ($localizacao) {
        $sql .= " AND localizacao LIKE ?";
        $params[] = "%$localizacao%";
        $types .= 's';
    }
    if ($area_atuacao) {
        $sql .= " AND area_atuacao LIKE ?";
        $params[] = "%$area_atuacao%";
        $types .= 's';
    }

    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    echo json_encode($usuarios);
    $stmt->close();
}
$conn->close();