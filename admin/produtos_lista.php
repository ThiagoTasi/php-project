<?php 
//controle de acesso
include "acesso_com.php";
//conexão com BD
include "../conn/connect.php";
$lista = $pdo->query("select * from vw_produtos");
$row = $lista->fetch(PDO::FETCH_ASSOC); // primeiro registro que ele buscar no array
$numrow = $lista->rowCount();// quantidade que terei no registro (qunatos ela retornou)
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
                <th>
                    <a href="produtos_insere.php" target="_self" class="btn btn-block btn-primary btn-xs" role="button">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        <span class="hidden-xs">ADICIONAR</span>
                    </a>
                </th>
            </thead>
            
            <tbody> <!-- início corpo da tabela -->
           	        <!-- início estrutura repetição -->
                     <?php 
                     //Iniciar loop para interar registro de produtos
                     do{
                     ?>
               
                    <tr>
                        <td class="hidden">
                            <?php 
                            echo $row['id'];
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
                                echo '<span class="glyphicon glyphicon-star-tets-danger"
                                aria-hidden="true"></span>';
                            }
                            else//caso contrário, exibe o icone de ok - sucess
                            {
                                '<span class=glyphicon glyphicon-ok text-sucess" aria-hidden="true"></span>';
                            }
                            //adicionar espaço
                            echo '&nbsp';
                            //exibir descrição do produto
                            echo $row['descricao'];
                            ?>
                            
                        </td>
                        <td>
                            <?php 
                            //exibir resumo
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
                            <img src="../images/<?php
                            //Exibe o nome da imagem do produto para formar o caminho da imagem
                            echo $row['imagem'];?>" width="100px">
                           
                        </td>
                        <td>
                            <a
                                href="produtos_atualiza.php?id=<?php
                                //passa o ID do produto
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
                                 $regra =$pdo->query("select destaque from vw_produtos where id=".$row['id']);
                                 //obtém o resultado da query como atrray assoc
                                 $regraRow = $regra->fetch(PDO::FETCH_ASSOC);
                                 ?>
                                

                            <button 
                                data-nome="<?php
                                //define o atributo data-nome com a descrição do produto
                                echo $row['descricao'];?>"
                                data-id="<?php
                                //Define o atributo data-id com o ID do produto
                                echo $row['id'];?>"
                                class="delete btn btn-xs btn-block btn-danger
                                <?php
                                //Adiciona a classe 'hidden' se o produto estiver em destaque para ocultar o botão de exclusão
                                echo $regraRow['destaque']=='Sim'?
                                 'hidden':'';?>                                
                                "     
                            >
                                <span class="glyphicon glyphicon-trash"></span>
                                <span class="hidden-xs">EXCLUIR</span>

                            </button>
                        </td>
                    </tr> 
                    <?php 
                    // continua o loop enquanto houver registros disponiveis
                        } while($row =$lista->fetch(PDO::FETCH_ASSOC));?>    
                
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