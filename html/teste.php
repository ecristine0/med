<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de medicamentos</title>
</head>

<body>

<?php

require_once "conexao.php";
require_once "funcoes.php";


/*
|--------------------------------------------------------------------------
| MEDICAMENTOS
|--------------------------------------------------------------------------
*/

echo "<h1>Listar medicamentos</h1>";

$medicamentos = listarmedicamento($conexao);

while ($med = $medicamentos->fetch_assoc()) {
    print_r($med);
    echo "<br>";
}


// Inserir medicamento
echo "<h2>1. Inserir medicamento</h2>";

// inserirmedicamento(
//     $conexao,
//     'Dipirona',
//     'dzgfg.png',
//     'Laboratório',
//     'Analgésico',
//     'Usar conforme orientação'
// );

echo "Medicamento inserido <br>";


// Buscar medicamento por ID
echo "<h2>3. Buscar medicamento por ID</h2>";

$medicamento = buscarmedicamentoid($conexao, 3);

while ($med = $medicamento->fetch_assoc()) {
    print_r($med);
    echo "<br>";
}


// Buscar medicamento por nome
echo "<h2>4. Buscar medicamento por nome</h2>";

$medicamento = buscarmedicamentopornome(
    $conexao,
    "Amoxicilina"
);

while ($med = $medicamento->fetch_assoc()) {
    print_r($med);
    echo "<br>";
}


// Atualizar medicamento
echo "<h2>5. Atualizar medicamento</h2>";

/*
atualizarmedicamento(
    $conexao,
    2,
    "Ibuprofeno",
    "ibuprofeno.png",
    "Laboratório",
    "Analgésico",
    "Usar conforme orientação"
);
*/


// Deletar medicamento
echo "<h2>6. Deletar medicamento</h2>";

// deletarmedicamento($conexao, 4);

echo "Medicamento deletado";


/*
|--------------------------------------------------------------------------
| IDOSOS
|--------------------------------------------------------------------------
*/

echo "<h1>Listar idosos</h1>";

$idosos = listaridoso($conexao);

while ($idoso = $idosos->fetch_assoc()) {
    print_r($idoso);
    echo "<br>";
}


// Inserir idoso
echo "<h2>1. Inserir idoso</h2>";

cadastraridoso(
    $conexao,
    'jose',
    'jose@gmail.com',
    '12345',
    'idoso',
    '1967-09-06',
    59,
    1.78,
    'dasfd.png'
);

echo "Idoso inserido <br>";


// Buscar idoso por ID
echo "<h2>3. Buscar idoso por ID</h2>";

$idoso = buscaridoso($conexao, 3);

while ($idoso_dados = $idoso->fetch_assoc()) {
    print_r($idoso_dados);
    echo "<br>";
}


// Buscar idoso por nome
echo "<h2>4. Buscar idoso por nome</h2>";

$idoso = buscaridosopornome(
    $conexao,
    "jose"
);

while ($idoso_dados = $idoso->fetch_assoc()) {
    print_r($idoso_dados);
    echo "<br>";
}


// Atualizar idoso
echo "<h2>5. Atualizar idoso</h2>";

/*
atualizaridoso(
    $conexao,
    2,
    "José",
    "jose@gmail.com",
    "12345",
    "idoso",
    "1967-09-06",
    59,
    1.78,
    "dasfd.png"
);
*/


// Deletar idoso
echo "<h2>6. Deletar idoso</h2>";

// deletaridoso($conexao, 9);

echo "Idoso deletado";

?>

</body>
</html>