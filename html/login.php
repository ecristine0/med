<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php 
    require_once "funcoes.php";

    if (isset($_POST['enviar'])){
        $email = $_POST['cpf'] ?? '';
        $senha = $_POST['senha'] ??'';

        $sucesso = login($conexao, $email, $senha);
        
        if($sucesso){
            header("Location:index.php");
            exit;
        } else {
            echo"Erro no login.";
        }
    
    
    }
    
    
    ?>
<div>
    <form action="confirma_login.php" method="POST">

        <h2>Login</h2>

        <input type="email" required placeholder="email@exemplo.com" name="pessoa_email"> <br><br>

        <input type="password" required placeholder="Senha" name="pessoa_senha"> <br><br>

        <input type="submit" value="Entrar"> <br><br>

        <p>
            Não tem uma conta? 
            <a href="cadastrar.php">Cadastre-se</a>
        </p>


    </form>
</div>

</body>
</html>