<?php
session_start();

require_once "conexao.php";
require_once "funcoes.php";

$email = $_POST['email'];
$senha = $_POST['senha'];

$usuario = Login($conexao, $email, $senha);

if(!$usuario){

    header("Location: index.php");
    exit();

}

$_SESSION['usuario_id'] = $usuario['usuario_id'];
$_SESSION['usuario_nome'] = $usuario['usuario_nome'];
$_SESSION['usuario_email'] = $usuario['usuario_email'];
$_SESSION['usuario_tipo'] = $usuario['usuario_tipo'];

if($usuario['usuario_tipo'] == 'a'){

    header("Location: home_aluno.php");

}elseif($usuario['usuario_tipo'] == 'm'){

    header("Location: home_motorista.php");

}else{

    header("Location: index.php");

}
exit();

//fazer o verifica logado dps!!!