<?php
include "acesso_com.php";
require "../conn/connect.php";

$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $nivel = $_POST['nivel'];

    try {
        // Verificar se o login já existe
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE login = :login");
        $stmt_check->bindParam(':login', $login);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            $mensagem_erro = "Erro: O login '$login' já existe.";
        } else {
            // Inserir novo usuário
            $sql = "INSERT INTO usuarios (login, senha, nivel) VALUES (:login, :senha, :nivel)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':nivel', $nivel);

            if ($stmt->execute()) {
                session_start();
                $_SESSION['cadastro_sucesso'] = true;
                header('location:usuarios_lista.php');
                exit();
            } else {
                $mensagem_erro = "Erro ao cadastrar usuário.";
            }
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Usuários - Insere</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/meu_estilo.css" type="text/css">
</head>
<body>
    <?php include "menu_adm.php"; ?>
    <main class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
                <h2 class="breadcrumb text-info">
                    <a href="usuarios_lista.php">
                        <button class="btn btn-info" type="button">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </button>
                    </a>
                    Inserindo Usuários
                </h2>
                <div class="thumbnail">
                    <div class="alert alert-info">
                        <?php if (!empty($mensagem_erro)) { ?>
                            <div class="alert alert-danger"><?php echo $mensagem_erro; ?></div>
                        <?php } ?>
                        <form action="usuarios_insere.php" name="form_insere_usuario" id="form_insere_usuario" method="POST" enctype="multipart/form-data">
                            <label for="login_usuario">Login:</label>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                <input type="text" name="login" id="login" autofocus maxlength="30" placeholder="Digite o seu login." class="form-control" required autocomplete="off">
                            </div>
                            <br>
                            <label for="senha">Senha:</label>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span></span>
                                <input type="password" name="senha" id="senha" maxlength="8" placeholder="Digite a senha desejada." class="form-control" required autocomplete="off">
                            </div>
                            <br>
                            <label for="nivel">Nível do usuário</label>
                            <div class="input-group">
                                <label for="nivel_c" class="radio-inline">
                                    <input type="radio" name="nivel" id="nivel_c" value="com" checked>Comum
                                </label>
                                <label for="nivel_s" class="radio-inline">
                                    <input type="radio" name="nivel" id="nivel_s" value="sup">Supervisor
                                </label>
                            </div>
                            <br>
                            <input type="submit" value="Cadastrar" role="button" name="enviar" id="enviar" class="btn btn-block btn-info">
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