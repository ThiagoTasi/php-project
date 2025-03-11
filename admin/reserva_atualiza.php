<?php
// Incluindo o sistema de autenticação SUPERVISOR
include "acesso_com.php";

// Incluir o arquivo e fazer a conexão
include "../conn/connect.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idreserva = $_POST['idreserva'];
    $idcliente = $_POST['idcliente'];
    $data_reserva = $_POST['data_reserva'];
    $horario = $_POST['horario'];
    $num_pessoas = $_POST['num_pessoas'];
    $motivo = $_POST['motivo'];
    $status = $_POST['status'];
    $num_mesa = $_POST['num_mesa'];
    $motivo_negativa = $_POST['motivo_negativa'];
    $cod_reserva = $_POST['cod_reserva'];

    // Atualizando os dados
    try {
        $sql = "UPDATE reserva SET idcliente = :idcliente, data_reserva = :data_reserva, horario = :horario, num_pessoas = :num_pessoas, motivo = :motivo, status = :status, num_mesa = :num_mesa, motivo_negativa = :motivo_negativa, cod_reserva = :cod_reserva WHERE idreserva = :idreserva";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idreserva', $idreserva);
        $stmt->bindParam(':idcliente', $idcliente);
        $stmt->bindParam(':data_reserva', $data_reserva);
        $stmt->bindParam(':horario', $horario);
        $stmt->bindParam(':num_pessoas', $num_pessoas);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':num_mesa', $num_mesa);
        $stmt->bindParam(':motivo_negativa', $motivo_negativa);
        $stmt->bindParam(':cod_reserva', $cod_reserva);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['atualizacao_reserva_sucesso'] = true; // Define a variável de sessão
            header('location:reserva_lista.php');
            exit();
        } else {
            echo "Erro ao atualizar reserva";
        }
    } catch (PDOException $e) {
        echo "Erro:" . $e->getMessage();
    }
} else {
    echo "Metodo de requisição inválido";
}
if (isset($_GET['idreserva'])) {
    $reserva = $pdo->query("SELECT * FROM reserva WHERE idcliente =" . $_GET['idcliente']);
    $reservarow = $reserva->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Reservas - Atualiza</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/meu_estilo.css" type="text/css">
</head>

<body class="fundofixo">
    <?php include "menu_adm.php"; ?>
    <main class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
                <h2 class="breadcrumb text-info">
                    <a href="reserva_lista.php">
                        <button class="btn btn-info" type="button">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </button>
                    </a>
                    Atualizar Reserva
                </h2>
                <div class="thumbnail">
                    <div class="alert alert-info">
                        <form action="reserva_atualiza.php" name="form_atualiza" id="form_atualiza" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="idreserva" id="idreserva" value="<?php echo $reservarow['idreserva'] ?>">

                            <label for="idcliente">ID Cliente:</label>
                            <input type="number" name="idcliente" id="idcliente" class="form-control" value="<?php echo $reservarow['idcliente']; ?>">

                            <label for="data_reserva">Data da Reserva:</label>
                            <input type="date" name="data_reserva" id="data_reserva" class="form-control" value="<?php echo $reservarow['data_reserva']; ?>">

                            <label for="horario">Horário:</label>
                            <input type="time" name="horario" id="horario" class="form-control" value="<?php echo $reservarow['horario']; ?>">

                            <label for="num_pessoas">Número de Pessoas:</label>
                            <input type="number" name="num_pessoas" id="num_pessoas" class="form-control" value="<?php echo $reservarow['num_pessoas']; ?>">

                            <label for="motivo">Motivo:</label>
                            <input type="text" name="motivo" id="motivo" class="form-control" value="<?php echo $reservarow['motivo']; ?>">

                            <label for="status">Status:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pendente" <?php echo ($reservarow['status'] == 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                                <option value="confirmado" <?php echo ($reservarow['status'] == 'confirmado') ? 'selected' : ''; ?>>Confirmado</option>
                                <option value="negado" <?php echo ($reservarow['status'] == 'negado') ? 'selected' : ''; ?>>Negado</option>
                                <option value="cancelado" <?php echo ($reservarow['status'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                                <option value="expirado" <?php echo ($reservarow['status'] == 'expirado') ? 'selected' : ''; ?>>Expirado</option>
                            </select>

                            <label for="num_mesa">Número da Mesa:</label>
                            <input type="number" name="num_mesa" id="num_mesa" class="form-control" value="<?php echo $reservarow['num_mesa']; ?>">

                            <label for="motivo_negativa">Motivo da Negativa:</label>
                            <input type="text" name="motivo_negativa" id="motivo_negativa" class="form-control" value="<?php echo $reservarow['motivo_negativa']; ?>">

                            <label for="cod_reserva">Código da Reserva:</label>
                            <input type="text" name="cod_reserva" id="cod_reserva" class="form-control" value="<?php echo $reservarow['cod_reserva']; ?>">

                            <input type="submit" value="Atualizar" role="button" name="enviar" id="enviar" class="btn btn-block btn-info">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>
<?php
// Na página reserva_lista.php
session_start();
if (isset($_SESSION['atualizacao_reserva_sucesso']) && $_SESSION['atualizacao_reserva_sucesso'] === true) {
    echo "<script>alert('Reserva atualizada com sucesso!');</script>";
    unset($_SESSION['atualizacao_reserva_sucesso']); // Limpa a variável de sessão
}
?>