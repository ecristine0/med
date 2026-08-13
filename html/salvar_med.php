<?php
require_once "conexao.php";
require_once "funcoes.php";

    $nome = $_POST['medicamento_nome'];
    $laboratorio = $_POST['medicamento_laboratorio'];
    $categoria = $_POST['medicamento_categoria'];
    $observacao = $_POST['medicamento_observacao'];
    $arquivoImagem = $_FILES['medicamento_foto'];

header('Location: index.php');
exit();

?>