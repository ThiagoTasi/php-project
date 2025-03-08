<?php
include "../conn/connect.php";

if (isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        $delete = $pdo->prepare("DELETE FROM tipos WHERE id = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);
        if ($delete->execute()) {
            echo "success";
        } else {
            echo "Erro ao excluir o tipo.";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "ID não fornecido.";
}
?>