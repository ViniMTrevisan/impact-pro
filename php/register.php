<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $localizacao = $_POST['localizacao'];
    $area_atuacao = $_POST['area_atuacao'];

    $sql = "INSERT INTO usuarios (tipo, nome, email, senha, localizacao, area_atuacao) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $tipo, $nome, $email, $senha, $localizacao, $area_atuacao);

    if ($stmt->execute()) {
        $usuario_id = $conn->insert_id;
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['tipo_usuario'] = $tipo;
        echo json_encode(['success' => true, 'tipo' => $tipo]);
    } else {
        echo json_encode(['success' => false, 'mensagem' => 'Erro ao cadastrar']);
    }
    $stmt->close();
}
$conn->close();