<?php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Enviando E-mail</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <style>
        body {
    font-size: 12px;
    font-family: Verdana, Geneva, sans-serif;
}
 
#contato_form {
    width: 500px;
    min-height: 175px;
    color: #999;
    margin: auto;
}
 
.asteristico {
    color: #F00;
}
 
.campo_input, .campo_submit {
    font-size: 12px;
}
 
table {
    border-collapse: collapse;
    width: 100%;
}
 
td {
    padding: 5px;
}
    </style>
</head>
<body>
    <div id="contato_form">
        <form action="enviar.php" name="form_contato" method="post">
            <p class="titulo">Formulário <small class="asteristico">*Campos Obrigatórios</small></p>
            <table align="center">
                <tr>
                    <td>Nome:<sup class="asteristico">*</sup></td>
                    <td><input type="text" name="nome" maxlength="40" required /></td>
                </tr>
                <tr>
                    <td>E-mail:<sup class="asteristico">*</sup></td>
                    <td><input type="email" name="email" maxlength="30" required /></td>
                </tr>
                <tr>
                    <td>Telefone:<sup class="asteristico">*</sup></td>
                    <td><input type="text" name="telefone" maxlength="14" required /></td>
                </tr>
                <tr>
                    <td>Opções:<sup class="asteristico">*</sup></td>
                    <td>
                        <select name="escolhas" class="campo_input" required>
                            <option value="Opção 1">Opção 1</option>
                            <option value="Opção 2">Opção 2</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Mensagem:<sup class="asteristico">*</sup></td>
                    <td><textarea name="msg" cols="16" rows="5" required></textarea></td>
                </tr>
                <tr align="right">
                    <td colspan="2">
                        <input type="reset" class="campo_submit" value="Limpar" />
                        <input type="submit" action="enviar.php" class="campo_submit" value="Enviar" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><small class="asteristico">* Campos obrigatórios</small></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
 