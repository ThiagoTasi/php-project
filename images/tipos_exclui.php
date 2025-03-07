<?php
include "acesso_com.php";
include "../conn/connect.php";

// Verifica se o ID do tipo foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara e executa a consulta SQL para excluir o tipo
    $stmt = $pdo->prepare("DELETE FROM tipos WHERE id = ?");
    $stmt->execute([$id]);

    // Verifica se a exclusão foi bem-sucedida
    if ($stmt->rowCount()) {
        header("Location: tipos.php"); // Redireciona para a lista de tipos
        exit;
    } else {
        echo "Erro ao excluir o tipo.";
    }
} else {
    echo "ID do tipo não especificado.";
    exit;
}
?>