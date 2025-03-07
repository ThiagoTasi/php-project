<?php
// Conexão com o banco de dados
include '../conn/connect.php';

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
// if (!isset($_SESSION['reserva_cliente'])) {
//     header("Location: reserva_cliente.php");
//     exit();
// }

// Função para gerar código único de reserva
function gerarCodigoReserva() {
    return strtoupper(uniqid('RES-', true)); // Gera um código único
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'reserva') {
    // Recebe os dados do formulário
    $cliente_id = $_POST['cliente_id']; // ID do cliente (usando sessão ou valor enviado)
    $data_reserva = $_POST['data_reserva'];
    $horario = $_POST['horario'];
    $num_pessoas = $_POST['num_pessoas'];
    $motivo = $_POST['motivo'];
    $status = $_POST['status'];
    $numero_mesa = $_POST['numero_mesa'];
    $motivo_negativa = isset($_POST['motivo_negativa']) ? $_POST['motivo_negativa'] : ''; // Motivo de negativa (se houver)
    $codigo_reserva = gerarCodigoReserva(); // Gera o código da reserva

    // Validação de dados
    if (empty($data_reserva) || empty($horario) || empty($num_pessoas) || empty($numero_mesa) || empty($status)) {
        echo "<script>alert('Erro: Todos os campos são obrigatórios.');</script>";
    } else {
        try {
            // Prepara a inserção no banco de dados
            $sql = "INSERT INTO reserva (cliente_id, data_reserva, horario, num_pessoas, motivo, status, numero_mesa, motivo_negativa, codigo_reserva)
                    VALUES (:cliente_id, :data_reserva, :horario, :num_pessoas, :motivo, :status, :numero_mesa, :motivo_negativa, :codigo_reserva)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->bindParam(':data_reserva', $data_reserva);
            $stmt->bindParam(':horario', $horario);
            $stmt->bindParam(':num_pessoas', $num_pessoas);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':numero_mesa', $numero_mesa);
            $stmt->bindParam(':motivo_negativa', $motivo_negativa);
            $stmt->bindParam(':codigo_reserva', $codigo_reserva);

            // Executa a consulta
            if ($stmt->execute()) {
                echo "<script>alert('Reserva feita com sucesso! Código da reserva: $codigo_reserva');</script>";
                // Redirecionar para uma página de confirmação ou lista de reservas
                header("Location: reservas_lista.php");
                exit();
            } else {
                echo "<script>alert('Erro ao fazer a reserva.');</script>";
            }
        } catch (PDOException $e) {
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

                                    <!-- cliente_id (campo oculto ou atribuído automaticamente, se necessário) -->
                                    <input type="hidden" name="cliente_id" id="cliente_id" value="ID_DO_CLIENTE">

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

                                    <!-- Status da reserva -->
                                    <label for="status">Status da Reserva:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-check text-info" aria-hidden="true"></span>
                                        </span>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="pendente">Pendente</option>
                                            <option value="confirmado">Confirmado</option>
                                            <option value="negado">Negado</option>
                                            <option value="cancelado">Cancelado</option>
                                            <option value="expirado">Expirado</option>
                                        </select>
                                    </p>

                                    <!-- Número da mesa -->
                                    <label for="numero_mesa">Número da Mesa:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-th text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="number" name="numero_mesa" id="numero_mesa" class="form-control" required placeholder="Digite o número da mesa">
                                    </p>

                                    <!-- Motivo da negativa (apenas se o status for 'negado', pode ser preenchido depois) -->
                                    <label for="motivo_negativa">Motivo da Negativa:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-exclamation-sign text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="text" name="motivo_negativa" id="motivo_negativa" class="form-control" placeholder="Digite o motivo da negativa (se aplicável)">
                                    </p>

                                    <!-- Código de reserva (gerado automaticamente pelo sistema) -->
                                    <label for="codigo_reserva">Código da Reserva:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-barcode text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="text" name="codigo_reserva" id="codigo_reserva" class="form-control" required readonly placeholder="Código gerado automaticamente">
                                    </p>

                                    <!-- Botões para reservar ou cancelar -->
                                    <p class="text-right">
                                        <button type="submit" value="Reservar" class="btn btn-primary">Reservar</button>
                                        </p>
                                    </p>
                                    <br>
                                    <p class="text-right">
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='reserva_cliente.php'">Cancelar</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>
</body>

</html>





<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>

</html>