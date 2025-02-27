<?php
include "acesso_com.php";
include "../conn/connect.php";

$lista = $pdo->query("SELECT * FROM tipos ORDER BY rotulo");

if ($lista) {
    $row = $lista->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <title>Chuleta Quente - Tipos</title>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/bootstrap.min.css">
            <link rel="stylesheet" href="../css/estilo.css" type="text/css">
        </head>
        <body>
        <?php include "menu_adm.php"; ?>
        <main class="container">
            <h1 class="breadcrumb alert-warning">Lista de Tipos</h1>
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
                <table class="table table-hover table-condensed tbopacidade">
                    <thead>
                    <tr>
                        <th class="hidden">ID</th>
                        <th>TIPOS</th>
                        <th>SIGLAS</th>
                        <th>
                            <a href="tipos_insere.php" target="_self" class="btn btn-block btn-primary btn-xs" role="button">
                                <span class="hidden-xs">ADICIONAR <br></span>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php do { ?>
                        <tr>
                            <td class="hidden"><?php echo $row['id']; ?></td>
                            <td><?php echo $row['rotulo']; ?></td>
                            <td><?php echo $row['sigla']; ?></td>
                            <td class="text-center">
                                <a href="tipos_atualiza.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-xs">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    <span class="hidden-xs">ALTERAR</span>
                                </a>
                                <button data-nome="<?php echo $row['sigla']; ?>" data-id="<?php echo $row['id']; ?>" class="delete btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-trash"></span>
                                    <span class="hidden-xs">EXCLUIR</span>
                                </button>
                            </td>
                        </tr>
                    <?php } while ($row = $lista->fetch(PDO::FETCH_ASSOC)); ?>
                    </tbody>
                </table>
            </div>
        </main>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
            $('.delete').on('click', function () {
                var nome = $(this).data('nome');
                var id = $(this).data('id');
                $('span.nome').text(nome);
                $('a.delete-yes').attr('href', 'tipos_exclui.php?id=' + id); //corrigido aqui
                $('#myModal').modal('show');
            });
        </script>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Nenhum resultado encontrado.</p>";
    }
} else {
    echo "<p>Erro na consulta ao banco de dados.</p>";
}
?>