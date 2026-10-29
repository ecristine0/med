<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "funcoes.php";
$erro = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    if ($email === "" || $senha === "") {
        $erro = "Preencha o e-mail e a senha.";
    } else {
        $resultado = login($conexao, $email, $senha);
        if ($resultado === true) {
            header("Location: index.php");
            exit;
        } elseif ($resultado === false) {
            $erro = "E-mail ou senha incorretos.";
        } else {
            $erro = "Ocorreu um erro ao realizar o login.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main class="login-page">
        <section class="login-container">

            <div class="login-header">
                <h1>Entrar</h1>
                <p>Acesse sua conta MedTime</p>
            </div>

            <?php if ($erro !== ""): ?>
                <div class="login-error">
                    <?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite sua senha"
                        required>
                </div>

                <button type="submit" class="login-button">
                    Entrar
                </button>

            </form>

            <div class="login-footer">
                <p>
                    Não possui uma conta?
                    <a href="cadastrar.php">Cadastre-se</a>
                </p>
                <a href="index.php" class="back-link">
                    Voltar para o início
                </a>
            </div>
        </section>
    </main>
</body>

</html>