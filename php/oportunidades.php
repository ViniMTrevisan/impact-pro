<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    if ($acao == '' && $tipo_usuario == 'ong') {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $localizacao = $_POST['localizacao'];
        $area_atuacao = $_POST['area_atuacao'];
        $sql = "INSERT INTO oportunidades (ong_id, titulo, descricao, localizacao, area_atuacao) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $usuario_id, $titulo, $descricao, $localizacao, $area_atuacao);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'mensagem' => 'Erro ao criar oportunidade']);
        }
        $stmt->close();
    } elseif ($acao == 'carregar') {
        $sql = "SELECT o.*, u.nome AS nome_ong FROM oportunidades o JOIN usuarios u ON o.ong_id = u.id ORDER BY o.criado_em DESC";
        $result = $conn->query($sql);
        $oportunidades = [];
        while ($row = $result->fetch_assoc()) {
            $oportunidades[] = $row;
        }
        echo json_encode($oportunidades);
    } elseif ($acao == 'candidatar' && $tipo_usuario == 'voluntario') {
        $oportunidade_id = $_POST['oportunidade_id'];
        $sql = "INSERT INTO candidaturas (oportunidade_id, voluntario_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $oportunidade_id, $usuario_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'mensagem' => 'Erro ao candidatar-se']);
        }
        $stmt->close();
    }
}
$conn->close();