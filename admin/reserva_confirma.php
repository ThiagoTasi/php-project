<?php
include 'acesso_com.php';
include '../conn/connect.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'libs/phpqrcode/qrlib.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Valida ID da reserva
$idreserva = filter_input(INPUT_GET, 'idreserva', FILTER_VALIDATE_INT);
if ($idreserva === false || $idreserva <= 0) {
    header("Location: reserva_lista.php?msg=ID da reserva inválido!");
    exit();
}

// Busca informações da reserva e do cliente
$stmt = $pdo->prepare("
    SELECT r.*, c.nome_completo, c.email 
    FROM reserva r 
    LEFT JOIN cliente c ON r.idcliente = c.idcliente 
    WHERE r.idreserva = :idreserva
");
$stmt->execute([':idreserva' => $idreserva]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    header("Location: reserva_lista.php?msg=Reserva não encontrada!");
    exit();
}

if ($reserva['status'] === 'confirmado') {
    header("Location: reserva_lista.php?msg=Reserva já confirmada!");
    exit();
}

// Variáveis para controle
$confirmado = false;
$mensagem = '';
$qr_filename = '';

if (isset($_POST['num_mesa'])) {
    $num_mesa = filter_input(INPUT_POST, 'num_mesa', FILTER_VALIDATE_INT);
    if ($num_mesa === false || $num_mesa <= 0) {
        $mensagem = "Número da mesa inválido!";
    } else {
        $cod_reserva = 'RES' . str_pad($idreserva, 6, '0', STR_PAD_LEFT);

        // Transação para garantir consistência
        $pdo->beginTransaction();
        try {
            // Atualiza a reserva
            $update = $pdo->prepare("
                UPDATE reserva 
                SET status = 'confirmado', 
                    num_mesa = :num_mesa, 
                    cod_reserva = :cod_reserva 
                WHERE idreserva = :idreserva
            ");
            $update->execute([
                ':num_mesa' => $num_mesa,
                ':cod_reserva' => $cod_reserva,
                ':idreserva' => $idreserva
            ]);

            // Gera o QR Code
            $qr_content = "Reserva: $cod_reserva\nData: {$reserva['data_reserva']}\nHorário: {$reserva['horario']}\nMesa: $num_mesa\nPessoas: {$reserva['num_pessoas']}";
            $qr_dir = "../imagens/qrcodes/";
            $qr_filename = $qr_dir . "{$cod_reserva}.png";

            if (!file_exists($qr_dir)) {
                mkdir($qr_dir, 0755, true);
            }
            QRcode::png($qr_content, $qr_filename, QR_ECLEVEL_L, 5);

            // Verifica e-mail do cliente
            if (empty($reserva['email'])) {
                throw new Exception("Cliente sem e-mail cadastrado!");
            }

            // Configuração do PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'quentechuleta@gmail.com';
            $mail->Password = 'ognj wqfy jjmm hron'; // Mova para arquivo seguro
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('quentechuleta@gmail.com', 'Chuleta Quente');
            $mail->addAddress($reserva['email']);
            $mail->Subject = 'Confirmação de Reserva - Chuleta Quente';
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = "
                <h2>Reserva Confirmada!</h2>
                <p>Olá " . htmlspecialchars($reserva['nome_completo'] ?? 'Cliente') . ",</p>
                <p>Sua reserva foi confirmada com sucesso!</p>
                <ul>
                    <li>Código da Reserva: " . htmlspecialchars($cod_reserva) . "</li>
                    <li>Data: " . htmlspecialchars($reserva['data_reserva']) . "</li>
                    <li>Horário: " . htmlspecialchars($reserva['horario']) . "</li>
                    <li>Número de Pessoas: " . htmlspecialchars($reserva['num_pessoas']) . "</li>
                    <li>Mesa: " . htmlspecialchars($num_mesa) . "</li>
                </ul>
                <p><img src='cid:qrcode' alt='QR Code' style='width: 150px; height: 150px;'></p>
            ";
            $mail->addEmbeddedImage($qr_filename, 'qrcode', "{$cod_reserva}.png");
            $mail->send();

            $pdo->commit();
            $confirmado = true;
            $mensagem = "Reserva confirmada e e-mail enviado com sucesso!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $confirmado = false;
            $mensagem = "Erro ao confirmar reserva: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Reserva - Chuleta Quente</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body { margin: 0; padding: 0; }
        .navbar { margin-bottom: 0; }
        main.container { margin-top: 0; padding-top: 0; }
    </style>
</head>
<body>
    <?php include 'menu_adm.php'; ?>
    <main class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
                <?php if (!$confirmado && empty($mensagem)) { ?>
                    <h2 class="breadcrumb alert-success">
                        <a href="reserva_lista.php" class="btn btn-success">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </a>
                        Confirmar Reserva #<?php echo htmlspecialchars($idreserva); ?>
                    </h2>
                    <div class="thumbnail">
                        <div class="alert alert-success" role="alert">
                            <form method="POST" name="form_confirma_reserva" id="form_confirma_reserva">
                                <label for="num_mesa">Número da Mesa:</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                                    </span>
                                    <input type="number" name="num_mesa" id="num_mesa" class="form-control" 
                                           placeholder="Digite o número da mesa" required min="1" autocomplete="off">
                                </div>
                                <br>
                                <input type="submit" value="Confirmar" class="btn btn-success btn-block">
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <h2 class="breadcrumb <?php echo $confirmado ? 'alert-success' : 'alert-danger'; ?>">
                        <a href="reserva_lista.php" class="btn <?php echo $confirmado ? 'btn-success' : 'btn-danger'; ?>">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </a>
                        Resultado da Confirmação
                    </h2>
                    <div class="thumbnail">
                        <div class="alert <?php echo $confirmado ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                            <h4><?php echo htmlspecialchars($mensagem); ?></h4>
                            <?php if ($confirmado) { ?>
                                <p><strong>Detalhes da Reserva:</strong></p>
                                <ul>
                                    <li>Código: <?php echo htmlspecialchars($cod_reserva); ?></li>
                                    <li>Data: <?php echo htmlspecialchars($reserva['data_reserva']); ?></li>
                                    <li>Horário: <?php echo htmlspecialchars($reserva['horario']); ?></li>
                                    <li>Pessoas: <?php echo htmlspecialchars($reserva['num_pessoas']); ?></li>
                                    <li>Mesa: <?php echo htmlspecialchars($num_mesa); ?></li>
                                </ul>
                                <p><strong>QR Code:</strong></p>
                                <img src="/imagens/qrcodes/<?php echo htmlspecialchars(basename($qr_filename)); ?>" 
                                     alt="QR Code" style="width: 150px; height: 150px;">
                            <?php } ?>
                            <a href="reserva_lista.php" class="btn <?php echo $confirmado ? 'btn-success' : 'btn-danger'; ?> btn-block">
                                Voltar para Lista de Reservas
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
</html>