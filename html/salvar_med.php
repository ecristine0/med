<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastrar_med.php');
    exit();
}

require_once "conexao.php";
require_once "funcoes.php";

$nome = trim($_POST['medicamento_nome'] ?? '');
$laboratorio = trim($_POST['medicamento_laboratorio'] ?? '');
$categoria = trim($_POST['medicamento_categoria'] ?? '');
$observacao = trim($_POST['medicamento_observacao'] ?? '');
$arquivoImagem = $_FILES['medicamento_foto'] ?? null;

if (
    $nome === '' ||
    $laboratorio === '' ||
    $categoria === '' ||
    $observacao === '' ||
    !$arquivoImagem ||
    $arquivoImagem['error'] !== UPLOAD_ERR_OK
) {
    header('Location: cadastrar_med.php?status=erro');
    exit();
}

$sucesso = inserirmedicamento(
    $conexao,
    $nome,
    $laboratorio,
    $categoria,
    $observacao,
    $arquivoImagem
);

if ($sucesso) {
    header('Location: cadastrar_med.php?status=sucesso');
} else {
    header('Location: cadastrar_med.php?status=erro');
}

exit();
?>
