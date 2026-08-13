<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
</head>

<body>
    <form action="salvar_cadastro.php" method="post">

    <h2>Cadastro</h2>

    <input type="text" name="pessoa_nome" required placeholder="Nome"> <br><br>

    <input type="email" name="pessoa_email" required placeholder="email@exemplo.com"> <br><br>

    <input type="password" name="pessoa_senha" required placeholder="Senha"> <br><br>

    <input type="text" name="pessoa_tipo" required placeholder="Tipo (ex: aluno, professor)"> <br><br>

    <input type="date" name="pessoa_data_nascimento" required> <br><br>

    <input type="number" step="0.1" name="pessoa_peso" required placeholder="Peso (kg)"> <br><br>

    <input type="number" step="0.01" name="pessoa_altura" required placeholder="Altura (m)"> <br><br>

    <input type="submit" value="Cadastrar-se"> <br><br>

    <p>
        Já tem uma conta? <a href="login.php">Entre aqui!</a>
    </p>

</form>

</body>

</html>