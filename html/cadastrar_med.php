<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once "conexao.php";
    require_once "funcoes.php";

    if (isset($_POST['enviar'])) {
        // Obter e remover espaços em branco das extremidades
        $nome = $_POST['nome'] ?? '';
        $laboratorio = $_POST['laboratorio'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $observacao = $_POST['observacao'] ?? '';
        $arquivoImagem = $_FILES['foto'] ?? null;


        $sucesso = inserirmedicamento($nome, $laboratorio, $categoria, $observacao, $arquivoImagem);

        if ($sucesso) {
            echo "Medicamneto inserido com sucesso";
        } else {
            echo "Erro no cadastro do Medicamento. Verifique a imagem ou a conexão.";
        }
        
    }
?>

 <form action="salvar_med.php" method="post"  enctype= "multipart/form-data">
        <h2>Adicione um medicamento</h2>
        Nome: <br>
        <input type="text" name="nome" required placeholder="Nome"> <br><br>

        Laboratorio: <br>
        <input type="text" name="laboratorio" required placeholder="Laboratorio"> <br><br>

        Categoria: <br>
        <input type="text" name="categoria" required placeholder="Categoria"> <br><br>

        Observação: <br>
        <input type="text" name="observacao" required placeholder="Observação"> <br><br>

        Foto:
        <input type="file" name="foto" required placeholder= "Foto">
        <br> <br>
        <input type="submit" name="enviar" required placeholder="Salvar medicamento"> <br><br>

</body>
</html>