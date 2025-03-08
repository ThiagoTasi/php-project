<?php
include "acesso_com.php";
include "../conn/connect.php";

$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $nivel = $_POST['nivel'];
    $idusuario = $_POST['id']; // Ajustado para idusuario

    try {
        $sql = "UPDATE usuarios SET login = :login, senha = :senha, nivel = :nivel WHERE idusuario = :idusuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT); // Ajustado para idusuario
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':nivel', $nivel);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['atualizacao_sucesso'] = true;
            header('location:usuarios_lista.php');
            exit();
        } else {
            $mensagem_erro = "Erro ao atualizar usuário.";
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro: " . $e->getMessage();
    }
} elseif (isset($_GET['id'])) {
    try {
        $idusuario = $_GET['id']; // Ajustado para idusuario
        $sql = "SELECT * FROM usuarios WHERE idusuario = :idusuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT); // Ajustado para idusuario
        $stmt->execute();
        $userrow = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao obter dados do usuário: " . $e->getMessage(), 0);
        $mensagem_erro = "Ocorreu um erro ao obter os dados do usuário.";
        $userrow = [];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Usuários - Atualiza</title>
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
                    <a href="usuarios_lista.php">
                        <button class="btn btn-info" type="button">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </button>
                    </a>
                    Atualizar Usuário
                </h2>
                <div class="thumbnail">
                    <div class="alert alert-info">
                        <?php if (!empty($mensagem_erro)) { ?>
                            <div class="alert alert-danger"><?php echo $mensagem_erro; ?></div>
                        <?php } ?>
                        <form action="usuarios_atualiza.php" name="form_atualiza" id="form_atualiza" method="POST" enctype="multipart/form-data">
                            <?php if (isset($userrow) && is_array($userrow) && !empty($userrow)) { ?>
                                <input type="hidden" name="id" id="id" value="<?php echo $userrow['idusuario']; ?>"> <!-- Ajustado para idusuario -->
                                <label for="login">Login:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                    <input type="text" name="login" id="login" autofocus maxlength="30" placeholder="Digite o seu login." class="form-control" required autocomplete="off" value="<?php echo $userrow['login']; ?>">
                                </div>
                                <br>
                                <label for="senha">Senha:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span></span>
                                    <input type="password" name="senha" id="senha" maxlength="8" placeholder="Digite a NOVA senha desejada." class="form-control" required autocomplete="off">
                                </div>
                                <br>
                                <label for="nivel">Nível do usuário</label>
                                <div class="input-group">
                                    <label for="nivel_c" class="radio-inline">
                                        <input type="radio" name="nivel" id="nivel_c" value="com" <?php echo isset($userrow['nivel']) && $userrow['nivel'] == 'com' ? "checked" : null; ?>>Comum
                                    </label>
                                    <label for="nivel_s" class="radio-inline">
                                        <input type="radio" name="nivel" id="nivel_s" value="sup" <?php echo isset($userrow['nivel']) && $userrow['nivel'] == 'sup' ? "checked" : null; ?>>Supervisor
                                    </label>
                                </div>
                                <br>
                                <input type="submit" value="Atualizar" role="button" name="enviar" id="enviar" class="btn btn-block btn-info">
                            <?php } else { ?>
                                <p>Usuário não encontrado.</p>
                            <?php } ?>
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