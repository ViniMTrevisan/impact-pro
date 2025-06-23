<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit();
}
include '../php/connect.php';
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImpactPro - Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>ImpactPro</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</p>
        <a href="../php/logout.php">Sair</a>
    </header>
    <main>
        <section class="search">
            <h2>Buscar ONGs ou Voluntários</h2>
            <form id="searchForm" onsubmit="buscarUsuarios(event)">
                <div class="form-group">
                    <label for="searchInput">Pesquisar:</label>
                    <input type="text" id="searchInput" name="termo" placeholder="Nome ou termo...">
                </div>
                <div class="form-group">
                    <label for="searchType">Tipo:</label>
                    <select id="searchType" name="tipo">
                        <option value="ong">ONGs</option>
                        <option value="voluntario">Voluntários</option>
                        <option value="todos" selected>Todos</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchLocation">Localização:</label>
                    <input type="text" id="searchLocation" name="localizacao" placeholder="Cidade/estado...">
                </div>
                <div class="form-group">
                    <label for="searchCategory">Área de Atuação:</label>
                    <input type="text" id="searchCategory" name="area_atuacao" placeholder="Educação, saúde...">
                </div>
                <button type="submit">Buscar</button>
            </form>
            <div id="searchResults"></div>
        </section>
        <section class="chat">
            <h2>Chat</h2>
            <select id="chatUser" onchange="carregarChat()">
                <option value="">Selecione um usuário</option>
            </select>
            <div id="chatMessages"></div>
            <input type="text" id="messageInput" placeholder="Digite sua mensagem...">
            <button onclick="enviarMensagem()">Enviar</button>
        </section>
        <section>
            <h2>Oportunidades</h2>
            <a href="opportunities.php">Ver e Gerenciar Oportunidades</a>
        </section>
    </main>
    <script src="../js/script.js"></script>
</body>
</html>