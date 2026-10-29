<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cadastrar.php");
    exit;
}

require_once __DIR__ . "/conexao.php";


function mensagem($texto, $sucesso = false)
{
    $cor = $sucesso ? "green" : "red";

    echo '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro - MedTime</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main class="cadastro-page">
            <section class="cadastro-container">
                <h1>Cadastro - MedTime</h1>
                <p style="color:' . $cor . '">'
        . htmlspecialchars($texto, ENT_QUOTES, "UTF-8") .
        '</p>
                <p><a href="' .
        ($sucesso ? "login.php" : "cadastrar.php") .
        '">' .
        ($sucesso ? "Ir para o login" : "Voltar ao cadastro") .
        '</a></p>
            </section>
        </main>
    </body>
    </html>';

    exit;
}

$nome = trim($_POST["pessoa_nome"] ?? "");
$email = trim($_POST["pessoa_email"] ?? "");
$senha = $_POST["pessoa_senha"] ?? "";
$tipo = $_POST["pessoa_tipo"] ?? "";
$data = $_POST["pessoa_data_nascimento"] ?? "";
$peso = $_POST["pessoa_peso"] ?? "";
$altura = $_POST["pessoa_altura"] ?? "";

if (
    $nome === "" ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !in_array($tipo, ["cuidador", "paciente"], true) ||
    !is_string($senha) ||
    strlen($senha) < 8 ||
    !preg_match('/[A-Z]/', $senha) ||
    !preg_match('/[0-9]/', $senha) ||
    !preg_match('/[!@#$%^&*()\-+]/', $senha)
) {
    mensagem("Verifique os dados e os requisitos da senha.");
}

if (
    !is_string($data) ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)
) {
    mensagem("Informe uma data de nascimento válida.");
}

$dataValidada = DateTime::createFromFormat("!Y-m-d", $data);
$errosData = DateTime::getLastErrors();

if (
    !$dataValidada ||
    ($errosData !== false &&
        ($errosData["warning_count"] > 0 || $errosData["error_count"] > 0)) ||
    $dataValidada->format("Y-m-d") !== $data ||
    $data > date("Y-m-d")
) {
    mensagem("A data de nascimento informada é inválida.");
}

if (
    !is_numeric($peso) ||
    !is_numeric($altura) ||
    (float)$peso <= 0 ||
    (float)$altura <= 0
) {
    mensagem("Informe um peso e uma altura válidos.");
}

$peso = (float)$peso;
$altura = (float)$altura;

if (!isset($conexao) || !($conexao instanceof mysqli)) {
    mensagem("Não foi possível conectar ao banco. Confira o arquivo de conexão.");
}

$conexao->set_charset("utf8mb4");

$sql = "SELECT pessoa_id FROM pessoa WHERE pessoa_email = ? LIMIT 1";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    mensagem("Erro ao consultar o banco de dados.");
}

$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();
    mensagem("Este e-mail já está cadastrado. Tente entrar na sua conta.");
}

$stmt->close();

$senhaHash = $senha;

$sql = "INSERT INTO pessoa
    (pessoa_nome, pessoa_email, pessoa_senha, pessoa_tipo,
     pessoa_data_nascimento, pessoa_peso, pessoa_altura)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    mensagem("Não foi possível preparar o cadastro.");
}

$stmt->bind_param(
    "sssssdd",
    $nome,
    $email,
    $senhaHash,
    $tipo,
    $data,
    $peso,
    $altura
);


if ($stmt->execute()) {
    $id = $conexao->insert_id;
    $stmt->close();

    session_regenerate_id(true);

    $_SESSION["id"] = $id;
    $_SESSION["pessoa"] = $nome;
    $_SESSION["email"] = $email;
    $_SESSION["tipo"] = $tipo;

    header("Location: index.php");
    exit;
}

if ($stmt->errno === 1062) {
    $stmt->close();
    mensagem("Este e-mail já está cadastrado.");
}

$stmt->close();
mensagem("Não foi possível concluir o cadastro. Verifique o banco de dados.");
