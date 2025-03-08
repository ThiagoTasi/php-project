<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "tinsphpdb01");

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Erro de conexão']);
    exit;
}

$id = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário não fornecido']);
    exit;
}

$sql = "DELETE FROM usuarios WHERE idusuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Usuário excluído com sucesso']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir usuário']);
}

$stmt->close();
$conn->close();
?>