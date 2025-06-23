<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit();
}
include '../php/connect.php';
$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImpactPro - Oportunidades</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>ImpactPro</h1>
        <p>Oportunidades de Voluntariado</p>
        <a href="dashboard.php">Voltar ao Dashboard</a>
    </header>
    <main>
        <?php if ($tipo_usuario == 'ong') { ?>
            <section class="create-opportunity">
                <h2>Criar Nova Oportunidade</h2>
                <form id="opportunityForm">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="localizacao">Localização:</label>
                        <input type="text" id="localizacao" name="localizacao" required>
                    </div>
                    <div class="form-group">
                        <label for="area_atuacao">Área de Atuação:</label>
                        <input type="text" id="area_atuacao" name="area_atuacao" required>
                    </div>
                    <button type="submit">Criar Oportunidade</button>
                </form>
            </section>
        <?php } ?>
        <section class="opportunities">
            <h2>Oportunidades Disponíveis</h2>
            <div id="opportunitiesList"></div>
        </section>
    </main>
    <script src="../js/script.js"></script>
</body>
</html>