<?php
include "acesso_com.php";
include "../conn/connect.php";

$lista = $pdo->query("SELECT * FROM reserva ORDER BY data_reserva DESC, horario DESC");
$numrow = $lista->rowCount();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Chuleta Quente - Reservas</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css" type="text/css">
</head>
<body>
    <?php include "menu_adm.php"; ?>
    <main class="container">
        <h1 class="breadcrumb alert-warning">Lista de Reservas</h1>
        <div class="col-xs-12 col-sm-offset-0 col-sm-12 col-md-offset-0 col-md-12">
            <table class="table table-hover table-condensed tbopacidade">
                <thead>
                    <tr>
                        <th class="hidden">idreserva</th>
                        <th>idcliente</th>
                        <th>data_reserva</th>
                        <th>horario</th>
                        <th>num_pessoas</th>
                        <th>motivo</th>
                        <th>status</th>
                        <th>num_mesa</th>
                        <th>cod_reserva</th>
                        <th>
                            <a href="reserva_cliente.php" class="btn btn-block btn-primary btn-xs" role="button">
                                <span class="hidden-xs">ADICIONAR <br></span>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($numrow > 0) { ?>
                        <?php while ($row = $lista->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td class="hidden"><?php echo htmlspecialchars($row['idreserva']); ?></td>
                                <td><?php echo htmlspecialchars($row['idcliente']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['data_reserva'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['horario'])); ?></td>
                                <td><?php echo htmlspecialchars($row['num_pessoas']); ?></td>
                                <td><?php echo htmlspecialchars($row['motivo']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['num_mesa']); ?></td>
                                <td><?php echo htmlspecialchars($row['cod_reserva']); ?></td>
                                <td>
                                    <a href="reserva_confirma.php?idreserva=<?php echo htmlspecialchars($row['cod_reserva']); ?>" class="btn btn-warning btn-block btn-xs">
                                        <span class="hidden-xs">CONFIRMAR <br></span>
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                                    </a>
                                    <a href="reserva_nega.php?idreserva=<?php echo htmlspecialchars($row['cod_reserva']); ?>" class="btn btn-danger btn-block btn-xs">
                                        <span class="hidden-xs">NEGAR <br></span>
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="10">Nenhuma reserva encontrada.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>