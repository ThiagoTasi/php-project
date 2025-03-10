<?php
include "acesso_com.php";
include "../conn/connect.php";

$lista = $pdo->query("SELECT * FROM reserva ORDER BY data_reserva DESC, horario DESC");
$row = $lista->fetch(PDO::FETCH_ASSOC);
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
                            <a href="#" class="btn btn-block btn-primary btn-xs disabled" role="button">
                                <span class="hidden-xs">ADICIONAR <br></span>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($numrow > 0) { ?>
                        <?php do { ?>
                            <tr>
                                <td class="hidden"><?php echo $row['idreserva']; ?></td>
                                <td><?php echo $row['idcliente']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['data_reserva'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['horario'])); ?></td>
                                <td><?php echo $row['num_pessoas']; ?></td>
                                <td><?php echo $row['motivo']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['num_mesa']; ?></td>
                                <td><?php echo $row['cod_reserva']; ?></td>
                                <td>
                                    <a href="reserva_atualiza.php?idreserva=<?php echo $row['cod_reserva'] ?>" class="btn btn-warning btn-block btn-xs">
                                        <span class="hidden-xs">CONFIRMAR <br></span>
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                                    </a>
                                    <button data-nome="<?php echo $row['cod_reserva'] ?>" data-id="<?php echo $row['cod_reserva'] ?>" class="delete btn btn-danger btn-block btn-xs">
                                        <span class="hidden-xs">NEGAR <br></span>
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php } while ($row = $lista->fetch(PDO::FETCH_ASSOC)) ?>
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