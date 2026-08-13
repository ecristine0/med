<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .lista {
    width: 80%;
    margin: 30px auto;
}

.medicamento {
    display: flex;
    align-items: center;
    gap: 20px;

    padding: 20px;
    margin-bottom: 15px;

    border: 2px solid #ffb3b3;
    border-radius: 18px;

    background-color: white;
}

.icone {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    background-color: #ffe6e6;
    border-radius: 15px;

    font-size: 25px;
}

.informacoes {
    flex: 1;
}

.informacoes h2 {
    margin: 0;
}

.informacoes p {
    margin: 5px 0;
    color: #888;
}

.medicamento a {
    padding: 10px 20px;

    background-color: #ff3040;
    color: white;

    text-decoration: none;

    border-radius: 10px;
}
</style>
<body>
    <?php

require_once "conexao.php";
require_once "funcoes.php";

if (isset($_GET['pesquisa']) && $_GET['pesquisa'] != "") {
    $resultado =  buscarmedicamentoid ($conexao, $_GET['pesquisa']);
} if {
    $resultado = listarmedicamento($conexao);
}


else {
    $resultado = buscarmedicamentopornome($conexao, $_GET['pesquisa']);
} else {
    $resultado = listarmedicamento($conexao);
}

?>

<div class="listamed">

    <h1>Medicamentos</h1>

    <form method="GET">

        <input
            type="text"
            name="pesquisa"
            placeholder="Pesquisar medicamento..."
        >

        <input
            type="submit"
            value="Pesquisar"
        >

    </form>

</div>


<div class="lista">

<?php

while ($medicamento = mysqli_fetch_assoc($resultado)) {

?>

    <div class="medicamento">

        <div class="icone">
            💊
        </div>

        <div class="informacoes">

            <h2>
                <?= $medicamento['medicamento_nome'] ?>
            </h2>

            <p>
                <?= $medicamento['medicamento_laboratorio'] ?>
            </p>

            <p>
                <?= $medicamento['medicamento_categoria'] ?>
            </p>

        </div>

        <a href="editarmedicamento.php?id=<?= $medicamento['medicamento_id'] ?>">
            Editar
        </a>

    </div>

<?php

}

?>

</div>
</body>
</html><?php
