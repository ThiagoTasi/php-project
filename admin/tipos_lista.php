<?php
include "acesso_com.php";
include "../conn/connect.php";

try {
    $lista = $pdo->query("SELECT * FROM tipos ORDER BY rotulo");
    $numRows = $lista->rowCount();
} catch (PDOException $e) {
    die("Erro na consulta ao banco de dados: " . $e->getMessage());
}

if ($numRows > 0) {
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
                <?php while ($row = $lista->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td class="hidden"><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['rotulo']); ?></td>
                        <td><?php echo htmlspecialchars($row['sigla']); ?></td>
                        <td class="text-center">
                            <a href="tipos_atualiza.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-xs">
                                <span class="glyphicon glyphicon-refresh"></span>
                                <span class="hidden-xs">ALTERAR</span>
                            </a>
                            <button data-nome="<?php echo htmlspecialchars($row['sigla']); ?>" data-id="<?php echo htmlspecialchars($row['id']); ?>" class="delete btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-trash"></span>
                                <span class="hidden-xs">EXCLUIR</span>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').on('click', function() {
                var nome = $(this).data('nome');
                var id = $(this).data('id');
                $('span.nome').text(nome);
                $('a.delete-yes').off('click').on('click', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'tipos_exclui.php',
                        type: 'POST', // Usando POST para maior segurança
                        data: { id: id },
                        success: function(response) {
                            console.log('Resposta:', response);
                            if (response === "success") {
                                $('#myModal').modal('hide');
                                location.reload();
                            } else {
                                alert('Erro: ' + response);
                            }
                        },
                        error: function(xhr) {
                            alert('Erro ao excluir: ' + xhr.statusText);
                        }
                    });
                });
                $('#myModal').modal('show');
            });
        });
    </script>
    </body>
    </html>
    <?php
} else {
    echo "<p>Nenhum resultado encontrado.</p>";
}
?>