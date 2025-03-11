<?php
include 'acesso_com.php';
include '../conn/connect.php';
$lista = $pdo->query("select * from vw_produtos");
$row = $lista->fetch(PDO::FETCH_ASSOC);
$rows= $lista->rowCount();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Lista</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body class="">
    <?php include 'menu_adm.php'; ?>
    <main class="container">
        <h2 class="breadcrumb alert-danger">Lista de Produtos</h2>
        <table class="table table-hover table-condensed tb-opacidade bg-warning">
            <thead>
                <th class="hidden">ID</th>
                <th>TIPO</th>
                <th>DESCRIÇÃO</th>
                <th>RESUMO</th>
                <th>VALOR</th>
                <th>IMAGEM</th>
                <th>DESTAQUE</th>
                    <a href="produtos_insere.php?id=" target="_self" class="btn btn-block btn-primary btn-xs" role="button">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        <span class="hidden-xs">ADICIONAR</span>
                    </a>
                </th>
            </thead>
           
            <tbody> <!-- início corpo da tabela -->
                    <!-- início estrutura repetição -->
                    <?php
                    //iniciar loop para iterar registros de produtos
                    do{
                    ?>
               
                    <tr>
                        <td class="hidden">
                          <?php
                          //exibe o id do produto
                          echo $row ['id'];
                          ?>
                        </td>
                        <td>
                        <?php
                        //exibe rótulo do produto
                        echo $row['rotulo'];
                        ?>
                            <span class="visible-xs"></span>
                            <span class="hidden-xs"></span>
                        </td>
                        <td>
                            <?php
                            //verifica se o produto está em destaque
                            if($row['destaque']=='Sim'){
                                //exibe icone com estilo danger
                                echo '<span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span>';
                            }
                            //caso contrario, exibe o icone de ok - success
                            else{
                                '<span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span>';
 
                            }
                            //adicionar espaço
                            echo '&nbsp;';
                            //exibir descriçao do produto
                            echo $row['descricao'];
                            ?>
 
                        </td>
                        <td>
                          <?php
                          //exibir resumo do produto
                          echo $row['resumo'];
 
                          ?>
                        </td>
                        <td>
                          <?php
                          //exibir valor do produto -
                          echo number_format($row['valor'],2,',','.');
                          ?>
                        </td>
                        <td>
                           <img src="../images/<?php echo $row['imagem']; ?>" width="100px"
                   
 
                            ?>
                        </td>
                        <td>
                            <a
                                href="produtos_atualiza.php?id=<?php
                                //passa o id do produto para a página de atualização
                                echo $row['id'];?>"
                                role="button"
                                class="btn btn-warning btn-block btn-xs"
                            >
                                <span class="glyphicon glyphicon-refresh"></span>
                                <span class="hidden-xs">ALTERAR</span>    
                            </a>
                                <!-- não mostrar o botão excluir se o produto estiver em destaque -->
                                <?php
                                //executa uma query para verificar o status de destaque do produto atual
                                $regra = $pdo->query("select * from vw_produtos where id =" .$row['id']);
 
                                //obtem o resultado da query como array asscoc
 
                                ?>
 
                            <button
                                data-nome="<?php
                                //define o atributo data-nome com a descrição do produto
                                echo $row['descricao'];
                                ?>"
                                data-id=""<?php
                                //define o atributo data-id com ID do produto
                                echo $row['id'];
                                ?>
                                class="delete btn btn-xs btn-block btn-danger
                                <?php
                                echo $regraRow ['destaques'] == 'Sim' ? 'hidden' : '';?>
                                ?>
                                "    
                            >
                                <span class="glyphicon glyphicon-trash"></span>
                                <span class="hidden-xs">EXCLUIR</span>
 
                            </button>
                           
                        </td>
                    </tr>    
                 <?php
                 //continua o loop enquanto houver registros disponíveis
                } while ($row = $lista->fetch(PDO::FETCH_ASSOC)); ?>
 
            </tbody><!-- final corpo da tabela -->
        </table>
    </main>
    <!-- inicio do modal para excluir... -->
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Vamos deletar?</h4>
                    <button class="close" data-dismiss="modal" type="button">
                        &times;
 
                    </button>
                </div>
                <div class="modal-body">
                    Deseja mesmo excluir o item?
                    <h4><span class="nome text-danger"></span></h4>
                </div>
                <div class="modal-footer">
                    <a href="#" type="button" class="btn btn-danger delete-yes">
                        Confirmar
                    </a>
                    <button class="btn btn-success" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript">
    $('.delete').on('click',function(){
        var nome = $(this).data('nome'); //busca o nome com a descrição (data-nome)
        var id = $(this).data('id'); // busca o id (data-id)
        //console.log(id + ' - ' + nome); //exibe no console
        $('span.nome').text(nome); // insere o nome do item na confirmação
        $('a.delete-yes').attr('href','produtos_excluir.php?id='+id); //chama o arquivo php para excluir o produto
        $('#modalEdit').modal('show'); // chamar o modal
    });
</script>
 
<?php
 
?>
</html>