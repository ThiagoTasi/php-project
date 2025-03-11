<?php
include 'acesso_com.php'; // Gerencia a sessão
include '../conn/connect.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Valida ID da reserva
$idreserva = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($idreserva === false || $idreserva <= 0) {
    header("Location: reservas_lista.php?msg=ID da reserva inválido!");
    exit();
}

// Busca informações da reserva e do cliente
$stmt = $pdo->prepare("
    SELECT r.*, c.nome_completo, c.email 
    FROM reservas r 
    JOIN clientes c ON r.cliente_id = c.id 
    WHERE r.id = :id
");
$stmt->execute([':id' => $idreserva]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    header("Location: reservas_lista.php?msg=Reserva não encontrada!");
    exit();
}

// Verifica se a reserva já foi negada
if ($reserva['status'] === 'negado') {
    header("Location: reservas_lista.php?msg=Reserva já foi negada anteriormente!");
    exit();
}

// Processa o formulário
if (isset($_POST['motivo_negativa'])) {
    $motivo_negativa = filter_input(INPUT_POST, 'motivo_negativa', FILTER_SANITIZE_STRING);
    if (empty($motivo_negativa)) {
        $mensagem = "Por favor, forneça um motivo para a negativa.";
    } else {
        // Inicia transação para consistência
        $pdo->beginTransaction();
        try {
            // Atualiza a reserva
            $stmt = $pdo->prepare("
                UPDATE reservas 
                SET status = 'negado', 
                    motivo_negativa = :motivo 
                WHERE id = :id
            ");
            $stmt->execute([
                ':motivo' => $motivo_negativa,
                ':id' => $idreserva
            ]);

            // Verifica se o cliente tem e-mail
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
            $mail->Subject = 'Reserva Negada - Chuleta Quente';
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = "
                <h2>Reserva Negada</h2>
                <p>Olá " . htmlspecialchars($reserva['nome_completo']) . ",</p>
                <p>Infelizmente, sua reserva não pôde ser confirmada.</p>
                <p><strong>Detalhes da Reserva:</strong></p>
                <ul>
                    <li>Data: " . htmlspecialchars($reserva['data_reserva']) . "</li>
                    <li>Horário: " . htmlspecialchars($reserva['horario']) . "</li>
                    <li>Número de Pessoas: " . htmlspecialchars($reserva['num_pessoas']) . "</li>
                </ul>
                <p><strong>Motivo da Negativa:</strong> " . htmlspecialchars($motivo_negativa) . "</p>
                <p>Pedimos desculpas pelo inconveniente. Entre em contato conosco se precisar de mais informações.</p>
                <p>Equipe Chuleta Quente</p>
            ";
            $mail->send();

            // Confirma transação
            $pdo->commit();
            header("Location: reservas_lista.php?msg=Reserva negada e e-mail enviado com sucesso!");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: reservas_lista.php?msg=Reserva negada, mas erro ao enviar e-mail: " . htmlspecialchars($e->getMessage()));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negar Reserva - Chuleta Quente</title>
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
                <h2 class="breadcrumb alert-danger">
                    <a href="reservas_lista.php" class="btn btn-danger">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                    Negar Reserva #<?php echo htmlspecialchars($idreserva); ?>
                </h2>
                <div class="thumbnail">
                    <div class="alert alert-danger" role="alert">
                        <?php if (!empty($mensagem)) { ?>
                            <p><?php echo htmlspecialchars($mensagem); ?></p>
                        <?php } ?>
                        <form method="POST" name="form_nega_reserva" id="form_nega_reserva">
                            <label for="motivo_negativa">Motivo da Negativa:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                                </span>
                                <textarea name="motivo_negativa" id="motivo_negativa" class="form-control" 
                                          placeholder="Digite o motivo da negativa" required rows="3"></textarea>
                            </div>
                            <br>
                            <input type="submit" value="Negar" class="btn btn-danger btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>