<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'];
    $usuario_id = $_SESSION['usuario_id'];

    if ($acao == 'enviar') {
        $destinatario_id = $_POST['destinatario_id'];
        $mensagem = $_POST['mensagem'];
        $sql = "INSERT INTO mensagens (remetente_id, destinatario_id, mensagem) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $usuario_id, $destinatario_id, $mensagem);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'mensagem' => 'Erro ao enviar mensagem']);
        }
        $stmt->close();
    } elseif ($acao == 'carregar') {
        $destinatario_id = $_POST['destinatario_id'];
        $sql = "SELECT m.*, u1.nome AS nome_remetente, u2.nome AS nome_destinatario 
                FROM mensagens m 
                JOIN usuarios u1 ON m.remetente_id = u1.id 
                JOIN usuarios u2 ON m.destinatario_id = u2.id 
                WHERE (m.remetente_id = ? AND m.destinatario_id = ?) OR (m.remetente_id = ? AND m.destinatario_id = ?) 
                ORDER BY m.enviado_em";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $usuario_id, $destinatario_id, $destinatario_id, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $mensagens = [];
        while ($row = $result->fetch_assoc()) {
            $mensagens[] = $row;
        }
        echo json_encode($mensagens);
        $stmt->close();
    }
}
$conn->close();