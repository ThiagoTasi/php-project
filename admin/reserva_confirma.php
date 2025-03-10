<?php
include 'acesso_com.php';
include '../conn/connect.php';
 
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'libs/phpqrcode/qrlib.php'; // Biblioteca QR Code
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
// Verifica se o ID foi passado via GET
if (!isset($_GET['id'])) {
    header("Location: reserva_lista.php?msg=ID da reserva não fornecido!");
    exit();
}
 
$id = $_GET['id'];
 
// Busca informações da reserva e do cliente
$stmt = $pdo->prepare("
    SELECT r.*, c.nome, c.email
    FROM reserva r
    JOIN cliente c ON r.idcliente = c.id
    WHERE r.id = :id
");
$stmt->execute([':id' => $id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);
 
if (!$reserva) {
    header("Location: reserva_lista.php?msg=Reserva não encontrada!");
    exit();
}
 
// Variáveis para controle da confirmação
$confirmado = false;
$mensagem = '';
$qr_filename = '';
 
if ($_POST) {
    $num_mesa = $_POST['num_mesa'];
    $cod_reserva = 'RES' . str_pad($id, 6, '0', STR_PAD_LEFT);
 
    // Atualiza a reserva no banco de dados
    $update = $pdo->prepare("
        UPDATE reserva
        SET status = 'confirmado',
            num_mesa = :mesa,
            cod_reserva = :codigo
        WHERE id = :id
    ");
    $update->execute([
        ':num_mesa' => $num_mesa,
        ':cod_reserva' => $cod_reserva,
        ':id' => $id
    ]);
 
    // Gera o QR Code
    $qr_content = "Reserva: $cod_reserva\nData: {$reserva['data_reserva']}\nHorário: {$reserva['horario']}\nMesa: $num_mesa\nPessoas: {$reserva['num_pessoas']}";
    $qr_dir = "../imagens/qrcodes/";
    $qr_filename = $qr_dir . "{$cod_reserva}.png";
 
    // Cria o diretório se não existir
    if (!file_exists($qr_dir)) {
        mkdir($qr_dir, 0777, true);
    }
 
    // Gera o QR Code
    QRcode::png($qr_content, $qr_filename, QR_ECLEVEL_L, 5);
 
    // Configuração e envio do email
    $mail = new PHPMailer(true);
    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'quentechuleta@gmail.com';
        $mail->Password = 'ognj wqfy jjmm hron'; // Senha de aplicativo do Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
 
        // Configurações do email
        $mail->setFrom('quentechuleta@gmail.com', 'Chuleta Quente');
        $mail->addAddress($reserva['email']); // Email do cliente
        $mail->Subject = 'Confirmação de Reserva - Chuleta Quente';
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
 
        // Corpo do email com detalhes e QR Code
        $mail->Body = "
            <h2>Reserva Confirmada!</h2>
            <p>Olá {$reserva['nome']},</p>
            <p>Sua reserva foi confirmada com sucesso!</p>
            <p><strong>Detalhes da Reserva:</strong></p>
            <ul>
                <li>Código da Reserva: {$cod_reserva}</li>
                <li>Data: {$reserva['data_reserva']}</li>
                <li>Horário: {$reserva['horario']}</li>
                <li>Número de Pessoas: {$reserva['num_pessoas']}</li>
                <li>Mesa: {$num_mesa}</li>
            </ul>
            <p><strong>QR Code da Reserva:</strong></p>
            <p>Apresente este QR Code ao chegar ao restaurante:</p>
            <img src='cid:qrcode' alt='QR Code da Reserva' style='width: 150px; height: 150px;'>
            <p>Agradecemos pela preferência!</p>
            <p>Equipe Chuleta Quente</p>
        ";
 
        // Anexa o QR Code como imagem embutida
        if (file_exists($qr_filename)) {
            $mail->addEmbeddedImage($qr_filename, 'qrcode', "{$cod_reserva}.png");
        } else {
            throw new Exception("QR Code não encontrado em $qr_filename");
        }
 
        // Envia o email
        $mail->send();
 
        // Define sucesso
        $confirmado = true;
        $mensagem = "Reserva confirmada, QR Code gerado e e-mail enviado com sucesso para {$reserva['email']}!";
    } catch (Exception $e) {
        $confirmado = false;
        $mensagem = "Reserva confirmada, mas erro ao enviar e-mail: " . $mail->ErrorInfo;
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Reserva - Chuleta Quente</title>
    <link rel="stylesheet" href="../css/bootstrap.min (1).css">
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
                        <a href="reservas_lista.php">
                            <button class="btn btn-success" type="button">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            </button>
                        </a>
                        Confirmar Reserva #<?php echo $id; ?>
                    </h2>
                    <div class="thumbnail">
                        <div class="alert alert-success" role="alert">
                            <form method="POST" name="form_confirma_reserva" id="form_reserva_confirma">
                                <label for="num_mesa">Número da Mesa:</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                                    </span>
                                    <input type="number" name="num_mesa" id="num_mesa" class="form-control"
                                           placeholder="Digite o número da mesa" required autocomplete="off">
                                </div>
                                <br>
                                <input type="submit" value="Confirmar" class="btn btn-success btn-block">
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <h2 class="breadcrumb <?php echo $confirmado ? 'alert-success' : 'alert-danger'; ?>">
                        <a href="reserva_lista.php">
                            <button class="btn <?php echo $confirmado ? 'btn-success' : 'btn-danger'; ?>" type="button">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            </button>
                        </a>
                        Resultado da Confirmação
                    </h2>
                    <div class="thumbnail">
                        <div class="alert <?php echo $confirmado ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                            <h4><?php echo $mensagem; ?></h4>
                            <?php if ($confirmado) { ?>
                                <p><strong>Detalhes da Reserva:</strong></p>
                                <ul>
                                    <li>Código: <?php echo $cod_reserva; ?></li>
                                    <li>Data: <?php echo $reserva['data_reserva']; ?></li>
                                    <li>Horário: <?php echo $reserva['horario']; ?></li>
                                    <li>Pessoas: <?php echo $reserva['num_pessoas']; ?></li>
                                    <li>Mesa: <?php echo $num_mesa; ?></li>
                                </ul>
                                <p><strong>QR Code:</strong></p>
                                <img src="<?php echo $qr_filename; ?>" alt="QR Code" style="width: 150px; height: 150px;">
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
 