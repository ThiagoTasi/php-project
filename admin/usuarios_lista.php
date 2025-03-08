<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Chuleta Quente - Usuarios</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css" type="text/css">
</head>
<body>
    <nav class="nav navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar" aria-expanded="false">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand">
                    <img src="../images/logo-chuleta.png" alt="">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="defaultNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <button type="button" class="btn btn-danger navbar-btn disabled" style="cursor: default;">
                            Olá, well
                        </button>
                    </li>
                    <li class="active"><a href="index.php">ADMIN</a></li>
                    <li><a href="produtos_lista.php">PRODUTOS</a></li>
                    <li><a href="tipos_lista.php">TIPOS</a></li>
                    <li><a href="usuarios_lista.php">USUÁRIOS</a></li>
                    <li class="active">
                        <a href="../index.php">
                            <span class="glyphicon glyphicon-home"></span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <span class="glyphicon glyphicon-log-out"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container">
        <h1 class="breadcrumb alert-warning">Lista de Usuário</h1>
        <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
            <table class="table table-hover table-condensed tbopacidade">
                <thead>
                    <tr>
                        <th class="hidden">ID</th>
                        <th>LOGIN</th>
                        <th>NÍVEL</th>
                        <th>
                            <a href="usuarios_insere.php" target="_self" class="btn btn-block btn-primary btn-xs" role="button">
                                <span class="hidden-xs">ADICIONAR <br></span>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conexão com o banco de dados corrigido para 'tinsphpdb01'
                    $conn = new mysqli("localhost", "root", "", "tinsphpdb01");

                    if ($conn->connect_error) {
                        die("Erro de conexão: " . $conn->connect_error);
                    }

                    // Consulta ajustada para a estrutura da tabela
                    $sql = "SELECT idusuario, login, nivel FROM usuarios";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $idusuario = $row['idusuario'];
                            $login = $row['login'];
                            $nivel = $row['nivel'];
                            $nivel_label = ($nivel == 'sup') ? 'sup' : 'com';
                            $nivel_icon = ($nivel == 'sup') ? 'sunglasses' : 'user';

                            echo "<tr>";
                            echo "<td class='hidden'>{$idusuario}</td>";
                            echo "<td>{$login}</td>";
                            echo "<td><span class='glyphicon glyphicon-{$nivel_icon} text-info' aria-hidden='true'></span> - {$nivel_label}</td>";
                            echo "<td>";
                            echo "<a href='usuarios_atualiza.php?id={$idusuario}' class='btn btn-warning btn-sm'>";
                            echo "<span class='glyphicon glyphicon-refresh' aria-hidden='true'>ALTERAR</span>";
                            echo "</a>";
                            echo "<button data-nome='{$login}' data-id='{$idusuario}' class='delete btn btn-danger btn-sm'>";
                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true'>EXCLUIR</span>";
                            echo "</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhum usuário encontrado</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title text-danger">ATENÇÃO!</h4>
                </div>
                <div class="modal-body">
                    Deseja mesmo EXCLUIR o item?
                    <h4><span class="nome text-danger"></span></h4>
                </div>
                <div class="modal-footer">
                    <a href="#" type="button" class="btn btn-danger delete-yes">Confirmar</a>
                    <button class="btn btn-success" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').on('click', function() {
                var nome = $(this).data('nome');
                var id = $(this).data('id');
                $('span.nome').text(nome);
                $('.delete-yes').attr('data-id', id);
                $('#myModal').modal('show');
            });

            $('.delete-yes').on('click', function() {
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        url: 'usuarios_exclui.php',
                        type: 'POST',
                        data: { id_usuario: id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Ocorreu um erro ao excluir o usuário: ' + error);
                        }
                    });
                    $('#myModal').modal('hide');
                } else {
                    alert('ID do usuário não encontrado.');
                }
            });
        });
    </script>
</body>
</html>