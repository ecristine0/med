<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Criar sua conta</h2>
    <h4>Escolha o tipo de conta e preencha seus dados</h4>
    
        <form action="">
        Tipo de Usuario: <br> 
        <input type="radio" name="Pessoa Comum" value="comum">
        <label for="comum">Pessoa comum</label>
        <input type="radio" name="Cuidador" value="cuidador">
        <label for="cuidador">Cuidador</label>
        
<br> <br> 

    Nome Completo: <br>
    <input type="text" name= "nome" value="Nome completo">
    <br> <br>
    Email: <br>
    <input type="text" name= "email" value="Email">
    <br> <br>
    Senha:<br>
    <input type="text" name= "senha" value="Senha">
    <br> <br>

    <input type="submit" name= "criar" value="Criar agora">
    </form>

    <p>Já tem uma conta?</p>
    <a href="login.php">Fazer Login</a>
</body>
</html>
