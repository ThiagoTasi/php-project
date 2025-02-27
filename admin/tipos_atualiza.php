<?php
include "acesso_com.php";
include "../conn/connect.php";

// Verifica se o ID do tipo foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara e executa a consulta SQL para buscar os dados do tipo
    $stmt = $pdo->prepare("SELECT * FROM tipos WHERE id = ?");
    $stmt->execute([$id]);
    $tipo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o tipo existe
    if (!$tipo) {
        echo "Tipo não encontrado.";
        exit;
    }
} else {
    echo "ID do tipo não especificado.";
    exit;
}

// Verifica se o formulário de atualização foi enviado
if (isset($_POST['atualizar'])) {
    $rotulo = $_POST['rotulo'];
    $sigla = $_POST['sigla'];

    // Prepara e executa a consulta SQL para atualizar o tipo
    $stmt = $pdo->prepare("UPDATE tipos SET rotulo = ?, sigla = ? WHERE id = ?");
    $stmt->execute([$rotulo, $sigla, $id]);

    // Verifica se a atualização foi bem-sucedida
    if ($stmt->rowCount()) {
        header("Location: tipos.php"); // Redireciona para a lista de tipos
        exit;
    } else {
        echo "Erro ao atualizar o tipo.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Atualizar Tipo</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Atualizar Tipo</h1>
        <form method="post">
            <div class="form-group">
                <label for="rotulo">Rótulo:</label>
                <input type="text" name="rotulo" id="rotulo" class="form-control" value="<?php echo $tipo['rotulo']; ?>" required>
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" name="sigla" id="sigla" class="form-control" value="<?php echo $tipo['sigla']; ?>" required>
            </div>
            <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
            <a href="tipos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>