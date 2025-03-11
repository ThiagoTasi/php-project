<?php
// Conexão com o banco de dados
include '../conn/connect.php';

// Inicia a sessão
session_start();

// Função para gerar código único de reserva
function gerarCodigoReserva()
{
    return strtoupper(uniqid('RES-', true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'reserva') {
    // Recebe os dados do formulário com verificação de existência
    $idcliente = isset($_SESSION['idcliente']) ? $_SESSION['idcliente'] : null;
    $data_reserva = isset($_POST['data_reserva']) ? $_POST['data_reserva'] : null;
    $horario = isset($_POST['horario']) ? $_POST['horario'] : null;
    $num_pessoas = isset($_POST['num_pessoas']) ? $_POST['num_pessoas'] : null;
    $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : '';
    $cod_reserva = gerarCodigoReserva();
    $status = 'pendente'; // Valor padrão para status

    // Validação de dados (apenas campos do formulário)
    if (empty($data_reserva) || empty($horario) || empty($num_pessoas)) {
        echo "<script>alert('Erro: Todos os campos obrigatórios devem ser preenchidos.');</script>";
    } else {
        try {
            // Se idcliente existe, verifica se é válido
            if (!empty($idcliente)) {
                $checkSql = "SELECT idcliente FROM cliente WHERE idcliente = :idcliente";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->bindParam(':idcliente', $idcliente);
                $checkStmt->execute();
                if ($checkStmt->rowCount() == 0) {
                    throw new Exception("O ID do cliente ($idcliente) não existe na tabela cliente.");
                }
            }

            // Prepara a inserção no banco de dados
            $sql = "INSERT INTO reserva (idcliente, data_reserva, horario, num_pessoas, motivo, cod_reserva, status)
                    VALUES (:idcliente, :data_reserva, :horario, :num_pessoas, :motivo, :cod_reserva, :status)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idcliente', $idcliente, PDO::PARAM_INT | PDO::PARAM_NULL);
            $stmt->bindParam(':data_reserva', $data_reserva);
            $stmt->bindParam(':horario', $horario);
            $stmt->bindParam(':num_pessoas', $num_pessoas);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':cod_reserva', $cod_reserva);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                echo "<script>alert('Reserva feita com sucesso! Código da reserva: $cod_reserva');</script>";
                header("Location: reserva_lista.php");
                exit();
            } else {
                echo "<script>alert('Erro ao fazer a reserva.');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/2495680ceb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/estilo.css" type="text/css">
    <title>Chuleta Quente - Reserva</title>
</head>
<body>
    <main class="container">
        <section>
            <article>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <h1 class="breadcrumb text-info text-center">Faça sua reserva</h1>
                        <div class="thumbnail">
                            <p class="text-info text-center" role="alert">
                                <i class="fas fa-user-plus fa-10x"></i>
                            </p>
                            <br>
                            <div class="alert alert-info" role="alert">
                                <form action="reserva_cliente.php" name="form_cadastro" id="form_cadastro" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="acao" value="reserva">

                                    <!-- Data da reserva -->
                                    <label for="data_reserva">Data da Reserva:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="date" name="data_reserva" id="data_reserva" class="form-control" required>
                                    </p>

                                    <!-- Horário -->
                                    <label for="horario">Horário:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="time" name="horario" id="horario" class="form-control" required>
                                    </p>

                                    <!-- Número de pessoas -->
                                    <label for="num_pessoas">Número de Pessoas:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-user text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="number" name="num_pessoas" id="num_pessoas" class="form-control" required min="1" max="100" placeholder="Digite o número de pessoas">
                                    </p>

                                    <!-- Motivo da reserva -->
                                    <label for="motivo">Motivo da Reserva:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-comment text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="text" name="motivo" id="motivo" class="form-control" maxlength="100" placeholder="Digite o motivo da reserva">
                                    </p>

                                    <!-- Botões para reservar ou cancelar -->
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-primary">Reservar</button>
                                        <button type="button" class="btn btn-primary" onclick="window.location.href=''">Cancelar</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>

    <!-- Scripts -->
    <script src="../js/jquery-1.12.4.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#form_cadastro').on('submit', function() {
                console.log('Formulário enviado!');
            });
        });
    </script>
</body>
</html>