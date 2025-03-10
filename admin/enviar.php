<?php
require 'reserva_atualiza.php';
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
 
$mail = new PHPMailer(true);
 
try {
    // Configurações do servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'quentechuleta@gmail.com'; // Seu e-mail Gmail
    $mail->Password = 'kpje xjrn evqf mitg';      // Sua senha de aplicativo
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
 
    // Configurações do e-mail
    $mail->setFrom('quentechuleta@gmail.com', 'Teste'); // Remetente fictício
    $mail->addAddress('thiagosilvate@gmail.com');      // Destinatário (seu e-mail)
    $mail->Subject = 'Teste de Envio';
    $mail->Body = 'Este é um e-mail de teste!';
    $mail->CharSet = 'UTF-8';
 
    // Envia o e-mail
    $mail->send();
    echo "E-mail de teste enviado com sucesso!";
} catch (Exception $e) {
    echo "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
}